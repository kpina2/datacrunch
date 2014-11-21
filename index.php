<?php 
  require "DBconnection.php.inc";
  require "AjaxController.php";
  require "TagManager.php";
  require "NeighborhoodManager.php";
  $tagMgr = new TagManager;
  $tags = $tagMgr->getAll();

  $neighborhoodMgr = new NeighborhoodManager;
  $neighborhoods = $neighborhoodMgr->getAll();
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" href="css/bootstrap.min.css">
        <style>
            body {
                padding-top: 50px;
                padding-bottom: 20px;
            }
        </style>
        <link rel="stylesheet" href="css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="css/main.css">
        <script>
            <?php
              $ajaxurl = "/ajax.php";
              if($_SERVER['HTTP_HOST'] == 'kaypiem.com'){
                $ajaxurl = "/datacrunch/ajax.php";
              }
            ?>
            ajaxurl = '<?php echo $ajaxurl; ?>';
        </script>
        <script src="js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Nashville Historic Markers</a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="#">About</a></li>
            <li><a href="#">Tags</a></li>
            <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
               More <b class="caret"></b>
            </a>
            <ul class="dropdown-menu">
               <li><a href="#">Contact</a></li>
            </ul>
         </li>
          </ul>
          <<!-- form class="navbar-form navbar-right" role="form">
            <div class="form-group">
              <input type="text" placeholder="Email" class="form-control">
            </div>
            <div class="form-group">
              <input type="password" placeholder="Password" class="form-control">
            </div>
            <button type="submit" class="btn btn-success">Sign in</button>
          </form> -->
        </div><!--/.navbar-collapse -->
      </div>
    </div>

    <!-- Main jumbotron for a primary marketing message or call to action -->
    

    <div class="container">
      <!-- Example row of columns -->
      <div class="row">
        <div id="map-canvas"></div>
      </div>

      <hr>

      <div class="row">
        <div class="col-md-4">
          <a class="btn btn-default" role="button" id='find-near-me'>Near Me</a>
          <a class="btn btn-default" role="button" id='show-all'>Show All</a>
        </div>
        <div class="col-md-4">
          Neighborhoods:
          <?php $count=0; ?>
          <?php foreach($neighborhoods as $neighborhood): ?>
              <a class='neighborhood-item' href='#' data-lat='<?php echo $neighborhood['lat']; ?>' data-lon='<?php echo $neighborhood['lon']; ?>'><?php echo $neighborhood['name']; ?></a>
             
              <?php $count++; ?>
              <?php echo ($count < count($neighborhoods) ? ",": ""); ?>
          <?php endforeach; ?>
        </div>

        <div class="col-md-4">
          Tags:
          <?php $count=0; ?>
          <?php foreach($tags as $tag): ?>
            
              <a class='tag-item' href='#' data-tag_id='<?php echo $tag['id']; ?>'><?php echo $tag['name']; ?></a>
              <?php $count++; ?>
              <?php echo ($count < count($tags) ? ",": ""); ?>
          <?php endforeach; ?>
        </div>
      <footer>
        
      </footer>
    </div> <!-- /container -->        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.11.1.min.js"><\/script>')</script>

        <script src="js/vendor/bootstrap.min.js"></script>

        <script src="js/plugins.js"></script>
        <script src="js/main.js"></script>

        <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js">

        <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
        <script>
            // (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
            // function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
            // e=o.createElement(i);r=o.getElementsByTagName(i)[0];
            // e.src='//www.google-analytics.com/analytics.js';
            // r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
            // ga('create','UA-XXXXX-X');ga('send','pageview');
        </script>
        <script>
          
          
          $(document).ready(function(){
            $().markerBrowser("init");

            $("#show-all").click(function(){
              $(this).markerBrowser("getmarkers", {
                opt_radius: 10,
                opt_zoom: 10,
                use_user_position: false,
                opt_tag: "",
                override: false
              });
            });

            $("#find-near-me").click(function(){
              $(this).markerBrowser("getmarkers", {
                opt_radius: 0.9,
                opt_zoom: 13,
                use_user_position: true,
                opt_tag: "",
                override: false
              });
            });

            $(".tag-item").click(function(){
              $(this).markerBrowser("getmarkers", {
                opt_radius: 10,
                opt_zoom: 10,
                use_user_position: false,
                opt_tag: $(this).attr("data-tag_id"),
                override: false
              });
            });

            $(".neighborhood-item").click(function(){
              console.log($(this).attr("data-lat") + " " + $(this).attr("data-lon"));

              $(this).markerBrowser("getmarkers", {
                opt_radius: 0.5,
                opt_zoom: 14,
                use_user_position: false,
                opt_lat: $(this).attr("data-lat"),
                opt_lon: $(this).attr("data-lon"),
                opt_tag: "",
                override: true
              });
            });

          });
          
        </script>
    </body>
</html>
