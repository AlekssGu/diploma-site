<link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>
<script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
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
                <p><strong>E-pasts:</strong> <a href="#" id='email' class="myeditable editable editable-click editable-empty" data-emptytext='nav ievadīts' data-original-title="Labot e-pastu"><?php echo $email; ?></a></p>
                <p><strong>Tālr. nr.:</strong> <a href="#" id='phone' class="myeditable editable editable-click editable-empty" data-emptytext='nav ievadīts' data-original-title="Labot telefona numuru"><?php echo $phone; ?></a></p>
                <p><strong>Faktiskā adrese:</strong> <?php echo $secondary_address; ?></p>
                <p><strong>Deklarētā adrese:</strong> <?php echo $primary_address; ?></p>
                <p><a class="btn btn-default btn-sm" href="/klients/mainit-datus">Mainīt paroli</a></p>
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
            
            <?php if(!empty($objects)) { ?>
            <ul class="list-group">
                <?php foreach ($objects as $object) { ?>
                <a href="/klients/objekti/apskatit/<?php echo $object->object_id; ?>" title="Spied šeit, lai apskatītu sīkāku informāciju" class="list-group-item"><?php echo $object->name; ?></a>
                <?php } ?>
            </ul>
            <a href="/klients/objekti" class="btn btn-default btn-large" title="Skatīt visus objektus"><span class="glyphicon glyphicon-list"></span> Skatīt visus objektus</a>
            <?php } else { ?>
                <p>Nav piesaistīts neviens objekts</p>
            <?php } ?>
        </div>
        <div class="col-md-4">
            <h3>Mana klienta vēsture</h3>
            <?php if(!empty($history)) { 
                   foreach ($history as $record) { ?>
                      <p><?php echo Date::forge($record->created_at)->format('%d.%m.%Y %H:%M') . ' ' . $record -> notes; ?></p>
                   <?php } ?>
            <?php } else echo '<p>Pagaidām nav informācijas par klienta vēsturi</p>'; ?>
        </div>
    </div>
</div>

<script>
 $(document).ready(function() {
    //Labo e-pastu ar ajax un x-editable palīdzību
    $('#email').editable({
        type: 'text',
        url: '/klients/mainit-datus',
        pk: '<?php echo $email; ?>',
        params: {
            action: 'email',
        },
        success: function(response) {
            if(!response) {
                return "E-pasts jau eksistē vai ir nepareizs formāts!";
            } 
        }  
    }); 
    
    //Labo telefona numuru ar ajax un x-editable palīdzību
    $('#phone').editable({
        type: 'text',
        url: '/klients/mainit-datus',
        pk: '<?php echo $phone; ?>',
        params: {
            action: 'phone',
        },
        success: function(response) {
            if(!response) {
                return "Telefona numurs netika mainīts!";
            } 
        }  
    }); 
 });
</script>