<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170628070804 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE invitation (id INT AUTO_INCREMENT NOT NULL, created_by_id INT DEFAULT NULL, code VARCHAR(255) NOT NULL, invitee_name LONGTEXT DEFAULT NULL, topic LONGTEXT DEFAULT NULL, intro LONGTEXT DEFAULT NULL, proposed_title LONGTEXT DEFAULT NULL, proposed_description LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, accepted_at DATETIME DEFAULT NULL, INDEX IDX_F11D61A2B03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE invitation ADD CONSTRAINT FK_F11D61A2B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE photo ADD invitation_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE photo ADD CONSTRAINT FK_14B78418A35D7AF0 FOREIGN KEY (invitation_id) REFERENCES invitation (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_14B78418A35D7AF0 ON photo (invitation_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE photo DROP FOREIGN KEY FK_14B78418A35D7AF0');
        $this->addSql('DROP TABLE invitation');
        $this->addSql('DROP INDEX UNIQ_14B78418A35D7AF0 ON photo');
        $this->addSql('ALTER TABLE photo DROP invitation_id');
    }
}
