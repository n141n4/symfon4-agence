<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200823154305 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE property_option (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE property_option_property (property_option_id INT NOT NULL, property_id INT NOT NULL, INDEX IDX_AC3E6DB8D62B1FD2 (property_option_id), INDEX IDX_AC3E6DB8549213EC (property_id), PRIMARY KEY(property_option_id, property_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE property_option_property ADD CONSTRAINT FK_AC3E6DB8D62B1FD2 FOREIGN KEY (property_option_id) REFERENCES property_option (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE property_option_property ADD CONSTRAINT FK_AC3E6DB8549213EC FOREIGN KEY (property_id) REFERENCES property (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE property_option_property DROP FOREIGN KEY FK_AC3E6DB8D62B1FD2');
        $this->addSql('DROP TABLE property_option');
        $this->addSql('DROP TABLE property_option_property');
    }
}
