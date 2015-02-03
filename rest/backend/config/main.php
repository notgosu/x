<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
	'language' => 'uk',
    'modules' => [
        'gridview' =>  [
            'class' => \kartik\grid\Module::className(),
            // enter optional module parameters below - only if you need to
            // use your own export download action or custom translation
            // message source
            // 'downloadAction' => 'gridview/export/download',
            // 'i18n' => []
        ],
	    'project' => [
		    'class' => 'backend\modules\project\Project',
	    ],
	    'user' => [
		    'class' => 'backend\modules\user\User'
	    ],
        'config' => [
            'class' => 'backend\modules\config\Config',
        ],
    ],
    'components' => [
        'config' => 'backend\modules\config\components\ConfigurationComponent',
	    'urlManager' => [
		    'enablePrettyUrl' => true,
		    'showScriptName' => false,
	    ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
    ],
    'params' => $params,
];
