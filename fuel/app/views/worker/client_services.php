<link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>
<script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
<?php echo Asset::js('bootstrap-datepicker.js'); ?>
<?php echo Asset::css('datepicker3.css'); ?>
<style>
    .datepicker {
        z-index: 1151!important;
    } 
</style>

<div class="container">
    <div class="row">
        <div class="col-md-5">
            <h1><?php echo $object[0]->object_name;?> </h1>
            <p><?php echo $object[0]->object_addr; ?></p>
            <hr/>
            <a href="/darbinieks/abonenti" alt="atpakaļ">Doties atpakaļ</a>
        </div>
    </div>
    
    <div class="row">
        <h3><?php echo $service[0]->service_name . ' (' . date_format(date_create($service[0]->srv_from), 'd.m.Y') . ' - ' . date_format(date_create($service[0]->srv_to), 'd.m.Y') . ')'; ?> </h3>
        <hr/>
        
        <div class="col-md-5">
            <?php if(Session::get_flash('success')) { ?>
                    <div class="alert alert-success">
                    <p class="text-success"><?php echo Session::get_flash('success'); ?></p>
                    </div>

            <?php } elseif(Session::get_flash('error')) { ?>
                                <div class="alert alert-danger">
                                    <p class="text-danger"><?php echo Session::get_flash('error'); ?></p>
                                </div>
            <?php } ?>
            
        <?php if(!empty($meters)) { ?>   
            <h4>Skaitītāji</h4>
            <?php foreach ($meters as $meter) { ?>
            
            <ul class='list-group'>
                <li class="list-group-item"><strong>Skaitītāja numurs:</strong> <a href="#" data-pk="<?php echo $meter->id; ?>" class="meter_number editable editable-click editable-empty" data-emptytext='nav ievadīts' data-original-title="Labot skaitītāja numuru"><?php echo $meter->meter_number; ?></a></li>
                <li class="list-group-item">Termiņš: no <a href="#" data-datepicker='{weekStart:1}' data-type="date" data-placement="bottom" data-name="date_from" data-viewformat="dd.mm.yyyy" data-emptytext='nav ievadīts' data-original-title="Izvēlies datumu" data-pk="<?php echo $meter->id; ?>" class="date_from editable editable-click editable-empty" data-emptytext='nav ievadīts' data-original-title="Labot skaitītāja termiņu"><?php echo date_format(date_create($meter->date_from), 'd.m.Y'); ?></a> līdz 
                                                        <a href="#" data-datepicker='{weekStart:1}' data-type="date" data-placement="bottom" data-name="date_from" data-viewformat="dd.mm.yyyy" data-emptytext='nav ievadīts' data-original-title="Izvēlies datumu" data-pk="<?php echo $meter->id; ?>" class="date_to editable editable-click editable-empty" data-emptytext='nav ievadīts' data-original-title="Labot skaitītāja termiņu"><?php echo date_format(date_create($meter->date_to), 'd.m.Y'); ?></a></li>
                <li class="list-group-item">Rādījums uzstādīšanas brīdī: <?php echo $meter->meter_lead; ?></li>
                <li class='list-group-item'><a href='/darbinieks/abonenti/skaititaji/nonemt/<?php echo $meter->service_id; ?>'>Noņemt skaitītāju</a></li>
            </ul>
        
            <?php } ?>
        <?php } else { ?>
            <p>Šim pakalpojumam nav neviena skaitītāja</p>
        <?php } ?>
        <button data-toggle="modal" data-target="#pievienot_skaititaju" class="btn btn-default btn-large"><span class="glyphicon glyphicon-plus-sign"></span> Pievienot skaitītāju</button>
        </div>
    </div>
    
</div>
<!-- Pievienot skaitītāju -->
<div class="modal fade" id="pievienot_skaititaju" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Pievienot skaitītāju</h4>
      </div>

    <form id="add_meter" action="/klients/pievienot-skaititaju" method="POST" role="form">
      <input type="hidden" name="<?php echo \Config::get('security.csrf_token_key');?>" value="<?php echo \Security::fetch_token();?>" />
      <input type="hidden" name="service_id" value="<?php echo $service[0]->usr_srv_id; ?>" />
      <input type='hidden' name='object_id' value='<?php echo $object[0]->object_id; ?>' />
      <div class="modal-body">
        <div class="form-group">
            <label for="number">Skaitītāja numurs</label>       
            <input id="number" name="number" type="text" class="form-control" placeholder="Numurs, kas rakstīts uz skaitītāja priekšējā stikliņa">
        </div>
        <div class="form-group">
           <label for="lead">Skaitītāja rādījums uzstādīšanas brīdī</label>
            <div class="row">
                 <div class="col-xs-4">
                     <div class="form-group">
                         <input id="lead" name="lead" type="text" class="form-control" placeholder="000000">
                     </div>
                 </div>
             </div>
         </div>
          
        <div class="date-pick form-group">
           <label for="date_from">Datums, kad uzstādīts</label>       
            <div class="input-group date">
              <input name='date_from' type="text" class="form-control" placeholder='dd.mm.gggg'><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
            </div>
        </div>
          
        <div class="date-pick form-group">
           <label for="date_to">Nākamās nomaiņas datums</label>       
            <div class="input-group date">
              <input name='date_to' type="text" class="form-control" placeholder='dd.mm.gggg'><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
            </div>
        </div>
          
        <div class="form-group">
            <label for="meter_type">Skaitītāja veids</label>       
            <select name='meter_type' type="text" class="form-control">
                <option disabled selected>Izvēlēties</option>
                <option value='FILTER'>Ar filtru</option>
                <option value='NO_FILTER'>Bez filtra</option>
            </select>
        </div>
          
        <div class="form-group">
            <label for="water_type">Ūdens veids</label>       
            <select name='water_type' type="text" class="form-control">
                <option disabled selected>Izvēlēties</option>
                <option value='K'>Karstais ūdens</option>
                <option value='A'>Aukstais ūdens</option>
            </select>
        </div>
          
        <div class="modal-footer">
          <button id="reset" type="button" class="btn btn-default" data-dismiss="modal">Notīrīt un aizvērt</button>
          <button type="submit" class="btn btn-primary">Pievienot</button>
        </div>
      </div>
    </form>
  </div>
</div>
</div>
<!-- /pievienot skaitītāju -->
<script>
    $(document).ready(function() {
        
    //Labo e-pastu ar ajax un x-editable palīdzību
    $('.meter_number').editable({
        type: 'text',
        url: '/darbinieks/abonenti/skaititaji/labot/',
        params: {
            action: 'meter_number',
        },
        success: function(response) {
            if(!response) {
                return "Skaitītājs ar šādu numuru jau eksistē!";
            } 
        }  
    }); 
    
    //Labo datums no ar ajax un x-editable palīdzību
    $('.date_from').editable({
        type: 'text',
        url: '/darbinieks/abonenti/skaititaji/labot/',
        params: {
            action: 'date_from',
        },
        success: function(response) {
            if(!response) {
                return "Notikusi kļūda! Datums nav nomainīts.";
            } 
        }  
    }); 
    
    //Labo datums līdz ar ajax un x-editable palīdzību
    $('.date_to').editable({
        type: 'text',
        url: '/darbinieks/abonenti/skaititaji/labot/',
        params: {
            action: 'date_to',
        },
        success: function(response) {
            if(!response) {
                return "Notikusi kļūda! Datums nav nomainīts.";
            } 
        }  
    }); 
    
        //Obligāts lauks
        $('.meter_number').editable('option', 'validate', function(v) {
            if(!v) return 'Obligāts lauks!';
        });
    
        //Obligāts lauks
        $('.date_from').editable('option', 'validate', function(v) {
            if(!v) return 'Obligāts lauks!';
        });
        
        //Obligāts lauks
        $('.date_to').editable('option', 'validate', function(v) {
            if(!v) return 'Obligāts lauks!';
        });
    
        
    $('.date-pick .input-group.date').datepicker({
        weekStart: 1,
        format: "dd.mm.yyyy",
        autoclose: true,
        todayBtn: "linked",
        language: "lv",
        orientation: "top auto",
        todayHighlight: true
    });
        
    $('#add_meter').validate({
                rules: {
                    number: {
                        required: true,
                    },
                    lead: {
                        required: true,
                    },
                    date_from: {
                        required: true,
                    },
                    date_to: {
                        required: true,
                    },
                    meter_type: {
                        required: true,
                    },
                },

                messages: {
                    number: {
                        required: "Lūdzu, ievadiet skaitītāja numuru!",
                    },
                    lead: {
                        required: "Lūdzu, ievadiet skaitītāja rādījumu!",
                    },
                    date_from: {
                        required: "Lūdzu, ievadiet uzstādīšanas datumu!",
                    },
                    date_to: {
                        required: "Lūdzu, ievadiet nākamās nomaiņas datumu!",
                    },
                    meter_type: {
                        required: "Lūdzu, ievadiet skaitītāja veidu!",
                    },
                },
                    submitHandler: function(form) {
                        form.submit();
                    }
            });    
            
        $('[data-dismiss=modal]').on('click', function (e) {
            var $t = $(this),
                target = $t[0].href || $t.data("target") || $t.parents('.modal') || [];

            $(target).find('.tooltip').remove();
                
            $(target)
              .find("input,textarea,select")
                 .val('')
                 .end()
              .find("input[type=checkbox], input[type=radio]")
                 .prop("checked", "")
                 .end();
        })

    });
</script>