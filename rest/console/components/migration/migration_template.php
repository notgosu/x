<?php
/**
 * This view is used by console/controllers/MigrateController.php
 * The following variables are available in this view:
 */
/* @var $className string the new migration class name */
/* @var $tableName string the new migration table name */

echo "<?php\n";
?>

use yii\db\Schema;
use yii\db\Migration;

/**
* Class <?= $className ?>
*/
class <?= $className ?> extends Migration
{
    /**
     * migration table name
     */
    public $tableName = '{{%<?= $tableName; ?>}}';

    /**
    * commands will be executed in transaction
    */
    public function safeUp()
    {
        $this->createTable(
            $this->tableName,
            [
                'id' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
                'label' => Schema::TYPE_STRING. ' NOT NULL COMMENT "Заголовок"',
                'content' => Schema::TYPE_TEXT. ' DEFAULT NULL COMMENT "Содержимое"',
                'visible' => Schema::TYPE_SMALLINT. '(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT "Отображать"',
                'position' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL DEFAULT 0 COMMENT "Позиция"',
                'created' => Schema::TYPE_DATETIME. ' NOT NULL COMMENT "Создано"',
                'modified' => Schema::TYPE_DATETIME. ' NOT NULL COMMENT "Обновлено"',
            ],
            'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB'
        );
    }

    /**
    * commands will be executed in transaction
    */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
