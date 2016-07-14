
<h1>Управление пользователями</h1>
<div class="table-responsive">
    <table class="table table-striped" id="lightgallery" > 
        <thead> 
            <tr> 
                <th>Имя</th> 
                <th>Телефон</th> 
                <th>Пароль</th>
                <th>Прим.</th>
                <th>Заказов</th>
                <th>Общая сумма</th>
                <th>Действие</th>
            </tr> 
        </thead> 
        <tbody>
            <?php foreach ($users as $user):?> 
                <tr> 
                    <td><a href="<?php echo Yii::app()->createUrl('customer/profile/'.$user->id); ?>"><?php echo $user->name; ?></a></td>
                    <td><?php echo $user->phone; ?></td>
                    <td><a onclick="ShowPass(this)">Показать</a><span onclick="HidePass(this)" class="dn"><?php echo $user->password; ?></span></td>
                    <!-- <td><?php echo $user->password; ?></td> -->
                    <td><?php echo $user->note; ?></td>
                    <td><?php echo count($user->orders); ?></td>
                    <td><?php Customer::TotalSum($user->id); ?></td>
                    <td>
                        <a href="<?php echo Yii::app()->createUrl('customer/changedata/', array('id'=>$user->id)); ?>"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                        <a onclick="DeleteCustomer(this, <?php echo $user->id; ?>)"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a> 
                    </td>
                </tr> 
            <?php endforeach; ?>
        </tbody>
    </table>
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
    )); ?>
</div>


<script type="text/javascript">
    function DeleteCustomer(elm, user){
        var lnk = jQuery(elm);
        var tr = elm.closest('tr');
        c(jQuery(tr));
        jQuery.ajax({
            type: 'POST',
            url: '<?php echo Yii::app()->createUrl('customer/delete/'); ?>',
            data: {'id': user},
            success: function(data){
                jQuery(tr).fadeOut(700);
            }, 
            error: function(){
                alert('error');
            }
        });
    }

    function ShowPass(elm){
        var lnk = jQuery(elm);
        var span = lnk.parent().find('span');
        span.show();
        lnk.hide();
    }

    function HidePass(elm){
        var span = jQuery(elm);
        var lnk = span.parent().find('a');
        span.hide();
        lnk.show();
    }
</script>

