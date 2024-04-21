<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240421180003 extends AbstractMigration
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
        $this->addSql('CREATE TABLE lectura (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, status VARCHAR(255) NOT NULL, fecha_comienzo DATETIME DEFAULT NULL, fecha_final DATETIME DEFAULT NULL)');
        $this->addSql('INSERT INTO lectura (id, status, fecha_comienzo, fecha_final) SELECT id, status, fecha_comienzo, fecha_final FROM __temp__lectura');
        $this->addSql('DROP TABLE __temp__lectura');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__lectura AS SELECT id, status, fecha_comienzo, fecha_final FROM lectura');
        $this->addSql('DROP TABLE lectura');
        $this->addSql('CREATE TABLE lectura (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, libro_id INTEGER NOT NULL, status VARCHAR(255) NOT NULL, fecha_comienzo DATETIME DEFAULT NULL, fecha_final DATETIME DEFAULT NULL, complete_name VARCHAR(255) NOT NULL, CONSTRAINT FK_C60ABD51C0238522 FOREIGN KEY (libro_id) REFERENCES libro (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO lectura (id, status, fecha_comienzo, fecha_final) SELECT id, status, fecha_comienzo, fecha_final FROM __temp__lectura');
        $this->addSql('DROP TABLE __temp__lectura');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C60ABD51C0238522 ON lectura (libro_id)');
    }
}
