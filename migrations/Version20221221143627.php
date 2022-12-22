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
        $this->addSql('INSERT INTO `categories`(`id`, `libelle`) VALUES (1, \'Exploitations\')');
        $this->addSql('INSERT INTO `categories`(`id`, `libelle`) VALUES (2, \'Batiments\')');
        $this->addSql('INSERT INTO `categories`(`id`, `libelle`) VALUES (3, \'Bois\')');
        $this->addSql('INSERT INTO `categories`(`id`, `libelle`) VALUES (4, \'Terrain agricole\')');
        $this->addSql('INSERT INTO `categories`(`id`, `libelle`) VALUES (5, \'Prairie\')');
        $this->addSql('INSERT INTO `biens` (`id`, `categorie_id`, `prix`, `surface`, `type`, `localisation`, `intitule`, `descriptif`, `reference`) VALUES (1, 4, \'2000.00\', \'13.00\', \'Location\', \'38500\', \'Vallons du Voironnais\', \'13 Ha de terrain\', \'38TB22187\'),(2, 4, \'1300.00\', \'10.00\', \'Location\', \'30430\', \'Situé à 15 minutes de Mende\', \'idéal pour polyculture sur 14 ha\', \'48RE11201\'),(3, 4, \'11000.00\', \'14.00\', \'Location\', \'47300\', \'PRET A USAGE sur 95 ha - PLAINE DES VOSGES \', \' A 5 min de Villeneuve-sur-Lot\', \'47.06.098\'),(4, 4, \'-1.00\', \'6.50\', \'Vente\', \'88170\', \'Vittel Dombrot : Ouest vosgien, secteur de VITTEL\', \'Terrains d\\\'environ 6,5 ha\', \'88 FB \'),(5, 4, \'156000.00\', \'12.00\', \'Vente\', \'29510\', \'Ancienne ferme équestre en ruine\', \'Terrains actuellement loués\', \'5667DB\'),(6, 5, \'113000.00\', \'11.00\', \'Vente\', \'56500\', \'Pra.iries orientées nord ouest\', \'Lot d\\\'un seul tenant\', \'765DN\'),(7, 5, \'3000.00\', \'5.50\', \'Location\', \'35200\', \'Terrain proche cours d\\\'eau\', \'Non accessible par la route, uniquement chemin d\\\'exploitation\', \'76RZDC\'),(8, 5, \'1200.00\', \'1.20\', \'Location\', \'44110\', \'Terrain avec abri\', \'Pour propriétaire équin\', \'9875RDC\'),(9, 5, \'2400.00\', \'3.40\', \'Location\', \'22700\', \'Légèrement en Pente\', \'Idéal paturage moutons\', \'Z34.345.45\'),(10, 5, \'7700.00\', \'2.00\', \'Vente\', \'64150\', \'Productions végétales\', \'La parcelle se situe dans le Béarn sur la commune de LAGOR.\', \'64.02.59\'),(11, 5, \'400000.00\', \'76.00\', \'Vente\', \'81090\', \'Prairies sur les plateaux\', \'Parcelle de terre labourable d\\\'environ 2 ha\', \'7629CA\'),(12, 5, \'15000.00\', \'1.22\', \'Vente\', \'29510\', \'Prairies en pays glazik\', \'Usage petits animaux type caprins\', \'43LM220118\'),(13, 3, \'500.00\', \'1.20\', \'Location\', \'56500\', \'Terrain classé T4\', \'cloturé et partiellement boisé\', \'65.23.876\'),(14, 3, \'800.00\', \'1.80\', \'Location\', \'35200\', \'Sapinière\', \'Sapinière en cours de bail, cherche reprise\', \'344334UJ\'),(15, 3, \'12000.00\', \'32.00\', \'Location\', \'44110\', \'Bois domainial\', \'Bois accessible avec sentiers\', \'QDSGF56\'),(16, 3, \'120000.00\', \'35.00\', \'Vente\', \'22700\', \'Idéal société de chasse\', \'Terrain boisé classé ONF\', \'313453DR\'),(17, 3, \'30000.00\', \'6.00\', \'Vente\', \'29510\', \'Bois sur pied\', \'Diverses essences sur place\', \'345E7EG\'),(18, 3, \'400000.00\', \'54.00\', \'Vente\', \'12200\', \'Secteur du Ségala-Viaur\', \'les secteurs les plus en pente sont empiérés\', \'81EL11100\'),(19, 2, \'700.00\', \'1.55\', \'Location\', \'48370\', \'Propriété Lozère\', \'Ensemble bâti avec environ 1ha55\', \'48EL11345\'),(20, 2, \'860.00\', \'1.55\', \'Location\', \'23320\', \'Propriété Creuse\', \'Dans un hameau à moins de 10 minutes d\\\'un bourg avec services et commerces, et d\\\'un village ayant un intérêt touristique sur les routes de St-Jacques-de-Compostelle.\', \'23.16.104\'),(21, 2, \'650.00\', \'6.00\', \'Location\', \'23500\', \'Propriété située dans un secteur vallonné\', \'Propriété Pyrénées-Atlantiques\', \'64.03.60\'),(22, 2, \'200000.00\', \'2.00\', \'Vente\', \'44220\', \'Bâtiments avicoles à transmettre\', \'Site avicole à transmettre sur la commune de Nort-sur-Erdre, au nord de Nantes.\', \'44 22 AN 08\'),(23, 2, \'1500000.00\', \'30.00\', \'Vente\', \'34280\', \'Propriété viticole et sa cave\', \'Au cœur de l\\\'appellation Saint-Chinian\', \'34VI6979\'),(24, 2, \'1490000.00\', \'1.90\', \'Vente\', \'34070\', \'Tourisme rural-hébergement\', \'Au nord de l\\\'Hérault, proche des axes routiers et à 45 minutes de Montpellier\', \'34AG10897\'),(25, 1, \'2000.00\', \'29.00\', \'Location\', \'34290\', \'Propriété Gard\', \'Ensemble immobilier proche d\\\'un plan d\\\'eau aménagé\', \'30VI9700\'),(26, 1, \'950.00\', \'34.00\', \'Location\', \'35200\', \'FERME 100% HERBAGERE/ ELEVAGE LAITIER\', \'Située à l\\\'orée d\\\'un bourg, à 10 minutes des services et commerces.\', \'19.07.118\'),(27, 1, \'-1.00\', \'59.00\', \'Location\', \'88340\', \'Propriété Meuse\', \'FERME DE COURUPT : Secteur Sainte-Menehould / Clermont-en-Argonne / Revigny\', \'55VS\'),(28, 1, \'173440.00\', \'17.00\', \'Vente\', \'14380\', \'Propriété Calvados\', \'JFD : Noue de Sienne (14)\', \'MQ14170356 \'),(29, 1, \'330000.00\', \'17.00\', \'Vente\', \'17200\', \'Activités Equestres, Apiculture, Chasse,\', \'Propriété Charente-Maritime\', \'17.03.017\'),(30, 1, \'-1.00\', \'87.00\', \'Vente\', \'72220\', \'Exploitation Agricole spécialisée en polyculture élevage\', \'Exploitation située dans le Sud Est de La Sarthe, entre la commune d\\\'Ecommoy (72220) et Sarcé (72327)\', \'AA 72 22 0088 R\')');
        
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
