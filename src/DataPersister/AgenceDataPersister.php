<?php

// src/DataPersister

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Agence;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use function Matrix\trace;

class AgenceDataPersister implements ContextAwareDataPersisterInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;


    public function __construct(EntityManagerInterface $em){
        $this->entityManager=$em;
    }
    public function supports($data, array $context = []): bool
    {
        //L'opérateur instanceof permet de vérifier si tel objet est une instance de telle classe.
        return $data instanceof Agence;
    }

    public function persist($data, array $context = [])
    {
        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }

    public function remove($data, array $context = [])
    {
        $etat=$data;
        $etat->setStatut(true);
        $this->entityManager->persist($etat);
        $users=$data->getUsers();
        foreach ($users as $user){
            $archived=$user->setEtat(true);
            $this->entityManager->persist($archived);
        }
        $this->entityManager->flush();
        return new Response("Archived");
    }

}