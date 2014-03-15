<div class="container">
        <div class="row landing-row">
                    <div id="start-now" class="col-md-offset-3 col-md-6">
                        <?php if(Session::get_flash('success')) { ?>
                                                
                        <div id="login-start" class="page-header text-center">
                            <h1>Ieiet sistēmā</h1>
                        </div>
                        
                        <div class="alert alert-success">
                              <p class="text-success"><?php echo Session::get_flash('success'); ?></p>
                        </div>
                        
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
                                    <a href="/user/forgot" class="btn btn-link">Aizmirsu paroli</a> 
                                </div>
                            </form>
                        </div>
                        
                        <?php } elseif(Session::get_flash('error')) { ?>
                        
                                <div class="page-header text-center">
                                    <h1>Ievadi saņemto kodu</h1>
                                </div>
                        
                                <div class="alert alert-danger">
                                    <p class="text-danger"><?php echo Session::get_flash('error'); ?></p>
                                </div>
                                <div class="col-md-offset-3 col-md-6">
                                    <form action="/confirm/post" method="POST" role="form">
                                    <div class="form-group">
                                        <label for="code">Kods</label>
                                        <input type="text" name="code" class="form-control" id="code" placeholder="Ieraksti saņemto kodu">
                                    </div>

                                    <input type="hidden" name="<?php echo \Config::get('security.csrf_token_key');?>" value="<?php echo \Security::fetch_token();?>" />

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-block btn-primary">Apstiprināt</button>
                                    </div>
                                    </form>
                                </div>
                        
                        <?php } else { ?>
                        
                        <div class="page-header text-center">
                                    <h1>Ievadi saņemto kodu</h1>
                        </div>
                        
                        <form action="/confirm/post" method="POST" role="form">
                        <div class="form-group">
                            <label for="code">Kods</label>
                            <input type="text" name="code" class="form-control" id="code" placeholder="Ieraksti saņemto kodu">
                        </div>
                        
                        <input type="hidden" name="<?php echo \Config::get('security.csrf_token_key');?>" value="<?php echo \Security::fetch_token();?>" />
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-block btn-primary">Apstiprināt</button>
                        </div>
                        </form>
                        
                        <?php } ?>
                        
                  </div>
        </div>
</div>