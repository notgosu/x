<?php
/**
 * Author: Pavel Naumenko
 *
 * @var $model \backend\components\BackModel
 */

use \kartik\form\ActiveForm;
use kartik\builder\Form;
use yii\helpers\Html;
?>


<div class="panel-body">
	<?php
    echo Html::errorSummary(
		$model,
		[
			'class' => 'alert alert-danger'
		]
	); ?>
	<?php
	$form = ActiveForm::begin(
		[
			'type' => ActiveForm::TYPE_VERTICAL,
			'enableClientValidation' => false
		]
	);

	echo Form::widget(
		[
			'model' => $model,
			'form' => $form,
			'columns' => $model->getColCount(),
			'attributes' => $model->getFormRows()
		]
	);
	?>
	<div class="row">
		<div class="col-sm-12">
			<?php
			echo Html::resetButton('Скинути', ['class' => 'btn btn-default']) . ' ' .
			Html::submitButton('Зберегти', ['class' => 'btn btn-primary'])
			?>
		</div>
	</div>
	<?php
	ActiveForm::end();
    ?>
</div>
