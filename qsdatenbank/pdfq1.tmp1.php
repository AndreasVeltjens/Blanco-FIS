<?php mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rsttyp = "SELECT artikeldaten.artikelid, artikeldaten.Bezeichnung, artikeldaten.Nummer FROM artikeldaten";
$rsttyp = mysql_query($query_rsttyp, $qsdatenbank) or die(mysql_error());
$row_rsttyp = mysql_fetch_assoc($rsttyp);
$totalRows_rsttyp = mysql_num_rows($rsttyp);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rstuser = "SELECT * FROM `user`";
$rstuser = mysql_query($query_rstuser, $qsdatenbank) or die(mysql_error());
$row_rstuser = mysql_fetch_assoc($rstuser);
$totalRows_rstuser = mysql_num_rows($rstuser);
		
		
		if ($row_rst1['debitorennummer2']==""){$suchtxt="fehlermeldungen.debitorennummer";}else{$suchtxt="fehlermeldungen.debitorennummer2";}
		
mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rstsg = "SELECT fhm_ereignis.id_fhme, fhm_ereignis.datum, fhm_ereignis.user, fhm_ereignis.beschreibung, fhm_ereignis.id_contact, fhm_ereignis.id_fhm, fhm_ereignis.id_fhmea, fhm.sn, fhm.artikelid, fehlermeldungen.fmid, fehlermeldungen.debitorennummer, fhm.id_fhm, fehlermeldungen.fmid
FROM fhm_ereignis, fehlermeldungen, fhm
WHERE fhm_ereignis.id_contact = $suchtxt 
AND fhm_ereignis.id_contact<>0
AND fhm_ereignis.id_contact<>''
AND fhm.id_fhm = fhm_ereignis.id_fhm
AND fehlermeldungen.fmid =$url_fmid 
ORDER BY fhm_ereignis.datum
LIMIT 0 , 30 ";

$rstsg = mysql_query($query_rstsg, $qsdatenbank) or die(mysql_error());
$row_rstsg = mysql_fetch_assoc($rstsg);
$totalRows_rstsg = mysql_num_rows($rstsg);

mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rstsg2 = "SELECT fhm_ereignisart.id_fhmea, fhm_ereignisart.name, fhm_ereignisart.piclink FROM fhm_ereignisart WHERE fhm_ereignisart.lokz=0 ORDER BY fhm_ereignisart.name";
$rstsg2 = mysql_query($query_rstsg2, $qsdatenbank) or die(mysql_error());
$row_rstsg2= mysql_fetch_assoc($rstsg2);
$totalRows_rstsg2 = mysql_num_rows($rstsg2);

$pdf->SetFont('Helvetica','B',12);
 $pdf->Write(12, 'Umlagerung von Servicegeräten');
  $pdf->Ln(12);

  
 $pdf->SetFont('Helvetica','',10);
if ($totalRows_rstsg == 0) { // Show if recordset empty 
$pdf->Ln(6);
	$pdf->Write(10, '   keine Servicegeräte gefunden.');
 	$pdf->Ln(4);
	} // Show if recordset empty 
 
if ($totalRows_rstsg > 0) { // Show if recordset not empty 
$pdf->Write(4, "Anzahl der bisherigen Umlagerungen: ".$totalRows_rstsg);
$pdf->Ln(6);
  $pdf->Write(10, 'Datum           Typ            Seriennummer        Umlagerung-Nr. Status     bearbeitet    Bemerkungen  ');
  $pdf->Ln(12);

$pdf->SetFont('Helvetica','',8);
  do { 
       mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rstsg1 = "SELECT * FROM fhm_ereignis WHERE fhm_ereignis.id_ref_fhme='$row_rstsg[id_fhme]'";
$rstsg1 = mysql_query($query_rstsg1, $qsdatenbank) or die(mysql_error());
$row_rstsg1 = mysql_fetch_assoc($rstsg1);
$totalRows_rstsg1 = mysql_num_rows($rstsg1);

 if  ($totalRows_rstsg1==0){ /* wenn Serviceumlagerung  noch offen, dann anzeige */

		$pdf->Write(4, $row_rstsg['datum']."      "); 
	  
					  do {  
							if (!(strcmp($row_rsttyp['artikelid'], $row_rstsg['artikelid']))) {$pdf->Write(4, $row_rsttyp['Bezeichnung']."  ");} 
					} while ($row_rsttyp = mysql_fetch_assoc($rsttyp));
						  $rows = mysql_num_rows($rsttyp);
						  if($rows > 0) {
							  mysql_data_seek($rsttyp, 0);
							  $row_rsttyp = mysql_fetch_assoc($rsttyp);
						  }
	$pdf->Write(4, $row_rstsg['sn']."                "); 
	
  
  
  /* suche ereignisdaten */

		do {  
			 if (!(strcmp($row_rstsg2['id_fhmea'], $row_rstsg['id_fhmea']))) {$pdf->Write(4, utf8_decode($row_rstsg2['name'])."  ");} 
			} while ($row_rstsg2 = mysql_fetch_assoc($rstsg2));
		  $rows = mysql_num_rows($rstsg2);
		  if($rows > 0) {
			  mysql_data_seek($rstsg2, 0);
			  $row_rstsg2 = mysql_fetch_assoc($rstsg2);
		  }
	 if  ($totalRows_rstsg1>0){ $pdf->Write(4,"00".$row_rstsg['id_fhme']." erledigt.   "); }
	
	 if  ($totalRows_rstsg1==0){ $pdf->Write(4,"00".$row_rstsg['id_fhme']." versendet.  "); }
								do {  
											if (!(strcmp($row_rstuser['id'], $row_rstsg['user']))) {$pdf->Write(4,  $row_rstuser['name']."       ");}
										} while ($row_rstuser = mysql_fetch_assoc($rstuser));
										  $rows = mysql_num_rows($rstuser);
										  if($rows > 0) {
											  mysql_data_seek($rstuser, 0);
											  $row_rstuser = mysql_fetch_assoc($rstuser);
										  }
				 $pdf->Write(4,utf8_decode(urldecode(str_replace("\n"," - ",$row_rstsg['beschreibung']) ))); 
					$pdf->Ln(5);
				
	} else { /* ende anzeige */
			$pdf->Write(4,"Derzeit gibt es keine offenen Umlagerungen von Servicegeräten."); 
			$pdf->Ln(4);
	}
}
	while ($row_rstsg = mysql_fetch_assoc($rstsg)); 
	
	
	
	$pdf->Ln(4);
$pdf->Write(4,"Bitte senden Sie uns alle Leihgeräte, die nicht in Zusammenhang mit aktuellen Retouren stehen, umgehend zurück. 
14 Tage nach Rücksendung Ihres Gerätes an die von Ihnen o.g. Lieferanschriftstellen wir Ihnen für die weitere 
Nutzung des Leihgerätes für diese Retoure ein Entgelt von pro Tag 10 € (netto) separat in Rechnung.

Wenn Sie Fragen zur Reparatur Ihres Gerätes oder defekte Servicegeräte erhalten haben, melden Sie sich bitte umgehend
telefonisch oder gern auch per E-Mail an o.g. Ansprechpartner. Die Reparatur von defekten Servicegeräten nach Ihrer 
Nutzung oder Ihrem unsachgemäßen Gebrauch stellen wir Ihnen nach Aufwand separat in Rechnung.");
	
	 } // Show if recordset not empty ?>