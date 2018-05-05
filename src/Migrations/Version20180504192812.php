<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180504192812 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DA76ED395');
        $this->addSql('DROP INDEX IDX_5A8A6C8DA76ED395 ON post');
        $this->addSql('ALTER TABLE post CHANGE user_id admin_id INT NOT NULL');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D642B8210 FOREIGN KEY (admin_id) REFERENCES app_admins (id)');
        $this->addSql('CREATE INDEX IDX_5A8A6C8D642B8210 ON post (admin_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D642B8210');
        $this->addSql('DROP INDEX IDX_5A8A6C8D642B8210 ON post');
        $this->addSql('ALTER TABLE post CHANGE admin_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DA76ED395 FOREIGN KEY (user_id) REFERENCES app_users (id)');
        $this->addSql('CREATE INDEX IDX_5A8A6C8DA76ED395 ON post (user_id)');
    }
}
