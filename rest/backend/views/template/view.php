<?php
/**
 * Author: Pavel Naumenko
 * @var $model \backend\components\BackModel
 */

$this->params['breadcrumbs'] = [
	[
		'label' => $model->getBreadCrumbRoot(),
		'url' => ['index']
	],
	'Перегляд'
];

?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Перегляд #<?= $model->id; ?></h3>
	</div>
	<div class="panel-body">
		<?php
		echo \yii\widgets\DetailView::widget([
				'model' => $model,
				'attributes' => $model->getViewColumns(true)
			]);
		?>
	</div>
</div>

