<?php

function recuperaLocandina($idSeminario){

 //Impostazioni connessione al DB MySQL
 $servername = "localhost";
 $username = "utenteprova";
 $password = "passwordprova";

 //Esegue la query sul database
 try {
    $db = new PDO("mysql:host=$servername;dbname=database_locandine", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "SELECT * FROM locandina WHERE id_seminario = :idseminario";
	$stmt = $db->prepare($sql);
	$stmt->bindValue(':idseminario', $idSeminario, PDO::PARAM_STR);
	$stmt->execute();
	while($righe = $stmt->fetch(PDO::FETCH_ASSOC)) {
		return $righe['nome_file'];
	}
 }
 catch(PDOException $e) {
	echo "Impossibile connettersi al database: " . $e->getMessage();
 }
}

?>