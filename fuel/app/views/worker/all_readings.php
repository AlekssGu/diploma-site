<div class="container">
    <div class="row">
        <div class="col-md-5">
            <h1>Iesniegtie rādījumi</h1>
            <hr/>
            <a href="/" alt="atpakaļ">Doties atpakaļ</a>
        </div>
    </div>

<div class="row main-block">
    
                <?php if(Session::get_flash('success')) { ?>
                    <div class="col-md-8">
                        <div class="alert alert-success">
                            <p class="text-success"><?php echo Session::get_flash('success'); ?></p>
                        </div>
                    </div>
                    <div class="clearfix"></div>

                <?php } elseif(Session::get_flash('error')) { ?>
                    <div class="col-md-8">
                        <div class="alert alert-danger">
                            <p class="text-danger"><?php echo Session::get_flash('error'); ?></p>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                <?php } ?>
    
            <table class="table text-center">
                <thead>
                    <th>Klienta numurs</th>
                    <th>Skaitāja numurs</th>
                    <th>Iepriekšējais rādījums</th>
                    <th>Iesniegtais rādījums</th>
                    <th>Datums</th>
                    <th>Atgriezt rādījumu</th>
                    <th>Apstiprināt rādījumu</th>
                </thead>   
                <?php foreach($readings as $reading) { ?>
                <tr>
                    <td><?php echo $reading -> client_number; ?></td>
                    <td><?php echo $reading -> meter_number; ?></td>
                    <td><?php echo $reading -> last_lead; ?></td>
                    <td><?php echo $reading -> lead; ?></td>
                    <td><?php echo date_format(date_create($reading -> date_taken),'d.m.Y'); ?></td>
                    <td><a class="return_trigger" data-cln="<?php echo $reading -> client_id; ?>" data-rdn="<?php echo $reading -> rdn_id; ?>" href="#" data-toggle="modal" data-target="#atgriezt_radijumu"><span class="glyphicon glyphicon-remove"></span></a></td>
                    <td><a href='#'><span class="glyphicon glyphicon-ok"></span></a></td>
                </tr>
                <?php } ?>
            </table>
        </div>
</div><!--/.container -->

<!-- atgriezt rādījumu -->
<div class="modal fade" id="atgriezt_radijumu" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Atgriešanas pamatojums</h4>
      </div>

    <form id="return_reading" action="/darbinieks/skaititaji/radijumi/atgriezt" method="POST" role="form">
       <input type="hidden" name="<?php echo \Config::get('security.csrf_token_key');?>" value="<?php echo \Security::fetch_token();?>" />
       <input id="rdn_id" type="hidden" name="reading_id" />
       <input id="cln_id" type="hidden" name="client_id" />
       
       <div class="modal-body">
        <div class="form-group">
            <label for="number">Paskaidrojums abonentam:</label>       
            <textarea class="form-control" placeholder="Īss un skaidrs paskaidrojums abonentam, kādēļ rādījums tiek atgriezts"></textarea>
        </div>
          
        <div class="modal-footer">
          <button id="reset" type="button" class="btn btn-default" data-dismiss="modal">Notīrīt un aizvērt</button>
          <button type="submit" class="btn btn-primary">Atgriezt</button>
        </div>
      </div>
    </form>
  </div>
</div>
</div>
<!-- atgriezt rādījumu -->

<script>
    $(document).ready(function() {
        $('.return_trigger').click(function() {
           $('#rdn_id').attr('value',$(this).attr('data-rdn')); 
           $('#cln_id').attr('value',$(this).attr('data-cln')); 
        });
    });
</script>

