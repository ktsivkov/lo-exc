<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240320134309 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE log_line_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE log_line (id INT NOT NULL, service_name VARCHAR(255) NOT NULL, log_file VARCHAR(255) NOT NULL, date TIMESTAMP(0) WITH TIME ZONE NOT NULL, ingestion_date TIMESTAMP(0) WITH TIME ZONE NOT NULL, status_code INT NOT NULL, log TEXT NOT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE log_line_id_seq CASCADE');
        $this->addSql('DROP TABLE log_line');
    }
}
