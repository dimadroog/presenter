<h1>Категории</h1>

<div id="sortableContainer">
    <div id="0" style="margin-left: -25px">
    </div>
    <?php Category::Tree(0); ?>
</div>
<div style="height:50px">
    <div class="wait dn" id="change_success">
            <img src="<?php echo Yii::app()->request->baseUrl; ?>/static/img/wait.GIF">
    </div>
</div>

<a id="new_category" onclick="ShowNewCategoryForm()">Новая категория</a>

<div id="new_category_form" class="panel panel-default dn">
    <div class="panel-body">
        <form>
            <h3>Новая категория</h3>
            <div class="form-group">
                <label for="name">Название</label>
                <input type="text" name="name" class="form-control" id="id_name" placeholder="Название">
            </div>
            <div class="form-group">
                <label for="parent_category">Родительская категория</label>
                <?php Category::Select(); ?>
            </div>
            <a class="btn btn-primary" onclick="CreateCategory()">Submit</a>
        </form>
    </div>
</div>





<script type="text/javascript">

    function ShowManageLink(elem){
        id = jQuery(elem).attr('id');
        jQuery(elem).append(' <span class="glyphicon glyphicon-pencil cat-icon" aria-hidden="true" id="edit'+id+'" style="margin-left: 20px" onclick="ShowRemaneCategory(this, '+id+')"></span>');
        jQuery(elem).append(' <span class="glyphicon glyphicon-remove cat-icon" aria-hidden="true" id="remove'+id+'" onclick="DeleteCategory('+id+')"></span>');
        jQuery(elem).mouseleave(function(){
            jQuery('#edit'+id).remove();
            jQuery('#remove'+id).remove();
        });
    }


    jQuery(function() {
        jQuery('#sortableContainer').sortable({
            update: function(event, ui) {
                var prev_margin = ui.item.prev().css('margin-left');
                var prev_margin = prev_margin.split('p');
                var prev_margin = Number(prev_margin[0]);
                var this_margin = ui.item.css('margin-left');

                ui.item.css('margin-left', prev_margin+25);
                
                var id = ui.item.attr("id");
                var par_id = ui.item.prev().attr("id");

                jQuery.ajax({
                    type: 'POST',
                    url: '<?php echo Yii::app()->createUrl('category/change/'); ?>',
                    data: {'id': id, 'par_id': par_id},
                    success: function(data){
                        jQuery('#change_success').fadeIn();
                        setTimeout(function() { 
                            jQuery("#change_success").fadeOut();
                            location.reload();
                        }, 600);
                    }, 
                    error: function(){
                        alert('error');
                    }
                });
            }
        });
        
    });

    function ShowNewCategoryForm(){
        if (jQuery('#new_category_form').css("display") == "none"){
            jQuery('#new_category_form').show();
            jQuery('#new_category').html('Скрыть');
            jQuery('#id_name').focus();
        } else {
            jQuery('#new_category_form').hide();
            jQuery('#new_category').html('Новая категория');
        }
    }

    function CreateCategory(){
        var name = jQuery('#id_name').val();
        var parent_category = jQuery('#id_parent_category').val();
        jQuery.ajax({
            type: 'POST',
            url: '<?php echo Yii::app()->createUrl('category/create/'); ?>',
            data: {'name': name, 'parent_category': parent_category},
            success: function(data){
                var jsondata = jQuery.parseJSON(data);
                console.log(jsondata);
                var prev_margin = jQuery('#'+jsondata.parent_id).css('margin-left');
                var prev_margin = prev_margin.split('p');
                var prev_margin = Number(prev_margin[0])+25;
                jQuery('#change_success').fadeIn();
                setTimeout(function() { 
                    jQuery("#change_success").fadeOut();
                }, 300);
                setTimeout(function() { 
                    jQuery('#'+jsondata.parent_id).after('<div class="cat-tem" id="'+jsondata.id+'" style="margin-left:'+prev_margin+'px" onmouseenter="ShowManageLink(this)"><span class="name-category">'+jsondata.name+'</span></div>');

                }, 600);
                jQuery('#id_name').val('');
                jQuery('#id_name').focus();  
                // jQuery('#id_parent_category').val('0'); 

            }, 
            error: function(){
                alert('error');
            }
        });
    }


    function DeleteCategory(id){
        jQuery.ajax({
            type: 'POST',
            url: '<?php echo Yii::app()->createUrl('category/delete/'); ?>',
            data: {'id': id},
            success: function(data){
                // alert(data);
                jQuery('#change_success').fadeIn();
                setTimeout(function() { 
                    jQuery("#change_success").fadeOut();
                    location.reload();
                }, 600);
            }, 
            error: function(){
                alert('error');
            }
        });
    }

   function ShowRemaneCategory(elm, id){

        var jelm = jQuery(elm);//convert to jQuery Element
        var span = jelm.parent().find(".name-category");
        var name = span.text();
        
        if (jQuery(span.html()).attr('value')){
            var nname = jQuery(span.html()).attr('value')
            span.html(nname);
            span.parent().find('.btn').remove();
        } else {
            span.html('<input id="name_inp'+id+'" class="name-category-input" name="name" value="'+name+'">');
            jQuery('#name_inp'+id).select();
            jQuery('#edit'+id).hide();
            jQuery('#remove'+id).hide();
            jelm.parent().append(' <a class="btn btn-primary btn-sm" onclick="RemaneCategory('+id+')">Изменить</a>');
        }
        
        // console.log(jQuery(span.html()).attr('value'));
    }

    function RemaneCategory(id){
        var name = jQuery('#name_inp'+id).val();
        jQuery.ajax({
            type: 'POST',
            url: '<?php echo Yii::app()->createUrl('category/rename/'); ?>',
            data: {'id': id, 'name': name},
            success: function(data){
                jQuery('#'+id).html('<span class="name-category">'+data+'</span>');
                jQuery('#change_success').fadeIn();
                setTimeout(function() { 
                    jQuery("#change_success").fadeOut();
                }, 300);
            }, 
            error: function(){
                alert('error');
            }
        });
    }




</script>