<?php  require_once('Connections/qsdatenbank.php'); 
		include ('./libraries/fpdf/fpdf.php');
/* lade Artikeldaten */	
		$diff=0;
		$aaa=0;
		$iii=0;
/* lade Sourcedateien f�r Barcode */	
		include "../src/jpgraph.php";
		include "../src/jpgraph_canvas.php";
		include "../src/jpgraph_barcode.php";
/* lade Template f�r Layout */
		include ('./pdf.HSN.php');	
/* Export */		
		$pdf->Output();
		exit;
?>
<?php
mysql_free_result($rst1);
?>