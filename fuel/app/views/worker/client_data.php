<?php if(!empty($client_data)) { ?>
    <?php foreach ($client_data as $nr => $data) { ?>

        <h3><?php echo $data -> name . ' ' . $data -> surname . ' - ' . $data -> username; ?></h3>
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" id="mytab">
              <li><a href="#klients" data-toggle="tab">Klients</a></li>
              <li><a href="#objekti" data-toggle="tab">Objekti</a></li>
              <li><a href="#vesture" data-toggle="tab">Abonenta vēsture</a></li>
            </ul>
            <br/>
            <!-- Tab panes -->
            <div class="tab-content">
                
                <div class="tab-pane fade" id="klients">
                    <?php if(!empty($client_data)) { ?>
                        <?php foreach($client_data as $client) { ?>
                            <p><strong>Klienta numurs:</strong> <a href="#" data-pk='<?php echo $client->user_id; ?>' title="Labot klienta numuru" class="client_number editable editable-click"><?php echo $client->username; ?></a></p>
                            <p><strong>Personas tips:</strong> <a href="#" data-pk='<?php echo $client->user_id; ?>' title="Mainīt personas tipu" class="person_type editable editable-click" data-type="select"  data-source="[{value:'F', text:'Fiziska persona'},{value:'J', text:'Juridiska persona'}]" data-emptytext='nav ievadīts' data-original-title="Izvēlies personas tipu"><?php if($client->person_type == 'F') echo 'Fiziska persona'; else echo 'Juridiska persona'; ?></a></p>
                            <p><strong>Vārds, Uzvārds:</strong> <a href="#" data-pk='<?php echo $client->user_id; ?>' title="Labot klienta vārdu" class="client_name editable editable-click"><?php echo $client->name; ?></a> <a href="#" data-pk='<?php echo $client->user_id; ?>' title="Labot klienta uzvārdu" class="client_surname editable editable-click"><?php echo $client->surname; ?></a></p>
                            <p><strong>Personas kods:</strong> <a href="#" data-pk='<?php echo $client->user_id; ?>' title="Labot klienta personas kodu" class="client_pk editable editable-click"><?php echo $client->person_code; ?></a></p>
                            <p><strong>Telefona numurs:</strong> <a href="#" data-pk='<?php echo $client->user_id; ?>' title="Labot klienta telefona numuru" class="client_phone editable editable-click"><?php echo $client->mobile_phone; ?></a></p>
                            <p><strong>E-pasta adrese:</strong> <a href="#" data-pk='<?php echo $client->user_id; ?>' title="Labot klienta e-pasta adresi" class="client_email editable editable-click"><?php echo $client->email; ?></a></p>
                            <p><strong>Aktīvs:</strong> <?php if($client->is_active == 'Y') echo 'Jā'; else echo 'Nē'; ?></p>
                            <p><strong>Izveidots:</strong> <?php echo Date::forge($client->created_at)->format('%d.%m.%Y %H:%M'); ?></p>
                            <p><strong>Pēdējoreiz pieslēdzies:</strong> <?php echo Date::forge($client->last_login)->format('%d.%m.%Y %H:%M'); ?></p>
                            <?php if($client->is_active == 'Y') { ?>
                                <p><a href="#" data-pk='<?php echo $client->user_id;?>' class="btn btn-sm btn-danger btn-deactivate" title="Slēgt lietotāja kontu">Slēgt lietotāja kontu</a></p>
                            <?php } else { ?>
                                <p><a href="#" data-pk='<?php echo $client->user_id;?>' class="btn btn-sm btn-success btn-activate" title="Atvērt lietotāja kontu">Atvērt lietotāja kontu</a></p>
                            <?php } ?>
                        <?php } ?>
                    <?php } else { ?>
                        <p>Nav klienta informācijas</p>
                    <?php } ?>
                </div>
                
                <div class="tab-pane fade" id="objekti">
                    
                    <?php if(Session::get_flash('success')) { ?>
                            <div class="alert alert-success">
                                <p class="text-success"><?php echo Session::get_flash('success'); ?></p>
                            </div>

                    <?php } elseif(Session::get_flash('error')) { ?>
                            <div class="alert alert-danger">
                                <p class="text-danger"><?php echo Session::get_flash('error'); ?></p>
                            </div>
                    <?php } ?>
                    
                    <button style="margin-bottom: 15px" data-toggle="modal" data-target="#pievienot_objektu" class="btn btn-default btn-large"><span class="glyphicon glyphicon-plus-sign"></span> Pievienot</button>
                    <?php if(!empty($client_objects)) { ?>
                        <ul id='client_objects' class='list-unstyled'>
                            <?php foreach ($client_objects as $nr => $object) { ?>
                            <li style="margin:5px"><?php echo $nr + 1 . '. ' . $object -> object_name . ' - ' . $object -> object_addr; ?> 
                                                   <a href='/ieladet-objekta-datus/<?php echo $object->object_id; ?>' class="btn btn-sm btn-default" data-toggle="modal" data-target="#obj_info">Pakalpojumi</a> 
                                                   <a href='/darbinieks/abonenti/dzest-objektu/<?php echo $object->object_id; ?>' onclick="return confirm('Vai tiešām vēlaties dzēst objektu?')" class="btn-delete btn btn-sm btn-danger">Dzēst</a></li>
                            <?php } ?>
                        </ul>
                    <?php } else { ?>
                        <p>Abonentam pašlaik nav piesaistīts neviens objekts</p>
                    <?php } ?>
                </div>

                <div class="tab-pane fade active" id="vesture">
                    <?php if(!empty($client_histories)) { ?>
                        <ul id='client_history' class='list-unstyled'>
                            <?php foreach ($client_histories as $nr => $cln_his) { ?>
                            <li><?php echo (Date::forge($cln_his->created_at)->format('%d.%m.%Y %H:%M')) . ' ' . $cln_his->notes; ?></li>
                            <?php } ?>
                        </ul>
                    <?php } else { ?>
                        <p>Abonentam nav vēstures ierakstu</p>
                    <?php } ?>
                </div>
            </div>
    <?php } ?>
<?php } else { ?>
        <p>Klientam pašlaik nav nekādas papildus informācijas</p>
<?php } ?>
        
<!-- Pievienot objektu-->
<div class="modal fade" id="pievienot_objektu" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Pievienot objektu</h4>
      </div>

    <form id="add_object" action="/darbinieks/abonenti/pievienot-objektu" method="POST" role="form">
      <input type="hidden" name="<?php echo \Config::get('security.csrf_token_key');?>" value="<?php echo \Security::fetch_token();?>" />
      <input type="hidden" name="client_id" value="<?php echo $data->user_id; ?>"/>
      <div class="modal-body">
        <div class="form-group">
           <label for="name">Objekta nosaukums</label>
           <input id="name" name="name" type="text" class="form-control" id="exampleInputEmail1" placeholder="piemēram, Dzīvoklis Liepājas centrā vai Māja Mārupē">
         </div>
          
        <div class="form-group">
           <label for="address">Adrese</label>       
                <div class="row">
                    <div class="col-xs-4">
                        <div class="form-group">
                            <input id="street" name="street" type="text" class="form-control" placeholder="Iela">
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="form-group">
                            <input  name="house" id="house" type="text" class="form-control" placeholder="Mājas numurs">
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="form-group">
                            <input name="flat" type="text" class="form-control" placeholder="Dzīvokļa numurs">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-4">
                        <div class="form-group">
                            <input id="district" name="district" type="text" class="form-control" placeholder="Novads">
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="form-group">
                            <input id="post_code" name="post_code" type="text" class="form-control" placeholder="Pasta indekss">
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="form-group">
                            <select name="city_id" class="form-control">
                                <?php foreach ($cities as $city) { ?>
                                <option value="<?php echo $city->id;?>"><?php echo $city -> city_name; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
        </div>
          
        <div class="form-group">
           <label for="notes">Piezīmes</label>
           <textarea name="notes" class="form-control" rows="3" placeholder="Iespējams, ka ir kaut kādi komentāri?"></textarea>
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
<!-- /pievienot objektu -->
<script>
    $(document).ready(function() {
        
    $('#mytab a:first').tab('show');
        
    $('.client_number').editable({
        type: 'text',
        name: 'cln_number',
        url: '/darbinieks/abonenti/labot-datus',
        title: 'Labot klienta numuru',
        validate: function(value) {
           if($.trim(value) == '') return 'Šis ir obligāts lauks!';
           else if(value.length != 8) return 'Klienta numurs sastāv no 8 simboliem!';
        }
    });    
    
    $('.person_type').editable({
        name:'person_type',
        url: '/darbinieks/abonenti/labot-datus',
        validate: function(value) {
           if($.trim(value) == '') return 'Šis ir obligāts lauks!';
        }
    });    
    
    $('.client_name').editable({
        name: 'cln_name',
        type: 'text',
        url: '/darbinieks/abonenti/labot-datus',
        title: 'Labot klienta vārdu',
        validate: function(value) {
           if($.trim(value) == '') return 'Šis ir obligāts lauks!';
        }
    }); 
    
    $('.client_surname').editable({
        name: 'cln_surname',
        type: 'text',
        url: '/darbinieks/abonenti/labot-datus',
        title: 'Labot klienta uzvārdu',
        validate: function(value) {
           if($.trim(value) == '') return 'Šis ir obligāts lauks!';
        }
    }); 
    
    $('.client_pk').editable({
        name: 'client_pk',
        type: 'text',
        url: '/darbinieks/abonenti/labot-datus',
        title: 'Labot klienta personas kodu',
        validate: function(value) {            
           if($.trim(value) == '') return 'Šis ir obligāts lauks!';
           else if(value.length != 12) return 'Personas kodam jāsatur 12 simboli!';
           //else if(!value.match('\d{6}-\d{5}')) return 'Nepareizs personas koda formāts!';
        }
    }); 
    
    $('.client_phone').editable({
        name: 'client_phone',
        type: 'text',
        url: '/darbinieks/abonenti/labot-datus',
        title: 'Labot klienta telefona numuru',
        validate: function(value) {            
           if($.trim(value) == '') return 'Šis ir obligāts lauks!';
        }
    }); 
    
    $('.client_email').editable({
        name: 'client_email',
        type: 'email',
        url: '/darbinieks/abonenti/labot-datus',
        title: 'Labot klienta e-pasta adresi',
        validate: function(value) {            
           if($.trim(value) == '') return 'Šis ir obligāts lauks!';
        }
    }); 
    
    $('.btn-activate').click(function(e) {
        e.preventDefault();
        $.post( "/darbinieks/abonenti/labot-datus", { 
            name: 'activate', 
            pk: $(this).attr('data-pk'),
            value: 'Y'
        },function(data) {
            if(data)
            {
                $( "#client_data" ).load("/ieladet-datus/" + $.parseJSON(data).user_id);
            }
        });
    });
    
    $('.btn-deactivate').click(function(e) {
        e.preventDefault();
        $.post( "/darbinieks/abonenti/labot-datus", { 
            name: 'deactivate', 
            pk: $(this).attr('data-pk'),
            value: 'N'
        },function(data) {
            if(data)
            {
                $( "#client_data" ).load("/ieladet-datus/" + $.parseJSON(data).user_id);
            }
        });

    });

    $('#add_object').validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 3,
                    },
                    street: {
                        required: true,
                        minlength: 3,
                    },
                    house: {
                        required: true,
                    },
                    district: {
                        required: true,
                    },
                    post_code: {
                        required: true,
                    },
                    city_id: {
                        required: true,
                    },
                },

                messages: {
                    name: {
                        required: "Lūdzu, ievadiet objekta nosaukumu!",
                        minlength: "Lūdzu, ievadiet garāku objekta nosaukumu!",
                    },
                    street: {
                        required: "Lūdzu, objekta atrašanās vietas ielas nosaukumu!",
                        minlength: "Adrese noteikti ir garāka par ievadīto!",
                    },
                    house: {
                        required: "Lūdzu, ievadiet objekta ēkas numuru!",
                    },
                    district: {
                        required: "Lūdzu, ievadiet novadu!",
                    },
                    post_code: {
                        required: "Lūdzu, ievadiet pasta indeksu!",
                    },
                    city_id: {
                        required: "Lūdzu, izvēlieties pilsētu!",
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
        
        