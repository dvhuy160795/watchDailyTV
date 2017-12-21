<?php


use Phinx\Migration\AbstractMigration;

class WtvVideo extends AbstractMigration
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
        $tableAdmin=$this-> table('wtv_video',['id' => false, 'primary_key' => ['video_id']]);
        $tableAdmin
                ->addColumn('video_id','integer')
                ->addColumn('video_code','string',['limit'=>100,'null' => false])
                ->addColumn('video_title','string',['limit'=>100,'null' => false])
                ->addColumn('video_description','string',['limit'=>10000,'null' => false])
                ->addColumn('video_url_image_alias','string',['limit'=>300,'null' => false])
                ->addColumn('video_url_video','string',['limit'=>300,'null' => false])
                ->addColumn('video_video_type_code','string',['limit'=>100,'null' => false])
                ->addColumn('video_size','integer',['null' => false])
                ->addColumn('video_view','integer',['null' => false])
                ->addColumn('video_type_account','integer',['default'=>0,'null' => false])
                ->addColumn('created','date')
                ->save();
    }
}
