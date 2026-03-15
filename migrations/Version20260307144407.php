<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260307144407 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE superior_evaluation (id INT AUTO_INCREMENT NOT NULL, evaluatee_role VARCHAR(30) NOT NULL, rating INT NOT NULL, comment LONGTEXT DEFAULT NULL, submitted_at DATETIME NOT NULL, is_draft TINYINT NOT NULL, evaluation_period_id INT NOT NULL, evaluator_id INT NOT NULL, evaluatee_id INT NOT NULL, question_id INT NOT NULL, INDEX IDX_7DE26A081E27F6BF (question_id), INDEX idx_sup_evaluator (evaluator_id), INDEX idx_sup_evaluatee (evaluatee_id), INDEX idx_sup_period (evaluation_period_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE superior_evaluation ADD CONSTRAINT FK_7DE26A083E8BB15A FOREIGN KEY (evaluation_period_id) REFERENCES evaluation_period (id)');
        $this->addSql('ALTER TABLE superior_evaluation ADD CONSTRAINT FK_7DE26A0843575BE2 FOREIGN KEY (evaluator_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE superior_evaluation ADD CONSTRAINT FK_7DE26A08C4292C65 FOREIGN KEY (evaluatee_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE superior_evaluation ADD CONSTRAINT FK_7DE26A081E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE superior_evaluation DROP FOREIGN KEY FK_7DE26A083E8BB15A');
        $this->addSql('ALTER TABLE superior_evaluation DROP FOREIGN KEY FK_7DE26A0843575BE2');
        $this->addSql('ALTER TABLE superior_evaluation DROP FOREIGN KEY FK_7DE26A08C4292C65');
        $this->addSql('ALTER TABLE superior_evaluation DROP FOREIGN KEY FK_7DE26A081E27F6BF');
        $this->addSql('DROP TABLE superior_evaluation');
    }
}
