<?php  header( "Content-type: image/png"); 
$encoder = BarcodeFactory::Create(ENCODING_CODEI25);
$e = BackendFactory::Create(BACKEND_IMAGE,$encoder);
$e->SetModuleWidth(2);
$e->SetHeight(70);
$e->SetImgFormat('PNG');
$e->SetVertical(1);
$im=$e->Stroke("0".$url_nummer,$link)
?>