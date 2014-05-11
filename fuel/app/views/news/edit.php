<div class='container'>
    <div class="row">
        <div class="col-md-5">
            <h1>Labot ziņu</h1>
            <hr/>
            <a href="/darbinieks/jaunumi/skatit/<?php echo $news -> id; ?>" alt="atpakaļ">Doties atpakaļ</a>
        </div>
    </div>
    <div class='row main-block'>
        <?php echo render('news/_form'); ?>
    </div>
    
</div>