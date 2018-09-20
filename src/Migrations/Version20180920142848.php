<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180920142848 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE app_users_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE address_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE stream_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE media_object_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE realty_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE refresh_tokens_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE app_users (id INT NOT NULL, photo_id INT DEFAULT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(64) NOT NULL, roles TEXT NOT NULL, name JSONB DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C2502824E7927C74 ON app_users (email)');
        $this->addSql('CREATE INDEX IDX_C25028247E9E4C8C ON app_users (photo_id)');
        $this->addSql('COMMENT ON COLUMN app_users.roles IS \'(DC2Type:array)\'');
        $this->addSql('COMMENT ON COLUMN app_users.name IS \'(DC2Type:json_array)\'');
        $this->addSql('CREATE TABLE address (id INT NOT NULL, street VARCHAR(255) NOT NULL, number VARCHAR(255) NOT NULL, district VARCHAR(255) NOT NULL, name VARCHAR(255) DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, developer VARCHAR(255) DEFAULT NULL, new_building BOOLEAN DEFAULT \'false\' NOT NULL, year INT DEFAULT NULL, floors INT DEFAULT NULL, description JSONB DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN address.description IS \'(DC2Type:json_array)\'');
        $this->addSql('CREATE TABLE stream (id INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, action VARCHAR(30) NOT NULL, snapshot JSONB DEFAULT NULL, item VARCHAR(30) NOT NULL, item_id INT NOT NULL, createdUser INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F0E9BE1C7AF16D89 ON stream (createdUser)');
        $this->addSql('COMMENT ON COLUMN stream.snapshot IS \'(DC2Type:json_array)\'');
        $this->addSql('CREATE TABLE media_object (id INT NOT NULL, created_user_id INT NOT NULL, content_url VARCHAR(255) NOT NULL, image_size INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, tags TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_14D43132E104C1D3 ON media_object (created_user_id)');
        $this->addSql('COMMENT ON COLUMN media_object.tags IS \'(DC2Type:array)\'');
        $this->addSql('CREATE TABLE realty (id INT NOT NULL, manager_id INT DEFAULT NULL, realty INT NOT NULL, updated_user_id INT NOT NULL, category VARCHAR(255) NOT NULL, area NUMERIC(7, 2) DEFAULT NULL, price NUMERIC(12, 2) DEFAULT NULL, description JSONB DEFAULT NULL, status VARCHAR(255) NOT NULL, cadastral_number VARCHAR(255) DEFAULT NULL, fee NUMERIC(8, 2) DEFAULT NULL, exclusive BOOLEAN DEFAULT \'false\' NOT NULL, hidden_info JSONB DEFAULT NULL, rooms SMALLINT DEFAULT NULL, floor SMALLINT DEFAULT NULL, owner JSONB NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_627221C783E3463 ON realty (manager_id)');
        $this->addSql('CREATE INDEX IDX_627221C627221C ON realty (realty)');
        $this->addSql('CREATE INDEX IDX_627221CBB649746 ON realty (updated_user_id)');
        $this->addSql('COMMENT ON COLUMN realty.description IS \'(DC2Type:json_array)\'');
        $this->addSql('COMMENT ON COLUMN realty.hidden_info IS \'(DC2Type:json_array)\'');
        $this->addSql('COMMENT ON COLUMN realty.owner IS \'(DC2Type:json_array)\'');
        $this->addSql('CREATE TABLE realty_media_object (realty_id INT NOT NULL, media_object_id INT NOT NULL, PRIMARY KEY(realty_id, media_object_id))');
        $this->addSql('CREATE INDEX IDX_3681E3CE71C56C69 ON realty_media_object (realty_id)');
        $this->addSql('CREATE INDEX IDX_3681E3CE64DE5A5 ON realty_media_object (media_object_id)');
        $this->addSql('CREATE TABLE refresh_tokens (id INT NOT NULL, refresh_token VARCHAR(128) NOT NULL, username VARCHAR(255) NOT NULL, valid TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9BACE7E1C74F2195 ON refresh_tokens (refresh_token)');
        $this->addSql('ALTER TABLE app_users ADD CONSTRAINT FK_C25028247E9E4C8C FOREIGN KEY (photo_id) REFERENCES media_object (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE stream ADD CONSTRAINT FK_F0E9BE1C7AF16D89 FOREIGN KEY (createdUser) REFERENCES app_users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE media_object ADD CONSTRAINT FK_14D43132E104C1D3 FOREIGN KEY (created_user_id) REFERENCES app_users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE realty ADD CONSTRAINT FK_627221C783E3463 FOREIGN KEY (manager_id) REFERENCES app_users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE realty ADD CONSTRAINT FK_627221C627221C FOREIGN KEY (realty) REFERENCES address (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE realty ADD CONSTRAINT FK_627221CBB649746 FOREIGN KEY (updated_user_id) REFERENCES app_users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE realty_media_object ADD CONSTRAINT FK_3681E3CE71C56C69 FOREIGN KEY (realty_id) REFERENCES realty (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE realty_media_object ADD CONSTRAINT FK_3681E3CE64DE5A5 FOREIGN KEY (media_object_id) REFERENCES media_object (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE stream DROP CONSTRAINT FK_F0E9BE1C7AF16D89');
        $this->addSql('ALTER TABLE media_object DROP CONSTRAINT FK_14D43132E104C1D3');
        $this->addSql('ALTER TABLE realty DROP CONSTRAINT FK_627221C783E3463');
        $this->addSql('ALTER TABLE realty DROP CONSTRAINT FK_627221CBB649746');
        $this->addSql('ALTER TABLE realty DROP CONSTRAINT FK_627221C627221C');
        $this->addSql('ALTER TABLE app_users DROP CONSTRAINT FK_C25028247E9E4C8C');
        $this->addSql('ALTER TABLE realty_media_object DROP CONSTRAINT FK_3681E3CE64DE5A5');
        $this->addSql('ALTER TABLE realty_media_object DROP CONSTRAINT FK_3681E3CE71C56C69');
        $this->addSql('DROP SEQUENCE app_users_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE address_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE stream_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE media_object_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE realty_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE refresh_tokens_id_seq CASCADE');
        $this->addSql('DROP TABLE app_users');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE stream');
        $this->addSql('DROP TABLE media_object');
        $this->addSql('DROP TABLE realty');
        $this->addSql('DROP TABLE realty_media_object');
        $this->addSql('DROP TABLE refresh_tokens');
    }
}
