<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260225125601 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE course (id INT AUTO_INCREMENT NOT NULL, course_name VARCHAR(100) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE evaluation_period ADD course_id INT DEFAULT NULL, DROP course');
        $this->addSql('ALTER TABLE evaluation_period ADD CONSTRAINT FK_FC72B39591CC992 FOREIGN KEY (course_id) REFERENCES course (id)');
        $this->addSql('CREATE INDEX IDX_FC72B39591CC992 ON evaluation_period (course_id)');
        $this->addSql('ALTER TABLE user ADD course_id INT DEFAULT NULL, DROP course');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649591CC992 FOREIGN KEY (course_id) REFERENCES course (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649591CC992 ON user (course_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE course');
        $this->addSql('ALTER TABLE evaluation_period DROP FOREIGN KEY FK_FC72B39591CC992');
        $this->addSql('DROP INDEX IDX_FC72B39591CC992 ON evaluation_period');
        $this->addSql('ALTER TABLE evaluation_period ADD course VARCHAR(100) DEFAULT NULL, DROP course_id');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649591CC992');
        $this->addSql('DROP INDEX IDX_8D93D649591CC992 ON `user`');
        $this->addSql('ALTER TABLE `user` ADD course VARCHAR(100) DEFAULT NULL, DROP course_id');
    }
}
