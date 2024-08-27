<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240827041716 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE questionaries ALTER questions TYPE JSON');
        $this->addSql('ALTER TABLE questionaries ALTER answers TYPE JSON');
        $this->addSql('COMMENT ON COLUMN questionaries.questions IS \'(DC2Type:question_answer_collection_type)\'');
        $this->addSql('COMMENT ON COLUMN questionaries.answers IS \'(DC2Type:question_answer_collection_type)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE questionaries ALTER questions TYPE JSON');
        $this->addSql('ALTER TABLE questionaries ALTER answers TYPE JSON');
        $this->addSql('COMMENT ON COLUMN questionaries.questions IS NULL');
        $this->addSql('COMMENT ON COLUMN questionaries.answers IS NULL');
    }
}
