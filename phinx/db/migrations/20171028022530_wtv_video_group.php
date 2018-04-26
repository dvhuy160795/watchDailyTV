<?php


use Phinx\Migration\AbstractMigration;

class WtvVideoGroup extends AbstractMigration
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
        $tableAdmin=$this-> table('wtv_video_group',['id' => true]);
        $tableAdmin
                ->addColumn('video_group_id','integer')
                ->addColumn('video_group_video_code','string',['limit'=>100,'null' => false])
                ->addColumn('video_group_group_code','string',['limit'=>100,'null' => false])
                ->addColumn('video_group_status','boolean',['default'=>0,'null' => false])
                ->addColumn('video_is_deleted','boolean',['default'=>0,'null' => false])
                ->addColumn('created','date')
                ->save();
    }
}
