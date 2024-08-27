<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240827043725 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE questionaries ADD created_at DATE NOT NULL');
        $this->addSql('ALTER TABLE questionaries ADD updated_at DATE NOT NULL');
        $this->addSql('ALTER TABLE questionaries ALTER questions SET NOT NULL');
        $this->addSql('ALTER TABLE questionaries ALTER answers SET NOT NULL');
        $this->addSql('COMMENT ON COLUMN questionaries.created_at IS \'(DC2Type:date_immutable)\'');
        $this->addSql('COMMENT ON COLUMN questionaries.updated_at IS \'(DC2Type:date_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE questionaries DROP created_at');
        $this->addSql('ALTER TABLE questionaries DROP updated_at');
        $this->addSql('ALTER TABLE questionaries ALTER questions DROP NOT NULL');
        $this->addSql('ALTER TABLE questionaries ALTER answers DROP NOT NULL');
    }
}
