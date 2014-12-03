<?php

namespace backend\modules\project\models;

use backend\components\BackModel;
use kartik\builder\Form;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "project".
 *
 * @property integer $id
 * @property string $name
 * @property integer $user_id
 * @property integer $logo_id
 * @property string $short_info
 * @property integer $show_in_sidebar
 * @property integer $position
 * @property integer $company_id
 *
 * @property User $user
 */
class Project extends BackModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'project';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'short_info', 'company_id'], 'required'],
            [['user_id', 'company_id', 'logo_id', 'show_in_sidebar', 'position'], 'integer'],
            [['short_info'], 'string'],
            [['name'], 'string', 'max' => 255],
	        ['logo_id', 'default', 'value' => 0],
	        ['user_id', 'default', 'value' => Yii::$app->getUser()->getId()]
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
            'user_id' => 'User ID',
            'logo_id' => 'Лого',
            'short_info' => 'Короткий опис',
            'show_in_sidebar' => 'Відображати в сайдбарі',
            'position' => 'Позицiя',
	        'company_id' => 'Компанiя'
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCompany()
	{
		return $this->hasOne(Company::className(), [ 'id' => 'company_id']);
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
				'short_info',
				'show_in_sidebar:boolean',
			]
			: [
				'id',
				'name',
				[
					'attribute' => 'company_id',
					'filter' => ArrayHelper::map(Company::find()->all(), 'id', 'name'),
					'value' => function ($data){
							return $data->getCompany()->one()->name;
						}
				],
				'show_in_sidebar:boolean',

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
				'logo_id' => [
					'type' => Form::INPUT_FILE,
				],
				'short_info' => [
					'type' => Form::INPUT_TEXTAREA,
				],
				'company_id' => [
					'type' => Form::INPUT_DROPDOWN_LIST,
					'items' => ArrayHelper::map(Company::find()->all(), 'id', 'name')
				],
				'position' => [
					'type' => Form::INPUT_TEXT,
				],
				'show_in_sidebar' => [
					'type' => Form::INPUT_CHECKBOX,
				],
			];

	}

	/**
	 * @return string
	 */
	public function getBreadCrumbRoot()
	{
		return 'Проекти';
	}

}
