<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231012084534 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sortie_utilisateur (sortie_id INT NOT NULL, utilisateur_id INT NOT NULL, INDEX IDX_2C57C50FCC72D953 (sortie_id), INDEX IDX_2C57C50FFB88E14F (utilisateur_id), PRIMARY KEY(sortie_id, utilisateur_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sortie_utilisateur ADD CONSTRAINT FK_2C57C50FCC72D953 FOREIGN KEY (sortie_id) REFERENCES sortie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sortie_utilisateur ADD CONSTRAINT FK_2C57C50FFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE inscription DROP FOREIGN KEY FK_5E90F6D69D1C3019');
        $this->addSql('ALTER TABLE inscription DROP FOREIGN KEY FK_5E90F6D6CC72D953');
        $this->addSql('DROP TABLE inscription');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE inscription (date_inscription DATE NOT NULL, participant_id INT NOT NULL, sortie_id INT NOT NULL, INDEX IDX_5E90F6D69D1C3019 (participant_id), INDEX IDX_5E90F6D6CC72D953 (sortie_id), PRIMARY KEY(date_inscription, participant_id, sortie_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE inscription ADD CONSTRAINT FK_5E90F6D69D1C3019 FOREIGN KEY (participant_id) REFERENCES utilisateur (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE inscription ADD CONSTRAINT FK_5E90F6D6CC72D953 FOREIGN KEY (sortie_id) REFERENCES sortie (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE sortie_utilisateur DROP FOREIGN KEY FK_2C57C50FCC72D953');
        $this->addSql('ALTER TABLE sortie_utilisateur DROP FOREIGN KEY FK_2C57C50FFB88E14F');
        $this->addSql('DROP TABLE sortie_utilisateur');
    }
}
