<?php
/**
 * Author: Pavel Naumenko
 */

namespace backend\components\gii\model;

/**
 * Class Generator
 *
 * @package backend\components\gii\model
 */
class Generator extends \yii\gii\generators\model\Generator
{
    public $baseClass = 'backend\components\BackModel';

    /**
     * Generate columns for grid and detail view
     *
     * @param $isView
     * @param \yii\db\ColumnSchema $column
     *
     * @return null|string
     */
    public function generateViewColumns($isView, $column)
    {
        $row = null;

        $name = $column->name;
        if ($isView) {
            switch ($column) {
                case $column->name == 'published':
                    $row = "'published:boolean',";
                    break;
                case $column->name == 'visible':
                    $row = "'visible:boolean',";
                    break;
                case $column->type === 'boolean':
                    $row = "'{$name}:boolean',";
                    break;
                default:
                    $row = "'{$name}',";
                    break;
            }
        } else {
            switch ($column) {
                case ($column->autoIncrement):
                case $column->name == 'position':
                    $row = "[
                    'attribute' => '{$name}',
                    'headerOptions' => ['class' => 'col-sm-1']
                ],";
                    break;
                case (($column->type == 'integer') || ($column->type == 'string' && $column->size && $column->size <= 255)):
                    $row = "'{$name}',";
                    break;
                default:
                    break;
            }
        }
        return $row ? $row . "\n" : $row;
    }

    /**
     * Generate form config row
     *
     * @param \yii\db\ColumnSchema $column
     *
     * @return null|string
     */
    public static function generateFormRow($column)
    {
        $row = null;

        $name = $column->name;
        switch ($column) {
            case ($name === 'id'):
                $row = null;
                break;
            case ($column->type === 'boolean'):
            case ($name === 'published'):
            case ($name === 'visible'):
                $row = "'{$name}' => [
                'type' => Form::INPUT_CHECKBOX,
            ],";
                break;
            case ($name === 'position'):
                $row = "'position' => [
                'type' => Form::INPUT_TEXT,
            ],";
                break;
            case ($column->type === 'integer'):
                $row = "'{$name}' => [
                'type' => Form::INPUT_TEXT,
            ],";
                break;
            case ($column->type === 'string' && $column->dbType === 'date'):
                $row = "'{$name}' => [
                    'type' => Form::INPUT_WIDGET,
                    'widgetClass' => DatePicker::className(),
                    'convertFormat' => true,
                    'options' => [
                        'pluginOptions' => [
                            'format' => 'yyyy-mm-dd',
                        ],
                    ],
                ],";
                break;
            case ($column->dbType === 'text'):
                $row = "'{$name}' => [
                'type' => Form::INPUT_TEXTAREA,
                'options' => ['rows' => 5]
            ],";
                break;
            default:
                $row = "'{$name}' => [
                'type' => Form::INPUT_TEXT,
            ],";
                break;
        }
        return $row . "\n";
    }
}
