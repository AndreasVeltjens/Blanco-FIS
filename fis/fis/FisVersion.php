<?php

/**
 * Created by PhpStorm.
 * User: andreasveltjens
 * Date: 21.10.18
 * Time: 12:57
 */
class FisVersion
{
    public $versionid=0;
    public $version="";
    public $material=0;
    public $data=array();
    public $dataall=array();
    public $conf=array();
    public $printsettings=array();
    public $process = array();
    public $mprice = 0.00;
    public $aprice = 0.00;
    public $defaultworkplan = array();
    public $limit=500;
    public $search="";
    public $no_free_search_value_allowed=0;


    function __construct($version="version",$material=0)
    {
        $this->version=$version;
        $this->material=$material;
        if (strlen($version)>0 && $material>0){
            $this->LoadData();
        }
    }


    function __toString(){
        return $this->versionid;
    }

    function LoadData(){
        include ("./connection.php");
        $this->data=array();
        $sql="SELECT * FROM artikelversionen WHERE artikelversionen.version='".$this->version."' AND artikelversionen.artikelid='".$this->material."' LIMIT 0,1";

        $rst=$mysqli->query($sql);
        if (mysqli_affected_rows($mysqli)>0){
            $row_rst = $rst->fetch_assoc();
            $this->data=$row_rst;
            $this->versionid=$row_rst['id'];

            $this->conf=json_decode($row_rst['configprofil'],true);
            $this->printsettings=json_decode($row_rst['print'],true);
            $this->process = json_decode($row_rst['process'], true);

        }else{
            $this->data=array();
            AddSessionMessage("error","Version <code> $this->version </code> nicht bekannt.","Datenbankfehler");
        }
    }

    function LoadDataById($id){
        include ("./connection.php");
        $this->data=array();
        $sql="SELECT * FROM artikelversionen WHERE artikelversionen.id='".$id."' LIMIT 0,1";

        $rst=$mysqli->query($sql);
        if (mysqli_affected_rows($mysqli)>0){
            $row_rst = $rst->fetch_assoc();
            $this->data=$row_rst;
            $this->versionid=$row_rst['id'];

            $this->conf=json_decode($row_rst['configprofil'],true);
            $this->printsettings=json_decode($row_rst['print'],true);
            $this->process = json_decode($row_rst['process'], true);

        }else{
            $this->data=array();
            AddSessionMessage("error","Version <code> $this->version </code> nicht bekannt.","Datenbankfehler");
        }
    }
    function LoadAllData()
    {
        if ($this->limit > 0 ) {
            if ($this->no_free_search_value_allowed == 1 && strlen($this->search) == 0) {
                $this->dataall = array();
                return $this;
            } else {
                $s="";

                include("./connection.php");
                $sql = "SELECT * FROM artikelversionen,artikeldaten 
                        WHERE (artikelversionen.version like '%" . $this->search . "%'
                        OR artikelversionen.materialnummer like '%" . $this->search . "%'
                        OR artikelversionen.beschreibung like '%" . $this->search . "%'
                        OR artikelversionen.configprofil like '%" . $this->search . "%'
                        ) 
                        $s
                        AND artikeldaten.artikelid = artikelversionen.artikelid
                        ORDER BY artikeldaten.Kontierung ASC ,artikeldaten.Gruppe ASC , artikeldaten.Bezeichnung DESC LIMIT 0," . $this->limit;

                $rst = $mysqli->query($sql);
                if ($mysqli->error) {

                    AddSessionMessage("error", "Versionen  <code>" . $this->search . "</code> konnten nicht abgefragt werden.", "Fehlermeldung");
                }
                if (mysqli_affected_rows($mysqli) > 0) {
                    $row_rst = $rst->fetch_assoc();
                    do {
                        $this->dataall[] = $row_rst;
                    } while ($row_rst = $rst->fetch_assoc());

                }else{
                    $this->dataall[] = array();
                }
                $mysqli->close();
            }

        }
    }

    function GetNextVersionsID()
    {
        include("./connection.php");
        $id = 0;
        $sql = "SELECT artikelversionen.id, artikelversionen.version as LastIdentifier FROM artikelversionen 
                WHERE artikelversionen.artikelid='" . $this->material . "'
                ORDER BY LastIdentifier DESC LIMIT 0,100";
        $rst = $mysqli->query($sql);
        if (mysqli_affected_rows($mysqli) == 0) {
            $id = 0;
        } else {
            $row_rst = $rst->fetch_assoc();
            $id = intval($row_rst['LastIdentifier']) + 1;
        }
        if ($id < mysqli_affected_rows($mysqli)) {
            $id = mysqli_affected_rows($mysqli) + 1;
        }

        return "Version " . $id;
    }

    function Add()
    {
        include("./connection.php");

        $sql = "SELECT artikelversionen.id FROM artikelversionen WHERE artikelversionen.version = '" . $_REQUEST['new_version'] . "' AND artikelversionen.artikelid='" . $this->material . "' LIMIT 0,1";
        $rst = $mysqli->query($sql);
        if (mysqli_affected_rows($mysqli) == 0) {

            //equip	sendungFHMID	sendungstext	datum	idk	lokz	user	packstuecke	gewicht	sonstiges	werk

            $updateSQL = sprintf("INSERT INTO  artikelversionen (`version`,artikelid) 
                                            VALUES (%s, %s)",

                GetSQLValueString($_REQUEST['new_version'], "text"),
                GetSQLValueString($this->material, "int")

            );


            $rst = $mysqli->query($updateSQL);
            if ($mysqli->error) {

                AddSessionMessage("error", "Version  <code> " . $_REQUEST['new_version'] . "</code> und ArtikelID <code> " . $this->material . "</code> konnte nicht durchgeführt werden.", "Fehlermeldung DB-Error");
            } else {

                $txt = "Version  <code> " . $_REQUEST['new_version'] . "</code> wurde mit Vorlage <code> " . $_SESSION['version'] . " </code>  angelegt.";
                AddSessionMessage("success", $txt, "Version gespeichert");
                $this->versionid = mysqli_insert_id($mysqli);
                $_SESSION['version'] = $_REQUEST['version'];
                $_GET['version'] = $_REQUEST['version'];

                $w = new FisWorkflow($txt, "versionid", $this->versionid);
                $w->Add();
                $this->data['materialnummer'] = ($_POST['materialnummer']);
                $this->data['kundenmaterialnummer'] = ($_POST['kundenmaterialnummer']);
                $this->data['beschreibung'] = $_POST['beschreibung'];
                $this->data['absn'] = $_POST['absn'];
                $this->data['version'] = $_POST['new_version'];
                $this->data['maxload'] = $_POST['maxload'];
                $this->data['powerdesc'] = ($_POST['powerdesc']);
                $this->data['Datum'] = date("Y-m-d", time());
                $this->SaveVersion(false);


            }

        } else {
            AddSessionMessage("error", "Version  <code> " . $_REQUEST['version'] . "</code> und ArtikelID <code> " . $this->material . "</code>  wurde nicht angelegt, weil die Bezeichnung bereits verwendet wurde.", "Bezeichnung fehlerhaft.");

        }
    }

    function SaveNewVersionName($n)
    {
        include("./connection.php");
        $updateSQL = sprintf("
                UPDATE artikelversionen SET version=%s
                WHERE artikelversionen.id='" . $this->versionid . "' ",

            GetSQLValueString($n, "text")
        );
        $rst = $mysqli->query($updateSQL);
        if ($mysqli->error) {

            AddSessionMessage("error", "Versionsänderung <code> " . $this->data['version'] . " </code> konnte nicht durchgeführt werden.", "Fehlermeldung");
        } else {

            $txt = "Versionsbezeichnung von " . $this->data['version'] . " auf <code> " . $n . " </code> wurde geändert.";
            AddSessionMessage("success", $txt, "Update Versionsdaten");
            $w = new FisWorkflow($txt, "versionid", $this->versionid);
            $w->Add();
        }
    }

    function SaveVersion($post=true){
        include ("./connection.php");

        if ($post){
            $this->data['materialnummer']=($_POST['materialnummer']);
            $this->data['kundenmaterialnummer']=($_POST['kundenmaterialnummer']);
            $this->data['beschreibung']=$_POST['beschreibung'];
            $this->data['absn']=$_POST['absn'];
            $this->data['version']=$_POST['version'];
            $this->data['maxload']=$_POST['maxload'];
            $this->data['powerdesc']=($_POST['powerdesc']);
            $this->data['print']=$_POST['print'];
            $this->data['configprofil']=$_POST['configprofil'];
            $this->data['process'] = $_POST['process'];
            $this->data['Datum']=$_POST['Datum'];
            $this->data['pruefzeichen']=$_POST['pruefzeichen'];
        }

        if (strlen($this->data['materialnummer']) == 0 or $this->data['materialnummer'] == 0) {
            $this->data['materialnummer'] = 100000 + $this->material;
        }

        $updateSQL=sprintf("
                UPDATE artikelversionen SET artikelversionen.materialnummer=%s, artikelversionen.kundenmaterialnummer=%s, artikelversionen.beschreibung=%s,
                artikelversionen.absn=%s, artikelversionen.version=%s, artikelversionen.maxload=%s, artikelversionen.powerdesc=%s,
                artikelversionen.print=%s, artikelversionen.configprofil=%s, artikelversionen.process=%s, artikelversionen.datum=%s	
                WHERE artikelversionen.id='".$this->versionid."' ",
                GetSQLValueString(utf8_decode($this->data['materialnummer']),"text"),
                GetSQLValueString(utf8_decode($this->data['kundenmaterialnummer']),"text"),
                GetSQLValueString(utf8_decode($this->data['beschreibung']),"text"),
                GetSQLValueString($this->data['absn'],"int"),
                GetSQLValueString($this->data['version'],"text"),
                GetSQLValueString(utf8_decode($this->data['maxload']),"text"),
                GetSQLValueString(utf8_decode($this->data['powerdesc']),"text"),
            GetSQLValueString(utf8_decode($this->data['print']), "text"),

            GetSQLValueString(utf8_decode($this->data['configprofil']), "text"),
            GetSQLValueString(utf8_decode($this->data['process']), "text"),
            GetSQLValueString($this->data['Datum'], "text")

        );
        $rst = $mysqli->query($updateSQL);
        if ($mysqli->error) {

            AddSessionMessage("error","Version <code> ".$this->data['version']." </code> konnte nicht durchgeführt werden.","Fehlermeldung");
        }else{

            $txt="Version <code> ".$this->data['version']." </code> wurde gespeichert.";
            AddSessionMessage("success", $txt, "Update Versionsdaten");
            $w=new FisWorkflow($txt,"versionid",$this->versionid);
            $w->Add();
        }
        $d=new FisDocument("Dokument zur Version","versionid",$this->versionid);
        $d->AddNewDocument();


        if (isset($_REQUEST['save_version_as_free'])){

            $updateSQL=sprintf("
                UPDATE artikeldaten SET artikeldaten.version=%s
                WHERE artikeldaten.artikelid='".$this->material."' ",
                GetSQLValueString($this->data['version'],"text")
            );
            $rst = $mysqli->query($updateSQL);
            if ($mysqli->error) {
                AddSessionMessage("error","Versionsfreigabe <code> ".$this->data['version']." </code> konnte nicht durchgeführt werden.","Fehlermeldung");
            }else{
                $txt="Version <code> ".$this->data['version']." </code> wurde als aktive Version freigeben.";
                AddSessionMessage("success", $txt, "Freigabe Version");
                $w=new FisWorkflow($txt,"versionid",$this->versionid);
                $w->Add();
            }
        }

    }

    function SavePrintSettings($arr)
    {
        if (count($arr) > 0) {
            foreach ($arr as $p => $item) {
                $this->printsettings[$p]['active'] = $item;
                if ($item == 1) {
                    $this->printsettings[$p]['count'] = 1;
                } else {
                    $this->printsettings[$p]['count'] = 0;
                }
            }
            include("./connection.php");
            $updateSQL = sprintf("
            UPDATE artikelversionen SET artikelversionen.print=%s WHERE artikelversionen.id='" . $this->versionid . "' ",
                GetSQLValueString(utf8_decode(json_encode($this->printsettings)), "text")
            );
            $rst = $mysqli->query($updateSQL);
            if ($mysqli->error) {
                AddSessionMessage("error", "Version <code> " . $this->data['version'] . " </code> konnte nicht durchgeführt werden.", "Fehlermeldung");
            } else {

                $txt = "Version Druckeinstellungen <code> " . $this->data['version'] . " </code> wurden angepasst.";
                $w = new FisWorkflow($txt, "versionid", $this->versionid);
                $w->Add();
            }
        }

    }


    function SavePrinterSettings(){
        include ("./connection.php");
        $updateSQL=sprintf("
                UPDATE artikelversionen SET artikelversionen.print=%s
                 WHERE artikelversionen.id='".$this->versionid."' ",
            GetSQLValueString(json_encode($this->printsettings),"text")
        );
        $rst = $mysqli->query($updateSQL);
        if ($mysqli->error) {
            AddSessionMessage("error","Version <code> ".$this->data['version']." </code> konnte nicht durchgeführt werden.","Fehlermeldung");
        }else{
            $txt="Druckeinstellungen für Version  <code> ".$this->data['version']." </code> wurde gespeichert.";
            AddSessionMessage("success", $txt, "Update Versionsdaten");

        }
    }

    //config
    function LoadPrintSettings($p)
    {
        $this->printsettings=json_decode($p,true);
        return $this;
    }

    function vdeLoadConfig($p)
    {
        $this->conf=json_decode($p,true);
        return $this;
    }



    function ShowConfigAsPicture(){
        $h="";
        if (count($this->conf)){
            foreach ($this->conf as $i=>$value){
                if (!is_null($value)){
                    if ($i=="Artikel"){
                        $h.="<b>".$value."</b>";
                    }else{
                        $h.="<img src=\"../picture/update2/$value.png\" width=\"50\" height=\"50\" align=\"absmiddle\"> ";
                    }
                }
            }
        }
        if (strlen($h)==0) $h="nicht konfiguriert";

        return $h;
    }

    function GetGuiltySerialNumber(){
        return  sprintf("%06.0f",$this->data['absn']);
    }

    function GetEAN13(){
        $ean13="";
        if (count(json_decode($this->data['ean13']))>0){
            $ean13=$this->GetEAN13FromConfig();
        }else{
            $ean13=$this->data['ean13'];
        }
        return $ean13;
    }

    function GetEAN13FromConfig(){
        return "GetEAN13FromConfig";
    }

    function GetMaterial(){
        return $this->data['materialnummer'];
    }

    function GetAllMaterialNumbers(){
        $h="";
        if (count($this->conf)>0){
            foreach ($this->conf as $item=>$k){
                //$h.=$item."<br>";
                if (is_array($k) && count($k)>0){
                    foreach ($k as $y=>$x){
                        if (!is_array($x)) $h.=$x."<br>";
                        if (is_array($x) && count($x)>0){
                            foreach ($x as $s=>$z){
                                //$h.=$s."<br>";
                                if (is_array($z)) {
                                    foreach ($z as $z1 => $z2) {
                                        $h .= $z2 . " ";
                                    }
                                    //$h.="<br>";
                                }
                            }
                        }
                    }
                }
            }
        }
        return $h;
    }

    function FindImportSAPEAN13($number=0){

        if ($number==0){
            $number=intval($this->data['materialnummer']);
        }
        include ("./connection.php");
        $sql="SELECT * FROM fissapean13 WHERE fissapean13.material='".$number."' ";
        $rst = $mysqli->query($sql);
        if (mysqli_affected_rows($mysqli)>0){
            $row=$rst->fetch_assoc();

            return $row['ean13'];
        }else{
            return false;
        }

    }
    function GetAllMaterialNumbersAndEANCodes(){
        $h="<table class='table table-responsive table-striped'><thead>
            <tr><td>Material</td><td>EAN13 Code</td></tr>
            </thead><tbody>";
        if (count($this->conf)>0){
            foreach ($this->conf as $item=>$k){
                $h.="<tr><td>".$item."</td><td>".$this->FindImportSAPEAN13($k)."</td></tr>";
                if (is_array($k) && count($k)>0){
                    foreach ($k as $y=>$x){
                        if (!is_array($x)) $h.="<tr><td>".$x."</td><td>".$this->FindImportSAPEAN13($x)."</td></tr>";

                        if (is_array($x) && count($x)>0){
                            foreach ($x as $s=>$z){
                                foreach ($z as $z1=>$z2){
                                    $h.="<tr><td>".$z2."</td><td>".$this->FindImportSAPEAN13($z2)."</td></tr>";
                                }
                            }
                        }
                    }
                }
            }
        }else{
            $m = new FisMaterial($this->material);
            $h .= "<tr><td>" . $m->GetMaterial() . "</td><td>" . $m->GetEAN13() . "</td></tr>";
            $h .= "<tr><td>Dieses Material ist nicht konfiguriert.</td><td></td></tr>";

        }
        $h."</tbody></table>";
        return $h;
    }

    function GetProcessasTable($level = 0)
    {
        $aprice = 0;
        $mprice = 0;
        $i = 0;
        $this->defaultworkplan = array();
        $_SESSION['workplan'][$this->data['artikelid']] = array();

        $h = "kein Plan gefunden.";
        $material = new FisMaterial($this->data['artikelid']);
        if ($material->data['aktivrep']) {

            if (count($this->process) > 0 && isset($this->process['Arbeitsplan'])) {
                $h = "<table class='table table-responsive table-striped'>";

                $h .= "<tbody>";
                foreach ($this->process['Arbeitsplan'] as $step => $item) {
                    $_SESSION['div']++;
                    $i++;

                    $asingleprice = $item['Arbeitsplatzkosten'] / 60 * ($item['Rüstzeit'] + $item['Vorgangszeit'] + $item['Handarbeitszeit']);
                    $aprice += $asingleprice;

                    $_SESSION['workplan'][$_SESSION['material']][] = array("level" => $level, "item" => $_SESSION['div'], "material" => $this->GetMaterial(), "artikelid" => $this->material, "desc" => $item['Vorgangsbezeichnung'], "setuptime" => $item['Rüstzeit']);

                    $h .= "<tr>";
                    $h .= "<td>" . $step . "</td>";
                    $h .= "<td>" . $item['Vorgangsbezeichnung'] . "</td>";
                    $h .= "<td>" . GetCurancy($asingleprice) . "</td>";
                    $h .= "<td>
                            <a href='#process_step_anchor" . $_SESSION['div'] . "' onclick='\$(\"#process_step" . $_SESSION['div'] . "\").show()'><i class='fa fa-eye'></i> </a> 
                            <a href='#process_step_anchor" . $_SESSION['div'] . "' onclick='\$(\"#process_step" . $_SESSION['div'] . "\").hide()'><i class='fa fa-eye-slash'></i> </a> 

                           </td>";
                    $h .= "<td>";
                    if (count($this->process['Arbeitsplan'][$step]['Stückliste']) > 0 && isset($this->process['Arbeitsplan'][$step]['Stückliste'])) {
                        $h .= "<a id=\"process_step_anchor" . $_SESSION['div'] . "\"></a>";
                        $h .= "<div id='process_step" . $_SESSION['div'] . "' >";
                        $h .= "<table class='table table-responsive table-striped'>";
                        $h .= "<tbody>";
                        foreach ($this->process['Arbeitsplan'][$step]['Stückliste'] as $pos => $material) {

                            $h .= "<tr>";
                            $s = GetArtikelName("isFisArtikelReturnArtikelid", $material['Material']);
                            if ($s > 0) {
                                $m = new FisMaterial($s);
                                $msingleprice = $m->GetPrice() * $material['Menge'];
                                $mprice += $msingleprice;


                                $link = "<a href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=" . $_SESSION['wp'] . "&action=15&equip=&materialgroup=&material=" . $s . "&version=&fid=\" target='_blank'> %s </a>";
                                $link2 = "<a href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=" . $_SESSION['wp'] . "&action=17&equip=&materialgroup=&material=" . $s . "&version=" . $material['Version'] . "&fid=\" target='_blank'> %s </a>";

                                $h .= "<td>" . $pos . "</td>";
                                $h .= "<td>" . $m->GetPicture() . "</td>";
                                $h .= "<td>" . sprintf($link, $m->GetMaterial()) . "</td>";
                                $h .= "<td>" . sprintf($link2, $material['Version']) . "</td>";
                                $h .= "<td>" . $m->GetMaterialName() . "</td>";
                                $h .= "<td>" . $material['Menge'] . "</td>";
                                $h .= "<td>" . GetCurancy($m->GetPrice()) . "</td>";
                                $h .= "<td>" . GetCurancy($msingleprice);
                            } else {
                                $msingleprice = $material['Preis'] * $material['Menge'];
                                $mprice += $msingleprice;
                                $h .= "<td>" . $pos . "</td>";
                                $h .= "<td> <i class='fa fa-minus-circle'> </i> </td>";
                                $h .= "<td>" . $material['Material'] . "</td>";
                                $h .= "<td>" . $material['Version'] . "</td>";
                                $h .= "<td>" . $material['Bezeichnung'] . "</td>";
                                $h .= "<td>" . $material['Menge'] . "</td>";
                                $h .= "<td>" . $material['Preis'] . "</td>";
                                $h .= "<td>" . GetCurancy($msingleprice);
                            }
                            $h .= "</td></tr>";
                            if (GetArtikelName("searchbymaterial", $material['Material']) == $this->data['artikelid']) {
                                AddSessionMessage("warning", "Rekusive Stückliste mit Material " . $material['Material'] . "gefunden", "Stücklistenfehler");
                            } else {
                                $v = new FisVersion($material['Version'], GetArtikelName("searchbymaterial", $material['Material']));
                                $h .= "<tr><td colspan='8'>" . $v->GetProcessasTable($level + 1) . "</td></tr>";

                                $mprice += $v->mprice;
                                $aprice += $v->aprice;
                            }
                        }
                        $h .= "</tbody>";
                        $h .= "</table>";
                        $h .= "</div>";

                    }
                    $h .= "</td></tr>";


                }
                $h .= "</tbody>";
                $h .= "</table>";

                $h .= "<table class='table table-responsive table-striped'>";
                $h .= "<thead><tr>
                <th>Material</th>
                <th>Materialkosten</th>
                <th>Arbeitsplatzkosten</th>
                <th>Gesamt</th>
                </tr></thead>";
                $h .= "<tbody>";
                $h .= "<tr><td><b>";
                $h .= GetArtikelName("text", $this->data['artikelid']);
                $h .= "</b></td><td><b>";
                $h .= GetCurancy($mprice);
                $h .= "</b></td><td><b>";
                $h .= GetCurancy($aprice);
                $h .= "</b></td><td><b>";
                $h .= GetCurancy($mprice + $aprice);
                $h .= "</b></td></tr>";
                $h .= "</tbody>";
                $h .= "</table>";

            }
            $this->mprice = $mprice;
            $this->aprice = $aprice;
        } else {
            $h = "Schüttgut / Nur Einkauf";
            $this->mprice = $material->GetPrice();
            $this->aprice = 0;
            $this->defaultworkplan = $_SESSION['workplan'][$this->data['artikelid']];
        }

        $h .= $this->GetWorkplan();

        return $h;
    }


    function GetWorkplan()
    {

        $h = "Standard-Arbeitsplan <br>";

        if ($this->data['artikelid'] == $_SESSION['material']) {

            if (is_array($_SESSION['workplan'][$this->data['artikelid']]) && count($_SESSION['workplan'][$this->data['artikelid']]) > 0) {
                $levels = array_column($_SESSION['workplan'][$this->data['artikelid']], 'level');
                array_multisort($levels, SORT_DESC, $_SESSION['workplan'][$this->data['artikelid']]);
                $h .= "<ul>";
                foreach ($_SESSION['workplan'][$this->data['artikelid']] as $vorgang) {
                    $h .= "<li>" . $vorgang['level'] . " - " . $vorgang['item'] . " - " . $vorgang['desc'] . " - " . $vorgang['material'] . " " . GetArtikelName("text", $vorgang['artikelid']) . "</li>";
                }
                $h .= "</ul>";
            } else {
                $h .= "kein Arbeitsplan gefunden.";
            }
        }

        return $h;
    }

    function GetCustomerMaterial(){
        return $this->data['kundenmaterialnummer'];
    }

    function GetVersionDatum(){
        $d=strtotime($this->data['Datum']);
        return date("Y-m-d",$d);
    }

    function GetPowerDesc(){
        return $this->data['powerdesc'];
    }



    function GetMaxLoad(){
        return $this->data['maxload'];
    }

    function GetVersionName(){
        return $this->data['version'];
    }
    function GetVersionDesc(){
        return $this->data['beschreibung'];
    }
    function GetPictureLink(){
        return "../config/etikett_logo_color_" . FIS_ID . ".png";
    }
    function GetPicture(){
        return "<img src=\"".$this->GetPictureLink()."\" class=\"thumb-lg\" alt='Material'>";
    }


    function GetStatus($type="text"){
        $t="";
        $m=new FisMaterial($this->material);
        if ($this->version==$m->activeversion['version']){
            if ($type=="text") $t="aktive Version";
        }else{
            if ($type=="text") $t="Version wird nicht mehr verwendet";
        }
        return $t;
    }


    function GetPrinterDefaults(){
        if (count(json_decode($this->data['print'],true))>0 or strlen($this->data['print']>2) ) {
            $t = json_decode($this->data['print'], true);
        }else{
            $t=GetPrinterDefaults();
            $this->data['print']=json_encode($t);
            $this->printsettings=$t;
        }
        return $t;
    }

    function ImportPrinterSettingFromFis9(){
        $t=GetPrinterDefaults();
        if (isset($this->printsettings)){
            $s= array_merge($t,$this->printsettings);
        }else{
            $s=$t;
        }
        $s['typenschild']['layout']['logo']=1;
        $s['verpackung']['layout']['logo']=1;
        $s['einbau']['layout']['logo']=1;


        if ($this->GetIconInfo()){  $s['typenschild']['layout']['info']=1;  }else{$s['typenschild']['layout']['info']=1; }
        if ($this->GetIconInfo()){  $s['verpackung']['layout']['info']=0;   }else{$s['verpackung']['layout']['info']=0;  }
        if ($this->GetIconCE()){    $s['typenschild']['layout']['ce']=1;    }else{$s['typenschild']['layout']['ce']=0;   }
        if ($this->GetIconCE()){    $s['verpackung']['layout']['ce']=0;     }else{$s['verpackung']['layout']['ce']=0;    }
        if ($this->GetIconIPX4()){  $s['typenschild']['layout']['ipx4']=1;  }else{$s['typenschild']['layout']['ipx4']=0; }
        if ($this->GetIconIPX4()){  $s['verpackung']['layout']['ipx4']=0;   }else{$s['verpackung']['layout']['ipx4']=0;  }
        if ($this->GetIconIPX6()){  $s['typenschild']['layout']['ipx6']=1;  }else{$s['typenschild']['layout']['ipx6']=0; }
        if ($this->GetIconIPX6()){  $s['verpackung']['layout']['ipx6']=0;   }else{$s['verpackung']['layout']['ipx6']=0;  }
        if ($this->GetIconVDE()){   $s['typenschild']['layout']['vde']=1;   }else{$s['typenschild']['layout']['vde']=0;  }
        if ($this->GetIconVDE()){   $s['verpackung']['layout']['vde']=0;    }else{$s['verpackung']['layout']['vde']=0;   }
        if ($this->GetIconVDEGS()){ $s['typenschild']['layout']['vdegs']=1; }else{$s['typenschild']['layout']['vdegs']=0;}
        if ($this->GetIconVDEGS()){ $s['verpackung']['layout']['vdegs']=0;  }else{$s['verpackung']['layout']['vdegs']=0; }
        if ($this->GetIconEAC()){   $s['typenschild']['layout']['eac']=1;   }else{$s['typenschild']['layout']['eac']=0;  }
        if ($this->GetIconEAC()){   $s['verpackung']['layout']['eac']=1;    }else{$s['verpackung']['layout']['eac']=0;   }
        if ($this->GetIconWEEE()){  $s['typenschild']['layout']['weee']=1;  }else{$s['typenschild']['layout']['weee']=0; }
        if ($this->GetIconWEEE()){  $s['verpackung']['layout']['weee']=0;   }else{$s['verpackung']['layout']['weee']=0;  }

        $this->printsettings=$s;
        return $s;
    }

    function GetIcon($type="typenschild",$icon="info"){
        $state= false;
        if (isset($this->printsettings[$type]['layout'][$icon]) ){
            $state=$this->printsettings[$type]['layout'][$icon];
        }

        return $state;
    }

    function ShowHtmlIcons2($type="typenschild"){
        $h="";
        if ($this->GetIcon("$type","logo")){
            $h .= "<img src='../config/etikett_logo_" . FIS_ID . ".png' class='img thumb-sm'> ";
        }
        if ($this->GetIcon("$type","info")){
            $h.="<img src='../picture/info.png' class='img thumb-sm'> ";
        }
        if ($this->GetIcon("$type","ce")){
            $h.="<img src='../picture/ce.png' class='img thumb-sm'> ";
        }
        if ($this->GetIcon("$type","ipx4")){
            $h.="<h4>IPX4</h4>";
        }
        if ($this->GetIcon("$type","ipx6")){
            $h.="<h4>IPX6</h4>";
        }
        if ($this->GetIcon("$type","vde")){
            $h.="<img src='../picture/vde.png' class='img thumb-sm'> ";
        }
        if ($this->GetIcon("$type","vdegs")){
            $h.="<img src='../picture/vdegs.png' class='img thumb-sm'> ";
        }
        if ($this->GetIcon("$type","eac")){
            $h.="<img src='../picture/eac.png' class='img thumb-sm'> ";
        }
        if ($this->GetIcon("$type","weee")){
            $h.="<img src='../picture/weee.png' class='img thumb-sm'> ";
        }
        return $h;
    }

    function ShowHtmlIcons(){
        $h="";
        if ($this->GetIconInfo()){
            $h.="<img src='../picture/info.png' class='img thumb-sm'> ";
        }
        if ($this->GetIconCE()){
            $h.="<img src='../picture/ce.png' class='img thumb-sm'> ";
        }
        if ($this->GetIconIPX4()){
            $h.="<h4>IPX4</h4>";
        }
        if ($this->GetIconIPX6()){
            $h.="<h4>IPX6</h4>";
        }
        if ($this->GetIconVDE()){
            $h.="<img src='../picture/vde.png' class='img thumb-sm'> ";
        }
        if ($this->GetIconVDEGS()){
            $h.="<img src='../picture/vdegs.png' class='img thumb-sm'> ";
        }
        if ($this->GetIconEAC()){
            $h.="<img src='../picture/eac.png' class='img thumb-sm'> ";
        }
        if ($this->GetIconWEEE()){
            $h.="<img src='../picture/weee.png' class='img thumb-sm'> ";
        }
        return $h;
    }

    function GetIconInfo(){
        if (stripos($this->data['pruefzeichen'],"info")!==false) {
            return true;
        }else{
            return false;
        }
    }
    function GetIconCE(){
        if (stripos($this->data['pruefzeichen'],"ce")!==false) {
            return true;
        }else{
            return false;
        }
    }

    function GetIconIPX4(){
        if (stripos($this->data['pruefzeichen'],"ipx4")!==false) {
            return true;
        }else{
            return false;
        }
    }

    function GetIconIPX6(){
        if (stripos($this->data['pruefzeichen'],"ipx6")!==false) {
            return true;
        }else{
            return false;
        }
    }



    function GetIconWEEE(){
        if (stripos($this->data['pruefzeichen'],"weee")!==false) {
            return true;
        }else{
            return false;
        }
    }

    function GetIconEAC(){
        if (stripos($this->data['pruefzeichen'],"eac")!==false) {
            return true;
        }else{
            return false;
        }
    }

    function GetIconVDE(){
        if (stripos($this->data['pruefzeichen'],"vde")!==false) {
            return true;
        }else{
            return false;
        }
    }

    function GetIconVDEGS(){
        if (stripos($this->data['pruefzeichen'],"vdgs")!==false) {
            return true;
        }else{
            return false;
        }
    }

    function SetSearchString($s=""){
        $this->search=utf8_encode($s);
        return $this;
    }

    function GetSearchString(){
        $s=utf8_decode($this->search);
        return $s;
    }
    function SetLimit($l=100){
        if  (intval($l) <501){
            $this->limit=intval($l);
            if ($this->limit <=10){
                $this->limit=10;
            }
        }else{
            $this->limit=10;
        }
        return $this;
    }

    function GetLimit(){
        return $this->limit;
    }

    function SetNoFreeValueAllowed($s=0){
        $this->no_free_search_value_allowed=$s;
        return $this;
    }

    function ShowHtmlAllAsTable($status="1",$limit=50,$search="",$no_free_search_value_allowed=0){
        $this->dataall=array();
        $this->SetSearchString($search);
        $this->SetLimit($limit);
        $this->SetNoFreeValueAllowed($no_free_search_value_allowed);
        $this->LoadAllData();

        $h=asModal(1000,"<div id='preview'></div>","",60,"Vorschau");
        $h.= "<h4 class=\"header-title m-t-0 m-b-30\">Versionsdaten Suchbegriff:(".$this->GetSearchString().") </h4>";

        if (count($this->dataall) > 0) {

            $_SESSION['datatable']++;
            $_SESSION['script_datatable'].="
            
            var table  = $(\"#datatable".$_SESSION['datatable']."\").DataTable({
                            
                    
                            language: {
                                \"lengthMenu\": \"Zeige _MENU_ Datensätze pro Seite\",
                                \"zeroRecords\": \"Nichts gefunden - sorry\",
                                \"info\": \"Zeige Seite _PAGE_ von _PAGES_\",
                                \"infoEmpty\": \"Keine Treffer gefunden\",
                                \"infoFiltered\": \"(gefiltert von _MAX_ Datensätzen)\"
                            },
                            dom: \"<lengthMenu> Bfrtip\",
                            buttons: [{extend: \"copy\", className: \"btn-sm\"}, {extend: \"csv\", className: \"btn-sm\"}, {
                                extend: \"excel\",
                                className: \"btn-sm\"
                            }, {extend: \"pdf\", className: \"btn-sm\"}, {extend: \"print\", className: \"btn-sm\"}],
                            responsive: !0,
                            lengthMenu: [[50,10, 25, 50, -1], [50,10, 25, 50, \"All\"]]
                        });
                    
                        var handleDataTableButtons = function () {
                            \"use strict\";
                            0 !== $(\"#datatable".$_SESSION['datatable']."\").length && table
                        }, TableManageButtons = function () {
                            \"use strict\";
                            return {
                                init: function () {
                                    handleDataTableButtons()
                                }
                            }
                        }();

           function ShowPreview(pic){
                $('#preview').html('<img src=\"'+pic+'\" width=\"100%\">');
             };
            ";

            $h.=" <table id=\"datatable".$_SESSION['datatable']."\" class=\"table table-striped dt-responsive nowrap\">
                    <thead>
                        <tr>
                         <th>Bild</th>
                         <th>Version</th>
                         <th>Material</th>
                         <th>Konfig</th>
                         <th>Nummer</th>
                         <th>Bezeichnung</th>
                         <th>Kontierung</th>
                         <th>Warengruppe</th>
                         <th>Produktfamilie</th>
                         <th>aktive Version</th>
                         <th>FIS</th>
                         <th>REP</th>
                         <th>Aktion</th>
                        </tr>
                    </thead>
                    <tbody>
                                       ";
            $old_group="";
            $old_productfamily="";
            $g="";
            $p="";
            foreach ($this->dataall as $d => $i) {
                if ($i['artikelid']>0) {
                    $e= new FisMaterial($i['artikelid']);
                    $v= new FisVersion($i['version'],$i['artikelid']);
                    $link = "<a class='btn btn-custom btn-sm' href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=" . $_SESSION['wp'] . "&action=15&equip=&materialgroup=&material=" . $i['artikelid'] . "&version=&fid=\" onclick='refresh_site()'> Bearbeiten </a>";

                    if ($old_group<>$i['Gruppe']) {
                        $g= "<span class='label label-info'> " . $i['Gruppe'] . " </span>";
                    }else{
                        $g=$i['Gruppe'];
                    }
                    // if ($old_productfamily<>$i['productfamily']){

                    $l="";
                    switch ($i['productfamily']){
                        case "grau":{ $l="label-inverse"; break;}
                        case "blau":{ $l="label-primery"; break;}
                        case "grün":{ $l="label-success"; break;}
                        case "rot":{ $l="label-danger"; break;}
                        case "gelb":{ $l="label-warning"; break;}
                    }
                    $p="<span class='label $l'> ".$i['productfamily']." </span>";
                    // }else{
                    //    $p=$i['productfamily'];
                    // }



                    $h .= "<tr>
                        <td><a href=\"#custom-width-modal1000\" data-toggle=\"modal\" data-target=\"#custom-width-modal1000\" 
                           onclick=\"ShowPreview('".$e->GetPictureLink()."');\" >           
                        ".$e->GetPicture()."</a></td>
                        <td>" .$i['version'] . "</td>
                        <td>" .$i['materialnummer'] . "</td>
                         <td>" .$v->GetAllMaterialNumbers() . "</td>
                        <td>" . $e->GetMaterial() . "</td>
                        <td>" . ($i['Bezeichnung']) . "</td>
                        <td>" . ($i['Kontierung']) . "</td>
                        <td>" . $g . "</td>
                        <td>" . $p . "</td>
                         <td>" . ($i['version']) . "</td>
                        <td>" . GetYesNoList("text",$i['aktiviert']) . "</td>
                        <td>" . GetYesNoList("text",$i['aktivrep']) . "</td>

                       ";
                    $h .= "  <td>" . "$link" . "</td>";
                    $h .= "</tr>";
                    $old_group=$i['Gruppe'];
                    // $old_productfamily=$i['productfamily'];
                }
            }
            $h.="</tbody></table>";
        }else {
            $h .= "keine Materialdaten gefunden.";
        }
        return $h;
    }


    function ShowPropertyAsTable(){

        $h="<div class=\"table-responsive\">
                <table class=\"table table-striped\">
                  <tbody><tr>";
        $h .= "<td>Material</td>";
        $h .= "<td>" . GetArtikelName("text", $this->data['artikelid']) . "</td>";
        $h .= "</tr><tr>";
        $h.="<td>Version</td>";
        $h.="<td>".$this->GetVersionName()."</td>";
        $h.="</tr><tr>";
        $h.="<td>Materialnummer</td>";
        $h.="<td>".$this->GetMaterial()."<br>".$this->GetAllMaterialNumbers()."</td>";
        $h.="</tr><tr>";
        $h.="<td>Kundenmaterialnummer</td>";
        $h.="<td>".$this->GetCustomerMaterial()."</td>";
        $h.="</tr><tr>";
        $h.="<td>Beschreibung</td>";
        $h.="<td>".$this->GetVersionDesc()."</td>";
        $h.="</tr><tr>";

        $h.="<td>Leistungangabe</td>";
        $h.="<td>".$this->GetPowerDesc()."</td>";
        $h.="</tr><tr>";
        $h.="<td>Traglast</td>";
        $h.="<td>".$this->GetMaxLoad()."</td>";
        $h.="</tr><tr>";
        $h.="<td>Druckeinstellungen Typenschild</td>";
        $h.="<td>".$this->ShowHtmlIcons2()."</td>";
        $h.="</tr><tr>";
        $h.="<td>Druckeinstellungen Verpackung</td>";
        $h.="<td>".$this->ShowHtmlIcons2("verpackung")."</td>";
        $h.="</tr><tr>";
        $h.="<td>Konfigurationsstatus</td>";
        $h.="<td>".$this->ShowProfilStatus()."</td>";
        $h.="</tr><tr>";
        $h.="</tr><tr>";
        $h.="<td>Konfiguration</td>";
        $h.="<td>".$this->ShowConfig()."</td>";
        $h.="</tr><tr>";
        $h.="<td>ab Seriennumer</td>";
        $h.="<td>".$this->GetGuiltySerialNumber()."</td>";
        $h.="</tr><tr>";
        $h.="<td>Datum</td>";
        $h.="<td>".$this->GetVersionDatum()."</td>";
        $h.="</tr><tr>";
        $h.="<td>Status</td>";
        $h.="<td>".$this->GetStatus("text")."</td>";



        $h.="</tr></tbody></table></div>";
        return $h;
    }

    function ProveNewVersion($n)
    {
        if (strlen($n) > 0) {
            include("./connection.php");
            $sql = "SELECT artikelversionen.id, artikelversionen.version FROM artikelversionen 
                WHERE artikelversionen.artikelid='" . $this->material . "'
                AND artikelversionen.version='" . $n . "'
                LIMIT 0,1";
            $rst = $mysqli->query($sql);

            if (mysqli_affected_rows($mysqli) == 0) {
                $h = "<span class='label label-success'>Versionsname <code>$n</code>ist gültig</span>";
                $h .= "<hr>";
                $h .= "<div>";
                $sql = "SELECT fertigungsmeldungen.fid, fertigungsmeldungen.version FROM fertigungsmeldungen 
                WHERE fertigungsmeldungen.fartikelid='" . $this->material . "'
                AND fertigungsmeldungen.version='" . $_SESSION['version'] . "' ";

                $rst = $mysqli->query($sql);
                $anzahl = mysqli_num_rows($rst);
                $h .= sprintf("Von der Änderung sind %s Fertigungsdaten betroffen.", $anzahl);

                if ($anzahl > 0) {
                    $h .= "<button name='change_version_name' type='submit' class='btn btn-custom btn-sm pull-right' onclick='refresh_site();'>Überschreibe die Versionsbezeichnung</button><hr>";
                    $h .= "<p>Sie können die Versionsbezeichnung jetzt änden. Es werden auch alle Fertigungsdaten aktualisiert. <br>Das Löschen ist nicht möglich, da bereits Fertigungsdaten mit dieser Bezeichnung existieren.<br></p>";

                } else {
                    $h .= "<p>Sie können die Versionsbezeichnung jetzt änden. Es werden auch alle Fertigungsdaten aktualisiert. </p>";
                    $h .= "<button name='change_version_name' type='submit' class='btn btn-custom btn-sm pull-right' onclick='refresh_site();'>Überschreibe die Versionsbezeichnung</button><hr>";
                    $h .= "<p>Sie können die Version auch löschen, da keine Fertigungsdaten existieren. Stellen Sie sicher, dass diese Version nicht aktiv ist.</p>";
                    $h .= "<button name='delete_version_name' type='submit' class='btn btn-danger btn-sm pull-left' onclick='refresh_site();'>Lösche diese Artikelversion</button><hr>";
                }
                $h .= "</div>";
            } else {
                $h = "<span class='label label-danger'>Diese Versionsbezeichnung <code> $n </code> ist bereits verwendet worden.</span>";


                $h .= "<hr>";
                $h .= "<div>";
                $sql = "SELECT fertigungsmeldungen.fid, fertigungsmeldungen.version FROM fertigungsmeldungen 
                WHERE fertigungsmeldungen.fartikelid='" . $this->material . "'
                AND fertigungsmeldungen.version='" . $_SESSION['version'] . "' ";

                $rst = $mysqli->query($sql);
                $anzahl = mysqli_num_rows($rst);

                $sql = "SELECT artikeldaten.artikelid FROM artikeldaten 
                WHERE artikeldaten.artikelid='" . $this->material . "'
                AND artikeldaten.version='" . $n . "' ";

                $rst = $mysqli->query($sql);
                $active = mysqli_num_rows($rst);
                $h .= sprintf("Es wären %s Fertigungsdaten betroffen.", $anzahl);


                if ($anzahl == 0 && $active == 0) {

                    $h .= "<p>Sie können die Version auch löschen, da keine Fertigungsdaten existieren. Es ist sicher gestellt, dass diese Version nicht aktiv ist.</p>";
                    $h .= "<button name='delete_version_name' type='submit' class='btn btn-danger btn-sm pull-left' onclick='refresh_site();'>Lösche diese Artikelversion</button>";
                    $h .= "<button name='reset_version_name' type='submit' class='btn btn-custom btn-sm pull-left' onclick='refresh_site();'>Artikelversion aus Archiv zurückholen</button><hr>";

                } else {
                    $h .= "<br>Diese Version <code> $n </code> ist als <span class='label label-success'> Aktiv </span>  gespeichert und kann nicht gelöscht werden.";
                }
                $h .= "</div>";
            }


        } else {
            $h = "<span class='label label-danger'>Eingabefehler. Versionsbezeichnung ist null und muss eingeben werden!</span>";

        }

        return $h;
    }

    function EditVersionName()
    {
        $h = "";
        $h .= "<div class=\"table-responsive\">
                <table class=\"table table-striped\">
                  <tbody><tr>";
        $h .= "<td> Hinweis: automatisch vergebene neue Versionsbezeichnung wäre: " . $this->GetNextVersionsID() . "</td>";

        $h .= "</tr><tr>";
        $h .= "<td>" . asTextField("Neue eigene Versionsbezeichnung eingeben", "newversion", $this->data['version'], 7, "required parsley-type=\"text\"", "onChange='ProveVersionName();'") . "</td>";

        $h .= "</tr></tbody></table></div>";
        $h .= "<a href='#' class='btn btn-custom btn-sm pull-right' onclick='ProveVersionName();'>Prüfe diese Versionbezeichnung</a><hr>";
        $h .= "<div id='divnewversion'></div>";
        $h .= "<script>
            function ProveVersionName(){
                var x1= $(\"#newversion\").val();
                 \$(\"#divnewversion\").html('Es wird der neue Versionsname geprüft... <i class=\"fa fa-refresh fa-spin\"><i> ');                          
                 \$(\"#divnewversion\").load(\"settings.php?sess=" . $_SESSION['sess'] . "&datatype=proveversionname&newversion=\" + encodeURI(x1) , function(responseTxt, statusTxt, xhr){
                toastr[\"success\"](\"Versionname geprüft\", \"Versionname geprüft\");
                 });
            };
            
            </script>";
        return $h;
    }

    function EditPropertyAsTable($new = false)
    {
        $this->GetPrinterDefaults();


        $d=new FisDocument("Bild ".$this->version,"VERSIONID",$this->versionid);
        $h= $this->JsonScripts();
        $h.="<div class=\"table-responsive\">
                <table class=\"table table-striped\">
                  <tbody>";
        $h .= "<tr>";
        $h .= "<td>Material: " . GetArtikelName("text", $this->data['artikelid']) . "</td>";
        $h .= "</tr><tr>";
        $h.="<td>".$this->GetPicture()."</td>";
        $h.="</tr><tr>";
        if ($new) {
            $h .= "<td>" . asTextField("Version", "new_version", $this->GetNextVersionsID(), 7) . "</td>";
        } else {
            $h .= "<td>" . asTextField("Version", "version", $this->data['version'], 7) . "</td>";
        }
        $h.="</tr><tr>";
        $h .= "<td>" . asTextarea("Beschreibung", "beschreibung", $this->data['beschreibung'], 8, 7) . "</td>";
        $h.="</tr><tr>";
        $h.="<td>".asTextField("Material-Nr","materialnummer",$this->data['materialnummer'],7)."</td>";
        $h.="</tr><tr>";
        $h .= "<td>" . asTextField("Kundenreferenz-Nr", "kundenmaterialnummer", $this->data['kundenmaterialnummer'], 7, "") . "</td>";
        $h.="</tr><tr>";

        $h.="<td>".asTextField("Leistungsangabe","powerdesc",$this->data['powerdesc'],7,"")."</td>";
        $h.="</tr><tr>";
        $h.="<td>".asTextField("Traglast","maxload",$this->data['maxload'],7,"")."</td>";
        $h.="</tr><tr>";
        $h.="<td>".asTextField("ab Seriennummer","absn",$this->data['absn'],7,"")."</td>";
        $h.="</tr><tr>";
        if ($new) {
            $h .= "<td>Datum der Freigabe: " . date("Y-m-d", time()) . "</td>";
        } else {

            $h .= "<td>" . asTextField("Datum der Freigabe", "Datum", $this->data['Datum'], 7) . "</td>";

        $h.="</tr><tr>";
        $h .= "<td>" . $d->ShowHtmlInputField() . "</td>";
        }
        $h .= "</tr></tbody></table></div>";
        $_SESSION['endscripts'] .= "
         \$('#Datum').datepicker({
                autoclose: true,
                todayHighlight: true,
                format: \"yyyy-mm-dd\",
               
            });
             $('#newDatum').datepicker({
                autoclose: true,
                todayHighlight: true,
                format: \"yyyy-mm-dd\",
               
            });
       
        ";
        return $h;
    }

    function EditProperty2AsTable()
    {
        $this->GetPrinterDefaults();

        $d = new FisDocument("Bild " . $this->version, "VERSIONID", $this->versionid);
        $h = $this->JsonScripts();
        $h .= "<div class=\"table-responsive\">
                <table class=\"table table-striped\">
                  <tbody><tr>";

        $h .= "<td><a name='print'></a>" . asTextarea("Druckereinstellungen", "print", $this->data['print'], 3, 7, "", " onChange=\"SetJson1();\" ")
            . "<a href='#print' class='btn btn-success btn-sm' onclick='ProvePrintSettings();'>Druckereinstellungen prüfen</a>"
            . "<a href='#printdefault' class='btn btn-inverse btn-sm' onclick='LoadPrintDefaults();'>Beispieldaten Druckeinstellungen laden</a><div id='jsoneditor1'></div>
        </td>";
        $h.="</tr><tr>";
        $h .= "<td><a name='configprofil'></a>" . asTextarea("Konfiguration", "configprofil", $this->data['configprofil'], 3, 7, "", " onChange=\"SetJson2();\" ")
            . "<a href='#configprofil' class='btn btn-success btn-sm' onclick='ProveConfigSettings();'>Konfigurationseinstellungen prüfen</a>"
            . "<a href='#configprofildefault' class='btn btn-inverse btn-sm' onclick='LoadConfigDefaults();'>Beispieldaten Konfiguration laden</a><div id='jsoneditor2'></div>
        </td>";
        $h.="</tr><tr>";
        $h .= "<td><a name='process'></a>" . asTextarea("Arbeitsplan und Stückliste", "process", $this->data['process'], 3, 7, "", " onChange=\"SetJson3();\" ")
            . "<a href='#process' class='btn btn-success btn-sm' onclick='ProveProcessSettings();'>Arbeitsplaneinstellungen prüfen</a>"
            . "<a href='#processdefault' class='btn btn-inverse btn-sm' onclick='LoadProcessDefaults();'>Beispieldaten Arbeitsplaneinstellungen laden</a><div id='jsoneditor3'></div>
        </td>";

        $h.="</tr></tbody></table></div>";

        $this->JsonEditor();
        $this->SetSessionScript();

        return $h;
    }

    function ShowPrinterDefaults(){
        $h="";
        $h .= "<script>
            function SavePrintSettings(){
               
                var x1= $(\"#typenschild\").val();
                var x2= $(\"#verpackung\").val();
                var x3= $(\"#einbau\").val();
                var x4= $(\"#dokuset\").val();
                var x5= $(\"#fehlermeldung\").val();
                var x6= $(\"#wareneingang\").val();
                
                 \$(\"#printersetup\").html('Es wird aktualisiert... <i class=\"fa fa-refresh fa-spin\"><i> ');                          
                 \$(\"#printersetup\").load(\"settings.php?sess=" . $_SESSION['sess'] . "&datatype=printsettings&typenschild=\" + x1 + \"&verpackung=\"+ x2  + \"&einbau=\"+ x3  + \"&dokuset=\"+ x4  + \"&fehlermeldung=\"+ x5  + \"&wareneingang=\"+ x6 , function(responseTxt, statusTxt, xhr){
           
                toastr[\"success\"](\"Druckeinstellungen aktualisiert\", \"Druckeinstellungen aktualisiert\");
                
                 });
                
            };
            
            </script>";
        $h .= "<div class='col-lg-12' id='printersetup'>";
        //$h.=print_r($_REQUEST,true);
        if (count($this->GetPrinterDefaults())>0){
            foreach ($this->GetPrinterDefaults() as $item=>$k){
                $h .= "<div class='col-lg-2 card-box'><i class='fa fa-print'> </i> " . $item . "<br>" . asSelectBox("", $item, GetYesNoList("select", $k['active']), 12) . "<br>";

                if (is_array($k) && count($k) > 0) {
                    foreach ($k as $y => $x) {
                        $h .= "--" . $y . ":$x<br><i class='fa fa-newspaper-o'> </i>";
                        if (is_array($x) && count($x) > 0) {
                            foreach ($x as $s => $z) {
                                $h .= " " . $s . ":" . $z . " ,";

                            }
                            $h .= "<br>";
                        }
                        }
                    }

                $h .= "</div>";
            }
        }

        $h .= "<div class='col-lg-12'>";
        $h .= "<a href='#' class='btn btn-custom btn-sm pull-right' onclick='SavePrintSettings();'>Druckereinstellungen aktualisieren</a>";
        $h .= "</div>";
        $h .= "</div>";
        return $h;
    }


    function ShowProfilStatus($view=0){
        if (is_array($this->conf)){
            $h="Konfiguration wurde erfolgreich geladen.<br>";
        }else{
            $h="Artikel ist nicht konfiguriert.<br>";
        }
        if ($view==0) {
            $h .= str_replace("Array", "<br>", print_r($this->conf, true));
        }
        return $h;
    }
    function ShowConfig(){
        $h="";
        if (count($this->conf)>0){
            foreach ($this->conf as $item=>$k){
                $h.=$item."<br>";
                if (is_array($k) && count($k)>0){
                    foreach ($k as $y=>$x){
                        $h.="|- ".$y."<br>";
                        if (is_array($x) && count($x)>0){
                            foreach ($x as $s => $z) {
                                $h .= "--|- " . $s . "<br>";
                                if (is_array($z) && count($z) > 0) {
                                    foreach ($z as $z1 => $z2) {
                                        $h .= "-----|---" . $z2 . " " . $z1 . "<br>";
                                    }
                                    $h .= "<br>";
                                }
                            }
                        }
                    }
                }
            }
        }
        return $h;
    }
    function ShowControlFields(){
        $h="";
        $select1=true;
        $select2=true;
        $select3=true;
        if (count($this->conf)>0){
            foreach ($this->conf as $item=>$k){
                $h.="<b>".$item."</b><br>";
                if (is_array($k) && count($k)>0){
                    $select1=true;
                    $select3=true;
                    foreach ($k as $y=>$x){
                        $h.="<table><tr>";
                        $h.="<td>";
                        $h.=$this->ShowAsRadioOption($item,$y,$y,$select1);
                        $select1=false;
                        $h.="</td>";
                        if (is_array($x) && count($x)>0){
                            foreach ($x as $s=>$z){
                                //$h.=$this->ShowAsRadioOption($s,$y,$x);
                                foreach ($z as $z1=>$z2){
                                    $h.="<td>";
                                    $h.=$this->ShowAsRadioOption($s,$z2,$z1,$select3);
                                    $select3=false;
                                    $h.="</td>";
                                }
                            }
                            $h.="</tr>";
                        }
                        $h.="</table><hr>";
                    }
                    $h.="<hr>";
                }
            }
        }
        return $h;
    }
    function ShowAsRadioOption($name,$k,$value,$s){
        $select="";
        if ($s){$select="checked";}
        $h="<label>
            <input type=\"radio\" name=\"$name\" value=\"$value\" $select> $value ($k)
            <img src=\"../picture/update2/$value.png\" width=\"50\" height=\"50\" align=\"absmiddle\"></label>
           ";
        return $h;
    }

    function FindArtikelNummer($Color="grey",$Plug="DE",$Logo="no"){
        if ($Logo=="yes"){
            return ($this->conf['Logo']['yes']);
        }else {
            if ($Plug=="") {
                if ($Color=="grey" && isset($this->conf['Color']['grey'])) return ($this->conf['Color']['grey']);
                if ($Color=="blue" && isset($this->conf['Color']['blue'])) return ($this->conf['Color']['blue']);
                if ($Color=="red" && isset($this->conf['Color']['red'])) return ($this->conf['Color']['red']);
                if ($Color=="green" && isset($this->conf['Color']['green'])) return ($this->conf['Color']['green']);
                if ($Color=="yellow" && isset($this->conf['Color']['yellow'])) return ($this->conf['Color']['yellow']);
            }else{
                if ($Color=="grey") {
                    if ($Plug=="DE" && isset($this->conf['Color']['grey']['Plug']['DE'])) return ($this->conf['Color']['grey']['Plug']['DE']);
                    if ($Plug=="CEE230V" && isset($this->conf['Color']['grey']['Plug']['CEE230V'])) return ($this->conf['Color']['grey']['Plug']['CEE230V']);
                    if ($Plug=="GB" && isset($this->conf['Color']['grey']['Plug']['GB'])) return ($this->conf['Color']['grey']['Plug']['GB']);
                    if ($Plug=="AUS" && isset($this->conf['Color']['grey']['Plug']['AUS'])) return ($this->conf['Color']['grey']['Plug']['AUS']);
                    if ($Plug=="DK" && isset($this->conf['Color']['grey']['Plug']['DK'])) return ($this->conf['Color']['grey']['Plug']['DK']);
                    if ($Plug=="CH" && isset($this->conf['Color']['grey']['Plug']['CH'])) return ($this->conf['Color']['grey']['Plug']['CH']);
                }
                if ($Color=="blue") {
                    if ($Plug=="DE" && isset($this->conf['Color']['blue']['Plug']['DE'])) return ($this->conf['Color']['blue']['Plug']['DE']);
                    if ($Plug=="CEE230V" && isset($this->conf['Color']['blue']['Plug']['CEE230V'])) return ($this->conf['Color']['blue']['Plug']['CEE230V']);
                    if ($Plug=="GB" && isset($this->conf['Color']['blue']['Plug']['GB'])) return ($this->conf['Color']['blue']['Plug']['GB']);
                    if ($Plug=="AUS" && isset($this->conf['Color']['blue']['Plug']['AUS'])) return ($this->conf['Color']['blue']['Plug']['AUS']);
                    if ($Plug=="DK" && isset($this->conf['Color']['blue']['Plug']['DK'])) return ($this->conf['Color']['blue']['Plug']['DK']);
                    if ($Plug=="CH" && isset($this->conf['Color']['blue']['Plug']['CH'])) return ($this->conf['Color']['blue']['Plug']['CH']);
                }
                if ($Color=="red") {
                    if ($Plug=="DE" && isset($this->conf['Color']['red']['Plug']['DE'])) return ($this->conf['Color']['red']['Plug']['DE']);
                    if ($Plug=="CEE230V" && isset($this->conf['Color']['red']['Plug']['CEE230V'])) return ($this->conf['Color']['red']['Plug']['CEE230V']);
                    if ($Plug=="GB" && isset($this->conf['Color']['red']['Plug']['GB'])) return ($this->conf['Color']['red']['Plug']['GB']);
                    if ($Plug=="AUS" && isset($this->conf['Color']['red']['Plug']['AUS'])) return ($this->conf['Color']['red']['Plug']['AUS']);
                    if ($Plug=="DK" && isset($this->conf['Color']['red']['Plug']['DK'])) return ($this->conf['Color']['red']['Plug']['DK']);
                    if ($Plug=="CH" && isset($this->conf['Color']['red']['Plug']['CH'])) return ($this->conf['Color']['red']['Plug']['CH']);
                }
                if ($Color=="green") {
                    if ($Plug=="DE" && isset($this->conf['Color']['green']['Plug']['DE'])) return ($this->conf['Color']['green']['Plug']['DE']);
                    if ($Plug=="CEE230V" && isset($this->conf['Color']['green']['Plug']['CEE230V'])) return ($this->conf['Color']['green']['Plug']['CEE230V']);
                    if ($Plug=="GB" && isset($this->conf['Color']['green']['Plug']['GB'])) return ($this->conf['Color']['green']['Plug']['GB']);
                    if ($Plug=="AUS" && isset($this->conf['Color']['green']['Plug']['AUS'])) return ($this->conf['Color']['green']['Plug']['AUS']);
                    if ($Plug=="DK" && isset($this->conf['Color']['green']['Plug']['DK'])) return ($this->conf['Color']['green']['Plug']['DK']);
                    if ($Plug=="CH" && isset($this->conf['Color']['green']['Plug']['CH'])) return ($this->conf['Color']['green']['Plug']['CH']);
                }
                if ($Color=="yellow") {
                    if ($Plug=="DE" && isset($this->conf['Color']['yellow']['Plug']['DE'])) return ($this->conf['Color']['yellow']['Plug']['DE']);
                    if ($Plug=="CEE230V" && isset($this->conf['Color']['yellow']['Plug']['CEE230V'])) return ($this->conf['Color']['yellow']['Plug']['CEE230V']);
                    if ($Plug=="GB" && isset($this->conf['Color']['yellow']['Plug']['GB'])) return ($this->conf['Color']['yellow']['Plug']['GB']);
                    if ($Plug=="AUS" && isset($this->conf['Color']['yellow']['Plug']['AUS'])) return ($this->conf['Color']['yellow']['Plug']['AUS']);
                    if ($Plug=="DK" && isset($this->conf['Color']['yellow']['Plug']['DK'])) return ($this->conf['Color']['yellow']['Plug']['DK']);
                    if ($Plug=="CH" && isset($this->conf['Color']['yellow']['Plug']['CH'])) return ($this->conf['Color']['yellow']['Plug']['CH']);
                }
            }
        }
    }
    function FindInConfig($ar){
        $r=array_search($ar,$this->conf);
        return $r;
    }


    function JsonEditor(){

        $h="<script>
             // create the editor
              var container1 = document.getElementById('jsoneditor1');
              var options1 = {};
              var editor1 = new JSONEditor(container1, options1);
              // set json
             
                var json1 =";
        $h.=str_replace(",",",".chr(13),($this->data['print'] ));
        $h.="
        ;
        editor1.set(json1);
        
              var container2 = document.getElementById('jsoneditor2');
              var options2 = {};
              var editor2 = new JSONEditor(container2, options2);
             
             
              var json2 =";
        $h.=str_replace(",",",".chr(13),json_encode(json_decode($this->data['configprofil'],true)) );
        $h.="
        ;
        editor2.set(json2);
        
        var container3 = document.getElementById('jsoneditor3');
              var options3 = {};
              var editor3 = new JSONEditor(container3, options3);
             
             
              var json3 =";
        $h .= str_replace(",", "," . chr(13), json_encode(json_decode($this->data['process'], true)));
        $h .= "
        ;
        editor3.set(json3);
      
        function ProvePrintSettings() {
                var json = editor1.get();
                document.getElementById('print').value=JSON.stringify(json, null, 2);
                alert(JSON.stringify(json, null, 2));
              };
              
         function ProveConfigSettings() {
                var jsonx2 = editor2.get();
                document.getElementById('configprofil').value=JSON.stringify(jsonx2, null, 2);
                alert(JSON.stringify(jsonx2, null, 2));
              };
              
          function ProveProcessSettings() {
            var jsonx3 = editor3.get();
            document.getElementById('process').value=JSON.stringify(jsonx3, null, 2);
            alert(JSON.stringify(jsonx3, null, 2));
          };
          
         function ProveJsonSettings() {
            var json = editor1.get();
            document.getElementById('print').value=JSON.stringify(json, null, 2);
            
            
            var jsonx2 = editor2.get();
            document.getElementById('configprofil').value=JSON.stringify(jsonx2, null, 2);
           
             var jsonx3 = editor3.get();
            document.getElementById('process').value=JSON.stringify(jsonx3, null, 2);
           
          };
          
    function IsValidJSONString(str) {
            try {
                JSON.parse(str);
            } catch (e) {
                alert('Fehler in Datenformat. JSON-Format enthält einen Fehler.');
                return false;
            }   
                alert('Daten wurden in den Editor übernommen.');
                return true;
        }
              
      function SetJson1(){
        var str=$('#print').val(); 
          if (IsValidJSONString(str)){
            editor1.set(JSON.parse(str) );
            };
      };
      function SetJson2(){
        var str=$('#configprofil').val(); 
          if (IsValidJSONString(str)){
            editor2.set(JSON.parse(str) );
            };
      };
      function SetJson3(){
        var str=$('#process').val(); 
          if (IsValidJSONString(str)){
            editor3.set(JSON.parse(str) );
            };
      };
     
     function LoadPrintDefaults(){
        editor1.set({\"typenschild\":{\"active\":1,\"count\":1,\"layout\":{\"logo\":1,\"info\":1,\"eac\":0,\"ipx4\":0,\"ipx6\":0,\"vde\":0,\"vdegs\":0,\"weee\":0,\"ce\":0}},\"verpackung\":{\"active\":1,\"count\":1,\"layout\":{\"logo\":1,\"info\":0,\"eac\":0,\"ipx4\":0,\"ipx6\":0,\"vde\":0,\"vdegs\":0,\"weee\":0,\"ce\":0}},\"dokuset\":{\"active\":1,\"count\":1},\"einbau\":{\"active\":1,\"count\":1,\"layout\":{\"logo\":1,\"info\":1,\"eac\":0,\"ipx4\":0,\"ipx6\":0,\"vde\":0,\"vdegs\":0,\"weee\":0,\"ce\":0}},\"fehlermeldung\":{\"active\":1,\"count\":2},\"wareneingang\":{\"active\":1,\"count\":2}});
        alert('Beispieldaten für Druckeinstellungen geladen');
     };
     
     function LoadConfigDefaults(){
        editor2.set({\"Bezugsstoff Rücken\":{\"1\":{\"Material\":\"12345678\",\"Version\":\"Version 1\",\"Bezeichnung\":\"Materialbezeichnung\",\"EAN\":\"\"},\"2\":{\"Material\":\"12345678\",\"Version\":\"Version 1\",\"Bezeichnung\":\"Materialbezeichnung\",\"EAN\":\"\"},\"3\":{\"Material\":\"12345678\",\"Version\":\"Version 1\",\"Bezeichnung\":\"Materialbezeichnung\",\"EAN\":\"\"},\"Darstellung\":\"option\"},\"Bezugsstoff Sitzfläche\":{\"1\":{\"Material\":\"12345678\",\"Version\":\"Version 1\",\"Bezeichnung\":\"Materialbezeichnung\",\"EAN\":\"\"},\"2\":{\"Material\":\"12345678\",\"Version\":\"Version 1\",\"Bezeichnung\":\"Materialbezeichnung\",\"EAN\":\"\"},\"3\":{\"Material\":\"12345678\",\"Version\":\"Version 1\",\"Bezeichnung\":\"Materialbezeichnung\",\"EAN\":\"\"},\"Darstellung\":\"option\"}} );
        alert('Beispieldaten für Konfiguration geladen');
     };
     
     function LoadProcessDefaults(){
        editor3.set({\"Arbeitsplan\":{\"Vorgang 1\":{\"Vorgangsbezeichnung\":\"Fräsen\",\"Rüstzeit\":15,\"Vorgangszeit\":10,\"Handarbeitszeit\":10,\"Arbeitsplatzkosten\":\"35.50 EUR/h\",\"Gleichzeitigkeit\":\"nein\",\"Stückliste\":{\"1\":{\"Material\":\"368213\",\"Version\":\"Version 1\",\"Bezeichnung\":\"Materialbezeichnung\",\"Eigenschaften\":[],\"Menge\":\"1 Stück\",\"Preis\":\"1.00 EUR\"}}},\"Vorgang 2\":{\"Vorgangsbezeichnung\":\"Montage\",\"Rüstzeit\":15,\"Vorgangszeit\":10,\"Handarbeitszeit\":10,\"Arbeitsplatzkosten\":\"35.50 EUR/h\",\"Gleichzeitigkeit\":\"nein\",\"Stückliste\":{\"1\":{\"Material\":\"368213\",\"Version\":\"Version 1\",\"Bezeichnung\":\"Materialbezeichnung\",\"Eigenschaften\":[],\"Menge\":\"1 Stück\",\"Preis\":\"0.50 EUR\"},\"2\":{\"Material\":\"368213\",\"Version\":\"Version 1\",\"Bezeichnung\":\"Materialbezeichnung\",\"Eigenschaften\":[],\"Menge\":\"2 Stück\",\"Preis\":\"0.10 EUR\"},\"3\":{\"Material\":\"368213\",\"Version\":\"Version 1\",\"Bezeichnung\":\"Materialbezeichnung\",\"Eigenschaften\":[],\"Menge\":\"1 Stück\",\"Preis\":\"0.40 EUR\"}}}}});
        alert('Beispieldaten für einen Arbeitsplan geladen');
     };
              
         function EditWithoutEditor(){
          $(\"#print\").show();
            $(\"#configprofil\").show();
             $(\"#process\").show();
         };
         
         function EditWithEditor(){
          $(\"#print\").hide();
            $(\"#configprofil\").hide();
             $(\"#process\").hide();
         };
            </script>";
        $_SESSION['jsoneditor']=$h;


        /**
         * alert('Druckeinstellungen \\n' + JSON.stringify(json, null, 2));
         * alert('Konfigurationsprofil \\n' + JSON.stringify(jsonx2, null, 2));
         * alert('Arbeitsplan und Stückliste \\n'+JSON.stringify(jsonx3, null, 2));
         */
    }

    function SetSessionScript(){
        $_SESSION['endscripts'] = "
           EditWithEditor(); 
        ";

    }

    function JsonScripts(){
        $h=" <link href=\"../plugins/jsoneditor/jsoneditor.css\" rel=\"stylesheet\" type=\"text/css\">
            <script src=\"../plugins/jsoneditor/jsoneditor.js\"></script>";
        return $h;
    }
}