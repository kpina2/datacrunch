<?php 
	class DBconnection{
		function __construct(){
			$db = 'kpm_datacrunch';
			$un = 'KPdaAdmin';
			$pw = '4dmin4dmin';
			if($_SERVER['HTTP_HOST'] == 'kaypiem.com'){
				$db = 'kaypiemc_datacrunch';
				$un = 'kaypiemc_data';
				$pw = '8bXa*8KLuBx@';
			}
			
			$this->pdo = new PDO("mysql:host=localhost;dbname=$db", "$un", "$pw");
		}
	}	
?>