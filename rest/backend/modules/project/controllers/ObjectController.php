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
} 
