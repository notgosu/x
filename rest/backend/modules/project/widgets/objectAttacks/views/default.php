<?php
/**
 * Author: Pavel Naumenko
 */



echo \yii\grid\GridView::widget(
	[
		'id' => 'object_attack_list',
		'dataProvider' => $provider,
		'filterModel' => null,
		'columns' => $cols
	]
);
