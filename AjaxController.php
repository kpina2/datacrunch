<?php
	class AjaxController{
		public $commands=array(
			"markers"
		);
		
		function __construct(){
			
		}

		function getMarkers($postdata){
			// set defaults
			$coords = new stdClass;
			$coords->lat = 36.174465;
			$coords->lon = -86.767960;
			$radius = 1;
			
			if(isset($postdata['coords']) && !empty($postdata['coords'])){
				if(is_numeric($postdata['coords']['lat'])  && is_numeric($postdata['coords']['lon'])){
					$coords->lat = $postdata['coords']['lat'];
					$coords->lon = $postdata['coords']['lon'];
				}
			}
			if(isset($postdata['radius']) && !empty($postdata['radius'])){
				if(is_numeric($postdata['radius'])){
					$radius = $postdata['radius'];
				}
			}

			$locationObj = new LocationManager;
			$markers = $locationObj->findMarkers($coords, $radius);
			echo json_encode($markers);
		}
	}
?>