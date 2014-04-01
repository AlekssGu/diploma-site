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
            <h1>Objekts: <?php echo $objects[0]->name;?> </h1>
            <p><?php echo $objects[0]->address; ?></p>
            <hr/>
            <a href="/klients" alt="atpakaļ">Doties atpakaļ</a>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-4">
            <h3>Skaitītāji</h3>
            <?php if(Session::get_flash('success')) { ?>
                    <div class="alert alert-success">
                    <p class="text-success"><?php echo Session::get_flash('success'); ?></p>
                    </div>

            <?php } elseif(Session::get_flash('error')) { ?>
                                <div class="alert alert-danger">
                                    <p class="text-danger"><?php echo Session::get_flash('error'); ?></p>
                                </div>
            <?php } ?>
        <hr/>
        <?php foreach ($meters as $meter) { ?>
        
        <ul class='list-group'>
            <li class="list-group-item"><strong>Skaitītāja numurs:</strong> <?php echo $meter->meter_number; ?></li>
            <li class="list-group-item">Termiņš: no <?php echo $meter->date_from . ' līdz ' . $meter->date_to; ?></li>
            <li class="list-group-item">Rādījums uzstādīšanas brīdī: <?php echo $meter->meter_lead; ?></li>
        </ul>
        
        <?php } ?>
        <button data-toggle="modal" data-target="#pievienot_skaititaju" class="btn btn-default btn-large"><span class="glyphicon glyphicon-plus-sign"></span> Pievienot</button>
        </div>
        <div class="col-md-4">
            <h3>Mērījumi</h3>
        </div>
        <div class="col-md-4">
            <h3>Maksājumi</h3>
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
      <input type="hidden" name="object_id" value="<?php echo $objects[0]->object_id;?>" />
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