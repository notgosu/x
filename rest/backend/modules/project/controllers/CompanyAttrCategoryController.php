<?php
/**
 * Author: Pavel Naumenko
 */

namespace backend\modules\project\controllers;

use backend\controllers\BackController;
use backend\modules\project\models\CompanyAttributeCategory;

/**
 * Class CompanyAttrCategoryController
 * @package backend\modules\project\controllers
 */
class CompanyAttrCategoryController extends BackController
{
	public function  getModel(){
		return CompanyAttributeCategory::className();
	}
} 
