<?php
/**
 * Author: Pavel Naumenko
 */

namespace backend\modules\project\controllers;

use backend\controllers\BackController;
use backend\modules\project\models\CompanyAttribute;

/**
 * Class CompanyAttrController
 * @package backend\modules\project\controllers
 */
class CompanyAttrController extends BackController
{
	public function getModel(){
		return CompanyAttribute::className();
	}
} 
