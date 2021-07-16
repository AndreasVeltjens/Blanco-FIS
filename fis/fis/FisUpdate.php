<?php

/**
 * Created by PhpStorm.
 * User: andreasveltjens
 * Date: 12.11.18
 * Time: 14:00
 */
class FisUpdate
{
    private $message=array();
    private $SAP=false;

    function __construct()
    {
        return $this;
    }
    function __toString(){
        return $this;
    }

    function SetEventfromFis9(){

        include ("./connection.php");

        if ($_SESSION['importcount']==0) {
            $sql = "SELECT COUNT( fhm_ereignis.id_fhme) as anzahl FROM  `fhm_ereignis` WHERE fhm_ereignis.id_ref_fhme=0 ";

            $rst = $mysqli->query($sql);
            if ($mysqli->error) {
                AddSessionMessage("error", "Tabelle fhm_ereignis enthält keine Daten", "DB Datenbankfehler");
                $_SESSION['importcount'] = 1;
            } else {
                $row_rst = $rst->fetch_assoc();
                $_SESSION['importcount'] = $row_rst['anzahl'];
            }
        }

        if ($_SESSION['importitem']==0) {
            $item=0;
        } else{
            $item=$_SESSION['importitem'];
        }

        for ($i=0; $i<100 or $item== $_SESSION['importcount']; $i++) {
            $item++;
            $_SESSION['importitem'] = $item;

            $sql = "SELECT fhm_ereignis.id_fhme,fhm_ereignis.id_fhm  FROM  `fhm_ereignis` WHERE fhm_ereignis.id_ref_fhme=0 LIMIT " . $item . ", 1";
            $rst = $mysqli->query($sql);
            if ($mysqli->error) {
                AddSessionMessage("error", "Datensatz $item enthält keine Daten", "DB Datenbankfehler");
                $_SESSION['event'] = "";
            } else {
                $row_rst = $rst->fetch_assoc();
                $_SESSION['event'] = $row_rst['id_fhme'];
                $_SESSION['equip'] = $row_rst['id_fhm'];

            }

            if ($_SESSION['event'] > 0) {
                $event = new FisEvent($_SESSION['event']);

                if ($event->GetRefEvent() == 0) {
                    $event->SaveEventReferenz($_SESSION['event'], $_SESSION['event']);
                    $w = new FisWorkflow("Datenübernahme über FIS-Update erstellt.", "equip", $_SESSION['equip']);
                    $w->Add();
                } else {
                    AddSessionMessage("warning", "Event bereits aktualisiert", "Abbruch");
                }


            }
        }
        $txt="Update <code>EVENT ".$item." von ".$_SESSION['importcount']."</code> Ereignisdaten aktualisiert";
        AddSessionMessage("info", $txt, "Abrechnung erfolgreich");
        return $txt;
    }

    function ImportFMIDfromFis9(){
        $piccount=0;

        include ("./connection.php");

        if ($_SESSION['importcount']==0) {
            $sql = "SELECT COUNT( fehlermeldungen.fmid ) as anzahl FROM  `fehlermeldungen`";

            $rst = $mysqli->query($sql);
            if ($mysqli->error) {
                AddSessionMessage("error", "Tabelle fehlermeldungen enthält keine Daten", "DB Datenbankfehler");
                $_SESSION['importcount'] = 1;
            } else {
                $row_rst = $rst->fetch_assoc();
                $_SESSION['importcount'] = $row_rst['anzahl'];
            }
        }

        if ($_SESSION['importitem']==0) {
            $item=0;
        } else{
            $item=$_SESSION['importitem'];
        }
        $item++;
        $_SESSION['importitem']=$item;

        $sql = "SELECT fehlermeldungen.fmid FROM  `fehlermeldungen` LIMIT ".$item.", 1";
        $rst = $mysqli->query($sql);
        if ($mysqli->error) {
            AddSessionMessage("error", "Datensatz $item enthält keine Daten", "DB Datenbankfehler");
            $_SESSION['fmid'] = "";
        } else {
            $row_rst = $rst->fetch_assoc();
            $_SESSION['fmid'] = $row_rst['fmid'];
        }

        if ($_SESSION['fmid']>0){
            $sql = "SELECT * FROM  `fehlermeldungenpicture` WHERE `fehlermeldungenpicture` .fmp_fm_id = '".$_SESSION['fmid']."' ";
            $rst = $mysqli->query($sql);
            if ($mysqli->error) {
                AddSessionMessage("info", "Fehlermeldung $item enthält keine Bilder", "DB Abfrage");
                $_SESSION['fmid'] = "";
            } else {
                $row_rst = $rst->fetch_assoc();
                do {
                    if (!is_dir("../fmid/".$_SESSION['fmid'])){
                        mkdir( "../fmid/".$_SESSION['fmid'] ,0777);
                    }

                    if (is_file("../fehlermeldungen/".$row_rst['link_dokument'])){

                        if (!is_file("../fmid/".$_SESSION['fmid']."/".$row_rst['link_dokument'])) {
                            $p = new FisThumbnail(
                                "../fehlermeldungen/" . $row_rst['link_dokument'],
                                "../fmid/" . $_SESSION['fmid'] . "/" . $row_rst['link_dokument']
                            );

                            $d=new FisDocument("IMPORT aus FIS9","fmid/".$_SESSION['fmid'],$_SESSION['fmid']);
                            $d->AddDocumentLink("../fmid/".$_SESSION['fmid']."/".$row_rst['link_dokument']);
                            $piccount++;
                        }else{
                            AddSessionMessage("success", "Dokument ".$row_rst['link_dokument']." ist schon vorhanden", "Create Thumbnail");
                        }
                    }else{
                        AddSessionMessage("warning", "Dokument ".$row_rst['link_dokument']." konnte nicht gefunden", "Create Thumbnail");
                    }


                }while ( $row_rst = $rst->fetch_assoc());
            }


        }



        $txt="Update <code>FMID ".$item." von ".$_SESSION['importcount']."</code> mit $piccount Dokumenten aktualisiert";

        AddSessionMessage("info", $txt, "Update erfolgreich");

        return $txt;
    }

    function SetFinalFMIDfromFis9(){
        $user=$_SESSION['active_user'];
        $datum=date('Y-m-d',time());
        include ("./connection.php");

        if ($_SESSION['importcount']==0) {
            $sql = "SELECT COUNT( fehlermeldungen.fmid ) as anzahl FROM  `fehlermeldungen` 
                    WHERE fehlermeldungen.fm5=1 AND fehlermeldungen.fm6=0 AND fehlermeldungen.sappcna>0 AND fehlermeldungen.fm7>0";

            $rst = $mysqli->query($sql);
            if ($mysqli->error) {
                AddSessionMessage("error", "Tabelle fehlermeldungen enthält keine Daten", "DB Datenbankfehler");
                $_SESSION['importcount'] = 1;
            } else {
                $row_rst = $rst->fetch_assoc();
                $_SESSION['importcount'] = $row_rst['anzahl'];
            }
        }

        if ($_SESSION['importitem']==0) {
            $item=0;
        } else{
            $item=$_SESSION['importitem'];
        }

        for ($i=0; $i<100 or $item== $_SESSION['importcount']; $i++) {
            $item++;
            $_SESSION['importitem'] = $item;

            $sql = "SELECT fehlermeldungen.fmid FROM  `fehlermeldungen` 
                WHERE fehlermeldungen.fm5=1 AND fehlermeldungen.fm6=0 AND fehlermeldungen.sappcna>0 AND fehlermeldungen.fm7>0 LIMIT " . $item . ", 1";
            $rst = $mysqli->query($sql);
            if ($mysqli->error) {
                AddSessionMessage("error", "Datensatz $item enthält keine Daten", "DB Datenbankfehler");
                $_SESSION['fmid'] = "";
            } else {
                $row_rst = $rst->fetch_assoc();
                $_SESSION['fmid'] = $row_rst['fmid'];
            }

            if ($_SESSION['fmid'] > 0) {
                $sql = "UPDATE  fehlermeldungen SET  
                    `fm6` =  '1',
                    `fm6user` =  '$user',
                    `fm6datum` =  '$datum',
                    `fm6notes` =  'Abrechnung mit Update' WHERE  `fehlermeldungen`.`fmid` =" . $_SESSION['fmid'] . " LIMIT 1 ;";
                $rst = $mysqli->query($sql);
                if ($mysqli->error) {
                    AddSessionMessage("info", "Fehlermeldung " . $_SESSION['fmid'] . " erzeugte einen Fehler", "DB Update");
                    $_SESSION['fmid'] = "";
                }

                $w = new FisWorkflow("Abrechnung über FIS-Update erstellt.", "fmid", $_SESSION['fmid']);
                $w->Add();
            }
        }
        $txt="Update <code>FMID ".$item." von ".$_SESSION['importcount']."</code> Abrechnung aktualisiert";
        AddSessionMessage("info", $txt, "Abrechnung erfolgreich");
        return $txt;
    }


    function ImportArtikelPicturesfromFis9(){
        $user=$_SESSION['active_user'];
        $datum=date('Y-m-d',time());

        include ("./connection.php");

        if ($_SESSION['importcount']==0) {
            $sql = "SELECT COUNT( artikeldaten.artikelid ) as anzahl FROM  `artikeldaten` 
                    ";

            $rst = $mysqli->query($sql);
            if ($mysqli->error) {
                AddSessionMessage("error", "Tabelle artikeldaten enthält keine Daten", "DB Datenbankfehler");
                $_SESSION['importcount'] = 1;
            } else {
                $row_rst = $rst->fetch_assoc();
                $_SESSION['importcount'] = $row_rst['anzahl'];
            }
        }

        if ($_SESSION['importitem']==0) {
            $item=0;
        } else{
            $item=$_SESSION['importitem'];
        }
        $item++;
        $_SESSION['importitem']=$item;

        $sql = "SELECT * FROM  `artikeldaten`  LIMIT ".$item.", 1";
        $rst = $mysqli->query($sql);
        if ($mysqli->error) {
            AddSessionMessage("error", "Datensatz $item enthält keine Daten", "DB Datenbankfehler");
            $_SESSION['material'] = "";
        } else {
            $row_rst = $rst->fetch_assoc();
            $_SESSION['material'] = $row_rst['artikelid'];
        }

        $txt="Material-ID <code>".$_SESSION['material']."</code> bearbeitet";
        if ($_SESSION['material']>0){

            $fname="";
            $folder="../materialbilder/";

            if (is_file($folder.$row_rst['link_dokument'])){$fname=$row_rst['link_dokument'];}

            if (is_file($folder.$_SESSION['material'].".jpg")) { $fname=$_SESSION['material'].".jpg";}
            if (is_file($folder.$_SESSION['material'].".jpeg")) { $fname=$_SESSION['material'].".jpg";}
            if (is_file($folder.$_SESSION['material'].".png")) { $fname=$_SESSION['material'].".png";}



            if (strlen($fname)>0) {
                copy($folder.$fname,"../artikelid/".$fname);
                $t=new FisThumbnail($folder.$fname,"../thumbnail/artikelid/",100,100);
                $txt=$fname;
                $d = new FisDocument("Materialbild", "artikelid", $_SESSION['material']);
                $d->AddDocumentLink("../artikelid/".$fname);

                $w = new FisWorkflow("Bilder-Update <code>$fname</code> über FIS-Update erstellt.", "artikelid", $_SESSION['material']);
                $w->Add();
                AddSessionMessage("success", $txt, "Bildquelle importiert");
            }else{
                AddSessionMessage("info", $txt, "keine Bildquelle gefunden");
            }
        }



        $txt="Update <code>FMID ".$item." von ".$_SESSION['importcount']."</code> Abrechnung aktualisiert";

        AddSessionMessage("info", $txt, "Stammdaten- Update erfolgreich");

        return $txt;
    }
    function ImportArtikeldatafromFis9(){
        $user=$_SESSION['active_user'];
        $datum=date('Y-m-d',time());

        include ("./connection.php");

        if ($_SESSION['importcount']==0) {
            $sql = "SELECT COUNT( artikeldaten.artikelid ) as anzahl FROM  `artikeldaten` 
                    ";

            $rst = $mysqli->query($sql);
            if ($mysqli->error) {
                AddSessionMessage("error", "Tabelle artikeldaten enthält keine Daten", "DB Datenbankfehler");
                $_SESSION['importcount'] = 1;
            } else {
                $row_rst = $rst->fetch_assoc();
                $_SESSION['importcount'] = $row_rst['anzahl'];
            }
        }

        if ($_SESSION['importitem']==0) {
            $item=0;
        } else{
            $item=$_SESSION['importitem'];
        }
        $item++;
        $_SESSION['importitem']=$item;

        $sql = "SELECT artikeldaten.artikelid FROM  `artikeldaten`  LIMIT ".$item.", 1";
        $rst = $mysqli->query($sql);
        if ($mysqli->error) {
            AddSessionMessage("error", "Datensatz $item enthält keine Daten", "DB Datenbankfehler");
            $_SESSION['material'] = "";
        } else {
            $row_rst = $rst->fetch_assoc();
            $_SESSION['material'] = $row_rst['artikelid'];
        }

        if ($_SESSION['material']>0){

            $m=new FisMaterial($_SESSION['material']);
            $m->ImportFis9data();

            $w=new FisWorkflow("Stammdaten-Update über FIS-Update erstellt.","artikelid",$_SESSION['material']);
            $w->Add();
        }



        $txt="Update <code>FMID ".$item." von ".$_SESSION['importcount']."</code> Abrechnung aktualisiert";

        AddSessionMessage("info", $txt, "Stammdaten- Update erfolgreich");

        return $txt;
    }


    function ImportDefineStandardDokuSet(){
        $user=$_SESSION['active_user'];
        $datum=date('Y-m-d',time());

        include ("./connection.php");

        if ($_SESSION['importcount']==0) {
            $sql = "SELECT COUNT( artikeldaten.artikelid ) as anzahl FROM  `artikeldaten` WHERE artikeldaten.Kontierung='Ersatzteile'
                    ";

            $rst = $mysqli->query($sql);
            if ($mysqli->error) {
                AddSessionMessage("error", "Tabelle artikeldaten enthält keine Daten", "DB Datenbankfehler");
                $_SESSION['importcount'] = 1;
            } else {
                $row_rst = $rst->fetch_assoc();
                $_SESSION['importcount'] = $row_rst['anzahl'];
            }
        }

        if ($_SESSION['importitem']==0) {
            $item=0;
        } else{
            $item=$_SESSION['importitem'];
        }
        $item++;
        $_SESSION['importitem']=$item;

        for ($i=0; $i<=$_SESSION['importcount']; $i++) {

            $sql1 = "SELECT artikeldaten.artikelid FROM  `artikeldaten` WHERE artikeldaten.Kontierung='Ersatzteile' LIMIT " . $i . ", 1";
            $rst1 = $mysqli->query($sql1);
            if ($mysqli->error) {
                AddSessionMessage("error", "Datensatz $item enthält keine Daten", "DB Datenbankfehler");
                $_SESSION['material'] = "";
            } else {
                $row_rst1 = $rst1->fetch_assoc();
                $_SESSION['material'] = $row_rst1['artikelid'];
            }

            if ($_SESSION['material'] > 0) {

                $updatesql = "INSERT INTO  `qsdb`.`dokumente` (
                       
                        `linkedid` ,
                        `dobject` ,
                        `duser` ,
                        `ddatum` ,
                        `dstate` ,
                        `lokz` ,
                        `dnotes` ,
                        `documentlink` ,
                        `username` ,
                        `werk`
                        )
                        VALUES (
                         '" . $_SESSION['material'] . "',  'artikelid',  '10',  '2019-02-26 12:05:22',  '1',  '0',  'Dokuset-Vorlage',  '../artikelid/1551179122Blanco_Flyer.pdf',  'Andreas Veltjens',  '6101'
                        );";
                $rst = $mysqli->query($updatesql);
                $w = new FisWorkflow("Dokuset-Update über FIS-Update erstellt.", "artikelid", $_SESSION['material']);
                $w->Add();
            }
        }


        $txt="Update <code>Material ".$item." von ".$_SESSION['importcount']."</code> Standard-Dokuset aktualisiert";

        AddSessionMessage("info", $txt, "Stammdaten- Update erfolgreich");

        return $txt;
    }





    function UpdateVersionPrintSettings(){
        $user=$_SESSION['active_user'];
        $datum=date('Y-m-d',time());

        include ("./connection.php");

        if ($_SESSION['importcount']==0) {
            $sql = "SELECT COUNT( artikelversionen.id ) as anzahl FROM  `artikelversionen` 
                    ";

            $rst = $mysqli->query($sql);
            if ($mysqli->error) {
                AddSessionMessage("error", "Tabelle artikelversionen enthält keine Daten", "DB Datenbankfehler");
                $_SESSION['importcount'] = 1;
            } else {
                $row_rst = $rst->fetch_assoc();
                $_SESSION['importcount'] = $row_rst['anzahl'];
            }
        }

        if ($_SESSION['importitem']==0) {
            $item=0;
        } else{
            $item=$_SESSION['importitem'];
        }

        for ($i=0; $i<100; $i++) {
            $item++;
            $_SESSION['importitem'] = $item;

            $sql = "SELECT artikelversionen.id FROM  `artikelversionen`  LIMIT " . $item . ", 1";
            $rst = $mysqli->query($sql);
            if ($mysqli->error) {
                AddSessionMessage("error", "Datensatz $item enthält keine Daten", "DB Datenbankfehler");
                $_SESSION['versionid'] = "";
            } else {
                $row_rst = $rst->fetch_assoc();
                $_SESSION['versionid'] = $row_rst['id'];
            }

            if ($_SESSION['versionid'] > 0) {

                $v = new FisVersion(0,0);
                $v->LoadDataById($_SESSION['versionid']);
                $v->ImportPrinterSettingFromFis9();
                $v->SavePrinterSettings();

                $w = new FisWorkflow("Printereinstellungen-Update über FIS-Update erstellt.", "versionid", $_SESSION['versionid']);
                $w->Add();
            }
        }


        $txt="Update <code>Version ".$item." von ".$_SESSION['importcount']."</code> Versionsdaten aktualisiert";

        AddSessionMessage("info", $txt, "Stammdaten- Update erfolgreich");

        return $txt;
    }


    function CreateWorkplacePrinter(){
        $_SESSION['importcount']=100;
        $w= new FisWpConfig();
        $wps=$w->GetWpDefaults();

        if (!is_dir("../".FIS_PRINT_FOLDER."print/typenschild")){
            mkdir("../".FIS_PRINT_FOLDER."print/typenschild",0777);
        }

        if (!is_dir("../".FIS_PRINT_FOLDER."print/verpackung")){
            mkdir("../".FIS_PRINT_FOLDER."print/verpackung",0777);
        }
        if (!is_dir("../".FIS_PRINT_FOLDER."print/dokuset")){
            mkdir("../".FIS_PRINT_FOLDER."print/dokuset",0777);
        }
        if (!is_dir("../".FIS_PRINT_FOLDER."print/wareneingang")){
            mkdir("../".FIS_PRINT_FOLDER."print/wareneingang",0777);
        }
        if (!is_dir("../".FIS_PRINT_FOLDER."print/fehlermeldung")){
            mkdir("../".FIS_PRINT_FOLDER."print/fehlermeldung",0777);
        }
        if (!is_dir("../".FIS_PRINT_FOLDER."print/einbau")){
            mkdir("../".FIS_PRINT_FOLDER."print/einbau",0777);
        }
        if (!is_dir("../".FIS_PRINT_FOLDER."print/gls")){
            mkdir("../".FIS_PRINT_FOLDER."print/gls",0777);
        }

        if (count($wps)){
            foreach ($wps as $wp=>$printers){
                if (count($printers['printer'])){
                    foreach ($printers['printer'] as $p=>$printer){
                        //echo $printer['link'];
                        if (!is_dir("../".FIS_PRINT_FOLDER."".$printer['link'])){
                            mkdir("../".FIS_PRINT_FOLDER."".$printer['link'],0777);
                            $i++;
                        }
                        $x++;
                    }
                }
            }
            AddSessionMessage("info", $i . " Verzeichnisse für  WPPrinter angelegt und " . $x . " Verzeichnisse überprüft", "Update erfolgreich");

        }
        $this->CreateUploadFolder();
        $_SESSION['importitem']=101;
    }

    function CreateUploadFolder()
    {
        $_SESSION['importcount'] = 100;
        if (!is_dir("../tmpupload")) {
            mkdir("../tmpupload", 0777);
        }
        chmod("../tmpupload", 0777);

        $i = 0;
        $r = array("fid", "artikelid", "id", "pid", "idk", "fmid", "thumbnail", "userid", "weid", "rid", "event", "equip");

        if (count($r) > 0) {
            foreach ($r as $index => $item) {
                if (!is_dir("../" . $item)) {
                    mkdir("../" . $item, 0777);
                    AddSessionMessage("success", "Verzeichnis  <code> ../" . $item . "</code> wurde angelegt und Berechtigungen gesetzt.", "Speicherort für Media-Upload angelegt");
                }
                $i++;
            }
        }
        AddSessionMessage("info", $i . " Verzeichnisse für Media- Upload überprüft", "Update erfolgreich");


        $_SESSION['importitem'] = 101;
    }



    function CreateWorkplaceFolder(){
        $_SESSION['importcount']=100;
        $w= new FisWpConfig();
        $wps=$w->GetWpDefaults();
        if (count($wps)){
            foreach ($wps as $wp=>$item){


                $d = "<?php 
                \$_REQUEST[\"wp\"]=$wp;
                \$_GET[\"wp\"]=$wp;
                include(\"./index.php\");
                ";
                file_put_contents($wp . ".php", $d);
                if (!is_dir($wp)) {
                    mkdir($wp, 0777);
                }
                $d = "<?php 
                \$_REQUEST[\"wp\"]=$wp;
                \$_GET[\"wp\"]=$wp;
                header (\"Location: ../$wp.php\");
                ";
                file_put_contents($wp . "/index.php", $d);
                file_put_contents($wp . "/login.php", $d);


            }
            AddSessionMessage("info", "WPFolder angelegt.", "Update erfolgreich");

        }

        $this->InitAllowedUser();
        $_SESSION['importitem']=101;
    }

    function InitAllowedUser()
    {

        $w = new FisWpConfig();
        $wps = $w->GetWpDefaults();
        if (count($wps)) {
            foreach ($wps as $i => $wp) {
                $u = new FisUser(1);
                if (!$u->CheckWpUser(1, $i)) {
                    $u->AddWpUser($i, 1);
                    AddSessionMessage("success", "Benutzerberechtigung " . (1) . " für Arbeitsplatz $i neu gespeichert", "Berechtigung");
                } else {
                    AddSessionMessage("success", "Benutzerberechtigung " . (1) . " für Arbeitsplatz $i war vorhanden", "Berechtigung");
                }
            }
        }

    }

    function FisUpdate(){
        $this->CreateTableFisDatenlogger();
        $this->ChangeErrorMessages();
        $this->ChangeProcessDecissions();
        $this->ChangeProcessDecissionVariants();
        $this->ChangeArticleData();
        $this->ChangeErrors();
        $this->UpdateWarehouseData();
        $this->UpdateFHMData();
        $this->UpdateReportData();
        $this->ChangeFaufTable();
        $this->ImportDefineStandardDokuSet();
    }

    function ShowUpdateResult(){
        $h="kein Update ausgeführt";
        if (count($this->message)){
            foreach ($this->message as $i=>$row){
                $h.=GetHtmlProgressbar($row['header'],$row['value'])." ".$row['text']."<br>";
            }
        }

        return $h;
    }

    function CreateTableEAN13(){

        include ("./connection.php");

        $sql = "CREATE TABLE   `fissapean13` (
        `id` INT NOT NULL AUTO_INCREMENT ,
        `material` INT NOT NULL ,
        `materialdesc` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
        `ean13` INT NOT NULL ,
        PRIMARY KEY (  `id` )
        ) ENGINE = MYISAM";

        $rst=$mysqli->query($sql);
        if ($mysqli->error) {
            AddSessionMessage("error","Create Table <code> fissapean13 </code> konnte nicht erstellt werden.","DB Datenbankfehler");
        }else{
            AddSessionMessage("success","Create Table <code> fissapean13 </code> erfolgreich.","DB Update");
            $this->message[]=array(
                "header"=>"create fissapean13",
                "txt"=>"Create Table <code> fissapean13 </code> erfolgreich.",
                "value"=>"100%",
            );
        }

        return $this;
    }

    function FindImportSAPEAN13($material){

        include ("./connection.php");

        $sql="SELECT * FROM fissapean13 WHERE fissapean13.material='$material' ";

        $rst = $mysqli->query($sql);

        if (mysqli_affected_rows($mysqli)>0){
            $row=$rst->fetch_assoc();
            return $row['material'];
        }else{
            return 0;
        }
        $mysqli->close();
    }

    function ImportSAPEAN13()
    {
        include("./connection.php");
        $_SESSION['importcount']=1;
        $state = 1;

        $folder = "../tmpupload/";
        $files = glob($folder . "*.*");
        foreach ($files as $value) {

            $file = $value;
            // AddSessionMessage("success", "Dokument  <code> " . $file. "</code> wurde hochgeladen und gespeichert.", "Dokumentupload");
            $filename = str_replace($folder, "../import/" . time(), $file);
            AddSessionMessage("info", "Importfile  <code> " . $filename . "</code> gefunden", "Import SAP EAN13 Daten");

            $i = 0;
            $u=0;
            if (file_exists($folder . $file)) {
                rename($file, $filename);
                $d = file_get_contents($filename);

                $r = explode(chr(13), $d);


                if (count($r) > 0) {
                    foreach ($r as $x=>$item) {
                        $input = explode(chr(9), $item);

                        if (strlen($item)>10 && $input[1]<>"Material") {

                        if ($this->FindImportSAPEAN13($input[1])==0){
                            $updateSQL = sprintf("
                        INSERT INTO  fissapean13 (material, materialdesc, ean13) VALUES (%s,%s,%s)",

                                GetSQLValueString($input[1], "int"),
                                GetSQLValueString($input[2], "text"),
                                GetSQLValueString($input[3], "text")
                            );
                            $i++;
                        }else{
                            $updateSQL = sprintf("
                        UPDATE  fissapean13 SET materialdesc=%s, ean13=%s WHERE 
                                fissapean13.material =%s",

                                GetSQLValueString($input[2], "text"),
                                GetSQLValueString($input[3], "text"),
                                GetSQLValueString($input[1], "int")
                            );
                            $u++;
                        }
                            $rst = $mysqli->query($updateSQL);

                            if ($mysqli->error) {

                                AddSessionMessage("warning", "Dokument wurde nicht angelegt, da ein Fehler aufgetreten ist.  <code> " . $filename . "</code> ", "Fehlermeldung");
                            } else {


                            }
                        }
                    }
                }
            }
        }
        AddSessionMessage("info", "Import  <code> " . $i . "</code> Datensätze ". "Update  <code> " . $u . "</code> Datensätze", "Import SAP EAN13 Daten");
        $_SESSION['importitem']=1;
        return $i;
    }

    function ResultSAPEAN13(){

    // $this->CreateTableEAN13();
    $this->ImportSAPEAN13();
        if ($this->SAP) {
            return "Import EAN13 abgeschlossen";
        }
        else{
            return "Fehler";
        }
    }


    function CreateTableFisDatenlogger(){
        include ("./connection.php");

        $sql = "CREATE TABLE   `fisdatalog` (
                `did` BIGINT NOT NULL ,
                `datum` INT NOT NULL DEFAULT  '0',
                `dtype` VARCHAR( 10 ) NOT NULL DEFAULT  'net',
                `data1` VARCHAR( 10 ) NOT NULL DEFAULT  '0',
                `data2` VARCHAR( 10 ) NOT NULL DEFAULT  '0',
                `data3` VARCHAR( 10 ) NOT NULL DEFAULT  '0',
                `data4` VARCHAR( 10 ) NOT NULL DEFAULT  '0',
                `data5` VARCHAR( 10 ) NOT NULL DEFAULT  '0',
                `data6` VARCHAR( 10 ) NOT NULL DEFAULT  '0',
                `data7` VARCHAR( 10 ) NOT NULL DEFAULT  '0',
                `data8` VARCHAR( 10 ) NOT NULL DEFAULT  '0',
                `idid` INT NOT NULL DEFAULT  '0',
                PRIMARY KEY (  `did` )
                ) ENGINE = MYISAM COMMENT =  'ifis Datenlogger'";

        $rst=$mysqli->query($sql);
        if ($mysqli->error) {
            AddSessionMessage("error","Create Table <code> fisdatalog </code> konnte nicht erstellt werden.","DB Datenbankfehler");
        }else{
            AddSessionMessage("success","Create Table <code> fisdatalog </code> erfolgreich.","DB Update");
            $this->message[]=array(
                "header"=>"create datalog",
                "txt"=>"Create Table <code> fisdatalog </code> erfolgreich.",
                "value"=>"100%",
            );
        }
    }

    function ChangeProcessDecissions(){
        include ("./connection.php");
        $sql = "ALTER TABLE    `entscheidung`  ADD  `vkz` TINYINT NOT NULL DEFAULT  '0';";
        $rst=$mysqli->query($sql);
        if ($mysqli->error) {
            AddSessionMessage("error","Change Table <code> entscheidung </code> konnte nicht erstellt werden.","DB Datenbankfehler");
        }else{
            AddSessionMessage("success","Change Table <code> entscheidung </code> erfolgreich.","DB Update");
            $this->message[]=array(
                "header"=>"Change entscheidung",
                "txt"=>"Create Table <code> entscheidung </code> erfolgreich.",
                "value"=>"100%",
            );
        }
    }

    function ChangeProcessDecissionVariants(){
        include ("./connection.php");
        $r=array(
            1 => "ALTER TABLE    `entscheidungsvarianten`  ADD  `vkz` TINYINT NOT NULL DEFAULT  '0';",
            2 => "ALTER TABLE    `entscheidungsvarianten`  ADD  `exchange` TINYINT NOT NULL DEFAULT  '0';",
            3 => "ALTER TABLE    `entscheidungsvarianten`  ADD  `upgrade` TINYINT NOT NULL DEFAULT  '0';",
        );

        foreach ($r as $i=>$sql){
            $rst=$mysqli->query($sql);
            if ($mysqli->error) {
                AddSessionMessage("error","Change Table <code> entscheidungsvarianten </code> konnte nicht erstellt werden.","DB Datenbankfehler");
            }else{
                AddSessionMessage("success","Change Table <code> entscheidungsvarianten </code> erfolgreich.","DB Update");
            }
            $this->message[]=array(
                "header"=>"Change entscheidungsvarianten",
                "txt"=>"Change Table <code> entscheidungsvarianten </code> erfolgreich.",
                "value"=>"100%",
            );
        }
    }



    function ChangeErrorMessages(){
        include ("./connection.php");
        $r=array(
            1 => "ALTER TABLE  `fehlermeldungen`  ADD  `gwl` TINYINT NOT NULL DEFAULT  '0';",
            2 => "ALTER TABLE    `fehlermeldungen`  ADD  `exchange` TINYINT NOT NULL DEFAULT  '0';",
            3 => "ALTER TABLE    `fehlermeldungen`  ADD  `upgrade` TINYINT NOT NULL DEFAULT  '0';",
            4 => "ALTER TABLE    `fehlermeldungen` ADD  `werk` INT NOT NULL DEFAULT  '6101';",
            5 => "ALTER TABLE    `fehlermeldungen` ADD  `fmartikelid2` INT NOT NULL DEFAULT  '0' AFTER  `fmartikelid` ;",
            6 => "ALTER TABLE    `fehlermeldungen` ADD  `kommission` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL AFTER  `fm2variante` ;",
            7=>"
            CREATE TABLE  `fisorderconfirm` (
            `fmoc` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
            `datum` INT NOT NULL DEFAULT  '0' ,
            `order` VARCHAR( 50 ) NOT NULL ,
            `fmids` TEXT NOT NULL
            ) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci
                        
            ",
            8 => "ALTER TABLE  `fisorderconfirm` ADD  `lokz` TINYINT NOT NULL DEFAULT  '0';",
        );

        foreach ($r as $i=>$sql){
            $rst=$mysqli->query($sql);
            if ($mysqli->error) {
                AddSessionMessage("error","Change Table <code> fehlermeldungen </code> konnte nicht erstellt werden.","DB Datenbankfehler");
            }else{
                AddSessionMessage("success","Change Table <code> fehlermeldungen </code> erfolgreich.","DB Update");
                $this->message[]=array(
                    "header"=>"Change fehlermeldungen",
                    "txt"=>"Change Table <code> fehlermeldungen </code> erfolgreich.",
                    "value"=>"100%",
                );
            }
        }
    }




    function UpdateFHMData(){

        include ("./connection.php");
        $r=array(
            1=>"
            CREATE TABLE   `fhm_pruef` (
            `pid` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
            `desc` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
            `nextdatum` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
            `datumremrember` INT NOT NULL ,
            `notes` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
            `lokz` TINYINT NOT NULL DEFAULT  '1'
            ) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci

            ",
            2 => "ALTER TABLE    `fhm_pruef` ADD  `id_contact` INT NOT NULL DEFAULT  '0';",
            3 => "  ALTER TABLE    `fhm_pruef` ADD  `werk` INT NOT NULL DEFAULT  '6101';",

            4 => "  ALTER TABLE    `fhm_pruef` ADD  `frequency` INT NOT NULL DEFAULT  '0';",
            5=>"CREATE TABLE  `db437050_8`.`link_fhm_pid` (
            `lwu` BIGINT( 20 ) NOT NULL AUTO_INCREMENT ,
             `pid` INT( 11 ) NOT NULL ,
             `id_fhm` INT( 11 ) NOT NULL ,
             `t` INT( 11 ) NOT NULL ,
            PRIMARY KEY (  `lwu` )
            ) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci",

            6 => "ALTER TABLE    `fhm_pruef` ADD  `prueftype` INT NOT NULL DEFAULT  '0';"
        );


        foreach ($r as $i=>$sql){
            $rst=$mysqli->query($sql);
            if ($mysqli->error) {
                AddSessionMessage("error","Change Table <code> fhm </code> konnte nicht erstellt werden.","DB Datenbankfehler");
            }else{
                AddSessionMessage("success","Change Table <code> fhm </code> erfolgreich.","DB Update");
                $this->message[]=array(
                    "header"=>"Change fiswareneingang",
                    "txt"=>"Change Table <code> fhm </code> erfolgreich.",
                    "value"=>"100%",
                );
            }
        }

    }


    function UpdateReportData()
    {

        include("./connection.php");
        $r = array(
            1 => "CREATE TABLE `link_artikelid_rid` (
                `lwu` bigint(20) NOT NULL AUTO_INCREMENT,
                `rid` int(11) NOT NULL,
                `artikelid` int(11) NOT NULL,
                `t` int(11) NOT NULL,
                PRIMARY KEY (`lwu`)
                ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1000",

            2 => "CREATE TABLE  `fisreport` (
  `rid` bigint(20) NOT NULL AUTO_INCREMENT,
  `desc` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `nextdatum` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `datumremember` int(11) NOT NULL,
  `notes` text COLLATE utf8_unicode_ci NOT NULL,
  `lokz` tinyint(4) NOT NULL DEFAULT '1',
  `werk` int(11) NOT NULL DEFAULT '6101',
  `frequency` int(11) NOT NULL DEFAULT '0',
  `reporttype` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`rid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1000 ;",

        );


        foreach ($r as $i => $sql) {
            $rst = $mysqli->query($sql);
            if ($mysqli->error) {
                AddSessionMessage("error", "Change Table <code> link_artikel_rid </code> konnte nicht erstellt werden.", "DB Datenbankfehler");
            } else {
                AddSessionMessage("success", "Change Table <code> link_artikel_rid </code> erfolgreich.", "DB Update");
                $this->message[] = array(
                    "header" => "Change fiswareneingang",
                    "txt" => "Change Table <code> link_artikel_rid </code> erfolgreich.",
                    "value" => "100%",
                );
            }
        }

    }

    function UpdateWarehouseData(){

        include ("./connection.php");
         $r=array(
             1 => "ALTER TABLE    `fiswareneingang`  ADD  `we_eti_count` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL;",
         );


        foreach ($r as $i=>$sql){
            $rst=$mysqli->query($sql);
            if ($mysqli->error) {
                AddSessionMessage("error","Change Table <code> fiswareneingang </code> konnte nicht erstellt werden.","DB Datenbankfehler");
            }else{
                AddSessionMessage("success","Change Table <code> fiswareneingang </code> erfolgreich.","DB Update");
                $this->message[]=array(
                    "header"=>"Change fiswareneingang",
                    "txt"=>"Change Table <code> fiswareneingang </code> erfolgreich.",
                    "value"=>"100%",
                );
            }
        }

    }



    function ChangeArticleData(){
        include ("./connection.php");
         $r=array(
             1 => "ALTER TABLE    `artikeldaten`  ADD  `merkmale` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL;",
             2 => "ALTER TABLE    `artikeldaten`  ADD  `supplier` VARCHAR( 200 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL;",
             3 => "ALTER TABLE    `artikeldaten`  ADD  `content` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL;",
             4 => "ALTER TABLE    `artikeldaten`  ADD  `count` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL;",
             5 => "ALTER TABLE   `artikeldaten` 
                ADD  `pcna` INT( 11 ) NOT NULL DEFAULT  '0',
                ADD  `pcnagwl` INT( 11 ) NOT NULL DEFAULT  '1';",

             6 => "UPDATE `artikelversionen` SET `Datum` = ( Select artikelversionenold.Datum FROM artikelversionenold WHERE `artikelversionen`.`id` = artikelversionenold.id)",

        );


        foreach ($r as $i=>$sql){
            $rst=$mysqli->query($sql);
            if ($mysqli->error) {
                AddSessionMessage("error","Change Table <code> artikeldaten </code> konnte nicht erstellt werden.","DB Datenbankfehler");
            }else{
                AddSessionMessage("success","Change Table <code> artikeldaten </code> erfolgreich.","DB Update");
                $this->message[]=array(
                    "header"=>"Change artikeldaten",
                    "txt" => "Change Table <code> artikeldaten </code> erfolgreich. Versions- Datum mit Hilfe der Datensicherung wieder angepasst",
                    "value"=>"100%",
                );
            }
        }
    }


    function ChangePCNANumber($old,$new){
        include ("./connection.php");
        $_SESSION['importitem']=1;
        $_SESSION['importcount']=1;
        $sql="SELECT * FROM artikeldaten WHERE pcna = '$old' ";
        $txt="Change Table with PCNA  <code> $old </code> konnte nicht erstellt werden, da keine Datensätze gefunden wurden.";
        $rst=$mysqli->query($sql);
        if ($mysqli->error) {
            AddSessionMessage("error",$txt,"DB Datenbankfehler");
        }else{
            $sql="UPDATE artikeldaten` SET  `pcna` =  '$new'  FROM artikeldaten WHERE artikeldaten.pcna = '$old' ";
            $rst=$mysqli->query($sql);
            $txt="Change Table PCNA Order Number form  <code> $old </code> to <code> $new </code> erfolgreich.";
            AddSessionMessage("success",$txt,"DB Update");
        }
        $_SESSION['importitem']=2;
        return $txt;

    }

    function ChangePCNAGWLNumber($old,$new){
        include ("./connection.php");
        $_SESSION['importitem']=1;
        $_SESSION['importcount']=1;
        $sql="SELECT * FROM artikeldaten WHERE pcnagwl = '$old' ";
        $txt="Change Table with PCNA GWL <code> $old </code> konnte nicht erstellt werden, da keine Datensätze gefunden wurden.";
        $rst=$mysqli->query($sql);
        if ($mysqli->error) {
            AddSessionMessage("error",$txt,"DB Datenbankfehler");
        }else{
            $sql="UPDATE artikeldaten` SET  `pcnagwl` =  '$new'  FROM artikeldaten WHERE artikeldaten.pcnagwl = '$old' ";
            $rst=$mysqli->query($sql);
            $txt="Change Table PCNA GWL Order Number form  <code> $old </code> to <code> $new </code> erfolgreich.";
            AddSessionMessage("success",$txt,"DB Update");
        }
        $_SESSION['importitem']=2;
        return $txt;

    }




    function ChangeErrors(){
        include ("./connection.php");
        $r=array(
            1 => "ALTER TABLE    `fehler` ADD  `fcolor` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT  'inverse';",



        );

        foreach ($r as $i=>$sql){
            $rst=$mysqli->query($sql);
            if ($mysqli->error) {
                AddSessionMessage("error","Change Table <code> Fehler </code> konnte nicht erstellt werden.","DB Datenbankfehler");
            }else{
                AddSessionMessage("success","Change Table <code> Fehler </code> erfolgreich.","DB Update");
                $this->message[]=array(
                    "header"=>"Change fehlermeldungen",
                    "txt"=>"Change Table <code> Fehler </code> erfolgreich.",
                    "value"=>"100%",
                );
            }
        }
    }

    function ChangeFaufTable()
    {
        include("./connection.php");
        $r = array(
            1 => "CREATE TABLE `fertigungsauftrag` (
                `faufid` BIGINT NOT NULL ,
                `artikelid` INT NOT NULL ,
                `fid` BIGINT NOT NULL ,
                `fauftype` TINYINT( 1 ) NOT NULL DEFAULT  '1',
                `faufstate` TINYINT( 1 ) NOT NULL DEFAULT  '1',
                `lokz` TINYINT( 1 ) NOT NULL DEFAULT  '0',
                `version` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ,
                `faufnotes` TEXT NOT NULL ,
                `profil` TEXT NOT NULL ,
                `config` TEXT NOT NULL ,
                PRIMARY KEY (  `faufid` )
                ) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci",
            2 => "ALTER TABLE  `fertigungsauftrag` ADD  `datum1` BIGINT NOT NULL ,
                ADD  `datum2` BIGINT NOT NULL ;",
            3 => "ALTER TABLE  `fertigungsauftrag` CHANGE  `faufid`  `faufid` BIGINT( 20 ) NOT NULL AUTO_INCREMENT",
            4 => "ALTER TABLE  `fertigungsauftrag` ADD  `process` TEXT NOT NULL ;",

        );

        foreach ($r as $i => $sql) {
            $rst = $mysqli->query($sql);
            if ($mysqli->error) {
                AddSessionMessage("error", "Change Table <code> Fauf </code> konnte nicht erstellt werden.", "DB Datenbankfehler");
            } else {
                AddSessionMessage("success", "Change Table <code> Fauf </code> erfolgreich.", "DB Update");
                $this->message[] = array(
                    "header" => "Change fehlermeldungen",
                    "txt" => "Change Table <code> Fauf </code> erfolgreich.",
                    "value" => "100%",
                );
            }
        }
    }



}