<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260222032745 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE audit_log (id INT AUTO_INCREMENT NOT NULL, action VARCHAR(50) NOT NULL, entity_type VARCHAR(255) NOT NULL, entity_id INT DEFAULT NULL, details LONGTEXT DEFAULT NULL, ip_address VARCHAR(45) DEFAULT NULL, created_at DATETIME NOT NULL, performed_by_id INT NOT NULL, INDEX IDX_F6E1C0F52E65C292 (performed_by_id), INDEX idx_audit_action (action), INDEX idx_audit_date (created_at), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE department (id INT AUTO_INCREMENT NOT NULL, department_name VARCHAR(255) NOT NULL, college_name VARCHAR(255) DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE enrollment (id INT AUTO_INCREMENT NOT NULL, student_id INT NOT NULL, subject_id INT NOT NULL, INDEX IDX_DBDCD7E1CB944F1A (student_id), INDEX IDX_DBDCD7E123EDC87 (subject_id), UNIQUE INDEX unique_enrollment (student_id, subject_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evaluation_period (id INT AUTO_INCREMENT NOT NULL, evaluation_type VARCHAR(10) NOT NULL, semester VARCHAR(50) DEFAULT NULL, school_year VARCHAR(20) DEFAULT NULL, start_date DATETIME NOT NULL, end_date DATETIME NOT NULL, status TINYINT NOT NULL, anonymous_mode TINYINT NOT NULL, results_locked TINYINT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evaluation_response (id INT AUTO_INCREMENT NOT NULL, rating INT NOT NULL, comment LONGTEXT DEFAULT NULL, submitted_at DATETIME NOT NULL, is_draft TINYINT NOT NULL, evaluation_period_id INT NOT NULL, question_id INT NOT NULL, evaluator_id INT DEFAULT NULL, faculty_id INT NOT NULL, subject_id INT DEFAULT NULL, INDEX IDX_333572DD3E8BB15A (evaluation_period_id), INDEX IDX_333572DD1E27F6BF (question_id), INDEX IDX_333572DD680CAB68 (faculty_id), INDEX IDX_333572DD23EDC87 (subject_id), INDEX idx_eval_faculty (evaluation_period_id, faculty_id), INDEX idx_evaluator (evaluator_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, question_text LONGTEXT NOT NULL, category VARCHAR(100) DEFAULT NULL, evaluation_type VARCHAR(10) NOT NULL, weight DOUBLE PRECISION DEFAULT NULL, sort_order INT NOT NULL, is_required TINYINT NOT NULL, is_active TINYINT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subject (id INT AUTO_INCREMENT NOT NULL, subject_code VARCHAR(50) NOT NULL, subject_name VARCHAR(255) NOT NULL, semester VARCHAR(50) DEFAULT NULL, school_year VARCHAR(20) DEFAULT NULL, faculty_id INT DEFAULT NULL, department_id INT DEFAULT NULL, INDEX IDX_FBCE3E7A680CAB68 (faculty_id), INDEX IDX_FBCE3E7AAE80F5DF (department_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, account_status VARCHAR(50) NOT NULL, profile_picture VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, last_login DATETIME DEFAULT NULL, department_id INT DEFAULT NULL, INDEX IDX_8D93D649AE80F5DF (department_id), UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE audit_log ADD CONSTRAINT FK_F6E1C0F52E65C292 FOREIGN KEY (performed_by_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE enrollment ADD CONSTRAINT FK_DBDCD7E1CB944F1A FOREIGN KEY (student_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE enrollment ADD CONSTRAINT FK_DBDCD7E123EDC87 FOREIGN KEY (subject_id) REFERENCES subject (id)');
        $this->addSql('ALTER TABLE evaluation_response ADD CONSTRAINT FK_333572DD3E8BB15A FOREIGN KEY (evaluation_period_id) REFERENCES evaluation_period (id)');
        $this->addSql('ALTER TABLE evaluation_response ADD CONSTRAINT FK_333572DD1E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE evaluation_response ADD CONSTRAINT FK_333572DD43575BE2 FOREIGN KEY (evaluator_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE evaluation_response ADD CONSTRAINT FK_333572DD680CAB68 FOREIGN KEY (faculty_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE evaluation_response ADD CONSTRAINT FK_333572DD23EDC87 FOREIGN KEY (subject_id) REFERENCES subject (id)');
        $this->addSql('ALTER TABLE subject ADD CONSTRAINT FK_FBCE3E7A680CAB68 FOREIGN KEY (faculty_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE subject ADD CONSTRAINT FK_FBCE3E7AAE80F5DF FOREIGN KEY (department_id) REFERENCES department (id)');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D649AE80F5DF FOREIGN KEY (department_id) REFERENCES department (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE audit_log DROP FOREIGN KEY FK_F6E1C0F52E65C292');
        $this->addSql('ALTER TABLE enrollment DROP FOREIGN KEY FK_DBDCD7E1CB944F1A');
        $this->addSql('ALTER TABLE enrollment DROP FOREIGN KEY FK_DBDCD7E123EDC87');
        $this->addSql('ALTER TABLE evaluation_response DROP FOREIGN KEY FK_333572DD3E8BB15A');
        $this->addSql('ALTER TABLE evaluation_response DROP FOREIGN KEY FK_333572DD1E27F6BF');
        $this->addSql('ALTER TABLE evaluation_response DROP FOREIGN KEY FK_333572DD43575BE2');
        $this->addSql('ALTER TABLE evaluation_response DROP FOREIGN KEY FK_333572DD680CAB68');
        $this->addSql('ALTER TABLE evaluation_response DROP FOREIGN KEY FK_333572DD23EDC87');
        $this->addSql('ALTER TABLE subject DROP FOREIGN KEY FK_FBCE3E7A680CAB68');
        $this->addSql('ALTER TABLE subject DROP FOREIGN KEY FK_FBCE3E7AAE80F5DF');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649AE80F5DF');
        $this->addSql('DROP TABLE audit_log');
        $this->addSql('DROP TABLE department');
        $this->addSql('DROP TABLE enrollment');
        $this->addSql('DROP TABLE evaluation_period');
        $this->addSql('DROP TABLE evaluation_response');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE subject');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
