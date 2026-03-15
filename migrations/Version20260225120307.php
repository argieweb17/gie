<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260225120307 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evaluation_period ADD department_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE evaluation_period ADD CONSTRAINT FK_FC72B39AE80F5DF FOREIGN KEY (department_id) REFERENCES department (id)');
        $this->addSql('CREATE INDEX IDX_FC72B39AE80F5DF ON evaluation_period (department_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evaluation_period DROP FOREIGN KEY FK_FC72B39AE80F5DF');
        $this->addSql('DROP INDEX IDX_FC72B39AE80F5DF ON evaluation_period');
        $this->addSql('ALTER TABLE evaluation_period DROP department_id');
    }
}
