<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260111012323 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE seance ADD titre VARCHAR(100) NOT NULL, ADD groupe VARCHAR(50) NOT NULL, ADD jour VARCHAR(20) NOT NULL, ADD heure_debut TIME NOT NULL, ADD heure_fin TIME NOT NULL, ADD salle VARCHAR(20) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE seance DROP titre, DROP groupe, DROP jour, DROP heure_debut, DROP heure_fin, DROP salle');
    }
}
