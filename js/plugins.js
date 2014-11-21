// Avoid `console` errors in browsers that lack a console.
(function() {
    var method;
    var noop = function () {};
    var methods = [
        'assert', 'clear', 'count', 'debug', 'dir', 'dirxml', 'error',
        'exception', 'group', 'groupCollapsed', 'groupEnd', 'info', 'log',
        'markTimeline', 'profile', 'profileEnd', 'table', 'time', 'timeEnd',
        'timeStamp', 'trace', 'warn'
    ];
    var length = methods.length;
    var console = (window.console = window.console || {});

    while (length--) {
        method = methods[length];

        // Only stub undefined methods.
        if (!console[method]) {
            console[method] = noop;
        }
    }
}());

// Place any jQuery/helper plugins in here.
(function($){
    var user_position={};
    var mapOptions;
    var map;
    var infowindow;
    var marker_list = [];
    var markers = [];

    var settings = {
        ajaxurl: ajaxurl,
        default_lat: 36.174465,
        default_lon: -86.767960,
        opt_radius: 0.9,
        opt_zoom: 11,
        opt_lat: 36.174465,
        opt_lon: -86.767960,
        opt_tag: "",
        use_user_position: false
    };

    function getUserLocation(){
        if (navigator.geolocation) {
            if($.isEmptyObject(user_position)){
                navigator.geolocation.getCurrentPosition(showPosition);
            }else{
                settings.opt_lat = user_position.coords.latitude;
                settings.opt_lon = user_position.coords.longitude;
            }
        }
    }
    function showPosition(pos){
        user_position = pos;
        settings.opt_lat = user_position.coords.latitude;
        settings.opt_lon = user_position.coords.longitude
    }
    function removeMarkers() {
        // console.log(markers);
      for (var i = 0; i < markers.length; i++) {
        markers[i].setMap(null);
      }
    }

    function mapit(){
        i=0;
        for(marker in marker_list){
          m = marker_list[marker];
          m_coords = new google.maps.LatLng(m.lat, m.lon);
          
          var m_marker = new google.maps.Marker({
              position: m_coords,
              map: map,
              title: m.title,
              m_id: i
          });
          markers[i] = m_marker;

          google.maps.event.addListener(m_marker, 'click', function(){
              infowindow.setContent("<h3>" + marker_list[this.m_id].title + "</h3>"+ marker_list[this.m_id].marker_text);
              infowindow.open(map, this);

              current_center = map.getCenter();
                console.log(current_center);
                

              google.maps.event.addListener(infowindow, 'closeclick', function() {
                    map.panTo(current_center);
              });
          });
          
          i++;
        }

        if(settings.use_user_position){
            pos = new google.maps.LatLng(settings.opt_lat, settings.opt_lon);
            map.setCenter(pos);
            map.setZoom(settings.opt_zoom);
        }else{
            if(settings.override){
                pos = new google.maps.LatLng(settings.opt_lat, settings.opt_lon);
                map.setCenter(pos);
                map.setZoom(settings.opt_zoom);
            }else{
                pos = new google.maps.LatLng(settings.default_lat, settings.default_lon);
                map.setCenter(pos);
                map.setZoom(settings.opt_zoom);
            }
            
        }
    }

    var methods = {
        init : function(options) {
            if(options) {
                $.extend(settings,options);
            }
            mapOptions = {
              center: {  lat: 36.174465, lng: -86.767960 },
              zoom: 11
            };
            map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
            infowindow = new google.maps.InfoWindow({
              maxWidth: 240
            });
            getUserLocation();
        },
        getmarkers : function(options) {
            removeMarkers();
            if(options) {
                $.extend(settings,options);
            }
            if(settings.use_user_position){
                query_lat = settings.opt_lat;
                query_lon = settings.opt_lon;
            }else {
                if(settings.override){
                    query_lat = settings.opt_lat;
                    query_lon = settings.opt_lon;
                }else{
                    query_lat = settings.default_lat;
                    query_lon = settings.default_lon;
                }
            }

            postdata = {
                cmd: "markers",
                coords: {
                  lat: query_lat,
                  lon: query_lon
                },
                radius: settings.opt_radius,
                tag_id: settings.opt_tag
            };
            $.ajax({
                url: settings.ajaxurl,
                data: postdata,
                method: "post",
                dataType: "json",
                success: function(response){
                  if($.isEmptyObject(response)){

                  }else{
                    marker_list = response;
                    mapit();
                  }
                }
              });
        },
        
    };

    $.fn.markerBrowser = function(method, options){
        var args = arguments;
        var $this = $(this);

        if ( methods[method] ) {
            return methods[ method ].apply( $this, Array.prototype.slice.call( args, 1 ));
        } else if ( typeof method === 'object' || ! method ) {
            // Default to "init"
            return methods.init.apply( $this, args );
        } else {
            $.error( 'Method ' +  method + ' does not exist on jQuery.tooltip' );
        }
    }
}(jQuery));