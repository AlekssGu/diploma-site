<div class="container">
        <div class="row landing-row">
                    <div id="start-now" class="col-md-offset-3 col-md-6">
                        <div id="login-start" class="page-header text-center">
                            <h1>Atjaunot paroli</h1>
                        </div>
                        
                        <?php if(Session::get_flash('success')) { ?>
                                <div class="alert alert-success">
                                    <p class="text-success"><?php echo Session::get_flash('success'); ?></p>
                                </div>

                        <?php } elseif(Session::get_flash('error')) { ?>
                                <div class="alert alert-danger">
                                    <p class="text-danger"><?php echo Session::get_flash('error'); ?></p>
                                </div>
                        <?php } ?>
                        
                        <div class="col-md-offset-3 col-md-6">
                            <form id="login-form" method="POST" action="/abonents/aizmirsta-parole" role="form">
                                <div class="form-group">
                                    <label for="email">Tavs lietotāja e-pasts</label>
                                    <input type="email" name="email" class="form-control" id="email" placeholder="">
                                </div>
                                
                                <input type="hidden" name="<?php echo \Config::get('security.csrf_token_key');?>" value="<?php echo \Security::fetch_token();?>" />
                                
                                <div class="form-group">
                                    <button type="submit" class="btn btn-block btn-default">Nosūtīt</button>
                                </div>
                            </form>
                        </div>                        
                  </div>
        </div>
</div>

<script>
    
$(document).ready(function() {
    $('#login-form').validate({
        rules: {
            email: {
                required: true,
                email: true
            }
        },
        
        messages: {
            email: "Lūdzu ievadiet korektu e-pasta adresi!"
        },
            submitHandler: function(form) {
                form.submit();
            }
    });    
});    
</script>