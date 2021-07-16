<?php 
require_once('Connections/qsdatenbank.php');
include ("../src/jpgraph.php");
include ("../src/jpgraph_line.php");
include ("../src/jpgraph_bar.php");

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT date_format(fehlermeldungen.fm1datum,\"%m/%y\") as fehlerdatum, count(fehlermeldungen.fmid) as anzahl FROM fehlermeldungen GROUP BY date_format(fehlermeldungen.fm1datum,\"%Y %M\") ORDER BY fehlermeldungen.fm1datum desc limit 0,24";
$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);

$i=$totalRows_rst2-1;

do{

$l1datay[$i]=round($row_rst2['anzahl'],1);
$datax[$i]=$row_rst2['fehlerdatum'];
$i=$i-1;
} while ($row_rst2 = mysql_fetch_assoc($rst2) or $i=0);
/* $datax=$gDateLocale->GetShortMonth(); */

// Create the graph. 
$graph = new Graph(730,500,"auto");    
 // Set a nice summer (in Stockholm) image
$graph->SetBackgroundImage('picture/vis.jpg',BGIMG_COPY);
$graph->SetScale("textlin");
$graph->SetMargin(50,50,50,50);
$graph->SetShadow();
$graph->xaxis->SetTickLabels($datax);
// Adjust the position of the legend box
$graph->legend->Pos(0.10,0.01);
// Create the linear error plot
$cplot=new BarPlot($l1datay);
$cplot->SetFillColor("blue");
$cplot->SetLegend(("Anzahl der Retoureneingänge pro Monat"));



// Display the values on top of each bar
$cplot->SetShadow();
$cplot->value->SetFormat("   %2.0f",70);
$cplot->value->SetFont(FF_ARIAL,FS_NORMAL,8);
$cplot->value->SetColor("blue");
$cplot->value->Show();

// Add the plots to t'he graph

$gbarplot = new GroupBarPlot(array($cplot));
$gbarplot->SetWidth(0.6);
$graph->Add($gbarplot);

$graph->title->Set(utf8_decode($row_rst3['name']));
/* $graph->xaxis->title->Set( utf8_decode("Monat")); */
$graph->yaxis->title->SetFont(FF_ARIAL,FS_NORMAL,6);
$graph->yaxis->title->Set( utf8_decode("Retoureneingang"));

$graph->title->SetFont(FF_ARIAL,FS_BOLD);
$graph->yaxis->title->SetFont(FF_ARIAL);
$graph->xaxis->title->SetFont(FF_ARIAL);

// Setup X-axis labels
$graph->xaxis->SetTickLabels($datax);
$graph->xaxis->SetLabelAngle(60);
$graph->xaxis->SetFont(FF_ARIAL,FS_NORMAL,8);
$graph->yaxis->SetFont(FF_ARIAL,FS_NORMAL,8);


// Display the graph
$graph->Stroke();
?>