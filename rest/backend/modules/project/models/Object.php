<?php

namespace backend\modules\project\models;

use backend\modules\project\widgets\objectAttacks\ObjectAttacksWidget;
use backend\modules\project\widgets\objectEmployee\ObjectEmployeeWidget;
use kartik\builder\Form;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * This is the model class for table "object".
 *
 * @property integer $id
 * @property string $name
 * @property integer $company_id
 * @property integer $object_type_id
 * @property integer $info_amount
 *
 * @property ObjectType $objectType
 * @property Company $company
 */
class Object extends \backend\components\BackModel
{

	/**
	 * @var array
	 */
	public $employees = array();

	/**
	 * @var array
	 */
	public $attacks = array();

	/**
	 * @var null
	 */
	public $tempSign = null;

	public function init(){
		parent::init();

		if (!$this->tempSign){
			$this->tempSign = \Yii::$app->security->generateRandomString();
		}

		$company = Company::find()->one();
		$objectType = ObjectType::find()->one();

		if ($company){
			$this->company_id = $company->id;
		}
		if ($objectType){
			$this->object_type_id = $objectType->id;
		}
	}

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'object';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['name', 'company_id', 'object_type_id', 'info_amount'], 'required'],
			[['company_id', 'object_type_id', 'info_amount'], 'integer'],
			[['name'], 'string', 'max' => 255],
			['info_amount', 'in', 'range' => range(1, 100)],
			['employees', 'validateEmployees'],
			['attacks', 'validateAttacks'],
			[['tempSign'], 'safe'],
			[['id', 'name', 'company_id', 'object_type_id', 'info_amount'], 'safe', 'on' => 'search']

		];
	}

	public function validateEmployees(){
        $i = 0;
        $error = false;

		foreach ($this->employees as $employee){
			if (!$this->isNewRecord){
				$employeeParam = ObjectEmployeeParams::find()
					->where('employee_id=:eid', [':eid' => $employee['employee_id']])
					->andWhere('object_id=:oid', [':oid' => $this->id])
					->one();
			}
			else{
				$employeeParam = ObjectEmployeeParams::find()
					->where('employee_id=:eid', [':eid' => $employee['employee_id']])
					->andWhere('temp_sign=:ts', [':ts' => $this->tempSign])
					->one();
			}

			if ($employeeParam){
                if ($employee['access_type_id'] == ''){
                    $this->addError('employees['.$i.']', 'Виберiть тип доступу для спiвробiтника '.$employeeParam->employee->name);
                    $error = true;
                }
                else{
                    $employeeParam->access_type_id = $employee['access_type_id'];
                    $employeeParam->is_active = $employee['is_active'];
                    $employeeParam->save(false);
                }
			}

            $i++;
		}

        if ($error){
            return false;
        }

		return true;
	}

	public function validateAttacks(){
		$i = 0;
		$valid = true;
		foreach ($this->attacks as $attack){
			if (!$this->isNewRecord){
				$attackParam = ObjectAttackParams::find()
					->where('attack_id=:aid', [':aid' => $attack['attack_id']])
					->andWhere('object_id=:oid', [':oid' => $this->id])
					->one();
			}
			else{
				$attackParam = ObjectAttackParams::find()
					->where('attack_id=:aid', [':aid' => $attack['attack_id']])
					->andWhere('temp_sign=:ts', [':ts' => $this->tempSign])
					->one();
			}

			if ($attackParam){
				$attackParam->cost = $attack['cost'];
				$attackParam->is_active = $attack['is_active'];

				if (!$attackParam->validate()){
					$this->addError('attacks['.$i.']', 'Aтака '.$attackParam->getAttack()->one()->name.': '.join(', ', $attackParam->getFirstErrors()));
					$valid = false;
				}
				$attackParam->save(false);
			}

			$i++;
		}


		return $valid;
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'name' => 'Назва',
			'company_id' => 'Компанія',
			'object_type_id' => 'Тип об\'єкту',
			'info_amount' => 'Загальний обєм інформації',
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
	public function getCompany()
	{
		return $this->hasOne(Company::className(), ['id' => 'company_id']);
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
					'attribute' => 'company_id',
					'value' => $this->getCompany()->one()->name
				],
				[
					'attribute' => 'object_type_id',
					'value' => $this->getObjectType()->one()->name
				],
				'info_amount'
			]
			: [
				'id',
				'name',
				[
					'attribute' => 'company_id',
					'filter' => ArrayHelper::map(Company::find()->all(), 'id', 'name'),
					'value' => function (self $data) {
							return $data->getCompany()->one()->name;
						}
				],
				[
					'attribute' => 'object_type_id',
					'filter' => ArrayHelper::map(ObjectType::find()->all(), 'id', 'name'),
					'value' => function (self $data) {
							return $data->getObjectType()->one()->name;
						}
				],
				'info_amount',
				[
					'class' => \yii\grid\ActionColumn::className()
				]
			];
	}

	public function afterSave($insert, $changedAttributes){
		parent::afterSave($insert, $changedAttributes);

		$this->updateEmployeeParams();
		$this->updateAttackParams();

		return true;
	}

	public function updateEmployeeParams(){
		ObjectEmployeeParams::deleteAll('object_id=:oid OR temp_sign IS NOT NULL', [':oid' => $this->id]);

		foreach ($this->employees as $employee){
			$model = new ObjectEmployeeParams();
			$model->employee_id = $employee['employee_id'];
			$model->access_type_id = $employee['access_type_id'];
			$model->is_active = $employee['is_active'];
			$model->object_id = $this->id;

			$model->save(false);
		}
	}

	public function updateAttackParams(){
		ObjectAttackParams::deleteAll('object_id=:oid OR temp_sign IS NOT NULL', [':oid' => $this->id]);

		foreach ($this->attacks as $attack){
			$model = new ObjectAttackParams();
			$model->attack_id = $attack['attack_id'];
			$model->cost = $attack['cost'];
			$model->is_active = $attack['is_active'];
			$model->object_id = $this->id;

			$model->save(false);
		}
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
		$query->andFilterWhere(['company_id' => $this->company_id]);
		$query->andFilterWhere(['object_type_id' => $this->object_type_id]);
		$query->andFilterWhere(['like', 'name', $this->name]);
		$query->andFilterWhere(['like', 'info_amount', $this->info_amount]);

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
				'company_id' => [
					'type' => Form::INPUT_DROPDOWN_LIST,
					'items' => ArrayHelper::map(Company::find()->all(), 'id', 'name'),
					'options' => [
						'class' => 'dependent',
						'data-name' => 'companyId',
						'data-url' => Url::to([
									'/project/object/get-employee-list',
									'objectId' => $this->isNewRecord ? $this->tempSign : $this->id
								])

						]
				],
				'object_type_id' => [
					'type' => Form::INPUT_DROPDOWN_LIST,
					'items' => ArrayHelper::map(ObjectType::find()->all(), 'id', 'name'),
					'options' => [
						'class' => 'dependent',
						'data-name' => 'object_type_id',
						'data-url' => Url::to([
									'/project/object/get-attack-list',
									'objectId' => $this->isNewRecord ? $this->tempSign : $this->id
								])

					]
				],
				'info_amount' => [
					'type' => Form::INPUT_TEXT,
					'hint' => 'від 1 до 100'
				],
				'employeeParams' => [
					'type' => Form::INPUT_RAW,
					'value' => function (self $data){
							return ObjectEmployeeWidget::widget([
									'company_id' => $data->company_id,
									'object_id' => $data->id,
									'temp_sign' => $data->isNewRecord ? $data->tempSign : null
								]);
						}
				],
				'attacks' => [
					'type' => Form::INPUT_RAW,
					'value' => function (self $data){
							return ObjectAttacksWidget::widget([
									'object_type_id' => $data->object_type_id,
									'object_id' => $data->id,
									'temp_sign' => $data->isNewRecord ? $data->tempSign : null
								]);
						}
				],
				'tempSing' => [
					'type' => Form::INPUT_RAW,
					'value' => function (self $data){
							return Html::activeHiddenInput($data, 'tempSign');
						}
				]


			];
	}

	/**
	 * @return string
	 */
	public function getBreadCrumbRoot()
	{
		return 'Об\'єкт';
	}
}
