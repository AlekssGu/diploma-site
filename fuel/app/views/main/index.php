        

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
          <div class="col-sm-6 col-md-3">
            <div class="thumbnail">
              <div class="caption">
                    <h3>Kas mēs esam?</h3>
                    <p>Īsumā par mums</p>
                    <p><a href="#" class="btn btn-primary" role="button">Uzzināt vairāk</a></p>
              </div>
            </div>
          </div>
          
          <div class="col-sm-6 col-md-3">
            <div class="thumbnail">
              <div class="caption">
                    <h3>Kā uzsākt darbu?</h3>
                    <p>Īsumā, dažos soļos, kā uzsākt darbu sistēmā</p>
                    <a id="start-more" href="#" class="btn btn-block btn-success" role="button">Sākt tagad</a>
              </div>
            </div>
          </div>
          
          <div class="col-sm-6 col-md-3">
            <div class="thumbnail">
              <div class="caption">
                    <h3>Mūsu klienti</h3>
                    <p>Īsumā par to, kas pieejams klientiem un kāpēc ir vērts būt klientam</p>
                    <a id="login-more" href="#" class="btn btn-block btn-warning" role="button">Ieiet sistēmā</a>
              </div>
            </div>
          </div>
          
          <div class="col-sm-6 col-md-3">
            <div class="thumbnail">
              <div class="caption">
                    <h3>Ko mēs piedāvājam?</h3>
                    <p>Īsumā par to, ko piedāvājam</p>
                    <p><a href="#" class="btn btn-primary" role="button">Uzzināt vairāk</a></p>
              </div>
            </div>
          </div>
          <!-- /galvenās lapas 4 bloki -->
        </div>
        <!-- pirmā rinda -->
       
        <div class="row landing-row">
            <div id="start-now" class="col-md-6">
                <div class="page-header text-center">
                    <h1>Ievadi savus datus <small><br/>un lieto sistēmu jau 5 minūšu laikā!</small></h1>
                </div>

                <!-- galvenās lapas forma -->
                <form action="/user/register" method="POST" role="form">
                <div class="form-group">
                    <label for="client_number">Klienta numurs</label>
                    <input type="text" class="form-control" id="client_number" autofocus="true" placeholder="Ieraksti savu klienta numuru">
                </div>
                <div class="form-group">
                    <label for="email">E-pasts</label>
                    <input type="email" class="form-control" id="email" placeholder="Ieraksti savu e-pastu">
                </div>
                <div class="form-group">
                    <label for="password">Parole</label>
                    <input type="password" class="form-control" id="password" placeholder="Ieraksti savu paroli">
                </div>
                <div class="form-group">
                    <label for="password">Atkārtota parole</label>
                    <input type="password" class="form-control" id="secpassword" placeholder="Atkārtoti ieraksti savu paroli">
                </div>
                <div class="checkbox">
                  <label>
                    <input checked="true" type="checkbox"> Vēlos saņemt paziņojumus no sistēmas administrācijas
                  </label>
                </div>
                <div class="form-group">
                    <input type="hidden" name="<?php echo \Config::get('security.csrf_token_key');?>" value="<?php echo \Security::fetch_token();?>" />
                    <button type="submit" class="btn btn-block btn-primary">Reģistrēties</button>
                </div>
                </form>
            </div>
            
            <div class="visible-lg visible-md col-md-6">
                <div class="page-header text-center">
                    <h1>Īsa pamācība <small><br/>parādīs tev svarīgākās sistēmas funkcijas</small></h1>
                </div>
                <iframe style="margin-top:40px" width="560" height="315" src="//www.youtube.com/embed/-Wn3fgh-sNs" frameborder="0" allowfullscreen></iframe>
            </div>
          <!-- /galvenās lapas forma -->
        </div>
        
        <div class="row landing-row">

                <div id="login-start" class="page-header text-center">
                    <h1>Ieiet sistēmā</h1>
                </div>
                <div class="col-md-offset-3 col-md-6">
                <!-- galvenās lapas forma -->
                <form id="login-form" role="form">
                <div class="form-group">
                    <label for="login">E-pasts vai klienta numurs</label>
                    <input type="text" class="form-control" id="login" placeholder="Ieraksti savu e-pastu vai klienta numuru">
                </div>
                <div class="form-group">
                    <label for="password">Parole</label>
                    <input type="password" class="form-control" id="password" placeholder="Ieraksti savu paroli">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-block btn-default">Ieiet</button>
                    <a href="#" class="btn btn-link">Aizmirsu paroli</a> 
                </div>
                <input type="hidden" name="<?php echo \Config::get('security.csrf_token_key');?>" value="<?php echo \Security::fetch_token();?>" />
              </form>
                </div>
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

$('#login-more').click(function(){
    $(document.body).animate({
        "scrollTop": $("#login-start").offset().top
    }, 800, "swing"); // animācijas laiks un beigu kustība
    return false; // prevent default
});
</script>
<!-- /Galvenās lapas skripts -->