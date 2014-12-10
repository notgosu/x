<?php
/**
 * Author: Pavel Naumenko
 */

namespace backend\modules\project\controllers;

use backend\controllers\BackController;
use backend\modules\project\models\ObjectType;

/**
 * Class ObjectTypeController
 * @package backend\modules\project\controllers
 */
class ObjectTypeController extends BackController
{
	public function getModel(){
		return ObjectType::className();
	}
} 
