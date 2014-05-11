<div class="container">
    <div class="row">
        <div class="col-md-5">
            <h1>Paziņot par bojājumu</h1>
            <hr/>
            <a href="/" alt="atpakaļ">Doties atpakaļ</a>
        </div>
    </div>
    
    <div class="row main-block">
        <div class='col-md-6'>
                        <?php if(Session::get_flash('success')) { ?>
                                <div class="alert alert-success">
                                    <p class="text-success"><?php echo Session::get_flash('success'); ?></p>
                                </div>
                            <div class="clearfix"></div>

                        <?php } elseif(Session::get_flash('error')) { ?>
                                <div class="alert alert-danger">
                                    <p class="text-danger"><?php echo Session::get_flash('error'); ?></p>
                                </div>
                            <div class="clearfix"></div>
                        <?php } ?>
                        
                        <!-- galvenās lapas forma -->
                        <form id="request_form" action="/pazinot-par-bojajumu" method="POST" role="form">
                        <div class="date-pick form-group">
                            <label for="address">Adrese, kur atrodas objekts</label>
                            <input name='address' id="address" class='form-control' type="text" placeholder='Kur konstatēta avārija?'/>
                        </div>
                            
                        <div class="form-group">
                            <label for="notes">Piezīmes</label>
                            <textarea name="notes" class="form-control" id="notes" placeholder="Papildus komentāri"></textarea>
                        </div>    
                            
                        <input type="hidden" name="<?php echo \Config::get('security.csrf_token_key');?>" value="<?php echo \Security::fetch_token();?>" />
                        <input id='latitude' type='hidden' name='latitude' />
                        <input id='longitude' type='hidden' name='longitude' />
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-block btn-primary">Paziņot</button>
                        </div>
                      </form>
        </div>
        <div class='col-md-6'>
            <div style="height:400px; width:100%" id="map_canvas"></div>
        </div>
    </div>

<script type="text/javascript" src="http://maps.google.com/maps/api/js?libraries=places&sensor=false"></script>
<script>
    $(document).ready(function(){
      var geocoder;
      var map;
      var marker;
      
        function initialize() {
          geocoder = new google.maps.Geocoder();
          var latlng = new google.maps.LatLng(-34.397, 150.644);
          var mapOptions = {
            zoom: 8,
            center: latlng
          }
          map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
        }

        function codeAddress() {
          var address = document.getElementById("address").value;
          geocoder.geocode( { 'address': address}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
              if(marker) marker.setMap(null);
              map.setCenter(results[0].geometry.location);
              
                var contentString = '<p>' + $('#notes').val() + '</p>';

                var infowindow = new google.maps.InfoWindow({
                    content: contentString
                });
              
                  marker = new google.maps.Marker({
                  map: map,
                  draggable: true,
                  position: results[0].geometry.location,
                  title: 'Būvdarbi',
              });
              
                $('#latitude').attr('value',results[0].geometry.location.k);
                $('#longitude').attr('value',results[0].geometry.location.A);
              
            google.maps.event.addListener(marker, "dragend", function(event) {
                var point = marker.getPosition();
                map.panTo(point);
                });

            google.maps.event.addListener(marker, 'click', function() {
              infowindow.open(map,marker);
            });
            }
          });
        }
  
  initialize();

    $('#address, #notes').on('blur', codeAddress);

    });
</script>