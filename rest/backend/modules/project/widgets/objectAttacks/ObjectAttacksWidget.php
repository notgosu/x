<?php
/**
 * Author: Pavel Naumenko
 */

namespace backend\modules\project\widgets\objectAttacks;

use backend\modules\project\models\Attack;
use backend\modules\project\models\AttackCategory;
use backend\modules\project\models\AttackGroup;
use backend\modules\project\models\ObjectAttackParams;
use kartik\grid\ExpandRowColumn;
use kartik\grid\GridView;
use yii\base\Widget;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

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



        $query = AttackGroup::find()
            ->joinWith(['attack'])
            ->where(Attack::tableName().'.object_type_id = :otid', [':otid' => $this->object_type_id]);

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'sort' => false,
            'pagination' => false
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
        $objectIdentifier = $this->object_id ? $this->object_id : $this->temp_sign;

		$cols[] = [
            'header' => 'Категорiя',
			'attribute' => 'attack_id',
			'format' => 'raw',
			'value' => function ($data, $key, $index){
					return $data->label;
				},
		];

        $cols[] = [
            'class' => ExpandRowColumn::className(),
            'value' => function ($model, $key, $index, $column) {
                    return GridView::ROW_COLLAPSED;
                },
            'expandIcon' => Html::tag('span', '', ['class' => 'glyphicon glyphicon-chevron-right']),
            'collapseIcon' => Html::tag('span', '', ['class' => 'glyphicon glyphicon-chevron-down']),
            'detailUrl' => Url::to([
                        '/project/object/get-attack-for-group',
                        'objectTypeId' => $this->object_type_id,
                        'objectId' => $objectIdentifier
                    ])
        ];

		return $cols;
	}
} 
