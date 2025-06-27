<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Migracja: tworzy tabelę employees zgodną z PostgreSQL.
 */
final class Version20240301124326 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add employees table (PostgreSQL compatible)';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE IF NOT EXISTS employees (
                id SERIAL PRIMARY KEY,
                first_name VARCHAR(50) NOT NULL,
                last_name VARCHAR(50) NOT NULL,
                email VARCHAR(255) NOT NULL,
                phone_number VARCHAR(20),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                company_id INTEGER,
                CONSTRAINT unique_employees_phone UNIQUE (company_id, phone_number),
                CONSTRAINT unique_employees_email UNIQUE (company_id, email),
                CONSTRAINT fk_employees_company FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE
            )
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS employees');
    }
}
