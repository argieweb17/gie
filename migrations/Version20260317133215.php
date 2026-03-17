<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260317133215 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE faculty_notification_read (id INT AUTO_INCREMENT NOT NULL, read_at DATETIME NOT NULL, user_id INT NOT NULL, evaluation_period_id INT NOT NULL, INDEX IDX_F9086C6AA76ED395 (user_id), INDEX IDX_F9086C6A3E8BB15A (evaluation_period_id), UNIQUE INDEX UNIQ_F9086C6AA76ED3953E8BB15A (user_id, evaluation_period_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE faculty_notification_read ADD CONSTRAINT FK_F9086C6AA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE faculty_notification_read ADD CONSTRAINT FK_F9086C6A3E8BB15A FOREIGN KEY (evaluation_period_id) REFERENCES evaluation_period (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE faculty_notification_read DROP FOREIGN KEY FK_F9086C6AA76ED395');
        $this->addSql('ALTER TABLE faculty_notification_read DROP FOREIGN KEY FK_F9086C6A3E8BB15A');
        $this->addSql('DROP TABLE faculty_notification_read');
    }
}
