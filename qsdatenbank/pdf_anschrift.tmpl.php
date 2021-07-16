<?php 
		if (!isset($url_user_id)){$url_user_id=3;}
		
mysql_select_db($database_qsdatenbank, $qsdatenbank);
$query_rstuser = "SELECT * FROM `user` WHERE `user`.id=$url_user_id";
$rstuser = mysql_query($query_rstuser, $qsdatenbank) or die(mysql_error());
$row_rstuser = mysql_fetch_assoc($rstuser);
$totalRows_rstuser = mysql_num_rows($rstuser);
		$pdf->SetFont('Helvetica','',10);
		$pdf->Text(140,31,substr(utf8_decode($row_rstuser['firma']),0,20));
		$pdf->Text(140,35,substr(utf8_decode($row_rstuser['firma']),20,60));
		$pdf->SetFont('Helvetica','',8);
		$pdf->Text(140,39,utf8_decode($row_rstuser['anschrift']));
		$pdf->Text(140,42,utf8_decode($row_rstuser['plz'])." ".utf8_decode($row_rstuser['ort']));
		$pdf->Text(140,48,utf8_decode($row_rstuser['telefon']));
		$pdf->Text(140,51,utf8_decode($row_rstuser['fax']));
		$pdf->Text(140,54,'Ihr Ansprechpartner: '.$row_rstuser['anrede']." ".utf8_decode($row_rstuser['name']));
		
		$pdf->Text(140,57,'E-Mail:'.utf8_decode($row_rstuser['email']));
		$pdf->Text(140,62,'Service-Hotline 0800 252 62 66 ');
		$pdf->Text(140,65,'Montag bis Freitag 8.00 - 18.00 Uhr ');
		$pdf->Text(140,68,'service.pro@blanco.de ');
		$pdf->SetFont('Helvetica','',10);
		/* $pdf->Text(140,100,'Leipzig, '.date("d.m.Y",time())); */
		
		$pdf->SetFont('Helvetica','',6);
		$pdf->Text(22,52,utf8_decode($row_rstuser['firma'])." - ".utf8_decode($row_rstuser['anschrift'])." - ".utf8_decode($row_rstuser['plz'])." ".utf8_decode($row_rstuser['ort']));
		$pdf->Text(22,280,utf8_decode($row_rstuser['firma'])." - ".utf8_decode($row_rstuser['anschrift'])." - ".utf8_decode($row_rstuser['plz'])." ".utf8_decode($row_rstuser['ort']));
		$pdf->Text(22,282,("HRB 3307 Amtgericht Leipzig, Geschäftsführer: Michael Huber - UST-ID-Nr.: DE141507813"));
		$pdf->Text(22,284,("Bankverbindung  Sparkasse Pforzheim - Konto-Nr. 897450 (BLZ 666 500 85)  IBAN DE43 6665 0085 0000 8974 50 BIC/SWIFT: PZHSDE66"));
				
?>