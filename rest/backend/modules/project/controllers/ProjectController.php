<?php
/**
 * Author: Pavel Naumenko
 */

namespace backend\modules\project\controllers;

use backend\controllers\BackController;
use backend\modules\project\models\Project;

/**
 * Class ProjectController
 * @package backend\modules\project\controllers
 */
class ProjectController extends BackController
{
	public function getModel(){
		return Project::className();
	}
} 
