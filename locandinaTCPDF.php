 <?php
/*
//Controlla i campi del form e li assegna a variabili
if (!isset($_POST["formLocandina"])){
    echo "Modulo non compilato correttamente!";
    } else {
            $nome = $_POST["nome"];
            $cognome = $_POST["conome"];
            $titolo = $_POST["seminario"];
        }; //chiudere il ciclo alla fine 

*/

$nome = $_POST["nome"];
$cognome = $_POST["cognome"];
$titolo = $_POST["seminario"];
$data = $_POST["data"];
$orario_inizio = $_POST["orario_inizio"];
$orario_fine = $_POST["orario_fine"];
$descrizione = $_POST["descrizione"];


// Include the main TCPDF library (search for installation path).
require_once('./tcpdf/tcpdf.php');

// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

	//Page header
	public function Header() {
		// Logo SX
		$logo_UdA = K_PATH_IMAGES.'logo_UdA.jpg';
		$this->Image($logo_UdA, 10, 10, 0, 25, 'JPG', '', 'M', false, 300, 'L', false, false, 0, false, false, false);
		// Imposta font
		$this->SetFont('helvetica', 'B', 12);
		// Imposta colore di riempimento della cella
		$this->SetFillColor(173, 216, 230);
		// Testo della cella
		$this->Cell(105, 12, 'UNIVERSITÀ DEGLI STUDI “G. D’ANNUNZIO” DI CHIETI - PESCARA', 0, 2, 'C', true, '', 2, false, 'C', 'C');
		$this->SetFont('helvetica', 'I', 8);
		$this->Cell(105, 7, 'Scuola delle Scienze Economiche, Aziendali, Giuridiche e Sociologiche - Dipartimento di Economia', 0, 0, 'C', true, '', 2, false, 'C', 'C');
		// Logo DX
		$logo_DEC = K_PATH_IMAGES.'logo_DEC.jpg';
		$this->Image($logo_DEC, 10, 10, 0, 12, 'JPG', '', 'N', false, 300, 'R', false, false, 0, false, false, false);
		
	}

	// Page footer
	public function Footer() {
		
		// Position at 15 mm from bottom
		$this->SetY(-15);
		// Set font
		$this->SetFont('helvetica', 'I', 6);
		// Imposta il colore di sfondo della cella
		$this->SetFillColor(173, 216, 230);
		// Testo del footer
		$this->Cell(0, 10, "Università Degli Studi “G. D’annunzio” Di Chieti - Pescara\nSede di Chieti: Via dei Vestini,31 - Sede di Pescara: Viale Pindaro,42 - www.unich.it", 0, false,'C', true, 'www.unich.it', 0, false, 'T', 'M');
	}
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator('UniCH');
$pdf->SetAuthor("$nome $cognome");
$pdf->SetTitle("$titolo");
$pdf->SetSubject('Locandina Seminario');
$pdf->SetKeywords('UdA, UniCH, Seminario, Università Chieti-Pescara');

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/ita.php')) {
    require_once(dirname(__FILE__).'/lang/ita.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set default font subsetting mode
$pdf->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
$pdf->SetFont('dejavusans', '', 14, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();

// set text shadow effect
//$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

//Stampa titolo seminario
$pdf->Ln(18);
$pdf->SetFont('helvetica', 'B', 20);
$pdf->Cell(0, 0, "Seminario: $titolo", 0, 0, 'C', false, '', 0, false, 'C', 'C');

//Stampa giorno e orario
$pdf->Ln(8);
$pdf->SetFont('helvetica', '', 15);
$pdf->Cell(0, 0, "Il seminario si terrà il giorno: $data dalle $orario_inizio alle $orario_fine", 0, 0, 'C', false, '', 0, false, 'C', 'C');

// Set some content to print
/*$pdf->SetFont('times', '', 12);
$html = <<<EOD
EOD;*/

// Stampa il contenuto di $descrizione utilizzando la function writeHTMLCell()
$pdf->SetFont('helvetica', '', 15);
$pdf->writeHTMLCell(0, 0, '', '65', $descrizione, 0, 1, 0, true, '', true);

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
ob_end_clean();
$pdf->Output('locandina.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+

//============================================================+
//
// Realizzato con TCPDF: https://tcpdf.org/ di Nicola Asuni
// 
//============================================================+
