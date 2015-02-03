<?php
/**
 * Author: Pavel Naumenko
 */

namespace backend\modules\project\controllers;

use backend\controllers\BackController;
use backend\modules\project\models\Attack;
use backend\modules\project\models\AttackGroup;
use backend\modules\project\models\Object;
use backend\modules\project\models\ObjectAttackParams;
use backend\modules\project\models\ObjectEmployeeParams;
use backend\modules\project\widgets\objectAttacks\ObjectAttacksWidget;
use backend\modules\project\widgets\objectEmployee\ObjectEmployeeWidget;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\Json;
use yii\web\Request;

/**
 * Class ObjectController
 *
 * @package backend\modules\project\controllers
 */
class ObjectController extends BackController
{
    public function getModel()
    {
        return Object::className();
    }

    public function actionGetEmployeeList($objectId)
    {

        $companyId = \Yii::$app->request->post('companyId');

        return Json::encode(
            [
                'replaces' => [
                    [
                        'what' => '#object_employee_list',
                        'data' => strlen($objectId) == 32
                                ? ObjectEmployeeWidget::widget(
                                    [
                                        'company_id' => $companyId,
                                        'temp_sign' => $objectId
                                    ]
                                )
                                : ObjectEmployeeWidget::widget(
                                    [
                                        'company_id' => $companyId,
                                        'object_id' => $objectId
                                    ]
                                )
                    ]
                ]
            ]

        );
    }

    public function actionGetAttackList($objectId)
    {

        $objTypeId = \Yii::$app->request->post('object_type_id');

        $tempSign = strlen($objectId) == 32;

        return Json::encode(
            [
                'replaces' => [
                    [
                        'what' => '#object_attack_list',
                        'data' => $this->renderAjax('object_attacks', [
                                    'objectId' => $objectId,
                                    'objTypeId' => $objTypeId,
                                    'tempSign' => $tempSign
                                ])
                    ]
                ]
            ]

        );
    }

    public function actionViewResult($id)
    {
        $model = $this->loadModel($id);
        $resultArr = $model->getFinalResultProvider();

        $columns = [
            'id',
            'Назва атаки',
            'Об`єкт',
            'Спiвробiтник',
        ];
        $columns = \yii\helpers\ArrayHelper::merge(
            $columns,
            \backend\modules\project\models\Attack::getAttackParams(null, true)
        );
        $columns = \yii\helpers\ArrayHelper::merge(
            $columns,
            [
                'Сума атаки',
                'cc',
                'kt',
                'w',
                'a',
                'z'
            ]
        );

        $provider = new ArrayDataProvider([
            'allModels' => $resultArr,
            'sort' => [
                'attributes' => $columns,
                'defaultOrder' => ['z' => SORT_DESC]
            ],
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('result', ['provider' => $provider, 'cols' => $columns]);
    }

    public function actionGetAttackForGroup()
    {
        $groupId = \Yii::$app->request->post('expandRowKey');
        $objectId = \Yii::$app->request->get('objectId');
        $objectTypeId = \Yii::$app->request->get('objectTypeId');

        if ($objectId && $objectTypeId)
        {
            if (is_numeric($objectId))
            {
                $tempSign = null;
            } else {
                $tempSign = $objectId;
                $objectId = null;
            }

            ObjectAttackParams::checkForExistParams($objectId, $objectTypeId, $tempSign);

            if ($groupId) {
                $query = ObjectAttackParams::find()
                    ->joinWith([
                            'attack' => function ($query) {
                                    return $query->joinWith(['group']);
                                }
                        ])
                    ->where(Attack::tableName().'.object_type_id = :tid', [':tid' => $objectTypeId])
                    ->andWhere(Attack::tableName().'.group_id = :gid', [':gid' => $groupId]);

                if ($objectId){
                    $query->andWhere('object_id=:oid', [':oid' => $objectId]);
                }
                elseif($tempSign){
                    $query->andWhere('temp_sign=:ts', [':ts' => $tempSign]);
                }

                $dataProvider = new ActiveDataProvider([
                    'query' => $query,
                    'pagination' => false,
                    'sort' => false
                ]);


                return GridView::widget(
                    [
                        'id' => 'object_attack_list'.$groupId,
                        'dataProvider' => $dataProvider,
                        'filterModel' => null,
                        'columns' => ObjectAttackParams::getAttackColsForGrid()
                    ]
                );
            }
        }

        return 'Атаки не знайдено';
    }
} 
