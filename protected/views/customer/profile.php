<h1>Профиль пользователя <?php echo $user->name; ?></h1>
<?php 
if (Yii::app()->user->hasFlash('changedata')){
    echo '<div class="panel panel-success"><div class="panel-body">'.Yii::app()->user->getFlash('changedata').'</div></div>';
} 
if (Yii::app()->user->hasFlash('changepass')){
    echo '<div class="panel panel-success"><div class="panel-body">'.Yii::app()->user->getFlash('changepass').'</div></div>';
} 
?>
<br>

<div class="row">
    <div class="col-sm-3">
        <p>Телефон: <span class="text-muted"> <?php echo $user->phone; ?></span></p>
        <p>Всего сделано заказов: <?php echo count($user->orders); ?></p>
        <p>На общую сумму: <?php echo $total_sum; ?><span class="glyphicon glyphicon-ruble ruble" aria-hidden="true"></span></p>
        <p><a href="<?php echo Yii::app()->createUrl('customer/changedata/', array('id'=>$user->id)); ?>">Изменить данные</a></p>
        <p><a href="<?php echo Yii::app()->createUrl('customer/changepass/', array('id'=>$user->id)); ?>">Изменить пароль</a></p>
    </div>
    <div class="col-sm-9">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h2 class="panel-title">Заказы:</h2>
            </div>
            <div class="panel-body">

                <?php foreach ($orders as $order):?> 
                    <div>
                        <p>Дата заказа <?php echo date('d.m.Y H:i' , $order->date); ?></p>
                        <p>Итоговая сумма: <?php echo $order->sum; ?><span class="glyphicon glyphicon-ruble ruble" aria-hidden="true"></span></p>
                        <p>Состояние заказа: <?php echo ($order->status == 1)?'<span class="text-success">Выполнен</span>':'<span class="text-danger">Ожидает выполнения</span>'; ?></p>
                        <p><a onclick="CollapseTable(this)">Развернуть</a></p>

                        <div id="collapse_tbl" class="table-responsive table-report dn">
                            <table> 
                                <thead> 
                                    <tr> 
                                        <th>Артикул</th> 
                                        <th>Цена</th> 
                                        <th>Размер</th>
                                        <th>Количество</th>
                                        <th>Сумма</th>
                                        <th>Прим.</th>
                                    </tr> 
                                </thead> 
                                <tbody>
                                    <?php foreach (json_decode($order->json, true) as $item):?> 
                                        <tr> 
                                            <td><?php echo $item['id']; ?></td>
                                            <td><?php echo $item['price']; ?></td>
                                            <td><?php echo $item['size']; ?></td>
                                            <td><?php echo $item['count']; ?></td>
                                            <td><?php echo $item['sum']; ?></td>
                                            <td><?php echo $item['note']; ?></td>
                                        </tr> 
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <hr>
                <?php endforeach; ?>
                <?php if (!$user->orders): ?>
                    <h2 class="text-muted">Пока нет заказов.</h2>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>



<script type="text/javascript">
    function CollapseTable(elm){
        var tbl = jQuery(elm).parent().parent().find('#collapse_tbl');
        if (tbl.css("display") == "none"){
            tbl.slideDown(200);
            jQuery(elm).html('Свернуть');
        } else {
            tbl.slideUp(200);
            jQuery(elm).html('Развернуть');
        }
    }
</script>


<?php         
// echo '<pre>';
// var_dump(Yii::app()->user->id);
// var_dump($user->id);
// var_dump($user->name);
// var_dump($user->phone);
// var_dump($user->password);
// echo '</pre>'; 
?>    