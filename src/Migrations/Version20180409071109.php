<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180409071109 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE person (id INT AUTO_INCREMENT NOT NULL, manager_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, INDEX IDX_34DCD176783E3463 (manager_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE objective_entry (id INT AUTO_INCREMENT NOT NULL, by_manager_id INT NOT NULL, for_employee_id INT NOT NULL, year SMALLINT NOT NULL, type VARCHAR(64) NOT NULL, subject VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, weight DOUBLE PRECISION DEFAULT NULL, status VARCHAR(64) NOT NULL, score DOUBLE PRECISION DEFAULT NULL, INDEX IDX_4DB5A043A8CAE705 (by_manager_id), INDEX IDX_4DB5A0431E9DE52B (for_employee_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE person ADD CONSTRAINT FK_34DCD176783E3463 FOREIGN KEY (manager_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE objective_entry ADD CONSTRAINT FK_4DB5A043A8CAE705 FOREIGN KEY (by_manager_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE objective_entry ADD CONSTRAINT FK_4DB5A0431E9DE52B FOREIGN KEY (for_employee_id) REFERENCES person (id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE person DROP FOREIGN KEY FK_34DCD176783E3463');
        $this->addSql('ALTER TABLE objective_entry DROP FOREIGN KEY FK_4DB5A043A8CAE705');
        $this->addSql('ALTER TABLE objective_entry DROP FOREIGN KEY FK_4DB5A0431E9DE52B');
        $this->addSql('DROP TABLE person');
        $this->addSql('DROP TABLE objective_entry');
    }
}
