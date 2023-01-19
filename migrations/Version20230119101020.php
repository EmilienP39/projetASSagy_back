<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230119101020 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE etat_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE commentaire_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE ticket_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE avantage_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE equipe_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_avantage_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE avantage (id INT NOT NULL, libelle VARCHAR(255) NOT NULL, points INT NOT NULL, categorie INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE equipe (id INT NOT NULL, nom VARCHAR(255) NOT NULL, is_senior BOOLEAN NOT NULL, cotisation_base DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE user_avantage (id INT NOT NULL, utilisateur_id INT DEFAULT NULL, avantage_id INT DEFAULT NULL, commentaire TEXT NOT NULL, created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, is_valide BOOLEAN NOT NULL, points INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_49D095E5FB88E14F ON user_avantage (utilisateur_id)');
        $this->addSql('CREATE INDEX IDX_49D095E5EA96B22C ON user_avantage (avantage_id)');
        $this->addSql('ALTER TABLE user_avantage ADD CONSTRAINT FK_49D095E5FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_avantage ADD CONSTRAINT FK_49D095E5EA96B22C FOREIGN KEY (avantage_id) REFERENCES avantage (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ticket DROP CONSTRAINT fk_97a0ada3d5e86ff');
        $this->addSql('ALTER TABLE ticket_user DROP CONSTRAINT fk_bf48c371700047d2');
        $this->addSql('ALTER TABLE ticket_user DROP CONSTRAINT fk_bf48c371a76ed395');
        $this->addSql('ALTER TABLE commentaire DROP CONSTRAINT fk_67f068bc700047d2');
        $this->addSql('ALTER TABLE commentaire DROP CONSTRAINT fk_67f068bcfb88e14f');
        $this->addSql('DROP TABLE etat');
        $this->addSql('DROP TABLE ticket');
        $this->addSql('DROP TABLE ticket_user');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP INDEX uniq_8d93d649e7927c74');
        $this->addSql('ALTER TABLE "user" ADD equipe_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD points INT NOT NULL');
        $this->addSql('ALTER TABLE "user" ALTER nom TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE "user" ALTER prenom TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE "user" RENAME COLUMN email TO username');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D6496D861B89 FOREIGN KEY (equipe_id) REFERENCES equipe (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON "user" (username)');
        $this->addSql('CREATE INDEX IDX_8D93D6496D861B89 ON "user" (equipe_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D6496D861B89');
        $this->addSql('DROP SEQUENCE avantage_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE equipe_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE user_avantage_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE etat_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE commentaire_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE ticket_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE etat (id INT NOT NULL, libelle VARCHAR(50) NOT NULL, ordre INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE ticket (id INT NOT NULL, etat_id INT NOT NULL, mail_expediteur VARCHAR(255) NOT NULL, mail_destinataire JSON NOT NULL, objet VARCHAR(255) NOT NULL, description TEXT NOT NULL, date_creation TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, date_limite DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_97a0ada3d5e86ff ON ticket (etat_id)');
        $this->addSql('CREATE TABLE ticket_user (ticket_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(ticket_id, user_id))');
        $this->addSql('CREATE INDEX idx_bf48c371a76ed395 ON ticket_user (user_id)');
        $this->addSql('CREATE INDEX idx_bf48c371700047d2 ON ticket_user (ticket_id)');
        $this->addSql('CREATE TABLE commentaire (id INT NOT NULL, ticket_id INT DEFAULT NULL, utilisateur_id INT DEFAULT NULL, contenu TEXT NOT NULL, created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_67f068bcfb88e14f ON commentaire (utilisateur_id)');
        $this->addSql('CREATE INDEX idx_67f068bc700047d2 ON commentaire (ticket_id)');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT fk_97a0ada3d5e86ff FOREIGN KEY (etat_id) REFERENCES etat (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ticket_user ADD CONSTRAINT fk_bf48c371700047d2 FOREIGN KEY (ticket_id) REFERENCES ticket (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ticket_user ADD CONSTRAINT fk_bf48c371a76ed395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT fk_67f068bc700047d2 FOREIGN KEY (ticket_id) REFERENCES ticket (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT fk_67f068bcfb88e14f FOREIGN KEY (utilisateur_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_avantage DROP CONSTRAINT FK_49D095E5FB88E14F');
        $this->addSql('ALTER TABLE user_avantage DROP CONSTRAINT FK_49D095E5EA96B22C');
        $this->addSql('DROP TABLE avantage');
        $this->addSql('DROP TABLE equipe');
        $this->addSql('DROP TABLE user_avantage');
        $this->addSql('DROP INDEX UNIQ_8D93D649F85E0677');
        $this->addSql('DROP INDEX IDX_8D93D6496D861B89');
        $this->addSql('ALTER TABLE "user" DROP equipe_id');
        $this->addSql('ALTER TABLE "user" DROP points');
        $this->addSql('ALTER TABLE "user" ALTER nom TYPE VARCHAR(50)');
        $this->addSql('ALTER TABLE "user" ALTER prenom TYPE VARCHAR(50)');
        $this->addSql('ALTER TABLE "user" RENAME COLUMN username TO email');
        $this->addSql('CREATE UNIQUE INDEX uniq_8d93d649e7927c74 ON "user" (email)');
    }
}
