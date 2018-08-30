<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180830140455 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE address_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE realty_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE address (id INT NOT NULL, street VARCHAR(255) NOT NULL, number VARCHAR(255) NOT NULL, district VARCHAR(255) NOT NULL, name VARCHAR(255) DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, developer VARCHAR(255) DEFAULT NULL, new_building BOOLEAN NOT NULL, year INT DEFAULT NULL, floors INT DEFAULT NULL, description JSON DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN address.description IS \'(DC2Type:json_array)\'');
        $this->addSql('CREATE TABLE realty (id INT NOT NULL, manager_id INT DEFAULT NULL, address_id INT NOT NULL, category VARCHAR(255) NOT NULL, area INT DEFAULT NULL, price INT DEFAULT NULL, description JSON DEFAULT NULL, status VARCHAR(255) NOT NULL, cadastral_number VARCHAR(255) DEFAULT NULL, fee INT DEFAULT NULL, exclusive BOOLEAN NOT NULL, hidden_info JSON DEFAULT NULL, rooms INT DEFAULT NULL, floor INT DEFAULT NULL, owner JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_627221C783E3463 ON realty (manager_id)');
        $this->addSql('CREATE INDEX IDX_627221CF5B7AF75 ON realty (address_id)');
        $this->addSql('COMMENT ON COLUMN realty.description IS \'(DC2Type:json_array)\'');
        $this->addSql('COMMENT ON COLUMN realty.hidden_info IS \'(DC2Type:json_array)\'');
        $this->addSql('COMMENT ON COLUMN realty.owner IS \'(DC2Type:json_array)\'');
        $this->addSql('CREATE TABLE realty_media_object (realty_id INT NOT NULL, media_object_id INT NOT NULL, PRIMARY KEY(realty_id, media_object_id))');
        $this->addSql('CREATE INDEX IDX_3681E3CE71C56C69 ON realty_media_object (realty_id)');
        $this->addSql('CREATE INDEX IDX_3681E3CE64DE5A5 ON realty_media_object (media_object_id)');
        $this->addSql('ALTER TABLE realty ADD CONSTRAINT FK_627221C783E3463 FOREIGN KEY (manager_id) REFERENCES app_users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE realty ADD CONSTRAINT FK_627221CF5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE realty_media_object ADD CONSTRAINT FK_3681E3CE71C56C69 FOREIGN KEY (realty_id) REFERENCES realty (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE realty_media_object ADD CONSTRAINT FK_3681E3CE64DE5A5 FOREIGN KEY (media_object_id) REFERENCES media_object (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE realty DROP CONSTRAINT FK_627221CF5B7AF75');
        $this->addSql('ALTER TABLE realty_media_object DROP CONSTRAINT FK_3681E3CE71C56C69');
        $this->addSql('DROP SEQUENCE address_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE realty_id_seq CASCADE');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE realty');
        $this->addSql('DROP TABLE realty_media_object');
    }
}
