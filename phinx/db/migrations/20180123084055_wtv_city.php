<?php


use Phinx\Migration\AbstractMigration;

class WtvCity extends AbstractMigration
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
        $tableAdmin=$this-> table('wtv_city',['id' => true, 'primary_key' => ['id']]);
        $tableAdmin
                ->addColumn('city_code','string',['limit'=>100,'null' => false])
                ->addColumn('city_name','string',['limit'=>3000,'null' => false])
                ->addColumn('city_is_deleted','boolean',['default'=>0,'null' => false])
                ->addColumn('updated','date')
                ->addColumn('created','date')
                ->save();
    }
}
