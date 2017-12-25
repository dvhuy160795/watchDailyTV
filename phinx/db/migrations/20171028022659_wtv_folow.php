<?php


use Phinx\Migration\AbstractMigration;

class WtvFolow extends AbstractMigration
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
        $tableAdmin=$this-> table('wtv_follow',['id' => false, 'primary_key' => ['follow_id']]);
        $tableAdmin
                ->addColumn('follow_id','integer')
                ->addColumn('follow_code','string',['limit'=>100,'null' => false])
                ->addColumn('follow_send_user_code','string',['limit'=>100,'null' => false])
                ->addColumn('follow_apply_user_code','string',['limit'=>100,'null' => false])
                ->addColumn('follow_is_deleted','boolean',['default'=>0,'null' => false])
                ->addColumn('delete','date')
                ->addColumn('created','date')
                ->save();
    }
}
