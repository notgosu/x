<?php

namespace backend\modules\user\models;

use backend\components\BackModel;
use kartik\builder\Form;
use kartik\date\DatePicker;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\widgets\MaskedInput;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $role
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class User extends BackModel
{

	public $birthday;

	public $city;

	public $sex;

	public $company;

	public $appointment;

	public $short_info;

	public $avatar_id;

	public $phones = array();

	public $skype;

	public $site;

	public $new_password;


	/**
	 * @return array
	 */
	public function getRoleList(){
		return [
			\common\models\User::ROLE_USER => 'Користувач',
			\common\models\User::ROLE_ADMIN => 'Адмiн'
		];
	}

	/**
	 * @param null $roleId
	 *
	 * @return null
	 */
	public function getRoleName($roleId = null){
		$roleId = $roleId ? $roleId : $this->role;
		$roles = $this->getRoleList();

		return isset($roles[$roleId]) ? $roles[$roleId] : null;
	}

	/**
	 * @return string
	 */
	public function getSexName(){
		return $this->sex == 0 ? 'Чоловiча' : 'Жiноча';
	}

	/**
	 * @inheritdoc
	 */
	public function afterFind()
	{
		parent::afterFind();

		if ($this->isNewRecord) {
			$model = new UserPrefs();
		} else {
			$model = UserPrefs::find()
				->where(['user_id' => $this->id])
				->one();

			if (!$model) {
				throw new NotFoundHttpException;
			}
		}

		$this->city = $model->city;
		$this->city = $model->city;
		$this->birthday = $model->birthday;
		$this->sex = $model->sex;
		$this->company = $model->company;
		$this->appointment = $model->appointment;
		$this->short_info = $model->short_info;
		$this->avatar_id = $model->avatar_id;
		$this->phones = json_decode($model->phones, true);
		if (is_array($this->phones)){
			$this->phones = array_values($this->phones);
		}
		$this->skype = $model->skype;
		$this->site = $model->site;
		$this->save(false);
	}

	/**
	 * @inheritdoc
	 */
	public function afterSave($jnsert, $changedAttributes)
	{
		parent::afterSave($jnsert, $changedAttributes);

		$userPrefs = $this->userPrefs;
		if (!$userPrefs) {
			$userPrefs = new UserPrefs();
			$userPrefs->user_id = $this->id;
		}

		$userPrefs->city = $this->city;
		$userPrefs->birthday = $this->birthday;
		$userPrefs->sex = $this->sex;
		$userPrefs->company = $this->company;
		$userPrefs->appointment = $this->appointment;
		$userPrefs->short_info = $this->short_info;
		$userPrefs->avatar_id = $this->avatar_id;
		$userPrefs->phones = json_encode($this->phones);
		$userPrefs->skype = $this->skype;
		$userPrefs->site = $this->site;
		$userPrefs->save(false);
	}


	public function afterValidate()
	{
		parent::afterValidate();

		if ($this->isNewRecord || $this->new_password){
			$this->password_hash = \Yii::$app->security->generatePasswordHash($this->new_password);
		}

		if (!$this->isNewRecord){
			$this->save(false);
		}

	}


	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{

		return 'user';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[
				['username', 'birthday', 'city', 'sex', 'skype', 'site', 'company', 'appointment', 'email'],
				'required'
			],
			[['role', 'status', 'created_at', 'updated_at'], 'integer'],
			[['username', 'email', 'city'], 'string', 'max' => 255],
			['birthday', 'date', 'format' => 'yyyy-mm-dd'],
			['sex', 'in', 'range' => [0, 1]],
			['email', 'email'],
			['phones', 'checkPhones'],
			['new_password', 'string', 'length' => [6]],
			['avatar_id', 'default', 'value' => 0],
			['short_info', 'filter', 'filter' => 'strip_tags'],
			[['username', 'email'], 'safe', 'on' => 'search']
		];
	}

	public function checkPhones(){
		if (!$this->hasErrors()){
			$valid = true;
			if (!empty($this->phones)){
				foreach ($this->phones as $phone){
					if (!$phone || $phone == ''){
						$valid = false;
					}
				}
			}

			if (!$valid){
				$this->addError('phones[0]', 'Необхiдно заповнити кожен телефон зi створених');
				return false;
			}

			return true;
		}

		return false;
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'username' => 'Повне iм\'я',
			'auth_key' => 'Auth Key',
			'password_hash' => 'Password Hash',
			'password_reset_token' => 'Password Reset Token',
			'email' => 'Email',
			'role' => 'Тип',
			'status' => 'Status',
			'created_at' => 'Створений',
			'updated_at' => 'Вiдредагований',
			'birthday' => 'День народження',
			'sex' => 'Стать',
			'city' => 'Мiсто',
			'company' => 'Компанiя',
			'appointment' => 'Посада',
			'short_info' => 'Коротка iнформацiя',
			'avatar_id' => 'аватар',
			'phones' => 'Телефон(и)',
			'site' => 'Сайт',
			'new_password' => 'Пароль'
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUserPrefs()
	{
		return $this->hasOne(UserPrefs::className(), ['user_id' => 'id']);
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

		return $dataProvider;
	}

	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			[
				'class' => TimestampBehavior::className(),
				'createdAtAttribute' => 'created_at',
				'updatedAtAttribute' => 'updated_at',
			],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function getColCount()
	{
		return 2;
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
				'username',
				'email',
				[
					'attribute' => 'role',
					'value' => $this->getRoleName()
				],
				'birthday',
				[
					'attribute' => 'sex',
					'value' => $this->getSexName()
				],

				[
					'attribute' => 'created_at',
					'value' => date('Y-m-d H:i', $this->created_at),
				],
				[
					'attribute' => 'updated_at',
					'value' => date('Y-m-d H:i', $this->updated_at),
				],
				'city',
				'company',
				'appointment',
				'short_info',
				[
					'attribute' => 'phones',
					'value' => implode(', ', $this->phones),
				],
				'site',
				'skype'
			]
			: [
				'id',
				'username',
				'email',
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
				'username' => [
					'type' => Form::INPUT_TEXT,
					'options' => ['placeholder' => 'Повне iм`я']
				],
				'birthday' => [
					'type' => Form::INPUT_WIDGET,
					'widgetClass' => DatePicker::className(),
					'convertFormat' => true,
					'options' => [
						'pluginOptions' => [
							'format' => 'yyyy-mm-dd',
						],
					],
					'hint' => 'День народження у форматi YYYY-MM-DD'
				],
				'sex' => [
					'type' => Form::INPUT_DROPDOWN_LIST,
					'items' => [0 => 'Чоловiча', 1 => 'Жiноча']
				],
				'city' => [
					'type' => Form::INPUT_TEXT,
					'options' => ['placeholder' => 'Мiсто']
				],
				'company' => [
					'type' => Form::INPUT_TEXT,
					'options' => ['placeholder' => 'Компанiя']
				],
				'appointment' => [
					'type' => Form::INPUT_TEXT,
					'options' => ['placeholder' => 'Посада']
				],
				'short_info' => [
					'type' => Form::INPUT_TEXTAREA,
					'options' => ['placeholder' => 'Коротка інформація']
				],
				'avatar_id' => [
					'type' => Form::INPUT_FILE,
					'options' => ['placeholder' => 'Фото']
				],
				'email' => [
					'type' => Form::INPUT_TEXT,
					'options' => [
						'placeholder' => 'Email',
					]
				],
				'phones[]' => [
					'type' => Form::INPUT_RAW,
					'value' => function ($data){
							return $this->getClonableInputList(
								$data,
								'phones',
								'.field-user-phones',
								'.phone-to-clone',
								'Телефон(и)',
								'User[phones][]',
								true
							);
						}
				],
				'skype' => [
					'type' => Form::INPUT_TEXT,
					'options' => ['placeholder' => 'Skype']
				],
				'site' => [
					'type' => Form::INPUT_TEXT,
					'options' => ['placeholder' => 'Сайт']
				],
				'role' => [
					'type' => Form::INPUT_DROPDOWN_LIST,
					'items' => [
						\common\models\User::ROLE_USER => 'Користувач',
						\common\models\User::ROLE_ADMIN => 'Адмiн']
				],
				'new_password' => [
					'type' => Form::INPUT_PASSWORD,
					'options' => ['placeholder' => 'Пароль']
				]
			];

	}

	/**
	 * @return string
	 */
	public function getBreadCrumbRoot()
	{
		return 'Користувачi';
	}
}
