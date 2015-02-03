<?php
/**
 * Author: Pavel Naumenko
 */

namespace backend\modules\project\controllers;

use backend\controllers\BackController;
use backend\modules\project\models\AttackGroup;

/**
 * Class AttackGroupController
 * @package backend\modules\project\controllers
 */
class AttackGroupController extends BackController
{
    public function getModel()
    {
        return AttackGroup::className();
    }
} 
