<?php
namespace App\DataPersister;

use App\Entity\Transaction;
use App\Service\TransactionService;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TransactionRepository;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;

class TransactionDataPersister implements ContextAwareDataPersisterInterface
{
   /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    private $transactionService;
    private $repo;


    public function __construct(EntityManagerInterface $em, TransactionService $transactionService, TransactionRepository $repo){
        $this->entityManager=$em;
        $this->transactionService=$transactionService;
        $this->repo=$repo;

    }
    public function supports($data, array $context = []): bool
    {
        return $data instanceof Transaction;
    }

    public function persist($data, array $context = [])
    {      
        if ($data->getType()=='Depot') {
            # code...
            if ($data->getCompte()->getSolde()>= 5000 && $data->getCompte()->getSolde()>$data->getMontant()) {
                # code...
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
                $solde=($data->getCompte()->getSolde() + $parts['PartDepot']) - $data->getMontant();
                $data->getCompte()->setSolde($solde);
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
                        $solde=($data->getCompte()->getSolde() + $parts['PartDepot']) + $data->getMontant();
                        $data->setStatutTransaction(true);
                        $data->getCompte()->setSolde($solde);
                       //$this->entityManager->persist($data);
                       // $this->entityManager->flush();
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