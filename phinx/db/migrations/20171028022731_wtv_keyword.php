<?php


use Phinx\Migration\AbstractMigration;

class WtvKeyword extends AbstractMigration
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
        $tableAdmin=$this-> table('wtv_keyword',['id' => false, 'primary_key' => ['keyword_id']]);
        $tableAdmin
                ->addColumn('keyword_id','integer')
                ->addColumn('keyword_code','string',['limit'=>100,'null' => false])
                ->addColumn('keyword_video_code','string',['limit'=>100,'null' => false])
                ->addColumn('keyword_list_keyword','string',['limit'=>10000,'null' => false])
                ->addColumn('created','date')
                ->save();
    }
}
