<?php


namespace App\Service;

use App\Entity\User;
use App\Entity\Frais;
use App\Entity\Profil;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use App\Repository\FraisRepository;
use App\Repository\ProfilRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ApiPlatform\Core\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;



class TransactionService
{
    private $serializer;
    private $validator;
    private $encoder;
    private $manager;
    private $Repofees;
    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator, 
    UserPasswordEncoderInterface $encoder, EntityManagerInterface $manager,
    ProfilRepository $repoProfil, UserRepository $repoUser, FraisRepository $Repofees){
        $this->serializer=$serializer;
        $this->validator=$validator;
        $this->encoder=$encoder;
        $this->manager=$manager;
        $this->repoProfil=$repoProfil;
        $this->repoUser=$repoUser;
        $this->Repofees=$Repofees;
    }

 

    private $prix; 
    public function CalculFrais($solde){
        $frais = $this->Repofees->findAll();
         foreach ($frais as  $taxes ) {
            # code...
            //switch (true) {
                //case ($solde >= $taxes->getMin() && $solde < $taxes->getMax()):
                    if ($solde >= $taxes->getMin() && $solde <= $taxes->getMax()) {
                        # code...
                        $this->prix = $taxes->getFrais();
                        return $this->prix;
                    } 
                   
                  else if($solde > 2000000) {
                    $this->prix = $solde * 0.02;
                    return $this->prix;
               
            }
        }
       
    }

    public function CalculPart($frais){

        $parts = [];
        //         ​ 40 % pour l’état
        // ▪ ​ 30% pour le transfert d’argent
        // ▪ ​ 30% restant réparti comme suit :
        // ∙ ​ 10% pour l’opérateur qui a effectué le dépôt.
        // ∙ ​ 20% pour l’opérateur qui a effectué le retrait
        $parts['PartEtat'] = ($frais * 40) / 100;
        $parts['PartTransfert'] = ($frais * 30) / 100;
        $parts['PartDepot'] = ($frais * 10) / 100;
        $parts['PartRetrait'] = ($frais * 20) / 100;

        return $parts;

    }
    
    

    public function Operation(Request $request){

        $post = $request->getContent();
        $data = $this->serializer->decode($post, "json");
        $frais = $this->CalculFrais($data["montant"]);
        $partEtat = $this->CalculPart($frais);
       
    }

     function generateCode($length = 9) {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }


    
}