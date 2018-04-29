<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180429070940 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE person ADD title VARCHAR(255) DEFAULT NULL, CHANGE manager_id manager_id INT DEFAULT NULL, CHANGE full_name full_name VARCHAR(255) DEFAULT NULL, CHANGE ldap_name ldap_name VARCHAR(64) DEFAULT NULL');
        $this->addSql('ALTER TABLE objective_management CHANGE vp_weight vp_weight DOUBLE PRECISION DEFAULT NULL, CHANGE ceo_weight ceo_weight DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE objective_entry CHANGE subject subject VARCHAR(255) DEFAULT NULL, CHANGE weight weight DOUBLE PRECISION DEFAULT NULL, CHANGE achieve achieve DOUBLE PRECISION DEFAULT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE objective_entry CHANGE subject subject VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE weight weight DOUBLE PRECISION DEFAULT \'NULL\', CHANGE achieve achieve DOUBLE PRECISION DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE objective_management CHANGE vp_weight vp_weight DOUBLE PRECISION DEFAULT \'NULL\', CHANGE ceo_weight ceo_weight DOUBLE PRECISION DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE person DROP title, CHANGE manager_id manager_id INT DEFAULT NULL, CHANGE full_name full_name VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE ldap_name ldap_name VARCHAR(64) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
    }
}
