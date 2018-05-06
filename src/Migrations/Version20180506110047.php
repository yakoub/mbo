<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180506110047 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE objective_entry CHANGE weight weight DOUBLE PRECISION DEFAULT NULL, CHANGE achieve achieve DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE objective_management ADD by_manager_id INT NOT NULL, CHANGE vp_weight vp_weight DOUBLE PRECISION DEFAULT NULL, CHANGE ceo_weight ceo_weight DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE objective_management ADD CONSTRAINT FK_2633AB68A8CAE705 FOREIGN KEY (by_manager_id) REFERENCES person (id)');
        $this->addSql('CREATE INDEX IDX_2633AB68A8CAE705 ON objective_management (by_manager_id)');
        $this->addSql('ALTER TABLE person CHANGE manager_id manager_id INT DEFAULT NULL, CHANGE reviewer_id reviewer_id INT DEFAULT NULL, CHANGE full_name full_name VARCHAR(255) DEFAULT NULL, CHANGE ldap_name ldap_name VARCHAR(64) DEFAULT NULL, CHANGE title title VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE objective_entry CHANGE weight weight DOUBLE PRECISION DEFAULT \'NULL\', CHANGE achieve achieve DOUBLE PRECISION DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE objective_management DROP FOREIGN KEY FK_2633AB68A8CAE705');
        $this->addSql('DROP INDEX IDX_2633AB68A8CAE705 ON objective_management');
        $this->addSql('ALTER TABLE objective_management DROP by_manager_id, CHANGE vp_weight vp_weight DOUBLE PRECISION DEFAULT \'NULL\', CHANGE ceo_weight ceo_weight DOUBLE PRECISION DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE person CHANGE manager_id manager_id INT DEFAULT NULL, CHANGE reviewer_id reviewer_id INT DEFAULT NULL, CHANGE full_name full_name VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE ldap_name ldap_name VARCHAR(64) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE title title VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
    }
}
