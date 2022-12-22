<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221221143627 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `admin` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, prenom VARCHAR(25) NOT NULL, nom VARCHAR(25) NOT NULL, passwords VARCHAR(400) NOT NULL, UNIQUE INDEX UNIQ_880E0D76E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE biens (id INT AUTO_INCREMENT NOT NULL, categorie_id INT NOT NULL, prix NUMERIC(11, 2) NOT NULL, surface NUMERIC(9, 2) NOT NULL, type VARCHAR(20) NOT NULL, localisation VARCHAR(10) NOT NULL, intitule VARCHAR(200) NOT NULL, descriptif VARCHAR(200) NOT NULL, reference VARCHAR(15) NOT NULL, INDEX IDX_1F9004DDBCF5E72D (categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(40) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, description VARCHAR(400) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE favoris (id INT AUTO_INCREMENT NOT NULL, mail VARCHAR(255) NOT NULL, date DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE favoriser (id INT AUTO_INCREMENT NOT NULL, favoris_id INT NOT NULL, biens_id INT NOT NULL, INDEX IDX_962146E651E8871B (favoris_id), INDEX IDX_962146E67773350C (biens_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE biens ADD CONSTRAINT FK_1F9004DDBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE favoriser ADD CONSTRAINT FK_962146E651E8871B FOREIGN KEY (favoris_id) REFERENCES favoris (id)');
        $this->addSql('ALTER TABLE favoriser ADD CONSTRAINT FK_962146E67773350C FOREIGN KEY (biens_id) REFERENCES biens (id)');
        $this->addSql('INSERT INTO `admin` (`id`, `email`, `roles`, `prenom`, `nom`, `passwords`) VALUES (1, \'etudesupfg@gmail.com\', \'[\"ROLE_SUPER_ADMIN\"]\', \'Guillaume\', \'Fontaine\', \'$2y$13$wtQJOSnVscgm6u9tz5hUheOCyz1ksciq/30E0Npn5rMVuMjSHwjPi\')');
        //Password : LQBVDMhdE7U2yAi
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE biens DROP FOREIGN KEY FK_1F9004DDBCF5E72D');
        $this->addSql('ALTER TABLE favoriser DROP FOREIGN KEY FK_962146E651E8871B');
        $this->addSql('ALTER TABLE favoriser DROP FOREIGN KEY FK_962146E67773350C');
        $this->addSql('DROP TABLE `admin`');
        $this->addSql('DROP TABLE biens');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE favoris');
        $this->addSql('DROP TABLE favoriser');
    }
}
