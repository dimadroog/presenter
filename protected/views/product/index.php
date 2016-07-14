<div class="row">
    <div class="col-sm-3">
    <?php Category::FilterTree('product/index/'); ?>
    </div>
    <div class="col-sm-9 showcase" id="lightgallery">
        <h1>Каталог товаров</h1>
        <h4>
            <?php Category::Path($current_cat->id); ?>
            <?php if ($current_cat): ?>
                <span class="text-muted">(<?php echo Category::getCountWithChilds($current_cat->id); ?>)</span>
            <?php endif; ?>
        </h4>
        <?php foreach ($prods as $prod): ?>
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
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><b>Артикул:</b> <?php echo $prod->id; ?></h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <form name="size-form" id="size_form" >
                                <p><b>Цена:</b> <span class="price text-primary"><?php echo $prod->price; ?></span><span class="glyphicon glyphicon-ruble ruble" aria-hidden="true"></span></p>
                                <p><b>Категория:</b> <?php Category::Path($prod->categ->id); ?>
                                <p><b>Размеры:</b> <?php Size::Lst($prod->id); ?></p>
                                <div class="form-group">
                                    <?php foreach ($prod->productSizes as $size): ?>
                                        <div class="size-row">
                                            <div class="size-label"><?php echo $size->name; ?></div>
                                            <input class="size-inp-showcase" type="number" name="amount" id="amount" value="0" size="20" min="0" max="<?php Amount::Amoun($prod->id, $size->id); ?>"> 
                                            <span class="text-muted ml5 available"><span id="amount-hint"><?php Amount::Amoun($prod->id, $size->id); ?></span></span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <div class="form-group">
                                    <a id="<?php echo $prod->id; ?>" class="btn btn-primary" onClick="AddToCart(this);">В корзину</a>
                                    <div id="error"></div>
                                </div>
                            </form>
                        </div>
                        <div class="col-sm-6">
                                <div class="selector"
                                data-src="<?php echo Yii::app()->request->baseUrl.'/images/md_'.$prod->image; ?>" 
                                data-sub-html='<?php echo $subtitle; ?>'>
                                    <a href="">
                                        <p><img class="thumb-result" src="<?php echo Yii::app()->request->baseUrl.'/images/md_'.$prod->image; ?>"></p>
                                    </a>
                                </div>
                            
                        </div>
                    </div>

                </div>
            </div>
        <?php endforeach; ?>

        <?php if (count($prods) < 1): ?>
            <h2 class="text-muted">Нет товаров в этой категории</h2>
        <?php endif; ?>

    </div>
</div>

<div class="row">
<div class="col-sm-9 col-sm-offset-3">
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
</div>
</div>



<a class="btn btn-primary small-cart dn" onclick="ShowCart()"><span class="glyphicon glyphicon-shopping-cart"></span> 
    <span class="cart_pos"><?php echo Yii::app()->LavrikShoppingCart->count_in_basket; ?></span> шт. 
    / 
    <span class="cart_sum"><?php echo Yii::app()->LavrikShoppingCart->sum; ?></span> руб.
</a>


<div class="middle-cart dn">
    <div class="panel panel-default">
        <div class="panel-heading">
            <!-- <h3 class="panel-title"><b>В корзине:</b><a class="fr close"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a></h3> -->
            <h3 class="panel-title"><b>В корзине:</b><span class="glyphicon glyphicon-remove close" onclick="HideCart()" ></span></h3>
        </div>
        <div class="panel-body">
            <p>Позиций: <span class="cart_pos"><?php echo Yii::app()->LavrikShoppingCart->count_in_basket; ?></span> шт.</p>
            <p>На сумму: <span class="cart_sum"><?php echo Yii::app()->LavrikShoppingCart->sum; ?></span> руб.</p>
            <p>Наименований: <span class="cart_itm"><?php echo Yii::app()->LavrikShoppingCart->count_of_different_products; ?></span></p>
            <a href="<?php echo Yii::app()->createUrl('order/cartlist/'); ?>">Оформить заказ</a>

        </div>
    </div>
</div>

<script type="text/javascript">
    function AddToCart(elm){
        var id = jQuery(elm).attr('id');
        var form = jQuery(elm).closest('form');
        var state = 0;

        var size = '';
        form.find('.size-row').each(function(){
            var hint = jQuery(this).find('#amount-hint').text();
            var lab = jQuery(this).find('.size-label').text();
            var val = parseInt(jQuery(this).find('.size-inp-showcase').val(), 10);


            if (val > hint){  /*проверка*/
                form.find('#error').html('<p class="text-danger">Нельзя заказать больше товара чем доступно!</p>');
                setTimeout(function() {
                    form.find('#error').html('');
                }, 2500);
                state = 'fail';
            } else if (val < 0) { /*проверка*/
                form.find('#error').html('<p class="text-danger">Количество не может быть отрицательным!</p>');
                setTimeout(function() {
                    form.find('#error').html('');
                }, 2500);
                state = 'fail';
            } else {
                if (val > 0){
                    /*переопределяем доступн. к-во*/
                    var new_hint = hint-val;
                    jQuery(this).find('#amount-hint').html(new_hint);
                    jQuery(this).find('#amount').attr('max', new_hint);
                    size += lab+','+val+':'; /*собираем size*/
                }
            }

        })
        if (state == 'fail') { /*не прошли проверку*/
            return false;
        }

        if (size == '') { /*проверка*/
            form.find('#error').html('<p class="text-danger">Укажите количество товара!</p>');
            setTimeout(function() {
                form.find('#error').html('');
            }, 2500);
            return false; /*не прошли проверку*/
        }

        jQuery.ajax({
            type: 'POST',
            url: '<?php echo Yii::app()->createUrl('order/addtocart/'); ?>',
            data: {'id': id, 'size': size},
            success: function(data){
                var jsondata = jQuery.parseJSON(data);
                c(jsondata);
                form.trigger('reset');
                jQuery('.small-cart').show();
                jQuery('.middle-cart').hide();
                jQuery('.cart_itm').html(jsondata.itm);
                jQuery('.cart_sum').html(jsondata.sum);
                jQuery('.cart_pos').html(jsondata.pos);
                form.find('#error').html('<p class="text-success">Товар добавлен в корзину!</p>');
                jQuery('.small-cart').addClass('btn-success op1');
                form.find('.btn').addClass('btn-success');
                setTimeout(function() {
                    form.find('#error').html('');
                    form.find('.btn').removeClass('btn-success');
                    jQuery('.small-cart').removeClass('btn-success op1');
                }, 1000);
            }, 
            error: function(){
                alert('error');
            }
        });

    }


    function ShowCart(){
        jQuery('.middle-cart').slideDown('500');
        jQuery('.small-cart').hide();
    }
    function HideCart(){
        jQuery('.middle-cart').slideUp('200');
        setTimeout(function() {
            jQuery('.small-cart').fadeIn('700');
        }, 300);
    }
</script>