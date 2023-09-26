<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230924095208 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creation of base tables such as Assesment, Inspector and Job with their properties.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE assessment (id INT AUTO_INCREMENT NOT NULL, inspector_id INT NOT NULL, job_id INT NOT NULL, status VARCHAR(255) NOT NULL, assigned_date DATE NOT NULL, delivery_date DATE NOT NULL, note LONGTEXT DEFAULT NULL, INDEX IDX_F7523D70D0E3F35F (inspector_id), INDEX IDX_F7523D70BE04EA9 (job_id), created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE inspector (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, location VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE job (id INT AUTO_INCREMENT NOT NULL, description VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE assessment ADD CONSTRAINT FK_F7523D70D0E3F35F FOREIGN KEY (inspector_id) REFERENCES inspector (id)');
        $this->addSql('ALTER TABLE assessment ADD CONSTRAINT FK_F7523D70BE04EA9 FOREIGN KEY (job_id) REFERENCES job (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE assessment DROP FOREIGN KEY FK_F7523D70D0E3F35F');
        $this->addSql('ALTER TABLE assessment DROP FOREIGN KEY FK_F7523D70BE04EA9');
        $this->addSql('DROP TABLE assessment');
        $this->addSql('DROP TABLE inspector');
        $this->addSql('DROP TABLE job');
    }
}
