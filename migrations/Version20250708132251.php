<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250708132251 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE objective_management (id INT AUTO_INCREMENT NOT NULL, for_employee_id INT NOT NULL, by_manager_id INT NOT NULL, status VARCHAR(64) NOT NULL, vp_weight DOUBLE PRECISION DEFAULT NULL, ceo_weight DOUBLE PRECISION DEFAULT NULL, year SMALLINT NOT NULL, INDEX IDX_2633AB681E9DE52B (for_employee_id), INDEX IDX_2633AB68A8CAE705 (by_manager_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE objective_management ADD CONSTRAINT FK_2633AB681E9DE52B FOREIGN KEY (for_employee_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE objective_management ADD CONSTRAINT FK_2633AB68A8CAE705 FOREIGN KEY (by_manager_id) REFERENCES person (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE objective_management DROP FOREIGN KEY FK_2633AB681E9DE52B');
        $this->addSql('ALTER TABLE objective_management DROP FOREIGN KEY FK_2633AB68A8CAE705');
        $this->addSql('DROP TABLE objective_management');
    }
}
