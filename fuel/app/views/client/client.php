
<div class="container">
    <div class="row">
        <div class="col-md-5">
            <h1>Mana abonenta informācija</h1>
            <hr/>
            <a href="/" alt="atpakaļ">Doties atpakaļ</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <Br/>
            <div class="well well-lg">
                <p><strong>Abonenta numurs:</strong> <?php echo $client_number; ?></p>
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
            <h3>Mana abonenta vēsture</h3>
            <?php if(!empty($history)) { 
                   foreach ($history as $record) { ?>
                      <p><?php echo Date::forge($record->created_at)->format('%d.%m.%Y %H:%M') . ' ' . $record -> notes; ?></p>
                   <?php } ?>
            <?php } else echo '<p>Pagaidām nav informācijas par abonenta vēsturi</p>'; ?>
        </div>
    </div>
</div>