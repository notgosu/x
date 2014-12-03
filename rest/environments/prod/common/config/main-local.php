<?php
/**
 * Local config for developer of environment.
 *
 * @author Evgeniy Tkachenko <et.coder@gmail.com>
 */

return [
	'language' => 'en',
	'components' => [
		'db' => [
			'class' => '\yii\db\Connection',
			'dsn' => 'mysql:host=127.0.0.1;dbname=x',
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
		],
	],
	'params' => [
		'adminEmail' => 'admin@example.com',
	],
];
