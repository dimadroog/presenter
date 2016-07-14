<h1>Редактировать товар</h1>
<form id="first_form" method="post" action="">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2 class="panel-title">Редактировать товар # <?php echo $product->id; ?></h2>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <label>Цена: </label>
                <input class="form-control" type="number" id="id_price" name="price" maxlength="55" value="<?php echo $product->price; ?>">
                <input type="hidden" id="size_str" name="size_str">
            </div>
            <div class="form-group">
                <label>Размеры: </label>
                <!-- <input class="form-control" type="text" id="" name="size"> -->
                <?php echo $size_spans; ?>

                <div class="size-class" id="">
                    <!-- for sizes and amounts -->           
                    <?php foreach ($amounts as $amount): ?>
                        <div class="size-row">
                            <div class="size-label"><?php echo $amount->size->name; ?></div>
                            <input class="size-inp" type="number" name="amount" id="amount" value="<?php echo $amount->amount; ?>"> 
                            <span class="glyphicon glyphicon-remove size-icon" aria-hidden="true" id="remove" onclick="RemoveSizeInput(this)"></span>
                        </div>
                    <?php endforeach; ?>
                </div>

            </div>
            <div class="form-group">
                <label>Категория:</label>
                <?php Category::UpdateSelectReverse($product->category); ?>
            </div>
            <div class="form-group">
                <label>Примечание: </label>
                <input class="form-control" type="text" id="id_note" name="note" maxlength="55" value="<?php echo $product->note; ?>">
            </div>            
            <div class="form-group">
                <label>Изображение: </label>
                <p><output id="list"></output></p>
                <input type="hidden" id="id_image" name="img">
                <input type="file" id="files" name="files" />
            </div>
            <button type="submit" class="btn btn-primary" onclick="CompleteSizes()">Изменить</button>
        </div>
    </div>
</form>





<script type="text/javascript">
    jQuery(function(){
        jQuery('#list').html('<img class="thumb" src="<?php echo Yii::app()->request->baseUrl.'/images/sm_'.$product->image; ?>">');
    });

    function handleFileSelect(evt) {
        var files = evt.target.files; // FileList object
        jQuery('#list').html('');
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
                    span.innerHTML = ['<img class="thumb" src="', e.target.result,'" title="', escape(theFile.name), '"/>'].join('');
                    document.getElementById('list').insertBefore(span, null);
                    jQuery('#id_image').val(e.target.result);
                };
            })(f);
            // Read in the image file as a data URL.
            reader.readAsDataURL(f);
        }
    }

    document.getElementById('files').addEventListener('change', handleFileSelect, false);


    function CompleteSizes(){
        var size = '';
        jQuery('.size-row').each(function(){
           var lab = jQuery(this).find('.size-label').text();
           var amo = jQuery(this).find('.size-inp').val();
           size += lab+','+amo+':';
        })
        jQuery('#size_str').val(size);
        c(size);
    }
</script>