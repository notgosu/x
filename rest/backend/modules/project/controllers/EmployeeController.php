<?php
/**
 * Author: Pavel Naumenko
 */

namespace backend\modules\project\controllers;

use backend\controllers\BackController;
use backend\modules\project\models\Employee;

/**
 * Class EmployeeController
 * @package backend\modules\project\controllers
 */
class EmployeeController extends BackController
{
	public function getModel(){
		return Employee::className();
	}
} 
