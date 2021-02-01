<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210125101720 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE event ADD picture VARCHAR(255) NOT NULL, DROP pic');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE event ADD pic TINYINT(1) NOT NULL, DROP picture');
    }
}
