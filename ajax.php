<?php
	require "DBconnection.php.inc";
	require "AjaxController.php";
	require "LocationManager.php";
	$ajaxOjb = new AjaxController;

	if(isset($_REQUEST['cmd']) && in_array($_REQUEST['cmd'], $ajaxOjb->commands)){
		
		switch ($_REQUEST['cmd']){
			case "markers":
				$ajaxOjb->getMarkers($_REQUEST);
				break;
		}
	}else{
		echo "";
	}	
?>