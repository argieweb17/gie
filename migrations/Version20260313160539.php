<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260313160539 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create evaluation message table when missing and add attachment fields';
    }

    public function up(Schema $schema): void
    {
        if (!$schema->hasTable('evaluation_message')) {
            $this->addSql('CREATE TABLE evaluation_message (id INT AUTO_INCREMENT NOT NULL, sender_id INT NOT NULL, evaluation_period_id INT DEFAULT NULL, replied_by_id INT DEFAULT NULL, subject VARCHAR(255) NOT NULL, message LONGTEXT NOT NULL, status VARCHAR(20) NOT NULL, admin_reply LONGTEXT DEFAULT NULL, replied_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, attachment VARCHAR(255) DEFAULT NULL, attachment_original_name VARCHAR(255) DEFAULT NULL, INDEX IDX_EVAL_MSG_SENDER (sender_id), INDEX IDX_EVAL_MSG_PERIOD (evaluation_period_id), INDEX IDX_EVAL_MSG_REPLIED_BY (replied_by_id), INDEX idx_eval_msg_date (created_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
            $this->addSql('ALTER TABLE evaluation_message ADD CONSTRAINT FK_EVAL_MSG_SENDER FOREIGN KEY (sender_id) REFERENCES `user` (id)');
            $this->addSql('ALTER TABLE evaluation_message ADD CONSTRAINT FK_EVAL_MSG_PERIOD FOREIGN KEY (evaluation_period_id) REFERENCES evaluation_period (id)');
            $this->addSql('ALTER TABLE evaluation_message ADD CONSTRAINT FK_EVAL_MSG_REPLIED_BY FOREIGN KEY (replied_by_id) REFERENCES `user` (id)');

            return;
        }

        $table = $schema->getTable('evaluation_message');

        if (!$table->hasColumn('attachment')) {
            $this->addSql('ALTER TABLE evaluation_message ADD attachment VARCHAR(255) DEFAULT NULL');
        }

        if (!$table->hasColumn('attachment_original_name')) {
            $this->addSql('ALTER TABLE evaluation_message ADD attachment_original_name VARCHAR(255) DEFAULT NULL');
        }
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evaluation_message DROP attachment, DROP attachment_original_name');
    }
}
