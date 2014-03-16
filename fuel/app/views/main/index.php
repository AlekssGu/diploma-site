        <?php if(Auth::check()) { // Lietotāju grupas "Klients" sākumlapas bloks ?>  
<?php echo Asset::js('highcharts.js'); ?>
<?php echo Asset::js('exporting.js'); ?>
<script>
$(function () {
        $('#container').highcharts({
            title: {
                text: 'Ūdens patēriņš',
                x: -20 //center
            },
            subtitle: {
                text: 'Klienta numurs: 00900299',
                x: -20
            },
            xAxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mai', 'Jun',
                    'Jūl', 'Aug', 'Sep', 'Okt', 'Nov', 'Dec']
            },
            yAxis: {
                title: {
                    text: 'Patēriņš (m3)'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            credits: {
                enabled: false
            },
            tooltip: {
                
                valueSuffix: 'm3'
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            series: [{
                name: 'Ūdensapgāde',
                data: [7.0, 6.9, 9.5, 14.5, 18.2, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6]
            }, {
                name: 'Notekūdeņi',
                data: [-0.2, 0.8, 5.7, 11.3, 17.0, 22.0, 24.8, 24.1, 20.1, 14.1, 8.6, 2.5]
            }]
        });
    });
    
</script>
            <div style="margin-top:20px" class="container">
                <div class="row">
                    <div class="col-md-3">
                        <a href="#" class="btn btn-block btn-primary" title="Apskatīt klienta datus"><span class="glyphicon glyphicon-user"></span> Klienta informācija</a>
                    </div>
                    <div class="col-md-3">
                        <a href="#" class="btn btn-block btn-primary" title="Dati par patērētajiem resursiem"><span class="glyphicon glyphicon-tint"></span> Patēriņa dati</a>
                    </div>
                    <div class="col-md-3">
                        <a href="#" class="btn btn-block btn-primary" title="Saņemtie pakalpojumi"><span class="glyphicon glyphicon-leaf"></span> Pakalpojumi</a>
                    </div>
                    <div class="col-md-3">
                        <a href="#" class="btn btn-block btn-primary" title="Uzdot jautājumu"><span class="glyphicon glyphicon-question-sign"></span> Uzdot jautājumu</a>
                    </div>
                </div>
                <div class="row" style="margin-top:20px">
                    <div class="col-md-6">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <h3 class="text-center">Mana pēdējā aktivitāte</h3>
                                <hr/>
                                <h4>Iesniegtais mērījums:</h4>
                                <p>Pēdējais mērījums: 000413<span class="text-danger">45</span></p>
                                <h4>Veiktie maksājumi:</h4>
                                <p>Pēdējais maksājums: 13.03.2014</p>
                                <h4>Saņemtie pakalpojumi:</h4>
                                <p>Pēdējais pakalpojums: Nosēdbedres likvidācija</p>
                            </div>
                        </div>
                        <div class='text-center'>
                        <a href="#" class="btn btn-info">Ievadīt mērījumu</a>
                        <a href="#" class="btn btn-info">Paziņot par bojājumu</a>
                        <a href="#" class="btn btn-info">Pasūtīt pakalpojumus</a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                    </div>
                </div>
        <?php } else { // Lietotāju grupas "Viesis" sākumlapas bloks ?>

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
                        <h1 class="text-default">Pilsētas ūdens</h1>
                    </div>
                </div>
                <div class="item">
                    <div class="slider-image" style="background-image:url(/assets/img/image2.jpg)"></div>
                    <div class="carousel-caption">
                        <h1 class="text-default">Ūdens ir vitāla mūsu dzīves sastāvdaļa</h1>
                    </div>
                </div>
                <div class="item">
                    <div class="slider-image" style="background-image:url(/assets/img/image3.jpg)"></div>
                    <div class="carousel-caption">
                        <h1 class="text-default">Ūdens ir dzīvība</h1>
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
                        <p><a href="#" class="btn btn-primary" role="button">Uzzināt vairāk</a></p>
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
                        <a href="/user/login" class="hidden-xs hidden-sm btn btn-primary btn-block" role="button">Ieiet sistēmā</a>
                        <a href="/user/login" class="hidden-lg hidden-md btn btn-primary" role="button">Ieiet sistēmā</a>
                  </div>
                </div>
              </div>

              <div class="col-md-3">
                <div class="thumbnail">
                  <div class="caption">
                        <h3>Ko mēs piedāvājam?</h3>
                        <p>Ievadi skaitītāja mērījumus, seko līdzi saviem rēķiniem, apskati plānotos remontus un paziņo par bojājumu pats.</p>
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
                    <form id="registration_form" action="/user/register" method="POST" role="form">
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
                    <iframe style="margin-top:40px" width="560" height="315" src="//www.youtube.com/embed/-Wn3fgh-sNs" frameborder="0" allowfullscreen></iframe>
                </div>
              <!-- /galvenās lapas forma -->
            </div>
        </div>

        <!-- galvenās lapas skripts -->
        <script>
        $('.start-more').click(function(){
            $(document.body).animate({
                "scrollTop": $("#start-now").offset().top
            }, 800, "swing"); // animācijas laiks un beigu kustība
            return false; // prevent default
        });

        // Aktivizē slīdrādi 
        $('.slider').carousel({
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
    <?php } // lietotāju grupas "Viesis" bloks beidzas ?>