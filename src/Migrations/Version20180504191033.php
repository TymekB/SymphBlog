<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180504191033 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D642B8210');
        $this->addSql('CREATE TABLE app_admins (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(20) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_users (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(20) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE admin');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP INDEX IDX_5A8A6C8D642B8210 ON post');
        $this->addSql('ALTER TABLE post CHANGE header header VARCHAR(100) NOT NULL, CHANGE body body VARCHAR(255) NOT NULL, CHANGE admin_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DA76ED395 FOREIGN KEY (user_id) REFERENCES app_users (id)');
        $this->addSql('CREATE INDEX IDX_5A8A6C8DA76ED395 ON post (user_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DA76ED395');
        $this->addSql('CREATE TABLE admin (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(20) NOT NULL COLLATE utf8mb4_unicode_ci, email VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, password VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(20) NOT NULL COLLATE utf8mb4_unicode_ci, email VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, password VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE app_admins');
        $this->addSql('DROP TABLE app_users');
        $this->addSql('DROP INDEX IDX_5A8A6C8DA76ED395 ON post');
        $this->addSql('ALTER TABLE post CHANGE header header VARCHAR(50) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE body body LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE user_id admin_id INT NOT NULL');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D642B8210 FOREIGN KEY (admin_id) REFERENCES admin (id)');
        $this->addSql('CREATE INDEX IDX_5A8A6C8D642B8210 ON post (admin_id)');
    }
}
