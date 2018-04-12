<?php declare(strict_types = 1);

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180412080813 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE bike_meter_data (id INT AUTO_INCREMENT NOT NULL, meter_id INT DEFAULT NULL, value LONGTEXT NOT NULL, date_time DATETIME NOT NULL COMMENT \'(DC2Type:datetime)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime)\', INDEX IDX_1E0ABF576E15CA9E (meter_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bike_meter (id INT AUTO_INCREMENT NOT NULL, city_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, INDEX IDX_F7AC15FA8BAC62AF (city_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bike_meter_data ADD CONSTRAINT FK_1E0ABF576E15CA9E FOREIGN KEY (meter_id) REFERENCES bike_meter (id)');
        $this->addSql('ALTER TABLE bike_meter ADD CONSTRAINT FK_F7AC15FA8BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bike_meter_data DROP FOREIGN KEY FK_1E0ABF576E15CA9E');
        $this->addSql('DROP TABLE bike_meter_data');
        $this->addSql('DROP TABLE bike_meter');
    }
}
