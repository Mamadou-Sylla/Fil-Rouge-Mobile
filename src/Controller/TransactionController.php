<?php

namespace App\Controller;

use App\Repository\TransactionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


class TransactionController extends AbstractController
{
    /**
     * @Route(
     *   name="transaction",
     *   path="api/transactions/mestransactions",
     *   methods={"GET"}
     *     )
     */
    public function GetTransaction(TokenStorageInterface $tokenStorage, TransactionRepository $repo, SerializerInterface $serializer): Response
    {
        $currentUser = $tokenStorage->getToken()->getUser();
        // dd($currentUser->getId());
        $id = $currentUser->getId();
        $transaction = $repo->findByField($id);
        $transaction=$serializer->serialize($transaction,"json");
        return new JsonResponse($transaction, Response::HTTP_OK,[],true);
    }



    /**
     * @Route(
     *   name="user",
     *   path="api/transactions/currentuser",
     *   methods={"GET"}
     *     )
     * @Security("is_granted('ROLE_AdminSysteme') or is_granted('ROLE_AdminAgence') or is_granted('ROLE_Caissier') or is_granted('ROLE_UserAgence')", message="Vous n'avez pas acces à ce ressource")
     */
    public function GetUserConnected(TokenStorageInterface $tokenStorage, TransactionRepository $repo, SerializerInterface $serializer): Response
    {
        $currentUser = $tokenStorage->getToken()->getUser();
        d($currentUser->getId());
//        $id = $currentUser->getId();
//        $transaction = $repo->findByField($id);
//        $transaction=$serializer->serialize($transaction,"json");
//        return new JsonResponse($transaction, Response::HTTP_OK,[],true);
    }
}
