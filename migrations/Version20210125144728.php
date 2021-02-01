<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210125144728 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
    
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, place_id INT NOT NULL, owner_id INT NOT NULL, name VARCHAR(60) NOT NULL, description LONGTEXT NOT NULL, start_at DATETIME NOT NULL, end_at DATETIME NOT NULL, picture VARCHAR(255) NOT NULL, price DOUBLE PRECISION DEFAULT NULL, capacity INT DEFAULT NULL, created_at DATETIME NOT NULL, INDEX IDX_3BAE0AA7DA6A219 (place_id), INDEX IDX_3BAE0AA77E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_category (event_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_40A0F01171F7E88B (event_id), INDEX IDX_40A0F01112469DE2 (category_id), PRIMARY KEY(event_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE participation (event_id INT NOT NULL, user_id INT NOT NULL, booking_number VARCHAR(20) NOT NULL, INDEX IDX_AB55E24F71F7E88B (event_id), INDEX IDX_AB55E24FA76ED395 (user_id), PRIMARY KEY(event_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE place (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(60) NOT NULL, address VARCHAR(255) NOT NULL, zipcode VARCHAR(5) NOT NULL, city VARCHAR(50) NOT NULL, accessibility TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(30) NOT NULL, email VARCHAR(100) NOT NULL, password VARCHAR(255) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7DA6A219 FOREIGN KEY (place_id) REFERENCES place (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA77E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE event_category ADD CONSTRAINT FK_40A0F01171F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_category ADD CONSTRAINT FK_40A0F01112469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24F71F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event_category DROP FOREIGN KEY FK_40A0F01112469DE2');
        $this->addSql('ALTER TABLE event_category DROP FOREIGN KEY FK_40A0F01171F7E88B');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24F71F7E88B');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7DA6A219');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA77E3C61F9');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24FA76ED395');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE event_category');
        $this->addSql('DROP TABLE participation');
        $this->addSql('DROP TABLE place');
        $this->addSql('DROP TABLE user');
    }
}
