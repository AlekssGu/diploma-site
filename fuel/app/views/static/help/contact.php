
<div class="container">
    <div class="row">
        <div class="col-md-5">
            <h1>Sazināties ar uzņēmumu</h1>
            <hr/>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-offset-3 col-lg-5"
            <form role="form">
              <div class="form-group">
                <label for="reason">Tēma</label>
                <input type="text" class="form-control" id="reason" placeholder="Ievadiet ziņas tēmu">
              </div>
              <div class="form-group">
                <label for="department">Nodaļa</label>
                <select class="form-control">
                    <option value='false'>Izvēlēties nodaļu</option>
                    <option>Ūdensapgāde</option>
                    <option>Kanalizācija</option>
                    <option>Ūdens attīrīšanas iekārtas</option>
                    <option>Abonentu daļa</option>
                    <option>Cits</option>
                </select>
              </div>
              <div class="form-group">
                <label for="exampleInputFile">Ziņojums</label>
                <textarea class="form-control" rows="3"></textarea>
              </div>
              <div class="checkbox">
                <label>
                <input type="checkbox"> Piekrītu <a href="#">noteikumiem</a> par saziņu ar uzņēmumu
                </label>
              </div>
              <button type="submit" class="btn btn-default">Sūtīt</button>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        //
    });
</script>