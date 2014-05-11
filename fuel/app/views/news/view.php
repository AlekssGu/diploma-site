<div class="container">
    <div class="row">
        <div class="col-md-5">
            <h2><?php echo $news->title; ?></h2>
            <hr/>
            <?php echo Html::anchor('/darbinieks/jaunumi', 'Doties atpakaļ'); ?> |
            <?php echo Html::anchor('/darbinieks/jaunumi/labot/'.$news->id, 'Labot'); ?>
            
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
        
        <?php if($news -> filename != '') { ?>
        <div class='row'>
            <div class='col-md-4'>
                <p><img class="img img-responsive" src="/assets/img/news/<?php echo $news -> filename_sys;?>"/></p>
            </div>
            <div class='col-md-8'>
        <p>
                <strong>Virsraksts:</strong>
                <?php echo $news->title; ?></p>
        <p>
                <strong>Attēls no vietnes:</strong>
                <?php echo $news->file_source; ?></p>
        <p>
                <strong>Statuss:</strong>
                <?php echo $news->status; ?></p>
        <p>
                <strong>Kopsavilkums:</strong>
                <?php echo $news->excerpt; ?></p>
        <p>
                <strong>Pilns teksts:</strong>
                <?php echo $news->body; ?></p>
            </div>
        </div>
        <?php } else { ?>
        <div class='row'>
            <div class='col-md-12'>
        <p>
                <strong>Virsraksts:</strong>
                <?php echo $news->title; ?></p>
        <p>
                <strong>Statuss:</strong>
                <?php echo $news->status; ?></p>
        <p>
                <strong>Kopsavilkums:</strong>
                <?php echo $news->excerpt; ?></p>
        <p>
                <strong>Pilns teksts:</strong>
                <?php echo $news->body; ?></p>
            </div>
        </div>
        <?php } ?>
    </div>
</div>

