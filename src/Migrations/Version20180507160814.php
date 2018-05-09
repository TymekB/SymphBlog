<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180507160814 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C642B8210');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D642B8210');
        $this->addSql('DROP TABLE app_admins');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C642B8210');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C642B8210 FOREIGN KEY (admin_id) REFERENCES app_users (id)');
        $this->addSql('ALTER TABLE app_users ADD user_type VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D642B8210');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D642B8210 FOREIGN KEY (admin_id) REFERENCES app_users (id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE app_admins (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(20) NOT NULL COLLATE utf8mb4_unicode_ci, email VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, password VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE app_users DROP user_type');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C642B8210');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C642B8210 FOREIGN KEY (admin_id) REFERENCES app_admins (id)');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D642B8210');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D642B8210 FOREIGN KEY (admin_id) REFERENCES app_admins (id)');
    }
}
