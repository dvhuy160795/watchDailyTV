<?php


use Phinx\Migration\AbstractMigration;

class WtvAttachment extends AbstractMigration
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
        $tableAdmin=$this-> table('wtv_attachment',['id' => true, 'primary_key' => ['id']]);
        $tableAdmin
                ->addColumn('attachment_name','string',['limit'=>100,'null' => false])
                ->addColumn('attachment_url_source','string',['limit'=>3000,'null' => false])
                ->addColumn('attachment_file_name','string',['limit'=>100,'null' => false])
                ->addColumn('attachment_size','integer',['null' => false])
                ->addColumn('attachment_type','integer',['null' => false])
                ->addColumn('attachment_type_upload','string',['limit'=>100,'null' => false])
                ->addColumn('attachment_type_upload_code','integer',['default'=>0,'null' => false])
                ->addColumn('attachment_is_deleted','boolean',['default'=>0,'null' => false])
                ->addColumn('created','date')
                ->save();
    }
}
