<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200309093903 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE fournisseur (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE produits ADD fournisseur_id INT DEFAULT NULL, DROP fournisseur');
        $this->addSql('ALTER TABLE produits ADD CONSTRAINT FK_BE2DDF8C670C757F FOREIGN KEY (fournisseur_id) REFERENCES fournisseur (id)');
        $this->addSql('CREATE INDEX IDX_BE2DDF8C670C757F ON produits (fournisseur_id)');
        $this->addSql('ALTER TABLE descriptions CHANGE produits_id produits_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE images CHANGE produit_id produit_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE produits DROP FOREIGN KEY FK_BE2DDF8C670C757F');
        $this->addSql('DROP TABLE fournisseur');
        $this->addSql('ALTER TABLE descriptions CHANGE produits_id produits_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE images CHANGE produit_id produit_id INT DEFAULT NULL');
        $this->addSql('DROP INDEX IDX_BE2DDF8C670C757F ON produits');
        $this->addSql('ALTER TABLE produits ADD fournisseur VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP fournisseur_id');
    }
}
