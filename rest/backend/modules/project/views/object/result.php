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
                'columns' => $cols,
                'rowOptions' => function ($model, $key, $index, $grid){
                        if ($model['z'] < Yii::$app->config->get('GreenValue', 0.37)){
                            return ['class' => 'green-color'];
                        }
                        elseif($model['z'] < Yii::$app->config->get('YellowValue', 0.37)){
                            return ['class' => 'yellow-color'];
                        }
                        else{
                            return ['class' => 'red-color'];
                        }
                    }
            ]
        );

        ?>
    </div>
</div>

