<div class="container">
    <div class="row">
        <div class="col-md-5">
            <h1>Iesniegt skaitītāja rādījumu</h1>
            <hr/>
            <a href="/klients" alt="atpakaļ">Doties atpakaļ</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <form id="add_reading" action="/klients/pievienot-radijumu" method="POST" role="form">
                  <input type="hidden" name="<?php echo \Config::get('security.csrf_token_key');?>" value="<?php echo \Security::fetch_token();?>" />
                  <div class="modal-body">
                    <div class="form-group">
                        <label for="number">Skaitītājs</label>       
                        <select name='meter_number' type="text" class="form-control">
                            <option disabled selected>Izvēlēties</option>
                            <?php foreach ($meters as $meter) { ?>
                            <option value="<?php echo $meter->meter_id; ?>"><?php if($meter -> water_type == 'A') 
                                            echo $meter -> name . ' - Aukstais ūdens - ' . $meter -> meter_number;
                                          else 
                                            echo $meter -> name . ' - Karstais ūdens - ' . $meter -> meter_number; ?> </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                       <label for="lead">Skaitītāja rādījums</label>
                       <input id="lead" name="lead" type="text" class="form-control" placeholder="000000">
                     </div>

                      <button id="reset" type="reset" class="btn btn-default">Notīrīt</button>
                      <button type="submit" class="btn btn-primary">Pievienot</button>
            </form>
        </div>
    </div>
    <div class='col-md-6'>
        <img class='img img-responsive img-rounded' src='http://i.ss.lv/images/2012-05-30/259955/VXgAFU1mRlo=/plumbing-plumbing-water-counters-0-2.800.jpg'/>
    </div>
</div>

<script>
    $(document).ready(function() {
//
    });
</script>