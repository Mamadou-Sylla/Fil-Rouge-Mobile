<?php

namespace App\DataFixtures;

use Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use App\Entity\Profil;

class ProfilFixtures extends Fixture
{
    public const Profil_AdminSysteme = 'SUPER_ADMIN';
    public const Profil_AdminAgence = 'ADMIN';
    public const Profil_Caissier = 'CAISSIER';




    public function load(ObjectManager $manager)
    {
        $faker= Factory::create('fr_FR');

        $profil_admin_systeme = new Profil();
        $profil_admin_systeme
            ->setLibelle("SUPER_ADMIN")
            ;
        $this->addReference(self::Profil_AdminSysteme, $profil_admin_systeme);
        $manager->persist($profil_admin_systeme);

        $profil_admin_agence = new Profil();
        $profil_admin_agence
            ->setLibelle("ADMIN")
            ;
        $this->addReference(self::Profil_AdminAgence, $profil_admin_agence);
        $manager->persist($profil_admin_agence);

        $profil_caissier = new Profil();
        $profil_caissier
            ->setLibelle("CAISSIER")
            ;
        $this->addReference(self::Profil_Caissier, $profil_caissier);
        $manager->persist($profil_caissier);

        $manager->flush();


    }

}