<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180228134954 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, auteur_id INT NOT NULL, titre VARCHAR(150) NOT NULL, contenu LONGTEXT NOT NULL, featured_image VARCHAR(150) NOT NULL, special TINYINT(1) NOT NULL, spotlight TINYINT(1) NOT NULL, date_creation DATETIME NOT NULL, INDEX IDX_23A0E6612469DE2 (category_id), INDEX IDX_23A0E6660BB6FE6 (auteur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE auteur (id INT AUTO_INCREMENT NOT NULL, prenom VARCHAR(50) NOT NULL, nom VARCHAR(50) NOT NULL, email VARCHAR(80) NOT NULL, password VARCHAR(64) NOT NULL, date_inscription DATETIME NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', derniere_connexion DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_55AB140E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E6612469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E6660BB6FE6 FOREIGN KEY (auteur_id) REFERENCES auteur (id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E6660BB6FE6');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E6612469DE2');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE auteur');
        $this->addSql('DROP TABLE category');
    }
}
