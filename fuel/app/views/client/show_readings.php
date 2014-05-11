<div class="container">
        <div class="row">
            <div class="col-md-5">
                <h1>Rādījumu vēsture</h1>
                <p>Skaitītājs nr. <?php echo $meter_number; ?></p>
                <hr/>
                <a href="/abonents/objekti/apskatit/<?php echo $object_id; ?>" alt="atpakaļ">Doties atpakaļ</a>
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
            
            <table class="table">
                <thead>
                    <th>Nr.p.k.</th>
                    <th>Iesniegtais rādījums</th>
                    <th>Statuss</th>
                    <th>Komentārs</th>
                    <th>Datums</th>
                    <th>Labot rādījumu</th>
                    <th>Iesniegt rādījumu</th>
                </thead>
            <?php foreach ($readings as $key => $reading) { ?>           
                <tr <?php if(html_entity_decode($reading->status) == 'Labošanā') echo 'class="info"'; 
                            else if(html_entity_decode($reading->status) == 'Atgriezts') echo 'class="danger"';
                            else if(html_entity_decode($reading->status) == 'Apstiprināts') echo 'class="success"'; ?>>
                    <td><?php echo $key + 1; ?></td>
                    <td><?php echo $reading->lead; ?></td>
                    <td><?php echo $reading->status; ?></td>
                    <td><?php echo $reading->notes; ?></td>
                    <td><?php echo $reading->date_taken; ?></td>
                    <td><a href='#' data='rdn<?php echo $reading->id;?>' class='rdn-edit-btn btn btn-sm btn-default <?php if(in_array(html_entity_decode($reading->status), array('Iesniegts', 'Sākotnējais', 'Apstiprināts'))) echo 'disabled';?>'>Labot</a></td>
                    <form method="POST" action="/abonents/iesniegt-merijumu">
                        <input type="hidden" name="reading_id" value="<?php echo $reading->id;?>"/>
                        <input type="hidden" name="reading" value="<?php echo $reading->lead;?>"/>
                        <input type="hidden" name="<?php echo \Config::get('security.csrf_token_key');?>" value="<?php echo \Security::fetch_token();?>" />
                        <input type="hidden" name="meter_id" value="<?php echo $meter_id;?>"/>
                        <input type='hidden' class='input-action' name="action" />
                    <td><button onclick='return confirm("Iesniegt bez iespējas vēlāk labot?")' class='submit-rdn btn btn-sm btn-default <?php if(in_array(html_entity_decode($reading->status), array('Iesniegts', 'Sākotnējais', 'Atgriezts', 'Apstiprināts'))) echo 'disabled'; ?>'>Iesniegt</button></td>
                    </form>
                </tr>
                <?php if(html_entity_decode($reading->status) == 'Labošanā' || $reading->status == 'Atgriezts') { ?>
                <tr id='rdn<?php echo $reading->id;?>' class='hidden'>
                    <td><?php //echo $key + 1; ?></td>
                    <td>
                        <form method="POST" action="/abonents/iesniegt-merijumu">
                            <input type="hidden" name="reading_id" value="<?php echo $reading->id;?>"/>
                            <input type="hidden" name="meter_id" value="<?php echo $meter_id;?>"/>
                            <input type="hidden" name="<?php echo \Config::get('security.csrf_token_key');?>" value="<?php echo \Security::fetch_token();?>" />
                            <input type='hidden' class='input-action' name="action" />
                            <input name="reading" class="form-control" value='<?php echo $reading->lead; ?>' type="text"/>
                            <input type='hidden' name="date_taken" value="<?php echo $reading->date_taken; ?>" />
                    </td>
                    <td><?php //echo $reading->status; ?></td>
                    <td></td>
                    <td><?php echo $reading->date_taken; ?></td>
                    <td></td>
                    <td><button class='save-rdn btn btn-sm btn-primary <?php if($reading->status == 'Iesniegts') echo 'disabled'; ?>'>Saglabāt</button></td>
                        </form>
                </tr>
                <?php } ?>
            <?php } ?>                  
            </table>
        </div>
</div>

<script>
    $(document).ready(function() {
    
    //Darbības {saglabāt;iesniegt}
    $('.submit-rdn').click(function(){
        $('.input-action').attr('value','1');
    })    
    $('.save-rdn').click(function(){
        $('.input-action').attr('value','2');
    })    
    
    $( ".rdn-edit-btn" ).click(function() {
        var item = '#' + $(this).attr('data');
        
        $( item ).slideToggle(function() {
          $(item).toggleClass('hidden');
        });
      });
      
  });
</script>