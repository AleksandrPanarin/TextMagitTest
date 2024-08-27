<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240827165651 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE questionaries_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE questionaries (id INT NOT NULL, full_name VARCHAR(255) NOT NULL, uuid VARCHAR(255) NOT NULL, multiple_choice_questions JSON NOT NULL, questions_with_answers_user JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN questionaries.multiple_choice_questions IS \'(DC2Type:question_with_answer_collection_type)\'');
        $this->addSql('COMMENT ON COLUMN questionaries.questions_with_answers_user IS \'(DC2Type:question_with_answer_collection_type)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE questionaries_id_seq CASCADE');
        $this->addSql('DROP TABLE questionaries');
    }
}
