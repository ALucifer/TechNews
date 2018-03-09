<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180307095152 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE auteur ADD active_account_token_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE auteur ADD CONSTRAINT FK_55AB140C7C4267F FOREIGN KEY (active_account_token_id) REFERENCES token_auteur (id)');
        $this->addSql('CREATE INDEX IDX_55AB140C7C4267F ON auteur (active_account_token_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE auteur DROP FOREIGN KEY FK_55AB140C7C4267F');
        $this->addSql('DROP INDEX IDX_55AB140C7C4267F ON auteur');
        $this->addSql('ALTER TABLE auteur DROP active_account_token_id');
    }
}
