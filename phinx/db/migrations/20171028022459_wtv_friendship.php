<?php


use Phinx\Migration\AbstractMigration;

class WtvFriendship extends AbstractMigration
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
        $tableAdmin=$this-> table('wtv_friendship',['id' => true]);
        $tableAdmin
                ->addColumn('friendship_id','integer')
                ->addColumn('friendship_user_send_code','string',['limit'=>100,'null' => false])
                ->addColumn('friendship_user_apply_code','string',['limit'=>100,'null' => false])
                ->addColumn('friendship_message','string',['limit'=>300,'null' => false])
                ->addColumn('friendship_status','boolean',['default' => 0,'null' => false])
                ->addColumn('friendship_is_deleted','boolean',['default'=>0,'null' => false])
                ->addColumn('created','date')
                ->save();
    }
}
