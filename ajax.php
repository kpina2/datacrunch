<?php
	require "DBconnection.php.inc";
	require "AjaxController.php";
	require "LocationManager.php";
	$ajaxCtlr = new AjaxController;

	if(isset($_REQUEST['cmd']) && in_array($_REQUEST['cmd'], $ajaxCtlr->commands)){
		
		switch ($_REQUEST['cmd']){
			case "markers":
				$ajaxCtlr->getMarkers($_REQUEST);
				break;
		}
	}else{
		echo "";
	}	
?>