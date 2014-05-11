<?php echo Asset::js('bootstrap-datepicker.js'); ?>
<?php echo Asset::css('datepicker3.css'); ?>

<div class="container">
    <div class="row">
        <div class="col-md-5">
            <h1>Pasūtīt pakalpojumu</h1>
            <hr/>
            <a href="/" alt="atpakaļ">Doties atpakaļ</a>
        </div>
    </div>
    

    <div class="row main-block">

                    <div class="col-md-offset-3 col-md-6">
                        <?php if(Session::get_flash('success')) { ?>
                                <div class="alert alert-success">
                                    <p class="text-success"><?php echo Session::get_flash('success'); ?></p>
                                </div>
                            <div class="clearfix"></div>

                        <?php } elseif(Session::get_flash('error')) { ?>
                                <div class="alert alert-danger">
                                    <p class="text-danger"><?php echo Session::get_flash('error'); ?></p>
                                </div>
                            <div class="clearfix"></div>
                        <?php } ?>
                        
                            
                        <?php if(!empty($services)) { ?>
                            <!-- galvenās lapas forma -->
                            <form id="request_form" action="/abonents/pakalpojumi/pasutit" method="POST" role="form">
                            <div class="form-group">
                                <label for="service">Pakalpojums</label>
                                <select name="service" class='form-control'>
                                    <option value='-1' selected='true' disabled='disabled'>Izvēlies pakalpojumu</option>
                                    <?php foreach($services as $service) { ?>
                                    <option value="<?php echo $service->id; ?>"><?php echo $service->name . ' - ' . $service->description; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="object">Objekts</label>
                                <select name="object" class='form-control'>
                                    <option value='-1' selected='true' disabled='disabled'>Izvēlies objektu</option>
                                    <?php foreach($objects as $object) { ?>
                                    <option value="<?php echo $object->object_id; ?>"><?php echo $object->object_name . ' - ' . $object->object_addr; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="date-pick form-group">
                                <label for="date_from">Datums no</label>
                                <div class="input-group date">
                                  <input name='date_from' type="text" class="form-control" placeholder='dd.mm.gggg'><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                                </div>
                            </div>
                            <div class="date-pick form-group">
                                <label for="date_to">Datums līdz</label>
                                <div class="input-group date">
                                  <input name='date_to' type="text" class="form-control" placeholder='dd.mm.gggg'><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                                </div>
                            </div>    

                            <div class="form-group">
                                <label for="notes">Piezīmes</label>
                                <textarea name="notes" class="form-control" id="notes" placeholder="Papildus komentāri"></textarea>
                            </div>    

                            <input type="hidden" name="<?php echo \Config::get('security.csrf_token_key');?>" value="<?php echo \Security::fetch_token();?>" />

                            <div class="form-group">
                                <button type="submit" class="btn btn-block btn-primary">Pasūtīt</button>
                            </div>
                          </form>
                      <?php } else { ?>
                            <p>Diemžēl šobrīd sistēmā nav pievienots neviens pakalpojums, tādēļ nav iespējams pasūtīt pakalpojumus!</p>
                            <p>Lūdzam šī jautājuma sakarā sazināties ar SIA "Liepājas ūdens" darbiniekiem</p>
                      <?php } ?>
                    </div>

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
    
    $('#request_form').validate({
        rules: {
            client_number: {
                required: true,
                minlength: 8,
                maxlength:8
            },
            email: {
                required: true,
                email: true
            },
            password: {
                required: true,
                minlength: 5,
                complex: true
            },
            secpassword: {
                equalTo: "#password"
            },
        },
        
        messages: {
            client_number: {
                required: "Lūdzu ievadiet savu klienta numuru!",
                minlength: "Klienta numuram jāsastāv tieši no 8 simboliem!",
                maxlength: "Klienta numuram jāsastāv tieši no 8 simboliem!"
            },
            email: "Lūdzu ievadiet korektu e-pasta adresi!",
            password: {
                required: "Lūdzu ievadiet paroli!",
                minlength: "Parolei ir jābūt vismaz 5 simbolu garai!",
                complex: "Parolei jāsatur vismaz viens burts un viens cipars!"
            },
            secpassword: {
                equalTo: "Parolēm jābūt vienādām!"
            },
        },
            submitHandler: function(form) {
                form.submit();
            }
    });    
});    
</script>
                    
    </div>
</div>

<script>
    $(document).ready(function() {
    
    });
</script>