<?php

namespace backend\modules\project\models;

use kartik\builder\Form;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * This is the model class for table "attack".
 *
 * @property integer $id
 * @property string $name
 * @property integer $object_type_id
 * @property integer $attack_sum
 * @property string $tech_parameter
 *
 * @property ObjectType $objectType
 */
class Attack extends \backend\components\BackModel
{
	/**
	 * @var array
	 */
	public $additionalAttributes = array();


	public function afterFind(){
		parent::afterFind();

		$this->getAttackAdditionalAttrs();
	}

	/**
	 * @inheritdoc
	 */
	public function afterSave($insert, $changedAttributes){
		parent::afterSave($insert, $changedAttributes);

		AttackCategoryValueToAttack::deleteAll('attack_id=:cid', [':cid' => $this->id]);
		foreach ($this->additionalAttributes as $attackId => $attrId){
			$value = new AttackCategoryValueToAttack();
			$value->attack_id = $this->id;
			$value->attack_category_id = $attackId;
			$value->attack_value_id = $attrId;
			$value->save(false);

		}
	}

	public function getAttackAdditionalAttrs(){
		$additionalAttrs = AttackCategoryValueToAttack::find()->where('attack_id=:cid', [':cid' => $this->id])->all();
		foreach ($additionalAttrs as $attr){
			$this->additionalAttributes[$attr->attack_category_id] = $attr->attack_value_id;
		}
	}

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'attack';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'object_type_id', 'attack_sum', 'tech_parameter'], 'required'],
            [['object_type_id', 'attack_sum'], 'integer'],
            [['tech_parameter'], 'number'],
            [['name'], 'string', 'max' => 255],
	        ['additionalAttributes', 'safe'],
	        [['id', 'name', 'object_type_id', 'attack_sum', 'tech_parameter'], 'safe', 'on' => 'search']
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
            'object_type_id' => 'Тип об`єкту',
            'attack_sum' => 'Витрати на атаку',
            'tech_parameter' => 'Технічний параметр',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getObjectType()
    {
        return $this->hasOne(ObjectType::className(), ['id' => 'object_type_id']);
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
					'attribute' => 'object_type_id',
					'value' => $this->getObjectType()->one()->name
				],

				'attack_sum',
				'tech_parameter'
			]
			: [
				'id',
				'name',
				[
					'attribute' => 'object_type_id',
					'filter' => ArrayHelper::map(ObjectType::find()->all(), 'id', 'name'),
					'value' => function (self $data) {
							return $data->getObjectType()->one()->name;
						}
				],
				'attack_sum',
				'tech_parameter',
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
				'object_type_id' => [
					'type' => Form::INPUT_DROPDOWN_LIST,
					'items' => ArrayHelper::map(ObjectType::find()->all(), 'id', 'name')
				],
				'additionalAttributes[]' => [
					'type' => Form::INPUT_RAW,
					'value' => function (self $data) {
							return $data->getAdditionalAttrs();
						}
				],
				'attack_sum' => [
					'type' => Form::INPUT_TEXT,
				],
				'tech_parameter' => [
					'type' => Form::INPUT_TEXT,
				],
			];

	}

	/**
	 * @return string
	 */
	public function getAdditionalAttrs(){
		$categories = AttackCategory::find()->orderBy('position')->all();
		$values = '';
		foreach ($categories as $cat){
			if ($cat->getAttackCategoryValues()->count()){
				$values .= Html::label($cat->name, 'additionalAttributes['.$cat->id.']');
				$values .= Html::activeDropDownList(
					$this,
					'additionalAttributes['.$cat->id.']',
					ArrayHelper::map($cat->getAttackCategoryValues()->all(), 'id', 'name'),
					['class' => 'form-control']
				);
				$values .= Html::tag('div', '', ['class' => 'help-block']);
			}

		}

		return $values;
	}

	/**
	 * @param $params
	 *
	 * @return ActiveDataProvider
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
		$query->andFilterWhere(['object_type_id' => $this->object_type_id]);
		$query->andFilterWhere(['like', 'name', $this->name]);
		$query->andFilterWhere(['like', 'attack_sum', $this->attack_sum]);
		$query->andFilterWhere(['like', 'tech_parameter', $this->tech_parameter]);

		return $dataProvider;
	}

	/**
	 * @return string
	 */
	public function getBreadCrumbRoot()
	{
		return 'Атаки';
	}
}
