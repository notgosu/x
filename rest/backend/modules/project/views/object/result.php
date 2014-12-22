<?php
/**
 *
 * @var $provider \yii\data\ArrayDataProvider
 * @var $searchModel \backend\components\BackModel
 * Author: Pavel Naumenko
 */
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Результати</h3>
    </div>

    <div class="panel-body">
        <?php
        echo \yii\grid\GridView::widget(
            [
                'dataProvider' => $provider,
                'filterModel' => null,
                'columns' => [
                            'id',
                            'attack_name',
                            'object',
                            'employee',
//                            'attack_param1',
//                            'attack_param2',
//                            'attack_param3',
//                            'attack_access',
                            'amount',
                            'cc',
                            'kt',
                            'w',
                            'a',
                            'z'
                        ]
            ]
        );

        ?>
    </div>
</div>

