<?php
use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
	<meta charset="<?= Yii::$app->charset ?>"/>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?= Html::csrfMetaTags() ?>
	<title><?= Html::encode($this->title) ?></title>
	<?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div class="wrap">
	<?php
	NavBar::begin(
		[
			'brandLabel' => 'App',
			'brandUrl' => Yii::$app->homeUrl,
			'options' => [
				'class' => 'navbar navbar-default navbar-fixed-top',
			],
		]
	);
	$menuItems = Yii::$app->user->isGuest
		? []
		: [
			['label' => 'Проект', 'url' => ['/project/project/index']],
			[
				'label' => 'Компанiя',
				'items' => [
					['label' => 'Компанiя', 'url' => ['/project/company/index']],
					['label' => 'Властивостi компанiї', 'url' => ['/project/company-attr/index']],
					['label' => 'Категорiї властивостей', 'url' => ['/project/company-attr-category/index']],
				]
			],
			[
				'label' => 'Спiвробiтник',
				'items' => [
					['label' => 'Спiвробiтник', 'url' => ['/project/employee/index']],
					['label' => 'Психотипи зловмисників', 'url' => ['/project/employee-psycho/index']],
					['label' => 'Типи доступу співробітників', 'url' => ['/project/employee-access-type/index']],
				]
			],
			[
				'label' => 'Об\'єкт',
				'items' => [
					['label' => 'Об\'єкт', 'url' => ['/project/object/index']],
					['label' => 'Тип об\'єкту', 'url' => ['/project/object-type/index']],
				],
			],
			[
				'label' => 'Атаки',
				'items' => [
					['label' => 'Атаки', 'url' => ['/project/attack/index']],
					['label' => 'Категорії атак', 'url' => ['/project/attack-category/index']],
					['label' => 'Значення категорій атак', 'url' => ['/project/attack-category-value/index']],
				]
			],
			['label' => 'Користувач', 'url' => ['/user/default/index']],

		];
	if (Yii::$app->user->isGuest) {
		echo Nav::widget(
			[
				'options' => ['class' => 'navbar-nav navbar-right'],
				'items' => [['label' => 'Login', 'url' => ['/site/login']]]
			]
		);
	} else {
		echo Nav::widget(
			[
				'options' => ['class' => 'navbar-nav navbar-right'],
				'items' => [
					[
						'label' => 'Вийти (' . Yii::$app->user->identity->username . ')',
						'url' => ['/site/logout'],
						'linkOptions' => ['data-method' => 'post']
					]
				]
			]
		);
	}
	echo Nav::widget(
		[
			'options' => ['class' => 'navbar-nav'],
			'items' => $menuItems,
		]
	);
	NavBar::end();
	?>

	<div class="container">
		<?=
		Breadcrumbs::widget(
			[
				'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
			]
		) ?>
		<?= $content ?>
	</div>
</div>

<footer class="footer">
	<div class="container">
		<p class="pull-left">&copy; Andrew Skyba <?= date('Y') ?></p>
		<p class="pull-right"><?= Yii::powered() ?></p>
	</div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
