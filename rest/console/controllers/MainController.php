<?php
/**
 * Author: Pavel Naumenko
 */

namespace console\controllers;

use yii\console\Controller;
use yii\db\Query;

/**
 * Class MainController
 * @package console\controllers
 */
class MainController extends Controller
{
    public function actionGroupAttack()
    {
        $attacks = (new Query())
            ->select('id, name')
            ->from('{{%attack}}');

        foreach ($attacks->each() as $attack) {
            $name = trim($attack['name']);
            $existAttackGroup = (new Query())
                ->select('id, label')
                ->from('{{%attack_group}}')
                ->where('label = :name', [':name' => $name])
                ->one();

            if ($existAttackGroup) {
                $groupId = $existAttackGroup['id'];
            } else {
                \Yii::$app->db->createCommand()->insert('{{%attack_group}}', [
                        'label' => $name,
                    ])->execute();
                $groupId = \Yii::$app->db->getLastInsertID();
            }

            \Yii::$app->db->createCommand()
                ->update('{{%attack}}', ['group_id' => $groupId], 'id = :id', [':id' => $attack['id']])
                ->execute();
        }
    }
} 
