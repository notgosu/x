<?php

namespace backend\modules\project\models;

use Yii;

/**
 * This is the model class for table "attack_category_value_to_attack".
 *
 * @property integer $id
 * @property integer $attack_id
 * @property integer $attack_category_id
 * @property integer $attack_value_id
 *
 * @property AttackCategoryValue $attackValue
 * @property AttackCategory $attackCategory
 * @property Attack $attack
 */
class AttackCategoryValueToAttack extends \backend\components\BackModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'attack_category_value_to_attack';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['attack_id', 'attack_category_id', 'attack_value_id'], 'required'],
            [['attack_id', 'attack_category_id', 'attack_value_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'attack_id' => 'Attack ID',
            'attack_category_id' => 'Attack Category ID',
            'attack_value_id' => 'Attack Value ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttackValue()
    {
        return $this->hasOne(AttackCategoryValue::className(), ['id' => 'attack_value_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttackCategory()
    {
        return $this->hasOne(AttackCategory::className(), ['id' => 'attack_category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttack()
    {
        return $this->hasOne(Attack::className(), ['id' => 'attack_id']);
    }
}
