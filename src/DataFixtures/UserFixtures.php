<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Profil;
use App\Entity\Caissier;
use App\Entity\AdminAgence;
use App\Entity\AdminSysteme;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $encoder;
    public function  __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder=$encoder;
    }
    public function load(ObjectManager $manager)
    {
        $faker= Factory::create('fr_FR');

      
        for ($i=1; $i <=2 ; $i++) {
            # code...
            $user = new AdminSysteme();
            $hash = $this->encoder->encodePassword($user, 'password');
            $user->setUsername($faker->username)
                ->setPrenom($faker->firstName)
                ->setNom($faker->LastName)
                ->setPassword($hash)
                ->setCni('123456789'.$i)
                ->setTelephone($faker->phoneNumber)
                ->setEtat(false)
                ->setProfils($this->getReference(ProfilFixtures::Profil_AdminSysteme));
            $manager->persist($user);

            $user1 = new AdminAgence();
            $hash = $this->encoder->encodePassword($user1, 'password');
            $user1->setUsername($faker->username)
                ->setPrenom($faker->firstName)
                ->setNom($faker->LastName)
                ->setPassword($hash)
                ->setCni('223456789'.$i)
                ->setTelephone($faker->phoneNumber)
                ->setEtat(false)
                ->setProfils($this->getReference(ProfilFixtures::Profil_AdminAgence));
            $manager->persist($user1);

            $user2 = new Caissier();
            $hash = $this->encoder->encodePassword($user2, 'password');
            $user2->setUsername($faker->username)
                ->setPrenom($faker->firstName)
                ->setNom($faker->LastName)
                ->setPassword($hash)
                ->setCni('323456789'.$i)
                ->setTelephone($faker->phoneNumber)
                ->setEtat(false)
                ->setProfils($this->getReference(ProfilFixtures::Profil_Caissier));
                
            $manager->persist($user2);



            $manager->flush();
        }
    }
}
