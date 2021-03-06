<?php 
require_once('Connections/qsdatenbank.php');
include ("../src/jpgraph.php");
include ("../src/jpgraph_line.php");
include ("../src/jpgraph_bar.php");
/* Definition Kennzahlteilung und Anzahl der selektierten Datensätze der Kennzahl */
$delta=12;
$delta2=$delta*2;

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rst2 = "SELECT * FROM kennzahlen_daten WHERE kennzahlen_daten.id_kz='$url_id_kz' ORDER BY kennzahlen_daten.wertx desc LIMIT 0,$delta2";
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


if ($i>=$delta){
$l1datay[$i-$delta]=round($row_rst2['wert1y'],1);
if (!isset($l1datay[$i-$delta])){$l1datay[$i-$delta]=0;}
$l3datay[$i-$delta]=round($row_rst2['wert2y'],1);
if (!isset($l3datay[$i-$delta])){$l3datay[$i-$delta]=0;}
$datax[$i-$delta]=substr($row_rst2['wertx'],0,7);
}else{
if (!isset($l1datay[1])){$l1datay[1]=0;}
$l2datay[$i]=round($row_rst2['wert1y'],1);
if (!isset($l3datay[1])){$l3datay[1]=0;}
$l4datay[$i]=round($row_rst2['wert2y'],1);
}
$l5datay[$i]=round($row_rst2['wert3y'],1);
if (!isset($datax[$i])&& $i<$delta){$datax[$i]=substr($row_rst2['wertx'],0,7);}
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
$l1plot=new LinePlot($l3datay);
$l1plot->SetColor("red");
$l1plot->SetWeight(3);
$l1plot->SetLegend("Zielvorgabe");

//Center the line plot in the center of the bars
$l1plot->SetBarCenter();
$graph->Add($l1plot);


// Create the bar plot
$cplot=new BarPlot($l1datay);
$cplot->SetFillColor("blue");
$cplot->SetLegend(utf8_decode($row_rst3['yachse']));
$bplot = new BarPlot($l2datay);
// This is how you make the bar graph start from something other than 0
$bplot->SetFillColor("blue@0.9");
$bplot->SetLegend(utf8_decode($row_rst3['yachse']));
// Display the values on top of each bar
$bplot->SetShadow();
$bplot->value->SetFormat("   %2.1f",70);
$bplot->value->SetFont(FF_ARIAL,FS_NORMAL,8);
$bplot->value->SetColor("blue@0.5");
$bplot->value->Show();
// Display the values on top of each bar
$cplot->SetShadow();
$cplot->value->SetFormat("   %2.1f",70);
$cplot->value->SetFont(FF_ARIAL,FS_NORMAL,8);
$cplot->value->SetColor("blue");
$cplot->value->Show();

// Create the bar plot
$eplot=new BarPlot($l3datay);
$eplot->SetFillColor("orange");
$eplot->SetLegend(utf8_decode($row_rst3['yachse2']));
$dplot = new BarPlot($l4datay);
// This is how you make the bar graph start from something other than 0
$dplot->SetFillColor("orange@0.9");
$dplot->SetLegend(utf8_decode($row_rst3['yachse2']));
// Display the values on top of each bar
$dplot->SetShadow();
$dplot->value->SetFormat("   %2.1f",70);
$dplot->value->SetFont(FF_ARIAL,FS_NORMAL,8);
$dplot->value->SetColor("orange@0.5");
$dplot->value->Show();
// Display the values on top of each bar
$eplot->SetShadow();
$eplot->value->SetFormat("   %2.1f",70);
$eplot->value->SetFont(FF_ARIAL,FS_NORMAL,8);
$eplot->value->SetColor("orange");
$eplot->value->Show();


// Add the plots to t'he graph

$gbarplot = new GroupBarPlot(array($bplot,$cplot,$dplot,$eplot));
$gbarplot->SetWidth(0.6);
$graph->Add($gbarplot);

$graph->title->Set(utf8_decode($row_rst3['name']));
$graph->xaxis->title->Set( utf8_decode($row_rst3['xachse']));
$graph->yaxis->title->SetFont(FF_ARIAL,FS_NORMAL,6);
$graph->yaxis->title->Set( utf8_decode($row_rst3['yachse'])." - ".utf8_decode($row_rst3['yachse2'] ));

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