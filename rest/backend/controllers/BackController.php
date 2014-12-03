<?php
/**
 * Author: Pavel Naumenko
 */

namespace backend\controllers;

use common\models\User;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

/**
 * Class BackController
 * @package backend\controllers
 */
class BackController extends Controller
{
	/**
	 * @return array
	 */
	public function behaviors(){
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'actions' => ['login', 'error'],
						'allow' => true,
					],
					[
						'allow' => true,
						'matchCallback' => function($rule, $action){
								return !\Yii::$app->user->isGuest &&
								\Yii::$app->user->identity->role == User::ROLE_ADMIN;
							}
					],
				],
			],
		];
	}

	/**
	 * @return \backend\components\BackModel
	 * @throws \yii\base\Exception
	 */
	public function getModel(){
		throw new Exception(500, 'Need to implement "getModel" method');
	}

	/**
	 * @return string
	 */
	public function actionIndex(){
		$class = $this->getModel();

		/**
		 * @var $model \backend\components\BackModel
		 */
		$model = new $class();

		$dataProvider = $model->search(\Yii::$app->request->queryParams);

		return $this->render('//template/index', [
				'searchModel' => $model,
				'dataProvider' => $dataProvider,
			]);
	}

	/**
	 * @return string|\yii\web\Response
	 */
	public function actionCreate()
	{
		$class = $this->getModel();
		/**
		 * @var $model \backend\components\BackModel
		 */
		$model = new $class();
		$model->loadDefaultValues();

		if ($model->load(\Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['index']);
		} else {
			return $this->render('//template/create', [
					'model' => $model,
				]);
		}
	}

	/**
	 * @param $id
	 *
	 * @return string|\yii\web\Response
	 * @throws \yii\web\HttpException
	 */
	public function actionUpdate($id)
	{
		$model = $this->loadModel($id);
		$isSaved = false;

		$transaction = \Yii::$app->db->beginTransaction();
		try {
			$isSaved = $model->load(\Yii::$app->request->post()) && $model->save();
			$transaction->commit();
		} catch (Exception $e) {
			$transaction->rollBack();
		}

		if ($isSaved) {
			return $this->redirect(['index']);
		} else {
			return $this->render('//template/update', [
					'model' => $model,
				]);
		}
	}


	/**
	 * @param $id
	 *
	 * @return string
	 */
	public function actionView($id){
		$model = $this->loadModel($id);

		return $this->render(
			'//template/view',
			[
				'model' => $model,
			]
		);
	}

	/**
	 * @param $id
	 *
	 * @throws \yii\base\ErrorException
	 */
	public function actionDelete($id){
		$model = $this->loadModel($id);

		if (!$model->delete()){
			throw new ErrorException;
		}

		$this->redirect(['index']);
	}

	/**
	 * @param $id
	 *
	 * @return \backend\components\BackModel
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function loadModel($id){
		$class = $this->getModel();
		/**
		 * @var $model \backend\components\BackModel
		 */
		$model = new $class();
		$model = $model->findOne(['id' => (int)$id]);

		if ($model){
			return $model;
		}
		else{
			throw new NotFoundHttpException;
		}
	}
} 
