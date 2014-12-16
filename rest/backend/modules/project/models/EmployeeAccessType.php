<?php

namespace backend\modules\project\models;

use kartik\builder\Form;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "employee_access_type".
 *
 * @property integer $id
 * @property string $name
 * @property float $value
 * @property integer $position
 */
class EmployeeAccessType extends \backend\components\BackModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'employee_access_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'value'], 'required'],
            [['position'], 'integer'],
            [['name'], 'string', 'max' => 255],
	        [['id', 'name', 'value', 'position'], 'safe', 'on' => 'search']
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
	        'value' => 'Значення',
            'position' => 'Порядок',
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
				'name',
				'value',
				'position'
			]
			: [
				'id',
				'name',
				'value',
				'position',
				[
					'class' => \yii\grid\ActionColumn::className()
				]
			];
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
		$query->andFilterWhere(['like', 'value', $this->value]);
		$query->andFilterWhere(['like', 'position', $this->position]);

		return $dataProvider;
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
		return 'Типи доступу співробітників';
	}
}
