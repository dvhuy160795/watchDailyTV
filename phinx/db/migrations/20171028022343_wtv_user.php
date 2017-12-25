<?php


use Phinx\Migration\AbstractMigration;

class WtvUser extends AbstractMigration
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
        $tableAdmin=$this-> table('wtv_user',['id' => false, 'primary_key' => ['user_id']]);
        $tableAdmin
                ->addColumn('user_id','integer')
                ->addColumn('user_code','string',['limit'=>100,'null' => false])
                ->addColumn('user_first_name','string',['limit'=>100,'null' => false])
                ->addColumn('user_last_name','string',['limit'=>300,'null' => false])
                ->addColumn('user_full_name','string',['limit'=>300,'null' => false])
                ->addColumn('user_login_name','string',['limit'=>300,'null' => false])
                ->addColumn('user_login_pass','string',['limit'=>300,'null' => false])
                ->addColumn('user_email','string',['limit'=>300,'null' => false])
                ->addColumn('user_phone','string',['limit'=>300,'null' => false])
                ->addColumn('user_city','string',['limit'=>300,'null' => false])
                ->addColumn('user_district','string',['limit'=>300,'null' => false])
                ->addColumn('user_address','string',['limit'=>300,'null' => false])
                ->addColumn('user_birthday','date',['null' => false])
                ->addColumn('user_url_image_alias','string',['limit'=>300,'null' => false])
                ->addColumn('user_url_background','string',['limit'=>300,'null' => false])
                ->addColumn('user_jog_present','string',['limit'=>300,'null' => false])
                ->addColumn('user_type_account','integer',['default'=>0,'null' => false])
                ->addColumn('user_is_deleted','boolean',['default'=>0,'null' => false])
                ->addColumn('created','date')
                ->save();
    }
}
