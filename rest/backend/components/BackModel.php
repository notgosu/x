<?php
/**
 * Author: Pavel Naumenko
 */

namespace backend\components;

use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseStringHelper;
use yii\helpers\Html;
use yii\widgets\MaskedInput;

/**
 * Class BackModel
 * @package backend\components
 */
class BackModel extends ActiveRecord
{
	/**
	 * @param null $names
	 */
	public function unsetAttributes($names = null)
	{
		if ($names === null) {
			$names = $this->attributes();
		}
		foreach ($names as $name) {
			$this->$name = null;
		}
	}

	/**
	 * @param $params
	 *
	 * @return ActiveDataProvider
	 */
	public function search($params)
	{
		$query = static::find();
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		return $dataProvider;
	}

	/**
	 * @param bool $viewAction
	 *
	 * @return array
	 */
	public function getViewColumns($viewAction = false){
		return [];
	}


	/**
	 * @return array
	 */
	public function getFormRows(){
		return [];
	}

	/**
	 * @return int
	 */
	public function getColCount(){
		return 1;
	}

	/**
	 * @return string
	 */
	public function getBreadCrumbRoot(){
		return '';
	}

	/**
	 * @param $data
	 * @param $attribute
	 * @param $fieldToAppend
	 * @param $itemToCount
	 * @param $itemName
	 * @param $inputName
	 * @param bool $useMaskWidget
	 *
	 * @return string
	 */
	public function getClonableInputList(
		$data,
		$attribute,
		$fieldToAppend,
		$itemToCount,
		$itemName,
		$inputName,
		$useMaskWidget = false
	){
		$itemsCount = count($data->{$attribute});

		$itemToCountClass = str_replace('.', '', $itemToCount);
		$fieldToAppendClass = str_replace('.', '', $fieldToAppend);
		if (!$itemsCount) {
			$itemsCount = 1;
		}

		$errors = $this->getErrors();
		if (isset($errors[$attribute.'[0]'])){
			$error = isset($errors[$attribute.'[0]'][0]) ? $errors[$attribute.'[0]'][0] : '';
		}
		else{
			$error = '';
		}

		$itemsList = '<div class="form-group '.$fieldToAppendClass.' required '.($error ? ' has-error' : '').'">';
		$itemsList .= '<label class="control-label" for="'.$attribute.'_1">'.$itemName.'</label>';
		for ($i = 1; $i <= $itemsCount; $i++) {

			$itemsList .= '<div class="input-group">';
			if ($useMaskWidget){
				$itemsList .= MaskedInput::widget(
					[
						'mask' => '(999) 999-99-99',
						'model' => $data,
						'attribute' => $attribute.'['.($i-1).']',
						'options' => [
							'class' => 'form-control '.$itemToCountClass,
							'id' => Html::getInputId($data, $attribute).'_'.($i-1),
							'data-name' => $inputName,
							'data-item-to-count' => $itemToCount,
							'data-field-to-append' => $fieldToAppend
						]

					]
				);
			}
			else
			{
				$itemsList .= Html::activeTextInput($this, $attribute.'['.($i-1).']', [
						'class' => 'form-control '.$itemToCountClass,
						'id' => Html::getInputId($data, $attribute).'_'.($i-1),
						'data-name' => $inputName,
						'data-item-to-count' => $itemToCount,
						'data-field-to-append' => $fieldToAppend
					]);
			}




			if ($i > 1){
				$itemsList .= '<span class="input-group-addon"><a class="clonable-item-minus" href="#"><i class="glyphicon glyphicon-minus"></i></a></span></div>';
			}
			else{
				$itemsList .= '<span class="input-group-addon"><a class="clonable-item-plus" href="#"><i class="glyphicon glyphicon-plus"></i></a></span></div>';
			}

			if ($error && $i == 1){
				$itemsList .= '<div class="help-block">'.$error.'</div>';
			}
			else{
				$itemsList .= '<div class="help-block"></div>';

			}

		}
		$itemsList .= '</div>';

		return $itemsList;
	}
} 
