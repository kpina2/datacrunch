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
          <a class="navbar-brand" href="#">Project name</a>
        </div>
        <div class="navbar-collapse collapse">
          <form class="navbar-form navbar-right" role="form">
            <div class="form-group">
              <input type="text" placeholder="Email" class="form-control">
            </div>
            <div class="form-group">
              <input type="password" placeholder="Password" class="form-control">
            </div>
            <button type="submit" class="btn btn-success">Sign in</button>
          </form>
        </div><!--/.navbar-collapse -->
      </div>
    </div>

    <!-- Main jumbotron for a primary marketing message or call to action -->
    

    <div class="container">
      <!-- Example row of columns -->
      <div class="row">

        <div id="map-canvas"></div>

        <div class="col-md-4">
          <p><a class="btn btn-default" role="button" id='find-near-me'>Near Me</a></p>
        </div>
       

      <hr>

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
          var user_position={};
          
          
          $(document).ready(function(){
            var markers = new Array();
            var mapOptions = {
              center: {  lat: 36.174465, lng: -86.767960 },
              zoom: 11
            };
            var infowindow = new google.maps.InfoWindow({
              maxWidth: 240
            });
            var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
            function showPosition(position) {
              user_position = position;
              getMarkers(0.9);
            }
            function getMarkers(radius){
              var pos = new google.maps.LatLng(user_position.coords.latitude, user_position.coords.longitude);
              map.setCenter(pos);
              map.setZoom(13);

              postdata = {
                cmd: "markers",
                coords: {
                  lat: user_position.coords.latitude,
                  lon: user_position.coords.longitude
                },
                radius: radius
              };
              
              $.ajax({
                url: "/ajax.php",
                data: postdata,
                method: "post",
                dataType: "json",
                success: function(response){
                  if($.isEmptyObject(response)){

                  }else{
                    i=0;
                    for(marker in response){
                      marker_list = response;
                      m = response[marker];
                      console.log(m);
                      m_coords = new google.maps.LatLng(m.lat, m.lon);
                      
                      var m_marker = new google.maps.Marker({
                          position: m_coords,
                          map: map,
                          title: m.title,
                          m_id: i
                      });
                      markers[i] =response[marker];

                      google.maps.event.addListener(m_marker, 'click', function(){
                          console.log(this.m_id);
                          infowindow.setContent("<h3>" + markers[this.m_id].title + "</h3>"+ markers[this.m_id].marker_text);
                          infowindow.open(map, this);
                      });
                      i++;
                    }
                  }
                }
              });
            }

            $("#find-near-me").click(function(){
              if (navigator.geolocation) {
                if($.isEmptyObject(user_position)){
                  navigator.geolocation.getCurrentPosition(showPosition);
                }else{
                  getMarkers(0.9);
                }
              }
            });
          });
          
        </script>
    </body>
</html>
