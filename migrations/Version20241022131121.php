<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241022131121 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE specificity (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(60) NOT NULL, active TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_SPECI_NAME (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE specificity_car (specificity_id INT NOT NULL, car_id INT NOT NULL, INDEX IDX_5E047FCB5F69A929 (specificity_id), INDEX IDX_5E047FCBC3C6F69F (car_id), PRIMARY KEY(specificity_id, car_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE specificity_car ADD CONSTRAINT FK_5E047FCB5F69A929 FOREIGN KEY (specificity_id) REFERENCES specificity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE specificity_car ADD CONSTRAINT FK_5E047FCBC3C6F69F FOREIGN KEY (car_id) REFERENCES car (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE specificity_car DROP FOREIGN KEY FK_5E047FCB5F69A929');
        $this->addSql('ALTER TABLE specificity_car DROP FOREIGN KEY FK_5E047FCBC3C6F69F');
        $this->addSql('DROP TABLE specificity');
        $this->addSql('DROP TABLE specificity_car');
    }
}
