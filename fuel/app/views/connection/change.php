<div class="container">
        <div class="row landing-row">
                    <div id="start-now" class="col-md-offset-3 col-md-6">
                                                
                        <div id="login-start" class="page-header text-center">
                            <h1>Mainīt paroli</h1>
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
                        
                        <?php if(isset($show_get) && $show_get == 'Y') { ?>
                        <div class="col-md-offset-3 col-md-6">
                            <form id="login-form" method="POST" action="/abonents/mainit/post" role="form">
                                <div class="form-group">
                                    <label for="password">Parole</label>
                                    <input type="password" name="password" class="form-control" id="password" placeholder="">
                                </div>
                                <div class="form-group">
                                    <label for="secpassword">Parole atkārtoti</label>
                                    <input type="password" name="secpassword" class="form-control" id="secpassword" placeholder="">
                                </div>
                                    
                                <input type="hidden" name="<?php echo \Config::get('security.csrf_token_key');?>" value="<?php echo \Security::fetch_token();?>" />
                                <input type="hidden" name="user" value="<?php if(isset($user_id)) echo $user_id;?>" />
                                <input type="hidden" name="is_user_forgot" value="Y" />
                                
                                <div class="form-group">
                                    <button type="submit" class="btn btn-block btn-default">Mainīt paroli</button>
                                </div>
                            </form>
                        </div>
                        <?php } else { ?>
                        <div class="col-md-offset-1 col-md-10">
                            <p>Ja esi aizmirsis paroli, dodies uz <a href="/abonents/aizmirsta-parole">paroles maiņas</a> pieprasījumu lapu.</p>
                        </div>
                        <?php } ?>
                  </div>
        </div>
</div>