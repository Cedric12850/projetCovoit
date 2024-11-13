<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241108074927 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE car (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, brand VARCHAR(150) NOT NULL, type_car VARCHAR(150) NOT NULL, active TINYINT(1) NOT NULL, INDEX IDX_773DE69D7E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE car_specificity (car_id INT NOT NULL, specificity_id INT NOT NULL, INDEX IDX_80A2B12CC3C6F69F (car_id), INDEX IDX_80A2B12C5F69A929 (specificity_id), PRIMARY KEY(car_id, specificity_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE car_user (id INT AUTO_INCREMENT NOT NULL, car_id INT NOT NULL, driver_id INT NOT NULL, active TINYINT(1) NOT NULL, INDEX IDX_46F9C2E5C3C6F69F (car_id), INDEX IDX_46F9C2E5C3423909 (driver_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, creator_id INT NOT NULL, concern_id INT NOT NULL, trip_id INT NOT NULL, comment LONGTEXT NOT NULL, response LONGTEXT DEFAULT NULL, notation INT DEFAULT NULL, INDEX IDX_9474526C61220EA6 (creator_id), INDEX IDX_9474526CBC6DCCD5 (concern_id), INDEX IDX_9474526CA5BC2E0E (trip_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, step_id INT NOT NULL, nb_place INT NOT NULL, notify TINYINT(1) NOT NULL, accept TINYINT(1) NOT NULL, cancel TINYINT(1) NOT NULL, INDEX IDX_42C84955A76ED395 (user_id), INDEX IDX_42C8495573B21E9C (step_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE specificity (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(60) NOT NULL, active TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_SPECI_NAME (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE step (id INT AUTO_INCREMENT NOT NULL, trip_id INT DEFAULT NULL, town_step_id INT NOT NULL, place_step VARCHAR(150) DEFAULT NULL, num_order INT NOT NULL, price_passenger DOUBLE PRECISION NOT NULL, length_km INT NOT NULL, duration INT NOT NULL, INDEX IDX_43B9FE3CA5BC2E0E (trip_id), INDEX IDX_43B9FE3C7D59FA45 (town_step_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE town (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, zip_code VARCHAR(10) NOT NULL, department VARCHAR(50) NOT NULL, insee VARCHAR(10) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trip (id INT AUTO_INCREMENT NOT NULL, car_id INT NOT NULL, driver_id INT NOT NULL, town_start_id INT NOT NULL, date_start DATE NOT NULL, place_start VARCHAR(150) DEFAULT NULL, frequency INT DEFAULT NULL, nb_passenger INT NOT NULL, comfort TINYINT(1) NOT NULL, cancel TINYINT(1) NOT NULL, INDEX IDX_7656F53BC3C6F69F (car_id), INDEX IDX_7656F53BC3423909 (driver_id), INDEX IDX_7656F53B9D3C780F (town_start_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, town_id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, type_user INT NOT NULL, pseudo VARCHAR(250) NOT NULL, lastname VARCHAR(150) NOT NULL, firstname VARCHAR(150) NOT NULL, active TINYINT(1) NOT NULL, address LONGTEXT NOT NULL, zip_code VARCHAR(5) DEFAULT NULL, driving_license TINYINT(1) NOT NULL, photo VARCHAR(150) DEFAULT NULL, auto_accept TINYINT(1) NOT NULL, is_verified TINYINT(1) NOT NULL, phone VARCHAR(15) NOT NULL, INDEX IDX_8D93D64975E23604 (town_id), UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), UNIQUE INDEX UNIQ_USER_PSEUDO (pseudo), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE car ADD CONSTRAINT FK_773DE69D7E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE car_specificity ADD CONSTRAINT FK_80A2B12CC3C6F69F FOREIGN KEY (car_id) REFERENCES car (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE car_specificity ADD CONSTRAINT FK_80A2B12C5F69A929 FOREIGN KEY (specificity_id) REFERENCES specificity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE car_user ADD CONSTRAINT FK_46F9C2E5C3C6F69F FOREIGN KEY (car_id) REFERENCES car (id)');
        $this->addSql('ALTER TABLE car_user ADD CONSTRAINT FK_46F9C2E5C3423909 FOREIGN KEY (driver_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C61220EA6 FOREIGN KEY (creator_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CBC6DCCD5 FOREIGN KEY (concern_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA5BC2E0E FOREIGN KEY (trip_id) REFERENCES trip (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495573B21E9C FOREIGN KEY (step_id) REFERENCES step (id)');
        $this->addSql('ALTER TABLE step ADD CONSTRAINT FK_43B9FE3CA5BC2E0E FOREIGN KEY (trip_id) REFERENCES trip (id)');
        $this->addSql('ALTER TABLE step ADD CONSTRAINT FK_43B9FE3C7D59FA45 FOREIGN KEY (town_step_id) REFERENCES town (id)');
        $this->addSql('ALTER TABLE trip ADD CONSTRAINT FK_7656F53BC3C6F69F FOREIGN KEY (car_id) REFERENCES car (id)');
        $this->addSql('ALTER TABLE trip ADD CONSTRAINT FK_7656F53BC3423909 FOREIGN KEY (driver_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE trip ADD CONSTRAINT FK_7656F53B9D3C780F FOREIGN KEY (town_start_id) REFERENCES town (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64975E23604 FOREIGN KEY (town_id) REFERENCES town (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE car DROP FOREIGN KEY FK_773DE69D7E3C61F9');
        $this->addSql('ALTER TABLE car_specificity DROP FOREIGN KEY FK_80A2B12CC3C6F69F');
        $this->addSql('ALTER TABLE car_specificity DROP FOREIGN KEY FK_80A2B12C5F69A929');
        $this->addSql('ALTER TABLE car_user DROP FOREIGN KEY FK_46F9C2E5C3C6F69F');
        $this->addSql('ALTER TABLE car_user DROP FOREIGN KEY FK_46F9C2E5C3423909');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C61220EA6');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CBC6DCCD5');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CA5BC2E0E');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955A76ED395');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495573B21E9C');
        $this->addSql('ALTER TABLE step DROP FOREIGN KEY FK_43B9FE3CA5BC2E0E');
        $this->addSql('ALTER TABLE step DROP FOREIGN KEY FK_43B9FE3C7D59FA45');
        $this->addSql('ALTER TABLE trip DROP FOREIGN KEY FK_7656F53BC3C6F69F');
        $this->addSql('ALTER TABLE trip DROP FOREIGN KEY FK_7656F53BC3423909');
        $this->addSql('ALTER TABLE trip DROP FOREIGN KEY FK_7656F53B9D3C780F');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64975E23604');
        $this->addSql('DROP TABLE car');
        $this->addSql('DROP TABLE car_specificity');
        $this->addSql('DROP TABLE car_user');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE specificity');
        $this->addSql('DROP TABLE step');
        $this->addSql('DROP TABLE town');
        $this->addSql('DROP TABLE trip');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
