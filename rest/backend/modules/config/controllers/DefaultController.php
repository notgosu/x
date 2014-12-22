<?php

namespace backend\modules\config\controllers;

use backend\controllers\BackController;
use backend\modules\config\models\Configuration;

class DefaultController extends BackController
{
    public function getModel(){
        return Configuration::className();
    }
}
