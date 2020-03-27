<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200310090606 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE produits ADD img VARCHAR(255) NOT NULL, CHANGE fournisseur_id fournisseur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE descriptions CHANGE produits_id produits_id INT DEFAULT NULL, CHANGE contenu contenu VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE images CHANGE produit_id produit_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE descriptions CHANGE produits_id produits_id INT DEFAULT NULL, CHANGE contenu contenu LONGTEXT CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`');
        $this->addSql('ALTER TABLE images CHANGE produit_id produit_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE produits DROP img, CHANGE fournisseur_id fournisseur_id INT DEFAULT NULL');
    }
}
