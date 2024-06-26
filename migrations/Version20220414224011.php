<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220414224011 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398548D9637');
        $this->addSql('DROP INDEX UNIQ_F5299398548D9637 ON `order`');
        $this->addSql('ALTER TABLE `order` CHANGE price_id_id price_id INT NOT NULL');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398D614C7E7 FOREIGN KEY (price_id) REFERENCES menu (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F5299398D614C7E7 ON `order` (price_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment CHANGE content content LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE meal CHANGE title title VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE description description LONGTEXT DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE image image VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE menu CHANGE title title VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE description description LONGTEXT DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE image image VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398D614C7E7');
        $this->addSql('DROP INDEX UNIQ_F5299398D614C7E7 ON `order`');
        $this->addSql('ALTER TABLE `order` CHANGE reference reference VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE delivery_address delivery_address LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE price_id price_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398548D9637 FOREIGN KEY (price_id_id) REFERENCES menu (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F5299398548D9637 ON `order` (price_id_id)');
        $this->addSql('ALTER TABLE table_booking CHANGE timesheet timesheet VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user CHANGE firstname firstname VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE lastname lastname VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE email email VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE address address LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE hash hash VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE city city VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
