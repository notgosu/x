<?php

namespace backend\modules\project\models;

use backend\components\BackModel;
use kartik\builder\Form;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\validators\EmailValidator;

/**
 * This is the model class for table "employee".
 *
 * @property integer $id
 * @property integer $company_id
 * @property string $name
 * @property string $post
 * @property integer $psycho_type_id
 * @property string $motivation
 * @property integer $addition_resources
 * @property string $emails
 * @property string $phones
 * @property string $site
 * @property string $messengers
 * @property string $address
 * @property string $comment
 *
 * @property EmployeePsychoType $psychoType
 * @property Company $company
 */
class Employee extends BackModel
{

	/**
	 * @inheritdoc
	 */
	public function afterFind()
	{
		parent::afterFind();


		$this->phones = json_decode($this->phones, true);
		if (is_array($this->phones)){
			$this->phones = array_values($this->phones);
		}

		$this->emails = json_decode($this->emails, true);
		if (is_array($this->emails)){
			$this->emails = array_values($this->emails);
		}

		$this->messengers = json_decode($this->messengers, true);
		if (is_array($this->messengers)){
			$this->messengers = array_values($this->messengers);
		}


	}

	/**
	 * @inheritdoc
	 */
	public function beforeSave($jnsert)
	{
		if (parent::beforeSave($jnsert)){
			$this->phones = json_encode($this->phones);
			$this->emails = json_encode($this->emails);
			$this->messengers = json_encode($this->messengers);

			return true;
		}

		return false;

	}

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'employee';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['company_id', 'name', 'post', 'psycho_type_id', 'motivation', 'addition_resources', 'site', 'address', 'comment'], 'required'],
            [['company_id', 'psycho_type_id', 'addition_resources'], 'integer'],
            [['motivation'], 'number'],
            [['comment'], 'string'],
	        ['comment', 'filter', 'filter' => 'strip_tags'],
	        ['phones', 'checkPhones'],
	        ['emails', 'checkEmails'],
	        ['messengers', 'checkMessengers'],
            [['post', 'site', 'address'], 'string', 'max' => 255],
	        [['id', 'name', 'company_id', 'post', 'psycho_type_id', 'motivation'], 'safe', 'on' => 'search']
        ];
    }

	/**
	 * @return bool
	 */
	public function checkPhones(){
		$valid = true;
		if (is_array($this->phones) && !empty($this->phones)){
			foreach ($this->phones as $phone){
				if (!$phone || $phone == ''){
					$valid = false;
				}
			}
		}
		else{
			$valid = false;
		}

		if (!$valid){
			$this->addError('phones[0]', 'Необхiдно заповнити кожен телефон зi створених');
			return false;
		}

		return true;

	}

	/**
	 * @return bool
	 */
	public function checkEmails(){
		$valid = true;
		$notCorrectEmail = false;

		if (is_array($this->emails) && !empty($this->emails)){
			foreach ($this->emails as $email){
				$emailValidator = new EmailValidator();
				if (!$email || $email == ''){
					$valid = false;
				}
				if ($valid && !$emailValidator->validate($email)){
					$valid = false;
					$notCorrectEmail = true;
				}
			}
		}
		else{
			$valid = false;
		}

		if (!$valid){

			$this->addError('emails[0]', $notCorrectEmail
					? 'Один чи декiлька iз заповнених email не є корректним'
					: 'Необхiдно заповнити кожен email зi створених');
			return false;
		}

		return true;

	}

	/**
	 * @return bool
	 */
	public function checkMessengers(){
		$valid = true;
		if (is_array($this->messengers) && !empty($this->messengers)){
			foreach ($this->messengers as $messanger){
				if (!$messanger || $messanger == ''){
					$valid = false;
				}
			}
		}
		else{
			$valid = false;
		}

		if (!$valid){
			$this->addError('messengers[0]', 'Необхiдно заповнити кожен мессенджер зi створених');
			return false;
		}

		return true;

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
		$query->andFilterWhere(['psycho_type_id' => $this->psycho_type_id]);
		$query->andFilterWhere(['like', 'name', $this->name]);
		$query->andFilterWhere(['like', 'post', $this->post]);
		$query->andFilterWhere(['like', 'motivation', $this->motivation]);

		return $dataProvider;
	}

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'ПІБ',
            'company_id' => 'Компанія',
            'post' => 'Посада',
            'psycho_type_id' => 'Психотип',
            'motivation' => 'Мотивація',
            'addition_resources' => 'Додаткові ресурси',
            'emails' => 'E-mails',
            'phones' => 'Телефон(и)',
            'site' => 'Сайт',
            'messengers' => 'Месенджер',
            'address' => 'Контактна адреса',
            'comment' => 'Коментарі',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPsychoType()
    {
        return $this->hasOne(EmployeePsychoType::className(), ['id' => 'psycho_type_id'])->orderBy('position');
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
				'post',
				[
					'attribute' => 'psycho_type_id',
					'value' => $this->getPsychoType()->one()->name
				],
				'motivation',
				'addition_resources',
				[
					'attribute' => 'emails',
					'value' => implode(', ', $this->emails),
				],
				[
					'attribute' => 'phones',
					'value' => implode(', ', $this->phones),
				],
				'site',
				[
					'attribute' => 'messengers',
					'value' => implode(', ', $this->messengers),
				],
				'address',
				'comment',
			]
			: [
				'id',
				'name',
				[
					'attribute' => 'company_id',
					'filter' => ArrayHelper::map(Company::find()->all(), 'id', 'name'),
					'value' => function (self $data){
							return $data->getCompany()->one()->name;
						}
				],
				'post',
				[
					'attribute' => 'psycho_type_id',
					'filter' => ArrayHelper::map(EmployeePsychoType::find()->all(), 'id', 'name'),
					'value' => function (self $data){
							return $data->getPsychoType()->one()->name;
						}
				],
				'motivation',

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
				'company_id' => [
					'type' => Form::INPUT_DROPDOWN_LIST,
					'items' => ArrayHelper::map(Company::find()->all(), 'id', 'name')
				],
				'post' => [
					'type' => Form::INPUT_TEXT,
				],
				'psycho_type_id' => [
					'type' => Form::INPUT_DROPDOWN_LIST,
					'items' => ArrayHelper::map(EmployeePsychoType::find()->all(), 'id', 'name')
				],
				'motivation' => [
					'type' => Form::INPUT_TEXT,
				],
				'addition_resources' => [
					'type' => Form::INPUT_TEXT,
				],
				'emails[]' => [
					'type' => Form::INPUT_RAW,
					'value' => function ($data){
							return $this->getClonableInputList(
								$data,
								'emails',
								'.field-user-emails',
								'.item-to-clone',
								'Emails',
								'Employee[emails][]'
							);
						}
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
								'Employee[phones][]',
								true
							);
						}
				],
				'site' => [
					'type' => Form::INPUT_TEXT,
				],
				'messengers[]' => [
					'type' => Form::INPUT_RAW,
					'value' => function ($data){
							return $this->getClonableInputList(
								$data,
								'messengers',
								'.field-user-messengers',
								'.messenger-to-clone',
								'Месенджер(и)',
								'Employee[messengers][]'
							);
						}
				],
				'address' => [
					'type' => Form::INPUT_TEXT,
				],
				'comment' => [
					'type' => Form::INPUT_TEXTAREA,
				],

			];

	}

	/**
	 * @return string
	 */
	public function getBreadCrumbRoot()
	{
		return 'Спiвробiтники';
	}

}
