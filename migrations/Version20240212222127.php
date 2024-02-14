<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240212222127 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categories DROP FOREIGN KEY FK_3AF34668727ACA70');
        $this->addSql('ALTER TABLE categories ADD CONSTRAINT FK_3AF34668727ACA70 FOREIGN KEY (parent_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE categories_posts DROP FOREIGN KEY FK_8C5EAFB7D5E258C5');
        $this->addSql('ALTER TABLE categories_posts DROP FOREIGN KEY FK_8C5EAFB7A21214B7');
        $this->addSql('ALTER TABLE categories_posts ADD CONSTRAINT FK_8C5EAFB7D5E258C5 FOREIGN KEY (posts_id) REFERENCES posts (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE categories_posts ADD CONSTRAINT FK_8C5EAFB7A21214B7 FOREIGN KEY (categories_id) REFERENCES categories (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962AD5E258C5');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A67B3B43D');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A727ACA70');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AD5E258C5 FOREIGN KEY (posts_id) REFERENCES posts (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A67B3B43D FOREIGN KEY (users_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A727ACA70 FOREIGN KEY (parent_id) REFERENCES comments (id)');
        $this->addSql('ALTER TABLE posts DROP FOREIGN KEY FK_885DBAFA67B3B43D');
        $this->addSql('ALTER TABLE posts ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE posts ADD CONSTRAINT FK_885DBAFA67B3B43D FOREIGN KEY (users_id) REFERENCES users (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categories DROP FOREIGN KEY FK_3AF34668727ACA70');
        $this->addSql('ALTER TABLE categories ADD CONSTRAINT FK_3AF34668727ACA70 FOREIGN KEY (parent_id) REFERENCES categories (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE categories_posts DROP FOREIGN KEY FK_8C5EAFB7A21214B7');
        $this->addSql('ALTER TABLE categories_posts DROP FOREIGN KEY FK_8C5EAFB7D5E258C5');
        $this->addSql('ALTER TABLE categories_posts ADD CONSTRAINT FK_8C5EAFB7A21214B7 FOREIGN KEY (categories_id) REFERENCES categories (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE categories_posts ADD CONSTRAINT FK_8C5EAFB7D5E258C5 FOREIGN KEY (posts_id) REFERENCES posts (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A727ACA70');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A67B3B43D');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962AD5E258C5');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A727ACA70 FOREIGN KEY (parent_id) REFERENCES comments (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A67B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AD5E258C5 FOREIGN KEY (posts_id) REFERENCES posts (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE posts DROP FOREIGN KEY FK_885DBAFA67B3B43D');
        $this->addSql('ALTER TABLE posts DROP created_at');
        $this->addSql('ALTER TABLE posts ADD CONSTRAINT FK_885DBAFA67B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON UPDATE CASCADE ON DELETE CASCADE');
    }
}
