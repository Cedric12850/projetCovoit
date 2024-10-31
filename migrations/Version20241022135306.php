<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241022135306 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, creator_id INT NOT NULL, concern_id INT NOT NULL, trip_id INT NOT NULL, comment LONGTEXT NOT NULL, response LONGTEXT DEFAULT NULL, notation INT DEFAULT NULL, INDEX IDX_9474526C61220EA6 (creator_id), INDEX IDX_9474526CBC6DCCD5 (concern_id), INDEX IDX_9474526CA5BC2E0E (trip_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C61220EA6 FOREIGN KEY (creator_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CBC6DCCD5 FOREIGN KEY (concern_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA5BC2E0E FOREIGN KEY (trip_id) REFERENCES trip (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C61220EA6');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CBC6DCCD5');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CA5BC2E0E');
        $this->addSql('DROP TABLE comment');
    }
}
