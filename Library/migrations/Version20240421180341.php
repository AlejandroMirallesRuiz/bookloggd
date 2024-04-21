<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240421180341 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__lectura AS SELECT id, status, fecha_comienzo, fecha_final FROM lectura');
        $this->addSql('DROP TABLE lectura');
        $this->addSql('CREATE TABLE lectura (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, book_id INTEGER NOT NULL, status VARCHAR(255) NOT NULL, fecha_comienzo DATETIME DEFAULT NULL, fecha_final DATETIME DEFAULT NULL, CONSTRAINT FK_C60ABD5116A2B381 FOREIGN KEY (book_id) REFERENCES libro (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO lectura (id, status, fecha_comienzo, fecha_final) SELECT id, status, fecha_comienzo, fecha_final FROM __temp__lectura');
        $this->addSql('DROP TABLE __temp__lectura');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C60ABD5116A2B381 ON lectura (book_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__lectura AS SELECT id, status, fecha_comienzo, fecha_final FROM lectura');
        $this->addSql('DROP TABLE lectura');
        $this->addSql('CREATE TABLE lectura (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, status VARCHAR(255) NOT NULL, fecha_comienzo DATETIME DEFAULT NULL, fecha_final DATETIME DEFAULT NULL)');
        $this->addSql('INSERT INTO lectura (id, status, fecha_comienzo, fecha_final) SELECT id, status, fecha_comienzo, fecha_final FROM __temp__lectura');
        $this->addSql('DROP TABLE __temp__lectura');
    }
}
