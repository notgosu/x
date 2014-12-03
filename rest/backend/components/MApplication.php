<?php
/**
 * Author: Pavel Naumenko
 */ 
namespace backend\components;

/**
 * Class MApplication
 *
 * @package backend\components
 */
class MApplication extends \yii\web\Application{

	/**
	 * @inheritdoc
	 */
	public function setVendorPath($path)
	{
		parent::setVendorPath($path);

		\Yii::setAlias('@bower', $this->getVendorPath() . DIRECTORY_SEPARATOR . 'bower-asset');

	}
}
