<?php
/**
 * Author: Pavel Naumenko
 */

namespace backend\modules\project\controllers;

use backend\controllers\BackController;
use backend\modules\project\models\EmployeePsychoType;

/**
 * Class EmployeePsychoController
 * @package backend\modules\project\controllers
 */
class EmployeePsychoController extends BackController
{
	public function getModel(){
		return EmployeePsychoType::className();
	}
} 
