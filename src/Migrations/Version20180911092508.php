<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180911092508 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE realty ALTER rooms TYPE SMALLINT');
        $this->addSql('ALTER TABLE realty ALTER rooms DROP DEFAULT');
        $this->addSql('ALTER TABLE realty ALTER floor TYPE SMALLINT');
        $this->addSql('ALTER TABLE realty ALTER floor DROP DEFAULT');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE realty ALTER rooms TYPE INT');
        $this->addSql('ALTER TABLE realty ALTER rooms DROP DEFAULT');
        $this->addSql('ALTER TABLE realty ALTER floor TYPE INT');
        $this->addSql('ALTER TABLE realty ALTER floor DROP DEFAULT');
    }
}
