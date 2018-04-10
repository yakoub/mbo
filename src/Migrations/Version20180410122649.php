<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180410122649 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE objective_entry DROP status, CHANGE subject subject VARCHAR(255) DEFAULT NULL, CHANGE weight weight DOUBLE PRECISION DEFAULT NULL, CHANGE score score DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE person CHANGE manager_id manager_id INT DEFAULT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE objective_entry ADD status VARCHAR(64) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE subject subject VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE weight weight DOUBLE PRECISION DEFAULT \'NULL\', CHANGE score score DOUBLE PRECISION DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE person CHANGE manager_id manager_id INT DEFAULT NULL');
    }
}
