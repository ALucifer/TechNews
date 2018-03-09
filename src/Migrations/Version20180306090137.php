<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180306090137 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE token_auteur (id INT AUTO_INCREMENT NOT NULL, auteur_id INT NOT NULL, date_validite DATETIME NOT NULL, INDEX IDX_4221E4260BB6FE6 (auteur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE token_auteur ADD CONSTRAINT FK_4221E4260BB6FE6 FOREIGN KEY (auteur_id) REFERENCES auteur (id)');
        $this->addSql('ALTER TABLE auteur DROP token_password, DROP token_password_validite');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE token_auteur');
        $this->addSql('ALTER TABLE auteur ADD token_password VARCHAR(250) NOT NULL COLLATE utf8_unicode_ci, ADD token_password_validite DATETIME NOT NULL');
    }
}
