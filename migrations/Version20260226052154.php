<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260226052154 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE curriculum (id INT AUTO_INCREMENT NOT NULL, curriculum_name VARCHAR(255) NOT NULL, curriculum_year VARCHAR(50) DEFAULT NULL, description VARCHAR(500) DEFAULT NULL, course_id INT DEFAULT NULL, department_id INT DEFAULT NULL, INDEX IDX_7BE2A7C3591CC992 (course_id), INDEX IDX_7BE2A7C3AE80F5DF (department_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE curriculum ADD CONSTRAINT FK_7BE2A7C3591CC992 FOREIGN KEY (course_id) REFERENCES course (id)');
        $this->addSql('ALTER TABLE curriculum ADD CONSTRAINT FK_7BE2A7C3AE80F5DF FOREIGN KEY (department_id) REFERENCES department (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE curriculum DROP FOREIGN KEY FK_7BE2A7C3591CC992');
        $this->addSql('ALTER TABLE curriculum DROP FOREIGN KEY FK_7BE2A7C3AE80F5DF');
        $this->addSql('DROP TABLE curriculum');
    }
}
