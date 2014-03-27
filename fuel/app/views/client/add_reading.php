<div class="container">
    <div class="row">
        <div class="col-md-5">
            <h1>Iesniegt skaitītāja rādījumu</h1>
            <hr/>
            <a href="/klients" alt="atpakaļ">Doties atpakaļ</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <form id="add_meter" action="/klients/pievienot-skaititaju" method="POST" role="form">
                  <input type="hidden" name="<?php echo \Config::get('security.csrf_token_key');?>" value="<?php echo \Security::fetch_token();?>" />
                  <div class="modal-body">
                    <div class="form-group">
                        <label for="number">Skaitītāja numurs</label>       
                        <input id="number" name="number" type="text" class="form-control" placeholder="Numurs, kas rakstīts uz skaitītāja priekšējā stikliņa">
                    </div>
                    <div class="form-group">
                       <label for="lead">Skaitītāja rādījums uzstādīšanas brīdī</label>
                       <input id="lead" name="lead" type="text" class="form-control" placeholder="000000">
                     </div>

                    <div class="form-group">
                        <label for="meter_type">Skaitītāja veids</label>       
                        <select name='meter_type' type="text" class="form-control">
                            <option disabled selected>Izvēlēties</option>
                            <option value='FILTER'>Ar filtru</option>
                            <option value='NO_FILTER'>Bez filtra</option>
                        </select>
                    </div>

                      <button id="reset" type="reset" class="btn btn-default">Notīrīt</button>
                      <button type="submit" class="btn btn-primary">Pievienot</button>
            </form>
        </div>
    </div>
    <div class='col-md-6'>
        <img class='img img-responsive img-rounded' src='http://i.ss.lv/images/2012-05-30/259955/VXgAFU1mRlo=/plumbing-plumbing-water-counters-0-2.800.jpg'/>
    </div>
</div>

<script>
    $(document).ready(function() {
//
    });
</script>