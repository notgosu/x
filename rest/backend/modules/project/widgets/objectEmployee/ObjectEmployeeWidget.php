<?php
/**
 * Author: Pavel Naumenko
 */

namespace backend\modules\project\widgets\objectEmployee;

use backend\modules\project\models\ObjectEmployeeParams;
use yii\base\Widget;
use yii\data\ActiveDataProvider;

/**
 * Class ObjectEmployeeWidget
 * @package backend\modules\project\widgets\objectEmployee
 */
class ObjectEmployeeWidget extends Widget
{
	public $object_id;

	public $company_id;

	public $temp_sign = null;

	public function run(){
		ObjectEmployeeParams::checkForExistParams($this->object_id, $this->company_id, $this->temp_sign);

		$query = ObjectEmployeeParams::find()
			->joinWith('employee')
			->where('employee.company_id=:cid', [':cid' => $this->company_id]);

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


		return $this->render('default', ['provider' => $dataProvider]);
	}
} 
