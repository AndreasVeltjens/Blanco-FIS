<?php 

if (isset($_POST['selectorder'])) {
	if ($_POST['selectorder']==1){
	$order=" fehlermeldungen.fmartikelid, fehlermeldungen.fmsn, fehlermeldungen.fmid ASC";
	} else {
	$order=" fehlermeldungen.fmid DESC";
	}
}else{
	$order=" fehlermeldungen.fmid DESC";
}


if (isset($_POST['selectland']) && $_POST['selectland']!="0" && $_POST['selectland']!="") {
								$listx=", kundendaten ";
								$landauswahlx="( kundendaten.idk=fehlermeldungen.idk or  kundendaten.idk=fehlermeldungen.idk2
								 				AND (fehlermeldungen.idk>0 OR fehlermeldungen.idk2>0) AND kundendaten.land='".$_POST['selectland']."' ) AND";
								}

if (isset($_POST['selectidk']) && $_POST['selectidk']!="0" && $_POST['selectland']!="") {
								$listx=", kundendaten ";
								$landauswahlx="( kundendaten.idk=fehlermeldungen.idk AND (fehlermeldungen.idk='".$_POST['selectidk']."'  or fehlermeldungen.idk2='".$_POST['selectidk']."') 
												AND kundendaten.idk=fehlermeldungen.idk AND kundendaten.idk=fehlermeldungen.idk2)  AND";
								}
								
if (isset($_POST['selectartikelid']) && intval($_POST['selectartikelid'])>0) {
	$filterbyartikelid=" AND fehlermeldungen.fmartikelid='".$_POST['selectartikelid']."' ";
}else{
	$filterbyartikelid="";
}

if (isset($suchtext)) {$_POST["suchtext"]=$suchtext;}

if (isset($_POST["suchtext"])){
if ($_POST["suchtext"]==""){$_POST["suchtext"]="%";}
	switch ($la) {
	
		case "la4":
		$suchtext=$list." WHERE (fehlermeldungen.fmid like '".substr($_POST["suchtext"],1,100)."' or fehlermeldungen.fmid like '".$_POST["suchtext"]."' 
							OR fehlermeldungen.fm1notes like '%".$_POST["suchtext"]."%' OR fehlermeldungen.fmsn like '".$_POST["suchtext"]."' 
							OR fehlermeldungen.fm11notes like '".$_POST["suchtext"]."' "." OR fehlermeldungen.fm12notes like '".$_POST["suchtext"]."' "." 
							OR fehlermeldungen.debitorennummer like '".$_POST["suchtext"]."'  "." OR fehlermeldungen.debitorennummer2 like '".$_POST["suchtext"]."' 
							OR fehlermeldungen.fm5notes like '".$_POST["suchtext"]."' OR fehlermeldungen.fm6notes like '".$_POST["suchtext"]."')";
		break;
		
		case "la4q1":
		$suchtext=" WHERE fehlermeldungen.fmart='$url_fmart' and fehlermeldungen.lokz=0 and 
							( fehlermeldungen.fmid like '".substr($_POST["suchtext"],1,100)."' 
							or fehlermeldungen.fmid like '".$_POST["suchtext"]."' 
							OR fehlermeldungen.fm1notes like '%".$_POST["suchtext"]."%' 
							OR fehlermeldungen.fmsn like '".$_POST["suchtext"]."' 
							OR fehlermeldungen.fm11notes like '".$_POST["suchtext"]."' "." 
							OR fehlermeldungen.fm12notes like '".$_POST["suchtext"]."' "." 
							OR fehlermeldungen.debitorennummer like '".$_POST["suchtext"]."'
							OR fehlermeldungen.debitorennummer2 like '".$_POST["suchtext"]."' 
							OR fehlermeldungen.fm5notes like '".$_POST["suchtext"]."' 
							OR fehlermeldungen.fm6notes like '".$_POST["suchtext"]."' )";
		break;
		
		case "la4q2":
		$suchtext=" WHERE fehlermeldungen.fmart='$url_fmart' and fehlermeldungen.lokz=0 and ( fehlermeldungen.fmid like '".substr($_POST["suchtext"],1,100)."' or fehlermeldungen.fmid like '".$_POST["suchtext"]."' OR fehlermeldungen.fm1notes like '%".$_POST["suchtext"]."%' OR fehlermeldungen.fmsn like '".$_POST["suchtext"]."' OR fehlermeldungen.fm11notes like '".$_POST["suchtext"]."' "." OR fehlermeldungen.fm12notes like '".$_POST["suchtext"]."' "." OR fehlermeldungen.debitorennummer like '".$_POST["suchtext"]."'  "." OR fehlermeldungen.debitorennummer2 like '".$_POST["suchtext"]."' OR fehlermeldungen.fm5notes like '".$_POST["suchtext"]."' OR fehlermeldungen.fm6notes like '".$_POST["suchtext"]."')";
		break;
		
		case "la4q3":
		$suchtext=" WHERE fehlermeldungen.fmart='$url_fmart' and fehlermeldungen.lokz=0 and ( fehlermeldungen.fmid like '".substr($_POST["suchtext"],1,100)."' or fehlermeldungen.fmid like '".$_POST["suchtext"]."' OR fehlermeldungen.fm1notes like '%".$_POST["suchtext"]."%' OR fehlermeldungen.fmsn like '".$_POST["suchtext"]."' OR fehlermeldungen.fm11notes like '".$_POST["suchtext"]."' "." OR fehlermeldungen.fm12notes like '".$_POST["suchtext"]."' "." OR fehlermeldungen.debitorennummer like '".$_POST["suchtext"]."'  "." OR fehlermeldungen.debitorennummer2 like '".$_POST["suchtext"]."' OR fehlermeldungen.fm5notes like '".$_POST["suchtext"]."' OR fehlermeldungen.fm6notes like '".$_POST["suchtext"]."')";
		break;
		
		/* case "la4":
		$suchtext=" WHERE fehlermeldungen.fmart=1 and fehlermeldungen.lokz=0 and ( fehlermeldungen.fmid like '".substr($_POST["suchtext"],1,100)."' or fehlermeldungen.fmid like '".$_POST["suchtext"]."' OR fehlermeldungen.fm1notes like '%".$_POST["suchtext"]."%' OR fehlermeldungen.fmsn like '".$_POST["suchtext"]."' OR fehlermeldungen.fm11notes like '".$_POST["suchtext"]."' "." OR fehlermeldungen.fm12notes like '".$_POST["suchtext"]."' "." OR fehlermeldungen.debitorennummer like '".$_POST["suchtext"]."'  "." OR fehlermeldungen.debitorennummer like '".$_POST["suchtext"]."')";
		break; */
		
		case "la5":
		$suchtext=" WHERE fehlermeldungen.lokz=0 and ( fehlermeldungen.debitorennummer='$url_kundenummer' AND ( fehlermeldungen.fmid like '".substr($_POST["suchtext"],1,100)."' or fehlermeldungen.fmid like '".$_POST["suchtext"]."' OR fehlermeldungen.fm1notes like '%".$_POST["suchtext"]."%' OR fehlermeldungen.fmsn like '".$_POST["suchtext"]."' OR fehlermeldungen.fm11notes like '".$_POST["suchtext"]."' "." OR fehlermeldungen.fm12notes like '".$_POST["suchtext"]."'"." OR fehlermeldungen.debitorennummer like '".$_POST["suchtext"]." OR fehlermeldungen.debitorennummer2 like '".$_POST["suchtext"]."' OR fehlermeldungen.fm5notes like '".$_POST["suchtext"]."' OR fehlermeldungen.fm6notes like '".$_POST["suchtext"]."')";
		break;
		
		case "la41s":
		$suchtext=" WHERE fehlermeldungen.lokz=0 and (
		(fehlermeldungen.fm0=1 AND fehlermeldungen.fm1<>1 ) AND 
		(fehlermeldungen.fmid like '".substr($_POST["suchtext"],1,100)."' 
		or fehlermeldungen.fmid like '".$_POST["suchtext"]."' 
		OR fehlermeldungen.fm1notes like '%".$_POST["suchtext"]."%' 
		OR fehlermeldungen.fmsn like '".$_POST["suchtext"]."' 
		OR fehlermeldungen.fm11notes like '".$_POST["suchtext"]."' "." 
		OR fehlermeldungen.fm12notes like '".$_POST["suchtext"]."'"." 
		OR fehlermeldungen.debitorennummer like '".$_POST["suchtext"]."' 
		OR fehlermeldungen.debitorennummer2 like '".$_POST["suchtext"]."' 
		OR fehlermeldungen.fm5notes like '".$_POST["suchtext"]."' 
		OR fehlermeldungen.fm6notes like '".$_POST["suchtext"]."') )";
		break;
		
		case "la42s":
		$suchtext=" WHERE fehlermeldungen.lokz=0 and ((fehlermeldungen.fm0=1 AND fehlermeldungen.fm1=1 AND fehlermeldungen.fm2=0 AND fehlermeldungen.fm1b=1) AND (fehlermeldungen.fmid like '".substr($_POST["suchtext"],1,100)."' or fehlermeldungen.fmid like '".$_POST["suchtext"]."' OR fehlermeldungen.fm1notes like '%".$_POST["suchtext"]."%' OR fehlermeldungen.fmsn like '".$_POST["suchtext"]."' OR fehlermeldungen.fm11notes like '".$_POST["suchtext"]."' "." OR fehlermeldungen.fm12notes like '".$_POST["suchtext"]."' "." OR fehlermeldungen.debitorennummer like '".$_POST["suchtext"]."' OR fehlermeldungen.debitorennummer2 like '".$_POST["suchtext"]."' OR fehlermeldungen.fm5notes like '".$_POST["suchtext"]."' OR fehlermeldungen.fm6notes like '".$_POST["suchtext"]."'))";
		break;
		case "la43s":
		$suchtext=" WHERE fehlermeldungen.lokz=0 and ((fehlermeldungen.fm0=1 AND fehlermeldungen.fm1=1 AND fehlermeldungen.fm2=1 AND fehlermeldungen.fm3=0) AND (fehlermeldungen.fmid like '".substr($_POST["suchtext"],1,100)."' or fehlermeldungen.fmid like '".$_POST["suchtext"]."' OR fehlermeldungen.fm1notes like '%".$_POST["suchtext"]."%' OR fehlermeldungen.fmsn like '".$_POST["suchtext"]."' OR fehlermeldungen.fm11notes like '".$_POST["suchtext"]."' "." OR fehlermeldungen.fm12notes like '".$_POST["suchtext"]."'"." OR fehlermeldungen.debitorennummer like '".$_POST["suchtext"]."' OR fehlermeldungen.debitorennummer2 like '".$_POST["suchtext"]."' OR fehlermeldungen.fm5notes like '".$_POST["suchtext"]."' OR fehlermeldungen.fm6notes like '".$_POST["suchtext"]."'))";
		break;
		
		case "la44s":
		$suchtext=" WHERE fehlermeldungen.lokz=0 and ((fehlermeldungen.fm0=1 AND fehlermeldungen.fm1=1 AND fehlermeldungen.fm2=1 AND fehlermeldungen.fm3=1  AND fehlermeldungen.fm4=0) AND (fehlermeldungen.fmid like '".substr($_POST["suchtext"],1,100)."' or fehlermeldungen.fmid like '".$_POST["suchtext"]."' OR fehlermeldungen.fm1notes like '%".$_POST["suchtext"]."%' OR fehlermeldungen.fmsn like '".$_POST["suchtext"]."' OR fehlermeldungen.fm11notes like '".$_POST["suchtext"]."' "." OR fehlermeldungen.fm12notes like '".$_POST["suchtext"]."'"." OR fehlermeldungen.debitorennummer like '".$_POST["suchtext"]."' OR fehlermeldungen.debitorennummer2 like '".$_POST["suchtext"]."' OR fehlermeldungen.fm5notes like '".$_POST["suchtext"]."' OR fehlermeldungen.fm6notes like '".$_POST["suchtext"]."'))";
		break;
		
		case "la442s":
		$suchtext=" WHERE fehlermeldungen.fm4user='$url_user' and fehlermeldungen.lokz=0 and ((fehlermeldungen.fm0=1 AND fehlermeldungen.fm1=1 AND fehlermeldungen.fm2=1 AND fehlermeldungen.fm3=1  AND fehlermeldungen.fm4=0) AND (fehlermeldungen.fmid like '".substr($_POST["suchtext"],1,100)."' or fehlermeldungen.fmid like '".$_POST["suchtext"]."' OR fehlermeldungen.fm1notes like '%".$_POST["suchtext"]."%' OR fehlermeldungen.fmsn like '".$_POST["suchtext"]."' OR fehlermeldungen.fm11notes like '".$_POST["suchtext"]."' "." OR fehlermeldungen.fm12notes like '".$_POST["suchtext"]."'"." OR fehlermeldungen.debitorennummer like '".$_POST["suchtext"]."' OR fehlermeldungen.debitorennummer2 like '".$_POST["suchtext"]."' OR fehlermeldungen.fm5notes like '".$_POST["suchtext"]."' OR fehlermeldungen.fm6notes like '".$_POST["suchtext"]."'))";
		break;
		
		case "la45s":
		$suchtext=" WHERE fehlermeldungen.lokz=0 and ((fehlermeldungen.fm0=1 AND fehlermeldungen.fm1=1 AND fehlermeldungen.fm2=1 AND fehlermeldungen.fm3=1  AND fehlermeldungen.fm4=1 AND fehlermeldungen.fm5=0 and fehlermeldungen.vkz=0) AND (fehlermeldungen.fmid like '".substr($_POST["suchtext"],1,100)."' or fehlermeldungen.fmid like '".$_POST["suchtext"]."' OR fehlermeldungen.fm1notes like '%".$_POST["suchtext"]."%' OR fehlermeldungen.fmsn like '".$_POST["suchtext"]."' OR fehlermeldungen.fm11notes like '".$_POST["suchtext"]."' "." OR fehlermeldungen.fm12notes like '".$_POST["suchtext"]."' "." OR fehlermeldungen.debitorennummer like '".$_POST["suchtext"]."' OR fehlermeldungen.debitorennummer2 like '".$_POST["suchtext"]."' OR fehlermeldungen.fm5notes like '".$_POST["suchtext"]."' OR fehlermeldungen.fm6notes like '".$_POST["suchtext"]."'))";
		break;
		
		case "la46s":
		$suchtext=" WHERE fehlermeldungen.lokz=0 and (((fehlermeldungen.fm0=1 AND fehlermeldungen.fm1=1 AND fehlermeldungen.fm2=1 AND fehlermeldungen.fm3=1  AND fehlermeldungen.fm4=1 AND fehlermeldungen.fm5=1 AND fehlermeldungen.fm6=0) or (fehlermeldungen.fm0=1 AND fehlermeldungen.fm1=1 AND fehlermeldungen.fm2=1 AND fehlermeldungen.fm3=1  AND fehlermeldungen.fm4=1 AND fehlermeldungen.fm6=0 and fehlermeldungen.vkz>1)) AND (fehlermeldungen.fmid like '".substr($_POST["suchtext"],1,100)."' or fehlermeldungen.fmid like '".$_POST["suchtext"]."' OR fehlermeldungen.fm1notes like '%".$_POST["suchtext"]."%' OR fehlermeldungen.fmsn like '".$_POST["suchtext"]."' OR fehlermeldungen.fm11notes like '".$_POST["suchtext"]."' "." OR fehlermeldungen.fm12notes like '".$_POST["suchtext"]."' "." OR fehlermeldungen.debitorennummer like '".$_POST["suchtext"]."' OR fehlermeldungen.fm5notes like '".$_POST["suchtext"]."'  ))" ;
		break;
		
		case "la48s":
		$suchtext=" WHERE fehlermeldungen.lokz=0 and (((fehlermeldungen.fm0=1 AND fehlermeldungen.fm1=1 AND fehlermeldungen.fm2=1 AND fehlermeldungen.fm3=1  AND fehlermeldungen.fm4=1  AND fehlermeldungen.fm6=1 AND fehlermeldungen.fm8=0) ) AND (fehlermeldungen.fmid like '".substr($_POST["suchtext"],1,100)."' or fehlermeldungen.fmid like '".$_POST["suchtext"]."' OR fehlermeldungen.fm1notes like '%".$_POST["suchtext"]."%' OR fehlermeldungen.fmsn like '".$_POST["suchtext"]."' OR fehlermeldungen.fm11notes like '".$_POST["suchtext"]."' "." OR fehlermeldungen.fm12notes like '".$_POST["suchtext"]."' "." OR fehlermeldungen.debitorennummer like '".$_POST["suchtext"]."' OR fehlermeldungen.fm6notes like '".$_POST["suchtext"]."'  ))" ;
		break;
		
		case "la481s":
		$suchtext=" WHERE fehlermeldungen.lokz=0 and (((fehlermeldungen.fm0=1 AND fehlermeldungen.fm1=1 AND fehlermeldungen.fm2=1 AND fehlermeldungen.fm3=1  AND fehlermeldungen.fm4=1  AND fehlermeldungen.fm6=1) ) AND (fehlermeldungen.fmid like '".substr($_POST["suchtext"],1,100)."' or fehlermeldungen.fmid like '".$_POST["suchtext"]."' OR fehlermeldungen.fm1notes like '%".$_POST["suchtext"]."%' OR fehlermeldungen.fmsn like '".$_POST["suchtext"]."' OR fehlermeldungen.fm11notes like '".$_POST["suchtext"]."' "." OR fehlermeldungen.fm12notes like '".$_POST["suchtext"]."' "." OR fehlermeldungen.debitorennummer like '".$_POST["suchtext"]."' OR fehlermeldungen.fm6notes like '".$_POST["suchtext"]."'  ))" ;
		break;
		
		} /* ende switch */
		
		$suchtext=$suchtext.$filterbyartikelid;
		
		
	} /* ende if */
else{


	switch ($la){
	
		case "la4":
		$suchtext="";
		break;
		
		case "la4q1":
		$suchtext="WHERE fehlermeldungen.fmart='$url_fmart' and fehlermeldungen.lokz=0";
		break;
		
		case "la4q2":
		$suchtext="WHERE fehlermeldungen.fmart='$url_fmart' and fehlermeldungen.lokz=0";
		break;
		
		case "la4q3":
		$suchtext="WHERE fehlermeldungen.fmart='$url_fmart' and fehlermeldungen.lokz=0";
		break;
		
		case "la5":
		$suchtext="WHERE fehlermeldungen.lokz=0 and ( fehlermeldungen.debitorennummer='$url_kundenummer' )";
		break;
		
		case "la41s":
		$suchtext="WHERE fehlermeldungen.lokz=0 and ((fehlermeldungen.fm0=1 AND fehlermeldungen.fm1<>1 ))";
		break;
		
		case "la42s":
		$suchtext="WHERE fehlermeldungen.lokz=0 and ((fehlermeldungen.fm0=1 AND fehlermeldungen.fm1=1 AND fehlermeldungen.fm2=0 AND fehlermeldungen.fm1b=1))";
		break;
		
		case "la43s":
		$suchtext="WHERE fehlermeldungen.lokz=0 and ((fehlermeldungen.fm0=1 AND fehlermeldungen.fm1=1 AND fehlermeldungen.fm2=1 AND fehlermeldungen.fm3=0))";
		break;
		
		case "la44s":
		$suchtext="WHERE fehlermeldungen.lokz=0 and ((fehlermeldungen.fm0=1 AND fehlermeldungen.fm1=1 AND fehlermeldungen.fm2=1 AND fehlermeldungen.fm3=1  AND fehlermeldungen.fm4=0))";
		break;
		
		case "la442s":
		$suchtext=" WHERE fehlermeldungen.fm4user='$url_user' and fehlermeldungen.lokz=0 and ((fehlermeldungen.fm0=1 AND fehlermeldungen.fm1=1 AND fehlermeldungen.fm2=1 AND fehlermeldungen.fm3=1  AND fehlermeldungen.fm4=0) AND (fehlermeldungen.fmid like '".substr($_POST["suchtext"],1,100)."' or fehlermeldungen.fmid like '".$_POST["suchtext"]."' OR fehlermeldungen.fm1notes like '%".$_POST["suchtext"]."%' OR fehlermeldungen.fmsn like '".$_POST["suchtext"]."' OR fehlermeldungen.fm11notes like '".$_POST["suchtext"]."' "." OR fehlermeldungen.fm12notes like '".$_POST["suchtext"]."'"." OR fehlermeldungen.debitorennummer like '".$_POST["suchtext"]."'  ))" ;
		break;
		
		
		case "la45s":
		$suchtext="WHERE fehlermeldungen.lokz=0 and ((fehlermeldungen.fm0=1 AND fehlermeldungen.fm1=1 AND fehlermeldungen.fm2=1 AND fehlermeldungen.fm3=1  AND fehlermeldungen.fm4=1 AND fehlermeldungen.fm5=0 and fehlermeldungen.vkz=0))";
		break;
		
		case "la46s":
		$suchtext="WHERE fehlermeldungen.lokz=0 and ((( fehlermeldungen.fm0=1 AND fehlermeldungen.fm1=1 AND fehlermeldungen.fm2=1 AND fehlermeldungen.fm3=1  AND fehlermeldungen.fm4=1 AND fehlermeldungen.fm5=1 AND fehlermeldungen.fm6=0) or (fehlermeldungen.fm0=1 AND fehlermeldungen.fm1=1 AND fehlermeldungen.fm2=1 AND fehlermeldungen.fm3=1  AND fehlermeldungen.fm4=1 AND fehlermeldungen.fm6=0 and fehlermeldungen.vkz>1)))";
		break;
		
		case "la48s":
		$suchtext=" WHERE fehlermeldungen.lokz=0 and (((fehlermeldungen.fm0=1 AND fehlermeldungen.fm1=1 AND fehlermeldungen.fm2=1 AND fehlermeldungen.fm3=1  AND fehlermeldungen.fm4=1 AND fehlermeldungen.fm5=1 AND fehlermeldungen.fm6=1 AND fehlermeldungen.fm8=0) ) AND (fehlermeldungen.fmid like '".substr($_POST["suchtext"],1,100)."' or fehlermeldungen.fmid like '".$_POST["suchtext"]."' OR fehlermeldungen.fm1notes like '%".$_POST["suchtext"]."%' OR fehlermeldungen.fmsn like '".$_POST["suchtext"]."' OR fehlermeldungen.fm11notes like '".$_POST["suchtext"]."' "." OR fehlermeldungen.fm12notes like '".$_POST["suchtext"]."' "." OR fehlermeldungen.debitorennummer like '".$_POST["suchtext"]."' OR fehlermeldungen.fm6notes like '".$_POST["suchtext"]."'  ))" ;
		break;
		
		case "la481s":
		$suchtext=" WHERE fehlermeldungen.lokz=0 and (((fehlermeldungen.fm0=1 AND fehlermeldungen.fm1=1 AND fehlermeldungen.fm2=1 AND fehlermeldungen.fm3=1  AND fehlermeldungen.fm4=1 AND fehlermeldungen.fm5=1 AND fehlermeldungen.fm6=1) ) AND (fehlermeldungen.fmid like '".substr($_POST["suchtext"],1,100)."' or fehlermeldungen.fmid like '".$_POST["suchtext"]."' OR fehlermeldungen.fm1notes like '%".$_POST["suchtext"]."%' OR fehlermeldungen.fmsn like '".$_POST["suchtext"]."' OR fehlermeldungen.fm11notes like '".$_POST["suchtext"]."' "." OR fehlermeldungen.fm12notes like '".$_POST["suchtext"]."' "." OR fehlermeldungen.debitorennummer like '".$_POST["suchtext"]."' OR fehlermeldungen.fm6notes like '".$_POST["suchtext"]."'  ))" ;
		break;
	} /* ende switch */


}/* ende else */?>