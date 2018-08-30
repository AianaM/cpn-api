<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180830090852 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE stream DROP CONSTRAINT fk_f0e9be1ce104c1d3');
        $this->addSql('DROP INDEX idx_f0e9be1ce104c1d3');
        $this->addSql('ALTER TABLE stream RENAME COLUMN created_user_id TO createdUser');
        $this->addSql('ALTER TABLE stream ADD CONSTRAINT FK_F0E9BE1C7AF16D89 FOREIGN KEY (createdUser) REFERENCES app_users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_F0E9BE1C7AF16D89 ON stream (createdUser)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE stream DROP CONSTRAINT FK_F0E9BE1C7AF16D89');
        $this->addSql('DROP INDEX IDX_F0E9BE1C7AF16D89');
        $this->addSql('ALTER TABLE stream RENAME COLUMN createduser TO created_user_id');
        $this->addSql('ALTER TABLE stream ADD CONSTRAINT fk_f0e9be1ce104c1d3 FOREIGN KEY (created_user_id) REFERENCES app_users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_f0e9be1ce104c1d3 ON stream (created_user_id)');
    }
}
