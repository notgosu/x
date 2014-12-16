<?php

namespace backend\modules\project\models;

use backend\components\BackModel;
use kartik\builder\Form;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "company_attribute".
 *
 * @property integer $id
 * @property string $name
 * @property integer $category_id
 * @property string $value
 * @property integer $position
 *
 * @property CompanyAttributeCategory $category
 */
class CompanyAttribute extends BackModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'company_attribute';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'category_id', 'value', 'position'], 'required'],
            [['category_id', 'position'], 'integer'],
            [['value'], 'number'],
            [['name'], 'string', 'max' => 255],
	        [['id', 'name', 'category_id', 'value', 'position'], 'safe', 'on' => 'search']
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
        return $this->hasOne(CompanyAttributeCategory::className(), ['id' => 'category_id']);
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
		$query->andFilterWhere(['like', 'category_id', $this->category_id]);
		$query->andFilterWhere(['like', 'value', $this->value]);

		return $dataProvider;
	}

	public function getCategoryList(){
		return CompanyAttributeCategory::find()->all();
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
					'filter' => ArrayHelper::map($this->getCategoryList(), 'id', 'name'),
					'value' => function (self $data){
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
					'items' => ArrayHelper::map($this->getCategoryList(), 'id', 'name')
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
		return 'Властивості компанії';
	}
}
