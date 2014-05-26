
<div class="container">
    <div class="row">
        <div class="col-md-5">
            <h1>Sazināties ar uzņēmumu</h1>           
            <hr/>
            <a href="/" alt="atpakaļ">Doties atpakaļ</a>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-offset-3 col-lg-5">
             
            <?php if(Session::get_flash('success')) { ?>
                    <div class="alert alert-success">
                        <p class="text-success"><?php echo Session::get_flash('success'); ?></p>
                    </div>
                <div class="clearfix"></div>

            <?php } elseif(Session::get_flash('error')) { ?>
                    <div class="alert alert-danger">
                        <p class="text-danger"><?php echo Session::get_flash('error'); ?></p>
                    </div>
                <div class="clearfix"></div>
            <?php } ?>
             
            <form id='contact_form' role="form" action='/palidziba/sazinaties' method='POST'>
              <input type="hidden" name="<?php echo \Config::get('security.csrf_token_key');?>" value="<?php echo \Security::fetch_token();?>" />
              
              <?php if(!Auth::check()) { ?>
                <div class="form-group">
                  <label for="fullname">Vārds, Uzvārds</label>
                  <input type='text' name='fullname' id='fullname' class="form-control" />
                </div>
                <div class="form-group">
                  <label for="email">E-pasts</label>
                  <input type='email' name='email' id='email' class="form-control" />
                </div>
              <?php } ?>
              
              <div class="form-group">
                <label for="topic">Tēma</label>
                <select name='topic' id='topic' class="form-control">
                    <option value=''>Izvēlēties tēmu</option>
                    <?php foreach($topics as $topic) { ?>
                    <option value='<?php echo $topic -> id; ?>'><?php echo $topic -> question; ?></option>
                    <?php } ?>
                </select>
              </div>
              
              <div class="form-group">
                <label for="comment">Ziņojums</label>
                <textarea autofocus name='comment' id='comment' class="form-control" rows="3"></textarea>
              </div>
              
              <div class="checkbox">
                <label>
                <input name='terms' id='terms' type="checkbox"> Piekrītu <a href="#">noteikumiem</a> par saziņu ar uzņēmumu
                </label>
              </div>
              <button type="submit" class="btn btn-default">Sūtīt</button>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#contact_form').validate({
            rules: {
                topic: {
                    required: true,
                },
                comment: {
                    required: true,
                },
                terms: {
                    required: true,
                },
                fullname: {
                    required: true,
                },
                email: {
                    required: true,
                    email: true,
                },
            },

            messages: {
                topic: {
                    required: "Šis lauks ir obligāts!"
                },
                comment: {
                    required: "Šis lauks ir obligāts!"
                },
                terms: {
                    required: "Šis lauks ir obligāts!"
                },
                fullname: {
                    required: "Šis lauks ir obligāts!"
                },
                email: {
                    required: "Šis lauks ir obligāts!",
                    email: 'Ievadīts nepareizs e-pasts!',
                },
            },
                submitHandler: function(form) {
                    form.submit();
                }
        });   
    });
</script>