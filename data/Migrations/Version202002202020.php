<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * A migration class. It either upgrades the databases schema (moves it to new state)
 * or downgrades it to the previous state.
 */
class Version202002202020 extends AbstractMigration
{

    /**
     * Returns the description of this migration.
     */
    public function getDescription()
    {
        $description = 'This is the initial migration which creates the required tables.';
        return $description;
    }

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // Create 'category' table
        $table = $schema->createTable('category');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('name', 'string', ['notnull' => true, 'length' => 128]);
        $table->addColumn('status', 'integer', ['notnull' => true]);
        $table->addColumn('created_at', 'datetime', ['notnull' => true]);
        $table->setPrimaryKey(['id']);
        $table->addOption('engine', 'InnoDB');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropTable('category');
    }

}
