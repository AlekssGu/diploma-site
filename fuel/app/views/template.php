<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Informācijas sistēma \"Pilsētas ūdens\"">
        <meta name="author" content="Aleksandrs Gusevs">
	<title><?php echo $title; ?></title>
	<?php echo Asset::css('bootstrap.min.css'); ?>
        <?php echo Asset::css('style.css'); ?>
        <?php echo Asset::js('jquery.min.js'); ?>
        <?php echo Asset::js('bootstrap.js'); ?>
</head>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-47792219-1', 'agusevs.com');
  ga('send', 'pageview');

</script>

<body>
    <!-- navigācijas panelis -->
    <div class="navbar navbar-default">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="visible-xs navbar-brand" href="/">Pilsētas ūdens</a>
        </div>
        <div class="text-center navbar-collapse collapse navbar-responsive-collapse">

            <div id="nav-links" class="row">
                <div class="col-lg-3">
                    <a href="/" type="button" class="btn btn-link">Sākums</a>
                </div>
                <div class="col-lg-3">
                    <a href="/" type="button" class="btn btn-link disabled">Lapa 2</a>
                </div>
                <div class="col-lg-3">
                    <a href="/" type="button" class="btn btn-link disabled">Lapa 3</a>
                </div>
                <div class="col-lg-3">
                    <a href="/" type="button" class="btn btn-link disabled">Lapa 4</a>
                </div>
            </div>

        </div>
      </div>
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
