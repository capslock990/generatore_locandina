<?php

//Include la funzione recuperaLocandina
include ('recupera_locandina.php');

//Preleva i valori inviati dal form
$idSeminario = $_POST['idSeminario'];
$matricolaProf = $_POST['matricola'];

//Impostazioni connessione al DB MySQL
$servername = "localhost";
$username = "utenteprova";
$password = "passwordprova";

//Imposta la cartella per l'upload del file
$cartellaUpload = 'C:/xampp/htdocs/uploads/';
$nomeFile = $_FILES['locandina_personale']['name'];
$fileTemp = $_FILES['locandina_personale']['tmp_name'];
$tipoFile = strtolower(pathinfo($_FILES['locandina_personale']['name'], PATHINFO_EXTENSION));
$locandinaCheck = true;

//Effettua i controlli sul file
if (!isset($_FILES['locandina_personale']) || !is_uploaded_file($_FILES['locandina_personale']['tmp_name'])) {
	$erroreLocandina = "Locandina non caricata correttamente!<br \>";
	$locandinaCheck = false;
} elseif ($_FILES['locandina_personale']['size'] > 5242880) {
	$erroreLocandina = "Dimensione locandina eccessiva!<br \>";
	$locandinaCheck = false;
} elseif ($tipoFile != "pdf" && $tipoFile != "doc" && $tipoFile != "docx" && $tipoFile != "odt" ) {
    $erroreLocandina = "Tipo file non consentito!<br \>";
	$locandinaCheck = false;
} else {
	move_uploaded_file($fileTemp, $cartellaUpload.$nomeFile);
}

//Inserisce la posizione del file caricato nel DB  
if ($locandinaCheck == false) {
	echo "$erroreLocandina";
	echo "<a href=inserimento_dati.html>Torna al modulo</a>";
} elseif (empty(recuperaLocandina($idSeminario))) {
	try {
    $db = new PDO("mysql:host=$servername;dbname=database_locandine", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "INSERT INTO locandina (id_locandina, id_seminario, matricola_prof, nome_file, file_locandina) VALUES (:id_locandina, :id_seminario, :matricola_prof, :nome_file, :posizione_locandina)";
	$stmt = $db->prepare($sql);
	$stmt->bindValue(':id_locandina', NULL, PDO::PARAM_STR);
	$stmt->bindValue(':id_seminario', $idSeminario, PDO::PARAM_STR);
	$stmt->bindValue(':matricola_prof', $matricolaProf, PDO::PARAM_STR);
	$stmt->bindValue(':nome_file', $_FILES['locandina_personale']['name'], PDO::PARAM_STR);
	$stmt->bindValue(':posizione_locandina', $cartellaUpload, PDO::PARAM_STR);
	$stmt->execute();
    }
	catch(PDOException $e)
    {
    echo "Impossibile inserire la locandina: " . $e->getMessage();
    }
} else {
    try {
    $db = new PDO("mysql:host=$servername;dbname=database_locandine", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "UPDATE locandina SET id_locandina=:id_locandina, nome_file=:nome_file, posizione_locandina=:posizione_locandina WHERE id_seminario=:id_seminario";
	$stmt = $db->prepare($sql);
	$stmt->bindValue(':id_locandina', NULL, PDO::PARAM_STR);
	$stmt->bindValue(':id_seminario', $idSeminario, PDO::PARAM_STR);
	$stmt->bindValue(':nome_file', $_FILES['locandina_personale']['name'], PDO::PARAM_STR);
	$stmt->bindValue(':posizione_locandina', $cartellaUpload, PDO::PARAM_STR);
	$stmt->execute();
	echo 'Locandina caricata correttamente.<br><br> Clicca qui visualizzarla: <a href="http://localhost/uploads/'.recuperaLocandina($idSeminario).'">Locandina</a><br><br>';
    echo '<a href=inserimento_dati.html>Torna al modulo</a>';
	}
	catch(PDOException $e)
    {
    echo "Impossibile inserire la locandina: " . $e->getMessage();
    }
}

?>
