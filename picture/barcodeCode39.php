<?php  header( "Content-type: image/png"); 

$encoder = BarcodeFactory::Create(ENCODING_CODE39);
$e = BackendFactory::Create(BACKEND_IMAGE,$encoder);
$e->SetModuleWidth(2);
$e->SetHeight(70);
$e->SetImgFormat('PNG');
$e->SetVertical(0);
$im=$e->Stroke($url_nummer,"barcode/testbild".$count.".png")

?>