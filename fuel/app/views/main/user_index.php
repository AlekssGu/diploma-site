<?php echo Asset::js('highcharts.js'); ?>
<?php echo Asset::js('exporting.js'); ?>
<script>
    <?php if(!empty($all_readings)) { ?>
    $(function () {
        $('#container').highcharts({
            title: {
                text: 'Ūdens patēriņš',
                x: -20 //center
            },
            subtitle: {
                text: 'Klienta numurs: <?php echo Auth::get('username'); ?>',
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
                name: 'Ūdens daudzums',
                data: [<?php foreach ($all_readings as $reading) { echo $reading->lead . ','; } ?>]
            }]
        });
    });
    <?php } ?>
    
</script>
            <div class="container">
                <div class="main-block row">
                    <div class="col-md-3">
                        <a href="/klients" class="btn btn-block btn-primary" title="Apskatīt klienta datus"><span class="glyphicon glyphicon-user"></span> Klienta informācija</a>
                    </div>
                    <div class="col-md-3">
                        <a href="/pazinot-par-bojajumu" class="btn btn-block btn-primary" title="Paziņot par bojājumu"><span class="glyphicon glyphicon-earphone"></span> Paziņot par bojājumu</a>
                    </div>
                    <div class="col-md-3">
                        <a href="/klients/pakalpojumi/pasutit" class="btn btn-block btn-primary" title="Pasūtīt pakalpojumu"><span class="glyphicon glyphicon-leaf"></span> Pasūtīt pakalpojumu</a>
                    </div>
                    <div class="col-md-3">
                        <a href="/palidziba/sazinaties" class="btn btn-block btn-primary" title="Uzdot jautājumu"><span class="glyphicon glyphicon-question-sign"></span> Uzdot jautājumu</a>
                    </div>
                </div>
                
                <div class="row main-block">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class='col-md-6'>
                                    <h3 class="text-center">Mana pēdējā aktivitāte</h3>
                                    <hr/>
                                    <?php if(!empty($last_reading) || !empty($service)) { ?>
                                        <?php if(!empty($last_reading)) { ?>
                                        <h4>Iesniegtais mērījums:</h4>
                                        <p>Pēdējais mērījums: <?php echo $last_reading[0]->lead; ?> (<?php echo $last_reading[0]->amount_since_last; ?>m<span style="vertical-align:super; font-size:0.7em;">3</span>)</p>
                                        <?php } ?>
                                        <?php if(!empty($service)) { ?>
                                        <h4>Pēdējais pakalpojuma pieprasījums:</h4>
                                        <p><?php if($service[0]->service_requested != '') 
                                                      echo 'Pieslēgt pakalpojumu "' . $service[0] -> service_requested . '"'; 
                                                 else echo 'Atslēgt pakalpojumu "' . $service[0] -> service_dismissed . '"'; ?> (Sākot ar <?php echo date_format(date_create($service[0] -> date_from),'d.m.Y'); ?>)
                                        </p>
                                        <br/>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <p>Pašlaik sistēmā nav reģistrēts nekas par tevi, bet drīzumā šeit parādīsies informācija par pēdējām tavām darbībām</p>
                                    <?php } ?>
                                </div>
                                <div class='col-md-6'>
                                    <h3 class='text-center'>Remontdarbi un avārijas</h3>
                                    <hr/>
                                    <div style="height:200px; width:500px" id="map_canvas"></div>
                                </div>
                            </div>
                        </div>
                        <div class='text-center'>
                        <a href="/klients/ievadit-merijumus" class="btn btn-success btn-lg btn-block">Ievadīt mērījumus</a>
                        </div>
                    </div>
                </div>
                
                <?php if(!empty($all_readings)) { ?>
                <div class='row main-block'>
                    <div class="col-md-12">
                        <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                    </div>
                </div>
                <?php } ?>
                
<script src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script>
$(document).ready(function(){
            
      var map;
      var contentString;
      var infoWindow = null;
      
      function initialize() {
        var map_canvas = document.getElementById('map_canvas');
        var map_options = {
          center: new google.maps.LatLng(56.5369455, 21.0384852),
          zoom: 10,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        map = new google.maps.Map(map_canvas, map_options);
        
        <?php foreach($emergencies as $emr) { ?>
           marker = new google.maps.Marker({
           map: map,
           draggable: false,
           position: new google.maps.LatLng(<?php echo $emr->lat; ?>, <?php echo $emr -> lon; ?>),
           title: "Būvdarbi"
           });
           
            marker.info = new google.maps.InfoWindow({
              content: '<p><?php echo $emr -> notes; ?></p>'
            });
        
            google.maps.event.addListener(marker, 'click', function() {
              marker.info.open(map, marker);
            });

        <?php } ?>      
      }
      
      google.maps.event.addDomListener(window, 'load', initialize);
 });
</script>