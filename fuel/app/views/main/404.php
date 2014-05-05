    <!-- 404 lapas saturs -->
    <div class="container">
        <div class="row main-block">
            <div class="col-md-12 text-center">
                <h1>Ups! <small>Pieprasītā lapa netika atrasta!</small></h1>
                <h4><em>Tagad galvenais nesatraukties</em></h4>
                
                <br/>
                <div class='col-md-offset-3 col-md-6 alert alert-info'>
                    <ul class='list-inline'>
                        <li><a href='/' class='btn btn-primary'>Doties uz sākumu</a></li>
                        <?php if(Auth::check()) { ?>
                            <li><a href='/abonents' class='btn btn-primary'>Apskatīt abonenta informāciju</a></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class='row main-block'>
            <div class='col-md-12 text-center'>
                <form style="margin-bottom:40px;" class="form-inline">
                    <div class="form-group">
                        <label class="sr-only" for="search">Meklēt</label>
                        <input class="form-control" placeholder="Esi pavisam pazudis?" />
                    </div>
                     <button type="submit" class="btn btn-default">Meklēt</button>
                </form>
            </div>
        </div>
    <!-- /404 lapas saturs -->