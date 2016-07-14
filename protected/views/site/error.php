<?php
$this->pageTitle=Yii::app()->name . ' - Error';
?>
<h2>Ошибка <?php echo $code; ?></h2>

<p><?php echo CHtml::encode($message); ?></p>
<hr>
<p><a href="<?php echo Yii::app()->createUrl('/'); ?>">Вернуться в каталог</a></p>
<p><a href="<?php echo Yii::app()->createUrl('site/contact'); ?>">Связаться с нами</a></p>