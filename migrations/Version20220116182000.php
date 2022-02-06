<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220116182000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_account (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, currency VARCHAR(3) NOT NULL, amount DOUBLE PRECISION NOT NULL, INDEX IDX_253B48AEA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_account ADD CONSTRAINT FK_253B48AEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE exchange CHANGE amount_after_exchange amount_after_exchange DOUBLE PRECISION NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user_account');
        $this->addSql('ALTER TABLE exchange CHANGE amount_after_exchange amount_after_exchange INT NOT NULL');
    }
}
