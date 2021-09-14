<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210123150803 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $table = $schema->createTable('public_trade_history');
        $table->addColumn('id', 'integer', array('autoincrement' => true));
        $table->addColumn('global_trade_id', 'bigint');
        $table->addColumn('trade_id', 'bigint');
        $table->addColumn('trade_date', 'datetime');
        $table->addColumn('trade_type', 'string');
        $table->addColumn('trade_rate','decimal',array('precision'=>14,'scale'=>6,'default'=>0));
        $table->addColumn('amount','decimal',array('precision'=>14,'scale'=>6,'default'=>0));
        $table->addColumn('total','decimal',array('precision'=>14,'scale'=>6,'default'=>0));
        $table->addColumn('order_number', 'bigint');
        $table->setPrimaryKey(array('id'));
        $table->addUniqueIndex(['global_trade_id']);

    }

    public function down(Schema $schema) : void
    {
        $schema->dropTable('public_trade_history');

    }
}
