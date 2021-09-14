<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210127055038 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $table = $schema->createTable('currency');
        $table->addColumn('id', 'integer', array('autoincrement' => true));
        $table->addColumn('code', 'string');
        $table->addColumn('name', 'string');
        $table->addColumn('tx_fee','decimal',array('precision'=>14,'scale'=>6,'default'=>0));
        $table->addColumn('min_conf','decimal',array('precision'=>14,'scale'=>6,'default'=>0));
        $table->addColumn('frozen', 'boolean');
        $table->addColumn('disabled', 'boolean');
        $table->addColumn('delisted', 'boolean');
        $table->setPrimaryKey(array('id'));

        $table = $schema->getTable('public_trade_history');
        $table->addColumn('base_currency_id', 'integer');
        $table->addColumn('market_currency_id', 'integer');
        $table->addForeignKeyConstraint($schema->getTable('currency'),['base_currency_id'],['id']);
        $table->addForeignKeyConstraint($schema->getTable('currency'),['market_currency_id'],['id']);

    }

    public function down(Schema $schema) : void
    {
        $schema->dropTable('currency');
    }
}
