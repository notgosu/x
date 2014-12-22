<?php

namespace backend\modules\config\models;

use Yii;
use kartik\builder\Form;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "configuration".
 *
 * @property integer $id
 * @property string $config_key
 * @property string $value
 * @property string $description
 * @property integer $type
 * @property integer $preload
 * @property string $created
 * @property string $modified
 */
class Configuration extends \backend\components\BackModel
{
    /**
     * integer
     */
    const TYPE_INTEGER = 1;

    /**
     * string
     */
    const TYPE_STRING = 2;

    /**
     * text
     */
    const TYPE_TEXT = 3;

    /**
     * html
     */
    const TYPE_HTML = 4;

    /**
     * file
     */
    const TYPE_FILE = 5;

    /**
     * array
     */
    const TYPE_ARRAY = 6;

    /**
     * boolean
     */
    const TYPE_BOOLEAN = 7;

    /**
     * float
     */
    const TYPE_FLOAT = 8;

    /**
     * image
     */
    const TYPE_IMAGE = 9;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'configuration';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['config_key', 'value'], 'required'],
            [['value'], 'string'],
            [['type', 'preload'], 'default', 'value' => 1],
            [['type', 'preload'], 'integer'],
            [['created', 'modified'], 'safe'],
            [['config_key'], 'string', 'max' => 100],
            [['description'], 'string', 'max' => 250],
            [['config_key'], 'unique'],
            [
                ['id', 'config_key', 'value', 'description', 'type', 'preload', 'created', 'modified'],
                'safe',
                'on' => 'search'
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'config_key' => 'Ключ',
            'value' => 'Значення',
            'description' => 'Опис',
            'type' => 'Type',
            'preload' => 'Preload',
            'created' => 'Створено',
            'modified' => 'Модифiковано',
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created',
                'updatedAtAttribute' => 'modified',
                'value' => function () {
                        return date("Y-m-d H:i:s");
                    }
            ],
        ];
    }

    /**
     * @param bool $viewAction
     *
     * @return array
     */
    public function getViewColumns($viewAction = false)
    {
        return $viewAction
            ? [
                'id',
                'config_key',
                'value',
                'description',
                'created',
                'modified',
            ]
            : [
                [
                    'attribute' => 'id',
                    'headerOptions' => ['class' => 'col-sm-1']
                ],
                'config_key',
                'value',
                [
                    'class' => \yii\grid\ActionColumn::className()
                ]
            ];
    }

    /**
     * @return array
     */
    public function getFormRows()
    {
        return [

            'config_key' => [
                'type' => Form::INPUT_TEXT,
            ],
            'value' => [
                'type' => Form::INPUT_TEXTAREA,
                'options' => ['rows' => 2],
                'hint' => 'Дробнi записувати через "."'
            ],
            'description' => [
                'type' => Form::INPUT_TEXTAREA,
                'options' => ['rows' => 5]
            ],

        ];
    }

    /**
     * @inheritdoc
     */
    public function getColCount()
    {
        return 1;
    }

    /**
     * @return string
     */
    public function getBreadCrumbRoot()
    {
        return 'Конфiгурацiя';
    }
}
