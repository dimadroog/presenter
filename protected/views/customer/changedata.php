<h2>Изменить данные пользователя</h2>
    <form action="" onsubmit="return CheckRequired()" method="post">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="form-group">
                    <label class="control-label" for="id_name">Имя и Фамилия: <span class="text-danger">*</span></label>
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

                <button type="submit" class="btn btn-primary">Сохранить</button>
            </div>
        </div>
    </form>
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
</script>