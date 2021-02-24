<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210223140845 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comptes ADD users_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comptes ADD CONSTRAINT FK_5673580167B3B43D FOREIGN KEY (users_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_5673580167B3B43D ON comptes (users_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comptes DROP FOREIGN KEY FK_5673580167B3B43D');
        $this->addSql('DROP INDEX IDX_5673580167B3B43D ON comptes');
        $this->addSql('ALTER TABLE comptes DROP users_id');
    }
}
