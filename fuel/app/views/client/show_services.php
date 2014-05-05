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
            <a href="/abonents/objekti/apskatit/<?php echo $object[0]->object_id; ?>" alt="atpakaļ">Doties atpakaļ</a>
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
            <?php foreach ($meters as $meter) { ?>

            <ul class='list-group'>
                <li class="list-group-item"><strong>Skaitītāja numurs:</strong> <?php echo $meter->meter_number; ?></li>
                <li class="list-group-item">Termiņš: no <?php echo date_format(date_create($meter->date_from), 'd.m.Y') . ' līdz ' . date_format(date_create($meter->date_to), 'd.m.Y'); ?></li>
                <li class="list-group-item">Rādījums uzstādīšanas brīdī: <?php echo $meter->meter_lead; ?></li>
            </ul>

            <?php } ?>
        <?php } else { ?>
            <p>Nav pievienots neviens skaitītājs</p>
        <?php } ?>
        </div>
    </div>
    
</div>