<?php
/**
 * Author: Pavel Naumenko
 */

namespace backend\modules\project\controllers;

use backend\controllers\BackController;
use backend\modules\project\models\EmployeeAccessType;

/**
 * Class EmployeeAccessTypeController
 * @package backend\modules\project\controllers
 */
class EmployeeAccessTypeController extends BackController
{
	public function getModel(){
		return EmployeeAccessType::className();
	}
} 
