<?php
/**
 * Author: Pavel Naumenko
 */

namespace backend\modules\project\controllers;

use backend\controllers\BackController;
use backend\modules\project\models\AttackCategoryValue;

/**
 * Class AttackCategoryValueController
 * @package backend\modules\project\controllers
 */
class AttackCategoryValueController extends BackController
{
	public function getModel(){
		return AttackCategoryValue::className();
	}
} 
