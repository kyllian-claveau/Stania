<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240517195645 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bet (id INT AUTO_INCREMENT NOT NULL, ticket_id INT NOT NULL, user_id INT NOT NULL, team VARCHAR(255) NOT NULL, odds DOUBLE PRECISION NOT NULL, match_id INT NOT NULL, match_name VARCHAR(255) NOT NULL, INDEX IDX_FBF0EC9B700047D2 (ticket_id), INDEX IDX_FBF0EC9BA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bet_ticket (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, amount_bet DOUBLE PRECISION NOT NULL, potential_win DOUBLE PRECISION NOT NULL, total_odds DOUBLE PRECISION NOT NULL, INDEX IDX_FB8972E4A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comments (id INT AUTO_INCREMENT NOT NULL, party_id INT NOT NULL, comment VARCHAR(255) NOT NULL, minute INT NOT NULL, INDEX IDX_5F9E962A213C1059 (party_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE party (id INT AUTO_INCREMENT NOT NULL, team_home_id INT DEFAULT NULL, team_away_id INT DEFAULT NULL, date DATE NOT NULL, time TIME NOT NULL, duration INT NOT NULL, team_home_odds NUMERIC(5, 2) NOT NULL, team_away_odds NUMERIC(5, 2) NOT NULL, draw_odds NUMERIC(5, 2) NOT NULL, weather VARCHAR(255) NOT NULL, status VARCHAR(255) DEFAULT NULL, home_score INT DEFAULT NULL, away_score INT DEFAULT NULL, INDEX IDX_89954EE0D7B4B9AB (team_home_id), INDEX IDX_89954EE0729679A8 (team_away_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE player (id INT AUTO_INCREMENT NOT NULL, team_id INT NOT NULL, name VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, number INT NOT NULL, player_filename VARCHAR(180) NOT NULL, role VARCHAR(255) NOT NULL, INDEX IDX_98197A65296CD8AE (team_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sport (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE team (id INT AUTO_INCREMENT NOT NULL, sport_id INT NOT NULL, name VARCHAR(255) NOT NULL, team_filename VARCHAR(180) NOT NULL, INDEX IDX_C4E0A61FAC78BCF8 (sport_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, roles JSON NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bet ADD CONSTRAINT FK_FBF0EC9B700047D2 FOREIGN KEY (ticket_id) REFERENCES bet_ticket (id)');
        $this->addSql('ALTER TABLE bet ADD CONSTRAINT FK_FBF0EC9BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE bet_ticket ADD CONSTRAINT FK_FB8972E4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A213C1059 FOREIGN KEY (party_id) REFERENCES party (id)');
        $this->addSql('ALTER TABLE party ADD CONSTRAINT FK_89954EE0D7B4B9AB FOREIGN KEY (team_home_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE party ADD CONSTRAINT FK_89954EE0729679A8 FOREIGN KEY (team_away_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE player ADD CONSTRAINT FK_98197A65296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61FAC78BCF8 FOREIGN KEY (sport_id) REFERENCES sport (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bet DROP FOREIGN KEY FK_FBF0EC9B700047D2');
        $this->addSql('ALTER TABLE bet DROP FOREIGN KEY FK_FBF0EC9BA76ED395');
        $this->addSql('ALTER TABLE bet_ticket DROP FOREIGN KEY FK_FB8972E4A76ED395');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A213C1059');
        $this->addSql('ALTER TABLE party DROP FOREIGN KEY FK_89954EE0D7B4B9AB');
        $this->addSql('ALTER TABLE party DROP FOREIGN KEY FK_89954EE0729679A8');
        $this->addSql('ALTER TABLE player DROP FOREIGN KEY FK_98197A65296CD8AE');
        $this->addSql('ALTER TABLE team DROP FOREIGN KEY FK_C4E0A61FAC78BCF8');
        $this->addSql('DROP TABLE bet');
        $this->addSql('DROP TABLE bet_ticket');
        $this->addSql('DROP TABLE comments');
        $this->addSql('DROP TABLE party');
        $this->addSql('DROP TABLE player');
        $this->addSql('DROP TABLE sport');
        $this->addSql('DROP TABLE team');
        $this->addSql('DROP TABLE user');
    }
}
