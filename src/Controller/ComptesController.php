<?php

namespace App\Controller;

use App\Entity\Comptes;
use App\Service\CompteService;
use App\Repository\ComptesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ComptesController extends AbstractController
{
    private $serializer;
    private $validator;
    private $manager;
    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $manager, ComptesRepository $repo)
    {
        $this->serializer=$serializer;
        $this->validator=$validator;
        $this->manager=$manager;
        $this->repo=$repo;
    }
    
   /**
     * @Route(
     *   name="operation",
     *   path="api/admin/systemes/comptes/{id}",
     *   methods={"PATCH"}
     *     )
     * @Security("is_granted('ROLE_AdminSysteme') or is_granted('ROLE_Caissier')", message="Vous n'avez pas acces à ce ressource")
     */

  public function OperationDepot($id, Request $request): Response
    {
        $data = $request->getContent();
        $data = $this->serializer->decode($data, "json");
        $comptes = $this->repo->findOneBy(["id" => $id]);    
        $somme = $comptes->getSolde();
        if ($comptes->getNumeroCompte() == $data['numeroCompte']) {
            # code...
            if ($data['solde']>0) {
                # code...
                $result = [];
                //$comptes = new Comptes();
            $somme = $data['solde'] + $somme;
           // $result['solde'] = $somme;
           $comptes->setSolde($somme);
            $this->manager->persist($comptes);
            $this->manager->flush();
            return new JsonResponse('Depot effectuer avec succes', Response::HTTP_OK);
         }
            else
            {
                return new JsonResponse("Une erreur est survenue lors du depôt", Response::HTTP_BAD_REQUEST, [], true);
            }
        }
        else
            {
                return new JsonResponse("Le numéro de compte ne correspond pas", Response::HTTP_BAD_REQUEST, [], true);
            }
       
    }
}
