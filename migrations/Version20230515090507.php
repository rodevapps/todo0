<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230515090507 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE todo_item (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, todo_list_id_id INTEGER NOT NULL, name CLOB NOT NULL, done BOOLEAN NOT NULL, CONSTRAINT FK_40CA4301BB2D8F86 FOREIGN KEY (todo_list_id_id) REFERENCES todo_list (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_40CA4301BB2D8F86 ON todo_item (todo_list_id_id)');
        $this->addSql('CREATE TABLE todo_list (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, CONSTRAINT FK_1B199E079D86650F FOREIGN KEY (user_id_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_1B199E079D86650F ON todo_list (user_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE todo_item');
        $this->addSql('DROP TABLE todo_list');
    }
}
