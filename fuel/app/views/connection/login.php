<div class="container">
        <div class="row landing-row">
                    <div id="start-now" class="col-md-offset-3 col-md-6">
                                                
                        <div id="login-start" class="page-header text-center">
                            <h1>Ieiet sistēmā</h1>
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
                            <form id="login-form" method="POST" action="/user/login" role="form">
                                <div class="form-group">
                                    <label for="login">E-pasts vai klienta numurs</label>
                                    <input type="text" name="username" class="form-control" id="login" placeholder="">
                                </div>
                                <div class="form-group">
                                    <label for="password">Parole</label>
                                    <input type="password" name="password" class="form-control" id="password" placeholder="">
                                </div>
                                    
                                <input type="hidden" name="<?php echo \Config::get('security.csrf_token_key');?>" value="<?php echo \Security::fetch_token();?>" />
                                
                                <div class="form-group">
                                    <button type="submit" class="btn btn-block btn-default">Ieiet</button>
                                    <a href="#" class="btn btn-link">Aizmirsu paroli</a> 
                                </div>
                            </form>
                        </div>
                        
                  </div>
        </div>
</div>