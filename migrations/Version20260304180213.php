<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260304180213 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evaluation_period DROP FOREIGN KEY `FK_FC72B39591CC992`');
        $this->addSql('DROP INDEX IDX_FC72B39591CC992 ON evaluation_period');
        $this->addSql('ALTER TABLE evaluation_period DROP course_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evaluation_period ADD course_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE evaluation_period ADD CONSTRAINT `FK_FC72B39591CC992` FOREIGN KEY (course_id) REFERENCES course (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_FC72B39591CC992 ON evaluation_period (course_id)');
    }
}
