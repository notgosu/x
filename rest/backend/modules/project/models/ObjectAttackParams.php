<?php

namespace backend\modules\project\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

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

    /**
     * @return array
     */
    public static function getAttackColsForGrid()
    {
        $object = new Object();

        $cols[] = [
            'attribute' => 'attack_id',
            'format' => 'raw',
            'value' => function (self $data, $key, $index) use ($object){
                    $return = Html::hiddenInput(
                        Html::getInputName($object, 'attacks').'[attack_id][]', $data->attack_id);
                    $return .= $data->attack->name;
                    return $return;
                },
        ];

        $categories = AttackCategory::find()->orderBy('position')->all();
        $catArr = [];
        foreach ($categories as $cat) {
            if ($cat->getAttackCategoryValues()->count()) {
                $catArr[$cat->id]['label'] = $cat->name;
                $catArr[$cat->id]['values'] = ArrayHelper::map($cat->getAttackCategoryValues()->all(), 'id', 'name');
            }
        }

        foreach ($catArr as $catId => $cA) {
            $cols[] = [
                'attribute' => $cA['label'],
                'format' => 'raw',
                'value' => function (\backend\modules\project\models\ObjectAttackParams $data, $key, $index) use (
                        $object,
                        $catId,
                        $cA
                    ) {
                        $addAtts = $data->attack->additionalAttributes;
                        return isset($addAtts[$catId])
                            ? $cA['values'][$addAtts[$catId]]
                            : null;
                    },
            ];
        }

        $cols[] = [
            'attribute' => 'start_value',
            'format' => 'raw',
            'value' => function ($data, $key, $index) use ($object) {
                    return Html::textInput(
                        Html::getInputName($object, 'attacks') . '[start_value][]',
                        $data->start_value,
                        [
                            'class' => 'form-control'
                        ]
                    );
                },
        ];
        $cols[] = [
            'attribute' => 'cost',
            'format' => 'raw',
            'value' => function ($data, $key, $index) use ($object) {
                    return Html::textInput(
                        Html::getInputName($object, 'attacks') . '[cost][]',
                        $data->cost,
                        [
                            'class' => 'form-control'
                        ]
                    );
                },
        ];
        $cols[] = [
            'attribute' => 'is_active',
            'format' => 'raw',
            'value' => function ($data, $key, $index) use ($object) {
                    $inputName = Html::getInputName($object, 'attacks') . '[is_active]['.$data->id. ']';
                    $inputValueInPost = isset($_POST['Object']['attacks']['is_active'][$data->id])
                        ? $_POST['Object']['attacks']['is_active'][$data->id]
                        : false;
                    return Html::checkbox(
                        $inputName,
                        $inputValueInPost ? true : $data->is_active,
                        [
                            'uncheck' => 0,
                            'label' => 'Активний',
                        ]
                    );
                }
        ];
        return $cols;
    }
}
