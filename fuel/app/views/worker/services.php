<link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>
<script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
<div class="container">
    <div class="row">
        <div class="col-md-5">
            <h1>Pakalpojumu pārvaldība</h1>
            <hr/>
            <a href="/darbinieks/klienti" alt="atpakaļ">Doties atpakaļ</a>
        </div>
    </div>
    
    <div class='row main-block'>
        <a href='#' class='btn btn-default' data-toggle="modal" data-target="#pievienot_pakalpojumu" title='Pievienot pakalpojumu'>Pievienot jaunu pakalpojumu</a>
    </div>

    <div class='row main-block'>
        <div class='col-md-offset-2 col-md-9'>
            
        <?php if(Session::get_flash('success')) { ?>
                <div class="alert alert-success">
                    <p class="text-success"><?php echo Session::get_flash('success'); ?></p>
                </div>

        <?php } elseif(Session::get_flash('error')) { ?>
                <div class="alert alert-danger">
                    <p class="text-danger"><?php echo Session::get_flash('error'); ?></p>
                </div>
        <?php } ?>
            
            <?php if(!empty($services)) { ?>
                <?php foreach($services as $service) { ?>
                    <div class='well well-sm'>
                        <a href='/darbinieks/pakalpojumi/dzest/<?php echo $service->srv_id; ?>' onclick='return confirm("Vai tiešām vēlaties dzēst pakalpojumu?")' class="close">&times;</a>
                        <p><strong>Kods:</strong> <a href="#" data-pk="<?php echo $service->code_id;?>" class="code editable editable-click"><?php echo $service -> code; ?></a></p>
                        <p><strong>Nosaukums:</strong> <a href="#" data-pk="<?php echo $service->srv_id; ?>" class="srv_name editable editable-click"><?php echo $service -> name; ?></a></p>
                        <p><strong>Apraksts:</strong> <a href="#" data-pk="<?php echo $service->srv_id; ?>" class="srv_desc editable editable-click"><?php echo $service -> description; ?></a></p>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <p>Šobrīd nav pievienots neviens pakalpojums</p>
            <?php } ?>
        </div>            
    </div>
    
<div id='pievienot_pakalpojumu' class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Pievienot jaunu pakalpojumu</h4>
      </div>
      <div class="modal-body">
            <form method='POST' action='/darbinieks/pakalpojumi/pievienot' role="form">
            <input type="hidden" name="<?php echo \Config::get('security.csrf_token_key');?>" value="<?php echo \Security::fetch_token();?>" />
              <div class="form-group">
                <label for="code">Pakalpojuma kods</label>
                <input type="text" class="form-control" name='code' id="code" placeholder="piemēram, A_UDENS vai K_UDENS">
              </div>
              <div class="form-group">
                <label for="code_notes">Pakalpojuma koda apraksts</label>
                <input type="text" class="form-control" name='code_notes' id="code_notes" placeholder="piemēram, Aukstā ūdens pieslēguma pakalpojuma kods">
              </div>
              <div class="form-group">
                <label for="service_name">Pakalpojuma nosaukums</label>
                <input type="text" class="form-control" name='service_name' id="service_name" placeholder="piemēram, Aukstais ūdens">
              </div>
              <div class="form-group">
                <label for="service_notes">Pakalpojuma apraksts</label>
                <input type="text" class="form-control" name='service_notes' id="service_notes" placeholder="piemēram, Aukstā ūdens pieslēgums">
              </div>
      </div>
      <div class="modal-footer">
        <button type="reset" class="btn btn-default" data-dismiss="modal">Notīrīt un aizvērt</button>
        <button type="submit" class="btn btn-primary">Pievienot</button>
            </form>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<script>
    $(document).ready(function() {
        
   $('#code').keyup(function() {
        this.value = this.value.toLocaleUpperCase();
    });        
        
    //Labo pakalpojuma kodu ar ajax un x-editable palīdzību
    $('.code').editable({
        type: 'text',
        url: '/darbinieks/pakalpojumi/labot',
        params: {
            action: 'code',
        },
        success: function(response) {
            if(!response) {
                return "Kļūda! Šāds kods jau pastāv datubāzē.";
            } 
        }  
    }); 
    
    //Labo pakalpojuma nosaukumu ar ajax un x-editable palīdzību
    $('.srv_name').editable({
        type: 'text',
        url: '/darbinieks/pakalpojumi/labot',
        params: {
            action: 'srv_name',
        },
        success: function(response) {
            if(!response) {
                return "Kļūda! Apraksts netika labots. Mēģini vēlreiz vai sazinies ar administratoru.";
            } 
        }  
    }); 
    
    //Labo pakalpojuma aprakstu ar ajax un x-editable palīdzību
    $('.srv_desc').editable({
        type: 'text',
        url: '/darbinieks/pakalpojumi/labot',
        params: {
            action: 'srv_desc',
        },
        success: function(response) {
            if(!response) {
                return "Kļūda! Apraksts netika labots. Mēģini vēlreiz vai sazinies ar administratoru.";
            } 
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

