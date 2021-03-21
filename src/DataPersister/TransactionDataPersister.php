<?php
namespace App\DataPersister;

use App\Entity\Transaction;
use App\Repository\AgenceRepository;
use App\Repository\ComptesRepository;
use App\Repository\UserAgenceRepository;
use App\Repository\UserRepository;
use App\Service\TransactionService;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TransactionRepository;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;


class TransactionDataPersister implements ContextAwareDataPersisterInterface
{
   /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    private $transactionService;
    private $repo;
    private $tokenStorage;
    private $serializer;
    private $repoUser;
    private $repoCompt;


    public function __construct(ComptesRepository $repoCompt, AgenceRepository $repoUser, EntityManagerInterface $em, TransactionService $transactionService, TransactionRepository $repo, TokenStorageInterface $tokenStorage, SerializerInterface $serializer){
        $this->entityManager=$em;
        $this->transactionService=$transactionService;
        $this->repo=$repo;
        $this->tokenStorage=$tokenStorage;
        $this->serializer=$serializer;
        $this->repoUser=$repoUser;
        $this->repoCompt=$repoCompt;
    }
    public function supports($data, array $context = []): bool
    {
        return $data instanceof Transaction;
    }

    public function persist($data, array $context = [])
    {

        if ($data->getType()=='Depot') {
            # code...
            $currentUser = $this->tokenStorage->getToken()->getUser();
            //dd($currentUser->getAgence());
            $id=$currentUser->getAgence()->getId();
            $ok = $this->repoUser->findOneBy(['id' => $id]);
            $idC=$ok->getCompte();
            $compte=$this->repoCompt->findOneBy(['id' => $idC]);
           // dd($compte->getSolde());
            if ($compte->getSolde()>= 5000 && $compte->getSolde()>$data->getMontant()) {
                # code...
                //dd($data);
                $code=($this->transactionService->generateCode());
                $data->setCodeTransfert(wordwrap($code, 3, "-",  true));
                $data->setDateDepot(new \DateTime);
                $frais=$this->transactionService->CalculFrais($data->getMontant());
                $data->setFrais($frais);
                $parts=$this->transactionService->CalculPart($frais);
                //dd($parts);
                $data->setPartEtat($parts['PartEtat']);
                $data->setPartTransfert($parts['PartTransfert']);
                $data->setPartDepot($parts['PartDepot']);
                $data->setPartRetrait($parts['PartRetrait']);
                $solde=($compte->getSolde() + $parts['PartDepot']) - $data->getMontant();
                $compte->setSolde($solde);

                $data->setUsers($currentUser);
                $data->setCompte($compte);

                //dd($data);
                $this->entityManager->persist($data);
                $this->entityManager->flush();
                 return new JsonResponse("Depot effectué avec succes", Response::HTTP_CREATED, [], true);

            }
            else{
                return new JsonResponse("Vous n'avez pas assez d'argent pour faire un transfert", Response::HTTP_FORBIDDEN, [], true);
            }
            }

        elseif ($data->getType()=='Retrait') {
            # code...
            $currentUser = $this->tokenStorage->getToken()->getUser();
            //dd($currentUser->getAgence());
            $id=$currentUser->getAgence()->getId();
            $ok = $this->repoUser->findOneBy(['id' => $id]);
            $idC=$ok->getCompte();
            $compte=$this->repoCompt->findOneBy(['id' => $idC]);
            // dd($compte->getSolde());
            $verify = new Transaction();
            $verify=$this->repo->findOneBy(["code_transfert" => $data->getCodeTransfert()]);
            
               if($verify && $verify->getStatutTransaction()==false){
                        # code...
                       // if ($data->getCompte()->getSolde()>$data->getMontant()) {
                            # code...getStatutTransaction
                       // }
                       $verify->setStatutTransaction(true);
                       $data->setDateDepot($verify->getDateDepot());
                       $data->setDateRetrait(new \DateTime);
                        $frais=$this->transactionService->CalculFrais($data->getMontant());
                        $data->setFrais($frais);
                        $parts=$this->transactionService->CalculPart($frais);
                        $data->setPartEtat($parts['PartEtat']);
                        $data->setPartTransfert($parts['PartTransfert']);
                        $data->setPartDepot($parts['PartDepot']);
                        $data->setPartRetrait($parts['PartRetrait']);
                        $solde=($compte->getSolde() + $parts['PartDepot']) + $data->getMontant();
                        $data->setStatutTransaction(true);
                        $compte->setSolde($solde);
                       $data->setUsers($currentUser);
                       $data->setCompte($compte);
                       //dd($data);
                       $this->entityManager->persist($data);
                       $this->entityManager->flush();
                        return new JsonResponse("Retrait effectuée avec succes", Response::HTTP_CREATED, [], true); 
                        
               // }
               // else{
               //     return new JsonResponse("Code de transaction ne correspond pas", Response::HTTP_NOT_FOUND, [], true);
              //  }
               }
               else {
                return new JsonResponse("Code de transaction n'existe pas ou  a été retiré", Response::HTTP_NOT_FOUND, [], true);
               }            
        }
        else {
            return new JsonResponse("Veuillez choisir un type de transaction", Response::HTTP_NOT_FOUND, [], true);
        }

        //$this->entityManager->persist($data);
        //$this->entityManager->flush();   
    }
  

    
public function remove($data, array $context = [])
    {
        $etat=$data;
        if ($etat->gettype()=="Depot") {
            # code...
            $etat->settype('Cancelled');
            $etat->setIsCancelled(true);
            $etat->setCodeTransfert('NULL');
            $this->entityManager->persist($etat);
            $this->entityManager->flush();
            return new JsonResponse("Transaction annulee avec succes", Response::HTTP_OK, [], true);
        } 
    }
}
