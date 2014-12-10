<?php
/**
 * Author: Pavel Naumenko
 */

namespace backend\modules\project\controllers;

use backend\controllers\BackController;
use backend\modules\project\models\AttackCategory;

/**
 * Class AttackCategoryController
 * @package backend\modules\project\controllers
 */
class AttackCategoryController extends BackController
{
	public function getModel(){
		return AttackCategory::className();
	}
} 
