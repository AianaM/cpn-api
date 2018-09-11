<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180911071305 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE realty ALTER area TYPE NUMERIC(5, 2)');
        $this->addSql('ALTER TABLE realty ALTER area DROP DEFAULT');
        $this->addSql('ALTER TABLE realty ALTER price TYPE NUMERIC(5, 2)');
        $this->addSql('ALTER TABLE realty ALTER price DROP DEFAULT');
        $this->addSql('ALTER TABLE realty ALTER fee TYPE NUMERIC(5, 2)');
        $this->addSql('ALTER TABLE realty ALTER fee DROP DEFAULT');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE realty ALTER area TYPE INT');
        $this->addSql('ALTER TABLE realty ALTER area DROP DEFAULT');
        $this->addSql('ALTER TABLE realty ALTER price TYPE INT');
        $this->addSql('ALTER TABLE realty ALTER price DROP DEFAULT');
        $this->addSql('ALTER TABLE realty ALTER fee TYPE INT');
        $this->addSql('ALTER TABLE realty ALTER fee DROP DEFAULT');
    }
}
