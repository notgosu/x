<?php

namespace backend\modules\project\models;

use Yii;

/**
 * This is the model class for table "object_attack_params".
 *
 * @property integer $id
 * @property integer $attack_id
 * @property integer $cost
 * @property float $start_value
 * @property integer $is_active
 *
 * @property Attack $attack
 */
class ObjectAttackParams extends \backend\components\BackModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'object_attack_params';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['attack_id', 'start_value', 'cost', 'is_active'], 'required'],
            [['attack_id', 'cost', 'is_active'], 'integer'],
	        ['cost', 'compare', 'compareValue' => 0, 'operator' => '>'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'attack_id' => 'Атака',
            'cost' => 'Вартість',
            'is_active' => 'Статус',
            'start_value' => 'Початкове значення'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttack()
    {
        return $this->hasOne(Attack::className(), ['id' => 'attack_id']);
    }

	/**
	 * @param $objectId
	 * @param $objectType
	 * @param null $tempSign
	 */
	public static function checkForExistParams($objectId, $objectType, $tempSign = null){
		$attacks = Attack::find()->where('object_type_id=:cid', [':cid' => $objectType])->all();

		foreach ($attacks as $attack){
			if ($objectId){
				$existData = self::find()
					->where('object_id=:oid', [':oid' => $objectId])
					->andWhere('attack_id=:aid', [':aid' => $attack->id])
					->exists();
			}
			else{
				//New record
				$existData = self::find()
					->where('temp_sign=:ts', [':ts' => $tempSign])
					->andWhere('attack_id=:aid', [':aid' => $attack->id])
					->exists();
			}


			if (!$existData){
				$model = new self();
				$model->attack_id = $attack->id;
				$model->object_id = $objectId;
				$model->temp_sign = $tempSign;
				$model->save(false);
			}
		}
	}
}
