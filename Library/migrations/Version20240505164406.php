<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240505164406 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE language (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, acronym VARCHAR(10) NOT NULL)');
        $this->addSql('CREATE TABLE lectura (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, book_id INTEGER NOT NULL, status VARCHAR(255) NOT NULL, fecha_comienzo DATETIME DEFAULT NULL, fecha_final DATETIME DEFAULT NULL, CONSTRAINT FK_C60ABD5116A2B381 FOREIGN KEY (book_id) REFERENCES libro (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C60ABD5116A2B381 ON lectura (book_id)');
        $this->addSql('CREATE TABLE libro (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, lengua_id INTEGER NOT NULL, titulo VARCHAR(255) NOT NULL, editorial VARCHAR(255) DEFAULT NULL, autor VARCHAR(255) NOT NULL, portada BLOB NOT NULL, fecha_publicacion DATETIME NOT NULL, CONSTRAINT FK_5799AD2B21852360 FOREIGN KEY (lengua_id) REFERENCES language (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_5799AD2B21852360 ON libro (lengua_id)');
        $this->addSql('CREATE TABLE messenger_messages (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, body CLOB NOT NULL, headers CLOB NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE language');
        $this->addSql('DROP TABLE lectura');
        $this->addSql('DROP TABLE libro');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
