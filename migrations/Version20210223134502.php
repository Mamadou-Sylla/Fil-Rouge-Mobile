<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210223134502 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE agence (id INT AUTO_INCREMENT NOT NULL, adresse VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, prenom VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, cni VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comptes (id INT AUTO_INCREMENT NOT NULL, numero_compte VARCHAR(255) NOT NULL, solde VARCHAR(255) NOT NULL, statut VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transaction (id INT AUTO_INCREMENT NOT NULL, date_depot DATE NOT NULL, date_retrait DATE NOT NULL, code_transfert VARCHAR(255) NOT NULL, frais INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE admin_agence CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE admin_agence ADD CONSTRAINT FK_3909AB50BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE admin_systeme CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE admin_systeme ADD CONSTRAINT FK_5145EF6ABF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE caissier CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE caissier ADD CONSTRAINT FK_1F038BC2BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE agence');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE comptes');
        $this->addSql('DROP TABLE transaction');
        $this->addSql('ALTER TABLE admin_agence DROP FOREIGN KEY FK_3909AB50BF396750');
        $this->addSql('ALTER TABLE admin_agence CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE admin_systeme DROP FOREIGN KEY FK_5145EF6ABF396750');
        $this->addSql('ALTER TABLE admin_systeme CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE caissier DROP FOREIGN KEY FK_1F038BC2BF396750');
        $this->addSql('ALTER TABLE caissier CHANGE id id INT AUTO_INCREMENT NOT NULL');
    }
}
