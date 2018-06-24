<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180624103545 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE quad_weight (id INT AUTO_INCREMENT NOT NULL, first DOUBLE PRECISION DEFAULT NULL, second DOUBLE PRECISION DEFAULT NULL, third DOUBLE PRECISION DEFAULT NULL, fourth DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE objective_entry ADD quad_weight_id INT DEFAULT NULL, CHANGE weight weight DOUBLE PRECISION DEFAULT NULL, CHANGE achieve achieve DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE objective_entry ADD CONSTRAINT FK_4DB5A043C9ECDD09 FOREIGN KEY (quad_weight_id) REFERENCES quad_weight (id)');
        $this->addSql('CREATE INDEX IDX_4DB5A043C9ECDD09 ON objective_entry (quad_weight_id)');
        $this->addSql('ALTER TABLE person CHANGE manager_id manager_id INT DEFAULT NULL, CHANGE reviewer_id reviewer_id INT DEFAULT NULL, CHANGE full_name full_name VARCHAR(255) DEFAULT NULL, CHANGE ldap_name ldap_name VARCHAR(64) DEFAULT NULL, CHANGE title title VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE objective_management CHANGE vp_weight vp_weight DOUBLE PRECISION DEFAULT NULL, CHANGE ceo_weight ceo_weight DOUBLE PRECISION DEFAULT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE objective_entry DROP FOREIGN KEY FK_4DB5A043C9ECDD09');
        $this->addSql('DROP TABLE quad_weight');
        $this->addSql('DROP INDEX IDX_4DB5A043C9ECDD09 ON objective_entry');
        $this->addSql('ALTER TABLE objective_entry DROP quad_weight_id, CHANGE weight weight DOUBLE PRECISION DEFAULT \'NULL\', CHANGE achieve achieve DOUBLE PRECISION DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE objective_management CHANGE vp_weight vp_weight DOUBLE PRECISION DEFAULT \'NULL\', CHANGE ceo_weight ceo_weight DOUBLE PRECISION DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE person CHANGE manager_id manager_id INT DEFAULT NULL, CHANGE reviewer_id reviewer_id INT DEFAULT NULL, CHANGE full_name full_name VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE ldap_name ldap_name VARCHAR(64) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE title title VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
    }
}
