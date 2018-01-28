<?php


use Phinx\Migration\AbstractMigration;

class WtvDistrict extends AbstractMigration
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
        $tableAdmin=$this-> table('wtv_district',['id' => false, 'primary_key' => ['district_code']]);
        $tableAdmin
                ->addColumn('district_code','string',['limit'=>100,'null' => false])
                ->addColumn('district_name','string',['limit'=>3000,'null' => false])
                ->addColumn('district_type','string',['limit'=>3000,'null' => false])
                ->addColumn('district_city_code','string',['limit'=>100,'null' => false])
                ->save();
    }
}
