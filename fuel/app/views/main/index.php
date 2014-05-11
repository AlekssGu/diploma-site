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
                        <h1 class="text-default">LIEPĀJAS ŪDENS</h1>
                        <p>Sabiedrība ar ierobežotu atbildību</p>
                    </div>
                </div>
                <div class="item">
                    <div class="slider-image" style="background-image:url(/assets/img/image2.jpg)"></div>
                    <div class="carousel-caption">
                        <h1 class="text-default">Komposta pārstrādes process</h1>
                        <p>Interesē šāds pakalpojums? Dodies uz mūsu <a style='color:#fff' href='/pakalpojumi'>pakalpojumu sadaļu!</a></p>
                    </div>
                </div>
                <div class="item">
                    <div class="slider-image" style="background-image:url(/assets/img/image3.jpg)"></div>
                    <div class="carousel-caption">
                        <h1 class="text-default">Ūdens ir dzīvība</h1>
                        <p>Vai tu zināji, ka, zaudējot 1% ķermeņa ūdens daudzuma, tev sākas slāpes?</p>
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

              <!-- galvenās lapas 4 bloki -->
              <div class="col-md-3">
                <div class="thumbnail">
                  <div class="caption">
                        <h3>Kas mēs esam?</h3>
                        <p>IS Pilsētas ūdens galvenais mērķis ir ūdens apgāde un notekūdeņu attīrīšana.</p>
                        <p><a href="/par-uznemumu/rekviziti" class="btn btn-primary" role="button">Uzzināt vairāk</a></p>
                  </div>
                </div>
              </div>

              <div class="col-md-3">
                <div class="thumbnail">
                  <div class="caption">
                        <h3>Kā uzsākt darbu?</h3>
                        <p>Vienkārši! Ievadi savus klienta datus, saņem e-pastu un lieto sistēmu. 5 minūtes un gatavs!</p>
                        <a href="#" class="start-more hidden-xs hidden-sm btn btn-block btn-success" role="button">Sākt tagad</a>
                        <a href="#" class="start-more hidden-lg hidden-md btn btn-success" role="button">Sākt tagad</a>
                  </div>
                </div>
              </div>

              <div class="col-md-3">
                <div class="thumbnail">
                  <div class="caption">
                        <h3>Mūsu klienti</h3>
                        <p>Mūsu klienti iegūst daudz vairāk brīvā laika, lietojot šo sistēmu. Pievienojies viņiem arī tu!</p>
                        <a href="/abonents/pieslegties" class="hidden-xs hidden-sm btn btn-primary btn-block" role="button">Ieiet sistēmā</a>
                        <a href="/abonents/pieslegties" class="hidden-lg hidden-md btn btn-primary" role="button">Ieiet sistēmā</a>
                  </div>
                </div>
              </div>

              <div class="col-md-3">
                <div class="thumbnail">
                  <div class="caption">
                        <h3>Ko mēs piedāvājam?</h3>
                        <p>Ievadi skaitītāja mērījumus, seko līdzi saviem rēķiniem, apskati plānotos remontus un paziņo par bojājumu pats.</p>
                        <p><a href="/pakalpojumi" class="btn btn-primary" role="button">Uzzināt vairāk</a></p>
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
                    <form id="registration_form" action="/abonents/registreties" method="POST" role="form">
                    <div class="form-group">
                        <label for="client_number">Klienta numurs</label>
                        <input type="text" name="client_number" class="form-control" id="client_number" placeholder="Ieraksti savu klienta numuru">
                    </div>
                    <div class="form-group">
                        <label for="email">E-pasts</label>
                        <input type="email" name="email" class="form-control" id="email" placeholder="Ieraksti savu e-pastu">
                    </div>
                    <div class="form-group">
                        <label for="password">Parole</label>
                        <input type="password" name="password" class="form-control" id="password" placeholder="Ieraksti savu paroli">
                    </div>
                    <div class="form-group">
                        <label for="password">Atkārtota parole</label>
                        <input type="password" name="secpassword" class="form-control" id="secpassword" placeholder="Atkārtoti ieraksti savu paroli">
                    </div>
                    <div class="checkbox">
                      <label>
                        <input name="messages" checked="true" value='Y' type="checkbox"> Vēlos saņemt paziņojumus no sistēmas administrācijas
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
                    <iframe width="560" height="315" src="//www.youtube.com/embed/gyA2L3Tl6h4" frameborder="0" allowfullscreen></iframe>
                </div>
              <!-- /galvenās lapas forma -->
            </div>
        </div>

        <!-- galvenās lapas skripts -->
        <script>
        $('.start-more').click(function(){      
            $('body, html').animate({
                "scrollTop": $("#start-now").offset().top - 50
            }, 800, "swing"); // animācijas laiks un beigu kustība
            return false; // prevent default
        });

        // Aktivizē slīdrādi 
        $('#slider').carousel({
            interval: 5000 // ātrums
        })

$(document).ready(function() {
    
    jQuery.validator.addMethod("complex", function(element) {
                    var has_char = false;
                    var has_num = false;
                    
                    /*contains characters*/
                    if(element.match(/[a-zA-Z+]+/) ) {
                            has_char = true;
                    }
                    /*contains digits*/
                    if(element.match(/[0-9]+/)) {
                            has_num = true;
                    }
                    if(has_char && has_num) return true;
                    else return false;

}, "Parolei jāsatur vismaz viens burts un viens cipars!");
    
    $('#registration_form').validate({
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
        <!-- /Galvenās lapas skripts -->