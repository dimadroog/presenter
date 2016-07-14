<h2>Вход для клиентов</h2>
<form action="<?php echo Yii::app()->createUrl('customer/login/'); ?>" onsubmit="return CheckRequired()" method="post">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="form-group">
                <label class="control-label" for="id_pass">Пароль: <span class="text-danger">*</span></label>
                <p id="pass_error" class="text-danger"><?php echo $message; ?></p>
                <input class="form-control" type="password" id="id_pass" name="pass" value="">
            </div>       
            <button type="submit" class="btn btn-primary">ВОЙТИ</button>
        </div>
    </div>
</form>

<script type="text/javascript">
    function CheckRequired(){
        var pass = jQuery('#id_pass');
        var state = 'ok';
        if (pass.val() == '') {
            pass.parent().addClass('has-error'); 
            setTimeout(function() {
                pass.parent().removeClass('has-error'); 
            }, 2500); 
            state = 'fail';     
        };
        if (state == 'fail') { /*не прошли проверку*/
            return false;
        }
    }
</script>
