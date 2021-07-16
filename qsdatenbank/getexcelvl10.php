<?php require_once('Connections/qsdatenbank.php'); 



mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT * FROM auslieferungen WHERE auslieferungen.typ = 1 and auslieferungen.avis> 0 ORDER BY auslieferungen.bezeichnung asc";
$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);

include_once "Spreadsheet/Excel/Writer.php"; 
$xls =& new Spreadsheet_Excel_Writer(); 
$xls->send("Lieferavis vom ".date("Y-m-d",time()).".xls"); 
$format =& $xls->addFormat(); 
$format->setBold(); 
$format->setColor("blue"); 
$sheet =& $xls->addWorksheet(date("Y-m-d",time())." FERTIGWARE"); 
/* Überschrift */ 
$sheet->write(0, 0, "Bezeichnung", $format);
 $sheet->write(0, 1, "Kd-Material", $format); 
 $sheet->write(0, 2, "Liefermenge", $format);
 $sheet->write(0, 3, "Palettenanzahl", $format);
 /* ende 
Überschrift */ 
$i=0;
 if ($totalRows_rst2 > 0) { // Show if recordset not empty 
do { 
$i=$i+1; 
$sheet->writeString($i, 0, $row_rst2['bezeichnung'], 0); 
$sheet->writeString($i, 1, $row_rst2['kd_nummer'], 0); 
$sheet->writeString($i, 2, $row_rst2['avis'],0); 
$sheet->writeString($i, 3, $row_rst2['palettenL'], 0); 

$summeavis=$summeavis+$row_rst2['avis'];
$summepalettenL=$summepalettenL+$row_rst2['palettenL'];

/* $sheet->writeString(1, 1, $_POST['value'], $format); */ 

} while ($row_rst2 = mysql_fetch_assoc($rst2)); 
} // Show if recordset not empty $xls->close(); exit; 
$sheet->write($i+2, 0, "Summe Menge", $format);
$sheet->writeString($i+2, 1,$summeavis, 0);
$sheet->write($i+3, 0, "Summe Paletten", $format);
$sheet->writeString($i+3, 1,$summepalettenL, 0);




$xls->close();
	exit;
?>