<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180414185146 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE objective_management ADD for_employee_id INT NOT NULL, ADD year SMALLINT NOT NULL, CHANGE vp_weight vp_weight DOUBLE PRECISION DEFAULT NULL, CHANGE ceo_weight ceo_weight DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE objective_management ADD CONSTRAINT FK_2633AB681E9DE52B FOREIGN KEY (for_employee_id) REFERENCES person (id)');
        $this->addSql('CREATE INDEX IDX_2633AB681E9DE52B ON objective_management (for_employee_id)');
        $this->addSql('ALTER TABLE person CHANGE manager_id manager_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE objective_entry CHANGE subject subject VARCHAR(255) DEFAULT NULL, CHANGE weight weight DOUBLE PRECISION DEFAULT NULL, CHANGE achieve achieve DOUBLE PRECISION DEFAULT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE objective_entry CHANGE subject subject VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE weight weight DOUBLE PRECISION DEFAULT \'NULL\', CHANGE achieve achieve DOUBLE PRECISION DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE objective_management DROP FOREIGN KEY FK_2633AB681E9DE52B');
        $this->addSql('DROP INDEX IDX_2633AB681E9DE52B ON objective_management');
        $this->addSql('ALTER TABLE objective_management DROP for_employee_id, DROP year, CHANGE vp_weight vp_weight DOUBLE PRECISION DEFAULT \'NULL\', CHANGE ceo_weight ceo_weight DOUBLE PRECISION DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE person CHANGE manager_id manager_id INT DEFAULT NULL');
    }
}
