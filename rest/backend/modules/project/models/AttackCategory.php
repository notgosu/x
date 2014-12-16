<?php

namespace backend\modules\project\models;

use kartik\builder\Form;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "attack_category".
 *
 * @property integer $id
 * @property string $name
 * @property integer $position
 *
 * @property AttackCategoryValue[] $attackCategoryValues
 */
class AttackCategory extends \backend\components\BackModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'attack_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['position'], 'integer'],
            [['name'], 'string', 'max' => 255],
	        [['id', 'name', 'position'], 'safe', 'on' => 'search']
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
            'position' => 'Порядок',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttackCategoryValues()
    {
        return $this->hasMany(AttackCategoryValue::className(), ['category_id' => 'id']);
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
				'position'
			]
			: [
				'id',
				'name',
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
		return 'Категорії атак';
	}

	/**
	 * @inheritdoc
	 */
	public function search($params)
	{
		$query = static::find();
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		if (!empty($params)){
			$this->load($params);
		}

		$query->andFilterWhere(['id' => $this->id]);
		$query->andFilterWhere(['like', 'name', $this->name]);
		$query->andFilterWhere(['like', 'position', $this->position]);

		return $dataProvider;
	}
}
