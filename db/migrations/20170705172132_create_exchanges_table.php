<?php

use Phinx\Migration\AbstractMigration;

class CreateExchangesTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        // create the table
        $table = $this->table('exchanges');
        $table->addColumn('currency_from_id', 'integer', ['null' => false])
              ->addColumn('currency_to_id', 'integer', ['null' => false])
              ->addColumn('rate', 'decimal', ['precision' => 15, 'scale' => 6])
              ->addColumn('created_at', 'timestamp', ['null' => false, 'default' => 'CURRENT_TIMESTAMP']);

        $table->addForeignKey('currency_from_id', 'currencies', 'id', array('delete'=> 'CASCADE', 'update'=> 'NO_ACTION'));
        $table->addForeignKey('currency_to_id', 'currencies', 'id', array('delete'=> 'CASCADE', 'update'=> 'NO_ACTION'));

        $table->create();
    }
}
