<?php echo Asset::js('bootstrap-datepicker.js'); ?>
<?php echo Asset::css('datepicker3.css'); ?>
<div class="container">
    <div class="row">
        <div class="col-md-5">
            <h1><?php echo $objects[0]->name;?> </h1>
            <p><?php echo $objects[0]->address; ?></p>
            <hr/>
            <a href="/klients" alt="atpakaļ">Doties atpakaļ</a>
        </div>
    </div>
    

    <div class="row">
        <br/>
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" id="mytab">
          <li><a href="#pakalpojumi" data-toggle="tab">Pakalpojumi</a></li>
          <li><a href="#skaititaji" data-toggle="tab">Visi skaitītāji</a></li>
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
                
                <?php //$days = date_diff(date_create($service->date_to),date_create(date('Y-m-d')))->format('%d'); ?>
                    <p><strong>Pakalpojums:</strong> <a href='/klients/pakalpojumi/apskatit/<?php echo $service->obj_id . '/' . $service->usr_srv_id; ?>'><?php echo $service->name; ?> - <em><?php echo $service -> description; ?></em></a> (<a href="#" class='dismiss_service' data-pk='<?php echo $service -> usr_srv_id; ?>' data-toggle="modal" data-target="#dismiss_service"><span class="glyphicon glyphicon-remove"></span> Atteikties</a>)</p>
                    <p><strong>Termiņš:</strong> <?php echo date_format(date_create($service->date_from), 'd.m.Y');?> - <?php echo date_format(date_create($service->date_to), 'd.m.Y');?> <!--(<?php //if($days == 1) echo 'vēl atlikusi ' . $days . ' diena'; elseif($days > 1) echo 'vēl atlikušas ' . $days . ' dienas'; else echo 'nav aktīvs';?>)--></p>
                    <br/><br/>
                <?php } ?>
                <?php } else echo '<p>Objektam nav neviena pakalpojuma</p>'; ?>

            </div>
            
            <div class="tab-pane fade" id="skaititaji">
                <?php if(!empty($meters)) { ?>
                        <div class='entered-reading hidden'>
                            <hr/>
                            <h3 class='text-center'></h3>
                            <hr/>
                        </div>
                
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
                                    <td class='previous-reading'><?php echo $meter->lead; ?></td>
                                    <td>
                                        <form method="POST" action="/klients/iesniegt-merijumu">
                                            <input type="hidden" name="meter_id" value="<?php echo $meter->id;?>"/>
                                            <input type="hidden" name="<?php echo \Config::get('security.csrf_token_key');?>" value="<?php echo \Security::fetch_token();?>" />
                                            <input maxlength='11' name="reading" class="reading-input form-control" type="text"/>
                                            <input type='hidden' class='input-action' name="action" />
                                            
                                    </td>
                                    <td><button onclick='return confirm("Iesniegt bez iespējas vēlāk labot?")' type="submit" class="submit-rdn btn btn-default">Iesniegt</button></td>
                                    <td><button type="submit" class="save-rdn btn btn-primary">Saglabāt</button></td>
                                        </form>
                                    <td><a href="/klients/objekti/radijumi/<?php echo $meter->id; ?>" class="btn btn-link">Apskatīt</a></td>
                                </tr>
                                <?php } ?>
                            </table>
                <?php } else echo '<p>Objektam nav neviena skaitītāja</p>'; ?>
            </div>            
        </div>
   </div>
</div>

<!-- Atteikties no pakalpojuma -->
<div class="modal fade" id="dismiss_service" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Atteikties no pakalpojuma</h4>
      </div>
        <form method="POST" action="/klients/pakalpojumi/atteikties">
            <input id='object' type='hidden' name='object' value='<?php echo $objects[0]->object_id; ?>' />
            <input id='service' type='hidden' name='service' />
            
            <div class="modal-body">
                <div class="date-pick form-group">
                   <label for="date_from">Datums, kurā vēlaties beigt saistības</label>       
                    <div class="input-group date">
                      <input name='date_from' type="text" class="form-control" placeholder='dd.mm.gggg'><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="notes">Paskaidrojums, kādēļ vēlaties atteikties</label>       
                    <textarea id="notes" name="notes" type="text" class="form-control" placeholder=""></textarea>
                </div>
            </div>
            <div class="modal-footer">
              <button type="reset" class="btn btn-default" data-dismiss="modal">Atcelt</button>
              <button type="submit" class="btn btn-primary">Atteikties</button>
            </div>
        </form>
    </div>
  </div>
</div>
<!-- /Atteikties no pakalpojuma -->

<script>
    $(document).ready(function() {
    
    $('#mytab a:first').tab('show');
    
    $('.dismiss_service').click(function(){
        $('#service').attr('value',$(this).attr('data-pk'));
    });
    
    //Ja ievadīts rādījums, tad parādām lietotājam, cik daudz ir ievadīts kopš pagājušā mērījuma
    $('.reading-input').keyup(function() {
        //Ja nav skaitliska vērtība, tad paziņo
        if(!parseInt($(this).val()))
        {
            $('.entered-reading h3').text('Ievadītajam rādījumam jābūt skaitliskai vērtībai!');
            $('.entered-reading').removeClass('hidden');
        }
        else if ( (parseInt($(this).val()) - parseInt($(this).parent().parent().parent().find('.previous-reading').text())) < 0 )
        {
            $('.entered-reading h3').text('Ievadītajam rādījumam jābūt lielākam par iepriekšējo rādījumu!');
            $('.entered-reading').removeClass('hidden');
        }
        else
        {
            //Ja ir skaitliska vērtība, tad parāda, cik ir ievadīts
            $('.entered-reading h3').text('Jūsu ievadītais patēriņš: ' + (parseInt($(this).val()) - parseInt($(this).parent().parent().parent().find('.previous-reading').text()) ) + ' kubikmetri');
            $('.entered-reading').removeClass('hidden');
        }   
    });
    
    //Ja nav ievadīta nekāda vērtība un iziet ārā no lauka, tad slēpjam paziņojumu
    $('.reading-input').blur(function() {
       if(!$(this).val())
       {
           $('.entered-reading').addClass('hidden');
       }
    });
    

    $('.date-pick .input-group.date').datepicker({
        weekStart: 1,
        minDate: 0,
        format: "dd.mm.yyyy",
        autoclose: true,
        todayBtn: "linked",
        language: "lv",
        orientation: "top auto",
        todayHighlight: true
    });
    
    //Darbības {saglabāt;iesniegt}
    $('.submit-rdn').click(function(){
        $('.input-action').attr('value','1');
    })    
    $('.save-rdn').click(function(){
        $('.input-action').attr('value','2');
    })    

    });
</script>