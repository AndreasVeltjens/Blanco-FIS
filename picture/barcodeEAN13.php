<?php  header( "Content-type: image/png"); 
$encoder = BarcodeFactory::Create(ENCODING_EAN13);
$e = BackendFactory::Create(BACKEND_IMAGE,$encoder);
$e->SetModuleWidth(2);
$e->SetHeight(70);
$e->SetImgFormat('PNG');
$e->SetVertical(1);
if($uuu==1){
$im=$e->Stroke($url_nummer,"barcode/testbildEAN13.png");
}else{
$im=$e->Stroke($url_nummer,"barcode/testbildEAN132.png");
}
?>