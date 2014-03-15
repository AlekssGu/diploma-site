<div class="container">
        <div class="row landing-row">
                    <div id="start-now" class="col-md-offset-3 col-md-6">
                        <div class="page-header text-center">
                            <h1>Ievadi savus datus</h1>
                        </div>
                        <?php if(Session::get_flash('success')) { ?>
                                <div class="alert alert-success">
                                <p class="text-success"><?php echo Session::get_flash('success'); ?></p>
                                </div>

                        <?php } elseif(Session::get_flash('error')) { ?>
                                            <div class="alert alert-danger">
                                                <p class="text-danger"><?php echo Session::get_flash('error'); ?></p>
                                            </div>

                        <?php } elseif(isset($result_message)) { ?>
                                            <div class="alert alert-danger">
                                                <?php echo htmlspecialchars_decode($result_message); ?>
                                            </div>
                        <?php } ?>
                        
                        <!-- galvenās lapas forma -->
                        <form id="registration_form" action="/user/register" method="POST" role="form">
                        <div class="form-group">
                            <label for="client_number">Klienta numurs</label>
                            <input type="text" name="client_number" class="form-control" id="client_number" placeholder="Ieraksti savu klienta numuru">
                        </div>
                        <div class="form-group">
                            <label for="email">E-pasts</label>
                            <input type="email" name="email" class="form-control" id="email" placeholder="Ieraksti savu e-pastu">
                        </div>
                        <div class="form-group">
                            <label for="password">Parole</label>
                            <input type="password" name="password" class="form-control" id="password" placeholder="Ieraksti savu paroli (vismaz 5 simboli, 1 burts, 1 cipars)">
                        </div>
                        <div class="form-group">
                            <label for="password">Atkārtota parole</label>
                            <input type="password" name="secpassword" class="form-control" id="secpassword" placeholder="Atkārtoti ieraksti savu paroli">
                        </div>
                        <div class="checkbox">
                          <label>
                            <input checked="true" name="messages" value='Y' type="checkbox"> Vēlos saņemt paziņojumus no sistēmas administrācijas
                          </label>
                        </div>                        
                            
                        <input type="hidden" name="<?php echo \Config::get('security.csrf_token_key');?>" value="<?php echo \Security::fetch_token();?>" />
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-block btn-primary">Reģistrēties</button>
                        </div>
                      </form>
                    </div>
        </div>
</div>
<script>
$(document).ready(function() {
    $('#registration_form').validate({
        rules: {
            client_number: {
                required: true,
                minlength: 8,
                maxlength:8
            },
            email: {
                required: true,
                email: true
            },
            password: {
                required: true,
                minlength: 5
            },
            secpassword: {
                equalTo: "#password"
            },
        },
        
        messages: {
            client_number: {
                required: "Lūdzu ievadiet savu klienta numuru!",
                minlength: "Klienta numuram jāsastāv no 8 simboliem!",
                maxlength: "Klienta numuram jāsastāv no 8 simboliem!"
            },
            email: "Lūdzu ievadiet korektu e-pasta adresi!",
            password: {
                required: "Lūdzu ievadiet paroli!",
                minlength: "Parolei ir jābūt vismaz 5 simbolu garai!",
            },
            secpassword: {
                equalTo: "Parolēm jābūt vienādām!"
            },
        },
            submitHandler: function(form) {
                form.submit();
            }
    });
});    
</script>