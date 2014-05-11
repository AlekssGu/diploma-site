<div class="container">
    <div class="row">
        <div class="col-md-5">
            <h1>Mainīt paroli</h1>
            <hr/>
            <a href="/abonents" alt="atpakaļ">Doties atpakaļ</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-offset-3 col-md-6">
            <?php if(Session::get_flash('success')) { ?>
                    <div class="alert alert-success">
                        <p class="text-success"><?php echo Session::get_flash('success'); ?></p>
                    </div>

            <?php } elseif(Session::get_flash('error')) { ?>
                    <div class="alert alert-danger">
                        <p class="text-danger"><?php echo Session::get_flash('error'); ?></p>
                    </div>
            <?php } ?>
            
            <form id="pass-change-form" method="POST" action="/abonents/mainit-datus" role="form">
                <div class="form-group">
                    <label for="old_password">Vecā parole</label>
                    <input type="password" name="old_password" class="form-control" id="opassword" placeholder="">
                </div>
                <div class="form-group">
                    <label for="new_password">Jaunā parole</label>
                    <input type="password" name="new_password" class="form-control" id="npassword" placeholder="">
                </div>
                <div class="form-group">
                    <label for="new_secpassword">Jaunā parole atkārtoti</label>
                    <input type="password" name="new_secpassword" class="form-control" id="spassword" placeholder="">
                </div>

                <input type="hidden" name="<?php echo \Config::get('security.csrf_token_key');?>" value="<?php echo \Security::fetch_token();?>" />

                <div class="form-group">
                    <button type="submit" class="btn btn-block btn-primary">Mainīt</button>
                </div>
            </form>
            
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
            jQuery.validator.addMethod("complex", function(element) {
                            var has_char = false;
                            var has_num = false;

                            /*contains characters*/
                            if(element.match(/[a-zA-Z+]+/) ) {
                                    has_char = true;
                            }
                            /*contains digits*/
                            if(element.match(/[0-9]+/)) {
                                    has_num = true;
                            }
                            if(has_char && has_num) return true;
                            else return false;

        }, "Parolei jāsatur vismaz viens burts un viens cipars!");

            $('#pass-change-form').validate({
                rules: {
                    old_password: {
                        required: true
                    },
                    new_password: {
                        required: true,
                        minlength: 5,
                        complex:true
                    },
                    new_secpassword: {
                        equalTo: "#npassword"
                    },
                },

                messages: {
                    old_password: {
                        required: "Lūdzu ievadiet savu veco paroli!",
                    },
                    new_password: {
                        required: "Lūdzu ievadiet jauno paroli!",
                        minlength: "Parolei ir jābūt vismaz 5 simbolu garai!",
                        complex: "Parolei jāsatur vismaz viens burts un viens cipars!"
                    },
                    new_secpassword: {
                        equalTo: "Parolēm jābūt vienādām!"
                    },
                },
                    submitHandler: function(form) {
                        form.submit();
                    }
            });    
    });
</script>    