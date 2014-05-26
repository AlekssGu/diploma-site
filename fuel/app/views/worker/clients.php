<link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>
<script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
<div class="container">
    <div class="row">
        <div class="col-md-5">
            <h1>Klientu pārvaldība</h1>
            <hr/>
            <a href="/" alt="atpakaļ">Doties atpakaļ</a>
        </div>
    </div>

    <div class='row main-block'>
        <a href='/darbinieks/pakalpojumi' class='btn btn-primary'>Pakalpojumu pārvaldība</a>
    </div>
    
    <div class="row">    
        <?php if(!empty($clients)) { ?>
            <div class="col-sm-12 col-xs-12 col-md-3 sidebar-offcanvas" id="sidebar" role="navigation" style="float:left">
            <h4>Klientu saraksts</h4>
                <div class="sidebar-nav">
                    <ul class="nav side-nav">
                        <?php foreach ($clients as $number => $client) { ?>
                          <li class="active"><a href='#' data='<?php echo $client -> user_id; ?>'><?php echo $client->name . ' ' . $client->surname . ' (' . $client->client_number . ')'; ?></a></li>
                        <?php } ?>
                    </ul>
                </div><!--/.well -->            
            </div>
            <div class="clearfix hidden-lg hidden-md"></div>
            <div class='col-md-9' id='client_data'></div>
        <?php } else { ?>
            <p class='text-center'>Pašlaik sistēmā nav reģistrējies neviens lietotājs</p>
        <?php } ?>
    </div>
</div><!--/.container -->

<!-- objekta informacija -->
<?php // { ?>
<div id='obj_info' class="modal fade">

</div><!-- /.modal -->
<?php //} ?>
<!-- /objekta informacija -->

<script>
    $(document).ready(function() {
        //Ielādē sarakstā pirmā klienta datus
        $( "#client_data" ).load("/ieladet-datus/" + $('.side-nav li a').first().attr('data'));

        //Ielādē izsauktā klienta datus
        $('.side-nav li a').click(function(e){
            e.preventDefault();
           $( "#client_data" ).load("/ieladet-datus/" + $(this).attr('data'));
        });
        
        $('body').on('hidden.bs.modal', '.modal', function () {
          $(this).removeData('bs.modal');
        });
    });
</script>

