<?php

namespace backend\modules\project\models;

use kartik\builder\Form;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "attack_category_value".
 *
 * @property integer $id
 * @property string $name
 * @property integer $category_id
 * @property string $value
 * @property integer $position
 *
 * @property AttackCategory $category
 */
class AttackCategoryValue extends \backend\components\BackModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'attack_category_value';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'category_id', 'value'], 'required'],
            [['category_id', 'position'], 'integer'],
            [['value'], 'number'],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Назва',
            'category_id' => 'Категорія',
            'value' => 'Значення',
            'position' => 'Порядок',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(AttackCategory::className(), ['id' => 'category_id']);
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
				'name',
				[
					'attribute' => 'category_id',
					'value' => $this->getCategory()->one()->name
				],
				'value',
				'position'
			]
			: [
				'id',
				'name',
				[
					'attribute' => 'category_id',
					'filter' => ArrayHelper::map(AttackCategory::find()->all(), 'id', 'name'),
					'value' => function (self $data) {
							return $data->getCategory()->one()->name;
						}
				],
				'value',
				'position',
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
		return
			[
				'name' => [
					'type' => Form::INPUT_TEXT,
				],
				'category_id' => [
					'type' => Form::INPUT_DROPDOWN_LIST,
					'items' => ArrayHelper::map(AttackCategory::find()->all(), 'id', 'name')
				],
				'value' => [
					'type' => Form::INPUT_TEXT,
				],
				'position' => [
					'type' => Form::INPUT_TEXT,
				],
			];

	}

	/**
	 * @return string
	 */
	public function getBreadCrumbRoot()
	{
		return 'Значення категорій атак';
	}
}
