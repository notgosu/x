<?php
/**
 * Author: Pavel Naumenko
 */

namespace backend\modules\project\controllers;

use backend\controllers\BackController;
use backend\modules\project\models\Object;
use backend\modules\project\models\ObjectEmployeeParams;
use backend\modules\project\widgets\objectAttacks\ObjectAttacksWidget;
use backend\modules\project\widgets\objectEmployee\ObjectEmployeeWidget;
use yii\data\ArrayDataProvider;
use yii\helpers\Json;
use yii\web\Request;

/**
 * Class ObjectController
 * @package backend\modules\project\controllers
 */
class ObjectController extends BackController
{
	public function getModel(){
		return Object::className();
	}

	public function actionGetEmployeeList($objectId){

		$companyId = \Yii::$app->request->post('companyId');

		return Json::encode(
			[
				'replaces' => [
					[
						'what' => '#object_employee_list',
						'data' => strlen($objectId) == 32
								? ObjectEmployeeWidget::widget([
										'company_id' => $companyId,
										'temp_sign' => $objectId
									])
								: ObjectEmployeeWidget::widget([
									'company_id' => $companyId,
									'object_id' => $objectId
								])
					]
				]
			]

		);
	}

	public function actionGetAttackList($objectId){

		$objTypeId = \Yii::$app->request->post('object_type_id');

		return Json::encode(
			[
				'replaces' => [
					[
						'what' => '#object_attack_list',
						'data' => strlen($objectId) == 32
								? ObjectAttacksWidget::widget([
										'object_type_id' => $objTypeId,
										'temp_sign' => $objectId
									])
								: ObjectAttacksWidget::widget([
										'object_type_id' => $objTypeId,
										'object_id' => $objectId
									])
					]
				]
			]

		);
	}

    public function actionViewResult($id){
        $model = $this->loadModel($id);
        $resultArr = $model->getFinalResultProvider();

//        $resultArr = [
//            [
//                'id' => 1,
//                'attack_name' => 'Атака 1',
//                'object' => 'Обьект 213',
//                'employee' => 'Петро',
//                'attack_param1' => 'Перш параметр',
//                'attack_param2' => 'Другий параметр',
//                'attack_param3' => 'Третiй параметр',
//                'attack_access' => 'Доступ 1',
//                'amount' => '20000',
//                'kt' => '0.323',
//                'a' => '0.7',
//                'z' => '0.234'
//            ],
//            [
//                'id' => 2,
//                'attack_name' => 'Атака 2',
//                'object' => 'Обьект 213',
//                'employee' => 'ПАвто',
//                'attack_param1' => 'Перш парауцйметр',
//                'attack_param2' => 'Другий парамуйцетр',
//                'attack_param3' => 'Третiй параметуцйр',
//                'attack_access' => 'Доступ 2',
//                'amount' => '20023130',
//                'kt' => '0.33213',
//                'a' => '0.23',
//                'z' => '0.44'
//            ],
//            [
//                'id' => 4,
//                'attack_name' => 'Атака 3',
//                'object' => 'Обьект 213',
//                'employee' => 'Афй',
//                'attack_param1' => 'Перш парамуйцетр',
//                'attack_param2' => 'ц параметр',
//                'attack_param3' => 'Треуйтiй параметр',
//                'attack_access' => 'Доступ 3',
//                'amount' => '5000',
//                'kt' => '0.223',
//                'a' => '0.23',
//                'z' => '0.6934'
//            ]
//        ];

        $provider = new ArrayDataProvider([
            'allModels' => $resultArr,
            'sort' => [
                'attributes' => ['id', 'employee', 'amount'],
            ],
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('result', ['provider' => $provider]);
    }
} 
