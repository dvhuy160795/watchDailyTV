<?php


use Phinx\Migration\AbstractMigration;

class UpdateFieldAddress extends AbstractMigration
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
        $fileContent = file_get_contents(__DIR__. "/update_field_address.sql");
        $sqlScript = explode(";", $fileContent);
        
        if (count($sqlScript) > 0) {
            foreach ($sqlScript as $sql) {
                $sql = trim($sql);
                $sql = trim($sql, "\n");
                if ($sql !== "") {
                    $sql .= ";";
                    $this->execute($sql);
                }
            }
        }
    }
}
