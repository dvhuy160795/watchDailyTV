<?php


use Phinx\Migration\AbstractMigration;

class WtvMessage extends AbstractMigration
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
        $tableAdmin=$this-> table('wtv_message',['id' => false, 'primary_key' => ['message_id']]);
        $tableAdmin
                ->addColumn('message_id','integer')
                ->addColumn('message_code','string',['limit'=>100,'null' => false])
                ->addColumn('message_group_chat_code','string',['limit'=>100,'null' => false])
                ->addColumn('message_user_code','string',['limit'=>100,'null' => false])
                ->addColumn('message_content','string',['limit'=>1000,'null' => false])
                ->addColumn('created','date')
                ->addColumn('update','date')
                ->addColumn('delete','date')
                ->save();
    }
}
