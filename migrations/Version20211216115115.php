<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211216115115 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE address_type (purchase_id INT NOT NULL, address_id INT NOT NULL, type VARCHAR(20) NOT NULL, INDEX IDX_F19287C2558FBEB9 (purchase_id), INDEX IDX_F19287C2F5B7AF75 (address_id), PRIMARY KEY(purchase_id, address_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE address_type ADD CONSTRAINT FK_F19287C2558FBEB9 FOREIGN KEY (purchase_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE address_type ADD CONSTRAINT FK_F19287C2F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
        $this->addSql('DROP TABLE order_address');
        $this->addSql('ALTER TABLE category ADD id_parent INT NOT NULL');
        $this->addSql('ALTER TABLE `order` ADD end_at DATETIME DEFAULT NULL, ADD is_validate TINYINT(1) DEFAULT NULL, ADD status VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE product ADD is_active TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE user ADD birth_date DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE order_address (order_id INT NOT NULL, address_id INT NOT NULL, INDEX IDX_FB34C6CA8D9F6D38 (order_id), INDEX IDX_FB34C6CAF5B7AF75 (address_id), PRIMARY KEY(order_id, address_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE order_address ADD CONSTRAINT FK_FB34C6CA8D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE order_address ADD CONSTRAINT FK_FB34C6CAF5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE address_type');
        $this->addSql('ALTER TABLE category DROP id_parent');
        $this->addSql('ALTER TABLE `order` DROP end_at, DROP is_validate, DROP status');
        $this->addSql('ALTER TABLE product DROP is_active');
        $this->addSql('ALTER TABLE user DROP birth_date');
    }
}
