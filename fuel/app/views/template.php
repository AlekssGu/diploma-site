<!DOCTYPE html>
<html lang="en">
<head>
	<title><?php echo $title; ?></title>    
	<meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Informācijas sistēma Pilsētas ūdens">
        <meta name="author" content="Aleksandrs Gusevs">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<?php echo Asset::css('bootstrap.min.css'); ?>
        <?php echo Asset::css('style.css'); ?>
        <?php echo Asset::js('jquery.min.js'); ?>
        <?php echo Asset::js('bootstrap.js'); ?>
        <?php echo Asset::js('jquery.validate.js'); ?>
        <?php echo Asset::js('jquery-validate.bootstrap-tooltip.min.js'); ?>
        <link rel="shortcut icon" href="/assets/img/favicon.ico" type="image/x-icon">
        <link rel="icon" href="/assets/img/favicon.ico" type="image/x-icon">
        
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
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Aktuālā informācija">Jaunumi <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="/aktuali/jaunumi">Ziņas</a></li>
            <li class='disabled'><a href="/aktuali/darbi">Plānotie darbi</a></li>
            <li class='disabled'><a href="/aktuali/medijiem">Medijiem</a></li>
            <li class='disabled'><a href="/aktuali/fakti">Interesanti fakti</a></li>
          </ul>
        </li>
        
        <li class="<?php if(Uri::segment(1)=='par-uznemumu') echo "active"; ?> dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Informācija par uzņēmumu">Par mums <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="/par-uznemumu/rekviziti" title="Uzņēmuma rekvizīti">Rekvizīti</a></li>
            <li><a href="/par-uznemumu/sertifikati" title="Uzņēmuma sertifikāti">Sertifikāti</a></li>
            <li><a href="/par-uznemumu/struktura" title="Organizatoriskā struktūra">Struktūra</a></li>
            <li><a href="/par-uznemumu/nozare" title="Uzņēmuma nozares">Nozare</a></li>
            <li><a href="/par-uznemumu/vesture" title="Uzņēmuma vēsture">Vēsture</a></li>
            <li><a href="/par-uznemumu/ekskursijas" title="Ekskursijas pa uzņēmumu">Ekskursijas</a></li>
          </ul>
        </li>
        
        <li class="<?php if(Uri::segment(1)=='abonents') echo "active"; ?> dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Sadaļa uzņēmuma abonentiem">Abonentiem <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <?php if(!Auth::check()) { ?>
            <li><a href="/abonents/pieslegties" title="Uzņēmuma rekvizīti">Ieiet sistēmā</a></li>
            <li><a href="/abonents/registreties" title="Uzņēmuma rekvizīti">Reģistrēties</a></li>
            <li class='divider'></li>
            <?php } ?>
            <li class='disabled'><a href="/par-uznemumu/dokumenti" title="Uzņēmuma sertifikāti">Aktuālā informācija</a></li>
            <li class='disabled'><a href="/par-uznemumu/parvalde" title="Organizatoriskā struktūra">Cenas un tarifi</a></li>
            <li class='disabled'><a href="/par-uznemumu/projekti" title="Uzņēmuma nozares">Skaitītāju maiņa</a></li>
          </ul>
        </li>
        
        <li <?php if(Uri::string()=='pakalpojumi') echo 'class="active"'; ?>><a href="/pakalpojumi" title="Doties uz pakalpojumu lapu">Pakalpojumi</a></li>
        <li class='disabled'<?php if(Uri::string()=='projekti') echo 'class="active"'; ?>><a href="/projekti" title="Doties uz projektu lapu">Projekti</a></li>

        
        
      </ul>
        
      <!-- mazais bloks -->
      <ul class="visible-md visible-sm nav navbar-nav navbar-right">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Palīdzība <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li class='hidden-lg'><a href='#'>Meklēt</a></li>
            <li class='disabled'><a href="#">Pieslēgšanās sistēmai</a></li>
            <li class='disabled'><a href="#">Patēriņa datu ievade</a></li>
            <li class='disabled'><a href="/palidziba/buj">Biežāk uzdotie jautājumi</a></li>
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
            <li><a href="/abonents/atslegties">Iziet</a></li>
        <?php } ?>
        <li class="<?php if(Uri::segment(1)=='palidziba') echo "active"; ?> dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Palīdzība <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li class='disabled'><a href="#">Pieslēgšanās sistēmai</a></li>
            <li class='disabled'><a href="#">Patēriņa datu ievade</a></li>
            <li><a href="/palidziba/buj">Biežāk uzdotie jautājumi</a></li>
            <li class="divider"></li>
            <li><a href="/palidziba/sazinaties">Sazināties ar uzņēmumu</a></li>
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
