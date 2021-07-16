<?php require_once('Connections/qsdatenbank.php'); 


mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst = "SELECT * FROM fehler  ORDER BY fehler.lokz, fehler.fkurz, fehler.farbeitsplatzgruppe";
$rst = mysql_query($query_rst, $qsdatenbank) or die(mysql_error());
$row_rst = mysql_fetch_assoc($rst);
$totalRows_rst = mysql_num_rows($rst);

include_once "Spreadsheet/Excel/Writer.php"; 
$xls =& new Spreadsheet_Excel_Writer(); 
$xls->send("Fehlerkurzzeichen.xls"); 
$format =& $xls->addFormat(); 
$format->setBold(); 
$format->setColor("blue"); 
$sheet =& $xls->addWorksheet("Kurzzeichen"); 
/* Überschrift */ 
$sheet->write(0, 0, "Kurzzeichen", $format);
 $sheet->write(0, 1, "Text", $format); 
 $sheet->write(0, 2, "Fehlleistungskostenpauschale bei diesem Fehler", $format);

/* Überschrift */ 
$i=0;
if ($totalRows_rst > 0) { // Show if recordset not empty 
do { 
$i++;
$sheet->writeString($i, 0, utf8_decode($row_rst['fkurz']), 0); 
$sheet->writeString($i, 1, utf8_decode($row_rst['fname']), 0); 
$sheet->writeString($i, 2, str_replace("?","€",utf8_decode($row_rst['fkosten']) ), 0); 

/* $sheet->writeString(1, 1, $_POST['value'], $format); */ 

} while ($row_rst = mysql_fetch_assoc($rst));
}
$sheet->writeString(1, 0, "keine Daten gefunden", 0); 


$xls->close();
exit;
?>