<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181007051015 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE address_media_object (address_id INT NOT NULL, media_object_id INT NOT NULL, PRIMARY KEY(address_id, media_object_id))');
        $this->addSql('CREATE INDEX IDX_1875E529F5B7AF75 ON address_media_object (address_id)');
        $this->addSql('CREATE INDEX IDX_1875E52964DE5A5 ON address_media_object (media_object_id)');
        $this->addSql('ALTER TABLE address_media_object ADD CONSTRAINT FK_1875E529F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE address_media_object ADD CONSTRAINT FK_1875E52964DE5A5 FOREIGN KEY (media_object_id) REFERENCES media_object (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE address_media_object');
    }
}
