        

        <!-- Galvenās lapas saturs -->
        <div id="slider" class="carousel slide">
            
        <!-- Indikatori (apaļie) -->
        <ol class="carousel-indicators">
            <li data-target="#slider" data-slide-to="0" class="active"></li>
            <li data-target="#slider" data-slide-to="1"></li>
            <li data-target="#slider" data-slide-to="2"></li>
        </ol>

        <!-- Slaidu ietvars -->
        <div class="carousel-inner">
            <div class="item active">
                <div class="slider-image" style="background-image:url(/assets/img/image1.jpg)"></div>
                <div class="carousel-caption">
                    <h1 class="text-primary">Pilsētas ūdens</h1>
                </div>
            </div>
            <div class="item">
                <div class="slider-image" style="background-image:url(/assets/img/image2.jpg)"></div>
                <div class="carousel-caption">
                    <h1 class="text-primary">Ūdens ir vitāla mūsu dzīves sastāvdaļa</h1>
                </div>
            </div>
            <div class="item">
                <div class="slider-image" style="background-image:url(/assets/img/image3.jpg)"></div>
                <div class="carousel-caption">
                    <h1 class="text-primary">Ūdens ir dzīvība</h1>
                </div>
            </div>
        </div>

        <!-- Pogas (uz priekšu/uz atpakaļu) -->
        <a class="left carousel-control" href="#slider" data-slide="prev">
            <span class="icon-prev"></span>
        </a>
        <a class="right carousel-control" href="#slider" data-slide="next">
            <span class="icon-next"></span>
        </a>
    </div>

    <div class="container">
        
        <!-- Sistēmas iespējas     
        <li>Sazināties ar uzņēmumu</li>
        <li>Ievadīt skaitītāja mērījumus</li>
        <li>Apskatīt mērījumu vēsturi</li>
        <li>Uzdot jautājumus uzņēmuma vadībai</li>
        <li>Uzzināt aktuālāko informāciju par būvdarbiem</li>
        <li>Ziņot par problēmām un avārijām</li> 
        -->
        
        <div id="first-row" class="row">
            
          <h3 class="text-center lead">Uzzini par mums!</h3>
          
          <!-- galvenās lapas 3 bloki -->
          <div class="col-sm-6 col-md-4">
            <div class="thumbnail">
              <div class="caption">
                    <h3>Kas mēs esam?</h3>
                    <p>Īsumā par mums</p>
                    <p><a href="#" class="btn btn-primary" role="button">Uzzināt vairāk</a></p>
              </div>
            </div>
          </div>
          
          <div class="col-sm-6 col-md-4">
            <div class="thumbnail">
              <div class="caption">
                    <h3>Kā uzsākt darbu?</h3>
                    <p>Īsumā, dažos soļos, kā uzsākt darbu sistēmā</p>
                    <p><a id="start-more" href="#" class="btn-lg btn btn-block btn-success" role="button">Sākt tagad!</a></p>
              </div>
            </div>
          </div>
          
          <div class="col-sm-6 col-md-4">
            <div class="thumbnail">
              <div class="caption">
                    <h3>Ko mēs piedāvājam?</h3>
                    <p>Īsumā par to, ko piedāvājam</p>
                    <p><a href="#" class="btn btn-primary" role="button">Uzzināt vairāk</a></p>
              </div>
            </div>
          </div>
          <!-- /galvenās lapas 3 bloki -->
        </div>
        <!-- pirmā rinda -->
       
        <div class="row">
            <div class="page-header text-center">
                <h1>Ievadi savus datus <small>un lieto sistēmu jau 5 minūšu laikā!</small></h1>
            </div>
            
            <!-- galvenās lapas forma -->
            <form id="start-now" class="form-horizontal" role="form">
            <div class="form-group">
              <label for="username" class="col-sm-3 control-label">Lietotājvārds (vai e-pasts)</label>
              <div class="col-sm-9">
                  <input type="text" class="form-control" id="username" autofocus="true" placeholder="Ierakstu savu lietotājvārdu">
              </div>
            </div>
            <div class="form-group">
              <label for="password" class="col-sm-3 control-label">Parole</label>
              <div class="col-sm-9">
                <input type="password" class="form-control" id="password" placeholder="Ieraksti savu paroli">
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-offset-3 col-sm-9">
                <a href="#" class="btn btn-primary">Reģistrēties</a>
                <button type="submit" class="btn btn-default">Ieiet</button>
                <a href="#" class="btn btn-link">Aizmirsu paroli</a>
              </div>
            </div>
          </form>
          <!-- /galvenās lapas forma -->
        </div>
        
    </div>
        
<!-- galvenās lapas skripts -->
<script>
$('#start-more').click(function(){
    $(document.body).animate({
        "scrollTop": $("#start-now").offset().top
    }, 800, "swing"); // animācijas laiks un beigu kustība
    return false; // prevent default
});
</script>
<!-- /Galvenās lapas skripts -->