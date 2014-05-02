<div class="container">
    <div class="row">
        <div class="col-md-5">
            <h1><?php echo $objects[0]->name;?> </h1>
            <p><?php echo $objects[0]->address; ?></p>
            <hr/>
            <a href="/abonents" alt="atpakaļ">Doties atpakaļ</a>
        </div>
    </div>
    

    <div class="row">
        <br/>
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" id="mytab">
          <li><a href="#pakalpojumi" data-toggle="tab">Pakalpojumi</a></li>
          <li><a href="#skaititaji" data-toggle="tab">Visi skaitītāji</a></li>
          <li><a href="#vesture" data-toggle="tab">Pakalpojumu vēsture</a></li>
        </ul>
        <br/>
        <!-- Tab panes -->
        <div class="tab-content">
            <div class="tab-pane fade" id="pakalpojumi">
                
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
                
                <?php if(!empty($services)) { 
                        foreach ($services as $service) { ?>
                
                <?php $days = date_diff(date_create($service->date_to),date_create(date('Y-m-d')))->format('%a'); ?>
                    <p><strong>Pakalpojums:</strong> <a href='/abonents/pakalpojumi/apskatit/<?php echo $service->obj_id . '/' . $service->usr_srv_id; ?>'><?php echo $service->name; ?> - <em><?php echo $service -> description; ?></em></a></p>
                <p><strong>Termiņš:</strong> <?php echo date_format(date_create($service->date_from), 'd.m.Y');?> - <?php echo date_format(date_create($service->date_to), 'd.m.Y');?> (<?php if($days==1) echo 'vēl atlikusi ' . $days . ' diena'; elseif($days>1) echo 'vēl atlikušas ' . $days . ' dienas'; else echo 'nav aktīvs';?>)</p>
                <br/><br/>
                <?php } ?>
                <?php } else echo '<p>Objektam nav neviena pakalpojuma</p>'; ?>

            </div>
            
            <div class="tab-pane fade" id="skaititaji">
                <?php if(!empty($meters)) { ?>
                            <table class="table text-center">
                                <thead>
                                    <th>Nr.p.k</th>
                                    <th>Ūdens tips</th>
                                    <th>Skaitītāja numurs</th>
                                    <th>Pēdējās iesniegšanas datums</th>
                                    <th>Iepriekšējais rādījums</th>
                                    <th>Nākošais rādījums</th>
                                    <th></th>
                                    <th></th>
                                    <th>Rādījumu vēsture</th>
                                </thead>
                                <?php foreach ($meters as $nr => $meter) { ?>
                                <tr>
                                    <td><?php echo $nr + 1; ?></td>
                                    <td><?php if($meter->water_type == 'A') echo 'Aukstais ūdens'; else echo 'Karstais ūdens'; ?></td>
                                    <td><?php echo $meter->meter_number;?></td>
                                    <td><?php echo date_format(date_create($meter->date_taken), 'd.m.Y') ;?></td>
                                    <td><?php echo $meter->lead; ?></td>
                                    <td>
                                        <form method="POST" action="/abonents/iesniegt-merijumu">
                                            <input type="hidden" name="meter_id" value="<?php echo $meter->id;?>"/>
                                            <input type="hidden" name="<?php echo \Config::get('security.csrf_token_key');?>" value="<?php echo \Security::fetch_token();?>" />
                                            <input name="reading" class="form-control" type="text"/>
                                            <input type='hidden' class='input-action' name="action" />
                                            
                                    </td>
                                    <td><button onclick='return confirm("Iesniegt bez iespējas vēlāk labot?")' type="submit" class="submit-rdn btn btn-default">Iesniegt</button></td>
                                    <td><button type="submit" class="save-rdn btn btn-primary">Saglabāt</button></td>
                                        </form>
                                    <td><a href="/abonents/objekti/radijumi/<?php echo $meter->id; ?>" class="btn btn-link">Apskatīt</a></td>
                                </tr>
                                <?php } ?>
                            </table>
                <?php } else echo '<p>Objektam nav neviena skaitītāja</p>'; ?>
            </div>
            
            <div class="tab-pane fade active" id="vesture">
                Pakalpojumu vēsture
            </div>
            
        </div>
   </div>
</div>

<script>
    $(document).ready(function() {
    
    $('#mytab a:first').tab('show');
    
    //Darbības {saglabāt;iesniegt}
    $('.submit-rdn').click(function(){
        $('.input-action').attr('value','1');
    })    
    $('.save-rdn').click(function(){
        $('.input-action').attr('value','2');
    })    

    });
</script>