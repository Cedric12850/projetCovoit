<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241108075611 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE car_specificity (car_id INT NOT NULL, specificity_id INT NOT NULL, INDEX IDX_80A2B12CC3C6F69F (car_id), INDEX IDX_80A2B12C5F69A929 (specificity_id), PRIMARY KEY(car_id, specificity_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, step_id INT NOT NULL, nb_place INT NOT NULL, notify TINYINT(1) NOT NULL, accept TINYINT(1) NOT NULL, cancel TINYINT(1) NOT NULL, INDEX IDX_42C84955A76ED395 (user_id), INDEX IDX_42C8495573B21E9C (step_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE town (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, zip_code VARCHAR(10) NOT NULL, department VARCHAR(50) NOT NULL, insee VARCHAR(10) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE car_specificity ADD CONSTRAINT FK_80A2B12CC3C6F69F FOREIGN KEY (car_id) REFERENCES car (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE car_specificity ADD CONSTRAINT FK_80A2B12C5F69A929 FOREIGN KEY (specificity_id) REFERENCES specificity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495573B21E9C FOREIGN KEY (step_id) REFERENCES step (id)');
        $this->addSql('ALTER TABLE specificity_car DROP FOREIGN KEY FK_5E047FCB5F69A929');
        $this->addSql('ALTER TABLE specificity_car DROP FOREIGN KEY FK_5E047FCBC3C6F69F');
        $this->addSql('DROP TABLE specificity_car');
        $this->addSql('ALTER TABLE car_user DROP FOREIGN KEY FK_46F9C2E5A76ED395');
        $this->addSql('ALTER TABLE car_user DROP FOREIGN KEY FK_46F9C2E5C3C6F69F');
        $this->addSql('DROP INDEX IDX_46F9C2E5A76ED395 ON car_user');
        $this->addSql('ALTER TABLE car_user ADD id INT AUTO_INCREMENT NOT NULL, ADD active TINYINT(1) NOT NULL, CHANGE user_id driver_id INT NOT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE car_user ADD CONSTRAINT FK_46F9C2E5C3423909 FOREIGN KEY (driver_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE car_user ADD CONSTRAINT FK_46F9C2E5C3C6F69F FOREIGN KEY (car_id) REFERENCES car (id)');
        $this->addSql('CREATE INDEX IDX_46F9C2E5C3423909 ON car_user (driver_id)');
        $this->addSql('ALTER TABLE step ADD town_step_id INT NOT NULL');
        $this->addSql('ALTER TABLE step ADD CONSTRAINT FK_43B9FE3C7D59FA45 FOREIGN KEY (town_step_id) REFERENCES town (id)');
        $this->addSql('CREATE INDEX IDX_43B9FE3C7D59FA45 ON step (town_step_id)');
        $this->addSql('ALTER TABLE trip ADD town_start_id INT NOT NULL');
        $this->addSql('ALTER TABLE trip ADD CONSTRAINT FK_7656F53B9D3C780F FOREIGN KEY (town_start_id) REFERENCES town (id)');
        $this->addSql('CREATE INDEX IDX_7656F53B9D3C780F ON trip (town_start_id)');
        $this->addSql('ALTER TABLE user ADD town_id INT NOT NULL, ADD is_verified TINYINT(1) NOT NULL, ADD phone VARCHAR(15) NOT NULL, CHANGE zip_code zip_code VARCHAR(5) DEFAULT NULL, CHANGE photo photo VARCHAR(150) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64975E23604 FOREIGN KEY (town_id) REFERENCES town (id)');
        $this->addSql('CREATE INDEX IDX_8D93D64975E23604 ON user (town_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE step DROP FOREIGN KEY FK_43B9FE3C7D59FA45');
        $this->addSql('ALTER TABLE trip DROP FOREIGN KEY FK_7656F53B9D3C780F');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64975E23604');
        $this->addSql('CREATE TABLE specificity_car (specificity_id INT NOT NULL, car_id INT NOT NULL, INDEX IDX_5E047FCBC3C6F69F (car_id), INDEX IDX_5E047FCB5F69A929 (specificity_id), PRIMARY KEY(specificity_id, car_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE specificity_car ADD CONSTRAINT FK_5E047FCB5F69A929 FOREIGN KEY (specificity_id) REFERENCES specificity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE specificity_car ADD CONSTRAINT FK_5E047FCBC3C6F69F FOREIGN KEY (car_id) REFERENCES car (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE car_specificity DROP FOREIGN KEY FK_80A2B12CC3C6F69F');
        $this->addSql('ALTER TABLE car_specificity DROP FOREIGN KEY FK_80A2B12C5F69A929');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955A76ED395');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495573B21E9C');
        $this->addSql('DROP TABLE car_specificity');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE town');
        $this->addSql('DROP INDEX IDX_7656F53B9D3C780F ON trip');
        $this->addSql('ALTER TABLE trip DROP town_start_id');
        $this->addSql('ALTER TABLE car_user MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE car_user DROP FOREIGN KEY FK_46F9C2E5C3423909');
        $this->addSql('ALTER TABLE car_user DROP FOREIGN KEY FK_46F9C2E5C3C6F69F');
        $this->addSql('DROP INDEX IDX_46F9C2E5C3423909 ON car_user');
        $this->addSql('DROP INDEX `PRIMARY` ON car_user');
        $this->addSql('ALTER TABLE car_user DROP id, DROP active, CHANGE driver_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE car_user ADD CONSTRAINT FK_46F9C2E5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE car_user ADD CONSTRAINT FK_46F9C2E5C3C6F69F FOREIGN KEY (car_id) REFERENCES car (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_46F9C2E5A76ED395 ON car_user (user_id)');
        $this->addSql('ALTER TABLE car_user ADD PRIMARY KEY (car_id, user_id)');
        $this->addSql('DROP INDEX IDX_8D93D64975E23604 ON user');
        $this->addSql('ALTER TABLE user DROP town_id, DROP is_verified, DROP phone, CHANGE zip_code zip_code VARCHAR(5) NOT NULL, CHANGE photo photo VARCHAR(150) NOT NULL');
        $this->addSql('DROP INDEX IDX_43B9FE3C7D59FA45 ON step');
        $this->addSql('ALTER TABLE step DROP town_step_id');
    }
}
