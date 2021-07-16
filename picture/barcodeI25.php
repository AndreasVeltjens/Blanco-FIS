<?php  header( "Content-type: image/png"); 
include "../src/jpgraph.php";
include "../src/jpgraph_canvas.php";
include "../src/jpgraph_barcode.php";

$encoder = BarcodeFactory::Create(ENCODING_CODEI25);
$e = BackendFactory::Create(BACKEND_IMAGE,$encoder);
$e->SetModuleWidth(1);
$e->SetHeight(70);
$e->SetImgFormat('PNG');
$e->SetVertical(1);
$im=$e->Stroke("0".$url_nummer,"barcode/testbild.png");

?>