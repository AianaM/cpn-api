<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180830040517 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE stream_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE stream (id INT NOT NULL, created_user_id INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, action VARCHAR(30) NOT NULL, snapshot JSONB DEFAULT NULL, item VARCHAR(30) NOT NULL, item_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F0E9BE1CE104C1D3 ON stream (created_user_id)');
        $this->addSql('COMMENT ON COLUMN stream.snapshot IS \'(DC2Type:json_array)\'');
        $this->addSql('ALTER TABLE stream ADD CONSTRAINT FK_F0E9BE1CE104C1D3 FOREIGN KEY (created_user_id) REFERENCES app_users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE stream_id_seq CASCADE');
        $this->addSql('DROP TABLE stream');
    }
}
