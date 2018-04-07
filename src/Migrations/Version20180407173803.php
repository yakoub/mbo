<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180407173803 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE mboyearly (id INT AUTO_INCREMENT NOT NULL, by_manager_id INT NOT NULL, for_employee_id INT NOT NULL, year SMALLINT NOT NULL, type VARCHAR(64) NOT NULL, subject VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, weight DOUBLE PRECISION DEFAULT NULL, status VARCHAR(64) NOT NULL, score DOUBLE PRECISION DEFAULT NULL, INDEX IDX_ED948CC8A8CAE705 (by_manager_id), INDEX IDX_ED948CC81E9DE52B (for_employee_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mboyearly ADD CONSTRAINT FK_ED948CC8A8CAE705 FOREIGN KEY (by_manager_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE mboyearly ADD CONSTRAINT FK_ED948CC81E9DE52B FOREIGN KEY (for_employee_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE person CHANGE manager_id manager_id INT DEFAULT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE mboyearly');
        $this->addSql('ALTER TABLE person CHANGE manager_id manager_id INT DEFAULT NULL');
    }
}
