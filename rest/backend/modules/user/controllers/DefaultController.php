<?php

namespace backend\modules\user\controllers;

use backend\controllers\BackController;
use backend\modules\user\models\User;

/**
 * Class DefaultController
 *
 * @package backend\modules\user\controllers
 */
class DefaultController extends BackController
{

	public function getModel(){
		return User::className();
	}
}
