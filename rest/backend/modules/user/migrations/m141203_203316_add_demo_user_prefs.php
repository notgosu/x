<?php

use yii\db\Schema;
use yii\db\Migration;

class m141203_203316_add_demo_user_prefs extends Migration
{
    public function up()
    {
		$user = \backend\modules\user\models\User::find()->where('username="demo"')->one();
	    if ($user){
		    $user->role = \common\models\User::ROLE_ADMIN;
		    $user->save(false);

		    $userPref = \backend\modules\user\models\UserPrefs::find()->where('user_id=:uid', [':uid' => $user->id])->one();

		    if (!$userPref){
			    $userPref = new \backend\modules\user\models\UserPrefs();
			    $userPref->user_id = $user->id;
		    }
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
    }

    public function down()
    {
        echo "m141203_203316_add_demo_user_prefs cannot be reverted.\n";

        return false;
    }
}
