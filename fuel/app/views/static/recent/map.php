<div class="container">
    <div class="row">
        <div class="col-md-6">
          <h1>Aktuālo notikumu karte</h1> 
          <hr/>  
        </div>
    </div>
    <!-- kartes bloks -->
    <div class="row">
        <div class="col-md-6">
            <div style="height:400px; width:500px" id="map_canvas"></div>
        </div>
        <div class="col-md-6 text-justify">
            <h4>Apzīmējumi:</h4>
            <ul>
                <li>XX - brīvkrāns</li>
                <li>YY - ūdensvada būvdarbi</li>
                <li>ZZ - kanalizācijas būvdarbi</li>
            </ul>
            <br/><br/>
            <p>Zini kaut ko tādu, par ko neesam informēti?</p>
            <a href="#" class="btn btn-primary" alt="Ziņot par bojājumu">Ziņot par bojājumu</a>
        </div>
    </div>
    <!-- kartes bloks beidzas -->
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