<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260415093000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Allow duplicate sections with different schedules for faculty subject loads';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->getTable('faculty_subject_load');

        if ($table->hasIndex('unique_faculty_subject_section_schedule_ay')) {
            return;
        }

        if ($table->hasIndex('unique_faculty_subject_section_ay')) {
            $this->addSql('ALTER TABLE faculty_subject_load DROP INDEX unique_faculty_subject_section_ay');
        } elseif ($table->hasIndex('unique_faculty_subject_ay')) {
            $this->addSql('ALTER TABLE faculty_subject_load DROP INDEX unique_faculty_subject_ay');
        }

        $this->addSql('CREATE UNIQUE INDEX unique_faculty_subject_section_schedule_ay ON faculty_subject_load (faculty_id, subject_id, section, schedule, academic_year_id)');
    }

    public function down(Schema $schema): void
    {
        $table = $schema->getTable('faculty_subject_load');

        if ($table->hasIndex('unique_faculty_subject_section_schedule_ay')) {
            $this->addSql('ALTER TABLE faculty_subject_load DROP INDEX unique_faculty_subject_section_schedule_ay');
        }

        if (!$table->hasIndex('unique_faculty_subject_ay')) {
            $this->addSql('CREATE UNIQUE INDEX unique_faculty_subject_ay ON faculty_subject_load (faculty_id, subject_id, academic_year_id)');
        }
    }
}
