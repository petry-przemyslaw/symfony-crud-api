<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Migracja: tworzy tabelę companies zgodną z PostgreSQL.
 */
final class Version20240301124305 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add companies table (PostgreSQL compatible)';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE IF NOT EXISTS companies (
                id SERIAL PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                city VARCHAR(255) NOT NULL,
                address VARCHAR(255) NOT NULL,
                nip VARCHAR(15) NOT NULL UNIQUE,
                postcode VARCHAR(10) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS companies');
    }
}
