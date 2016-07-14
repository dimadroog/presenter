<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle=Yii::app()->name . ' - Login';
$this->breadcrumbs=array(
	'Login',
);
?>

<h1>Вход</h1>



<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'login-form',
	'enableClientValidation'=>false,
	'clientOptions'=>array(
		// 'validateOnSubmit'=>true,
	),
)); ?>

	<div class="form-group">
		<label><?php echo $form->labelEx($model,'username'); ?></label>
		<?php echo $form->textField($model,'username', array('class' => 'form-control')); ?>
		<div class="text-danger"><?php echo $form->error($model,'username'); ?></div>
    </div>

	<div class="form-group">
		<label><?php echo $form->labelEx($model,'password'); ?></label>
		<?php echo $form->passwordField($model,'password', array('class' => 'form-control')); ?>
		<div class="text-danger"><?php echo $form->error($model,'password'); ?></div>
    </div>


	<div class="form-group">
		<?php echo $form->checkBox($model,'rememberMe'); ?>
		<label><?php echo $form->labelEx($model,'rememberMe'); ?></label>
		<div class="text-danger"><?php echo $form->error($model,'rememberMe'); ?></div>
    </div>

	<?php echo CHtml::submitButton('Войти', array('class' => 'btn btn-primary')); ?>


<?php $this->endWidget(); ?>
</div><!-- form -->
