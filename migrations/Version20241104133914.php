<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241104133914 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE car_specificity (car_id INT NOT NULL, specificity_id INT NOT NULL, INDEX IDX_80A2B12CC3C6F69F (car_id), INDEX IDX_80A2B12C5F69A929 (specificity_id), PRIMARY KEY(car_id, specificity_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE car_specificity ADD CONSTRAINT FK_80A2B12CC3C6F69F FOREIGN KEY (car_id) REFERENCES car (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE car_specificity ADD CONSTRAINT FK_80A2B12C5F69A929 FOREIGN KEY (specificity_id) REFERENCES specificity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE specificity_car DROP FOREIGN KEY FK_5E047FCB5F69A929');
        $this->addSql('ALTER TABLE specificity_car DROP FOREIGN KEY FK_5E047FCBC3C6F69F');
        $this->addSql('DROP TABLE specificity_car');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE specificity_car (specificity_id INT NOT NULL, car_id INT NOT NULL, INDEX IDX_5E047FCB5F69A929 (specificity_id), INDEX IDX_5E047FCBC3C6F69F (car_id), PRIMARY KEY(specificity_id, car_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE specificity_car ADD CONSTRAINT FK_5E047FCB5F69A929 FOREIGN KEY (specificity_id) REFERENCES specificity (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE specificity_car ADD CONSTRAINT FK_5E047FCBC3C6F69F FOREIGN KEY (car_id) REFERENCES car (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE car_specificity DROP FOREIGN KEY FK_80A2B12CC3C6F69F');
        $this->addSql('ALTER TABLE car_specificity DROP FOREIGN KEY FK_80A2B12C5F69A929');
        $this->addSql('DROP TABLE car_specificity');
    }
}
