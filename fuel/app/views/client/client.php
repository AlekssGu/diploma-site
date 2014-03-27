
<div class="container">
    <div class="row">
        <div class="col-md-5">
            <h1>Mana klienta informācija</h1>
            <hr/>
            <a href="/" alt="atpakaļ">Doties atpakaļ</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <Br/>
            <div class="well well-lg">
                <p><strong>Klienta numurs:</strong> <?php echo $client_number; ?></p>
                <p><strong>Vārds, uzvārds:</strong> <?php echo $fullname; ?></p>
                <p><strong>E-pasts:</strong> <?php echo $email; ?></p>
                <p><strong>Tālr. nr.:</strong> <?php echo $phone; ?></p>
                <p><strong>Faktiskā adrese:</strong> <?php echo $secondary_address; ?></p>
                <p><strong>Deklarētā adrese:</strong> <?php echo $primary_address; ?></p>
            </div>
        </div>
        <div class="col-md-4">
            <h3>Mani objekti</h3>
            <?php if(Session::get_flash('success')) { ?>
                    <div class="alert alert-success">
                        <p class="text-success"><?php echo Session::get_flash('success'); ?></p>
                    </div>

            <?php } elseif(Session::get_flash('error')) { ?>
                    <div class="alert alert-danger">
                        <p class="text-danger"><?php echo Session::get_flash('error'); ?></p>
                    </div>
            <?php } ?>
            <ul class="list-group">
                <?php foreach ($objects as $object) { ?>
                <a href="/klients/objekti/apskatit/<?php echo $object->object_id; ?>" title="Spied šeit, lai apskatītu sīkāku informāciju" class="list-group-item"><p class="text-danger"><span class="glyphicon glyphicon-exclamation-sign"></span> <strong>Parāds:</strong> <?php echo 5.45+round(rand(0, 40));?> EUR</p><?php echo $object->name; ?></a>
                <?php } ?>
            </ul>
            <a href="/klients/objekti" class="btn btn-default btn-large" title="Skatīt visus objektus"><span class="glyphicon glyphicon-list"></span> Skatīt visus objektus</a>
            <button data-toggle="modal" data-target="#pievienot_objektu" class="btn btn-default btn-large"><span class="glyphicon glyphicon-plus-sign"></span> Pievienot</button>
        </div>
        <div class="col-md-4">
            <h3>Mana klienta vēsture</h3>
            <p>Pagaidām nav nekādas klienta informācijas</p>
        </div>
    </div>
</div>

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