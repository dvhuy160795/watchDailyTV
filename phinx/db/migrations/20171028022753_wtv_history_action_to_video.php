<?php


use Phinx\Migration\AbstractMigration;

class WtvHistoryActionToVideo extends AbstractMigration
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
        $tableAdmin=$this-> table('wtv_action_on_video',['id' => false, 'primary_key' => ['action_on_video_id']]);
        $tableAdmin
                ->addColumn('action_on_video_id','integer')
                ->addColumn('action_on_video_code','string',['limit'=>100,'null' => false])
                ->addColumn('action_on_video_video_code','string',['limit'=>100,'null' => false])
                ->addColumn('action_on_video_user_code','string',['limit'=>100,'null' => false])
                ->addColumn('action_on_video_action_type','integer',['default'=>0,'null' => false])
                ->addColumn('action_is_deleted','boolean',['default'=>0,'null' => false])
                ->addColumn('created','date')
                ->save();
    }
}
