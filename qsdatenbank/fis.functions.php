<?php

// GetFehlermeldungAddonDataClass
// GetFehlermeldungAddonDataGroup


function GetFehlermeldungAddonDataGroup($type,$select){
$alloc=array(
	0=>"unbekannte Gruppe",
	1=>"Elektrische Werte",
	2=>"Mechanische Werte"
);
	switch ($type){
		case "text":{
		$data=$alloc[$select];
		break;
		}	
	}
return $data;
}

function GetFehlermeldungAddonDataClass($type,$select){
$alloc=array(
	0=>"unbekannte Messwert",
	1=>"Schutzleiterwiderstand",
	2=>"Isolationswiderstand",
	3=>"Kurzschlusstrom",
	4=>"Gewicht",
);
$allowed=array(
	0=>"",
	1=>"< 0,300 Ohm",
	2=>"> 1,0 MOhm",
	3=>"< 3,500 mA",
	4=>"",
);

$unit=array(
	0=>"",
	1=>"Ohm",
	2=>"MOhm",
	3=>"mA",
	4=>"kg",
);

	switch ($type){
		case "text":{
		$data=$alloc[$select];
		break;
		}	
		case "allowed":{
		$data=$allowed[$select];
		break;
		}
		case "unit":{
		$data=$unit[$select];
		break;
		}	
	}
return $data;
}

function GetUserName($userid){
	include('Connections/qsdatenbank.php');
	mysql_select_db($database_qsdatenbank, $qsdatenbank);
	$query_rst1 = "SELECT * FROM user WHERE user.id = '$userid'";
	$rst1 = mysql_query($query_rst1, $qsdatenbank) or die(mysql_error());
	$row_rst1 = mysql_fetch_assoc($rst1);
	$totalRows_rst1 = mysql_num_rows($rst1);
	if ($totalRows_rst1== 1 ){
		return $row_rst1['name'];
	}
}

function GetArtikelData($artikelid)
{
	include('Connections/qsdatenbank.php'); 
	mysql_select_db($database_qsdatenbank, $qsdatenbank);
	$query_rst = "SELECT * FROM artikeldaten WHERE artikelid='$artikelid'" ;
	$rst = mysql_query($query_rst, $qsdatenbank) or die(mysql_error());
	$row_rst = mysql_fetch_assoc($rst);
	if (mysql_num_rows($rst)==1) {	
		return $row_rst['Bezeichnung'];
	}else{
	 	return "keine Artikeldaten gefunden";
	}
}

function GetArtikelDataList()
{
	include('Connections/qsdatenbank.php'); 
	mysql_select_db($database_qsdatenbank, $qsdatenbank);
	$query_rst = "SELECT * FROM artikeldaten ORDER BY Kontierung, Gruppe" ;
	$rst = mysql_query($query_rst, $qsdatenbank) or die(mysql_error());
	$row_rst = mysql_fetch_assoc($rst);
	if (mysql_num_rows($rst)>0) {
			do{
				$data[$row_rst['artikelid'] ]=$row_rst['Bezeichnung']." ".$row_rst['Nummer'];
			}while ($row_rst = mysql_fetch_assoc($rst));
		return $data;
	}else{
	 	return "keine Artikeldaten gefunden";
	}
}


function GetProzessInfo($fmid)
{
	include('Connections/qsdatenbank.php'); 
	mysql_select_db($database_qsdatenbank, $qsdatenbank);
	$query_rst = "SELECT * FROM fehlermeldungsdaten WHERE fehlermeldungsdaten.fmid='$fmid' AND fehlermeldungsdaten.entid=1 ORDER BY fehlermeldungsdaten.varname" ;
	$rst = mysql_query($query_rst, $qsdatenbank) or die(mysql_error());
	$row_rst = mysql_fetch_assoc($rst);
	if (mysql_num_rows($rst)==1) {	
		return $row_rst['varname']. " ".$row_rst['varbeschreibung'];
	}else{
	 	return "noch keine Info gefunden";
	}
}

function GetProzessCosts($fmid)
{
	include('Connections/qsdatenbank.php'); 
	mysql_select_db($database_qsdatenbank, $qsdatenbank);
	$query_rst = "SELECT * FROM fehlermeldungsdaten WHERE fehlermeldungsdaten.fmid='$fmid' AND fehlermeldungsdaten.entid=1 ORDER BY fehlermeldungsdaten.varname" ;
	$rst = mysql_query($query_rst, $qsdatenbank) or die(mysql_error());
	$row_rst = mysql_fetch_assoc($rst);
	if (mysql_num_rows($rst)==1) {
		if  ($row_rst['Kosten1']=="-1"){
			return "kostenlos";
		}else{
			return $row_rst['Kosten1']. " EUR";
		}
	}else{
	 	return "offen";
	}
}



function GetDeviceLiveTime($fmid)
{
	include('Connections/qsdatenbank.php'); 
	mysql_select_db($database_qsdatenbank, $qsdatenbank);
	$query_rst = "SELECT * FROM fehlermeldungen WHERE fehlermeldungen.fmid='$fmid' " ;
	$rst = mysql_query($query_rst, $qsdatenbank) or die(mysql_error());
	$row_rst = mysql_fetch_assoc($rst);
	if (mysql_num_rows($rst)==1) {
			$r=sprintf("%01.0f",(strtotime($row_rst['fmdatum'])-strtotime($row_rst['fmfd']))  / (60*60*24*30) );
			$str=sprintf("%01.1f", ($r/12))." Jahre ";
			return $str;
	}else{
	 	return "keine Daten gefunden";
	}
}


?>