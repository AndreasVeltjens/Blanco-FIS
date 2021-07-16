<?php require_once('Connections/qsdatenbank.php'); 
/* require fauf.fid */
$dokumentart=utf8_decode("Barcode-Generator");

$autoprint=$autoprint;
if ($extentend=="1"){$onesite=false;} else {$onesite=true;}

$la = "pdf.barcodegenerator"; 
include("function.tpl.php");
/* load fauf.fid */
mysql_select_db($database_qsdatenbank, $qsdatenbank);
	$query_rstfauf = "	SELECT artikeldaten.Nummer, artikeldaten.Bezeichnung, fertigungsmeldungen.version , fertigungsmeldungen.fsn1, 
						fertigungsmeldungen.fsn2,fertigungsmeldungen.fsn3
						FROM artikeldaten, fertigungsmeldungen
						WHERE fertigungsmeldungen.fid='$url_fid' 
						AND fertigungsmeldungen.fartikelid= artikeldaten.artikelid";
	$rstfauf = mysql_query($query_rstfauf, $qsdatenbank) or die($globalfehler1.mysql_error().$globalfehler2);
	$row_rstfauf = mysql_fetch_assoc($rstfauf);
	$totalRows_rstfauf = mysql_num_rows($rstfauf);
/* load konfig for fauf.fid*


/* load fpdf api */
include ('./libraries/fpdf/fpdf.php');
/* load barcode api */
include "../src/jpgraph.php";
include "../src/jpgraph_canvas.php";
include "../src/jpgraph_barcode.php";

if ($autoprint==1){
		require('./libraries/fpdf/pdf_js.php');
		
		class PDF_AutoPrint extends PDF_JavaScript
		{
		function AutoPrint($dialog=false)
		{
			//Open the print dialog or start printing immediately on the standard printer
			$param=($dialog ? 'true' : 'false');
			$script="print($param);";
			$this->IncludeJS($script);
		}
		
		function AutoPrintToPrinter($server, $printer, $dialog=false)
		{
			//Print on a shared printer (requires at least Acrobat 6)
			$script = "var pp = getPrintParams();";
			if($dialog)
				$script .= "pp.interactive = pp.constants.interactionLevel.full;";
			else
				$script .= "pp.interactive = pp.constants.interactionLevel.automatic;";
			$script .= "pp.printerName = '\\\\\\\\".$server."\\\\".$printer."';";
			$script .= "print(pp);";
			$this->IncludeJS($script);
		}
		}
		$pdf =& new PDF_AutoPrint('P','mm','A4');
} else {
		$pdf = new FPDF();	
}
		$pdf->AliasNbPages();
		$pdf->AddPage();
/* create a Barcode */
		
		
			
	
		$data[0]= array("Bezeichnung",$row_rstfauf['Bezeichnung']);
		$data[1]= array("Materialnummer",$row_rstfauf['Nummer']);
		$data[2]= array("Version",str_replace("Version ","",$row_rstfauf['version']) );
		$data[3]= array("SN2",substr($row_rstfauf['fsn2'],0,7)," + SN eingeben");
		$data[4]= array("SN1",$row_rstfauf['fsn1']);
		$data[5]= array("SN3",$row_rstfauf['fsn3']);
		
		 
		

		$pdf->SetTitle($dokumentart."-".$row_rstfauf['fid']);
		$pdf->Ln();
		
		$pdf->Image('picture/logo'.$row_rst1['werk'].'.jpg',140,5,45,14,"","");
		$pdf->SetFont('Helvetica','',10);
		
		
		$y=0;
		$count=0;
		if (is_array($data)) {
			foreach ($data as $row ){
				$count++;
				if ((strlen($row[1])>0) &&  
					(strlen($row[1])<10) && 
					(!is_null($row[1])) &&
					($row[1]<>"") &&
					($row[1]<>" ") &&
					($row[1]<>"none")					
				){
					$url_nummer=$row[1];	
					include('picture/barcodeCode39.php'); 
					$pdf->Image('barcode/testbild'.$count.'.png',60,20+$y,80,30,"","");	
				} else {
					$pdf->Text(70,30+$y,$row[1]);
				}
				$pdf->Text(20,30+$y,$row[0]);
				$pdf->Text(150,30+$y,$row[2]);
				$y=$y+40;
			}
		} /* is_array */
		
		$pdf->SetFont('Helvetica','B',14);
		$pdf->Text(20,10,"Barcode Generator");
		
		$pdf->SetFont('Helvetica','',10);
		
		
		
	if ($autoprint==1){
		$pdf->AutoPrint();
		}
		$pdf->Output($dokumentart."-".$row_rst3['lfd_nr'].".pdf","I");
		exit;

?>