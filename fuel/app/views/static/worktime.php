<div class="container">
    <div class="row">
        <div class="col-md-6">
          <h1>Uzņēmuma darba laiks</h1> 
          <hr/>  
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
                <dl>
                    <dt>Uzņēmuma darba laiks:</dt>
                    <dd>P.O.T.C. 08.00 – 12.00; 12.30 - 17.00;</dd>
                    <dd>Piektdien no 08.00 – 14.00</dd>
                    <dd>Telefons: 63423417</dd>
                    <br/>
                    <dt>Abonentu daļa un kase:</dt>
                    <dd>P.O.T.C. 08.00 – 17.00;</dd>
                    <dd>Piektdien no 08.00 – 14.00</dd>
                    <dd>Atrodas K. Valdemāra ielā 12 <a href='#' class='show-vald'>[skatīt kartē]</a></dd>
                    <dd>Telefons: 63484962</dd>
                    <br/>
                    <dt>Tehniskā daļa:</dt>
                    <dd>O, C: 9.00 - 11.00</dd>
                    <dd>T: 9.00 - 11.30</dd>
                    <dd>Atrodas K. Valdemāra ielā 12 <a href='#' class='show-vald'>[skatīt kartē]</a></dd>
                    <dd>Telefons: 63422318</dd>
                    <br/>
                    <dt>Laboratorija:</dt>
                    <dd>P.O.T.C. 08.00 - 12.00; 12.30 - 17.00</dd>
                    <dd>Piektdien no 08.00 – 14.00</dd>
                    <dd>Atrodas Ventas ielā 11/17 <a href='#' class='show-vent'>[skatīt kartē]</a></dd>
                    <dd>Telefons: 63423911</dd>
                </dl>
        </div>
        <div class='col-md-6'>
            <div style="height:500px; width:100%" id="map_canvas"></div>
        </div>
    </div>
</div>

<script src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script>
$(document).ready(function(){
            
      var map;
      var contentString;
      var infoWindow = null;
      var marker1;
      var marker2;
      
      function initialize() {
        var map_canvas = document.getElementById('map_canvas');
        var map_options = {
          center: new google.maps.LatLng(56.49897,21.0069),
          zoom: 13,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        map = new google.maps.Map(map_canvas, map_options);
        
           marker1 = new google.maps.Marker({
           map: map,
           draggable: false,
           position: new google.maps.LatLng(56.5074878,21.0067185),
           title: "K. Valdemāra iela 12"
           });
           
            marker1.info = new google.maps.InfoWindow({
              content: '<p>K. Valdemāra iela 12</p>'
            });
        
            google.maps.event.addListener(marker1, 'click', function() {
              marker2.info.close(map,marker2);
              marker1.info.open(map, marker1);
            });   
            
           marker2 = new google.maps.Marker({
           map: map,
           draggable: false,
           position: new google.maps.LatLng(56.4883344,21.0105353),
           title: "Ventas iela 11/17"
           });
           
            marker2.info = new google.maps.InfoWindow({
              content: '<p>Ventas iela 11/17</p>'
            });
        
            google.maps.event.addListener(marker2, 'click', function() {
              marker1.info.close(map,marker1);
              marker2.info.open(map, marker2);
            });    
      }
      
      
      google.maps.event.addDomListener(window, 'load', initialize);
      
            $('.show-vald').click(function(e){
                e.preventDefault();
                map.setZoom(15);
                map.setCenter(marker1.getPosition());
                marker2.info.close(map,marker2);
                marker1.info.open(map, marker1);    
            });
            
            $('.show-vent').click(function(e){
                e.preventDefault();
                map.setZoom(15);
                map.setCenter(marker2.getPosition());
                marker1.info.close(map,marker1);
                marker2.info.open(map, marker2);    
            });
 });
</script>