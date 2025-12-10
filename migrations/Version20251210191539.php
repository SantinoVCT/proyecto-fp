<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251210191539 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE categoria ADD fecha_creada DATETIME DEFAULT NULL, ADD fecha_update DATETIME DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE producto ADD fecha_creada DATETIME DEFAULT NULL, ADD fecha_update DATETIME DEFAULT NULL, DROP imagen
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE usuario ADD fecha_creada DATETIME DEFAULT NULL, ADD fecha_update DATETIME DEFAULT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE categoria DROP fecha_creada, DROP fecha_update
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE producto ADD imagen VARCHAR(255) DEFAULT NULL, DROP fecha_creada, DROP fecha_update
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE usuario DROP fecha_creada, DROP fecha_update
        SQL);
    }
}
