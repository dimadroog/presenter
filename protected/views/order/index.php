 <h1>Упраление заказами</h1>
 <div class="row">
    <div class="col-sm-9">      

        <div class="panel panel-default">
            <div class="panel-heading">
                <h2 class="panel-title">
                Заказы:
                <?php if ($current_user): ?>
                    <a href="<?php echo Yii::app()->createUrl('customer/profile/'.$current_user->id); ?>"><?php echo $current_user->name; ?></a>
                <?php endif; ?>
                </h2>
            </div>
            <div class="panel-body">

                <?php foreach ($orders as $order):?> 
                    <div>
                        <h3>Клиент: <b><a href="<?php echo Yii::app()->createUrl('customer/profile/'.$order->customer->id); ?>"><?php echo $order->customer->name; ?></a></b></h3>
                        <p>Телефон: <span class="text-muted"> <?php echo $order->customer->phone; ?></span></p>
                        <p>Дата заказа <?php echo date('d.m.Y H:i' , $order->date); ?></p>
                        <p>Итоговая сумма: <?php echo $order->sum; ?><span class="glyphicon glyphicon-ruble ruble" aria-hidden="true"></span></p>
                        <p>Состояние заказа: <?php echo ($order->status == 1)?'<span id="status_span" class="text-success">Выполнен</span>':'<span id="status_span" class="text-danger">Ожидает выполнения</span>'; ?> <a onclick="ChangeStatus(this, <?php echo $order->id; ?>)"><span class="glyphicon glyphicon-refresh ruble cp" aria-hidden="true"></span></a></p>
                        <!-- btn btn-primary btn-xs -->
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
                    <br>
                <?php endforeach; ?>

                <?php if (!$current_user->orders && $current_user): ?>
                    <h2 class="text-muted">У этого клиента нет заказов.</h2>
                <?php endif; ?>

            </div>
        </div>


        <div class="text-center">
            <?php $this->widget('CLinkPager', array(
                'pages' => $pages,
                'header' => '',
                'firstPageLabel' => '<<',
                'lastPageLabel' => '>>',
                'nextPageLabel' => '>',
                'prevPageLabel' => '<',
                'selectedPageCssClass' => 'active',
                'maxButtonCount' => '3',
                'htmlOptions' => array('class' => 'pagination'),
            )) ?>
        </div>


    </div>
    <div class="col-sm-3 showcase">
        <div class="cat-head">
            <a href="<?php echo Yii::app()->createUrl('order/index/'); ?>" class="name-category"><b>Все клиенты</b></a>
        </div>
        <?php foreach ($users as $user): ?>
        <p>
            <a href="<?php echo Yii::app()->createUrl('order/index/', array('user'=>$user->id)); ?>"><?php echo $user->name; ?> (<?php echo count($user->orders); ?>)</a>

        </p>
        <?php endforeach; ?>
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
    function ChangeStatus(elm, order){
        var lnk = jQuery(elm);
        var span = lnk.parent().find('#status_span')
        c(order);
        jQuery.ajax({
            type: 'POST',
            url: '<?php echo Yii::app()->createUrl('order/changestatus/'); ?>',
            data: {'id': order},
            success: function(data){
                if (data == 1) {
                    span.removeClass('text-danger');
                    span.addClass('text-success');
                    span.html('Выполнен');
                } else {
                    span.removeClass('text-success');
                    span.addClass('text-danger');
                    span.html('Ожидает выполнения');
                };
            }, 
            error: function(){
                alert('error');
            }
        });
    }
</script>