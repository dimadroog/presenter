<h1>Добавить товар</h1>
<div id="first_form">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2 class="panel-title">Задать маску для всех товаров</h2>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <label>Цена по умолчанию: </label>
                <input class="form-control" type="number" id="id_price" name="price" maxlength="55">
            </div>
            <div class="form-group">
                <label>Размеры по умолчанию: </label>
                <!-- <input class="form-control" type="text" id="" name="size"> -->
                <?php echo $size_spans; ?>
                <div class="size-class" id="">
                    <!-- for sizes and amounts -->           
                </div>
            </div>
            <div class="form-group">
                <label>Категория по умолчанию:</label>
                <?php Category::SelectReverse(); ?>
            </div>
            <div class="form-group">
                <label>Примечание по умолчанию: </label>
                <input class="form-control" type="text" id="id_note" name="note" maxlength="55">
            </div>
            <a class="btn btn-primary" onclick="jQuery('#files').click()">Выбрать файлы</a>
        </div>
    </div>
</div>


<form enctype="multipart/form-data" action="upload.php" method="post">
    <input type="file" id="files" name="files[]" multiple style="display:none">
    <output id="list"></output>
</form> 


<script type="text/javascript">


    function handleFileSelect(evt) {
        

/*generate default_siz*/
        var result = [];
        jQuery('.size-row').each(function(){
           var lab = jQuery(this).find('.size-label').text();
           var amo = jQuery(this).find('.size-inp').val();
           result.push({lab, amo});
        })

        var default_siz = '';
        function getValues(array) {
            for(i=0;i<array.length;i++){
                default_siz += '<div class="size-row"><div class="size-label">'+array[i].lab+'</div><input class="size-inp" type="number" name="amount" id="amount" value="'+array[i].amo+'"> <span class="glyphicon glyphicon-remove size-icon" aria-hidden="true" id="remove" onclick="RemoveSizeInput(this)"></span></div>';
            }
        }
        getValues(result);
/*end generate default_siz*/

        var default_pri = jQuery('#id_price').val();
        var default_amo = jQuery('#id_amount').val();
        var default_cat = jQuery('#id_parent_category').val();
        var default_not = jQuery('#id_note').val();

        jQuery('#first_form').hide();
        var files = evt.target.files; // FileList object

        // Loop through the FileList and render image files as thumbnails.
        for (var i = 0, f; f = files[i]; i++) {

            // Only process image files.
            if (!f.type.match('image.*')) {
            continue;
            }

            var reader = new FileReader();

            // Closure to capture the file information.
            reader.onload = (function(theFile) {
                return function(e) {
                    // Render thumbnail.
                    var span = document.createElement('span');

                    var img = '<div class="form-group"><input class="form-control" type="hidden" id="id_img" name="img" value="'+e.target.result+'"></div>';
                    var price = '<label>Цена: </label><div class="form-group"><input class="form-control inp-pri" type="number" id="id_price" name="price"></div>';
                    var size = '<?php echo $size_spans; ?><div class="size-class"></div>';
                    var category = '<label>Категория: </label><div class="form-group"><?php Category::SelectReverse() ?></div>';
                    var note = '<label>Примечание: </label><div class="form-group"><input class="form-control inp-not" type="text" id="id_note" name="note" maxlength="55"></div>';
                    var btn = '<div class="form-group"><a class="btn btn-primary" onclick="SubmitProduct(this)">Отправить</a></div>';
                    
                    span.innerHTML = ['<form class="panel panel-default"><div class="panel-body"><img class="thumb" src="', e.target.result, '" title="', escape(theFile.name), '">' + img + price + size + category + note + btn].join('')+'</div></form>';
                    document.getElementById('list').insertBefore(span, null);
                    
                    jQuery('.size-class').html(default_siz);
                    jQuery('.inp-pri').val(default_pri);
                    jQuery('.sel-cat').val(default_cat);
                    jQuery('.inp-not').val(default_not);
                };
            })(f);

            // Read in the image file as a data URL.
            reader.readAsDataURL(f);
        }
    }

    document.getElementById('files').addEventListener('change', handleFileSelect, false);


    function SubmitProduct(elm){
        var jelm = jQuery(elm);//convert to jQuery Element
        var form = jelm.parent().parent();
        var img = form.find('#id_img').val();
        var note = form.find('#id_note').val();
        var price = form.find('#id_price').val();
        var size = '';
        form.find('.size-row').each(function(){
           var lab = jQuery(this).find('.size-label').text();
           var amo = jQuery(this).find('.size-inp').val();
           size += lab+','+amo+':';
        })
        console.log(size);
        var category = form.find('#id_parent_category').val();
        jQuery.ajax({
            type: 'POST',
            url: '<?php echo Yii::app()->createUrl('product/upload/'); ?>',
            data: {'img': img, 'note': note, 'price': price, 'size': size, 'category': category, },
            success: function(data){
                var jsondata = jQuery.parseJSON(data);
                form.addClass('bg-success');

                var res_img = '<img class="thumb-result" src="<?php echo Yii::app()->request->baseUrl; ?>'+jsondata.image+'">';
                var res_id = '<p><b>Артикул: </b>'+jsondata.id+'</p>';
                var res_price = '<p><b>Цена: </b>'+jsondata.price+' <span class="glyphicon glyphicon-ruble ruble" aria-hidden="true"></span></p>';
                var res_category = '<p><b>Категория: </b>'+jsondata.category+'</p>';
                var res_note = '<p><b>Примечание: </b>'+jsondata.note+'</p>';
                form.html('<div class="row"><div class="col-sm-2">'+res_img+'</div><div class="col-sm-10">'+res_id+res_price+res_category+res_note+'</div></div>');
            }, 
            error: function(){
                alert('error');
            }
        });
    }


</script>
