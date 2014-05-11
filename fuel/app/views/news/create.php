<div class="container">
    <div class="row">
        <div class="col-md-5">
            <h1>Pievienot jaunumus</h1>
            <hr/>
            <a href="/darbinieks/jaunumi" alt="atpakaļ">Doties atpakaļ</a>
        </div>
    </div>
    <div class='row main-block'>
        <?php if(Session::get_flash('error')) { ?>
            <div class="alert alert-danger">
                <p>Kļūda!<?php echo Session::get_flash('error'); ?></p>
            </div>
        <?php } ?>
        <?php if(Session::get_flash('success')) { ?>
            <div class="alert alert-success">
                <p><?php echo Session::get_flash('success'); ?></p>
            </div>
        <?php } ?>
        
        <?php echo render('news/_form'); ?>
        
    </div>