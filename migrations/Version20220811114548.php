<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220811114548 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE candidat (id INT AUTO_INCREMENT NOT NULL, offres_id INT NOT NULL, firstname VARCHAR(100) NOT NULL, lastname VARCHAR(100) NOT NULL, mobile VARCHAR(30) NOT NULL, email VARCHAR(255) NOT NULL, cv_candidat VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_6AB5B4716C83CD9F (offres_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offres (id INT AUTO_INCREMENT NOT NULL, societe_id INT NOT NULL, title VARCHAR(255) NOT NULL, type_contrat VARCHAR(100) NOT NULL, description LONGTEXT NOT NULL, profil_desc LONGTEXT NOT NULL, profil_comp LONGTEXT DEFAULT NULL, poste_desc LONGTEXT NOT NULL, poste_mission LONGTEXT DEFAULT NULL, website_offre VARCHAR(255) DEFAULT NULL, createdat DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', slug VARCHAR(255) NOT NULL, INDEX IDX_C6AC3544FCF77503 (societe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE societe (id INT AUTO_INCREMENT NOT NULL, login VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(100) NOT NULL, logo VARCHAR(255) NOT NULL, logo_color VARCHAR(50) DEFAULT NULL, city VARCHAR(255) NOT NULL, website VARCHAR(255) DEFAULT NULL, firstname_contact VARCHAR(100) DEFAULT NULL, lastname_contact VARCHAR(100) DEFAULT NULL, mail VARCHAR(255) DEFAULT NULL, mobile_contact VARCHAR(30) DEFAULT NULL, slug VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_19653DBDAA08CB10 (login), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE candidat ADD CONSTRAINT FK_6AB5B4716C83CD9F FOREIGN KEY (offres_id) REFERENCES offres (id)');
        $this->addSql('ALTER TABLE offres ADD CONSTRAINT FK_C6AC3544FCF77503 FOREIGN KEY (societe_id) REFERENCES societe (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE candidat DROP FOREIGN KEY FK_6AB5B4716C83CD9F');
        $this->addSql('ALTER TABLE offres DROP FOREIGN KEY FK_C6AC3544FCF77503');
        $this->addSql('DROP TABLE candidat');
        $this->addSql('DROP TABLE offres');
        $this->addSql('DROP TABLE societe');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
