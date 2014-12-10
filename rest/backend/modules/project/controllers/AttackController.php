<?php
/**
 * Author: Pavel Naumenko
 */

namespace backend\modules\project\controllers;

use backend\controllers\BackController;
use backend\modules\project\models\Attack;

/**
 * Class AttackController
 * @package backend\modules\project\controllers
 */
class AttackController extends BackController
{
	public function getModel(){
		return Attack::className();
	}
} 
