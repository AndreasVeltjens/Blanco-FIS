<?php 
require_once('Connections/qsdatenbank.php');
include ("../src/jpgraph.php");
include ("../src/jpgraph_line.php");
include ("../src/jpgraph_bar.php");

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT * FROM kennzahlen_daten WHERE kennzahlen_daten.id_kz='$url_id_kz' ORDER BY kennzahlen_daten.wertx desc LIMIT 0,12";
$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst3 = "SELECT * FROM kennzahlen WHERE kennzahlen.id_kz='$url_id_kz'";
$rst3 = mysql_query($query_rst3, $qsdatenbank) or die(mysql_error());
$row_rst3 = mysql_fetch_assoc($rst3);
$totalRows_rst3 = mysql_num_rows($rst3);

$i=$totalRows_rst2-1;

do{

$l1datay[$i]=round($row_rst2['wert2y'],1);
$l2datay[$i]=round($row_rst2['wert1y'],1);
$datax[$i]=$row_rst2['wertx'];
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
$graph->legend->Pos(0.05,0.01);
$graph->legend->SetFont(FF_ARIAL);
// Create the linear error plot
$l1plot=new LinePlot($l1datay);
$l1plot->SetColor("red");
$l1plot->SetWeight(3);
$l1plot->SetLegend("Zielvorgabe");

//Center the line plot in the center of the bars
$l1plot->SetBarCenter();


// Create the bar plot
$bplot = new BarPlot($l2datay);
$bplot->SetFillColor("blue");
$bplot->SetLegend("IST-".utf8_decode($row_rst3['name']));

// Display the values on top of each bar
$bplot->SetShadow();
$bplot->value->SetFormat("   %2.1f",70);
$bplot->value->SetFont(FF_ARIAL,FS_NORMAL,8);
$bplot->value->SetColor("blue");
$bplot->value->Show();

// Add the plots to t'he graph
$graph->Add($bplot);
$graph->Add($l1plot);


$graph->title->Set(utf8_decode($row_rst3['name']));
$graph->xaxis->title->Set( utf8_decode($row_rst3['xachse']));
$graph->yaxis->title->SetFont(FF_ARIAL,FS_NORMAL,6);
$graph->yaxis->title->Set( utf8_decode($row_rst3['yachse']));

$graph->title->SetFont(FF_ARIAL,FS_BOLD);
$graph->yaxis->title->SetFont(FF_ARIAL);
// Setup X-axis labels
$graph->xaxis->SetTickLabels($datax);
$graph->xaxis->SetLabelAngle(60);
$graph->xaxis->SetFont(FF_ARIAL,FS_NORMAL,8);
$graph->yaxis->SetFont(FF_ARIAL,FS_NORMAL,8);



// Display the graph
$graph->Stroke();
?>