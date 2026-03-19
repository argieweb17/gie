<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260319043600 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE message_notification (id INT AUTO_INCREMENT NOT NULL, is_read TINYINT NOT NULL, created_at DATETIME NOT NULL, read_at DATETIME DEFAULT NULL, notified_user_id INT NOT NULL, message_id INT NOT NULL, INDEX IDX_7B55432CF5D8C9A7 (notified_user_id), INDEX IDX_7B55432C537A1329 (message_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE message_notification ADD CONSTRAINT FK_7B55432CF5D8C9A7 FOREIGN KEY (notified_user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE message_notification ADD CONSTRAINT FK_7B55432C537A1329 FOREIGN KEY (message_id) REFERENCES evaluation_message (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message_notification DROP FOREIGN KEY FK_7B55432CF5D8C9A7');
        $this->addSql('ALTER TABLE message_notification DROP FOREIGN KEY FK_7B55432C537A1329');
        $this->addSql('DROP TABLE message_notification');
    }
}
