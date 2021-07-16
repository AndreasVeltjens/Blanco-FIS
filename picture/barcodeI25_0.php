<?php  header( "Content-type: image/png"); 
$encoder = BarcodeFactory::Create(ENCODING_CODEI25);
$e = BackendFactory::Create(BACKEND_IMAGE,$encoder);
$e->SetModuleWidth(1);
$e->SetHeight(70);
$e->SetImgFormat('PNG');
$e->SetVertical(1);
if($uuu==1){
$im=$e->Stroke($url_nummer,"barcode/testbild.png");
}else{
$im=$e->Stroke($url_nummer,"barcode/testbild2.png");
}
?>