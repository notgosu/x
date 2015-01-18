<?php
/**
 * Author: Pavel Naumenko
 */
$object = new \backend\modules\project\models\Object();


echo \yii\grid\GridView::widget(
	[
		'id' => 'object_employee_list',
		'dataProvider' => $provider,
		'filterModel' => null,
		'columns' => [
				[
					'attribute' => 'employee_id',
					'format' => 'raw',
					'value' => function ($data, $key, $index) use ($object){
							$return = \yii\helpers\Html::hiddenInput(
								\yii\helpers\Html::getInputName($object, 'employees').'['.$index.'][employee_id]', $data->employee_id);
							$return .= $data->getEmployee()->one()->name;
							return $return;
						},
				],
				[
					'attribute' => 'access_type_id',
					'format' => 'raw',
					'value' => function ($data, $key, $index) use ($object){
							return \yii\helpers\Html::dropDownList(
								\yii\helpers\Html::getInputName($object, 'employees').'['.$index.'][access_type_id]',
								$data->access_type_id,
                                \yii\helpers\ArrayHelper::merge(
                                    ['' => 'вибeрiть тип доступу'],
                                    \yii\helpers\ArrayHelper::map(
                                        \backend\modules\project\models\EmployeeAccessType::find()->all(),
                                        'id',
                                        'name'
                                    )
                                ),
								[
									'class' => 'form-control'
								]
							);
					}
				],
				[
					'attribute' => 'is_active',
					'format' => 'raw',
					'value' => function ($data, $key, $index) use ($object){
                            $inputName = \yii\helpers\Html::getInputName($object, 'employees').'['.$index.'][is_active]';
                            $inputValueInPost = isset($_POST['Object']['employees'][$index]['is_active'])
                                ? $_POST['Object']['employees'][$index]['is_active']
                                : false;

							return \yii\helpers\Html::checkbox(
                                $inputName,
                                $inputValueInPost ? true : $data->is_active,
								[
									'uncheck' => 0,
									'label' => 'Активний',
								]);
						}
				],

		],
	]
);
