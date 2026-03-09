<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260305080807 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE anuncio (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, imagen VARCHAR(255) DEFAULT NULL, activo TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE codigo_pedido (id INT AUTO_INCREMENT NOT NULL, codigo INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pedidos ADD codigo_pedido_relacion_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pedidos ADD CONSTRAINT FK_6716CCAADDDDFE34 FOREIGN KEY (codigo_pedido_relacion_id) REFERENCES codigo_pedido (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_6716CCAADDDDFE34 ON pedidos (codigo_pedido_relacion_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE producto ADD destacado TINYINT(1) DEFAULT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE pedidos DROP FOREIGN KEY FK_6716CCAADDDDFE34
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE anuncio
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE codigo_pedido
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_6716CCAADDDDFE34 ON pedidos
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pedidos DROP codigo_pedido_relacion_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE producto DROP destacado
        SQL);
    }
}
