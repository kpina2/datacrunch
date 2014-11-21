<?php
	class NeighborhoodManager extends DBconnection{
		function getAll(){
			$sql = "SELECT * FROM neighborhood";
			$stmt = $this->pdo->query($sql);
	  		return $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
	}
?>