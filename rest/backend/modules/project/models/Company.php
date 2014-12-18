<?php

namespace backend\modules\project\models;

use backend\components\BackModel;
use kartik\builder\Form;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\validators\EmailValidator;

/**
 * This is the model class for table "company".
 *
 * @property integer $id
 * @property string $name
 * @property integer $photo_id
 * @property integer $critical_info_price
 * @property integer $market_info_price
 * @property string $emails
 * @property string $phones
 * @property string $site
 * @property string $messengers
 * @property string $address
 * @property string $juristic_address
 * @property string $bank_requisites
 * @property string $comment
 * @property integer $employee_id
 *
 */
class Company extends BackModel
{

	/**
	 * @var array
	 */
	public $additionalAttributes = [];

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

		$this->getCompanyAdditionalAttrs();

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

	public function afterSave($insert, $changedAttributes){
		parent::afterSave($insert, $changedAttributes);

		CompanyCategoryAttributeValue::deleteAll('company_id=:cid', [':cid' => $this->id]);
		foreach ($this->additionalAttributes as $categoryId => $attrId){
			$value = new CompanyCategoryAttributeValue();
			$value->company_id = $this->id;
			$value->category_id = $categoryId;
			$value->attribute_id = $attrId;
			$value->save(false);

		}
	}

	public function getCompanyAdditionalAttrs(){
		$additionalAttrs = CompanyCategoryAttributeValue::find()->where('company_id=:cid', [':cid' => $this->id])->all();
		foreach ($additionalAttrs as $attr){
			$this->additionalAttributes[$attr->category_id] = $attr->attribute_id;
		}
	}

	public function getEmployee(){
		return $this->hasOne(Employee::className(), ['id' => 'employee_id']);
	}


	/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'company';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'critical_info_price', 'market_info_price'], 'required'],
            [['photo_id', 'critical_info_price', 'market_info_price', 'employee_id'], 'integer'],
	        ['photo_id', 'default', 'value' => 0],
            ['comment', 'string'],
	        ['comment', 'filter', 'filter' => 'strip_tags'],
	        ['phones', 'checkPhones'],
	        ['emails', 'checkEmails'],
	        [['messengers', 'additionalAttributes', 'comment', 'site', 'address', 'juristic_address', 'bank_requisites'], 'safe'],
            [['name', 'site', 'address', 'juristic_address', 'bank_requisites'], 'string', 'max' => 255],
	        [['id', 'name', 'critical_info_price', 'market_info_price'],'safe', 'on' => 'search']
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
		$query->andFilterWhere(['like', 'critical_info_price', $this->critical_info_price]);
		$query->andFilterWhere(['like', 'market_info_price', $this->market_info_price]);

		return $dataProvider;
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

			if (!$valid && $notCorrectEmail){

				$this->addError('emails[0]', 'Один чи декiлька iз заповнених email не є корректним');
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
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Назва',
            'photo_id' => 'Фото',
            'critical_info_price' => 'Критична вартість інформації',
            'market_info_price' => 'Ринкова вартість інформації',
            'emails' => 'Emails',
            'phones' => 'Телефони',
            'site' => 'Сайт',
            'messengers' => 'Месенджери',
            'address' => 'Фактична адреса',
            'juristic_address' => 'Юридична адреса',
            'bank_requisites' => 'Банківські реквізити',
            'comment' => 'Коментарі',
            'employee_id' => 'Основний контакт компанії',
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
				'name',
				'critical_info_price',
				'market_info_price',
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
				'juristic_address',
				'bank_requisites',
				'comment',
				[
					'attribute' => 'employee_id',
					'value' => $this->employee_id ? $this->getEmployee()->one()->name : null,
				]
			]
			: [
				'id',
				'name',
				'critical_info_price',
				'market_info_price',
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
				'photo_id' => [
					'type' => Form::INPUT_FILE,
				],
				'additionalAttributes[]' => [
					'type' => Form::INPUT_RAW,
					'value' => function (self $data) {
							return $data->getAdditionalAttrs();
						}
				],
				'critical_info_price' => [
					'type' => Form::INPUT_TEXT,
				],
				'market_info_price' => [
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
								'Company[emails][]'
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
								'Company[phones][]',
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
								'Company[messengers][]'
							);
						}
				],
				'address' => [
					'type' => Form::INPUT_TEXT,
				],
				'juristic_address' => [
					'type' => Form::INPUT_TEXT,
				],
				'bank_requisites' => [
					'type' => Form::INPUT_TEXT,
				],
				'comment' => [
					'type' => Form::INPUT_TEXTAREA,
				],
				'employee_id' => [
					'type' => Form::INPUT_DROPDOWN_LIST,
					'items' => ArrayHelper::map(Employee::find()->all(), 'id', 'name')
				],

			];

	}

	/**
	 * @return string
	 */
	public function getBreadCrumbRoot()
	{
		return 'Компанiя';
	}

	/**
	 * @inheritdoc
	 */
	public function getColCount(){
		return 1;
	}

	/**
	 * @return string
	 */
	public function getAdditionalAttrs(){
		$categories = CompanyAttributeCategory::find()->orderBy('position')->all();
		$values = '';
		foreach ($categories as $cat){
			if ($cat->getCompanyAttributes()->count()){
				$values .= Html::label($cat->name, 'additionalAttributes['.$cat->id.']');
				$values .= Html::activeDropDownList(
					$this,
					'additionalAttributes['.$cat->id.']',
					ArrayHelper::map($cat->getCompanyAttributes()->all(), 'id', 'name'),
					['class' => 'form-control']
				);
				$values .= Html::tag('div', '', ['class' => 'help-block']);
			}

		}

		return $values;
	}
}
