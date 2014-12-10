<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
	'controllerMap'       => [
		'migrate' => [
			'class' => 'dmstr\console\controllers\MigrateController'
		],
	],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
    ],
	'params' => [
		"yii.migrations"=> [
			"@backend/modules/user/migrations",
			"@backend/modules/project/migrations",
		],
	]
];
