<?php
//=============================================================================
// File:	ODOEX05.PHP
// Description: Example 1 for odometer graphs
// Created:	2002-02-22
// Version:	$Id$
// 
// Comment:
// Example file for odometer graph. This examples extends odoex04
// by 1) changing color of odometer canvas 2) Adding a second indicator
// needle 3) making the tick marks thicker 4) and finally adding a short
// scale text in the middle of the odometer.
//
// Copyright (C) 2002 Johan Persson. All rights reserved.
//=============================================================================



/* Datenbankselection  */
require_once('Connections/qsdatenbank.php');

$maxRows_rst2 = 1;
$pageNum_rst2 = 0;
if (isset($HTTP_GET_VARS['pageNum_rst2'])) {
  $pageNum_rst2 = $HTTP_GET_VARS['pageNum_rst2'];
}
$startRow_rst2 = $pageNum_rst2 * $maxRows_rst2;

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT * FROM pruefungen WHERE id_pruef='$url_id_pruef' ";
$query_limit_rst2 = sprintf("%s LIMIT %d, %d", $query_rst2, $startRow_rst2, $maxRows_rst2);
$rst2 = mysql_query($query_limit_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);

if (isset($HTTP_GET_VARS['totalRows_rst2'])) {
  $totalRows_rst2 = $HTTP_GET_VARS['totalRows_rst2'];
} else {
  $all_rst2 = mysql_query($query_rst2);
  $totalRows_rst2 = mysql_num_rows($all_rst2);
}
$totalPages_rst2 = ceil($totalRows_rst2/$maxRows_rst2)-1;




$queryString_rst2 = "";
if (!empty($HTTP_SERVER_VARS['QUERY_STRING'])) {
  $params = explode("&", $HTTP_SERVER_VARS['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rst2") == false && 
        stristr($param, "totalRows_rst2") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rst2 = "&" . implode("&", $newParams);
  }
}
$queryString_rst2 = sprintf("&totalRows_rst2=%d%s", $totalRows_rst2, $queryString_rst2);

 
  
mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst3 = "SELECT * FROM pruefungdaten WHERE pruefungdaten.id_pruef=$row_rst2[id_pruef] and pruefungdaten.datum >=now()-interval $row_rst2[intervall] day AND pruefungdaten.lokz =0";
$rst3 = mysql_query($query_rst3, $qsdatenbank) or die(mysql_error());
$row_rst3 = mysql_fetch_assoc($rst3);
$totalRows_rst3 = mysql_num_rows($rst3);
  

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst6 = "SELECT pruefungdaten.datum, pruefungdaten.messwert1, pruefungdaten.messwert2, pruefungdaten.messwert3, pruefungdaten.messwert4, pruefungdaten.messwert5, pruefungdaten.lokz, pruefungdaten.id_pruef FROM pruefungdaten WHERE pruefungdaten.id_pruef=$row_rst2[id_pruef] AND pruefungdaten.lokz =0 ORDER BY pruefungdaten.datum asc";
$rst6 = mysql_query($query_rst6, $qsdatenbank) or die(mysql_error());
$row_rst6 = mysql_fetch_assoc($rst6);
$totalRows_rst6 = mysql_num_rows($rst6);

/* wie wird eder Istwert berechnet */
do{
			if ($row_rst2['formelid']==1){
						$ist=($row_rst6['messwert4']*$row_rst2['Umrechnungsfaktor'])/($row_rst6['messwert1']* $row_rst6['messwert2']*$row_rst6['messwert3']);
					}else{
						$ist=$row_rst6['messwert1']*$row_rst2['Umrechnungsfaktor'];
					}
} while ($row_rst6 = mysql_fetch_assoc($rst6));
  $ist=round($ist,1);
  
/* ende datenbank und berechnung IST-Wert */


include ("../src/jpgraph.php");
include ("../src/jpgraph_odo.php");

//---------------------------------------------------------------------
// Create a new odometer graph (width=250, height=200 pixels)
//---------------------------------------------------------------------
$graph = new OdoGraph(250,200);

//---------------------------------------------------------------------
// Change the color of the odometer plotcanvas. NOT the odometer
// fill color itself.
//---------------------------------------------------------------------


if (($ist>=$row_rst2['ugw']) && ($ist<=$row_rst2['ogw'])) {
$graph->SetColor("green");
}

if ( (($ist>=$row_rst2['ueg'])&& ($ist<=$row_rst2['ugw'])) or (($ist>=$row_rst2['ogw'])&& ($ist<=$row_rst2['oeg'])) ){
$graph->SetColor("yellow");
}
if (($ist>$row_rst2['ogw'])&& ($ist<$row_rst2['ueg'])) {
$graph->SetColor("red");
}
//---------------------------------------------------------------------
// Specify title and subtitle using default fonts
// * Note each title may be multilines by using a '\n' as a line
// divider.
//---------------------------------------------------------------------
$graph->title->Set($row_rst2['name']);
$graph->title->SetColor("white");

if ($totalRows_rst3 > 0) { // Datum überschritten Show if recordset not empty 
    $graph->subtitle->Set($row_rst6['datum']);
	$graph->subtitle->SetColor("red");   
} // Show if recordset not empty 
if ($totalRows_rst3 == 0) { // Show if recordset empty 
	$graph->subtitle->Set($row_rst6['datum']);
	$graph->subtitle->SetColor("green"); 
} // Show if recordset empty 


//---------------------------------------------------------------------
// Specify caption.
// * (This is the text at the bottom of the graph.) The margins will
// automatically adjust to fit the height of the text. A caption
// may have multiple lines by including a '\n' character in the 
// string.
//---------------------------------------------------------------------
$graph->caption->Set("Masse in x10 g\nfreigeschäumt mit Probeschußprogramm");
$graph->caption->SetColor("white");

//---------------------------------------------------------------------
// Now we need to create an odometer to add to the graph.
// By default the scale will be 0 to 100
//---------------------------------------------------------------------
$odo = new Odometer(); 

//---------------------------------------------------------------------
// Set color indication 
//---------------------------------------------------------------------

$b1=$row_rst2['ueg']/10;
$b2=$row_rst2['ugw']/10;
$b3=$row_rst2['ogw']/10;
$b4=$row_rst2['oeg']/10;




$odo->AddIndication(0,$b1,"red");
$odo->AddIndication($b1,$b2,"yellow");
$odo->AddIndication($b2,$b3,"green");
$odo->AddIndication($b3,$b4,"yellow");
$odo->AddIndication($b4,100,"red");

//---------------------------------------------------------------------
// Set the center area that will not be affected by the color bands
//---------------------------------------------------------------------
$odo->SetCenterAreaWidth(0.4);  // Fraction of radius

//---------------------------------------------------------------------
// Adjust scale ticks to be shown at 10 steps interval and scale
// labels at every second tick
//---------------------------------------------------------------------
$odo->scale->SetTicks(10,1);

//---------------------------------------------------------------------
// Make the tick marks 2 pixel wide
//---------------------------------------------------------------------
$odo->scale->SetTickWeight(2);

//---------------------------------------------------------------------
// Use a bold font for tick labels
//---------------------------------------------------------------------
$odo->scale->label->SetFont(FF_FONT1, FS_BOLD);

//---------------------------------------------------------------------
// Set display value for the odometer
//---------------------------------------------------------------------
$odo->needle->Set($ist/10);

//---------------------------------------------------------------------
// Specify scale caption. Note that depending on the position of the
// indicator needle this label might be partially hidden. 
//---------------------------------------------------------------------
$odo->label->Set("IST Masse");

//---------------------------------------------------------------------
// Set a new style for the needle
//---------------------------------------------------------------------
$odo->needle->SetStyle(NEEDLE_STYLE_MEDIUM_TRIANGLE);
$odo->needle->SetLength(0.9);  // Length as 70% of the radius
$odo->needle->SetFillColor("black");

//---------------------------------------------------------------------
// Setup the second indicator needle
//---------------------------------------------------------------------
$odo->needle2->Set($ist/10);
$odo->needle2->SetStyle(NEEDLE_STYLE_SMALL_TRIANGLE);
$odo->needle2->SetLength(0.55);  // Length as 70% of the radius
$odo->needle2->SetFillColor("lightgray");
$odo->needle2->Show();  


//---------------------------------------------------------------------
// Add the odometer to the graph
//---------------------------------------------------------------------
$graph->Add($odo);

//---------------------------------------------------------------------
// ... and finally stroke and stream the image back to the browser
//---------------------------------------------------------------------
$graph->Stroke();

// EOF
?>