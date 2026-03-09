<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260309195132 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE codigo_pedido ADD cliente_id INT DEFAULT NULL, ADD fecha DATE DEFAULT NULL, ADD estado INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE codigo_pedido ADD CONSTRAINT FK_BA0EB922DE734E51 FOREIGN KEY (cliente_id) REFERENCES usuario (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_BA0EB922DE734E51 ON codigo_pedido (cliente_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pedidos CHANGE usuario_id usuario_id INT DEFAULT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE codigo_pedido DROP FOREIGN KEY FK_BA0EB922DE734E51
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_BA0EB922DE734E51 ON codigo_pedido
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE codigo_pedido DROP cliente_id, DROP fecha, DROP estado
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE pedidos CHANGE usuario_id usuario_id INT NOT NULL
        SQL);
    }
}
