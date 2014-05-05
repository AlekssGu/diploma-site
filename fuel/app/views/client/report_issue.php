<?php echo Asset::js('bootstrap-datepicker.js'); ?>
<?php echo Asset::css('datepicker3.css'); ?>
<div class="container">
    <div class="row">
        <div class="col-md-5">
            <h1>Paziņot par bojājumu</h1>
            <hr/>
            <a href="/" alt="atpakaļ">Doties atpakaļ</a>
        </div>
    </div>
    

    <div class="row main-block">
        <div style="height:600px; width:100%" id="map_canvas"></div>
    </div>

<script src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script>
    $(document).ready(function(){

  var map;
  function initialize() {
    var map_canvas = document.getElementById('map_canvas');
    var map_options = {
      center: new google.maps.LatLng(56.5369455, 21.0384852),
      zoom: 12,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    map = new google.maps.Map(map_canvas, map_options);
  }
  google.maps.event.addDomListener(window, 'load', initialize);

    });
</script>