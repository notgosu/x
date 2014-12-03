<?php

use yii\db\Schema;
use yii\db\Migration;

class m141203_203316_add_demo_user_prefs extends Migration
{
	public function up()
	{
		$userPref = new \backend\modules\user\models\UserPrefs();
		$userPref->user_id = 1;
		$userPref->birthday = '1989-08-09';
		$userPref->sex = 0;
		$userPref->city = 'Киев';
		$userPref->company = 'Vintage';
		$userPref->appointment = 'Developer';
		$userPref->short_info = 'Short info';
		$userPref->phones = json_encode(array('(063) 407-76-15'));
		$userPref->skype = 'test';
		$userPref->site = 'test';

		$userPref->save(false);
	}

	public function down()
	{
		echo "m141203_203316_add_demo_user_prefs cannot be reverted.\n";

		return false;
	}
}
