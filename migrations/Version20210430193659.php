<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210430193659 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE objective_entry (id INT AUTO_INCREMENT NOT NULL, by_manager_id INT NOT NULL, for_employee_id INT NOT NULL, quad_weight_id INT DEFAULT NULL, year SMALLINT NOT NULL, type VARCHAR(64) NOT NULL, subject VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, weight DOUBLE PRECISION DEFAULT NULL, achieve DOUBLE PRECISION DEFAULT NULL, INDEX IDX_4DB5A043A8CAE705 (by_manager_id), INDEX IDX_4DB5A0431E9DE52B (for_employee_id), INDEX IDX_4DB5A043C9ECDD09 (quad_weight_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE objective_management (id INT AUTO_INCREMENT NOT NULL, for_employee_id INT NOT NULL, by_manager_id INT NOT NULL, status VARCHAR(64) NOT NULL, vp_weight DOUBLE PRECISION DEFAULT NULL, ceo_weight DOUBLE PRECISION DEFAULT NULL, year SMALLINT NOT NULL, INDEX IDX_2633AB681E9DE52B (for_employee_id), INDEX IDX_2633AB68A8CAE705 (by_manager_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person (id INT AUTO_INCREMENT NOT NULL, manager_id INT DEFAULT NULL, reviewer_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, full_name VARCHAR(255) DEFAULT NULL, ldap_name VARCHAR(64) DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, active TINYINT(1) NOT NULL, INDEX IDX_34DCD176783E3463 (manager_id), INDEX IDX_34DCD17670574616 (reviewer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quad_weight (id INT AUTO_INCREMENT NOT NULL, first DOUBLE PRECISION DEFAULT NULL, second DOUBLE PRECISION DEFAULT NULL, third DOUBLE PRECISION DEFAULT NULL, fourth DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE objective_entry ADD CONSTRAINT FK_4DB5A043A8CAE705 FOREIGN KEY (by_manager_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE objective_entry ADD CONSTRAINT FK_4DB5A0431E9DE52B FOREIGN KEY (for_employee_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE objective_entry ADD CONSTRAINT FK_4DB5A043C9ECDD09 FOREIGN KEY (quad_weight_id) REFERENCES quad_weight (id)');
        $this->addSql('ALTER TABLE objective_management ADD CONSTRAINT FK_2633AB681E9DE52B FOREIGN KEY (for_employee_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE objective_management ADD CONSTRAINT FK_2633AB68A8CAE705 FOREIGN KEY (by_manager_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE person ADD CONSTRAINT FK_34DCD176783E3463 FOREIGN KEY (manager_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE person ADD CONSTRAINT FK_34DCD17670574616 FOREIGN KEY (reviewer_id) REFERENCES person (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE objective_entry DROP FOREIGN KEY FK_4DB5A043A8CAE705');
        $this->addSql('ALTER TABLE objective_entry DROP FOREIGN KEY FK_4DB5A0431E9DE52B');
        $this->addSql('ALTER TABLE objective_management DROP FOREIGN KEY FK_2633AB681E9DE52B');
        $this->addSql('ALTER TABLE objective_management DROP FOREIGN KEY FK_2633AB68A8CAE705');
        $this->addSql('ALTER TABLE person DROP FOREIGN KEY FK_34DCD176783E3463');
        $this->addSql('ALTER TABLE person DROP FOREIGN KEY FK_34DCD17670574616');
        $this->addSql('ALTER TABLE objective_entry DROP FOREIGN KEY FK_4DB5A043C9ECDD09');
        $this->addSql('DROP TABLE objective_entry');
        $this->addSql('DROP TABLE objective_management');
        $this->addSql('DROP TABLE person');
        $this->addSql('DROP TABLE quad_weight');
    }
}
