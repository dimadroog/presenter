        <h1>Товары</h1>
<div class="row">
    <div class="col-sm-9">
        <h4>
            <span id="current_cat" class="dn"><?php echo $current_cat->id; ?></span><?php Category::Path($current_cat->id) ?>
        </h4>

        <div class="table-responsive">
            <table class="table table-striped" id="lightgallery" > 
                <thead> 
                    <tr> 
                        <th>Арт.</th> 
                        <th>Фото</th> 
                        <th>Цена</th> 
                        <th>Размеры</th>
                        <th>Категория</th>
                        <th>Действия</th>
                    </tr> 
                </thead> 
                <tbody>
                    <?php foreach ($prods as $prod):?> 
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
                            <td><nobr><?php echo Amount::LstAdm($prod->id); ?></nobr></td>
                            <td>
                                <?php echo $prod->categ->name; ?>
                                <?php if ($prod->category == 0):?>
                                    <i>Корень каталога</i>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a class="" href="<?php echo Yii::app()->createUrl('product/update/', array('id'=>$prod->id)); ?>"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                                <a class="" href="<?php echo Yii::app()->createUrl('product/delete/', array('id'=>$prod->id)); ?>"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a> 
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
            )) ?>
        </div>

        <?php if (count($prods) < 1): ?>
            <h2 class="text-muted">Нет товаров в этой категории</h2>
        <?php endif; ?>

    </div>
    <div class="col-sm-3 showcase">
        <?php Category::FilterTree('product/manageproduct/'); ?>
    </div>
</div>