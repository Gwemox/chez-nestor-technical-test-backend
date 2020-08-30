<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200830134014 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Create Room table';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE apartment_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE room_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE room (id UUID NOT NULL, apartment_id UUID NOT NULL, number INT NOT NULL, area DOUBLE PRECISION NOT NULL, price INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_729F519B176DFE85 ON room (apartment_id)');
        $this->addSql('ALTER TABLE room ADD CONSTRAINT FK_729F519B176DFE85 FOREIGN KEY (apartment_id) REFERENCES apartment (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE room_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE apartment_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('DROP TABLE room');
    }
}
