<?php
/**
 * Author: Pavel Naumenko
 *
 * @var $model \backend\components\BackModel
 */
use \kartik\form\ActiveForm;
use kartik\builder\Form;
use yii\helpers\Html;

$this->params['breadcrumbs'] = [
	[
		'label' => $model->getBreadCrumbRoot(),
		'url' => ['index']
	],
	'Редагування'
];

?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">Редагування</h3>
		</div>
		<?php echo $this->render('_form', ['model' => $model]) ?>
	</div>
<?php
//echo Form::widget([
//		'model'=>$model,
//		'form'=>$form,
//		'columns'=>1,
//		'attributes'=>[       // 1 column layout
//			'notes'=>['type'=>Form::INPUT_TEXTAREA, 'options'=>['placeholder'=>'Enter notes...']],
//		]
//	]);
//echo Form::widget([
//		'model'=>$model,
//		'form'=>$form,
//		'columns'=>3,
//		'attributes'=>[       // colspan example
//			'phone'=>['type'=>Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter phone number...']],
//			'address'=>[
//				'type'=>Form::INPUT_TEXT,
//				'options'=>['placeholder'=>'Enter address...'],
//				'columnOptions'=>['colspan'=>2]
//			],
//		]
//	]);
//echo Form::widget([
//		'model'=>$model,
//		'form'=>$form,
//		'columns'=>3,
//		'attributes'=>[       // 3 column layout
//			'birthday'=>['type'=>Form::INPUT_WIDGET, 'widgetClass'=>'\kartik\widgets\DatePicker', 'hint'=>'Enter birthday (mm/dd/yyyy)'],
//			'state_1'=>['type'=>Form::INPUT_DROPDOWN_LIST, 'items'=>$model->typeahead_data, 'hint'=>'Type and select state'],
//			'color'=>['type'=>Form::INPUT_WIDGET, 'widgetClass'=>'\kartik\widgets\ColorInput', 'hint'=>'Choose your color'],
//			'rememberMe'=>[   // radio list
//				'type'=>Form::INPUT_RADIO_LIST,
//				'items'=>[true=>'Yes', false=>'No'],
//				'options'=>['inline'=>true]
//			],
//			'brightness'=>[   // uses widget class with widget options
//				'type'=>Form::INPUT_WIDGET,
//				'label'=>Html::label('Brightness (%)'),
//				'widgetClass'=>'\kartik\widgets\RangeInput',
//				'options'=>['width'=>'80%']
//			],
//			'actions'=>[    // embed raw HTML content
//				'type'=>Form::INPUT_RAW,
//				'value'=>  '<div style="text-align: right; margin-top: 20px">' .
//					Html::resetButton('Reset', ['class'=>'btn btn-default']) . ' ' .
//					Html::submitButton('Submit', ['class'=>'btn btn-primary']) .
//					'</div>'
//			],
//		]
//	]);
