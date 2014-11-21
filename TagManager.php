<?php
	class TagManager extends DBconnection{
		function getAll(){
			$sql = "SELECT * FROM tag";
			$stmt = $this->pdo->query($sql);
      		return $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
	}
?>