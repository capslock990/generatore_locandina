<?php

//Divide la descrizione in righe per contarle
$contaDescrizione = count(preg_split("/\\r\\n|\\r|\\n/",$_POST['descrizione']));

//Controlla se tutti i campi del form sono stati compilati
if (!isset($_POST['nome'])){
	echo "Nome non compilato correttamente!<br>";
	echo "<a href=inserimento_dati.html>Torna al modulo</a>";
} elseif (!isset($_POST['cognome'])){
	echo "Cognome non compilato correttamente!<br>";
	echo "<a href=inserimento_dati.html>Torna al modulo</a>";
} elseif (!isset($_POST['seminario'])){
	echo "Titolo non compilato correttamente!<br>";
	echo "<a href=inserimento_dati.html>Torna al modulo</a>";
} elseif (!isset($_POST['data'])){
	echo "Data non compilata correttamente!<br>";
	echo "<a href=inserimento_dati.html>Torna al modulo</a>";
} elseif (!isset($_POST['orario_inizio'])){
	echo "Orario non compilato correttamente!<br>";
	echo "<a href=inserimento_dati.html>Torna al modulo</a>";
} elseif (!isset($_POST['descrizione'])){
	echo "Descrizione non compilata correttamente!<br>";
	echo "<a href=inserimento_dati.html>Torna al modulo</a>";
}elseif ($contaDescrizione > 46){
	echo "Numero righe descrizione eccessivo!<br>";
	echo "<a href=inserimento_dati.html>Torna al modulo</a>";
} else {

//Carica l'immagine per lo sfondo personalizzato e controlla che rispetti i requisiti
if ($_POST['sfondo'] == 'personalizzato') {
	$cartellaUpload = 'C:/xampp/htdocs/uploads/';
	$nomeFile = $_FILES['sfondoupload']['name'];
	$fileTemp = $_FILES['sfondoupload']['tmp_name'];
	$tipoFileSfondo = strtolower(pathinfo($_FILES['sfondoupload']['name'], PATHINFO_EXTENSION));
	$controlloFormato = getimagesize($_FILES['sfondoupload']['tmp_name']);
	$sfondoCheck = TRUE;

if (!isset($_FILES['sfondoupload']) || !is_uploaded_file($_FILES['sfondoupload']['tmp_name'])) {
	$erroreSfondo = "Sfondo personalizzato non caricato correttamente!<br \>";
	$sfondoCheck = FALSE;
} elseif ($_FILES['sfondoupload']['size'] > 1048576) {
	$erroresfondo = "Dimensione sfondo personalizzato eccessiva!<br \>";
	$sfondoCheck = FALSE;
} elseif($tipoFileSfondo != "jpg" && $tipoFileSfondo != "png" && $tipoFileSfondo != "jpeg" && $tipoFileSfondo != "gif" ) {
    $erroreSfondo = "Tipo file non consentito!<br \>";
	$sfondoCheck = FALSE;
} elseif (!$controlloFormato) {
	$erroreSfondo = "Il file caricato non è un'immagine!<br \>";
	$sfondoCheck = FALSE;  
} else {
	move_uploaded_file($fileTemp, $cartellaUpload.$nomeFile);
	} 
}

if ($_POST['sfondo'] == 'personalizzato' AND $sfondoCheck == FALSE){
	echo "$erroreSfondo";
	echo "<a href=inserimento_dati.html>Torna al modulo</a>";
} else {

//Associa i campi del form a variabili
$nome = $_POST['nome'];
$cognome = $_POST['cognome'];
$titolo = $_POST['seminario'];
$sottotitolo = $_POST['sottotitolo'];
$orarioInizio = $_POST['orario_inizio'];
$orarioFine = $_POST['orario_fine'];
$descrizione = $_POST['descrizione'];
$cfu = $_POST['cfu'];
$luogo = $_POST['luogo'];
$luogPersonalizzato = $_POST['luogo_pers_valore'];
$aula = $_POST['aula'];
$cdl = $_POST['cdl'];
$cdlm = $_POST['cdlm'];
$idSeminario = $_POST['idSeminario'];

//Converte la data nel formato italiano esteso
setlocale(LC_TIME, 'ita', 'it_IT');
$data = strtotime($_POST['data']);
$dataIta = strftime("%e %B %Y", $data);

//Imposta il carattere 'bullett' fra data ed orario
$bull = "\xe2\x80\xa2";

//Imposta il font
$font = 'dejavuserifcondensed';

// Include la libreria TCPDF (search for installation path).
require_once('./tcpdf/tcpdf.php');

// Estende la classe TCPDF per creare un Header e un Footer personalizzati
class MYPDF extends TCPDF {

	public function Header() {
		//Imposta il font
		$font = 'dejavuserifcondensed';

		// Sfondo della locandina
	    // Imposta il BreakMargin
        $bMargin = $this->getBreakMargin();
        // Salva la modalità auto-page-break mode
        $auto_page_break = $this->AutoPageBreak;
        // Disabilita l'auto break
        $this->SetAutoPageBreak(false, 0);
        // Seleziona l'immagine da inserire come sfondo (predefinita o personalizzata)
		if($_POST['sfondo'] == 'personalizzato'){
		$sfondoPersImg = 'C:/xampp/htdocs/uploads/'.$_FILES['sfondoupload']['name'];
		$this->SetAlpha(0.2);
        $this->Image($sfondoPersImg, 0, 0, 210, 297, '', '', '', true, 300, '', false, false, 0);
		} else {
        $sfondoDefault = K_PATH_IMAGES.'sfondodefault.jpg';
        $this->Image($sfondoDefault, 0, 0, 210, 297, '', '', '', true, 300, '', false, false, 0);
		}
        // Ripristina lo stato di auto-page-break
        $this->SetAutoPageBreak($auto_page_break, $bMargin);
        // Imposta il punto di inizio del contenuto della pagina
        $this->setPageMark();
		//Ripristina la trasparenza
		$this->SetAlpha(1);
		// Header locandina
		// Logo SX
		$this->Image(K_PATH_IMAGES.'logo_DEC.png', 5, 7, 0, 20, '', 'https://www.economia.unich.it', '', false, 300, 'L', false, false, 0, false, false, false);
		// Imposta font
		$this->SetFont($font, 'B', 40);
		// Testo header
		$this->Ln(5);
		$this->SetTextColor(0,10,70);
		$this->Write(0, SEMINARIO, '', 0, 'C', true, 0, false, false, 0);	
		// Logo DX
		$this->Image(K_PATH_IMAGES.'logo_UdA.png', 5, 7, 0, 27, '', 'https://www.unich.it', '', false, 300, 'R', false, false, 0, false, false, false);
	}

	// Footer locandina
	public function Footer() {
		//Imposta il font
		$font = 'dejavuserifcondensed';

		//Stampa i loghi dei corsi di laurea (se selezionati)
		$stampaLoghi = null;

		if (isset($_POST['cdl'])) {
			foreach ($_POST['cdl'] as $cdl => $cdlValore){
			if (isset($cdl)){ $stampaLoghi .= '<img alt='.$cdlValore.' src="./tcpdf/examples/images/'.$cdlValore.'.jpg" width="60" height="35">  ';
			}
			}
		}

		if (isset($_POST['cdlm'])) {
			foreach ($_POST['cdlm'] as $cdlm => $cdlmValore){
			if (isset($cdlm)){ $stampaLoghi .= '<img alt='.$cdlmValore.' src="./tcpdf/examples/images/'.$cdlmValore.'.jpg" width="60" height="35">  ';
			}
			}
		}

		// Posizione a 28mm dal fondo pagina
		$this->SetY(-28);

		// Stampa i loghi dei CDL
		$this->writeHTMLCell(0, 10, '', '', $stampaLoghi, 0, 1, 0, true, 'C', true);

		// Posizione a 13 mm dal fondo pagina
		$this->SetY(-13);

		// Imposta il font
		$this->SetFont($font, '', 8);

		// Testo del footer
		$footer = 'Dipartimento di Economia - Università degli studi G.DAnnunzio - Chieti Pescara - economia.unich.it';
		$this->Write(0, $footer, 'https://economia.unich.it/', 0, 'C', true, 0, false, false, 0);
	}
}

// Crea il documento PDF
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Imposta le informazioni del documento
$pdf->SetCreator('UniCH');
$pdf->SetAuthor("$nome $cognome");
$pdf->SetTitle("$titolo");
$pdf->SetSubject('Locandina Seminario');
$pdf->SetKeywords('UdA, UniCH, Seminario, Università Chieti-Pescara');

// Imposta il font come monospaced
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// Imposta i margini
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// Imposta il punto in cui comincia la pagina successiva
$pdf->SetAutoPageBreak(TRUE, 27);

// Imposta il fattore di scala delle immagini
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// Imposta la lingua delle stringhe specifiche
if (@file_exists(dirname(__FILE__).'/lang/ita.php')) {
    require_once(dirname(__FILE__).'/lang/ita.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// Imposta la modalità del fontsubsetting
$pdf->setFontSubsetting(true);

// Aggiunge una pagina
$pdf->AddPage();

//Stampa titolo seminario
$pdf->Ln(11);
$pdf->SetTextColor(0,10,70);
$pdf->SetFont($font, 'B', 20);
$pdf->Cell(0, 10, utf8_encode("''$titolo''"), 0, 1, 'C', false, '', 1, false, 'C', 'C');

//Stampa il sottotitolo (se presente)
if(!empty($sottotitolo)){
	$pdf->Ln(2);
	$pdf->SetFont($font, '', 12);
	$pdf->Cell(0, 5, utf8_encode("''$sottotitolo''"), 0, 0, 'C', false, '', 1, false, 'C', 'C');
}

//Stampa giorno e orario
if(!empty($orarioFine)){
	$orarioFineStampa = "- $orarioFine";
}
$pdf->Ln(10);
$pdf->SetFont($font, '', 13);
$pdf->Cell(0, 10, "$dataIta $bull  Ore $orarioInizio $orarioFineStampa", 0, 1, 'C', false, '', 0, false, 'C', 'C');

//Stampa il luogo e l'aula (se presente)
if ($luogo == sede_pescara) {
			$pdf->Ln(2);
			if(!empty($aula)){
			$aulacheck = "- Aula $aula";
			}
	$pdf->Cell(0, 10, utf8_encode("Pescara, Viale Pindaro 42 $aulacheck"), 0, 1, 'C', false, '', 0, false, 'C', 'C');
} elseif ($luogo == sede_chieti) {
			$pdf->Ln(2);
			if(!empty($aula)){
			$aulacheck = "- Aula $aula"; 
			}
	$pdf->Cell(0, 10, utf8_encode("Chieti, Via dei Vestini 31 $aulacheck"), 0, 1, 'C', false, '', 0, false, 'C', 'C');
} elseif ($luogo == sede_aurum) {
			$pdf->Ln(2);
			if(!empty($aula)){
			$aulacheck = "- $aula";
			}
	$pdf->Cell(0, 10, utf8_encode("Aurum, Largo Gardone Riviera $aulacheck"), 0, 1, 'C', false, '', 0, false, 'C', 'C');
} elseif ($luogo == sede_pers) {
			$pdf->Ln(2);
			if(!empty($aula)){
			$aulacheck = "- $aula";
			}
	$pdf->Cell(0, 10, utf8_encode("$luogPersonalizzato $aulacheck"), 0, 1, 'C', false, '', 0, false, 'C', 'C');
}

//Stampa i CFU se presenti
if($cfu > 0){
	$pdf->Ln(2);
	$pdf->SetFont($font, 'B', 10);
	$pdf->Cell(0, 12, "Ai frequentanti saranno riconosciuti $cfu CFU di lettera F", 0, 0, 'C', false, '', 0, false, 'C', 'C');
}

// Stampa il contenuto di $descrizione utilizzando la funzione "writeHTMLCell()"
$descrizioneGiust = '<span style="text-align:justify;">'.$descrizione.'</span>';
$pdf->SetTextColor(0,0,0);
$pdf->SetFont($font, '', 12);
$pdf->writeHTMLCell(0, 0, '', '75', $descrizioneGiust, 0, 1, 0, true, '', true);

// ---------------------------------------------------------

// Chiude e genera il documento
ob_end_clean();
$pdf->Output('locandina-'.$idSeminario.'.pdf', 'I');

}
}

//============================================================+
// FINE FILE
//============================================================+

//============================================================+
//
// Realizzato da Luigigiuseppe Prosperi con la 
// libreria TCPDF: https://tcpdf.org/ di Nicola Asuni
// 
//============================================================+
?>
