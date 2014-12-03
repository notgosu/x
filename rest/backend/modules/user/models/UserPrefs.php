<?php

namespace backend\modules\user\models;

use Yii;

/**
 * This is the model class for table "user_prefs".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $birthday
 * @property integer $sex
 * @property string $city
 * @property string $company
 * @property string $appointment
 * @property string $short_info
 * @property integer $avatar_id
 * @property string $phones
 * @property string $skype
 * @property string $site
 *
 * @property User $user
 */
class UserPrefs extends \backend\components\BackModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_prefs';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'birthday', 'city', 'company', 'appointment', 'short_info', 'avatar_id', 'phones', 'skype', 'site'], 'required'],
            [['user_id', 'sex', 'avatar_id'], 'integer'],
            [['birthday'], 'safe'],
            [['short_info', 'phones'], 'string'],
            [['city', 'company', 'appointment', 'skype', 'site'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'birthday' => 'Birthday',
            'sex' => 'Sex',
            'city' => 'City',
            'company' => 'Company',
            'appointment' => 'Appointment',
            'short_info' => 'Short Info',
            'avatar_id' => 'Avatar ID',
            'phones' => 'Phones',
            'skype' => 'Skype',
            'site' => 'Site',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
