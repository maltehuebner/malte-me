<?php declare(strict_types = 1);

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180413171836 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE weather_data (id INT AUTO_INCREMENT NOT NULL, city_id INT DEFAULT NULL, temperatur_min DOUBLE PRECISION NOT NULL, temperatur_max DOUBLE PRECISION NOT NULL, wind_speed DOUBLE PRECISION NOT NULL, wind_direction DOUBLE PRECISION NOT NULL, clouds DOUBLE PRECISION NOT NULL, weather DOUBLE PRECISION NOT NULL, date_time DATETIME NOT NULL COMMENT \'(DC2Type:datetime)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime)\', INDEX IDX_3370691A8BAC62AF (city_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE weather_data ADD CONSTRAINT FK_3370691A8BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE weather_data');
    }
}
