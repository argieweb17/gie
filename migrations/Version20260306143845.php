<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260306143845 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create faculty subject load and bootstrap missing academic year table';
    }

    public function up(Schema $schema): void
    {
        if (!$schema->hasTable('academic_year')) {
            $this->addSql('CREATE TABLE academic_year (id INT AUTO_INCREMENT NOT NULL, year_label VARCHAR(30) NOT NULL, semester VARCHAR(50) DEFAULT NULL, start_date DATE DEFAULT NULL, end_date DATE DEFAULT NULL, is_current TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        }

        if (!$schema->hasTable('faculty_subject_load')) {
            $this->addSql('CREATE TABLE faculty_subject_load (id INT AUTO_INCREMENT NOT NULL, section VARCHAR(50) DEFAULT NULL, schedule VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, faculty_id INT NOT NULL, subject_id INT NOT NULL, academic_year_id INT DEFAULT NULL, INDEX IDX_31523284680CAB68 (faculty_id), INDEX IDX_3152328423EDC87 (subject_id), INDEX IDX_31523284C54F3401 (academic_year_id), UNIQUE INDEX unique_faculty_subject_ay (faculty_id, subject_id, academic_year_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
            $this->addSql('ALTER TABLE faculty_subject_load ADD CONSTRAINT FK_31523284680CAB68 FOREIGN KEY (faculty_id) REFERENCES `user` (id) ON DELETE CASCADE');
            $this->addSql('ALTER TABLE faculty_subject_load ADD CONSTRAINT FK_3152328423EDC87 FOREIGN KEY (subject_id) REFERENCES subject (id) ON DELETE CASCADE');
            $this->addSql('ALTER TABLE faculty_subject_load ADD CONSTRAINT FK_31523284C54F3401 FOREIGN KEY (academic_year_id) REFERENCES academic_year (id) ON DELETE SET NULL');

            return;
        }

        $foreignKeyNames = array_map(
            static fn ($foreignKey): string => $foreignKey->getName(),
            $schema->getTable('faculty_subject_load')->getForeignKeys()
        );

        if (!in_array('FK_31523284680CAB68', $foreignKeyNames, true)) {
            $this->addSql('ALTER TABLE faculty_subject_load ADD CONSTRAINT FK_31523284680CAB68 FOREIGN KEY (faculty_id) REFERENCES `user` (id) ON DELETE CASCADE');
        }

        if (!in_array('FK_3152328423EDC87', $foreignKeyNames, true)) {
            $this->addSql('ALTER TABLE faculty_subject_load ADD CONSTRAINT FK_3152328423EDC87 FOREIGN KEY (subject_id) REFERENCES subject (id) ON DELETE CASCADE');
        }

        if (!in_array('FK_31523284C54F3401', $foreignKeyNames, true)) {
            $this->addSql('ALTER TABLE faculty_subject_load ADD CONSTRAINT FK_31523284C54F3401 FOREIGN KEY (academic_year_id) REFERENCES academic_year (id) ON DELETE SET NULL');
        }
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE faculty_subject_load DROP FOREIGN KEY FK_31523284680CAB68');
        $this->addSql('ALTER TABLE faculty_subject_load DROP FOREIGN KEY FK_3152328423EDC87');
        $this->addSql('ALTER TABLE faculty_subject_load DROP FOREIGN KEY FK_31523284C54F3401');
        $this->addSql('DROP TABLE faculty_subject_load');
    }
}
