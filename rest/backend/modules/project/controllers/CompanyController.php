<?php
/**
 * Author: Pavel Naumenko
 */

namespace backend\modules\project\controllers;

use backend\controllers\BackController;
use backend\modules\project\models\Company;

/**
 * Class CompanyController
 * @package backend\modules\project\controllers
 */
class CompanyController extends BackController
{
	public function getModel(){
		return Company::className();
	}
} 
