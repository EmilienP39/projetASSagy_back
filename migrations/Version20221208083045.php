<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221208083045 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE avantage_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE equipe_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_avantage_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE avantage (id INT NOT NULL, user_avantage_id INT DEFAULT NULL, libelle VARCHAR(255) NOT NULL, points INT NOT NULL, categorie INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A95D71E5438D7BB9 ON avantage (user_avantage_id)');
        $this->addSql('CREATE TABLE equipe (id INT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, user_avantage_id INT DEFAULT NULL, equipe_id INT DEFAULT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, points INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON "user" (username)');
        $this->addSql('CREATE INDEX IDX_8D93D649438D7BB9 ON "user" (user_avantage_id)');
        $this->addSql('CREATE INDEX IDX_8D93D6496D861B89 ON "user" (equipe_id)');
        $this->addSql('CREATE TABLE user_avantage (id INT NOT NULL, commentaire TEXT DEFAULT NULL, is_valide BOOLEAN NOT NULL, created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, points INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE avantage ADD CONSTRAINT FK_A95D71E5438D7BB9 FOREIGN KEY (user_avantage_id) REFERENCES user_avantage (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D649438D7BB9 FOREIGN KEY (user_avantage_id) REFERENCES user_avantage (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D6496D861B89 FOREIGN KEY (equipe_id) REFERENCES equipe (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE avantage_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE equipe_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE user_avantage_id_seq CASCADE');
        $this->addSql('ALTER TABLE avantage DROP CONSTRAINT FK_A95D71E5438D7BB9');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D649438D7BB9');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D6496D861B89');
        $this->addSql('DROP TABLE avantage');
        $this->addSql('DROP TABLE equipe');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE user_avantage');
    }
}
