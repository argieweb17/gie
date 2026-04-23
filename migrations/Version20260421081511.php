<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260421081511 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Align mapped schema with current metadata while preserving legacy enrollment table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DROP INDEX idx_corr_released_at ON correspondence_record');
        $this->addSql('ALTER TABLE correspondence_record CHANGE created_at created_at DATETIME NOT NULL, CHANGE is_released is_released TINYINT NOT NULL, CHANGE released_at released_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE correspondence_record RENAME INDEX idx_corr_created_by TO IDX_54FC9199B03A8386');
        $this->addSql('ALTER TABLE correspondence_record RENAME INDEX idx_corr_released_by TO IDX_54FC9199445947DD');
        $this->addSql('ALTER TABLE evaluation_message RENAME INDEX idx_eval_msg_sender TO IDX_95AE28F5F624B39D');
        $this->addSql('ALTER TABLE evaluation_message RENAME INDEX idx_eval_msg_period TO IDX_95AE28F53E8BB15A');
        $this->addSql('ALTER TABLE evaluation_message RENAME INDEX idx_eval_msg_replied_by TO IDX_95AE28F5D6FBBEB5');
        $this->addSql('ALTER TABLE loadslip_verification CHANGE codes codes JSON NOT NULL, CHANGE loadslip_rows loadslip_rows JSON NOT NULL, CHANGE created_at created_at DATETIME NOT NULL, CHANGE updated_at updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE question CHANGE evidence_items evidence_items JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE subject ADD term VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE superior_evaluation CHANGE verification_selections verification_selections JSON DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE correspondence_record CHANGE created_at created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE is_released is_released TINYINT DEFAULT 0 NOT NULL, CHANGE released_at released_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE INDEX idx_corr_released_at ON correspondence_record (released_at)');
        $this->addSql('ALTER TABLE correspondence_record RENAME INDEX idx_54fc9199b03a8386 TO IDX_CORR_CREATED_BY');
        $this->addSql('ALTER TABLE correspondence_record RENAME INDEX idx_54fc9199445947dd TO IDX_CORR_RELEASED_BY');
        $this->addSql('ALTER TABLE evaluation_message RENAME INDEX idx_95ae28f5f624b39d TO IDX_EVAL_MSG_SENDER');
        $this->addSql('ALTER TABLE evaluation_message RENAME INDEX idx_95ae28f53e8bb15a TO IDX_EVAL_MSG_PERIOD');
        $this->addSql('ALTER TABLE evaluation_message RENAME INDEX idx_95ae28f5d6fbbeb5 TO IDX_EVAL_MSG_REPLIED_BY');
        $this->addSql('ALTER TABLE loadslip_verification CHANGE codes codes JSON NOT NULL COMMENT \'(DC2Type:json)\', CHANGE loadslip_rows loadslip_rows JSON NOT NULL COMMENT \'(DC2Type:json)\', CHANGE created_at created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE updated_at updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE question CHANGE evidence_items evidence_items JSON DEFAULT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE subject DROP term');
        $this->addSql('ALTER TABLE superior_evaluation CHANGE verification_selections verification_selections JSON DEFAULT NULL COMMENT \'(DC2Type:json)\'');
    }
}
