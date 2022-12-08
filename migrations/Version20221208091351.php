<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221208091351 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avantage DROP CONSTRAINT fk_a95d71e5438d7bb9');
        $this->addSql('DROP INDEX idx_a95d71e5438d7bb9');
        $this->addSql('ALTER TABLE avantage DROP user_avantage_id');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT fk_8d93d649438d7bb9');
        $this->addSql('DROP INDEX idx_8d93d649438d7bb9');
        $this->addSql('ALTER TABLE "user" DROP user_avantage_id');
        $this->addSql('ALTER TABLE user_avantage ADD utilisateur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_avantage ADD avantage_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_avantage ALTER commentaire SET NOT NULL');
        $this->addSql('ALTER TABLE user_avantage ADD CONSTRAINT FK_49D095E5FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_avantage ADD CONSTRAINT FK_49D095E5EA96B22C FOREIGN KEY (avantage_id) REFERENCES avantage (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_49D095E5FB88E14F ON user_avantage (utilisateur_id)');
        $this->addSql('CREATE INDEX IDX_49D095E5EA96B22C ON user_avantage (avantage_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "user" ADD user_avantage_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT fk_8d93d649438d7bb9 FOREIGN KEY (user_avantage_id) REFERENCES user_avantage (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_8d93d649438d7bb9 ON "user" (user_avantage_id)');
        $this->addSql('ALTER TABLE avantage ADD user_avantage_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE avantage ADD CONSTRAINT fk_a95d71e5438d7bb9 FOREIGN KEY (user_avantage_id) REFERENCES user_avantage (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_a95d71e5438d7bb9 ON avantage (user_avantage_id)');
        $this->addSql('ALTER TABLE user_avantage DROP CONSTRAINT FK_49D095E5FB88E14F');
        $this->addSql('ALTER TABLE user_avantage DROP CONSTRAINT FK_49D095E5EA96B22C');
        $this->addSql('DROP INDEX IDX_49D095E5FB88E14F');
        $this->addSql('DROP INDEX IDX_49D095E5EA96B22C');
        $this->addSql('ALTER TABLE user_avantage DROP utilisateur_id');
        $this->addSql('ALTER TABLE user_avantage DROP avantage_id');
        $this->addSql('ALTER TABLE user_avantage ALTER commentaire DROP NOT NULL');
    }
}
