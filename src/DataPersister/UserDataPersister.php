<?php

// src/DataPersister

namespace App\DataPersister;

use App\Entity\User;
use function Matrix\trace;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserDataPersister implements ContextAwareDataPersisterInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    private $passwordEncoder;


    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->entityManager=$em;
        $this->passwordEncoder = $passwordEncoder;

    }
    public function supports($data, array $context = []): bool
    {
        //L'opérateur instanceof permet de vérifier si tel objet est une instance de telle classe.
        return $data instanceof User;
    }

    public function persist($data, array $context = [])
    {
        if ($data->getPassword()) {
            $data->setPassword(
                $this->passwordEncoder->encodePassword($data, $data->getPassword())
            );

            $data->eraseCredentials();
        }
        $this->entityManager->persist($data);
        $this->entityManager->flush();
        return $data;
    }

    public function remove($data, array $context = [])
    {
        $etat=$data;
        $etat->setEtat(true);
        $this->entityManager->persist($etat);
        $this->entityManager->flush();
        return new Response("Comptes");
    }

}