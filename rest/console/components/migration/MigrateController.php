<?php
/**
 * Author: Pavel Naumenko
 */

namespace console\components\migration;

use yii\base\Exception;

/**
 * Class MigrateController
 */
class MigrateController extends \dmstr\console\controllers\MigrateController
{
    /**
     * Generate language migration or not
     *
     * @var bool
     */
    public $lang = false;

    /**
     * Name for language migration table
     *
     * @var string
     */
    public $langTemplateName = '@console/components/migration/migration_lang_template.php';

    /**
     * Name for the migration table
     *
     * @var string
     */
    protected $_tableName = '';

    /**
     * @inheritdoc
     */
    public function options($actionId)
    {
        return array_merge(
            parent::options($actionId),
            ($actionId == 'create') ? ['templateFile', 'lang'] : [] // action create
        );
    }

    /**
     * @inheritdoc
     */
    public function actionCreate($name)
    {
        if (!preg_match('/^\w+$/', $name)) {
            throw new Exception("The migration name should contain letters, digits and/or underscore characters only.");
        }

        $nameForLang = 'm' . gmdate('ymd_His', strtotime('+10 second')) . '_' . $name. '_lang';
        $name = 'm' . gmdate('ymd_His') . '_' . $name;
        $file = \Yii::getAlias($this->migrationPath) . DIRECTORY_SEPARATOR . $name . '.php';
        $this->_tableName = $this->prompt('Enter a name for the new DB table(without prefix):');
        $this->stdout("\n");

        if ($this->confirm("Create new migration '$file'?")) {
            $content = $this->renderFile(\Yii::getAlias($this->templateFile), [
                    'className' => $name,
                    'tableName' => $this->_tableName
                ]);
            file_put_contents(\Yii::getAlias($file), $content);
            echo "New migration created successfully.\n";

            if ($this->lang) {
                $file = \Yii::getAlias($this->migrationPath) . DIRECTORY_SEPARATOR . $nameForLang . '.php';

                $content = $this->renderFile(\Yii::getAlias($this->langTemplateName), [
                        'className' => $nameForLang,
                        'tableName' => $this->_tableName
                    ]);
                file_put_contents(\Yii::getAlias($file), $content);
                echo "Language migration created successfully.\n";
            }
        }
    }
}
