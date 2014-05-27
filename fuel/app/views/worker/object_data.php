<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Objekta papildus informācija</h4>
        </div>
        <div class="modal-body">
            
            <div class="row">
                <div class="col-md-5">
                    <h3>Pievienot jaunu pakalpojumu</h3>
                        <?php if(!empty($services)) { ?>
                        <table id="new_service" class=" table table-bordered table-striped">
                            <tbody> 
                                <tr>         
                                    <td width="30%">Pakalpojums</td>
                                    <td><a href="#" id='service_name' class="myeditable editable editable-click editable-empty" data-type="select" data-name="service" data-source="[<?php foreach($services as $service) { echo '{value: ' . $service->id . ', text: ' . "'" . $service->name . "'},"; } ?>]" data-emptytext='nav ievadīts' data-original-title="Izvēlies pakalpojumu"></a></td>
                                    <td class="hidden"><a href='#' class="myeditable" data-type="text" data-name="object_id"><?php echo $object_id; ?></a></td>
                                </tr>
                                <tr>         
                                    <td>Termiņš no</td>
                                    <td><a href="#" id='srv_date_from' class="myeditable editable editable-click editable-empty" data-datepicker='{weekStart:1}' data-type="date" data-placement="bottom" data-name="date_from" data-viewformat="dd/mm/yyyy" data-emptytext='nav ievadīts' data-original-title="Izvēlies datumu"></a></td>
                                </tr>  
                                <tr>         
                                    <td>Termiņš līdz</td>
                                    <td><a href="#" id='srv_date_to' class="myeditable editable editable-click editable-empty" data-datepicker='{weekStart:1}' data-type="date" data-placement="bottom" data-name="date_to" data-viewformat="dd/mm/yyyy" data-emptytext='nav ievadīts' data-original-title="Izvēlies datumu"></a></td>
                                </tr>     
                            </tbody>
                        </table>
                    
                    <a href='#' id='btn_save' class="btn btn-default">Pievienot pakalpojumu</a>
                    
                        <?php } else { ?>
                    <p>Sistēmā šobrīd nav neviena pakalpojuma! Pakalpojumus var izveidot <a href='/darbinieks/pakalpojumi' title='Pievienot pakalpojumu'>šeit</a>.</p>
                        <?php } ?>

                </div>
            </div>

            <hr/>
            
            <h3>Aktīvie pakalpojumi: </h3>
            <hr/>
            <?php if(!empty($obj_services)) { ?>
            
                <?php foreach ($obj_services as $key => $service) { ?>
                <?php $days = date_format(date_create($service->srv_to), 'd.m.Y') - date('d.m.Y'); ?>
            
                    <?php if(date_format(date_create($service->srv_to), 'd.m.Y') - $days > 0) { ?>
            <p><?php echo $service -> service_name; ?> (<a class='rm-service' href="#" onclick="return confirm('Vai tiešām atslēgt?')" data-object='<?php echo $service->object_id; ?>' data-service='<?php echo $service->service_id; ?>'><span class="glyphicon glyphicon-remove"></span> Atslēgt</a>)</p>
                        <p><strong>Termiņš:</strong> no <a href="#" data-name='service_from' data-params='{"object_id": "<?php echo $service->object_id; ?>"}' data-pk='<?php echo $service->usr_srv_id; ?>' class="service_from"><?php echo date_format(date_create($service->srv_from), 'd.m.Y'); ?></a> līdz <a href="#" data-name='service_to' data-params='{"object_id": "<?php echo $service->object_id; ?>"}' data-pk='<?php echo $service->usr_srv_id; ?>' class="service_to"><?php echo date_format(date_create($service->srv_to), 'd.m.Y'); ?></a></p>
                        <p><a href='/darbinieks/klienti/apskatit-pakalpojumu/<?php echo $service->object_id; ?>/<?php echo $service->usr_srv_id; ?>'>Apskatīt sīkāk</a></p>
                        <hr/>
                    <?php } ?>
                        
                <?php } ?>
            <?php } else { ?>
                    <p>Pašlaik nav neviena aktīva pakalpojuma</p>
            <?php } ?>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Aizvērt</button>
        </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
  
<script>
        //Labo datumu "no" ar ajax un x-editable palīdzību
        $('.service_from').editable({
            type: 'text',
            url: '/darbinieks/klienti/labot-pakalpojumu',
            title: 'Izvēlies datumu',
            mode: 'inline',
            success: function(response) {
                if(!response) {
                    return "Kļūda! Lūdzu, ievadiet korektu datumu!";
                } 
            }  
        });     
        
        //Labo datumu "līdz" ar ajax un x-editable palīdzību
        $('.service_to').editable({
            type: 'text',
            url: '/darbinieks/klienti/labot-pakalpojumu',
            title: 'Izvēlies datumu',
            mode: 'inline',
            success: function(response) {
                if(!response) {
                    return "Kļūda! Lūdzu, ievadiet korektu datumu!";
                } 
            }  
        });      
        
        //Ļauj labot visas nepieciešamās lietas (palaiž instanci)
        $('.myeditable').editable({
            //url: '/post'
        });

        //Obligāts lauks
        $('#service_name').editable('option', 'validate', function(v) {
            if(!v) return 'Obligāts lauks!';
        });
        
        $('#srv_date_from').editable('option', 'validate', function(v) {
            if(!v) return 'Obligāts lauks!';
        });
        
        $('#srv_date_to').editable('option', 'validate', function(v) {
            if(!v) return 'Obligāts lauks!';
        });
        
        $('.service_from').editable('option', 'validate', function(v) {
            if(!v) return 'Obligāts lauks!';
        });
        
        $('.service_to').editable('option', 'validate', function(v) {
            if(!v) return 'Obligāts lauks!';
        });

        //Automātiski parādīt nākamo labojamo logu
        $('.myeditable').on('save.newuser', function(){
            var that = this;
            setTimeout(function() {
                $(that).closest('tr').next().find('.myeditable').editable('show');
            }, 200);
        });
        
        //Saglabāt pakalpojumu datubāzē
        $('#btn_save').click(function() {
           $('.myeditable').editable('submit', { 
               url: '/darbinieks/klienti/pievienot-pakalpojumu', 
               ajaxOptions: {
                   dataType: 'json' //assuming json response
               },           
               success: function(data, config) {
                   $('#obj_info').modal('hide');
                   $('.modal').removeData('bs.modal');
                   alert('Pakalpojums pievienots!');
               },
               error: function(errors) {
                   alert('Pakalpojumu neizdevās pievienot!');
               }
           });
        });
        
        //Atslēgt pakalpojumu
        $('.rm-service').click(function(e) {
            e.preventDefault();
            $.post( "/darbinieks/klienti/atslegt-pakalpojumu", { object_id: $(this).attr('data-object'), 
                                                                     service_id: $(this).attr('data-service') })
            .done(function( data ) {
                $('#obj_info').modal('hide');
                $('.modal').removeData('bs.modal');
                alert( "Pakalpojums atslēgts!" );
            });
        })
</script>