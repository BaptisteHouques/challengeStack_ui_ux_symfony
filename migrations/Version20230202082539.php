<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230202082539 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE action ADD responsible_id INT NOT NULL');
        $this->addSql('ALTER TABLE action ADD CONSTRAINT FK_47CC8C92602AD315 FOREIGN KEY (responsible_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_47CC8C92602AD315 ON action (responsible_id)');
        $this->addSql('ALTER TABLE user_action DROP is_responsible');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE action DROP FOREIGN KEY FK_47CC8C92602AD315');
        $this->addSql('DROP INDEX IDX_47CC8C92602AD315 ON action');
        $this->addSql('ALTER TABLE action DROP responsible_id');
        $this->addSql('ALTER TABLE user_action ADD is_responsible TINYINT(1) NOT NULL');
    }
}
