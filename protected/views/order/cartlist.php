<h1>Корзина</h1>
<?php if(Yii::app()->LavrikShoppingCart->sum == 0): ?>
    <p class="text-muted">Ваша корзина пуста. <a href="<?php echo Yii::app()->createUrl('product/index/'); ?>">Вернуться в каталог</a></p>
<?php else: ?>
    <?php       
        // echo '<pre>';
        // print_r($cart);
        // echo '</pre>'; 
        // echo '<pre>';
        // var_dump(Yii::app()->user->id);
        // echo '</pre>'; 

    ?>
    <div class="table-responsive">
        <table class="table table-striped" id="lightgallery" > 
            <thead> 
                <tr> 
                    <th>Артикул</th> 
                    <th>Фото</th> 
                    <th>Цена</th> 
                    <th>Размер</th>
                    <th>Количество</th>
                    <th>Сумма</th>
                    <th>Действия</th>
                </tr> 
            </thead> 
            <tbody>
                <?php foreach ($cart as $key => $item):?> 
                    <?php $prod = Product::model()->findByPk($item['id']) ?>
                    <?php $subtitle = '<div class="row">
                                            <div class="col-md-6 col-md-offset-3 text-left">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <p>Артикул: '.$prod->id.'</p>       
                                                        <p>Цена: '.$prod->price.'</p>       
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <p>Категория: '.$prod->categ->name.'</p>       
                                                        <p>Прим.: '.$prod->note.'</p> 
                                                    </div>
                                                </div>
                                            </div>
                                        </div>'; ?>
                    <tr> 
                        <th scope="row"><?php echo $prod->id; ?></th>
                        <td>
                           <div class="selector"
                            data-src="<?php echo Yii::app()->request->baseUrl.'/images/md_'.$prod->image; ?>" 
                            data-sub-html='<?php echo $subtitle; ?>'>
                                <a href="">
                                    <img class="img-responsive admin-thumb" src="<?php echo Yii::app()->request->baseUrl.'/images/sm_'.$prod->image; ?>">
                                </a>
                            </div>
                        </td>
                        <td class="text-success"><?php echo $prod->price; ?></td>
                        <td><?php echo $item['size']; ?></td>
                        <td><?php echo $item['count']; ?></td>
                        <td><?php echo $item['count']*$item['price']; ?></td>
                        <td>
                            <a class="" href="<?php echo Yii::app()->createUrl('order/deleteitem/', array('key'=>$key)); ?>">Удалить</a> 
                        </td>
                    </tr> 
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <p><a href="<?php echo Yii::app()->createUrl('product/index/'); ?>">Добавить еще товаров</a></p> 
            <p><a href="<?php echo Yii::app()->createUrl('order/clear/'); ?>">Очистить корзину</a></p> 
        </div>
        <div class="col-sm-6">
            <p class="text-right text-muted">Наименований: <span class="cart_itm"><?php echo Yii::app()->LavrikShoppingCart->count_of_different_products; ?></span></p>
            <p class="text-right text-muted">Позиций: <span class="cart_pos"><?php echo Yii::app()->LavrikShoppingCart->count_in_basket; ?></span> шт.</p>
            <p class="text-right text-muted">На сумму: <span class="cart_sum"><?php echo Yii::app()->LavrikShoppingCart->sum; ?></span> руб.</p>
        </div>
    </div>
    <hr>
    <h2>Оформить заказ</h2>
    <?php if (Yii::app()->user->name == 'admin'): ?>
        <form action="<?php echo Yii::app()->createUrl('order/order/'); ?>" onsubmit="return CheckRequiredAdmin()" method="post">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2 class="panel-title">Выбрать клиента</h2>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label class="control-label" for="id_select">Клиент: <span class="text-danger">*</span></label>
                        <select class="form-control" type="text" id="id_select" name="select" onchange="FillInputs(this)">
                            <option value="" disabled selected>Выбрать</option>
                            <?php foreach ($users as $user):?>
                                <!-- <option value="<?php echo $user->id; ?>"><?php echo $user->name; ?></option> -->
                                <option value='{
                                "id": "<?php echo $user->id; ?>", 
                                "name": "<?php echo $user->name; ?>", 
                                "phone": "<?php echo $user->phone; ?>",
                                "note": "<?php echo $user->note; ?>"
                                }'>
                                    <?php echo $user->name; ?> : <?php echo $user->phone; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <input class="form-control" type="hidden" id="id_id" name="id" value="">
                        <input class="form-control" type="hidden" id="id_name" name="name" value="">
                        <input class="form-control" type="hidden" id="id_phone" name="phone" value="">
                        <input class="form-control" type="hidden" id="id_note" name="note" value="">
                    </div>           
                    <button type="submit" class="btn btn-primary">ОФОРМИТЬ ЗАКАЗ</button>
                </div>
            </div>
        </form>
    <?php else: ?>
        <form action="<?php echo Yii::app()->createUrl('order/order/'); ?>" onsubmit="return CheckRequired()" method="post">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2 class="panel-title">Данные о заказчике</h2>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label class="control-label" for="id_name">Имя и Фамилия: <span class="text-danger">*</span></label>
                        <input class="form-control" type="hidden" id="id_id" name="id" value="<?php echo $user->id; ?>">
                        <input class="form-control" type="text" id="id_name" name="name" value="<?php echo $user->name; ?>">
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="id_phone">Телефон: <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" id="id_phone" name="phone" value="<?php echo $user->phone; ?>">
                    </div>            
                    <div class="form-group">
                        <label class="control-label" for="id_note">Дополнительная информация:</label>
                        <textarea id="id_note" name="note" class="form-control" rows="3"><?php echo $user->note; ?></textarea>
                    </div>            

                    <button type="submit" class="btn btn-primary">ОФОРМИТЬ ЗАКАЗ</button>
                </div>
            </div>
        </form>
    <?php endif; //admin ?>
<?php endif; ?>
<script type="text/javascript">
    function CheckRequired(){
        var name = jQuery('#id_name');
        var phone = jQuery('#id_phone');
        var state = 'ok';
        if (name.val() == '') {
            name.parent().addClass('has-error');   
            setTimeout(function() {
                name.parent().removeClass('has-error'); 
            }, 2500); 
            state = 'fail';  
        };
        if (phone.val() == '') {
            phone.parent().addClass('has-error'); 
            setTimeout(function() {
                phone.parent().removeClass('has-error'); 
            }, 2500); 
            state = 'fail';     
        };
        if (state == 'fail') { /*не прошли проверку*/
            return false;
        }
        
    }

    function FillInputs(elm){
        var select_val = jQuery(elm).val();
        var jsondata = jQuery.parseJSON(select_val);
        var id = jQuery('#id_id');
        var name = jQuery('#id_name');
        var phone = jQuery('#id_phone');
        var note = jQuery('#id_note');
        id.val(jsondata.id);
        name.val(jsondata.name);
        phone.val(jsondata.phone);
        note.val(jsondata.note);
        // c(jsondata);
    }   

    function CheckRequiredAdmin(){
        var id = jQuery('#id_id');
        if (id.val() == ''){          
            alert('Выбрать пользователя');
            return false;
        };
    }
</script>
