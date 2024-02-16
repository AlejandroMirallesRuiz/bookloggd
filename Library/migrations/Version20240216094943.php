<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240216094943 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE lectura (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, libro_id INTEGER NOT NULL, status VARCHAR(255) NOT NULL, fecha_comienzo DATETIME DEFAULT NULL, fecha_final DATETIME DEFAULT NULL, CONSTRAINT FK_C60ABD51C0238522 FOREIGN KEY (libro_id) REFERENCES libro (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C60ABD51C0238522 ON lectura (libro_id)');
        $this->addSql('CREATE TABLE libro (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, lengua_id INTEGER NOT NULL, titulo VARCHAR(255) NOT NULL, editorial VARCHAR(255) DEFAULT NULL, autor VARCHAR(255) NOT NULL, portada BLOB NOT NULL, fecha_publicacion DATETIME NOT NULL, CONSTRAINT FK_5799AD2B21852360 FOREIGN KEY (lengua_id) REFERENCES language (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_5799AD2B21852360 ON libro (lengua_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE lectura');
        $this->addSql('DROP TABLE libro');
    }
}
