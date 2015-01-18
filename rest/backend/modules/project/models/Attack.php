<?php

namespace backend\modules\project\models;

use kartik\builder\Form;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "attack".
 *
 * @property integer $id
 * @property string $name
 * @property integer $object_type_id
 * @property integer $access_type_id
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
            [['name', 'object_type_id', 'tech_parameter'], 'required'],
            [['object_type_id', 'access_type_id'], 'integer'],
            [['tech_parameter'], 'number'],
            [['name'], 'string', 'max' => 255],
	        ['additionalAttributes', 'safe'],
	        [['id', 'name', 'object_type_id', 'tech_parameter'], 'safe', 'on' => 'search']
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
            'access_type_id' => 'Тип доступу',
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
     * @return \yii\db\ActiveQuery
     */
    public function getAccessType()
    {
        return $this->hasOne(EmployeeAccessType::className(), ['id' => 'access_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryValueToAttack()
    {
        return $this->hasMany(AttackCategoryValueToAttack::className(), ['attack_id' => 'id']);
    }

	/**
	 * @param bool $viewAction
	 *
	 * @return array
	 */
	public function getViewColumns($viewAction = false)
	{

        $indexAttributtes = [
            'id',
            'name',
            [
                'attribute' => 'object_type_id',
                'filter' => ArrayHelper::map(ObjectType::find()->all(), 'id', 'name'),
                'value' => function (self $data) {
                        return $data->getObjectType()->one()->name;
                    }
            ],
            [
                'attribute' => 'access_type_id',
                'filter' => ArrayHelper::map(EmployeeAccessType::find()->all(), 'id', 'name'),
                'value' => function (self $data) {
                        return $data->accessType->name;
                    }
            ]];


            $indexAttributtes = ArrayHelper::merge($indexAttributtes, static::getAdditionalAttrsForIndex());

            $indexAttributtes[] = 'tech_parameter';
            $indexAttributtes[] = [
                'class' => \yii\grid\ActionColumn::className()
            ];


		return $viewAction
			? [
				'id',
				'name',
				[
					'attribute' => 'object_type_id',
					'value' => $this->getObjectType()->one()->name
				],

				'tech_parameter'
			]
			: $indexAttributtes;
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
                'access_type_id' => [
                    'type' => Form::INPUT_DROPDOWN_LIST,
                    'items' => ArrayHelper::map(EmployeeAccessType::find()->all(), 'id', 'name')
                ],
				'additionalAttributes[]' => [
					'type' => Form::INPUT_RAW,
					'value' => function (self $data) {
							return $data->getAdditionalAttrs();
						}
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
     * @return string
     */
    public static function getAdditionalAttrsForIndex()
    {
        $attributes = [];

        $categories = AttackCategory::find()->orderBy('position')->all();
        foreach ($categories as $category) {
            $attributes[] = [
                'header' => $category->name,
                'filter' => ArrayHelper::map(
                        AttackCategoryValue::find()
                        ->where('category_id = :cid', [':cid' => $category->id])
                        ->all(),
                        'id',
                        'name'
                    ),
                'attribute' => 'additionalAttributes',
                'filterInputOptions' => [
                    'name' => Html::getInputName(new Attack(), 'additionalAttributes['.$category->id.']'),
                    'class' => 'form-control'
                ],
                'value' => function (self $data) use ($category) {
                        $attackValue = AttackCategoryValueToAttack::find()
                            ->where('attack_id = :aid', [':aid' => $data->id])
                            ->andWhere('attack_category_id = :acid', [':acid' => $category->id])
                            ->one();

                        return $attackValue ? $attackValue->attackValue->name : null;
                    }
            ];
        }


        return $attributes;
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

//        $dataProvider->setSort([
//                'attributes' => [
//                    'id',
//                    'categoryValueToAttack' => [
//                        'asc' => ['attack_category_value_to_attack.attack_value_id' => SORT_ASC],
//                        'desc' => ['attack_category_value_to_attack.attack_value_id' => SORT_DESC],
//                        'label' => 'Country Name'
//                    ]
//                ]
//            ]);

		if (!empty($params)){
			$this->load($params);
            if (isset($params['Attack']['additionalAttributes'])){
                $addAttrs = array_filter($params['Attack']['additionalAttributes']);
                if (!empty($addAttrs)) {

                    $query->joinWith(
                        [
                            'categoryValueToAttack' => function (ActiveQuery $q) use ($addAttrs) {
                                    $attrCount = count($addAttrs) - 1;
                                    $q->andWhere([AttackCategoryValueToAttack::tableName(
                                        ) . '.attack_value_id' => array_values($addAttrs)]);
                                    $q->groupBy(AttackCategoryValueToAttack::tableName(
                                        ).'.attack_id');
                                    $q->having( 'COUNT(attack_id) > '.$attrCount);

                                }
                        ]
                    );
                }
            }
		}


		$query->andFilterWhere([static::tableName().'.id' => $this->id]);
		$query->andFilterWhere(['object_type_id' => $this->object_type_id]);
		$query->andFilterWhere(['like', 'name', $this->name]);
		$query->andFilterWhere(['access_type_id' => $this->access_type_id]);
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

    /**
     * @param null $attackId
     * @param bool $onlyKeys
     *
     * @return array
     */
    public static function getAttackParams($attackId = null, $onlyKeys = false)
    {
        $result = [];

        if ($onlyKeys){
            return (new Query())
                ->select('name')
                ->from(AttackCategory::tableName())
                ->column();
        }

        $params = AttackCategoryValueToAttack::find();
        if ($attackId){
            $params->where('attack_id = :aid', [':aid' => (int)$attackId]);
        }

        $params = $params->all();

        foreach ($params as $param){
            $result[$param->attackCategory->name] = $param->attackValue->name;
        }

        return $result;
    }
}
