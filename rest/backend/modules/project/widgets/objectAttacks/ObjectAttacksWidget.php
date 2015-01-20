<?php
/**
 * Author: Pavel Naumenko
 */

namespace backend\modules\project\widgets\objectAttacks;

use backend\modules\project\models\Attack;
use backend\modules\project\models\AttackCategory;
use backend\modules\project\models\ObjectAttackParams;
use yii\base\Widget;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * Class ObjectAttacksWidget
 * @package backend\modules\project\widgets\objectAttacks
 */
class ObjectAttacksWidget extends Widget
{
	public $object_id;

	public $object_type_id;

	public $temp_sign = null;

	public function run(){
		ObjectAttackParams::checkForExistParams($this->object_id, $this->object_type_id, $this->temp_sign);

		$query = ObjectAttackParams::find()
			->joinWith('attack')
			->where('attack.object_type_id=:tid', [':tid' => $this->object_type_id]);

		if ($this->object_id){
			$query->andWhere('object_id=:oid', [':oid' => $this->object_id]);
		}
		elseif($this->temp_sign){
			$query->andWhere('temp_sign=:ts', [':ts' => $this->temp_sign]);
		}

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'sort' => false
		]);


		return $this->render('default', [
				'provider' => $dataProvider,
				'cols' => $this->generateCols()
			]);
	}

	/**
	 * @return array
	 */
	public function generateCols(){
		$object = new \backend\modules\project\models\Object();
		$cols = [];
		$categories = AttackCategory::find()->orderBy('position')->all();
		$catArr = [];
		foreach ($categories as $cat){
			if ($cat->getAttackCategoryValues()->count()){
				$catArr[$cat->id]['label'] = $cat->name;
				$catArr[$cat->id]['values'] = ArrayHelper::map($cat->getAttackCategoryValues()->all(), 'id', 'name');
			}
		}


		$cols[] = [
			'attribute' => 'attack_id',
			'format' => 'raw',
			'value' => function (\backend\modules\project\models\ObjectAttackParams $data, $key, $index) use ($object){
					$return = \yii\helpers\Html::hiddenInput(
						\yii\helpers\Html::getInputName($object, 'attacks').'['.$index.'][attack_id]', $data->attack_id);
					$return .= $data->getAttack()->one()->name;
					return $return;
				},
		];

		foreach ($catArr as $catId => $cA){
			$cols[] = [
				'attribute' => $cA['label'],
				'format' => 'raw',
				'value' => function (\backend\modules\project\models\ObjectAttackParams $data, $key, $index) use ($object, $catId, $cA){
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
            'value' => function ($data, $key, $index) use ($object){
                    return \yii\helpers\Html::textInput(
                        \yii\helpers\Html::getInputName($object, 'attacks').'['.$index.'][start_value]',
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
			'value' => function ($data, $key, $index) use ($object){
					return \yii\helpers\Html::textInput(
						\yii\helpers\Html::getInputName($object, 'attacks').'['.$index.'][cost]',
						$data->cost,
						[
							'class' => 'form-control'
						]
					);
				},
		];
		$cols[]	 =	[
			'attribute' => 'is_active',
			'format' => 'raw',
			'value' => function ($data, $key, $index) use ($object){
                    $inputName = \yii\helpers\Html::getInputName($object, 'attacks').'['.$index.'][is_active]';
                    $inputValueInPost = isset($_POST['Object']['attacks'][$index]['is_active'])
                        ? $_POST['Object']['attacks'][$index]['is_active']
                        : false;

					return \yii\helpers\Html::checkbox(
                        $inputName,
                        $inputValueInPost ? true : $data->is_active,
						[
							'uncheck' => 0,
							'label' => 'Активний',
						]);
				}
		];

		return $cols;
	}
} 
