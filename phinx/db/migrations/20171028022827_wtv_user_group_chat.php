<?php


use Phinx\Migration\AbstractMigration;

class WtvUserGroupChat extends AbstractMigration
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
        $tableAdmin=$this-> table('wtv_user_group_chat',['id' => true]);
        $tableAdmin
                ->addColumn('user_group_chat_id','integer')
                ->addColumn('user_group_chat_code','string',['limit'=>100,'null' => false])
                ->addColumn('user_group_chat_group_chat_code','string',['limit'=>100,'null' => false])
                ->addColumn('user_group_chat_user_code','string',['limit'=>100,'null' => false])
                ->addColumn('user_group_chat_type_join','integer',['null' => false])
                ->addColumn('group_is_deleted','boolean',['default'=>0,'null' => false])
                ->addColumn('created','date')
                ->save();
    }
}
