<?php 
require_once('Connections/qsdatenbank.php');
include ("../src/jpgraph.php");
include ("../src/jpgraph_line.php");
include ("../src/jpgraph_bar.php");

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT * FROM kennzahlen_daten WHERE kennzahlen_daten.id_kz='$url_id_kz' ORDER BY kennzahlen_daten.wertx desc LIMIT 0,52";
$rst2 = mysql_query($query_rst2, $qsdatenbank) or die(mysql_error());
$row_rst2 = mysql_fetch_assoc($rst2);
$totalRows_rst2 = mysql_num_rows($rst2);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst3 = "SELECT * FROM kennzahlen WHERE kennzahlen.id_kz='$url_id_kz'";
$rst3 = mysql_query($query_rst3, $qsdatenbank) or die(mysql_error());
$row_rst3 = mysql_fetch_assoc($rst3);
$totalRows_rst3 = mysql_num_rows($rst3);

$i=$totalRows_rst2-1;
$www=0;
do{
$www=$www+1;
			$l1datay[$i]=round($row_rst2['wert2y'],1);
			$l2datay[$i]=round($row_rst2['wert1y'],1);
			$l3datay[$i]=round($row_rst2['wert3y'],1);
			$l4datay[$i]=round($row_rst2['wert4y'],1);
			$l5datay[$i]=round($row_rst2['wert1y']+$row_rst2['wert2y']+$row_rst2['wert3y']+$row_rst2['wert4y'],1);
			$datax[$i]=$row_rst2['wertx'];


	$i=$i-1;
} while ($row_rst2 = mysql_fetch_assoc($rst2) or $i=0);
/* $datax=$gDateLocale->GetShortMonth(); */

$i=0;
do {
	$l5datay[$i]=$l5datay[$i-1] + $l5datay[$i];
	$i=$i+1;
}while ($i<= $totalRows_rst2);


// Create the graph. 
$graph = new Graph(730,500,"auto");    
 // Set a nice summer (in Stockholm) image
$graph->SetBackgroundImage('picture/vis.jpg',BGIMG_COPY);
$graph->SetScale("textlin");
$graph->SetMargin(50,50,50,50);
$graph->SetShadow();
$graph->xaxis->SetTickLabels($datax);
// Adjust the position of the legend box
$graph->legend->Pos(0.5,0.8);
$graph->legend->SetFont(FF_ARIAL);


// Create the bar plot
$bplot = new BarPlot($l2datay);
$bplot->SetFillColor("green");
$bplot->SetLegend(utf8_decode($row_rst3['yachse']));
// Create the bar plot
$cplot = new BarPlot($l1datay);
$cplot->SetFillColor("red");
$cplot->SetLegend(utf8_decode($row_rst3['yachse2']));



// Create the bar plot
$dplot = new BarPlot($l3datay);
$dplot->SetFillColor("red@0.5");
$dplot->SetLegend(utf8_decode($row_rst3['yachse3']));

// Create the bar plot
$eplot = new BarPlot($l4datay);
$eplot->SetFillColor("red@0.8");
$eplot->SetLegend(utf8_decode($row_rst3['yachse4']));

// Display the values on top of each bar
$bplot->SetShadow();

$bplot->value->SetFont(FF_ARIAL,FS_NORMAL,8);
$bplot->value->SetColor("black");
$bplot->value->Show();
// Display the values on top of each bar
$cplot->SetShadow();

$cplot->value->SetFont(FF_ARIAL,FS_NORMAL,8);
$cplot->value->SetColor("black");
$cplot->value->Show();

// Display the values on top of each bar
$dplot->SetShadow();

$dplot->value->SetFont(FF_ARIAL,FS_NORMAL,8);
$dplot->value->SetColor("black");
$dplot->value->Show();

// Display the values on top of each bar
$eplot->SetShadow();

$eplot->value->SetFont(FF_ARIAL,FS_NORMAL,8);
$eplot->value->SetColor("black");
$eplot->value->Show();

// Add the plots to t'he graph

$gbarplot = new AccBarPlot(array($bplot,$cplot,$dplot,$eplot));
$gbarplot->SetWidth(0.6);
$graph->Add($gbarplot);

// Create the linear error plot
$l1plot=new LinePlot($l5datay);
$l1plot->SetColor("blue");
$l1plot->SetWeight(5);
$l1plot->SetLegend("Cashflow");
$l1plot->value->SetFormat("   %2.0f");
$l1plot->value->SetFont(FF_ARIAL,FS_NORMAL,8);
$l1plot->value->SetColor("blue");
$l1plot->value->Show();


//Center the line plot in the center of the bars
$l1plot->SetBarCenter();

$graph->Add($l1plot);

$graph->title->Set(utf8_decode($row_rst3['name']));

$graph->yaxis->title->SetFont(FF_ARIAL,FS_NORMAL,6);
$graph->yaxis->title->Set( utf8_decode($row_rst3['yachse2'])." - ".utf8_decode($row_rst3['yachse'] ));
$graph->yaxis->SetFont(FF_ARIAL,FS_NORMAL,8);
// Setup X-axis labels
$graph->xaxis->SetTickLabels($datax);
$graph->xaxis->SetLabelAngle(60);
$graph->xaxis->SetFont(FF_ARIAL,FS_NORMAL,8);

$graph->xaxis->title->Set( utf8_decode($row_rst3['xachse']));

$graph->title->SetFont(FF_ARIAL,FS_BOLD);
$graph->yaxis->title->SetFont(FF_ARIAL);



// Display the graph
$graph->Stroke();
?>