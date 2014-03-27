<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Informācijas sistēma Pilsētas ūdens">
        <meta name="author" content="Aleksandrs Gusevs">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title><?php echo $title; ?></title>
	<?php echo Asset::css('bootstrap.min.css'); ?>
        <?php echo Asset::css('style.css'); ?>
        <?php echo Asset::js('jquery.min.js'); ?>
        <?php echo Asset::js('bootstrap.js'); ?>
        <?php echo Asset::js('jquery.validate.js'); ?>
        <?php echo Asset::js('jquery-validate.bootstrap-tooltip.min.js'); ?>
        
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
</head>
<body>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-47792219-1', 'agusevs.com');
  ga('send', 'pageview');

</script>

<!-- navigācijas panelis -->
<nav class="navbar navbar-default" role="navigation">
    
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Paplašināt navigācijas izvēlni</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="/">IS Pilsētas ūdens</a>
    </div>
    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li <?php if(Uri::string()=='') echo 'class="active"'; ?>><a href="/" title="Doties uz lapas sākumlapu">Sākums</a></li>
        <li class="<?php if(Uri::segment(1)=='aktuali') echo "active"; ?> dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Aktuālā informācija">Aktuāli <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="/aktuali/jaunumi">Ziņas</a></li>
            <li><a href="/aktuali/karte">Aktuālo notikumu karte</a></li>
          </ul>
        </li>
        <li <?php if(Uri::string()=='pakalpojumi') echo 'class="active"'; ?>><a href="/pakalpojumi" title="Doties uz pakalpojumu lapu">Pakalpojumi</a></li>
        <li class="<?php if(Uri::segment(1)=='par-uznemumu') echo "active"; ?> dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Informācija par uzņēmumu">Par mums <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="/par-uznemumu/darbiba">Darbība</a></li>
            <li><a href="/par-uznemumu/dokumenti">Normatīvie dokumenti</a></li>
            <li><a href="/par-uznemumu/parvalde">Pārvalde</a></li>
            <li><a href="/par-uznemumu/projekti">Projekti</a></li>
            <li><a href="/par-uznemumu/vesture">Vēsture</a></li>
            <!--<li class="divider"></li>-->

          </ul>
        </li>
      </ul>
        
      <form class="visible-lg navbar-form navbar-left" role="search">
        <div class="form-group">
          <input type="text" class="form-control" placeholder="nevari kaut ko atrast?">
        </div>
        <button type="submit" class="btn btn-default" title="Ieraksti, ko gribi atrast un spied pogu">Meklēt</button>
      </form>
        
      <!-- mazais bloks -->
      <ul class="visible-md visible-sm nav navbar-nav navbar-right">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Pieslēgties <b class="caret"></b></a>
          <ul class="dropdown-menu">
        <?php if(Auth::check()) { ?>
            <li><a href="/user/logout">Iziet</a></li>
        <?php } else { ?>
            <li><a href="/user/login">Ieiet</a></li>
            <li><a href="/user/register">Reģistrēties</a></li>
        <?php } ?>
          </ul>
        </li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Palīdzība <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li class='hidden-lg'><a href='#'>Meklēt</a></li>
            <li><a href="#">Pieslēgšanās sistēmai</a></li>
            <li><a href="#">Patēriņa datu ievade</a></li>
            <li><a href="/palidziba/buj">Biežāk uzdotie jautājumi</a></li>
            <li class="divider"></li>
            <li><a href="/palidziba/sazinaties">Sazināties ar uzņēmumu</a></li>
            <?php if(Auth::get('group')==100) { ?>
            <li><a href="/vadiba/">Administrācijas panelis</a></li>
            <?php } ?>
          </ul>
        </li>
      </ul>
      <!-- mazais bloks beidzas -->
      
      <!-- lielais bloks -->  
      <ul class="hidden-md hidden-sm nav navbar-nav navbar-right">
        <?php if(Auth::check()) { ?>
            <li><a href="/user/logout">Iziet</a></li>
        <?php } else { ?>
            <li><a href="/user/login">Ieiet</a></li>
            <li><a href="/user/register">Reģistrēties</a></li>
        <?php } ?>
        <li class="<?php if(Uri::segment(1)=='palidziba') echo "active"; ?> dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Palīdzība <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="#">Pieslēgšanās sistēmai</a></li>
            <li><a href="#">Patēriņa datu ievade</a></li>
            <li><a href="/palidziba/buj">Biežāk uzdotie jautājumi</a></li>
            <li class="divider"></li>
            <li><a href="/palidziba/sazinaties">Sazināties ar uzņēmumu</a></li>
            <?php if(Auth::get('group')==100) { ?>
            <li><a href="/vadiba/">Administrācijas panelis</a></li>
            <?php } ?>
          </ul>
        </li>
      </ul>
      <!-- lielais bloks beidzas -->
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
<!-- /navigācijas panelis -->

    
    <!-- lapas ietvars -->
    <?php echo $content; ?>
    <!-- /lapas ietvars -->
    
    <!-- lapas kājene -->
    <div class="container">
        <hr> 
        <div class="text-right col-lg-12">
            <ul class="list-inline">
                <li><a href='#' class="text-muted">Ziņot par kļūdu</a></li>
                <li>Aleksandrs Gusevs &copy; <?php echo Date::time()->format('%Y'); ?></li>
            </ul>
        </div>
    </div
    <!-- /lapas kājene -->

</body>
</html>
