<?php

/**
 * Created by PhpStorm.
 * User: andreasveltjens
 * Date: 16.10.18
 * Time: 14:06
 */
class FisFid
{
    public $fid=0;
    public $data=array();
    public $latest=array();
    public $newsn=0;
    public $sn=0;
    public $fidtype = 0;
    public $material=0;
    public $version="";
    public $activeversion=array();
    public $materialdata=array();
    public $conf=array();
    public $stuli=array();
    public $usage=array();

    private $fidhistory=array();

    public $limit=100;
    public $search="";
    public $no_free_search_value_allowed=0;
    public $dataall=array();
    public $gruppe = "";
    public $lokz=0;

    public $sql = "";

    function __construct($i)
    {
        $this->fid=$i;
        if ($i>0){
            $this->LoadData();
            $this->LoadConfig($this->data['configprofil']);

        }else{
            $this->SetMaterial($_SESSION['material']);
            $this->SetVersion($_SESSION['version']);
        }
    }
    function __toString(){
        return $this->fid;
    }
    function LoadData(){
        include ("./connection.php");
        $this->data=array();
        $sql="SELECT * FROM fertigungsmeldungen WHERE fertigungsmeldungen.fid='$this->fid' ";

        $rst=$mysqli->query($sql);
        if (mysqli_affected_rows($mysqli)>0){
            $row_rst = $rst->fetch_assoc();
            $this->data=$row_rst;
            $this->SetMaterial($row_rst['fartikelid']);
            $this->LoadMaterialData();
            $this->SetVersion($row_rst['version']);
        }else{
            $this->data=array();
            AddSessionMessage("error","FID <code> FID $this->fid</code> nicht bekannt.","Datenbankfehler");
        }
    }

    function LoadAllData()
    {
        if ($this->limit > 0 ) {
            if ($this->no_free_search_value_allowed == 1 && strlen($this->search) == 0) {
                $this->dataall = array();
                return $this;
            } else {
                include("./connection.php");
                $s="";
                if ($this->material > 0){
                    $s=" AND ( fertigungsmeldungen.fartikelid='".$this->material."'";
                    $m=new FisMaterial($this->material);
                    if ($m->data['stuli']>0) {
                        $s .= "OR fertigungsmeldungen.fartikelid='" . $this->m['stuli'] . "'";
                    }
                    $s=$s." )";
                }


                if (strlen($this->gruppe) > 1) {


                    $sql = "SELECT artikeldaten.artikelid FROM artikeldaten WHERE artikeldaten.Gruppe = '" . $this->gruppe . "' ";
                    $rst = $mysqli->query($sql);
                    if (mysqli_affected_rows($mysqli) > 0) {
                        $s .= " AND ( ";
                        $row_rst = $rst->fetch_assoc();
                        $i = 0;
                        do {
                            $i++;
                            if ($i > 1) $s .= " OR ";
                            $s .= "fertigungsmeldungen.fartikelid='" . $row_rst['artikelid'] . "' ";
                        } while ($row_rst = $rst->fetch_assoc());
                        $s .= " ) ";
                    }

                }
                if ($this->sn > 0){
                    $s.=" AND fertigungsmeldungen.fsn='".$this->sn."'";
                }

                $s.=" AND fertigungsmeldungen.lokz='".$this->lokz."'";


                $sql = "SELECT * FROM fertigungsmeldungen 
                        WHERE fertigungsmeldungen.fartikelid>0 AND (
                           fertigungsmeldungen.fsn like '" . $this->search . "'
                        OR fertigungsmeldungen.fsn1 like '%" . $this->search . "%'
                        OR fertigungsmeldungen.fsn2 like '%" . $this->search . "%'
                        OR fertigungsmeldungen.fsn3 like '%" . $this->search . "%'
                        OR fertigungsmeldungen.fsn4 like '%" . $this->search . "%'
                         ) 
                         $s
                       
                         ORDER BY fertigungsmeldungen.fid DESC,fertigungsmeldungen.fdatum DESC, fertigungsmeldungen.fsn DESC LIMIT 0," . $this->limit;

                $this->sql = $sql;
                $rst = $mysqli->query($sql);
                if ($mysqli->error) {

                    AddSessionMessage("error", "Fertigungsmeldungen  <code>" . $this->search . "</code> konnten nicht abgefragt werden.", "Fehlermeldung");
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


    function LoadFidHistory(){
        include ("./connection.php");
        $this->fidhistory=array();
        $sql="SELECT * FROM fertigungsmeldungen WHERE fertigungsmeldungen.fartikelid='".$this->data['fartikelid']."'
         AND fertigungsmeldungen.fsn='".$this->data['fsn']."' ORDER BY fertigungsmeldungen.fid DESC, fertigungsmeldungen.fdatum DESC LIMIT 0,100";

        $rst=$mysqli->query($sql);
        if ($mysqli->error) {
            AddSessionMessage("error","FID <code> History $this->fid</code> nicht bekannt.","Datenbankfehler");
        }

        if (mysqli_affected_rows($mysqli)>0){
            $row_rst = $rst->fetch_assoc();
            do{
                $this->fidhistory[]=$row_rst;
            }while ( $row_rst = $rst->fetch_assoc() );

        }
    }


    function LoadMaterialData(){
        include ("./connection.php");
        if ($this->material>0){
            $this->materialdata=array();
            $sql="SELECT * FROM artikeldaten WHERE artikeldaten.artikelid='$this->material' ";

            $rst=$mysqli->query($sql);
            if (mysqli_affected_rows($mysqli)>0){
                $row_rst = $rst->fetch_assoc();
                $this->materialdata=$row_rst;
                $this->LoadStuliData();
            }else{
                $this->materialdata=array();
                AddSessionMessage("error","FID <code> ArticleID $this->fid</code> nicht bekannt.","Datenbankfehler");
            }
        }
    }

    function FindImportSAPEAN13(){
        $this->LoadMaterialData();
        include ("./connection.php");
        $sql="SELECT * FROM fissapean13 WHERE fissapean13.material='".intval($this->materialdata['Nummer'])."' ";
        $rst = $mysqli->query($sql);
        if (mysqli_affected_rows($mysqli)>0){
            $row=$rst->fetch_assoc();
            $this->materialdata['ean13']=$row['ean13'];
        }
        $mysqli->close();
    }

    function LoadStuliData(){
        include ("./connection.php");
        if ($this->materialdata['stuli']>0){
            $this->stuli=array();
            $sql="SELECT fertigungsmeldungen.fid FROM fertigungsmeldungen, artikeldaten
                  WHERE fertigungsmeldungen.fartikelid=artikeldaten.artikelid
                  AND artikeldaten.artikelid='".$this->materialdata['stuli']."' 
                  AND fertigungsmeldungen.fsn ='".$this->data['fsn1']."'
                  ORDER BY fertigungsmeldungen.fdatum DESC
                  LIMIT 0,10";

            $rst=$mysqli->query($sql);
            if (mysqli_affected_rows($mysqli)>0){
                $row_rst = $rst->fetch_assoc();
                do {
                    $this->stuli[] = $row_rst;
                }while ($row_rst = $rst->fetch_assoc());
            }else{
                $this->stuli=array();
                //AddSessionMessage("info","<code> STULI ".$this->data['fsn1']." </code> hat keine Stückliste auflösen können, da keine Fertigungsdaten gefunden wurden.","Info");
            }
        }
    }

    function LoadUsageData(){
        include ("./connection.php");
        $this->usage=array();
        $sql="SELECT fertigungsmeldungen.fid FROM fertigungsmeldungen, artikeldaten
              WHERE fertigungsmeldungen.fartikelid=artikeldaten.artikelid
              AND artikeldaten.stuli='".$this->data['fartikelid']."' 
              AND fertigungsmeldungen.fsn1 ='".$this->data['fsn']."'
              ORDER BY fertigungsmeldungen.fdatum DESC
              LIMIT 0,10";

        $rst=$mysqli->query($sql);
        if (mysqli_affected_rows($mysqli)>0){
            $row_rst = $rst->fetch_assoc();
            do {
                $this->usage[] = $row_rst;
            }while ($row_rst = $rst->fetch_assoc());
        }

    }


    function LoadLatestFids($status=2,$limit=10,$search="",$arbeitsplatz=""){
        //2=erledigt, 1=in Arbeit, 0=gelöscht
        include ("./connection.php");
        $a="";
        $s="";
        if (strlen($search)>0) {
            $s = " AND ( fertigungsmeldungen.fsn='".intval($search)."' 
                    OR fertigungsmeldungen.fsn1='$search' 
                    OR fertigungsmeldungen.fsn2='$search'
                    OR fertigungsmeldungen.fsn3='$search'
                    OR fertigungsmeldungen.fsn4='$search'
                    OR fertigungsmeldungen.fid='$search' 
            )
            ";
        }
        $this->latest=array();

        if (strlen($arbeitsplatz) == 0 or $arbeitsplatz == 0) {
            $a = " ";
        } else {
            $a = "AND fertigungsmeldungen.farbeitsplatz='" . $arbeitsplatz . "' ";
        }

        $sql="SELECT * FROM fertigungsmeldungen 
        WHERE fertigungsmeldungen.status='$status' $a $s
         ORDER BY fertigungsmeldungen.fid DESC, fertigungsmeldungen.fdatum DESC  LIMIT 0,$limit";

        $rst=$mysqli->query($sql);
        if (mysqli_affected_rows($mysqli)>0){
            $row_rst = $rst->fetch_assoc();
            do {
                $this->latest[]=$row_rst;
            }while( $row_rst = $rst->fetch_assoc());
        }else{
            $this->latest=array();
        }


    }


    function GetNextSN(){
        include ("./connection.php");
        $this->newsn=0;
        $sql="SELECT * FROM fertigungsmeldungen 
              WHERE fertigungsmeldungen.fartikelid='".$this->material."' 
              AND fertigungsmeldungen.fsn <=900000 ORDER BY (fertigungsmeldungen.fsn)+0 DESC LIMIT 0,1
        ";
        $rst=$mysqli->query($sql);
        if (mysqli_affected_rows($mysqli)>0){
            $row_rst = $rst->fetch_assoc();
            $this->newsn=$row_rst['fsn']+1;
        }else{
            $this->newsn=1;
            AddSessionMessage("info","FID <code> FID $this->fid</code> Erstmusterprüfung erforderlich.","Erstmuster");
        }
        return  $this->newsn;
    }

    function GetLastFSNxValues(){
        include ("./connection.php");
        $this->newsn=0;
        $sql="SELECT * FROM fertigungsmeldungen 
              WHERE fertigungsmeldungen.fartikelid='".$this->material."' 
              AND fertigungsmeldungen.fsn <=900000 ORDER BY (fertigungsmeldungen.fsn)+0 DESC LIMIT 0,1
        ";
        $rst=$mysqli->query($sql);
        if (mysqli_affected_rows($mysqli)>0){
            $row_rst = $rst->fetch_assoc();
            $r=$row_rst;
        }else{
            $r=array();
            AddSessionMessage("info","Keine Datenübernahme aus vorheriger Seriennumer möglich.","Eingabehilfe");
        }
        return  $r;
    }

    function SaveHistory($text){
        include ("./connection.php");
        $updateSQL=sprintf("
            UPDATE fertigungsmeldungen SET fertigungsmeldungen.history=%s 
            WHERE fertigungsmeldungen.fid='".$this->fid."' ",
            GetSQLValueString($text.chr(13).$this->data['history'],"text")
        );
        $rst = $mysqli->query($updateSQL);
        if ($mysqli->error) {
            AddSessionMessage("error","History auf Event <code> " . $text . "</code> konnte nicht durchgeführt werden.","Fehlermeldung");
        }
    }

    function AddFid($frei=0,$sn=0,$fidtype=0,$version="",$artikelid="",$addtxt="",$updatesninfm=true){
        include ("./connection.php");
        $wp= new FisWorkplace($_REQUEST['wp']);
        $status = $wp->GetAddFidStatus();
        // Freigabe =2  nur Anlegen ohne Freigabe=1, deleted=0

        $sonder=0;
        if ($status==2 && $frei==0){
            $frei=1;
        }

        if ($status==""){$status=1;}

        if (strlen($version)==0){
            $version=$_SESSION['version'];
        }

        if (strlen($artikelid)==0){
            $artikelid=$_SESSION['material'];
        }

        if (strlen($version)==0){
            $m =new FisMaterial($artikelid);
            $version =$m->GetSelectedVersion();
        }
        if ($sn==0 or $fidtype==0){
            $sn=$this->GetNextSN();

        }
        $_SESSION['sn']=$sn;


        $v=new FisVersion($version,$artikelid);

        $signatur=$_SESSION['wp']."FISpdfetikett";
        if ($_REQUEST['fsn1'] <> "") {
            $sn1 = $_REQUEST['fsn1'];
        } else {
            $sn1 = " ";
        }

        if ($_REQUEST['fsn2'] <> "") {
            $sn2 = $_REQUEST['fsn2'];
        } else {
            $sn2 = " ";
        }

        if ($_REQUEST['fsn3'] <> "") {
            $sn3 = $_REQUEST['fsn3'];
        } else {
            $sn3 = " ";
        }

        if ($_REQUEST['fsn4'] <> "") {
            $sn4 = $_REQUEST['fsn4'];
        } else {
            $sn4 = date("Y-m", time());;
        }


        switch ($fidtype){
            case 0: {
                $txt="neue Bauteilprüfung mit ".$version;
                $sonder=0;
                $notes=" ";
                $notes1=" ";
                break;
            }
            case 1: {
                $txt="neue Wiederholungsprüfung mit ".$version;
                $fidtype=1;
                $status=1;
                $sonder=0;
                $frei=1;
                $notes="Nacharbeit SN $sn ".$addtxt;
                $notes1="Nacharbeit SN $sn ".$addtxt;
                $sn1 = $_REQUEST['new_fsn1'];
                $sn2 = $_REQUEST['new_fsn2'];
                $sn3 = $_REQUEST['new_fsn3'];
                $sn4 = $_REQUEST['new_fsn4'];
                break;
            }
            case 2: {
                $txt="Reparatur";
                $status=2;
                $frei=1;

                $notes="Reparatur SN $sn mit FMID:".$_SESSION['fmid']." ".$addtxt;
                $notes1="Reparatur SN $sn mit FMID:".$_SESSION['fmid']." ".$addtxt;
                break;
            }
            case 3: {
                $txt="Upgrade";
                $status=2;
                $frei=1;

                $notes="Upgrade SN $sn mit FMID:".$_SESSION['fmid']." ".$addtxt;
                $notes1="Upgrade SN $sn mit FMID:".$_SESSION['fmid']." ".$addtxt;
                break;
            }
            case 4: {
                $txt="Verschrottung";
                $status=2;
                $frei=0;

                $notes="Verschrottung SN $sn mit FMID:".$_SESSION['fmid'];
                $notes1="Verschrottung SN $sn mit FMID:".$_SESSION['fmid'];
                break;
            }
            case 5: {
                $txt="Austausch";
                $status=2;
                $frei=1;

                $notes="Austausch SN $sn mit FMID:".$_SESSION['fmid']." ".$addtxt;
                $notes1="Austausch SN $sn mit  FMID:".$_SESSION['fmid']." ".$addtxt;

                break;
            }
        }

        if ($_SESSION['fmid']>0 && $updatesninfm){
            $fmid=new FisReturn($_SESSION['fmid']);
            $fmid->UpdateSN2($sn, $artikelid);
            $w= new FisWorkflow("$notes","fmid",$_SESSION['fmid']);
            $w->Add();
        }




        if ($_REQUEST['rfid']<>"") {
            $frfid=$_REQUEST['rfid'];
        } else {
            $frfid=" ";
        }

        $pgeraet="Check bei Montage ".$_REQUEST['wp'];
        $farbeitsplatz=$_REQUEST['wp'];
        $erstelltdatum=date('Y-m-d H:i:s',time());
        $freigabedatum=date('Y-m-d H:i:s',time());


        $artikel=$v->FindArtikelNummer($_REQUEST['Color'],$_REQUEST['Plug'],$_REQUEST['Logo']);

        $configprofil=json_encode(array("Color"=>$_REQUEST['Color'],"Plug"=>$_REQUEST['Plug'],"Logo"=>$_REQUEST['Logo'],"Artikel"=>$artikel) );
        if ($configprofil=="") {
            $configprofil = " ";
        }

        $insertSQL = sprintf("INSERT INTO fertigungsmeldungen 
            (fartikelid, fuser, fdatum, fsn, version, fsn1, fsn2, fsn3, fsn4,
              pgeraet, signatur, farbeitsplatz, fsonder, ffrei, fnotes,fnotes1,status, 
              erstelltdatum, freigabedatum, history ,frfid, configprofil, fidtype) 
              VALUES ( %s, %s, %s, %s,%s,%s,%s, %s,%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
            GetSQLValueString($artikelid, "int"),
            GetSQLValueString($_SESSION['active_user'], "int"),
            "now()",
            GetSQLValueString($sn, "text"),
            GetSQLValueString($version, "text"),
            GetSQLValueString($sn1, "text"),
            GetSQLValueString($sn2, "text"),
            GetSQLValueString($sn3, "text"),
            GetSQLValueString($sn4, "text"),
            GetSQLValueString($pgeraet, "text"),
            GetSQLValueString($signatur, "text"),
            GetSQLValueString($farbeitsplatz, "text"),
            GetSQLValueString($sonder,"int"),
            GetSQLValueString($frei,"int"),
            GetSQLValueString(($notes), "text"),
            GetSQLValueString(($notes1), "text"),
            GetSQLValueString($status, "int"),
            GetSQLValueString($erstelltdatum, "text"),
            GetSQLValueString($freigabedatum, "text"),
            GetSQLValueString("Montage 611401 User ".$_SESSION['active_user']."  - $erstelltdatum; Freigabe ".$_REQUEST['wp']." User ".$_SESSION['active_user']."  - $freigabedatum;", "text"),
            GetSQLValueString($frfid, "text"),
            GetSQLValueString($configprofil, "text"),
            GetSQLValueString($fidtype, "int"));


        $rst = $mysqli->query($insertSQL);
        if ($mysqli->error) {
            $_SESSION['zzz']=$insertSQL;
            AddSessionMessage("error","FID wurde nicht angelegt, da ein Fehler aufgetreten ist. Material:".$artikelid." WP:".$_REQUEST['wp']." Version:". $version." Fidtype:".$fidtype,"Fehlermeldung");
        }else{
            $_SESSION['fid']=$mysqli->insert_id;
            $this->fid=$mysqli->insert_id;
            $this->LoadData();
            $this->LoadConfig($this->data['configprofil']);
            AddSessionMessage("success", $txt." <code> FID " .$_SESSION['fid'] . "</code>  wurde gespeichert.", $txt);
            $w=new FisWorkflow("Fertigungsmeldung $txt erstellt","fid",$this->fid);
            $w->Add();
        }

        return $_SESSION['fid'];

    }


    function AddCharge(){
        //only when merkmale is null
        if (isset($_REQUEST['count']) && $_REQUEST['count']>0) {
            for ($i=0; $i<$_REQUEST['count']; $i++) {

                if (isset($_REQUEST['new_fmidfid'])){
                    if ($_SESSION['fidstate']==3) {
                        $this->AddFid(2, "", 3,"",$_SESSION['material'],"Upgrade von SN ".$_SESSION['sn_old']." ".GetArtikelName("text",$_SESSION['material_old']));
                    }else {
                        $this->AddFid(2, $_SESSION['sn'], $_SESSION['fidstate']);
                    }
                }else{
                    $_SESSION['fidstate']=0;

                    $this->AddFid(1,0,0);
                }

                if ($_SESSION['fidstate']==4){
                    AddSessionMessage("info","Verschrottung, kein Druck von Typenschild oder Aufklebern","Verschrottung");
                }else {
                    $this->PrintSerialEtikett();
                    $this->PrintPackageEtikett();
                    $this->PrintDoku();
                    $this->PrintMountingEtikett();
                }

                if ($_SESSION['fidstate']==3){
                    $this->AddFid(2,$_SESSION['sn_old'],3,"",$_SESSION['material_old'],"Neue SN ".$_SESSION['sn']." ".GetArtikelName("text",$_SESSION['material']),false);

                }
                if ($_SESSION['fidstate']==5){
                    $this->AddFid(2,$_SESSION['sn_old'],5,"",$_SESSION['material_old'],"Neue SN ".$_SESSION['sn']." ".GetArtikelName("text",$_SESSION['material']),false);

                }
            }
            AddSessionMessage("info", " <code> " .$i. "</code> Bauteile wurden angelegt.", "Charge anlegen");
        }else{
            if (isset($_REQUEST['new_fmidfid'])){
                if ($_SESSION['fidstate']==3) {
                    $this->AddFid(2, "", 3,"",$_SESSION['material'],"Upgrade von SN ".$_SESSION['sn_old']." ".GetArtikelName("text",$_SESSION['material_old']));
                }else {
                    $this->AddFid(2, $_SESSION['sn'], $_SESSION['fidstate']);
                }
            }else{
                $this->AddFid(1,0,0);
            }

            if ($_SESSION['fidstate']==4){
                AddSessionMessage("info","Verschrottung, kein Druck von Typenschild oder Aufklebern","Verschrottung");
            }else {
                $this->PrintSerialEtikett();
                $this->PrintPackageEtikett();
                $this->PrintDoku();
                $this->PrintMountingEtikett();
            }

            if ($_SESSION['fidstate']==3){
                $this->AddFid(2,$_SESSION['sn_old'],3,"",$_SESSION['material_old'],"Neue SN ".$_SESSION['sn']." ".GetArtikelName("text",$_SESSION['material']),false);

            }
            if ($_SESSION['fidstate']==5){
                $this->AddFid(2,$_SESSION['sn_old'],5,"",$_SESSION['material_old'],"Neue SN ".$_SESSION['sn']." ".GetArtikelName("text",$_SESSION['material']),false);

            }
        }
    }

    function SaveFid(){
        include ("./connection.php");
        if (strlen( $_POST['fnotes'])>2){ $_POST['fsonder']=1;}else{$_POST['fsonder']=0;}
        if ($_POST['ffrei'] == "on") {
            $_POST['ffrei'] = 1;
        } else {
            $_POST['ffrei'] = 0;
        }
        if ($_POST['fsonder'] == "on") {
            $_POST['fsonder'] = 1;
        }
        //new_save_fid_free_and_print

        $status = 2;
        $fidtype = 0;
        $lokz = 0;

        if (isset($_REQUEST['new_save_fid_copy_and_print'])) {
            $_POST['ffrei'] = 0;
            $_POST['fsonder'] = 0;
            $fsn1 = $_POST['fsn1'];
            $fsn2 = $_POST['fsn2'];
            $fsn3 = $_POST['fsn3'];
            $fsn4 = $_POST['fsn4'];
            $fidtype = 0;
        }
        if (isset($_REQUEST['new_save_fid_at_work_and_print'])) {
            $_POST['ffrei'] = 0;
            $_POST['fsonder'] = 0;
            $fsn1 = $_POST['fsn1'];
            $fsn2 = $_POST['fsn2'];
            $fsn3 = $_POST['fsn3'];
            $fsn4 = $_POST['fsn4'];
            $status = 1;
            $fidtype = 0;
        }
        if (isset($_REQUEST['new_save_fid_copy_and_print_at_work'])) {
            $fsn1 = $_POST['fsn1'];
            $fsn2 = $_POST['fsn2'];
            $fsn3 = $_POST['fsn3'];
            $fsn4 = $_POST['fsn4'];
            $_POST['ffrei'] = 0;
            $_POST['fsonder'] = 0;
            $status = 1;
            $fidtype = 0;
        }

        if (isset($_REQUEST['new_save_fid_at_work'])) {
            $fsn1 = $_POST['fsn1'];
            $fsn2 = $_POST['fsn2'];
            $fsn3 = $_POST['fsn3'];
            $fsn4 = $_POST['fsn4'];
            $_POST['ffrei'] = 0;
            $_POST['fsonder'] = 0;
            $status = 1;
            $fidtype = 0;
        }

        // Nacharbeit
        if (isset($_REQUEST['new_save_fid_copy_and_print'])) {
            $_POST['ffrei'] = 0;
            $_POST['fsonder'] = 0;
            $_POST['fnotes'] = "Nacharbeit " . $_REQUEST['fid_new_notes'] . " ";
            $_POST['fnotes1'] = "Nacharbeit " . $_REQUEST['fid_new_notes'] . " ";

            $_POST['version'] = $this->data['version'];
            $fsn1 = $this->data['fsn1'];
            $fsn2 = $this->data['fsn2'];
            $fsn3 = $this->data['fsn3'];
            $fsn4 = $this->data['fsn4'];
            $fidtype = 1;
        }

        //Reparatur setfree
        if (isset($_REQUEST['new_save_fid_repair'])) {
            $_POST['ffrei'] = 1;
            $_POST['fsonder'] = 0;
            $_POST['fnotes'] = "Wiederholungsprüfung " . $_REQUEST['fid_new_notes'] . " ";
            $_POST['fnotes1'] = "Wiederholungsprüfung " . $_REQUEST['fid_new_notes'] . " ";

            $_POST['version'] = $this->data['version'];
            $fsn1 = $this->data['fsn1'];
            $fsn2 = $this->data['fsn2'];
            $fsn3 = $this->data['fsn3'];
            $fsn4 = $this->data['fsn4'];
            $fidtype = 2;
        }
        //Reparatur at work
        if (isset($_REQUEST['new_save_fid_repair_at_work'])) {
            $_POST['ffrei'] = 1;
            $_POST['fsonder'] = 0;
            $_POST['fnotes'] = "Wiederholungsprüfung " . $_REQUEST['fid_new_notes'] . " ";
            $_POST['fnotes1'] = "Wiederholungsprüfung " . $_REQUEST['fid_new_notes'] . " ";

            $_POST['version'] = $this->data['version'];
            $fsn1 = $this->data['fsn1'];
            $fsn2 = $this->data['fsn2'];
            $fsn3 = $this->data['fsn3'];
            $fsn4 = $this->data['fsn4'];
            $fidtype = 2;
            $status = 1;
        }


        //Verschrotten
        if (isset($_REQUEST['new_save_fid_scrap'])) {
            $_POST['ffrei'] = 0;
            $_POST['fsonder'] = 0;
            $_POST['fnotes'] = "Verschrottung " . $_REQUEST['fid_new_notes'] . " ";
            $_POST['fnotes1'] = "Verschrottung " . $_REQUEST['fid_new_notes'] . " ";
            $_POST['version'] = $this->data['version'];
            $fsn1 = $this->data['fsn1'];
            $fsn2 = $this->data['fsn2'];
            $fsn3 = $this->data['fsn3'];
            $fsn4 = $this->data['fsn4'];
            $fidtype = 4;
        }
        //Löschen
        if (isset($_REQUEST['new_save_fid_delete'])) {
            $_POST['ffrei'] = 0;
            $_POST['fsonder'] = 0;
            $_POST['fnotes'] = "gelöscht " . $_REQUEST['fid_new_notes'] . " ";
            $_POST['fnotes1'] = "gelöscht " . $_REQUEST['fid_new_notes'] . " ";
            $_POST['version'] = $this->data['version'];
            $fsn1 = $this->data['fsn1'];
            $fsn2 = $this->data['fsn2'];
            $fsn3 = $this->data['fsn3'];
            $fsn4 = $this->data['fsn4'];
            $fidtype = 99;
            $lokz = 1;
        }


        $updateSQL=sprintf("
                UPDATE fertigungsmeldungen SET fertigungsmeldungen.fsn1=%s, fertigungsmeldungen.fsn2=%s, fertigungsmeldungen.fsn3=%s,
                fertigungsmeldungen.fsn4=%s,fertigungsmeldungen.ffrei=%s,fertigungsmeldungen.fsonder=%s, fertigungsmeldungen.fnotes=%s, fertigungsmeldungen.fnotes1=%s,
                fertigungsmeldungen.status=%s,
                fertigungsmeldungen.version=%s, fertigungsmeldungen.fdatum=%s,fertigungsmeldungen.fidtype=%s,fertigungsmeldungen.lokz=%s
                WHERE fertigungsmeldungen.fid='".$this->fid."' ",
            GetSQLValueString($fsn1,"text"),
            GetSQLValueString($fsn2,"text"),
            GetSQLValueString($fsn3,"text"),
            GetSQLValueString($fsn4,"int"),
            GetSQLValueString($_POST['ffrei'],"int"),
            GetSQLValueString($_POST['fsonder'],"int"),
            GetSQLValueString($_POST['fnotes'],"text"),
            GetSQLValueString($_POST['fnotes1'],"text"),
            GetSQLValueString($status, "int"),
            GetSQLValueString($_POST['version'],"text"),
            GetSQLValueString(date("Y-m-d", time()), "text"),
            GetSQLValueString($fidtype, "int"),
            GetSQLValueString($lokz, "int")

        );
        $rst = $mysqli->query($updateSQL);
        if ($mysqli->error) {

            AddSessionMessage("error","Version <code>  ".$_POST['version']."</code> konnte nicht durchgeführt werden.","Fehlermeldung");
        }else{
            // $_SESSION['zz']=$updateSQL;
            $txt="Version <code> ".$_POST['version']."</code> ".$this->GetFree("text",$_POST['ffrei'])." ".$this->GetSpecial("text",$_POST['fsonder'])." wurde gespeichert.";
            AddSessionMessage("success", $txt, "Update der Fertigungsdaten");
            $txt="Fertigungmeldung wurde geändert.";
            $d=new FisWorkflow($txt,"fid",$this->fid);
            $d->Add();
        }
        $d=new FisDocument("Anhang zur Fertigungsmeldung","fid",$this->fid);
        $d->AddNewDocument();

        if (isset($_REQUEST['new_save_fid_free_and_print']) or isset($_REQUEST['new_save_fid_at_work_and_print'])) {
            $this->PrintMountingEtikett();
        }


        if (isset($_REQUEST['new_save_fid_copy_and_print']) or isset($_REQUEST['new_save_fid_copy_and_print_at_work'])) {
            $this->AddFid(0,$this->data['fsn'],1,$this->data['version'],$this->data['fartikelid'],"Nacharbeit ".$_REQUEST['fid_new_notes'],false); //in Arbeit + Nacharbeit
            $this->PrintMountingEtikett();
            AddSessionMessage("info","Nacharbeit angelegt und Montage-Etikett nochmals gedruckt.","Nacharbeit");
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
                $this->limit=50;
            }
        }else{
            $this->limit=50;
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

    function PrintSerialEtikett(){
        if (!isset($_SESSION['fid']) or $_SESSION['fid']==0){
            AddSessionMessage("error","SN-Etikett (Typenschild) wurde nicht angelegt, da ein Fehler aufgetreten ist.","Druckfehler");
        }else{
            if ($this->GetPrinterActive("typenschild")) {
                $count=$this->GetPrintCount("typenschild");
                include('./print.sn.php');

                if ($count>1) { include('./print.sn.php'); }
                if ($count>2) { include('./print.sn.php'); }
                if ($count>3) { include('./print.sn.php'); }
                if ($count>4) { include('./print.sn.php'); }

                $txt = "Typenschild-Etikett $count-mal gedruckt.";
                AddSessionMessage("info", $txt, "Druckinfo");
                $w = new FisWorkflow($txt, "fid", $this->fid);
                $w->Add();
            }
        }
    }

    function PrintMountingEtikett(){

        if (!isset($_SESSION['fid']) or $_SESSION['fid']==0){
            AddSessionMessage("error", "Einbau-Etikett wurde nicht angelegt, da ein Fehler PrintMountingEtikett aufgetreten ist.", "Druckfehler");
        }else{
            if ($this->GetPrinterActive("einbau")) {
                $count=$this->GetPrintCount("einbau")  ;

                include('./print.mounting.php');

                if ($count>1) { include('./print.mounting.php'); }
                if ($count>2) { include('./print.mounting.php'); }
                if ($count>3) { include('./print.mounting.php'); }
                if ($count>4) { include('./print.mounting.php'); }

                $txt = "Einbau-Etikett $count-mal gedruckt.";
                AddSessionMessage("info", $txt, "Druckinfo");
                $w = new FisWorkflow($txt, "fid", $this->fid);
                $w->Add();
            }
        }
    }

    function PrintPackageEtikett(){
        if (!isset($_SESSION['fid']) or $_SESSION['fid']==0){
            AddSessionMessage("error", "Verpackung-Etikett wurde nicht angelegt, da ein Fehler PrintPackageEtikett aufgetreten ist.", "Druckfehler");
        }else{
            if ($this->GetPrinterActive("verpackung")) {
                $count=$this->GetPrintCount("verpackung");
                include('./print.package.php');

                if ($count>1) { include('./print.package.php'); }
                if ($count>2) { include('./print.package.php'); }
                if ($count>3) { include('./print.package.php'); }
                if ($count>4) { include('./print.package.php'); }
                $txt = "Verpackung-Etikett $count-mal gedruckt.";
                AddSessionMessage("info", $txt, "Druckinfo");
                $w = new FisWorkflow($txt, "fid", $this->fid);
                $w->Add();
            }
        }
    }

    function PrintDoku(){
        if (!isset($_SESSION['fid']) or $_SESSION['fid']==0){
            AddSessionMessage("error", "Dokumentation wurde nicht angelegt, da ein Fehler PrintDoku aufgetreten ist.", "Druckfehler");
        }else{
            if ($this->GetPrinterActive("dokuset")) {
                $count = $this->GetPrintCount("dokuset");
                $this->CreateDokuset();

                if ($count > 1) {
                    $this->CreateDokuset(2);
                }
                if ($count > 2) {
                    $this->CreateDokuset(3);
                }
                if ($count > 3) {
                    $this->CreateDokuset(4);
                }
                if ($count > 4) {
                    $this->CreateDokuset(5);
                }
                $txt = "Dokumentation $count-mal gedruckt.";
                AddSessionMessage("info", $txt, "Druckinfo");
                $w = new FisWorkflow($txt, "fid", $this->fid);
                $w->Add();
            }
        }
    }


    function CreateDokuset($count = 1)
    {
        $d = new FisDocument($this->material, "artikelid", $this->material);
        $l = $d->GetLatestDocumentLink("pdf");

        if (strlen($l) < 4) {
            include('./print.doku.php');
        } else {
            $wp = new FisWorkplace($_SESSION['wp']);
            $link = $_SERVER['DOCUMENT_ROOT'] . FIS_PRINT_FOLDER . $wp->GetPrinterDirectory("dokuset") . '/Dokumentation-' . $this->fid . '-' . $count . '-' . time() . '.pdf';
            copy($l, $link);
            AddSessionMessage("success", "Dokumentation nach <code>$link</code> kopiert.", "Druckinfo");
        }
    }

    function GetMaterialName(){
        $artikel=new FisMaterial($this->material);
        return substr($artikel->GetMaterialName(),0,50);
    }
    function GetSerialnumber(){
        return sprintf("%06.0f", $this->data['fsn']);
    }
    function GetChargennumber(){
        return sprintf("%06.0f",$this->data['fsn'])."-".sprintf("%06.0f",$this->data['csn']);;
    }
    function GetSerialnumber1(){
        return $this->data['fsn1'];
    }
    function GetSerialnumber2(){
        return $this->data['fsn2'];
    }
    function GetSerialnumber3(){
        return $this->data['fsn3'];
    }
    function GetSerialnumber4(){
        return $this->data['fsn4'];
    }

    function GetNotes(){
        return utf8_decode($this->data['fnotes']);
    }
    function GetNotes1(){
        return utf8_decode($this->data['fnotes1']);
    }
    function GetPropertyDesc($property=1){
        if (!isset($this->materialdata['merkmale'])){
            $this->LoadMaterialData();
        }
        $r=explode(";",$this->materialdata['merkmale']);
        $count=count($r);
        $s=array();
        for ($i=0; $i<($count-1); $i++) {
            $s[$i+1 ] = $r[$i];
        }
        return $s[$property];
    }

    function IsPropertyDesc($property = 1)
    {
        if (!isset($this->materialdata['merkmale'])) {
            $this->LoadMaterialData();
        }
        $r = explode(";", $this->materialdata['merkmale']);
        $count = count($r);
        if ($count > $property) {
            return true;
        } else {
            return false;
        }

    }

    /**
     * @param string $type
     * @return mixed
     */
    function GetPrinterActive($type="typenschild"){
            $m=new FisMaterial($this->material);
            $p=$m->GetPrinterSettings();

            return $p[$type]['active'];

     }

    /**
     * @param string $type
     * @return mixed
     */
    function GetPrintCount($type="typenschild"){
        $m=new FisMaterial($this->material);
        $p=$m->GetPrinterSettings();
        return $p[$type]['count'];

    }


    function SetMaterial($m){
        $this->material=$m;
        $this->LoadMaterialData();

    }
    function GetMaterial(){
        return $this->material;
    }

    function GetEAN13(){
        $this->FindImportSAPEAN13();
        if (isset($this->materialdata['ean13']) && strlen($this->materialdata['ean13'])==13){
            $e=$this->materialdata['ean13'];
        }else{
            $e="1234567890128";
        }
        return $e;
    }

    function GetMaterialNumber(){

        $r=json_decode($this->data['configprofil'],true);
        if (count($r)>0){
            $n=$r['Artikel'];
        }else{

            $n=$this->materialdata['Nummer'];
        }
        if (strlen($n)==0){
            $n=$this->materialdata['Nummer'];
        }
        return sprintf("%06.0f",$n);
    }

    function SetVersion($v){
        $this->version = $v;
        if (strlen($v)>0 && ($this->material>0)) {

            $m = new FisMaterial($this->material);
            $m->LoadActivVersionData($v);
            $this->activeversion = $m->activeversion;
        }
    }

    function GetVersion(){
        return $this->version;
    }

    function GetFree($type,$i=""){
        if ($i=="") {$v=$this->data['ffrei'];} else {$v=$i;}
        $h="";
        switch ($type){
            case "text":{
                switch ( $v){
                    case 0 : {
                        $h="<div class='label label-danger'> <i class='fa fa-crop'> </i> gesperrt </div>";
                        break;
                    }
                    case 1 : {
                        $h="<div class='label label-success'> <i class='fa fa-check-circle-o'> </i> Freigabe </div>";
                        break;
                    }
                    case 2 : {
                        $h="<div class='label label-warning'> <i class='fa fa-cart-arrow-down'> </i> Sonderfreigabe </div>";
                        break;
                    }
                    case 3 : {
                        $h="<div class='label label-primary'> <i class='fa fa-check-edit'> </i> in Arbeit </div>";
                        break;
                    }
                    case 99 :
                        {
                            $h = "<div class='label label-danger'> <i class='fa fa-crop'> </i> gelöscht </div>";
                            break;
                        }
                }
                break;
            }
            case "data":{
                $h= $v;
                break;
            }
        }

        return $h;
    }

    function GetSpecial($type,$i=""){
        if ($i=="") {$v=$this->data['fsonder'];} else {$v=$i;}
        $h="";
        switch ($type){
            case "text":{
                switch ( $v){
                    case 1 : {
                        $h="<div class='label label-warning'> <i class='fa fa-cart-arrow-down'> </i> Sonderfreigabe </div>";
                        break;
                    }
                }
                break;
            }
            case "data":{
                $h= $v;
                break;
            }
        }

        return $h;
    }
    function GetStatus($type,$i=""){
        if ($i=="") {$v=$this->data['status'];} else {$v=$i;}
        $h="";
        switch ($type){
            case "text":{
                switch ( $v){
                    case 0 : {
                        $h = "Datenübernahme FIS9";
                        break;
                    }
                    case 1 : {
                        $h="in Arbeit";
                        break;
                    }
                    case 2 : {
                        $h="abgeschlossen";
                        break;
                    }
                }
                break;
            }
            case "data":{
                $h= $v;
                break;
            }
        }

        return $h;
    }

    function GetLokz($type, $i = "")
    {
        if ($i == "") {
            $v = $this->data['lokz'];
        } else {
            $v = $i;
        }
        $h = "";
        switch ($type) {
            case "text":
                {
                    switch ($v) {
                        case 0 :
                            {
                                $h = "aktiv";
                                break;
                            }
                        case 1 :
                            {
                                $h = "gelöscht";
                                break;
                            }
                    }
                    break;
                }
            case "data":
                {
                    $h = $v;
                    break;
                }
        }

        return $h;
    }

    function GetFidType($type=""){
        if ($type=="") {$type=$this->data['fidtype'];}
        $h="";
        switch ($type){
            case 0 : {
                $h="Neuanlage";
                break;
            }
            case 1 : {
                $h="Nacharbeit";
                break;
            }
            case 2 : {
                $h="Reparatur";
                break;
            }
            case 3: {
                $h="Upgrade";
                break;
            }
            case 4: {
                $h="Verschrottung";
                break;
            }
            case 5: {
                $h="Austausch";
                break;
            }
        }
        return $h;
    }

    function GetIcon($type="typenschild",$item="logo"){
        $v=new FisVersion(0,0);
        $v->LoadDataById($this->activeversion['id']);
        $h=$v->GetIcon($type,$item);
        return $h;

    }

    function ShowHtmlIcons2($type){
        $v=new FisVersion(0,0);
        $v->LoadDataById($this->activeversion['id']);
        $h=$v->ShowHtmlIcons2($type);
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
            $h.="<b>IPX4</b>";
        }
        if ($this->GetIconIPX6()){
            $h.="<b>IPX6</b>";
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
        if (stripos($this->activeversion['pruefzeichen'],"info")!==false) {
            return true;
        }else{
            return false;
        }
    }
    function GetIconCE(){
        if (stripos($this->activeversion['pruefzeichen'],"ce")!==false) {
            return true;
        }else{
            return false;
        }
    }

    function GetIconIPX4(){
        if (stripos($this->activeversion['pruefzeichen'],"ipx4")!==false) {
            return true;
        }else{
            return false;
        }
    }

    function GetIconIPX6(){
        if (stripos($this->activeversion['pruefzeichen'],"ipx6")!==false) {
            return true;
        }else{
            return false;
        }
    }



    function GetIconWEEE(){
        if (stripos($this->activeversion['pruefzeichen'],"weee")!==false) {
            return true;
        }else{
            return false;
        }
    }

    function GetIconEAC(){
        if (stripos($this->activeversion['pruefzeichen'],"eac")!==false) {
            return true;
        }else{
            return false;
        }
    }

    function GetIconVDE(){
        if (stripos($this->activeversion['pruefzeichen'],"vde")!==false) {
            return true;
        }else{
            return false;
        }
    }

    function GetIconVDEGS(){
        if (stripos($this->activeversion['pruefzeichen'],"vdgs")!==false) {
            return true;
        }else{
            return false;
        }
    }

    function GetFDatum($type=""){
        $d=strtotime($this->data['fdatum']);
        if ($type=="Y-m") {
            $s = date("Y-m", $d);
        }elseif ($type=="Y-m-d"){
            $s=date("Y-m-d",$d);
        }else {
            $s = date("d.m.Y", $d);
        }
        if ($this->data['fidtype'] == 3) {
            $s = "R-" . $s;
        }
        return $s;
    }

    function GetPowerDesc(){
        return $this->activeversion['powerdesc'];
    }

    function GetMaxLoad(){
        return $this->activeversion['maxload'];
    }



    function GetPictureLink(){
        if (strlen($this->materialdata['piclink'])>3){
            $l=FIS_MATERIAL_PICTURE."/".$this->materialdata['piclink'];
        }else{
            $l = "../config/logo_no_picture_" . FIS_ID . ".png";
        }
        $t='../thumbnail/' . pathinfo($l, PATHINFO_FILENAME) . '.' . pathinfo($l, PATHINFO_EXTENSION);


        if (!is_dir('../thumbnail')){
            mkdir('../thumbnail',0777);
            chmod('../thumbnail',777);
        }
        if (is_file($l) && !is_file($t)) {
            new FisThumbnail($l, '../thumbnail', 80, 80);
        }

        return  $l;

    }
    function GetPicture($size="thumb-lg"){
        return "<img src=\"".$this->GetPictureLink()."\" class=\"$size\" alt='Material'>";
    }

    function ShowHtmlFid(){
        $h="Material: ".$this->GetMaterialName()."<br>";
        $h.="Seriennummer: ".$this->GetSerialnumber()."<br>";
        $h.="EAN: ".$this->GetEAN13()."<br>";

        if (is_array(json_decode($this->data['configprofil'],true))){
            $r=json_decode($this->data['configprofil'],true);
            foreach ($r as $i=>$item){
                $h.=$i.":".$item."<br>";
            }
        }
        return $h;
    }

    function ShowHtmLButtonTS($link,$type="link",$target="_self"){
        if (($this->data['status'] == 0 or $this->data['status'] == 2) && $this->data['ffrei'] == 1 && $this->GetPrinterActive("typenschild")) {
            if ($type=="link") {
                return "<a href=\"" . $link . "&action=14&fid=" . $this->fid . "\" target=\"$target\" class=\"btn btn-custom btn-sm waves-effect waves-light\"><i class='fa fa-print'> </i> Typenschild</a>";
            }else{
                return"<a href=\"".$_SERVER['PHP_SELF']."?sess=".$_SESSION['sess']."&wp=".$_SESSION['wp']."&fid=".$this->fid."&print_typenschild=1\" name=\"print_typenschild\" type=\"submit\" onclick='refresh_site()' class=\"btn btn-custom btn-sm waves-effect waves-light\"> Typenschild </a>";

            }
        }
    }
    function ShowHtmLButtonVP($link,$type="link",$target="_self"){
        if (($this->data['status'] == 0 or $this->data['status'] == 2) && $this->data['ffrei'] == 1 && $this->GetPrinterActive("verpackung")) {
            if ($type=="link") {
                return "<a href=\"" . $link . "&action=14&fid=" . $this->fid  . "\" target=\"$target\" class=\"btn btn-custom btn-sm waves-effect waves-light\"><i class='fa fa-print'> </i> Verpackung</a>";
            }else{
                return"<a href=\"".$_SERVER['PHP_SELF']."?sess=".$_SESSION['sess']."&wp=".$_SESSION['wp']."&fid=".$this->fid."&print_package=1\" name=\"print_package\" type=\"submit\" onclick='refresh_site()' class=\"btn btn-custom btn-sm waves-effect waves-light\"> Verpackung </a>";


            }
        }
    }

    function ShowHtmLButtonDoku($link,$type="link",$target="_self"){
        if (($this->data['status'] == 0 or $this->data['status'] == 2) && $this->data['ffrei'] == 1 && $this->GetPrinterActive("dokuset")) {
            if ($type=="link") {
                return "<a href=\"" . $link . "&action=14&fid=" . $this->fid . "\" target=\"$target\" class=\"btn btn-custom btn-sm waves-effect waves-light\"><i class='fa fa-print'> </i> Doku</a>";
            }else{
                return"<a href=\"".$_SERVER['PHP_SELF']."?sess=".$_SESSION['sess']."&wp=".$_SESSION['wp']."&fid=".$this->fid."&print_doku=1\" name=\"print_doku\" type=\"submit\" onclick='refresh_site()' class=\"btn btn-custom btn-sm waves-effect waves-light\"> Doku </a>";

            }
        }
    }

    function ShowHtmLButtonMounting($link,$type="link",$target="_self"){
        if (($this->data['status'] >= 1) && $this->data['ffrei'] >= 1 && $this->GetPrinterActive("einbau")) {
            if ($type == "link") {
                return "<a href=\"" . $link . "&action=14&fid=" . $this->fid . "\"  target=\"$target\" class=\"btn btn-custom btn-sm waves-effect waves-light\"><i class='fa fa-print'> </i> Montage</a>";
            } else {
                return"<a href=\"".$_SERVER['PHP_SELF']."?sess=".$_SESSION['sess']."&wp=".$_SESSION['wp']."&fid=".$this->fid."&print_mounting=1\" name=\"print_mounting\" type=\"submit\" onclick='refresh_site()' class=\"btn btn-custom btn-sm waves-effect waves-light\"> Montage-Etikett </a>";

            }
        }
    }



    function ShowHtmLButtonEdit($link){
        if (($this->data['status']==1) ){
            return "<a href=\"".$link."&action=43&fid=".$this->fid ."\" onclick='refresh_site()' class=\"btn btn-custom btn-sm waves-effect waves-light\"><i class='fa fa-edit'> </i> Bearbeiten</a>";
        }
    }

    function ShowHtmLButtonView($link){
        if (($this->data['status'] == 0 or $this->data['status'] == 2)) {
            return "<a href=\"".$link."&action=14&fid=".$this->fid ."\" onclick='refresh_site()' class=\"btn btn-custom btn-sm waves-effect waves-light\"><i class='zmdi zmdi-eye'> </i> anzeigen</a>";
        }
    }
    function ShowHtmLButtonProductionDate(){
        return "<a href=\"#\" onclick=\"set_sn('".$this->GetSerialnumber()."','".$this->GetFDatum("Y-m-d")."','".$this->data['fartikelid']."','".GetArtikelName("text",$this->data['fartikelid'])."')\" class=\"btn btn-custom btn-sm waves-effect waves-light\"><i class='fa fa-check'> </i> Fertigungsdatum übernehmen</a>";
    }


    function ShowFidPropertyAsTable(){
        $h="<div class=\"table-responsive\">
                <table class=\"table table-striped\">
                  <tbody><tr>";
        $h.="<td>Bild</td>";
        $h.="<td>".$this->GetPicture()."</td>";
        $h.="</tr><tr>";
        $h .= "<td>Seriennummer</td>";
        $h .= "<td><h4>" . $this->GetSerialnumber() . "</h4></td>";
        $h .= "</tr><tr>";
        $h.="<td>Bezeichnung</td>";
        $h .= "<td><h4>" . $this->GetMaterialName() . "</h4></td>";
        $h.="</tr><tr>";
        $h.="<td>Material-Nr.</td>";
        $h .= "<td><h4>" . $this->GetMaterialNumber() . "</h4><br>";
        $h.="</tr><tr>";
        $h.="<td>Version</td>";
        $h.="<td>".$this->GetVersion()."</td>";
        $h.="</tr><tr>";
        $h .= "<td>Freigabestatus</td>";
        $h .= "<td>" . $this->GetFree("text") . "</td>";
        $h .= "</tr><tr>";
        $h.="<td>Prüfzeichen Typenschild</td>";
        $h.="<td>".$this->ShowHtmlIcons()."</td>";
        $h.="</tr><tr>";
        $h.="<td>Leistungangabe</td>";
        $h.="<td>".$this->GetPowerDesc()."</td>";
        $h.="</tr><tr>";
        $h.="<td>Traglast</td>";
        $h.="<td>".$this->GetMaxLoad()."</td>";
        $h.="</tr><tr>";
        $h.="<td>Verpackung</td>";
        $h.="<td>".$this->ShowHtmlIcons2("verpackung")."</td>";
        $h.="</tr><tr>";
        $h.="<td>EAN</td>";
        $h.="<td>".$this->GetEAN13()."</td>";
        $h.="</tr><tr>";
        $h.="<td>Konfiguration</td>";
        $h.="<td>".$this->ShowConfigAsPicture()."</td>";
        $h.="</tr><tr>";
        $h.="<td>Seriennumer</td>";
        $h.="<td>".$this->GetSerialnumber()."</td>";
        $h.="</tr><tr>";
        $h.="<td>Chargennummer</td>";
        $h.="<td>".$this->GetChargennumber()."</td>";
        $h.="</tr><tr>";
        if (($this->IsPropertyDesc(1))) {
            $h .= "<td>" . $this->GetPropertyDesc(1) . "</td>";
            $h .= "<td>" . $this->GetSerialnumber1() . "</td>";
            $h .= "</tr><tr>";
        }
        if (($this->IsPropertyDesc(2))) {
            $h .= "<td>" . $this->GetPropertyDesc(2) . "</td>";
            $h .= "<td>" . $this->GetSerialnumber2() . "</td>";
            $h .= "</tr><tr>";
        }
        if (($this->IsPropertyDesc(3))) {
        $h.="<td>".$this->GetPropertyDesc(3)."</td>";
        $h.="<td>".$this->GetSerialnumber3()."</td>";
        $h.="</tr><tr>";
        }
        if (($this->IsPropertyDesc(4))) {
            $h .= "<td>" . $this->GetPropertyDesc(4) . "</td>";
            $h .= "<td>" . $this->GetSerialnumber4() . "</td>";
            $h .= "</tr><tr>";
        }
        $h.="<td>Meldungsart</td>";
        $h.="<td>".$this->GetFidType()."</td>";
        $h.="</tr><tr>";
        $h.="<td>Status</td>";
        $h.="<td>".$this->GetStatus("text")."</td>";
        $h.="</tr><tr>";
        $h.="<td>Sonderfreigabe</td>";
        $h.="<td>".$this->GetSpecial("text")."</td>";
        $h.="</tr><tr>";
        $h.="<td>Bemerkungen</td>";
        $h.="<td>".$this->GetNotes()."</td>";
        $h.="</tr><tr>";
        $h.="<td>Sonderfreigabe</td>";
        $h.="<td>".$this->GetNotes1()."</td>";
        $h.="</tr><tr>";

        $h.="</tr></tbody></table></div>";
        return $h;
}

    function EditPropertyAsTable(){
        $d=new FisDocument("Geräteinfo","fid",$this->fid);
        $h="<div class=\"table-responsive\">
                <table class=\"table table-striped\">
                  <tbody><tr>";
        $h.="</tr><tr>";
        $h.="<td>".$this->GetPicture()."</td>";
        $h.="<td> <b> Seriennummer : <h3>".$this->GetSerialnumber()."</h3><br> ".$this->GetMaterialName()."</b><br>";

        $h.="<b> Materialnummer : ".$this->GetMaterialNumber()."</b> </td>";
        $h.="</tr><tr>";
        $h.="<td colspan='2'>".asTextField("Version","version",$this->data['version'],9)."</td>";
        $h.="</tr><tr>";
        if (($this->IsPropertyDesc(1))) {
            $h .= "<td colspan='2'>" . asTextField($this->GetPropertyDesc(1), "fsn1", $this->data['fsn1'], 9, "") . "</td>";
            $h .= "</tr><tr>";
        }
        if (($this->IsPropertyDesc(2))) {
            $h .= "<td colspan='2'>" . asTextField($this->GetPropertyDesc(2), "fsn2", $this->data['fsn2'], 9, "") . "</td>";
            $h .= "</tr><tr>";
        }
        if (($this->IsPropertyDesc(3))) {
            $h .= "<td colspan='2'>" . asTextField($this->GetPropertyDesc(3), "fsn3", $this->data['fsn3'], 9, "") . "</td>";
            $h .= "</tr><tr>";
        }
        if (($this->IsPropertyDesc(4))) {
            $h .= "<td colspan='2'>" . asTextField($this->GetPropertyDesc(4), "fsn4", $this->data['fsn4'], 9, "") . "</td>";
            $h .= "</tr><tr>";
        }


        $h .= "<td >Freigabestatus: " . $this->GetFree("text") . "</td>";
        $h .= "<td >Bearbeitungsstatus: " . $this->GetStatus("text") . "</td>";
        $h .= "</tr><tr>";
        $h .= "<td >Fertigungsstatus: " . $this->GetFidType("text") . "</td>";
        $h .= "<td >Arbeitsplatz: " . $this->data['farbeitsplatz'] . "</td>";
        $h .= "</tr><tr>";
        $h.="<td >".asSwitchery("Freigabe","ffrei",$this->data['ffrei'],"00b19d",9)."</td>";

        $h.="<td >".asSwitchery("Sonderfreigabe","fsonder",$this->data['fsonder'],"FF0000",9)."</td>";
        $h.="</tr><tr>";
        $h .= "<td colspan='2'>" . asTextarea("Sonderfreigabetext", "fnotes", utf8_decode($this->data['fnotes']), 3, 9, "") . "</td>";
        $h.="</tr><tr>";
        $h .= "<td colspan='2'>" . asTextarea("Bemerkungen", "fnotes1", utf8_decode($this->data['fnotes1']), 3, 9, "") . "</td>";
        $h.="</tr><tr>";
        $h.="<td colspan='2'>".asTextarea("Konfiguration","config",$this->data['configprofil'],3,9,"")."</td>";
        $h.="</tr><tr>";

        $h.="<td colspan='2'>".$d->ShowHtmlInputField()."</td>";
        $h.="</tr></tbody></table></div>";
        return $h;
    }





    //config

    function LoadConfig($p)
    {
        $this->conf=json_decode($p,true);
    }
    function GetArtikelNummer(){
        return $this->conf['Artikel'];
    }
    function GetArtikelPlug(){
        if ($this->conf['Plug']=="CEE230V"){
            $this->conf['Plug']="CEE";
        }
        return substr($this->conf['Plug'],0,3);
    }
    function GetArtikelColor(){
        switch ($this->conf['Color']){
            case "grey":{ $s="grau"; break;}
            case "green":{ $s="grün"; break;}
            case "red":{ $s="rot"; break;}
            case "yellow":{ $s="gelb"; break;}
            case "blue":{ $s="blau"; break;}
        }

        return $s;
    }
    function GetArtikelLogo(){
        if ($this->conf['Logo']=="yes"){
            return "LOGO";
        }else{
            return "";
        }
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

    function GetFidHistory(){
        $this->LoadFidHistory();
        $h="<div class=\"table-responsive\">gespeicherte Fertigungsmeldungen für SN ".$this->GetSerialnumber()." - Material ".GetArtikelName("text",$this->data['fartikelid']);
        if (count($this->fidhistory)>0){
            foreach($this->fidhistory as $i=>$item){
                $fid=new FisFid($item['fid']);
                $fid->LoadUsageData();
                if ($i==0){
                   $h.="<table class=\"table table-striped\">
                    <thead>
                    <tr>
                        <th>Datum</th>
                        <th>SN</th>
                         <th>Version</th>
                         <th>Meldung</th>
                         <th>Prüfung</th>
                         <th>Status</th>
                         <th>".$fid->GetPropertyDesc(1)."</th>
                         <th>".$fid->GetPropertyDesc(2)."</th>
                         <th>".$fid->GetPropertyDesc(3)."</th>
                         <th>".$fid->GetPropertyDesc(4)."</th>
                         <th>Verwendung</th>
                         <th>Bemerkungen</th>
                        <th>Arbeitsplatz</th>
                        
                    </tr>
                    </thead>
                    <tbody>";
                }

                $h.="<tr><td>".substr($item['fdatum'],0,16)."</td>";
                $h.="<td>".sprintf("%06.0f",$item['fsn'])."</td>";
                $h.="<td>".$item['version']."</td>";
                $h.="<td>".$fid->GetFidType()."</td>";
                $h.="<td>".$fid->GetFree("text").$fid->GetSpecial("text")."</td>";
                $h.="<td>".$fid->GetStatus("text")."</td>";
                $h.="<td>".$item['fsn1']."</td>";
                $h.="<td>".$item['fsn2']."</td>";
                $h.="<td>".$item['fsn3']."</td>";
                $h.="<td>".$item['fsn4']."</td>";
                $h.="<td>".$fid->GetUsageFidHistory()."</td>";
                $h .= "<td>" . utf8_decode($item['fnotes']) . "</td>";
                $h.="<td>".$item['farbeitsplatz']."</td></tr>";
            }
            $h.="</tbody>";
            $h.="</table>";
        }else{
            $h.="<div class='alert alert-info'>keine Fertigungsmeldungen vorhanden</div>";

        }
        $h.="</div>";

        return $h;
    }

    function GetUsageFidHistory($showsetfd=false){
        $this->LoadUsageData();
        $h="<div class=\"table-responsive\">";
        if (count($this->usage) > 0) {
            foreach ($this->usage as $i => $item) {

                $fid=new FisFid($item['fid']);
                $fid->LoadUsageData();
                if ($i==0){
                    $h.="<table class=\"table table-striped\">
                    <thead>
                    <tr>
                        <th>Datum</th>
                        <th>SN</th>
                         <th>Material</th>
                          <th>Bezeichnung</th>
                         <th>Version</th>
                         <th>Meldung</th>
                         <th>Prüfung</th>
                         <th>Status</th>
                         <th>".$fid->GetPropertyDesc(1)."</th>
                         <th>".$fid->GetPropertyDesc(2)."</th>
                         <th>".$fid->GetPropertyDesc(3)."</th>
                         <th>".$fid->GetPropertyDesc(4)."</th>
                         <th>Verwendung</th>
                        <th>Arbeitsplatz</th>";
                    if ($showsetfd) {
                        $h .= "<th>Aktion</th>";
                    }
                    $h.="</tr>
                    </thead>
                    <tbody>";
                }

                $h.="<tr><td>".substr($fid->data['fdatum'],0,16)."</td>";
                $h.="<td>".sprintf("%06.0f",$fid->data['fsn'])."</td>";
                $h.="<td>".$fid->GetMaterialNumber()."</td>";
                $h.="<td>".$fid->GetMaterialName()."</td>";
                $h.="<td>".$fid->data['version']."</td>";
                $h.="<td>".$fid->GetFidType()."</td>";
                $h.="<td>".$fid->GetFree("text").$fid->GetSpecial("text")."</td>";
                $h.="<td>".$fid->GetStatus("text")."</td>";
                $h.="<td>".$fid->data['fsn1']."</td>";
                $h.="<td>".$fid->data['fsn2']."</td>";
                $h.="<td>".$fid->data['fsn3']."</td>";
                $h.="<td>".$fid->data['fsn4']."</td>";
                $h.="<td>".$fid->GetUsageFidHistory()."</td>";
                $h.="<td>".$fid->data['farbeitsplatz']."</td>";
                if ($showsetfd) {
                    $h .= "<td>". $fid->ShowHtmLButtonProductionDate() . "</td>  ";
                }
                $h.="</tr>";
            }
            $h.="</tbody>";
            $h.="</table>";
        }else{
            $h.="keine";

        }
        $h.="</div>";

        return $h;
    }

    function ShowHtmlAllAsTable($status="1",$limit=100,$search="",$no_free_search_value_allowed=0,$showaction=true,$showsetfd=false){

        $this->SetSearchString($search);
        $this->SetLimit($limit);
        $this->SetNoFreeValueAllowed(0);
        $this->LoadAllData();

        if ($this->material > 0) {
            $mat = $this->GetMaterialName("text", $this->material);
        }
        $h = "<h4 class=\"header-title m-t-0 m-b-30\">Fertigungsmeldungen Suchbegriff:(" . $this->GetSearchString() . ") " . $mat . "</h4>";

        if (count($this->dataall) > 0) {

            $_SESSION['datatable']++;
            $_SESSION['script_datatable'].="
            
            var table  = $(\"#datatable".$_SESSION['datatable']."\").DataTable({
                            order: [[ 0, \"desc\" ]],
                    
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
                            lengthMenu: [[20,10, 25, 50, -1], [20,10, 25, 50, \"All\"]]
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

     
            ";
            $_SESSION['script'].="
            $('#datatable".$_SESSION['datatable']."').dataTable();
            ";

            $h.=" <table id=\"datatable".$_SESSION['datatable']."\" class=\"table table-striped dt-responsive nowrap\">
                    <thead>
                        <tr>
                            <th>SN</th>
                            <th>Material</th>
                             <th>Materialbezeichnung</th>
                             <th>Version</th>
                             <th>Meldung</th>
                            
                              <th>Status</th>
                            
                             <th>Datum</th>
                             <th>SN1</th>
                             <th>SN2</th>
                             <th>SN3</th>";
                    if ($showaction) {
                        $h .= "<th>Montage</th>
                            <th>Typenschild</th>
                            <th>Verpackung</th>
                            <th>Dokuset</th>";
                    }
                        $h.="  <th>Aktion</th>";
                   if ($showsetfd) {
                        $h .= "<th>Datenübernahme</th>";
                   }
                        $h.="<th>Sonderfreigabe</th>
                            <th>Bemerkungen</th>
                              
                            <th>Status</th>
                             <th>Lokz</th>
                         
                        </tr>
                    </thead>
                    <tbody>";
            $form="";
            foreach ($this->dataall as $d => $i) {
             if ($i['fid']>0) {
                // <td>".$f->GetPicture("thumb-sm")."</td>
                //
                $f = new FisFid($i['fid']);
                $link = "index.php?sess=" . $_SESSION['sess'] . "&action=14&fid=" . $i['fid'];
                $form .= "<form method=\"POST\" action=\"" . $link . "\" >";
                $h .= "<tr>
                        <td><a href='$link' target='_self'>" . $f->GetSerialnumber() . "</a></td>
                        <td><a href='$link' target='_self'>" . $f->GetMaterialNumber() . "</a></td>
                        <td>" . $f->GetMaterialName() . "</td>
                        <td>" . $f->GetVersion() . "</td>
                        <td>" . $f->GetFidType() . "</td>
                        <td>" . $f->GetFree("text") . " " . $f->GetSpecial("text") . "</td>
                      
                        
                        <td>" . $f->GetFDatum() . "</td>
                        <td>" . $f->GetSerialnumber1() . "</td>
                        <td>" . $f->GetSerialnumber2() . "</td>
                        <td>" . $f->GetSerialnumber3() . "</td>";
            if ($showaction) {
                $h .= "   <td>" . $form . $f->ShowHtmLButtonMounting("", "submit") . "</form></td>                     
                    <td>" . $form . $f->ShowHtmLButtonTS("", "submit") . "</form></td>
                    <td>" . $form . $f->ShowHtmLButtonVP("", "submit") . "</form></td>
                    <td>" . $form . $f->ShowHtmLButtonDoku("", "submit") . "</form></td>";
            }
                    $h .= "<td>" . $f->ShowHtmLButtonEdit($link) . $f->ShowHtmLButtonView($link) . "</td>";
            if ($showsetfd) {
                 $h .= "<td>" . $form . $f->ShowHtmLButtonProductionDate() . "</form></td>  ";
            }
                $h.="<td>" . $f->GetNotes() . "</td>
                      <td>" . $f->GetNotes1() . "</td>
                      <td>" . $f->GetStatus("text") . "</td>
                      <td>" . $f->GetLokz("text") . "</td>
                    </tr>";
            }
            }
            $h.="</tbody></table>";
        }else {
            $h .= "keine Fertigungsmeldungen gefunden.";
        }
        return $h;
    }


    function ShowHtmlAssemblyAsTable($status="1",$limit=100,$search="",$no_free_search_value_allowed=0,$showaction=true,$showsetfd=false){

        $this->SetSearchString($search);
        $this->SetLimit($limit);
        $this->SetNoFreeValueAllowed(0);
        $this->LoadAllData();

        $h = "<h4 class=\"header-title m-t-0 m-b-30\">Fertigungsmeldungen Suchbegriff:(" . $this->GetSearchString() . ") Material: " . $this->material . " Gruppe:" . $this->gruppe . "  </h4>";

        if (count($this->dataall) > 0) {
            $_SESSION['datatable']++;

            $h.=" <table id=\"datatable".$_SESSION['datatable']."\" class=\"table table-striped dt-responsive nowrap\">
                    <thead>
                        <tr>
                            <th>SN</th>
                            <th>Material</th>
                             <th>Materialbezeichnung</th>
                             <th>Version</th>
                             <th>Meldung</th>
                              <th>Freigabestatus</th>
                              <th>Status</th>
                            
                             <th>Datum</th>
                             <th>SN1</th>
                             <th>SN2</th>
                             <th>SN3</th>";
            if ($showaction) {
                $h .= "<th>Montage</th>
                            <th>Typenschild</th>
                            <th>Verpackung</th>
                            <th>Dokuset</th>";
            }
            $h.="  <th>Aktion</th>";
            if ($showsetfd) {
                $h .= "<th>Datenübernahme</th>";
            }
            $h.="<th>Sonderfreigabe</th>
                            <th>Bemerkungen</th>
                          
                        </tr>
                    </thead>
                    <tbody>";
            $form="";
            foreach ($this->dataall as $d => $i) {
                if ($i['fid']>0) {
                    // <td>".$f->GetPicture("thumb-sm")."</td>
                    //
                    $f = new FisFid($i['fid']);
                    $link = "index.php?sess=" . $_SESSION['sess'] . "&action=14&fid=" . $i['fid'];
                    $form .= "<form method=\"POST\" action=\"" . $link . "\" >";
                    $h .= "<tr>
                        <td>" . $f->GetSerialnumber() . "</td>
                        <td><a href='$link' target='_self'>" . $f->GetMaterialNumber() . "</a></td>
                        <td>" . $f->GetMaterialName() . "</td>
                        <td>" . $f->GetVersion() . "</td>
                        <td>" . $f->GetFidType() . "</td>
                        <td>" . $f->GetFree("text") . " " . $f->GetSpecial("text") . "</td>
                        <td>" . $f->GetStatus("text") . "</td>
                        
                        <td>" . $f->GetFDatum() . "</td>
                        <td>" . $f->GetSerialnumber1() . "</td>
                        <td>" . $f->GetSerialnumber2() . "</td>
                        <td>" . $f->GetSerialnumber3() . "</td>";
                    if ($showaction) {
                        $h .= "   <td>" . $form . $f->ShowHtmLButtonMounting("", "submit") . "</form></td>                     
                    <td>" . $form . $f->ShowHtmLButtonTS("", "submit") . "</form></td>
                    <td>" . $form . $f->ShowHtmLButtonVP("", "submit") . "</form></td>
                    <td>" . $form . $f->ShowHtmLButtonDoku("", "submit") . "</form></td>";
                    }
                    $h .= "<td>" . $f->ShowHtmLButtonEdit($link) . $f->ShowHtmLButtonView($link) . "</td>";
                    if ($showsetfd) {
                        $h .= "<td>" . $form . $f->ShowHtmLButtonProductionDate() . "</form></td>  ";
                    }
                    $h.="<td>" . $f->GetNotes() . "</td>
                        <td>" . $f->GetNotes1() . "</td></tr><tr>
                         <td>Verwendung</td>
                         <td colspan='12'>" . $f->GetUsageFidHistory(true) . "</td>
                    </tr>";
                }
            }
            $h.="</tbody></table>";

            $h .= $this->sql;
        }else {
            $h .= "keine Fertigungsmeldungen gefunden.";
        }
        return $h;
    }

    function EditFIDChange()
    {

        $h = "<div class=\"col-lg-12 table-responsive\">Wählen Sie aus den Optionen aus<br>";

        //Neuware
        if ($this->fidtype == 0) {
            $visible0 = false;
            $in0 = "";
            $visible1 = false;
            $in1 = "";
            $visible2 = false;
            $in2 = "";
            $visible3 = false;
            $in3 = "";
            $visible4 = false;
            $in4 = "";
            $visible5 = false;
            $in5 = "";
        }
        //Nacharbeit
        if ($this->fidtype == 1) {
            $visible0 = false;
            $in0 = "";
            $visible1 = false;
            $in1 = "";
            $visible2 = true;
            $in2 = "in";
            $visible3 = false;
            $in3 = "";
            $visible4 = false;
            $in4 = "";
            $visible5 = false;
            $in5 = "";
        }
        //Reparatur /Wiederholungsprüfung
        if ($this->fidtype == 2) {
            $visible0 = false;
            $in0 = "";
            $visible1 = false;
            $in1 = "";
            $visible2 = false;
            $in2 = "";
            $visible3 = true;
            $in3 = "in";
            $visible4 = false;
            $in4 = "";
            $visible5 = false;
            $in5 = "";
        }
        //Verschrottung
        if ($this->fidtype == 4) {
            $visible0 = false;
            $in0 = "";
            $visible1 = false;
            $in1 = "";
            $visible2 = false;
            $in2 = "";
            $visible3 = false;
            $in3 = "";
            $visible4 = true;
            $in4 = "in";
            $visible5 = false;
            $in5 = "";
        }
        //Löschen
        if ($this->fidtype == 99) {
            $visible0 = false;
            $in0 = "";
            $visible1 = false;
            $in1 = "";
            $visible2 = false;
            $in2 = "";
            $visible3 = false;
            $in3 = "";
            $visible4 = false;
            $in4 = "";
            $visible5 = true;
            $in5 = "in";
        }

        $h .= "<div class=\"panel-group table-responsive\" id=\"accordionR\" role=\"tablist\"
                     aria-multiselectable=\"false\">
                     <div class=\"panel panel-default bx-shadow-none\">
                        <div class=\"panel-heading\" role=\"tab\" id=\"headingRnull\">
                            <h4 class=\"panel-title\">
                                <a role=\"button\" data-toggle=\"collapse\"
                                   data-parent=\"#accordion\" href=\"#collapseRnull\"
                                   aria-expanded=\"$visible0\" aria-controls=\"collapseRnull\">
                                    Bauteildaten freigeben
                                </a>
                            </h4>
                        </div>
                        <div id=\"collapseRnull\" class=\"panel-collapse collapse $in0\"
                             role=\"tabpanel\" aria-labelledby=\"headingnull\">
                            <div class=\"panel-body\">
                                     <div class=\"table-responsive\">
                                    <table class=\"table table-striped\">
                                      <tbody><tr><td>
                                        " . $this->EditFIDSetFree() . "
                                    </td></tr></tbody></table></div>
                            </div>
                        </div>
                    </div>
                    <div class=\"panel panel-default bx-shadow-none\">
                        <div class=\"panel-heading\" role=\"tab\" id=\"headingROne\">
                            <h4 class=\"panel-title\">
                                <a role=\"button\" data-toggle=\"collapse\"
                                   data-parent=\"#accordion\" href=\"#collapseROne\"
                                   aria-expanded=\"$visible1\" aria-controls=\"collapseROne\">
                                    Bauteildaten korrigieren
                                </a>
                            </h4>
                        </div>
                        <div id=\"collapseROne\" class=\"panel-collapse collapse $in1\"
                             role=\"tabpanel\" aria-labelledby=\"headingOne\">
                            <div class=\"panel-body\">
                                     <div class=\"table-responsive\">
                                    <table class=\"table table-striped\">
                                      <tbody><tr><td>
                                        " . $this->EditFID0() . "
                                    </td></tr></tbody></table></div>
                            </div>
                        </div>
                    </div>
                    <div class=\"panel panel-default bx-shadow-none\">
                        <div class=\"panel-heading\" role=\"tab\" id=\"headingRTwo\">
                            <h4 class=\"panel-title\">
                                <a class=\"collapsed\" role=\"button\" data-toggle=\"collapse\"
                                   data-parent=\"#accordion\" href=\"#collapseRTwo\"
                                   aria-expanded=\"$visible2\" aria-controls=\"collapseRTwo\">
                                   Nacharbeit mit anderen Bauteildaten dokumentieren
                                </a>
                            </h4>
                        </div>
                        <div id=\"collapseRTwo\" class=\"panel-collapse collapse $in2\"
                             role=\"tabpanel\" aria-labelledby=\"headingRTwo\">
                            <div class=\"panel-body\">
                                    <div class=\"table-responsive\">
                                    <table class=\"table table-striped\">
                                      <tbody><tr><td>
                                        " . $this->EditFID1() . "
                                    </td></tr></tbody></table></div> 
                            </div>
                        </div>
                    </div>
                    <div class=\"panel panel-default bx-shadow-none\">
                        <div class=\"panel-heading\" role=\"tab\" id=\"headingRThree\">
                            <h4 class=\"panel-title\">
                                <a class=\"collapsed\" role=\"button\" data-toggle=\"collapse\"
                                   data-parent=\"#accordion\" href=\"#collapseRThree\"
                                   aria-expanded=\"$visible3\" aria-controls=\"collapseRThree\">
                                    Wiederholungsprüfung mit gleichen Bauteildaten
                                </a>
                            </h4>
                        </div>
                        <div id=\"collapseRThree\" class=\"panel-collapse collapse $in3\"
                             role=\"tabpanel\" aria-labelledby=\"headingRThree\">
                            <div class=\"panel-body\">
                                      <div class=\"table-responsive\">
                                    <table class=\"table table-striped\">
                                      <tbody><tr><td>
                                        " . $this->EditFID2() . "
                                    </td></tr></tbody></table></div>
                            </div>
                        </div>
                        
                    </div>
                    <div class=\"panel panel-default bx-shadow-none\">
                        <div class=\"panel-heading\" role=\"tab\" id=\"headingRThree\">
                            <h4 class=\"panel-title\">
                                <a class=\"collapsed\" role=\"button\" data-toggle=\"collapse\"
                                   data-parent=\"#accordion\" href=\"#collapseRFour\"
                                   aria-expanded=\"$visible4\" aria-controls=\"collapseRFour\">
                                    Bauteil verschrotten
                                </a>
                            </h4>
                        </div>
                        <div id=\"collapseRFour\" class=\"panel-collapse collapse $in4\"
                             role=\"tabpanel\" aria-labelledby=\"headingFour\">
                            <div class=\"panel-body\">
                                      <div class=\"table-responsive\">
                                    <table class=\"table table-striped\">
                                      <tbody><tr><td>
                                         " . $this->EditFID4() . "
                                    </td></tr></tbody></table></div>
                            </div>
                        </div>
                        
                    </div>
                     <div class=\"panel panel-default bx-shadow-none\">
                        <div class=\"panel-heading\" role=\"tab\" id=\"headingRFive\">
                            <h4 class=\"panel-title\">
                                <a class=\"collapsed\" role=\"button\" data-toggle=\"collapse\"
                                   data-parent=\"#accordion\" href=\"#collapseRFive\"
                                   aria-expanded=\"$visible5\" aria-controls=\"collapseRFive\">
                                    Bauteildaten löschen
                                </a>
                            </h4>
                        </div>
                        <div id=\"collapseRFive\" class=\"panel-collapse collapse $in5\"
                             role=\"tabpanel\" aria-labelledby=\"headingFive\">
                            <div class=\"panel-body\">
                                      <div class=\"table-responsive\">
                                    <table class=\"table table-striped\">
                                      <tbody><tr><td>
                                         " . $this->EditFID99() . "
                                    </td></tr></tbody></table></div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div><!-- end col -->
        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
        </div>";
        return $h;
    }

    function EditFIDSetFree()
    {
        $h = "Diese Bauteildaten freigegeben";

        $h .= "<div class=\"form-group \">
             <button name=\"new_save_fid_free\" type=\"submit\" class=\"btn btn-success waves-effect waves-light pull-right\"> Freigabe ohne Druck</button>";
        $h .= "<button name=\"new_save_fid_free_and_print\" type=\"submit\" class=\"btn btn-success waves-effect waves-light pull-right\"> Freigabe mit<br>Montageaufkleber drucken </button>";
        $h .= "</div>";

        return $h;
    }

    function EditFID0()
    {
        $h = "Diese Bauteildaten werden überschrieben";
        $h .= $this->EditPropertyAsTable();
        $h .= "<div class=\"form-group \">
             <button name=\"new_save_fid_at_work\" type=\"submit\" class=\"btn btn-primary waves-effect waves-light pull-left\"> Überschreiben </button>";
        $h .= "<button name=\"new_save_fid_at_work_and_print\" type=\"submit\" class=\"btn btn-primary waves-effect waves-light pull-left\"> Überschreiben mit<br>Montageaufkleber drucken </button>";
        $h .= "</div>";
        $h .= "<div class=\"form-group \">
             <button name=\"new_save_fid_free\" type=\"submit\" class=\"btn btn-success waves-effect waves-light pull-right\"> Freigabe ohne Druck</button>";
        $h .= "<button name=\"new_save_fid_free_and_print\" type=\"submit\" class=\"btn btn-success waves-effect waves-light pull-right\"> Freigabe mit<br>Montageaufkleber drucken </button>";
        $h .= "</div>";

        return $h;
    }

    function EditFID1()
    {
        $material = new FisMaterial($this->data['fartikelid']);
        $h = "Die bisherige FID wird <code>gesperrt</code> und eine neuer Datensatz angelegt. Der neue Status ist <code>in Arbeit</code>. Die Seriennummer bleibt gleich.";
        $h .= "<div >";
        $h .= "Neue Komponenten eingeben";
        $h .= "<a href='#' id='btn' class='btn btn-info pull-right' onclick='Javascript: Autofill();'> bisherige Bauteildaten übernehmen</a>";

        $h .= $material->ShowHtmlInputFields("new_");
        $s = "";
        if (count($material->GetLastFSNxValues() > 0)) {
            $lastValues = $material->GetLastFSNxValues();
            $r = explode(";", $material->data['merkmale']);
            $count = count($r);
            if ($count > 1) {
                for ($i = 0; $i < ($count - 1); $i++) {
                    $index = $i + 1;
                    $s .= " $('#new_fsn$index').val($('#fsn" . $index . "').val() );";
                }
            }
        }
        $h .= "<script>
        function Autofill(){
            $s
        }


        </script>";

        $h .= asTextarea("Nacharbeitsgrund eingeben", "fid_new_notes", "Nacharbeit: ", 9, 15, "");
        $h .= "</div>";
        $h .= "<hr><div class=\"form-group \">
                <button name=\"new_save_fid_copy_and_print\" type=\"submit\" class=\"btn btn-primary waves-effect waves-light pull-right\"> Nacharbeit erstellen und Montageetikett erneut drucken </button>";
        $h .= "</div>";
        return $h;
    }

    function EditFID2()
    {
        $h = "Das Bauteil wird erneut geprüft und als Wiederholungsprüfung gespeichert.";
        $h .= "<hr><div class=\"form-group \">
                <button name=\"new_save_fid_repair_at_work\" type=\"submit\" class=\"btn btn-primary waves-effect waves-light pull-left\"> Wiederholungsprüfung</button>";
        $h .= "</div>";
        $h .= "<div class=\"form-group \">
                <button name=\"new_save_fid_repair\" type=\"submit\" class=\"btn btn-success waves-effect waves-light pull-right\"> Wiederholungsprüfung und freigeben </button>";
        $h .= "</div>";
        return $h;
    }

    function EditFID4()
    {
        $h = "Die Bauteildaten bleiben gespeichert. Das Bauteil wird als <code>verschrottet</code> gespeichert und hat den Status gesperrt.";
        $h .= "<hr><div class=\"form-group \">
                <button name=\"new_save_fid_scrap\" type=\"submit\" class=\"btn btn-danger waves-effect waves-light pull-right\"> Verschrotten </button>";
        $h .= "</div>";
        return $h;
    }

    function EditFID99()
    {
        $h = "Bauteildaten werden als Status <code>gelöscht</code> markiert. <br>Grund: <br>-Fehler beim Auswahl dr Artikelnummer. <br>-Fehlerhafte Etiketten";
        $h .= "<hr><div class=\"form-group \">
                <button name=\"new_save_fid_delete\" type=\"submit\" class=\"btn btn-danger waves-effect waves-light pull-right\"> Löschen </button>";
        $h .= "</div>";
        return $h;
    }
}