<?php

namespace backend\modules\project\models;

use backend\modules\project\widgets\objectAttacks\ObjectAttacksWidget;
use backend\modules\project\widgets\objectEmployee\ObjectEmployeeWidget;
use kartik\builder\Form;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Query;
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
					'class' => \yii\grid\ActionColumn::className(),
                    'buttons' => [
                        'view-result' => function ($url, $model, $key){
                                return Html::a(
                                    '<i class="glyphicon glyphicon-list-alt"></i>',
                                    $url
                                );
                            }
                    ],
                    'template' => '{view} {update} {delete} {view-result}'
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

    /**
     * @return array
     */
    public function getFinalResultProvider(){
        $result = [];
        //Массив динамiчних параметрiв атак для запобiгання повторних запитiв до БД
        $attackDynamicParams = [];
        //Результати першого этапу розрахункiв
        $internalResult = [];
        $company = $this->company;
        $CC = $company->calculateCC();
        //обєм інформації в обєкті
        $V = number_format($this->info_amount/100, 2);
        //ціна інформації - береться з компанії - ринкова вартість інформації
        $R = $company->market_info_price;
        $m = number_format($R / $company->critical_info_price, 7);

        //Активные сотрудники в обьекте
        $objectEmployees = (new Query())
            ->select('employee_id, access_type_id')
            ->from(ObjectEmployeeParams::tableName())
            ->where('is_active=1')
            ->all();

        foreach ($objectEmployees as $employee){
            $employeeModel = (new Query())
                ->select('employee.name, employee.addition_resources, employee_psycho_type.value AS psychoTypeValue')
                ->from(Employee::tableName())
                ->leftJoin(EmployeePsychoType::tableName(), 'employee_psycho_type.id=employee.psycho_type_id')
                ->where('employee.id=:id', [':id' => $employee['employee_id']])
                ->one();

            if ($employeeModel){
                //Параметры атак, которые могут выполнить сотрудники
                $attacksParams = (new Query())
                    ->select('attack_id, cost')
                    ->from(ObjectAttackParams::tableName())
                    ->leftJoin(Attack::tableName(), 'attack_id=attack.id')
                    ->where('object_id=:oid', [':oid' => $this->id])
                    ->andWhere('attack.access_type_id=:atid', [':atid' => $employee['access_type_id']])
                    ->andWhere('cost <= :cost', [':cost' => $employeeModel['addition_resources']])
                    ->andWhere('is_active=1')
                    ->all();

                //Для каждой возможной атаки расчитываем данные
                foreach ($attacksParams as $attackParam){

                    $attackModel = (new Query())
                        ->select('id, name, tech_parameter')
                        ->from(Attack::tableName())
                        ->where('id=:id', [':id' => $attackParam['attack_id']])
                        ->one();

                    if ($attackModel){
                        //технічний коефіціент атаки
                        $kT = $attackModel['tech_parameter'];
                        //витрата на атаку
                        $D = $attackParam['cost'];
                        //для конкретного співробітника можливість залучення додаткових ресурсів
                        $M = number_format($employeeModel['addition_resources'] / $attackParam['cost'], 7);
                        //З модуля психотив, який відповідає конкретному зловмисника
                        $Ph = $employeeModel['psychoTypeValue'];
                        //ймовірнісний коефіціент атаки
                        $A = number_format(1 - ($D / ($V * $R)), 7);

                        //коефіціент співробітника
                        $W = number_format($m * $M * $Ph, 7);

                        $internalResult['W'][] = $W;
                        $internalResult['KT'][] = $kT;
                        $internalResult['A'][] = $A;

                        $partialResult = [
                            'id' => '',
                            'Назва атаки' => $attackModel['name'],
                            'Об`єкт' => $this->name,
                            'Спiвробiтник' => $employeeModel['name'],
                            'Сума атаки' => $attackParam['cost'],
                            'cc' => $CC,
                            'kt' => $kT,
                            'w' => $W,
                            'a' => $A,
                            'z' => ''
                        ];

                        if (!isset($attackDynamicParams[$attackModel['id']])){
                            $attackDynamicParams[$attackModel['id']] = Attack::getAttackParams($attackModel['id']);
                        }
                        $result[] = ArrayHelper::merge(
                            $partialResult,
                            $attackDynamicParams[$attackModel['id']]
                        );
                    }
                }
            }
        }

//        for ($i=1; $i <=25; $i++) {
//            $result[] = [
//                'id' => '',
//                'attack_name' => "Атака".$i,
//                'object' => $this->name,
//                'employee' => 'Роб'.$i,
//                'attack_param1' => '',
//                'attack_param2' => '',
//                'attack_param3' => '',
//                'attack_access' => '',
//                'amount' => 23,
//                'cc' => 0.63,
//                'kt' => '',
//                'w' => '',
//                'a' => '',
//                'z' => ''
//            ];
//        }
//
//
//
//        $internalResult['W'] = [
//            '0.346',
//            '0.526',
//            '0.248',
//            '0.577',
//            '0.555',
//            '0.748',
//            '0.426',
//            '0.533',
//            '0.269',
//            '0.245',
//            '0.535',
//            '0.362',
//            '0.592',
//            '0.27',
//            '0.582',
//            '0.365',
//            '0.457',
//            '0.361',
//            '0.763',
//            '0.472',
//            '0.521',
//            '0.564',
//            '0.291',
//            '0.23',
//            '0.78'
//        ];
//        $internalResult['KT'] = [
//            '0.383',
//            '0.445',
//            '0.6',
//            '0.321',
//            '0.373',
//            '0.45',
//            '0.778',
//            '0.309',
//            '0.575',
//            '0.668',
//            '0.454',
//            '0.719',
//            '0.383',
//            '0.548',
//            '0.399',
//            '0.634',
//            '0.305',
//            '0.779',
//            '0.317',
//            '0.474',
//            '0.671',
//            '0.478',
//            '0.458',
//            '0.3',
//            '0.9'
//        ];
//        $internalResult['A'] = [
//            '0.567',
//            '0.431',
//            '0.606',
//            '0.416',
//            '0.293',
//            '0.662',
//            '0.542',
//            '0.562',
//            '0.724',
//            '0.473',
//            '0.279',
//            '0.711',
//            '0.446',
//            '0.738',
//            '0.712',
//            '0.426',
//            '0.623',
//            '0.732',
//            '0.39',
//            '0.285',
//            '0.714',
//            '0.333',
//            '0.01',
//            '0.01',
//            '0.74',
//        ];



        //Обработка промежуточного массива для поиска min/max и формирования результирующего массива
        if (!empty($internalResult)){
            $config = Yii::$app->config;

            $CCmin = $config->get('CCmin', 0.15);
            $CCmax = $config->get('CCmax', 0.89);
            $CCcrit = $config->get('CCcrit', 0.1);

            $Wmin = min($internalResult['W']);
            $Wmax = max($internalResult['W']);
            $Wcrit = $config->get('Wcrit', 0.3);

            $KTmin = min($internalResult['KT']);
            $KTmax = max($internalResult['KT']);
            $KTcrit = $config->get('KTcrit', 0.35);

            $Amin = min($internalResult['A']);
            $Amax = max($internalResult['A']);
            $Acrit = $config->get('Acrit', 0.35);

            $i = 1;
            foreach ($result as &$resultElem){
                $resultElem['id'] = $i;

                $ccDivided = $CCmax - $CCmin;
                $resultElemCC = $ccDivided != 0
                    ? number_format(
                        abs(($resultElem['cc'] - $CCmin)/$ccDivided),
                        7
                    )
                    : 1;


                $ktDivided = number_format($KTmax - $KTmin, 7);
                $resultElemKT = $ktDivided != 0
                    ? number_format(
                        abs(($internalResult['KT'][$i-1] - $KTmin)/$ktDivided),
                        7
                    )
                    : 1;

                $wDivided = $Wmax - $Wmin;
                $resultElemW = $wDivided != 0
                    ? number_format(
                        abs(($internalResult['W'][$i-1] - $Wmin)/$wDivided),
                        7
                    )
                    : 1;

                $aDivided = $Amax - $Amin;
                $resultElemA = $aDivided != 0
                    ? number_format(
                        abs(($internalResult['A'][$i-1] - $Amin)/$aDivided),
                        7
                    )
                    : 1;

                $resultElem['z'] = number_format(
                    pow($resultElemCC, $CCcrit) *
                    pow($resultElemW, $Wcrit) *
                    pow($resultElemKT, $KTcrit) *
                    pow($resultElemA, $Acrit),
                    7);

                $i++;
            }
        }


        return $result;
    }
}
