<?php
/**
 * Author: Pavel Naumenko
 */



echo \kartik\grid\GridView::widget(
	[
		'id' => 'object_attack_list',
		'dataProvider' => $provider,
		'filterModel' => null,
		'columns' => $cols,
        'export' => false
    ]
);

