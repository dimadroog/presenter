<?php 
if (!$order) {
    $this->redirect(array('product/index'));
} 
?>
<?php
// echo '<pre>';
// var_dump($_SESSION['order']);
// var_dump($order->id);
// var_dump($order->customer->name);
// var_dump($order->json);
// var_dump($order->sum);
// var_dump(date('d.m.Y H:i' , $order->date));
// var_dump($order->status);
// echo '</pre>'; 
?>
<h1>Заказ обработан успешно.</h1>
<p>Уважаемый пользователь <b><?php echo $order->customer->name; ?></b>, Ваш заказ успешно обработан.</p>
<p>В ближайшее время мы свяжемся с Вами по указанному в заказе номеру телефона, и обсудим время доставки и условия оплаты.</p>

<h3>Детали заказа:</h3>
    <div class="table-responsive table-report">
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





<h4 class="text-prymary">Итоговая сумма*: <b><?php echo $order->sum; ?></b><span class="glyphicon glyphicon-ruble ruble" aria-hidden="true"></span></h4>
<p class="text-muted">*Итоговая сумма могла быть пересчитана, вследствие изменения остатков на складе во время формирования заказа.</p>
<br>
<p class=""><a href="<?php echo Yii::app()->createUrl('product/index/'); ?>">Вернуться в магазин</a></p>

