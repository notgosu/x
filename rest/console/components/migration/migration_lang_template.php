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
     * migration related table name
     */
    public $tableName = '{{%<?= $tableName; ?>}}';

    /**
     * main table name, to make constraints
     */
    public $relatedTableName = '{{%<?= $tableName.'_lang'; ?>}}';

    /**
     * commands will be executed in transaction
     */
    public function safeUp()
    {
        $this->createTable(
            $this->relatedTableName,
            [
            'l_id' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'model_id' => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
            'lang_id' => Schema::TYPE_STRING . '(5) NOT NULL',
            // examples:
            //'l_label' => Schema::TYPE_STRING. ' NOT NULL',
            //'l_announce' => Schema::TYPE_TEXT. ' NULL DEFAULT NULL',
            //'l_content' => Schema::TYPE_TEXT. ' NULL DEFAULT NULL',

            'INDEX key_model_id_lang_id (model_id, lang_id)',
            'INDEX key_model_id (model_id)',
            'INDEX key_lang_id (lang_id)',
            ],
            'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB'
        );

        $this->addForeignKey(
            'fk_<?= $tableName.'_lang'; ?>_model_id_to_main_model_id',
            $this->relatedTableName,
            'model_id',
            $this->tableName,
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_<?= $tableName.'_lang'; ?>_lang_id_to_language_id',
            $this->relatedTableName,
            'lang_id',
            '{{%language}}',
            'code',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * commands will be executed in transaction
     */
    public function safeDown()
    {
        $this->dropTable($this->relatedTableName);
    }
}
