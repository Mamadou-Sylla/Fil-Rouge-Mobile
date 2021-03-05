<?php

// src/DataPersister

namespace App\DataPersister;

use App\Entity\Comptes;
use function Matrix\trace;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\DateTime;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;

class CompteDataPersister implements ContextAwareDataPersisterInterface
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
        return $data instanceof Comptes;
    }

    public function persist($data, array $context = [])
    {
        $data->setNumeroCompte($this->generateRandomString().$this->generateRandomNumber());
        $data->setDateCreation(new \DateTime);
        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }

    public function remove($data, array $context = [])
    {
        $etat=$data;
        $etat->setStatut(true);
        $this->entityManager->persist($etat);
        $this->entityManager->flush();
        return new Response("Comptes");
    }

   
    function generateRandomString($length = 3) {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(1, $charactersLength - 1)];
        }
        return $randomString;
    }

    function generateRandomNumber($length = 5) {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(1, $charactersLength - 1)];
        }
        return $randomString;
    }
}