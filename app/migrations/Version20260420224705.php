<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260420224705 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item DROP INDEX UNIQ_1F1B251E5DFCD4B8, ADD INDEX IDX_1F1B251E5DFCD4B8 (winner_id)');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY `FK_1F1B251E5DFCD4B8`');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E5DFCD4B8 FOREIGN KEY (winner_id) REFERENCES user (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item DROP INDEX IDX_1F1B251E5DFCD4B8, ADD UNIQUE INDEX UNIQ_1F1B251E5DFCD4B8 (winner_id)');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251E5DFCD4B8');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT `FK_1F1B251E5DFCD4B8` FOREIGN KEY (winner_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
