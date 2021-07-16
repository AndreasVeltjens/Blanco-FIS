<?php require_once('Connections/qsdatenbank.php'); 

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT komponenten.kompnr, komponenten.anzahl, komponenten.lagerort, komponenten_vorlage.kompname FROM komponenten, komponenten_vorlage WHERE komponenten.lokz=0 AND komponenten.lagerort<>'Sond' AND komponenten.kompnr=komponenten_vorlage.kompnr AND komponenten.id_fm=$url_fmid";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);

mysql_select_db($database_qsdatenbank, $qsdatenbank); $query_rst2 = "SELECT fehlermeldungen.fmid, fehlermeldungen.fm4notes, 
fehlermeldungen.sappcna FROM fehlermeldungen WHERE fehlermeldungen.fmid=$url_fmid"; 
$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error()); 
$row_rst2 = mysql_fetch_assoc($rst2); 
$totalRows_rst2 = mysql_num_rows($rst2); 

include_once "Spreadsheet/Excel/Writer.php"; 
$xls =& new Spreadsheet_Excel_Writer(); 
$xls->send($row_rst2['sappcna'].".xls"); 
$format =& $xls->addFormat(); 
$format->setBold(); 
$format->setColor("blue"); 
$sheet =& $xls->addWorksheet($row_rst2['sappcna']."XLS"); 
/* Überschrift */ 
$sheet->write(0, 0, "Material", $format);
 $sheet->write(0, 1, "Anzahl", $format); 
 $sheet->write(0, 2, "Einheit", $format);
 $sheet->write(0, 3, "Werk", $format);
 $sheet->write(0, 4, "Lagerort", $format);
  $sheet->write(0, 5, "Komponentenname", $format); /* ende 
Überschrift */ 
$i=0;
 if ($totalRows_rst1 > 0) { // Show if recordset not empty 
do { 
$i=$i+1; 
$sheet->writeString($i, 0, $row_rst1['kompnr'], 0); 
$sheet->writeString($i, 1, $row_rst1['anzahl'], 0); 
$sheet->writeString($i, 2, "ST",0); 
$sheet->writeString($i, 3, "6101", 0); 
$sheet->writeString($i, 4, $row_rst1['lagerort'], 0); 
$sheet->writeString($i, 5, $row_rst1['kompname'], 0); 

/* $sheet->writeString(1, 1, $_POST['value'], $format); */ 

} while ($row_rst1 = mysql_fetch_assoc($rst1)); 
} // Show if recordset not empty $xls->close(); exit; 
$sheet->write($i+2, 0, "Arbeitszeit", $format);
$sheet->writeString($i+2, 1,$row_rst2['fm4notes'], 0);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst1 = "SELECT komponenten.kompnr, komponenten.anzahl, komponenten.lagerort, komponenten_vorlage.kompname FROM komponenten, komponenten_vorlage WHERE komponenten.lokz=0 AND komponenten.lagerort='Sond' AND komponenten.kompnr=komponenten_vorlage.kompnr AND komponenten.id_fm=$url_fmid";
$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
$row_rst1 = mysql_fetch_assoc($rst1);
$totalRows_rst1 = mysql_num_rows($rst1);

/* Überschrift */ 
$sheet->write($i+9, 0, "Materialverwendung ohne rückmeldepflichtige Komponenten / Retourenbestand", $format);
$sheet->write($i+10, 0, "Material", $format);
 $sheet->write($i+10, 1, "Anzahl", $format); 
 $sheet->write($i+10, 2, "Einheit", $format);
 $sheet->write($i+10, 3, "Werk", $format);
 $sheet->write($i+10, 4, "Lagerort", $format);
  $sheet->write($i+10, 5, "Komponentenname", $format); /* ende 
Überschrift */ 
$i=$i+11;
 if ($totalRows_rst1 > 0) { // Show if recordset not empty 
do { 
$i=$i+1; 
$sheet->writeString($i, 0, $row_rst1['kompnr'], 0); 
$sheet->writeString($i, 1, $row_rst1['anzahl'], 0); 
$sheet->writeString($i, 2, "ST",0); 
$sheet->writeString($i, 3, "6101", 0); 
$sheet->writeString($i, 4, $row_rst1['lagerort'], 0); 
$sheet->writeString($i, 5, $row_rst1['kompname'], 0); 

/* $sheet->writeString(1, 1, $_POST['value'], $format); */ 

} while ($row_rst1 = mysql_fetch_assoc($rst1)); 
} // Show if recordset not empty $xls->close(); exit; 



$xls->close();
	exit;
?>