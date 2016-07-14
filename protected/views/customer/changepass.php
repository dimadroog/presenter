<h2>Изменить пароль</h2>
<form action="" onsubmit="return CheckRequired()" method="post">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="form-group">
                <label class="control-label" for="id_pass">Старый пароль: <span class="text-danger">*</span></label>
                <input class="form-control" type="password" id="id_pass" name="pass" value="">
            </div>  
            <div class="form-group">
                <label class="control-label" for="id_pass2">Новый пароль: <span class="text-danger">*</span></label>
                <input class="form-control" type="password" id="id_pass2" name="pass2" value="">
            </div>       
            <p id="pass_error" class="text-danger"><?php echo $message; ?></p>
            <button type="submit" class="btn btn-primary">Сохранить</button>
        </div>
    </div>
</form>

<script type="text/javascript">
    function CheckRequired(){
        var pass = jQuery('#id_pass');
        var pass2 = jQuery('#id_pass2');
        var state = 'ok';
        if (pass.val() == '') {
            pass.parent().addClass('has-error'); 
            setTimeout(function() {
                pass.parent().removeClass('has-error'); 
            }, 2500); 
            state = 'fail';     
        };
        if (pass2.val() == '') {
            pass2.parent().addClass('has-error'); 
            setTimeout(function() {
                pass2.parent().removeClass('has-error'); 
            }, 2500); 
            state = 'fail';     
        };
        if (state == 'fail') { /*не прошли проверку*/
            return false;
        }
    }
</script>