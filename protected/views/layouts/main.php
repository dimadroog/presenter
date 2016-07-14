<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="language" content="en">
	<meta name="viewport" content="width=device-width">

	<meta charset="utf-8" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/static/bootstrap/css/bootstrap_cosmo.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/static/css/style.css">
	<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/static/lightgallery/dist/css/lightgallery.css" >

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>
<a href="#" class="scrollup">Наверх</a> 

<nav class="navbar navbar-primary">
	<div class="container-fluid">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
				<span class="sr-only">Toggle navigation</span>
				<span class="glyphicon glyphicon-menu-hamburger" aria-hidden="true"></span>
			</button>
			<a class="navbar-brand" href="<?php echo Yii::app()->request->baseUrl; ?>">
				<span class="glyphicon glyphicon-star-empty" aria-hidden="true"></span>
			</a>
		</div>

		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav">
				<li><a href="<?php echo Yii::app()->createUrl('/product/'); ?>">Каталог</a></li>
				<?php if (Yii::app()->user->name == 'admin'):?>       
			        <li class="dropdown">
			          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Админ <span class="caret"></span></a>
			          <ul class="dropdown-menu">
						<li><a href="<?php echo Yii::app()->createUrl('/product/form/'); ?>">Добавить товары</a></li>
			            <li role="separator" class="divider"></li>
						<li><a href="<?php echo Yii::app()->createUrl('/product/manageproduct/'); ?>">Управление товарами</a></li>
			            <li role="separator" class="divider"></li>
						<li><a href="<?php echo Yii::app()->createUrl('/category/index/'); ?>">Управление категориями</a></li>
			            <li role="separator" class="divider"></li>
						<li><a href="<?php echo Yii::app()->createUrl('/order/index/'); ?>">Управление заказами</a></li>
			            <li role="separator" class="divider"></li>
						<li><a href="<?php echo Yii::app()->createUrl('/customer/index/'); ?>">Управление клиентами</a></li>
			          </ul>
			        </li>
				<?php endif ?>
				<?php if ((Yii::app()->user->name != 'admin') && (!Yii::app()->user->isGuest)):?>       
					<li><a href="<?php echo Yii::app()->createUrl('/customer/profile/'.Yii::app()->user->id); ?>">Профиль</a></li>
				<?php endif ?>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<?php if (!Yii::app()->user->isGuest):?>  
					<?php $user = Customer::model()->findByPk(Yii::app()->user->id); ?>     
					<?php $username = (Yii::app()->user->name == 'admin') ? Yii::app()->user->name : $user->name ?>     
			        <li><?php echo CHtml::link('Выйти ('.$username.')', array('/site/logout')); ?></li>
				<?php else: ?>
			        <li><?php echo CHtml::link('Войти', array('/customer/login')); ?></li>
				<?php endif ?> 
			</ul>
		</div><!-- /.navbar-collapse -->
	</div><!-- /.container-fluid -->
</nav>


<div class="container" id="page">

	<?php echo $content; ?>
<!-- 
	<div id="footer">
		Copyright &copy; <?php echo date('Y'); ?> by My Company.<br/>
		All Rights Reserved.<br/>
		<?php echo Yii::powered(); ?>
	</div>
 -->

</div><!-- page -->


<?php Yii::app()->getClientScript()->registerCoreScript('jquery');  ?> 	
<script src="//ajax.aspnetcdn.com/ajax/jquery.ui/1.10.3/jquery-ui.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/static/bootstrap/js/bootstrap.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/static/js/script.js"></script>

<script src="<?php echo Yii::app()->request->baseUrl; ?>/static/lightgallery/dist/js/lightgallery.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/static/lightgallery/dist/js/lg-fullscreen.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/static/lightgallery/dist/js/lg-thumbnail.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/static/lightgallery/dist/js/lg-autoplay.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/static/lightgallery/dist/js/lg-zoom.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/static/lightgallery/lib/jquery.mousewheel.min.js"></script>







</body>
</html>
