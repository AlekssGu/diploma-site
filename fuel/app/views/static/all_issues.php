<div class='container'>
    <div class='row'>
        <h3 class='text-center'>Remontdarbi un avārijas</h3>
        <a href='/pazinot-par-bojajumu' class='btn btn-default'>Paziņot par bojājumu</a>
        <hr/>
        <div style="height:500px; width:100%" id="map_canvas"></div>
    </div>
</div>
                
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