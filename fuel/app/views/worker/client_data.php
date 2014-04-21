<?php if(!empty($client_data)) { ?>
    <?php foreach ($client_data as $nr => $data) { ?>

        <h3><?php echo $data -> name . ' ' . $data -> surname . ' - ' . $data -> username; ?></h3>
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" id="mytab">
              <li><a href="#objekti" data-toggle="tab">Objekti</a></li>
              <li><a href="#vesture" data-toggle="tab">Abonenta vēsture</a></li>
            </ul>
            <br/>
            <!-- Tab panes -->
            <div class="tab-content">
                <div class="tab-pane fade" id="objekti">
                    <button style="margin-bottom: 15px" data-toggle="modal" data-target="#pievienot_objektu" class="btn btn-default btn-large"><span class="glyphicon glyphicon-plus-sign"></span> Pievienot</button>
                    <?php if(!empty($client_objects)) { ?>
                        <ul id='client_objects' class='list-unstyled'>
                            <?php foreach ($client_objects as $nr => $object) { ?>
                            <li><?php echo $nr + 1 . '. ' . $object -> object_name . ' - ' . $object -> object_addr; ?> <a href='/ieladet-objekta-datus/<?php echo $object->object_id; ?>' data-toggle="modal" data-target="#obj_info"><span class='glyphicon glyphicon-eye-open'></span> Pakalpojumi</a></li>
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
        
<script>
    $(document).ready(function() {
        $('#mytab a:first').tab('show');
    });
</script>




<!-- Pievienot objektu-->
<div class="modal fade" id="pievienot_objektu" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Pievienot objektu</h4>
      </div>

    <form id="add_object" action="/klients/pievienot-objektu" method="POST" role="form">
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
                            <input id="district" name="district" type="text" class="form-control" placeholder="Rajons">
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
                        required: "Lūdzu, ievadiet rajonu vai novadu!",
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
        
        