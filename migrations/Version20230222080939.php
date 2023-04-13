<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230222080939 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE avantage (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, points INT NOT NULL, categorie INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE equipe (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, is_senior TINYINT(1) NOT NULL, cotisation_base DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, equipe_id INT DEFAULT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, points INT NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), INDEX IDX_8D93D6496D861B89 (equipe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_avantage (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT DEFAULT NULL, avantage_id INT DEFAULT NULL, commentaire LONGTEXT NOT NULL, created DATETIME NOT NULL, is_valide TINYINT(1) NOT NULL, points INT NOT NULL, INDEX IDX_49D095E5FB88E14F (utilisateur_id), INDEX IDX_49D095E5EA96B22C (avantage_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D6496D861B89 FOREIGN KEY (equipe_id) REFERENCES equipe (id)');
        $this->addSql('ALTER TABLE user_avantage ADD CONSTRAINT FK_49D095E5FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE user_avantage ADD CONSTRAINT FK_49D095E5EA96B22C FOREIGN KEY (avantage_id) REFERENCES avantage (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D6496D861B89');
        $this->addSql('ALTER TABLE user_avantage DROP FOREIGN KEY FK_49D095E5FB88E14F');
        $this->addSql('ALTER TABLE user_avantage DROP FOREIGN KEY FK_49D095E5EA96B22C');
        $this->addSql('DROP TABLE avantage');
        $this->addSql('DROP TABLE equipe');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE user_avantage');
    }
}
