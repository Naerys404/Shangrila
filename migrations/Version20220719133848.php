<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220719133848 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE blog (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, author VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, image VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, created_at DATETIME NOT NULL, rating INT NOT NULL, content LONGTEXT NOT NULL, public_view TINYINT(1) DEFAULT NULL, UNIQUE INDEX UNIQ_9474526CF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE meal (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, INDEX IDX_9EF68E9C12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, description LONGTEXT DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, menu_id INT DEFAULT NULL, reference VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, delivery_address LONGTEXT NOT NULL, stripe_token VARCHAR(255) DEFAULT NULL, brand_stripe VARCHAR(255) DEFAULT NULL, last4_stripe VARCHAR(255) DEFAULT NULL, id_charge_stripe VARCHAR(255) DEFAULT NULL, status_stripe VARCHAR(255) DEFAULT NULL, price DOUBLE PRECISION NOT NULL, delivered_status TINYINT(1) DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_F5299398A76ED395 (user_id), INDEX IDX_F5299398CCD7E912 (menu_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE table_booking (id INT AUTO_INCREMENT NOT NULL, booker_id INT DEFAULT NULL, date DATETIME NOT NULL, timesheet VARCHAR(255) NOT NULL, guests INT NOT NULL, INDEX IDX_E23FACD18B7E4006 (booker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, address LONGTEXT NOT NULL, hash VARCHAR(255) NOT NULL, roles JSON NOT NULL, created_at DATETIME NOT NULL, postal_code INT NOT NULL, city VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE meal ADD CONSTRAINT FK_9EF68E9C12469DE2 FOREIGN KEY (category_id) REFERENCES menu (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398CCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id)');
        $this->addSql('ALTER TABLE table_booking ADD CONSTRAINT FK_E23FACD18B7E4006 FOREIGN KEY (booker_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE meal DROP FOREIGN KEY FK_9EF68E9C12469DE2');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398CCD7E912');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CF675F31B');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398A76ED395');
        $this->addSql('ALTER TABLE table_booking DROP FOREIGN KEY FK_E23FACD18B7E4006');
        $this->addSql('DROP TABLE blog');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE meal');
        $this->addSql('DROP TABLE menu');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE table_booking');
        $this->addSql('DROP TABLE user');
    }
}
