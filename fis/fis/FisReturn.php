<?php

/**
 * Created by PhpStorm.
 * User: andreasveltjens
 * Date: 26.10.18
 * Time: 22:27
 */
class FisReturn
{
    public $bks=6101;
    public $fmid = 0;
    public $data = array();
    public $FMdata = array();
    public $allprozess = array();
    public $allerrors=array();
    public $errordata=array();
    private $variants=array();
    private $confirmlist=array();
    public $slectedvariants=array();
    private $slectedvariant=array();
    private $materials=array();
    private $fids=array();
    private $repduration=0;
    private $confirmtxt="";
    private $order = "";

    public $customergroup = "";
    public $ref1 = 0;
    public $ref2 = 0;
    public $search = "";
    public $no_free_search_value_allowed = 0;
    public $fmstate = 0;
    public $limit = 100;
    public $datum="2018-01-01";

    function __construct($i = 0)
    {
        $this->bks = FIS_ID;
        $this->fmstate=$_GET['fmstate'];
        $this->fmid = $i;
        if ($i > 0) {
            $this->LoadData();
        }
        return $this;
    }
    function __toString(){
        return $this->fmid;
    }
    function LoadData()
    {

        include("./connection.php");
        $this->data = array();
        $sql = "SELECT * FROM fehlermeldungen WHERE fehlermeldungen.fmid = '" . $this->fmid . "'  LIMIT 0,1";

        $rst = $mysqli->query($sql);
        if (mysqli_affected_rows($mysqli) > 0) {
            $row_rst = $rst->fetch_assoc();
            $this->data = $row_rst;

        } else {
            $this->data = array();
            AddSessionMessage("warning", "Fehlermeldung <code> FMID $this->fmid </code> hat keine Datensätze.", "Datenbankfehler");
        }
        $mysqli->close();
        return $this;
    }

    function LoadReturnToConfirm($gwl=0,$order){
        // lffid	fmid	fid	lffuser	lokz

        include("./connection.php");
        $this->confirmlist = array();
        $this->repduration=0;
        $s="";
        if ($gwl==1){
            $s="AND artikeldaten.pcnagwl='".$order."'";
        }else{
            $s="AND artikeldaten.pcna='".$order."'";
        }


        $sql = "SELECT fehlermeldungen.fmid,fehlermeldungen.fm4notes FROM fehlermeldungen,artikeldaten 
                WHERE artikeldaten.artikelid=fehlermeldungen.fmartikelid 
                
                $s
                AND fehlermeldungen.gwl='$gwl'
                AND fehlermeldungen.fm5=1 
               
                AND fehlermeldungen.fm7=0 
                LIMIT 0,1000";

        $rst = $mysqli->query($sql);
        if ($mysqli->error) {
            AddSessionMessage("error", "Rückmeldung  <code> " . $this->search . "</code> konnten nicht abgefragt werden.", "Fehlermeldung");
        }
        if (mysqli_affected_rows($mysqli) > 0) {
            $row_rst = $rst->fetch_assoc();
            do {
                $this->confirmlist[] =array("fmid"=>$row_rst['fmid']);
                $this->repduration=$this->repduration+$row_rst['fm4notes'];

            } while ($row_rst = $rst->fetch_assoc());

            AddSessionMessage("info", mysqli_affected_rows($mysqli)." Fehlermeldungen gefunden <code> ORDER: $order - GWL:" . $gwl . "</code>", "DB Abfrage");

            $this->LoadAllConfirmMaterial();
        }
        $mysqli->close();
    }


    function LoadOrderConfirmData()
    {
        include("./connection.php");

        $sql = "SELECT * FROM fisorderconfirm  
       
        ORDER BY  fisorderconfirm.datum DESC
        LIMIT 0,12";

        $rst = $mysqli->query($sql);
        if ($mysqli->error) {

            AddSessionMessage("error", "erledigte Rückmeldung konnten nicht abgefragt werden.", "Fehlermeldung");
        }
        if (mysqli_affected_rows($mysqli) > 0) {
            $row_rst = $rst->fetch_assoc();
            do {
                $this->fmids[] = $row_rst;
            } while ($row_rst = $rst->fetch_assoc());

        } else {
            $this->fmids = array();
        }
        $mysqli->close();

    }
    function LoadFMData()
    {

        if ($this->no_free_search_value_allowed == 1 && strlen($this->search) == 0) {
            $this->FMdata = array();
            return $this;
        } else {
            include("./connection.php");
            $order = "ORDER BY fehlermeldungen.fmid DESC ";
            $this->order = " order: [[ 0, \"DESC\" ]],";
            $s = "";
            if ($this->fmstate == 1) {
                $s = " AND fm1=0    AND fm2=0 AND fm3=0 AND fm4=0 AND fm5=0 AND fm6=0 AND lokz=0";
            }
            if ($this->fmstate == 2) {
                $s = " AND fm1=1 AND fm2=0    AND fm3=0 AND fm4=0 AND fm5=0 AND fm6=0 AND lokz=0";
                $order = "ORDER BY fehlermeldungen.fmid ASC ";
                $this->order = " order: [[ 0, \"ASC\" ]],";
            }
            if ($this->fmstate == 3) {
                $s = " AND fm1=1 AND fm2=1 AND fm3=0    AND fm4=0 AND fm5=0 AND fm6=0 AND lokz=0 ";
                $order = "ORDER BY fehlermeldungen.fmid ASC ";
                $this->order = " order: [[ 0, \"ASC\" ]],";
            }
            if ($this->fmstate == 4) {
                $s = " AND fm1=1 AND fm2=1 AND fm3=1 AND fm4=0    AND fm5=0 AND fm6=0  AND lokz=0";
                $order = "ORDER BY fehlermeldungen.vip DESC, fehlermeldungen.gwl DESC, fehlermeldungen.fmid ASC ";
                $this->order = " order: [[ 7, \"DESC\" ],[ 8, \"DESC\" ],[ 0, \"ASC\" ]],";
            }
            if ($this->fmstate == 5) {
                $s = " AND fm1=1 AND fm2=1 AND fm3=1 AND fm4=1 AND fm5=0    AND fm6=0  AND vkz=0 AND lokz=0";
                $order = "ORDER BY fehlermeldungen.vip DESC, fehlermeldungen.gwl DESC, fehlermeldungen.fmid ASC ";
                $this->order = " order: [[ 7, \"DESC\" ],[ 8, \"DESC\" ],[ 0, \"ASC\" ]],";
            }
            if ($this->fmstate == 6) {
                $s = " AND fm1=1 AND fm2=1 AND fm3=1 AND fm4=1 AND fm5=1 AND fm6=0    AND lokz=0";
            }
            if ($this->fmstate == 7) {
                $s = " AND fm1=1 AND fm2=1 AND fm3=1 AND fm4=1 AND fm5=1 AND fm6=1  AND fm7=0  AND lokz=0";
            }
            if ($this->fmstate == 8) {
                $s = "AND fm7>=1    AND lokz=0";
            }



            if ($this->fmstate == 96) {
                $s = " AND fm1=1 AND fm2=1 AND fm3=1  AND exchange=1    AND lokz=0";
            }
            if ($this->fmstate == 97) {
                $s = " AND fm1=1 AND fm2=1 AND fm3=1  AND gwl=1    AND lokz=0";
            }
            if ($this->fmstate == 98) {
                $s = " AND fm1=1 AND fm2=1 AND fm3=1  AND upgrade=1    AND lokz=0";
            }
            if ($this->fmstate == 99) {
                $s = " AND fm1=1 AND fm2=1 AND fm3=1  AND vkz=1    AND lokz=0";
            }

            if ($this->fmstate == "" or $this->fmstate == 0) {
                $s = "";
            }

            if (strlen($this->customergroup) > 0) {

                $sql = "SELECT * FROM fehlermeldungen LEFT JOIN kundendaten ON 
            
                fehlermeldungen.idk=kundendaten.idk
                
                WHERE 
                (fehlermeldungen.fm11notes like '%" . $this->search . "%' 
                OR fehlermeldungen.rechnung like '%" . $this->search . "%'
                OR fehlermeldungen.lieferung like '%" . $this->search . "%'
                OR fehlermeldungen.fmsn like '" . $this->search . "' 
                OR fehlermeldungen.fmsn2 like'" . $this->search . "' 
                or fehlermeldungen.fmid like  '" . $this->search . "'
                ) AND kundendaten.suchbegriff like='%" . $this->customergroup . "%' 
                $s
                $order
                LIMIT 0," . $this->limit;
            } else {


                $sql = "SELECT * FROM fehlermeldungen WHERE 
                (fehlermeldungen.fm11notes like '%" . $this->search . "%' 
                OR fehlermeldungen.rechnung like '%" . $this->search . "%'
                OR fehlermeldungen.lieferung like '%" . $this->search . "%'
                OR fehlermeldungen.fmsn like '" . $this->search . "' 
                OR fehlermeldungen.fmsn2 like'" . $this->search . "' 
                or fehlermeldungen.fmid like  '" . $this->search . "'
                ) $s
                $order
                LIMIT 0," . $this->limit;
            }

            if ($this->ref1 > 0 or $this->ref2 > 0) {
                $sql = "SELECT * FROM fehlermeldungen WHERE 
                (
                fehlermeldungen.debitorennummer = '" . $this->ref1 . "'
                OR fehlermeldungen.debitorennummer = '" . $this->ref2 . "'     
                )
                AND fehlermeldungen.debitorennummer>0
                $s
                $order
                LIMIT 0," . $this->limit;
            }

            //$_SESSION['sql'][0] .= $sql;
            $rst = $mysqli->query($sql);
            if ($mysqli->error) {
                
                AddSessionMessage("error", "Fehlermeldungen  <code> " . $this->search . "</code> konnten nicht abgefragt werden.", "Fehlermeldung");
            }
            if (mysqli_affected_rows($mysqli) > 0) {
                $_SESSION['zzz'] = $sql;
                $row_rst = $rst->fetch_assoc();
                do {
                    $this->FMdata[] = $row_rst;
                } while ($row_rst = $rst->fetch_assoc());

            } else {
                $this->FMdata = array();
            }
            $mysqli->close();
        }
    }

    function LoadRueckholungData()
    {
        include("./connection.php");

        $sql = "SELECT * FROM fehlermeldungen WHERE 
        fehlermeldungen.lokz = 0
        AND fehlermeldungen.fm1datum > '".$this->datum."'
        AND fehlermeldungen.fm11notes like '%" . $this->search . "%' 
        ORDER BY fehlermeldungen.fmid DESC
        LIMIT 0," . $this->limit;


        //$_SESSION['sql'][0] .= $sql;
        $rst = $mysqli->query($sql);
        if ($mysqli->error) {
            
            AddSessionMessage("error", "Fehlermeldungen  <code> " . $this->search . "</code> konnten nicht abgefragt werden.", "Fehlermeldung");
        }
        if (mysqli_affected_rows($mysqli) > 0) {
            $row_rst = $rst->fetch_assoc();
            do {
                $this->FMdata[] = $row_rst;
            } while ($row_rst = $rst->fetch_assoc());

        } else {
            $this->FMdata = array();
        }
        $mysqli->close();

    }


    function LoadErrors(){
        // lffid	fmid	fid	lffuser	lokz

        include("./connection.php");
        $this->errordata = array();
        $sql = "SELECT * FROM fehler, linkfehlerfehlermeldung 
        WHERE fehler.fid = linkfehlerfehlermeldung.fid 
        AND linkfehlerfehlermeldung.lokz = 0 
        AND linkfehlerfehlermeldung.fmid='".$this->fmid."' LIMIT 0,100";

        $rst = $mysqli->query($sql);
        if ($mysqli->error) {
            AddSessionMessage("error", "Fehlerzuordnung  <code> " . $this->search . "</code> konnten nicht abgefragt werden.", "Fehlermeldung");
        }
        if (mysqli_affected_rows($mysqli) > 0) {
            $row_rst = $rst->fetch_assoc();
            do {
                $this->errordata[] = $row_rst;
            } while ($row_rst = $rst->fetch_assoc());

        }
        $mysqli->close();
    }

    function LoadAviableErrors(){
        // fid	fkurz	fname	fuser	lokz	farbeitsplatzgruppe	fkosten
        // LEFT JOIN linkfehlerfehlermeldung ON (linkfehlerfehlermeldung.fid = linkfehlerartikel.fid
        // AND linkfehlerfehlermeldung.fmid='$this->fmid')

        include("./connection.php");

        $sql="SELECT fehler.fid, fehler.fname, fehler.fkurz, linkfehlerfehlermeldung.fid  as fid2
              FROM fehler, linkfehlerartikel 
              LEFT JOIN linkfehlerfehlermeldung ON (linkfehlerfehlermeldung.fmid='".$this->fmid."' AND linkfehlerfehlermeldung.fid = linkfehlerartikel.fid )
              WHERE linkfehlerartikel.artikelid = '".$this->data['fmartikelid']."'
              AND linkfehlerartikel.fid = fehler.fid
              ORDER BY fehler.fname";


        $rst = $mysqli->query($sql);
        if ($mysqli->error) {
            AddSessionMessage("error", "Fehlerauswahl  <code> " . $this->search . "</code> konnten nicht abgefragt werden.", "Fehlermeldung");
        }
        if (mysqli_affected_rows($mysqli) > 0) {
            $row_rst = $rst->fetch_assoc();
            do {
                $this->allerrors[] = $row_rst;
            } while ($row_rst = $rst->fetch_assoc());
            $mysqli->close();
        } else {
            $this->allerrors= array();
        }
    }

    function LoadSelectedRepairVariant(){
        include ("./connection.php");
        $this->slectedvariant=array();
        $sql="SELECT * FROM  `fehlermeldungsdaten` WHERE `fehlermeldungsdaten`.`fmid`='".$this->fmid."'
             AND `fehlermeldungsdaten`.`entid`>='1'
              LIMIT 0 , 10";

        $rst = $mysqli->query($sql);

        if ($mysqli->error) {
            AddSessionMessage("error", "Reparaturvarianten <code> " . $s . "</code> konnten nicht abgefragt werden.", "Fehlermeldung");
        }
        if (mysqli_affected_rows($mysqli) > 0) {
            $row_rst = $rst->fetch_assoc();
            do {
                $this->slectedvariant[] = $row_rst;
            }while ($row_rst=$rst->fetch_assoc() );
        }
        $mysqli->close();

    }
    function LoadSelectedVariants(){
        include ("./connection.php");
        $this->slectedvariants=array();
        $sql="SELECT * FROM  `fehlermeldungsdaten` WHERE `fehlermeldungsdaten`.`fmid`='".$this->fmid."'
              ORDER BY  `fehlermeldungsdaten`.`varname` ASC 
              LIMIT 0 , 100";

        $rst = $mysqli->query($sql);
        if ($mysqli->error) {
            AddSessionMessage("error", "Selektierte Varianten <code> " . $s . "</code> konnten nicht abgefragt werden.", "Fehlermeldung");
        }
        if (mysqli_affected_rows($mysqli) > 0) {
            $row_rst = $rst->fetch_assoc();
            do {
                $this->slectedvariants[] = $row_rst;
            }while ( $row_rst = $rst->fetch_assoc() );

        }
        $mysqli->close();

    }

    function LoadVariantProperty($s){

        include("./connection.php");

        $sql="SELECT entscheidung.entid,entscheidung.entname, entscheidung.gvwl, entscheidungsvarianten.varname, 
              entscheidungsvarianten.varbeschreibung, 
              entscheidungsvarianten.varid,entscheidungsvarianten.Kosten1, entscheidungsvarianten.vkz,
              entscheidungsvarianten.exchange, entscheidungsvarianten.upgrade
              FROM entscheidungsvarianten, entscheidung
              WHERE entscheidungsvarianten.varid='".$s."'
              AND entscheidung.entid = entscheidungsvarianten.entid
              LIMIT 0,1 ";



        $rst = $mysqli->query($sql);
        if ($mysqli->error) {
            AddSessionMessage("error", "Varianteneigenschaften <code> " . $s . "</code> konnten nicht abgefragt werden.", "Fehlermeldung");
        }
        if (mysqli_affected_rows($mysqli) > 0) {
            $row_rst = $rst->fetch_assoc();
            $r = $row_rst;

        } else {
            $r=array();
        }
        $mysqli->close();
        return $r;
    }

    function LoadAviableProzess(){
        // fid	fkurz	fname	fuser	lokz	farbeitsplatzgruppe	fkosten
        //  artikeldaten_entscheidung_link

        include("./connection.php");

        $sql="SELECT entscheidung.entid,entscheidung.entname, entscheidungsvarianten.varname, entscheidungsvarianten.varbeschreibung, 
              entscheidungsvarianten.varid,entscheidungsvarianten.Kosten1, 
              CONVERT(SUBSTRING_INDEX(entscheidungsvarianten.varname,'-',-1),UNSIGNED INTEGER) as variante
              FROM entscheidung, entscheidungsvarianten, artikeldaten_entscheidung_link 
              WHERE artikeldaten_entscheidung_link.id_artikel = '".$this->data['fmartikelid']."'
              AND artikeldaten_entscheidung_link.id_ent = entscheidungsvarianten.entid
              AND entscheidung.entid = entscheidungsvarianten.entid
              AND entscheidung.lokz = 0
              AND entscheidungsvarianten.lokz=0
              ORDER BY entscheidung.entname ASC, variante ASC";


        $rst = $mysqli->query($sql);
        if ($mysqli->error) {
            AddSessionMessage("error", "Fehlerauswahl  <code> " . $this->search . "</code> konnten nicht abgefragt werden.", "Fehlermeldung");
        }
        if (mysqli_affected_rows($mysqli) > 0) {
            $row_rst = $rst->fetch_assoc();
            do {
                $this->allprozess[] = $row_rst;
            } while ($row_rst = $rst->fetch_assoc());

        } else {
            $this->allprozess= array();
        }
        $mysqli->close();
    }

    function LoadConfirmMaterial(){
        include ("./connection.php");

        $this->materials=array();
        $sql="SELECT * FROM  `komponenten` WHERE `komponenten`.`id_fm`='".$this->fmid."'
             AND `komponenten`.`lokz`='0'
              LIMIT 0 , 100";

        $rst = $mysqli->query($sql);
        if ($mysqli->error) {
            AddSessionMessage("error", "Materialkomponenten <code> FMID " . $this->fmid . "</code> konnten nicht abgefragt werden.", "Fehlermeldung");
        }
        if (mysqli_affected_rows($mysqli) > 0) {
            $row_rst=$rst->fetch_assoc();
            do{
            $this->materials[] = $row_rst;
            }while($row_rst=$rst->fetch_assoc() );
        }
        $mysqli->close();

    }

    function LoadAllConfirmMaterial(){
        include ("./connection.php");

        $this->confirmtxt="";
        $r=array();
        if (count($this->confirmlist)>0){
            $i=0;
            foreach ($this->confirmlist as $item=>$fmid){
                $fms.="FMID".$fmid['fmid']." ";
                $sql="SELECT * FROM  `komponenten` WHERE `komponenten`.`id_fm`='".$fmid['fmid']."'
             AND `komponenten`.`lokz`='0'
              LIMIT 0 , 100";

                $rst = $mysqli->query($sql);
                if ($mysqli->error) {
                    AddSessionMessage("error", "Materialkomponenten <code> FMID " . $fmid['fmid'] . "</code> konnten nicht abgefragt werden.", "Fehlermeldung");
                }

                if (mysqli_affected_rows($mysqli) > 0) {
                    AddSessionMessage("info", mysqli_affected_rows($mysqli)." Materialkomponenten  wurden abgefragt und für SAP formatiert.<br>".$fms, "Info");

                    $row_rst=$rst->fetch_assoc();

                    do{

                        $i++;
                        $anzahl=0;
                        if (count($r)>0){
                            foreach ($r as $x=>$y){
                                foreach ($y as $z=>$a){
                                    //$this->confirmtxt.=print_r($a,true);
                                    if ($row_rst['kompnr']==$z) {
                                        $anzahl=$a['anzahl'];
                                        unset($r[$x]);
                                    }
                                }
                            }
                        }

                        $r[$i] =array($row_rst['kompnr']=>array(
                            "kompnr"=>$row_rst['kompnr'],
                            "anzahl" => $row_rst['anzahl']+$anzahl,
                            "unit" => $row_rst['unit'],
                            "fmid" => $fmid['fmid']

                        ) );

                    }while($row_rst=$rst->fetch_assoc() );
                }





            }
            if (count($r)>0){
                //$this->confirmtxt.=print_r($r,true);

                foreach ($r as $material=>$kompnr) {
                    //$this->confirmtxt.=print_r($kompnr,true);
                    foreach ($kompnr as $mat=>$item) {
                        if (GetArtikelName("isFisArtikel", $item['kompnr'])) {
                            if (GetArtikelName("isFisRep", $item['kompnr'])) {
                                $this->confirmtxt .= $item['kompnr']

                                    . chr(9) . $item['anzahl']
                                    . chr(9) . $item['unit']
                                    . chr(9) . $this->bks
                                    . chr(9) . $item['lagerort']
                                    . chr(13);
                            } else {
                                AddSessionMessage("info", "Materialkomponente <code>" . $item['kompnr'] . "</code> wurde " . $item['anzahl'] . "-mal gemeldet, ist aber im FIS als Schüttgut angelegt.<br>" . $fms, "Schüttgutentnahme");

                            }
                        } else {
                            AddSessionMessage("error", "Materialkomponente <code>" . $item['kompnr'] . "</code> " . $item['anzahl'] . "-mal gemeldet, ist aber nicht als Artikel im FIS angelegt.<br>" . $fms, "Stammdatenfehler");

                        }
                    }
                }
            }else{
                AddSessionMessage("warning", "Materialkomponenten wurden abgefragt, jedoch wurde keine Materialentnahme gemeldet.<br>".$fms, "Info");

            }


            $mysqli->close();
        }else{
            $this->confirmtxt="keine Daten";
        }


    }

    function LoadFids($sn,$artikelid){
        include ("./connection.php");

        $this->fids=array();
        $sql="  SELECT * FROM  `fertigungsmeldungen` WHERE `fertigungsmeldungen`.`fsn`='".$sn."'
                AND `fertigungsmeldungen`.`fartikelid`>='".$artikelid."'
                ORDER BY fertigungsmeldungen.fdatum DESC
                LIMIT 0 , 100";

        $rst = $mysqli->query($sql);
        if ($mysqli->error) {
            AddSessionMessage("error", "Fertigungsmeldungen für <code> SN " . $sn . "</code> konnten nicht abgefragt werden.", "Fehlermeldung");
        }
        if (mysqli_affected_rows($mysqli) > 0) {
            $row_rst=$rst->fetch_assoc();
            do{
                $this->fids[] = $row_rst;
            }while($row_rst=$rst->fetch_assoc() );
        }
        $mysqli->close();

    }

    function LoadReturnDevice($label){
        include ("./connection.php");


        $sql="  SELECT * FROM `rueckholung`
                WHERE rueckholung.sendungsid like '%" . $label . "%' 
                ORDER BY rueckholung.datum DESC
                LIMIT 0 , 1";

        $rst = $mysqli->query($sql);
        if ($mysqli->error) {
            AddSessionMessage("error", "Fertigungsmeldungen für <code> SN " . $sn . "</code> konnten nicht abgefragt werden.", "Fehlermeldung");
        }
        if (mysqli_affected_rows($mysqli) > 0) {
            $row_rst=$rst->fetch_assoc();
            $idk = $row_rst['idk'];
        }else{
            $idk=0;
        }
        $mysqli->close();
        return $idk;
    }

    function GetRepairDuration()
    {
        $t1 = strtotime($this->data['fm1datum']);
        if ($this->data['fm5'] > 0) {
            $t2 = strtotime($this->data['fm5datum']);
        } else {
            $t2 = time();
        }
        $t = $t2 - $t1;
        return "Reparaturdauer: " . round($t / (60 * 60 * 24), 0) . " Tage";
    }

    function GetDeviceAge()
    {
        $t1 = strtotime($this->data['fmfd']);

        $t2 = time();

        $t = ($t2 - $t1) / (60 * 60 * 24);
        if ($t > 365) {
            $y = round($t / 365, 0) - 1;
            $d = $t - ($y * 365);
            if ($y > 1) $e = "e";
            if ($d > 1) $ee = "e";
            $s = "Gerätealter:  " . $y . " Jahr$e und " . round($d, 0) . " Tag$ee";
        } else {
            $s = "Gerätealter: " . round($t, 0) . " Tage";
        }
        return $s;
    }
//print
    function GetPrinterActive(){
        return true;
    }
    function GetPrinterPageCount(){
        return 1;
    }
    function GetPrinterPageCountGLS(){
        return 1;
    }
// new FMID
    function Add (){
        include("./connection.php");

        if (($_REQUEST['fm_sn']*1)==0){
            AddSessionMessage("info", "Bitte eriennumer eingeben", "SN fehlt");
        }else{
            if (($_REQUEST['fm_artikelid']*1)==0){
            AddSessionMessage("info", "Bitte Material auswählen", "Material fehlt");
            }else{
                if (strlen($_REQUEST['fm_fd'])<10){
                    AddSessionMessage("info", "Bitte Fertigungsdatum eingeben Format: YYYY-MM-DD", "Datum fehlt");
                }else{

                            if (strlen($_REQUEST['fm_fm1notes'])==0){
                                $idk=new FisCustomer($_REQUEST['fm_idk']);
                                $_REQUEST['fm_fm1notes']=$idk->GetCustomerName();
                            }

                            if (strlen($_REQUEST['fm_deliverycontent'])==0){
                                $_REQUEST['fm_deliverycontent']="keine";
                            }

                                $updateSQL = sprintf("INSERT INTO  fehlermeldungen (fmsn,fmsn2, fmartikelid,fmartikelid2, fmfd, fmdatum, idk, fm11notes,fm1notes,fm12notes, werk) 
                                                                VALUES (%s, %s, %s,%s, %s,%s,%s,%s,%s,%s,%s)",

                                    GetSQLValueString($_REQUEST['fm_sn'], "text"),
                                    GetSQLValueString($_REQUEST['fm_sn'], "text"),
                                    GetSQLValueString($_REQUEST['fm_artikelid'], "int"),
                                    GetSQLValueString($_REQUEST['fm_artikelid'], "int"),
                                    GetSQLValueString($_REQUEST['fm_fd'], "text"),
                                    GetSQLValueString(date('Y-m-d',time()), "text"),
                                    GetSQLValueString($_REQUEST['fm_idk'], "int"),
                                    GetSQLValueString($_REQUEST['fm_label'], "text"),
                                    GetSQLValueString($_REQUEST['fm_fm1notes'], "text"),
                                    GetSQLValueString($_REQUEST['fm_deliverycontent'], "text"),

                                    GetSQLValueString($this->bks, "int")

                                );

                                $rst = $mysqli->query($updateSQL);
                                if ($mysqli->error) {
                                    
                                    AddSessionMessage("error", "Fehlermeldung  <code> FHMID " . $_REQUEST['fmsn'] . "</code> konnte nicht durchgeführt werden.", "Fehlermeldung DB-Error");
                                } else {
                                    
                                    $this->fmid=mysqli_insert_id($mysqli);
                                    $txt = "Fehlermeldung  <code> FMID" . $this->fmid . "</code> mit <code> SN" . $_REQUEST['fm_sn']. "</code> wurde angelegt.";
                                    AddSessionMessage("success", $txt, "Fehlrmeldung erfolgreich");

                                    $w = new FisWorkflow($txt, "fmid", $this->fmid);
                                    $w->Add();
                                    $_SESSION['fmid']=$this->fmid;
                                    $_GET['fmid']=$this->fmid;
                                }

                                if ($_REQUEST['fm_idk']>0){
                                    $_REQUEST['same']=1;
                                    $_REQUEST['idk']=$_REQUEST['fm_idk'];
                                    $_REQUEST['idk2']=$_REQUEST['fm_idk'];
                                    $this->TakeAdressbook();
                                    $_POST['fm_new_contact']=1;
                                    $this->SaveContactPersons();
                                }

                                return $this->fmid;
                }
            }
        }
        return 0;
    }

//errors
    function DeleteErrors(){
        include ("./connection.php");
        $updateSQL = sprintf("
                DELETE FROM linkfehlerfehlermeldung WHERE linkfehlerfehlermeldung.fmid='%s'",
            GetSQLValueString($this->fmid, "int")
        );
        $rst = $mysqli->query($updateSQL);
        if ($mysqli->error) {
            AddSessionMessage("error","Fehlerzuordnung wurde nicht gelöscht, da ein Fehler aufgetreten ist.","Fehlermeldung");
        }
    }
    function AddErrors($id){
        // lffid	fmid	fid	lffuser	lokz
        include("./connection.php");
        $updateSQL = sprintf("INSERT INTO  linkfehlerfehlermeldung (fid, fmid) 
                                            VALUES (%s, %s)",
            GetSQLValueString($id, "int"),
            GetSQLValueString($this->fmid, "int"));

        $rst = $mysqli->query($updateSQL);
        if ($mysqli->error) {
            
            AddSessionMessage("error", "Fehlerzuordnung  <code> ERROR ID " . $id . "</code> konnte nicht durchgeführt werden.", "Fehlermeldung DB-Error");
        }
    }
    function SaveErrors()
    {
        if ($this->data['fm1'] == 0){
            $this->DeleteErrors();
        }
        if (count($_POST['my_multi_selectError'])>0) {

            foreach ($_POST['my_multi_selectError'] as $item) {
               $this->AddErrors($item);
            }
            $txt="Fehlerauswahl gespeichert.";
            AddSessionMessage("success", $txt, "Fehlerauswahl erfolgreich");
            $w = new FisWorkflow($txt, "fmid", $this->fmid);
            $w->Add();
        }
        return $this;
    }

// repair variants
    function DeleteVariants(){
        include ("./connection.php");
        $updateSQL = sprintf("
                DELETE FROM fehlermeldungsdaten WHERE fehlermeldungsdaten.fmid='%s'",
            GetSQLValueString($this->fmid, "int")
        );
        $rst = $mysqli->query($updateSQL);
        if ($mysqli->error) {
            AddSessionMessage("error","Fehlerzuordnung wurde nicht gelöscht, da ein Fehler aufgetreten ist.","Fehlermeldung");
        }
    }
    function DeleteVariant($variant){
        include ("./connection.php");
        $updateSQL = sprintf("
                DELETE FROM fehlermeldungsdaten WHERE fehlermeldungsdaten.fmid='%s' AND fehlermeldungsdaten.varid='$variant'",
            GetSQLValueString($this->fmid, "int")
        );
        $rst = $mysqli->query($updateSQL);
        if ($mysqli->error) {
            AddSessionMessage("error","Variante wurde nicht gelöscht, da ein Fehler aufgetreten ist.","Fehlermeldung");
        }else{
            AddSessionMessage("info"," Variante  <code> VARID " . $_REQUEST['varname_'.$variant] . "</code> wurde gelöscht.","Fehlermeldung");

        }
    }
    function SaveVariant($variant){
        include ("./connection.php");
        //varid	varname	varbeschreibung	Kosten1	Datum	lokz	Kosten2	entid	kosten3	kst	fmid
        $updateSQL=sprintf("
                UPDATE fehlermeldungsdaten SET varname=%s, varbeschreibung=%s, Kosten1=%s
                WHERE varid='".$variant."' ",
            GetSQLValueString(utf8_decode($_REQUEST['varname_'.$variant]), "text"),
            GetSQLValueString(utf8_decode($_REQUEST['varbeschreibung_'.$variant]), "text"),
            GetSQLValueString(utf8_decode($_REQUEST['Kosten1_'.$variant]), "text")

        );
        $rst = $mysqli->query($updateSQL);
        if ($mysqli->error) {
            
            AddSessionMessage("error","Änderung Variante  <code> VARID " . $_REQUEST['varname_'.$variant] . "</code> konnte nicht durchgeführt werden.","Fehlermeldung");
        }

    }
    function AddVariant($id){
        // lffid	fmid	fid	lffuser	lokz
        include("./connection.php");
        $r=$this->LoadVariantProperty($id);
        $updateSQL = sprintf("INSERT INTO  fehlermeldungsdaten (varname, varbeschreibung, Kosten1, fmid) 
                                            VALUES (%s, %s, %s, %s)",
            GetSQLValueString(utf8_decode($r['varname']), "text"),
            GetSQLValueString(utf8_decode($r['varbeschreibung']), "text"),
            GetSQLValueString(utf8_decode($r['Kosten1']), "text"),
            GetSQLValueString($this->fmid, "int"));

        $rst = $mysqli->query($updateSQL);
        if ($mysqli->error) {
            
            AddSessionMessage("error", "Varianten kopiert  <code> ERROR ID " . $id . "</code> konnte nicht durchgeführt werden.", "Fehlermeldung DB-Error");
        }
    }
    function SaveVariantDecission($variant){
        include ("./connection.php");
        //varid	varname	varbeschreibung	Kosten1	Datum	lokz	Kosten2	entid	kosten3	kst	fmid
        $updateSQL=sprintf("
                UPDATE fehlermeldungsdaten SET entid=%s
                WHERE varid='".$variant."' ",
                GetSQLValueString(1, "int")
        );
        $rst = $mysqli->query($updateSQL);
        if ($mysqli->error) {
            
            AddSessionMessage("error","Variante  <code> VARID " . $_REQUEST['varname_'.$variant] . "</code> konnte nicht durchgeführt werden.","Fehlermeldung");
        }else{
            $txt = "Entscheidung mit Variante <code>  " . $_REQUEST['varname_'.$variant] . " </code> wurde gespeichert.";
            AddSessionMessage("info", $txt, "Entscheidung gespeichert");
            $w = new FisWorkflow($txt, "fmid", $this->fmid);
            $w->Add();
        }

    }

// material compoents
    function DeleteComponentMaterial($id_ruck){
        include ("./connection.php");
        $updateSQL = "DELETE FROM komponenten WHERE komponenten.id_fm='".$this->fmid."' AND komponenten.id_ruck='".$id_ruck."'";


        $rst = $mysqli->query($updateSQL);
        if ($mysqli->error) {
            AddSessionMessage("error","Material wurde nicht gelöscht, da ein Fehler aufgetreten ist.","Fehlermeldung");
        }else{
            AddSessionMessage("info"," Material wurde gelöscht.","Komponente gelöscht");

        }
    }
    function AddComponentMaterial($material){
        //vollständige Textfelder	id_ruck	kompnr	anzahl	lagerort	lokz	id_fm	unit
        include("./connection.php");

        $lagerort = GetArtikelName("lagerort", $material);

        if (strlen($lagerort) == 0) {
            $lagerort = "P602";
        }
        $updateSQL = sprintf("INSERT INTO komponenten (kompnr, anzahl, lagerort, id_fm, unit) 
                                            VALUES (%s, %s, %s, %s,%s)",
            GetSQLValueString(utf8_decode($material), "text"),
            GetSQLValueString(utf8_decode(1), "text"),
            GetSQLValueString(utf8_decode($lagerort), "text"),
            GetSQLValueString($this->fmid, "int"),
            GetSQLValueString(utf8_decode("ST"), "text"));

        $rst = $mysqli->query($updateSQL);
        if ($mysqli->error) {
            
            AddSessionMessage("error", "Komponente   <code> Material ID " . $material . "</code> konnte nicht durchgeführt werden.", "Fehlermeldung DB-Error");
        }
    }


// customer
    function TakeAdressbook(){

        if ($_REQUEST['idk']>0) {
            $c=new FisCustomer($_REQUEST['idk']);
            $_REQUEST['rechnung']=utf8_decode($c->data['firma']);
            $_REQUEST['rstrasse']=utf8_decode($c->data['strasse']);
            $_REQUEST['rplz']    =utf8_decode($c->data['plz']);
            $_REQUEST['rort']    =utf8_decode($c->data['ort']);
            $_REQUEST['rland']   =utf8_decode($c->data['land']);
            $_REQUEST['ansprechpartner'] =utf8_decode($c->data['ansprechpartner']);
            $_REQUEST['rtelefon'] =utf8_decode($c->data['telefon']);
            $_REQUEST['faxnummer']=utf8_decode($c->data['faxnummer']);
            $_REQUEST['remail']   =utf8_decode($c->data['email']);
            $_REQUEST['fm2notes']   =utf8_decode($c->data['intern']);

            $txt="Rechnungsaddresse aus Kundendaten  <code> IDK " . $c->idk . " </code> wurde übernommen.";
            AddSessionMessage("success", $txt, "Adressdaten geladen");
            $w=new FisWorkflow($txt,"fmid",$this->fmid);
            $w->Add();
        }

        if ($_REQUEST['same']==1){
            $_REQUEST['idk2']=$_REQUEST['idk'];
        }

        if ($_REQUEST['idk2']>0) {

            $c=new FisCustomer($_REQUEST['idk2']);
            $_REQUEST['lieferung']=utf8_decode($c->data['firma']);
            $_REQUEST['lstrasse']=utf8_decode($c->data['strasse']);
            $_REQUEST['lplz']    =utf8_decode($c->data['plz']);
            $_REQUEST['lort']    =utf8_decode($c->data['ort']);
            $_REQUEST['lland']   =utf8_decode($c->data['land']);
            $_REQUEST['lansprechpartner'] =utf8_decode($c->data['ansprechpartner']);
            $_REQUEST['rtelefon'] =utf8_decode($c->data['telefon']);
            $_REQUEST['lfax']=utf8_decode($c->data['faxnummer']);
            $_REQUEST['lemail']   =utf8_decode($c->data['email']);

            $txt="Warenempfänger aus Kundendaten  <code> IDK " . $c->idk . " </code> wurde übernommen.";
            AddSessionMessage("success", $txt, "Adressdaten geladen");
            $w=new FisWorkflow($txt,"fmid",$this->fmid);
            $w->Add();
        }

        if ($_REQUEST['idk3']>0 && $_REQUEST['nopartner']==0) {
            $c=new FisCustomer($_REQUEST['idk3']);
            $_REQUEST['partner']=utf8_decode($c->data['firma']);
            $_REQUEST['pstrasse']=utf8_decode($c->data['strasse']);
            $_REQUEST['pplz']    =utf8_decode($c->data['plz']);
            $_REQUEST['port']    =utf8_decode($c->data['ort']);
            $_REQUEST['pland']   =utf8_decode($c->data['land']);
            $_REQUEST['pansprechpartner'] =utf8_decode($c->data['ansprechpartner']);
            $_REQUEST['ptelefon'] =utf8_decode($c->data['telefon']);
            $_REQUEST['pfax']=utf8_decode($c->data['faxnummer']);
            $_REQUEST['pemail']   =utf8_decode($c->data['email']);

            $txt="Partneraddresse aus Kundendaten  <code> IDK " . $c->idk . " </code> wurde übernommen.";
            AddSessionMessage("success", $txt, "Adressdaten geladen");
            $w=new FisWorkflow($txt,"fmid",$this->fmid);
            $w->Add();
        }
        if ($_REQUEST['idk3']==0 or $_REQUEST['idk3']=="") {
            $_REQUEST['partner']=utf8_decode("");
            $_REQUEST['pstrasse']=utf8_decode("");
            $_REQUEST['pplz']    =utf8_decode("");
            $_REQUEST['port']    =utf8_decode("");
            $_REQUEST['pland']   =utf8_decode("");
            $_REQUEST['pansprechpartner'] =utf8_decode("");
            $_REQUEST['ptelefon'] =utf8_decode("");
            $_REQUEST['pfax']=utf8_decode("");
            $_REQUEST['pemail']   =utf8_decode("");
        }
    }
    function SaveContactPersons(){

            if (isset($_POST['fm_new_contact'])) {
                $this->TakeAdressbook();
            }

            include("./connection.php");
            $updateSQL = sprintf("
                UPDATE fehlermeldungen SET idk=%s, fm2notes=%s, rechnung=%s, rstrasse=%s, rplz=%s, rort=%s,	rland=%s, ansprechpartner=%s,rtelefon=%s,	
                faxnummer=%s,	remail=%s, debitorennummer=%s
                WHERE fmid='" . $this->fmid . "' ",
                GetSQLValueString(utf8_decode($_REQUEST['idk']), "int"),
                GetSQLValueString(utf8_decode($_REQUEST['fm2notes']), "text"),
                GetSQLValueString(utf8_decode($_REQUEST['rechnung']), "text"),
                GetSQLValueString(utf8_decode($_REQUEST['rstrasse']), "text"),
                GetSQLValueString(utf8_decode($_REQUEST['rplz']), "text"),
                GetSQLValueString(utf8_decode($_REQUEST['rort']), "text"),
                GetSQLValueString(utf8_decode($_REQUEST['rland']), "text"),
                GetSQLValueString(utf8_decode($_REQUEST['ansprechpartner']), "text"),
                GetSQLValueString(utf8_decode($_REQUEST['rtelefon']), "text"),
                GetSQLValueString(utf8_decode($_REQUEST['rfaxnummer']), "text"),
                GetSQLValueString(utf8_decode($_REQUEST['remail']), "text"),
                GetSQLValueString(utf8_decode($_REQUEST['debitorennummer']), "text")
            );

            $rst = $mysqli->query($updateSQL);
            if ($mysqli->error) {
                
                AddSessionMessage("error", "Änderung von Ansprechpartner Rechnung von  <code> FMID " . $this->fmid . "</code> konnte nicht durchgeführt werden.", "Fehlermeldung");

            }


        $updateSQL=sprintf("
                UPDATE fehlermeldungen SET idk2=%s, lieferung=%s, lstrasse=%s, lplz=%s, lort=%s,	lland=%s, lansprechpartner=%s,ltelefon=%s,	lfax=%s,	lemail=%s
                WHERE fmid='".$this->fmid."' ",
            GetSQLValueString(utf8_decode($_REQUEST['idk2']), "int"),
            GetSQLValueString(utf8_decode($_REQUEST['lieferung']), "text"),
            GetSQLValueString(utf8_decode($_REQUEST['lstrasse']), "text"),
            GetSQLValueString(utf8_decode($_REQUEST['lplz']), "text"),
            GetSQLValueString(utf8_decode($_REQUEST['lort']), "text"),
            GetSQLValueString(utf8_decode($_REQUEST['lland']), "text"),
            GetSQLValueString(utf8_decode($_REQUEST['lansprechpartner']), "text"),
            GetSQLValueString(utf8_decode($_REQUEST['ltelefon']), "text"),
            GetSQLValueString(utf8_decode($_REQUEST['lfaxnummer']), "text"),
            GetSQLValueString(utf8_decode($_REQUEST['lemail']), "text")

        );


        $rst = $mysqli->query($updateSQL);
        if ($mysqli->error) {
            
            AddSessionMessage("error","Änderung von Ansprechpartner Warenempfänger von  <code> FMID " . $this->fmid . "</code> konnte nicht durchgeführt werden.","Fehlermeldung");

        }

        $updateSQL=sprintf("
                UPDATE fehlermeldungen SET idk3=%s, partner=%s, pstrasse=%s, pplz=%s, port=%s,	pland=%s, pansprechpartner=%s,ptelefon=%s,	pfax=%s,	pemail=%s
                WHERE fmid='".$this->fmid."' ",
            GetSQLValueString(utf8_decode($_REQUEST['idk3']), "int"),
            GetSQLValueString(utf8_decode($_REQUEST['partner']), "text"),
            GetSQLValueString(utf8_decode($_REQUEST['pstrasse']), "text"),
            GetSQLValueString(utf8_decode($_REQUEST['pplz']), "text"),
            GetSQLValueString(utf8_decode($_REQUEST['port']), "text"),
            GetSQLValueString(utf8_decode($_REQUEST['pland']), "text"),
            GetSQLValueString(utf8_decode($_REQUEST['pansprechpartner']), "text"),
            GetSQLValueString(utf8_decode($_REQUEST['ptelefon']), "text"),
            GetSQLValueString(utf8_decode($_REQUEST['pfaxnummer']), "text"),
            GetSQLValueString(utf8_decode($_REQUEST['pemail']), "text")

        );


        $rst = $mysqli->query($updateSQL);
        if ($mysqli->error) {
            
            AddSessionMessage("error","Änderung von Ansprechpartner Partner von  <code> FMID " . $this->fmid . "</code> konnte nicht durchgeführt werden.","Fehlermeldung");

        }


        $txt="Rechnungs- und Warenempfänger aktualisiert  <code> FHMID " . $this->fmid . " </code> wurde gespeichert.";
        AddSessionMessage("success", $txt, "Fehlermeldung gespeichert");
        $w=new FisWorkflow($txt,"fmid",$this->fmid);
        $w->Add();

    }

//general save
    function SaveFM1(){
        include ("./connection.php");
        $_REQUEST['gewichtvorher']=str_replace(",",".",$_REQUEST['gewichtvorher']);
        $updateSQL=sprintf("
                UPDATE fehlermeldungen SET fmsn=%s, fmartikelid=%s, fmfd=%s, fm11notes=%s,	gewichtvorher=%s, fm12notes=%s, fm1notes=%s, intern=%s,  fm1=%s, fm1datum=%s
                WHERE fmid='".$this->fmid."' ",
            GetSQLValueString(utf8_decode($_REQUEST['fmsn']), "text"),
            GetSQLValueString(utf8_decode($_REQUEST['fmartikelid']), "text"),
            GetSQLValueString(utf8_decode($_REQUEST['fmfd']), "text"),

            GetSQLValueString(utf8_decode($_REQUEST['fm11notes']), "text"),
            GetSQLValueString(utf8_decode($_REQUEST['gewichtvorher']), "text"),
            GetSQLValueString(utf8_decode($_REQUEST['fm12notes']), "text"),
            GetSQLValueString(utf8_decode($_REQUEST['fmnotes']), "text"),
            GetSQLValueString(utf8_decode($_REQUEST['intern']), "text"),

            GetSQLValueString(utf8_decode($_REQUEST['fm1']), "int"),
            GetSQLValueString(date('Y-m-d',time()), "text")
        );
       $rst = $mysqli->query($updateSQL);
        if ($mysqli->error) {

            AddSessionMessage("error","Änderung von Fehleraufnahme von  <code> SaveFM1 FMID " . $this->fmid . "</code> konnte nicht durchgeführt werden.","Fehlermeldung");
        }
       $txt="Fehleraufnahme und Fehlerbeschreibung <code> FHMID " . $this->fmid . " </code> Fehleraufnahme wurde gespeichert.";
        AddSessionMessage("success", $txt, "Fehlermeldung gespeichert");
        $w=new FisWorkflow($txt,"fmid",$this->fmid);
        $w->Add();

    }
    function SaveFM2(){
        // save positions
        $this->LoadSelectedVariants();
        if (count($this->slectedvariants)>0){
            foreach($this->slectedvariants as $item) {
                if (isset($_REQUEST['del_'.$item['varid']])){
                    $this->DeleteVariant($item['varid']);
                }else{
                    $this->SaveVariant($item['varid']);
                }

            }
        }

    // add position from Modal
        if (isset($_REQUEST['fm_add_varianten'])) {

            if (count($_POST['my_multi_selectProzess'])) {

                foreach ($_POST['my_multi_selectProzess'] as $item) {
                    $this->AddVariant($item);
                    $r=$this->LoadVariantProperty($item);

                    $_REQUEST['gwl']=       $r['gvwl'];  //field list: gvwl !!!
                    $_REQUEST['vkz']=       $r['vkz'];
                    $_REQUEST['fm2variante']=$r['entid'];

                }
                $txt="Prozess- Varianten kpiert und gespeichert.";
                if ( $_REQUEST['gwl']) { $txt.=" Gewährleistung ausgewäht.";} else {$txt.=" Kostenpflichtige Reparatur.";}
                if ( $_REQUEST['vkz']) { $txt.=" Verschrottung ausgewäht.";}

                AddSessionMessage("success", $txt, "Prozessentscheidung ergänzt");
                $w = new FisWorkflow($txt, "fmid", $this->fmid);
                $w->Add();
            }
        }

        // rename all positons
        if (isset($_REQUEST['fm_new_varianten'])) {

            if (count($_POST['my_multi_selectProzess'])) {
                $this->DeleteVariants();
                foreach ($_POST['my_multi_selectProzess'] as $item) {
                    $this->AddVariant($item);
                    $r=$this->LoadVariantProperty($item);

                    $_REQUEST['gwl']=       $r['gvwl'];
                    $_REQUEST['vkz']=       $r['vkz'];
                    $_REQUEST['fm2variante']=$r['entid'];

                }
                $txt="Prozess- Varianten kpiert und gespeichert.";
                if ( $_REQUEST['gwl']) { $txt.=" Gewährleistung ausgewäht.";} else {$txt.=" Kostenpflichtige Reparatur.";}
                if ( $_REQUEST['vkz']) { $txt.=" Verschrottung ausgewäht.";}

                AddSessionMessage("success", $txt, "Prozessentscheidung durchgeführt");
                $w = new FisWorkflow($txt, "fmid", $this->fmid);
                $w->Add();
            }
        }



        // Save fmid head
        if ($_REQUEST['fm2variante']==0 or $_REQUEST['fm2variante']==""){
            $_REQUEST['fm2variante']=$this->data['fm2variante'];
        }
        if ($_REQUEST['fm2variante']>0){
        include ("./connection.php");
        $updateSQL=sprintf("
                UPDATE fehlermeldungen SET fm2variante=%s, gwl=%s, vkz=%s,kommission=%s, fm2notes=%s,  anrede=%s, fm2=%s, fm2datum=%s
                WHERE fmid='".$this->fmid."' ",
            GetSQLValueString(utf8_decode($_REQUEST['fm2variante']), "int"),
            GetSQLValueString(utf8_decode($_REQUEST['gwl']), "int"),
            GetSQLValueString(utf8_decode($_REQUEST['vkz']), "int"),
            GetSQLValueString(utf8_decode($_REQUEST['kommission']), "text"),
            GetSQLValueString(utf8_decode($_REQUEST['fm2notes']), "text"),
            GetSQLValueString(utf8_decode($_REQUEST['anrede']), "text"),
            GetSQLValueString(utf8_decode($_REQUEST['fm2']), "int"),
            GetSQLValueString(date('Y-m-d',time()), "text")
        );

        $rst = $mysqli->query($updateSQL);
        if ($mysqli->error) {
            
            AddSessionMessage("error","Änderung von Kostenklärung von  <code> FMID " . $this->fmid . "</code> konnte nicht durchgeführt werden.","Fehlermeldung");
        }
        $txt="Kostenklärung  <code> FHMID " . $this->fmid . " </code> Kostenklärung wurde gespeichert.";
        AddSessionMessage("success", $txt, "Fehlermeldung gespeichert");
        $w=new FisWorkflow($txt,"fmid",$this->fmid);
        $w->Add();
        }
    }
    function SaveFM3(){
        include ("./connection.php");

        // save positions
        $this->LoadSelectedVariants();
        if (count($this->slectedvariants)>0){
            foreach($this->slectedvariants as $item) {
                if (isset($_REQUEST['checked_variant_'.$item['varid']])){
                    $this->SaveVariantDecission($item['varid']);
                }
            }
        }

        $updateSQL = sprintf("
            UPDATE fehlermeldungen SET sapsd=%s, sappcna=%s, vip=%s, upgrade=%s, exchange=%s,fm3=%s,fm3datum=%s
            WHERE fmid='" . $this->fmid . "' ",
            GetSQLValueString(utf8_decode($_REQUEST['sapsd']), "text"),
            GetSQLValueString(utf8_decode($_REQUEST['sappcna']), "text"),
            GetSQLValueString(utf8_decode($_REQUEST['vip']), "int"),
            GetSQLValueString(utf8_decode($_REQUEST['upgrade']), "int"),
            GetSQLValueString(utf8_decode($_REQUEST['exchange']), "int"),
            GetSQLValueString(utf8_decode($_REQUEST['fm3']), "int"),
            GetSQLValueString(date('Y-m-d',time()), "text")
        );

        $rst = $mysqli->query($updateSQL);
        if ($mysqli->error) {
            
            AddSessionMessage("error", "Änderung von Reparaturfreigabe von  <code> FMID " . $this->fmid . "</code> konnte nicht durchgeführt werden.", "Fehlermeldung");
        }
        $txt = "Freigabe  <code> FHMID " . $this->fmid . " </code> Reparaturfreigabe wurde gespeichert.";
        AddSessionMessage("success", $txt, "Fehlermeldung gespeichert");
        $w = new FisWorkflow($txt, "fmid", $this->fmid);
        $w->Add();

    }
    function SaveFM4(){
        include ("./connection.php");

        $this->LoadConfirmMaterial();
        if (count($this->materials)>0){
            foreach ($this->materials as $i=>$material){
                if (isset($_REQUEST['del_material_'.$material['id_ruck']])){
                    $this->DeleteComponentMaterial($material['id_ruck']);
                }
            }
        }
        if (strlen($_REQUEST['fm4_material'])>0) {
            $r=explode(chr(13),$_REQUEST['fm4_material']);
            foreach ($r as $material) {
                if (intval($material*1)>0){
                    $this->AddComponentMaterial(intval($material*1) );
                }
            }
        }

        if (strlen($_REQUEST['fm4notes'])>0) {
            $updateSQL = sprintf("
                UPDATE fehlermeldungen SET fmsn2=%s, fm4notes=%s, fm4=%s, fm4datum=%s
                WHERE fmid='" . $this->fmid . "' ",
                GetSQLValueString(utf8_decode($_REQUEST['fmsn2']), "text"),

                GetSQLValueString(utf8_decode($_REQUEST['fm4notes']), "text"),
                GetSQLValueString(utf8_decode($_REQUEST['fm4']), "int"),
                GetSQLValueString(date('Y-m-d',time()), "text")
            );
            $rst = $mysqli->query($updateSQL);
            if ($mysqli->error) {
                
                AddSessionMessage("error", "Änderung von Reparatur von  <code> FMID " . $this->fmid . "</code> konnte nicht durchgeführt werden.", "Fehlermeldung");
            }else{
                $txt = "Reparaturrückmeldung für  <code> FHMID " . $this->fmid . " </code> wurde gespeichert.";
                AddSessionMessage("success", $txt, "Fehlermeldung gespeichert");
                $w = new FisWorkflow($txt, "fmid", $this->fmid);
                $w->Add();
            }
        }

    }
    function SaveFM5(){
        include ("./connection.php");
        if (strlen($_REQUEST['glsversand'])>0) {
            $updateSQL = sprintf("
                UPDATE fehlermeldungen SET fm1text=%s,glsversand=%s, fm5notes=%s, fm5=%s, fm5datum=%s
                WHERE fmid='" . $this->fmid . "' ",
                GetSQLValueString(utf8_decode($_REQUEST['fm1text']), "text"),
                GetSQLValueString(utf8_decode($_REQUEST['glsversand']), "text"),
                GetSQLValueString(utf8_decode($_REQUEST['fm5notes']), "text"),
                GetSQLValueString(utf8_decode($_REQUEST['fm5']), "int"),
                GetSQLValueString(date('Y-m-d',time()), "text")
            );

            $rst = $mysqli->query($updateSQL);
            if ($mysqli->error) {
                
                AddSessionMessage("error", "Änderung Versand von  <code> FMID " . $this->fmid . "</code> konnte nicht durchgeführt werden.", "Fehlermeldung");
            }else {
                $txt = "Kommissionierung  <code> FMID " . $this->fmid . " </code> und Versand wurde gespeichert.";
                AddSessionMessage("success", $txt, "Fehlermeldung gespeichert");
                $w = new FisWorkflow($txt, "fmid", $this->fmid);
                $w->Add();
            }
        }

    }
    function SaveFM6($state=1){
        include ("./connection.php");
         {
             if ($state==1) {
                 $updateSQL = sprintf("
                UPDATE fehlermeldungen SET fm6=%s, fm6datum=%s
                WHERE fmid='" . $this->fmid . "' ",

                     GetSQLValueString(1, "text"),
                     GetSQLValueString(date('Y-m-d', time()), "text")
                 );
             }else {
                 $updateSQL = sprintf("
                UPDATE fehlermeldungen SET fm6=%s, fm6datum=%s
                WHERE fmid='" . $this->fmid . "' ",

                     GetSQLValueString(0, "text"),
                     GetSQLValueString(date('Y-m-d', time()), "text")
                 );
             }
            $rst = $mysqli->query($updateSQL);
            if ($mysqli->error) {
                
                AddSessionMessage("error", "Abrechnung von  <code> FMID " . $this->fmid . "</code> konnte nicht durchgeführt werden.", "Fehlermeldung");
            }
            $txt = "Faktura  <code> FMID " . $this->fmid . " </code> mit  <code> STATE " . $state . " </code>  wurde gespeichert.";
            AddSessionMessage("success", $txt, "Faktura gespeichert");
            $w = new FisWorkflow($txt, "fmid", $this->fmid);
            $w->Add();
        }
    }

    function SaveFM7($state=1,$order=0){
        include ("./connection.php");
        {
            if ($state>0) {
                $updateSQL = sprintf("
                UPDATE fehlermeldungen SET fm7=%s, fm7datum=%s, sappcna=%s
                WHERE fmid='" . $this->fmid . "' ",
                    GetSQLValueString($state, "int"),
                    GetSQLValueString(date('Y-m-d', time()), "text"),
                    GetSQLValueString($order, "int")
                );
                $txt = "Rückmeldung  <code> FMID " . $this->fmid . " </code> mit  <code> STATE " . $state . " </code>  wurde gespeichert.";

            }else {
                $updateSQL = sprintf("
                UPDATE fehlermeldungen SET fm7=%s, fm7datum=%s
                WHERE fmid='" . $this->fmid . "' ",
                    GetSQLValueString(0, "int"),
                    GetSQLValueString(date('Y-m-d', time()), "text")
                );
                $txt = "Rückmeldung  storniert <code> FMID " . $this->fmid . " </code> mit  <code> STATE " . $state . " </code> ";
            }
            $rst = $mysqli->query($updateSQL);
            if ($mysqli->error) {

                AddSessionMessage("error", "Rückmeldung von  <code> FMID " . $this->fmid . "</code> konnte nicht durchgeführt werden.", "Fehlermeldung");
            }else {
                AddSessionMessage("success", $txt, "Abrechnung gespeichert");
                $w = new FisWorkflow($txt, "fmid", $this->fmid);
                $w->Add();
            }
        }
    }

    function UpdateSN2($sn,$artikelid){
        include ("./connection.php");
        {
            if ($sn>0 && $artikelid>0) {
                $updateSQL = sprintf("
                UPDATE fehlermeldungen SET fmsn2=%s, fmartikelid2=%s
                WHERE fmid='" . $this->fmid . "' ",
                    GetSQLValueString($sn, "int"),
                    GetSQLValueString($artikelid, "int")

                );
                $txt = "Austausch mit  <code> SN " . $sn . " </code> wurde durchgeführt.";

            }
            $rst = $mysqli->query($updateSQL);
            if ($mysqli->error) {

                AddSessionMessage("error", "Rückmeldung von  <code> FMID " . $this->fmid . "</code> konnte nicht durchgeführt werden. Softwareupdate erforderlich: fmartikelid2", "Fehlermeldung");
            }else {
                AddSessionMessage("success", $txt, "Austausch gespeichert");
                $w = new FisWorkflow($txt, "fmid", $this->fmid);
                $w->Add();
            }
        }
    }

    function Save (){

        if (!isset($_REQUEST['fm1'])){$_REQUEST['fm1']=0;}else{$_REQUEST['fm1']=1;}
        if (!isset($_REQUEST['fm2'])){$_REQUEST['fm2']=0;}else{$_REQUEST['fm2']=1;}
        if (!isset($_REQUEST['fm3'])){$_REQUEST['fm3']=0;}else{$_REQUEST['fm3']=1;}
        if (!isset($_REQUEST['fm4'])){$_REQUEST['fm4']=0;}else{$_REQUEST['fm4']=1;}
        if (!isset($_REQUEST['fm5'])){$_REQUEST['fm5']=0;}else{$_REQUEST['fm5']=1;}
        if (!isset($_REQUEST['fm6'])){$_REQUEST['fm6']=0;}else{$_REQUEST['fm6']=1;}
        if (!isset($_REQUEST['fm7'])){$_REQUEST['fm7']=0;}


        if ($this->data['fm2']==0 or $_REQUEST['fm2']==0) $this->SaveContactPersons();

        if ($this->data['fm1']==0 or $_REQUEST['fm1']==0) {
            $this->SaveErrors();
            $this->SaveFM1();
        }else {

            if ($this->data['fm2'] == 0 or $_REQUEST['fm2'] == 0) $this->SaveFM2();

            if ($this->data['fm3'] == 0 or $_REQUEST['fm3'] == 0) $this->SaveFM3();

            if ($this->data['fm4'] == 0 or $_REQUEST['fm4'] == 0) {

                $this->SaveFM4();

                if ($this->data['vkz'] == 1) {
                    $_REQUEST['fm5notes'] = "Verschrottung";
                    $_REQUEST['fm5'] = 1;
                    $this->SaveFM5();
                }


            }else{
                if ($this->data['fm5'] == 0 or $_REQUEST['fm5'] == 0) {
                    $this->SaveFM5();

                    if ($this->data['vkz'] == 1) {
                        $_REQUEST['fm5notes'] = "Verschrottung";
                        $_REQUEST['fm5'] = 1;
                        $this->SaveFM5();
                    }

                }


                if (($this->data['fm5'] == 1 or $_REQUEST['fm5'] == 1) or
                    ($this->data['fm6'] == 0 or $_REQUEST['fm6'] == 0)) {
                    $this->SaveFM6(0);
                }


                if ($this->data['fm5'] == 1 or $_REQUEST['fm5'] == 1) {

                    $this->SaveFM6(1); //set automatic by BLANCO todo savemode customzing in wpconfig!

                    if ($this->data['fm7'] == 0 or $_REQUEST['fm7'] >= 0) {

                        $this->SaveFM7($_REQUEST['fm7'], $_REQUEST['sappcnax']);
                    }

                }
            }//fm4
        }//fm1

        $d=new FisDocument("Dokumente zur Fehlermeldung","fmid/".$this->fmid,$this->fmid);
        $d->AddNewDocument();

        return $this;
    }

    function SetConfirmState($item,$gwl)
    {
       if ($item>=0){

           $fms="";
            include("./connection.php");


            if ( $gwl ) {
                $s = " AND artikeldaten.pcnagwl='$item' AND fehlermeldungen.gwl=1";
            }else {
                $s = " AND artikeldaten.pcna='$item' AND fehlermeldungen.gwl=0";
            }
            $sql = "SELECT fehlermeldungen.fmid FROM fehlermeldungen,artikeldaten 
                    WHERE artikeldaten.artikelid=fehlermeldungen.fmartikelid 
                    $s
                    AND fehlermeldungen.fm5=1 
                    AND fehlermeldungen.fm6=1 
                   
                    AND fehlermeldungen.fm7=0 
                    LIMIT 0,1000";

            $rst = $mysqli->query($sql);
            if ($mysqli->error) {
                AddSessionMessage("error", "Rückmeldung  <code> " . $this->search . "</code> konnten nicht abgefragt werden.", "Fehlermeldung");
            }
            if (mysqli_affected_rows($mysqli) > 0) {
                $row_rst = $rst->fetch_assoc();
                do {
                    $fms.="FMID".$row_rst['fmid']." ";
                    $this->fmid = $row_rst['fmid'];
                    $this->SaveFM6(1);
                    $this->SaveFM7(3,$item);
                    $fmids[]=$row_rst['fmid'];

                } while ($row_rst = $rst->fetch_assoc());
                AddSessionMessage("info", mysqli_affected_rows($mysqli) . " Fehlermeldungen abgerechnet <code> ORDER: $order </code><br>".$fms, "Abrechnung fertiggestellt");
                $this->SaveConfirm($item, $fmids);
            }else{
                AddSessionMessage("warning", mysqli_affected_rows($mysqli) . " keine Fehlermeldungen abgerechnet <code> ORDER: $order </code>", "Warnung bei Abrechnung");

            }
            $mysqli->close();

        }else{
            AddSessionMessage("error", "Kein Auftrag zur Abrechnung an Funktion SetConfirmState(item)= $item übergeben.", "Fehlermeldung");
        }
    }


    function SaveConfirm($order, $fmids){
        include ("./connection.php");
        {
            if ($order>0) {
                $updateSQL = sprintf("
                INSERT INTO fisorderconfirm (datum, `order`, fmids)
                 VALUES(%s,%s,%s)",
                    GetSQLValueString(time(), "int"),
                    GetSQLValueString($order, "int"),
                    GetSQLValueString(json_encode($fmids), "text")

                );
                $txt = "Rückmeldung mit Auftrag <code> " . $order . " </code> wurde durchgeführt.";

            }
            $rst = $mysqli->query($updateSQL);
            if ($mysqli->error) {

                AddSessionMessage("error", "Datensicherung von  <code> FMID " . $this->fmid . "</code> konnte nicht durchgeführt werden. Softwareupdate erforderlich: Tabelle fisorderconfirm fehlt.", "Fehlermeldung");
            }else {
                AddSessionMessage("success", $txt, "Datensicherung der Rückmeldung gespeichert");
                $w = new FisWorkflow($txt, "fmid", $this->fmid);
                $w->Add();
            }
        }

    }

    function ResetOrderConfirm($fmoc){
        include ("./connection.php");
        if ($fmoc>0){

            $sql = "SELECT * FROM fisorderconfirm WHERE fisorderconfirm.fmoc='$fmoc' ";

            $rst = $mysqli->query($sql);
            if ($mysqli->error) {
                AddSessionMessage("error", "Datensicherung  <code> " . $fmoc . "</code> konnten nicht abgefragt werden.", "Fehlermeldung");
            }
            if (mysqli_affected_rows($mysqli) > 0) {
                $row_rst = $rst->fetch_assoc();

                $txt="";
                $fmids=json_decode($row_rst['fmids'],true);
                if (count($fmids)>0){
                    foreach ($fmids as $item){
                        $this->fmid=$item;
                        $this->SaveFM7(0,$row_rst['order']);
                        $txt.="FMID ".$item;
                    }
                    $sql = "Update fisorderconfirm SET fisorderconfirm.lokz='1' WHERE fisorderconfirm.fmoc='" . $fmoc . "'";
                    $rst = $mysqli->query($sql);
                }

                AddSessionMessage("info", mysqli_affected_rows($mysqli) . " Fehlermeldungensabrechnung für $txt storniert <code> Rückmeldung: $fmoc </code> storniert.");

            }else{
                AddSessionMessage("warning", mysqli_affected_rows($mysqli) . " Datensätze übergeben. Keine Fehlermeldungen storniert <code> Datensicherung: $fmoc </code>", "Warnung bei Abrechnung");

            }
            $mysqli->close();

        }
    }


    function SetSearchString($s=""){
        $this->search=$s;
        return $this;
    }

    function SetDatum($d="2018-01-01"){
        $this->datum=$d;
        return $this;
    }
    function SetStatus($s=0){
        $this->fmstate=$s;
        return $this;
    }
    function SetNoFreeValueAllowed($s=0){
        $this->no_free_search_value_allowed=$s;
        return $this;
    }

    function SetLimit($l=100){
        if  (intval($l) <501){
            $this->limit=intval($l);
            if ($this->limit <10){
                $this->limit=100;
            }
        }else{
            $this->limit=500;
        }
        return $this;
    }
    function GetLimit(){
        return $this->limit;
    }

    function GetFMSN2(){

        if ($this->data['fmsn']==$this->data['fmsn2']){
            return "";
        }else{
            if ($this->data['fmsn2']==0){
                return "";
            }else{
                return "<label class='label label-danger'><i class='fa fa-arrow-right'> ".$this->data['fmsn2']." </label>";
            }
        }
    }

    function GetArtikelid2(){

        if ($this->data['fmartikelid']==$this->data['fmartikelid2']){
            return "";
        }else{
            if ($this->data['fmartikelid2']==0){
                return "";
            }else{
                return "<label class='label label-danger'><i class='fa fa-arrow-right'> ".GetArtikelName("text",$this->data['fmartikelid2'])." </label>";
            }
        }
    }

    function ShowFMStatus($state=array(0,0,0,0,0,0,0,0),$sonder=array(0,0,0,0,0)){
                                      //fm1 2 3 4 5 6 7 8            gwl 96, exchange 97, upgrade 98,  vkz 99, $vip
        $r=GetFMState("data","");
        $s="";

        for ($i=1; $i<8; $i++){
            $a=$i;

            if (($i==3) && $sonder[0]==1){ $a=96;} //gwl

            if (($i==4) && $sonder[1]==1){ $a=97;} //exchange
            if (($i==4) && $sonder[2]==1){ $a=98;} //upgrade

            if (($i==4) && $sonder[3]==1){ $a=99;} //vkz
            if (($i==5) && $sonder[3]==1){ $a=99;} //vkz

            if ($state[$i - 1] >= 1) {
                $color = "label label-" . $r[$a]['color'];
            } else {
                $color = "label label-" . $r[$a]['inactive'];
            }
            $s .= "<span class='$color'> <i class='" . $r[$a]['icon'] . "'> </i> </span> ";

        }
        return $s;
    }
    function ShowVIPStatus($state=array(0,0,0,0,0,0,0,0),$sonder=array(0,0,0,0,0)){
        //fm1 2 3 4 5 6 7 8            gwl 96, exchange 97, upgrade 98,  vkz 99, $vip

        $s="";

        if ($sonder[4] == 1) {
            $s = "<span class='label label-warning'><i class='zmdi zmdi-notifications-active'> </i> </span>";
        }
        if ($sonder[4] == 2) {
            $s = "<span class='label label-danger'><i class='zmdi zmdi-notifications-active'> </i> <i class='zmdi zmdi-notifications-active'> </i> </span>";
        }

        return $s;
    }

    function ShowGWLStatus($state = array(0, 0, 0, 0, 0, 0, 0, 0), $sonder = array(0, 0, 0, 0, 0))
    {
        //fm1 2 3 4 5 6 7 8            gwl 96, exchange 97, upgrade 98,  vkz 99, $vip

        $s = "";

        if ($sonder[3] == 1) {
            $s .= "<span class='label label-primary'><i class='fa fa-trash-o' aria-label='Verschrotten'> </i></span><br>";
        }

        if ($sonder[1] == 1) {
            $s .= "<span class='label label-primary'><i class='zmdi zmdi-refresh-sync'> </i></span><br>";
        }
        if ($sonder[2] == 1) {
            $s .= "<span class='label label-primary'><i class='zmdi zmdi-upload'> </i></span><br>";
        }

        if ($sonder[0] == 1) {
            $s .= "<span class='label label-primary'><i class='zmdi zmdi-money-box'> </i> </span>";
        }

        return $s;
    }

    function GetInternMessage(){
        $h="<b>interne Hinweise: </b><div class='alert-info'>".($this->data['intern'])."</div>";
        return $h;
    }

    function ShowHtmlAllReturn($status=0,$limit=100,$search="",$no_free_value_allowed=0){
        $this->FMdata=array();
        $this->SetSearchString($search);
        $this->SetLimit($limit);
        $this->SetStatus($status);
        $this->SetNoFreeValueAllowed($no_free_value_allowed);
        $this->LoadFMData();

        $h = "<h4 class=\"header-title m-t-0 m-b-30\">Fehlermeldung Suchbegriff:(".$this->search.") </h4>";

        if (count($this->FMdata) > 0) {

            $_SESSION['datatable']++;
            $_SESSION['script_datatable'].="
            
            var table  = $(\"#datatable".$_SESSION['datatable']."\").DataTable({
                            
                            " . $this->order . "
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
                            lengthMenu: [[-1,10,20, 25, 50, -1], [\"Alle\",10,20, 25, 50, \"Alle\"]]
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

            $h.=" <table id=\"datatable".$_SESSION['datatable']."\" class=\"table table-striped dt-responsive nowrap\">
                    <thead>
                        <tr>
                             <th>FMID</th>
                             <th>SN</th>
                           
                             <th>Material</th>
                             <th>Datum Eingang</th>
                             <th>FD</th>";
            if ($this->fmstate == 4) {
                $h .= "<th>Freigabe</th>";

            } else {
                $h .= "<th>Maßnahmen</th>";
            }
            $h .= "<th>VIP</th>
                             <th>GWL</th>
                             <th> Reparaturstatusfortschrittsverlauf </th>
                             <th>Sendungs-Nr.</th>
                              <th>Lieferung an</th>
                             <th>Rechnung an</th>
                           
                             <th>Partner</th>
                             
                              <th>Zusatz</th>
                             <th>Debitoren-Nr.</th>
                            
                             <th>SAP Auftrag</th>
                             <th>SAP Lieferung</th>
                             <th>Sendungsverfolgung </th>
                             <th>SAP Abrechnung</th>
                              <th>FM Status</th>
                               <th>Debitorennummer</th>
                              <th>Aktion</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                                       ";
            foreach ($this->FMdata as $d => $i) {
                $r=array($i['fm1'],$i['fm2'],$i['fm3'],$i['fm4'],$i['fm5'],$i['fm6'],$i['fm7']);
                $sonder=array($i['gwl'],$i['exchange'],$i['upgrade'],$i['vkz'],$i['vip']);

                $link = "index.php?sess=" . $_SESSION['sess'] . "&wp=" . $_SESSION['wp'] . "&action=25&fmid=" . $i['fmid'] . "&fmstate=" . $this->fmstate . "&materialgroup=&material=&version=&fid=";
                $btn = "<a class='btn btn-custom btn-sm' href=\"$link\" onclick='refresh_site()'> Bearbeiten </a>";

                $h .= "<tr>
                        <td><a href='$link' onclick='refresh_site()'>" . $i['fmid'] . "</a></td>";
                if ($i['fmsn']==$i['fmsn2'] or $i['fmsn2']==0){
                    $h.="<td>" . $i['fmsn']."</td>";
                } else {
                    $h .= "<td>" . $i['fmsn'] . "<br><label class='label label-danger'>" . $i['fmsn2'] . "</label></td>";
                }

                if ($i['fmartikelid']==$i['fmartikelid2'] or $i['fmartikelid2']==0){
                    $h.="<td>" . GetArtikelName("text",$i['fmartikelid'])."</td>";
                } else {
                    $h .= "<td>" . GetArtikelName("text",$i['fmartikelid']). "<br><label class='label label-danger'>" . GetArtikelName("text",$i['fmartikelid2'] ). "</label></td>";
                }
                $h.="<td>" . substr($i['fm1datum'],0,10) . "</td>
                        <td>" . $i['fmfd'] . "</td>
                        ";

                if ($this->fmstate == 4) {
                    $h .= "<td>" . $this->ShowHtmlVarianteRepair(false, $i['fmid']) . "</td>";
                } else {
                    $h .= "<td>" . ($i['fmnotes']) . "</td>";
                }

                $h .= "<td>" . $this->ShowVIPStatus($r, $sonder) . "</td>
                         <td>" . $this->ShowGWLStatus($r, $sonder) . "</td>
                        <td>" . $this->ShowFMStatus($r,$sonder) . "</td>
                        <td>" .($i['fm11notes']) . "</td>
                        <td>" .($i['lieferung'])   ."</td>
                        <td>" .($i['rechnung'])   ."</td>
                       
                        <td>" .($i['partner'])  ."</td>
                        
                         <td>" .($i['fm12notes']) . "</td>
                        <td>" .($i['debitorennummer']) . "</td>
                       
                        <td>" .($i['sapsd'])  ."</td>
                        <td>" .($i['fm5notes'])  ."</td>
                        <td>" .($i['glsversand'])  ."</td>
                        <td>" .($i['sappcna'])  ."</td>
                        <td>" .($this->fmstate)  ."</td>
                         <td>" . ($i['debitorennummer']) . "</td>
                         <td>" . $btn . "</td>
                       ";


                $h.="</tr>";
                unset($c);
            }
            $h.="</tbody></table>";
        }else {
            $h .= "keine Fehlermeldungen gefunden.";
        }
        return $h;
    }

    function ShowFMList(){

        if (count($this->FMdata)>0 ) {
            $html= "<table class=\"table table-striped table-bordered table-hover\"  id=\"FMList\" cellspacing=\"0\" width=\"100%\">"
                . "<tbody>";
            foreach ($this->FMdata as  $i=>$item) {
                $r=array($item['fm1'],$item['fm2'],$item['fm3'],$item['fm4'],$item['fm5'],$item['fm6'],$item['fm7']);
                $sonder=array($item['gwl'],$item['exchange'],$item['upgrade'],$item['vkz'],$item['vip']);

                $html.= "<tr>"
                    . "<td><a href=\"index.php?sess=".$_SESSION['sess']."&wp=".$_SESSION['wp']."&action=25&fmid=".$item['fmid']."\" >
                    FMID " . $item['fmid']  . "</a></td>"
                    . "<td>SN " . $item['fmsn']  . "</td>"
                    . "<td>" . $this->ShowVIPStatus($r, $sonder) . "</td>"
                    . "<td>" . $this->ShowFMStatus($r, $sonder) . "</td>"
                    . "<td>" . $item['rechnung'] . "</td>";
                $html.= "</tr>";
            }
            $html.= "</tbody>"
                . "</table>";
        }else{
            $html="<span class=\"label label-danger\">Sendung nicht eingetroffen</span>";
        }
        return $html;
    }

    function ShowStatusAsTable(){
        $r = array(
            $this->data['fm1'],
            $this->data['fm2'],
            $this->data['fm3'],
            $this->data['fm4'],
            $this->data['fm5'],
            $this->data['fm6'],
            $this->data['fm7']);
        $sonder = array($this->data['gwl'], $this->data['echange'], $this->data['upgrade'], $this->data['vkz'], $this->data['vip']);

        $h=" <div class=\"table-responsive\">
            <table class=\"table table-striped\">
              <tbody><tr>
              <td><b>FMID: ".$this->data['fmid']."</b></td>
              <td><b>SN: ".$this->data['fmsn']." ".$this->GetFMSN2()."</b></td>
              <td><b>FD: ".$this->data['fmfd']."</b></td>
              <td><b>Eingang: ".substr($this->data['fm1datum'],0,10)."</b></td>
              <td><b> ".GetArtikelName("text",$this->data['fmartikelid'])."</b>".$this->GetArtikelid2()."</td>
              <td>" . $this->ShowVIPStatus($r, $sonder) . "</td>
              <td>" . $this->ShowGWLStatus($r, $sonder) . "</td>
              <td>" . $this->ShowFMStatus($r, $sonder) . "</td>
            
              </tr><tr><td colspan='9'>
              " . $this->data['fm1notes'] . " <i class='fa fa-arrow-right'> </i>  " . $this->data['partner'] . " <i class='fa fa-arrow-right'> </i>  " . $this->data['lieferung'] . "  <i class='fa fa-arrow-right'> </i> " . $this->data['rechnung'] . "
              </td></tr></tbody></table></div>";
        return $h;
    }

    function EditPropertyAsTable(){
        $h="";
        if ($this->fmid==0) {



        }
        if ($this->fmid>0) {

            $h .= "<h4 class=\"m-b-30 m-t-0 header-title\"><b>Fehlermeldung bearbeiten </b></h4>";
            $h .= $this->ShowStatusAsTable();

            $h .= "<div class=\"table-responsive\">
                <table class=\"table \">
                  <tbody><tr><td>";

            $a1 = "";
            $a2 = "";
            $a3 = "";
            $a4 = "";
            $a5 = "";
            $a6 = "";
            $a7 = "";

            if ($this->data['fm0'] == 1) {
                $c1 = "<span class=\"text-success\"><i class=\"fa fa-check\"> </i> </span>";
            }
            if ($this->data['fm1'] == 1) {
                $c2 = "<span class=\"text-success\"><i class=\"fa fa-check\"> </i> </span>";
            }
            if ($this->data['fm2'] == 1) {
                $c3 = "<span class=\"text-success\"><i class=\"fa fa-check\"> </i> </span>";
            }
            if ($this->data['fm3'] == 1) {
                $c4 = "<span class=\"text-success\"><i class=\"fa fa-check\"> </i> </span>";
            }
            if ($this->data['fm4'] == 1) {
                $c5 = "<span class=\"text-success\"><i class=\"fa fa-check\"> </i> </span>";
            }
            if ($this->data['fm5'] == 1) {
                $c6 = "<span class=\"text-success\"><i class=\"fa fa-check\"> </i> </span>";
            }
            if ($this->data['fm7'] == 1) {
                $c7 = "<span class=\"text-success\"><i class=\"fa fa-check\"> </i> </span>";
            }
            if ($this->data['fm6'] == 1) {
                $c7 = "<span class=\"text-success\"><i class=\"fa fa-check\"> </i> </span>";
            }


            if ($this->data['fm1'] == 0) {
                $a2 = "in active";
                $b2 = "class=\"active\"";
                $c2 = "<span class=\"text-danger\"><i class=\"fa fa-edit\"> </i> </span>";
            } else {
                if ($this->data['fm2'] == 0) {
                    $a3 = "in active";
                    $b3 = "class=\"active\"";
                    $c3 = "<span class=\"text-danger\"><i class=\"fa fa-edit\"> </i> </span>";
                } else {
                    if ($this->data['fm3'] == 0) {
                        $a4 = "in active";
                        $b4 = "class=\"active\"";
                        $c4 = "<span class=\"text-danger\"><i class=\"fa fa-edit\"> </i> </span>";
                    } else {
                        if ($this->data['fm4'] == 0) {
                            $a5 = "in active";
                            $b5 = "class=\"active\"";
                            $c5 = "<span class=\"text-danger\"><i class=\"fa fa-edit\"> </i> </span>";
                        } else {
                            if ($this->data['fm5'] == 0) {
                                $a6 = "in active";
                                $b6 = "class=\"active\"";
                                $c6 = "<span class=\"text-danger\"><i class=\"fa fa-edit\"> </i> </span>";
                            } else {
                                if ($this->data['fm6'] == 0) {
                                    $a7 = "in active";
                                    $b7 = "class=\"active\"";
                                    $c7 = "<span class=\"text-danger\"><i class=\"fa fa-edit\"> </i> </span>";
                                } else {
                                    if ($this->data['fm7'] == 0) {
                                        $a8 = "in active";
                                        $b8 = "class=\"active\"";
                                        $c8 = "<span class=\"text-danger\"><i class=\"fa fa-edit\"> </i> </span>";

                                    }
                                }
                            }
                        }
                    }
                }
            }



        } //fmid>0





        $h.="
                <div class=\"row\">
                    <div class=\"col-lg-12\">

                        <ul class=\"nav nav-tabs\">
                            <li role=\"presentation\" $b1>
                                <a href=\"#a1\" role=\"tab\" data-toggle=\"tab\">$c1 Kontakte</a>
                            </li>
                            <li role=\"presentation\" $b2>
                                <a href=\"#a2\" role=\"tab\" data-toggle=\"tab\">$c2 Aufnahme</a>
                            </li>
                            <li role=\"presentation\" $b3>
                                <a href=\"#a3\" role=\"tab\" data-toggle=\"tab\">$c3 Kostenklärung</a>
                            </li>
                             <li role=\"presentation\" $b4>
                                <a href=\"#a4\" role=\"tab\" data-toggle=\"tab\">$c4 Freigabe</a>
                            </li>
                            <li role=\"presentation\" $b5>
                                <a href=\"#a5\" role=\"tab\" data-toggle=\"tab\">$c5 Reparatur</a>
                            </li>
                            <li role=\"presentation\" $b6>
                                <a href=\"#a6\" role=\"tab\" data-toggle=\"tab\">$c6 Versand</a>
                            </li>
                            <li role=\"presentation\" $b7>
                                <a href=\"#a7\" role=\"tab\" data-toggle=\"tab\">$c7 CO</a>
                            </li>
                        </ul>
                        <div class=\"tab-content\">
                            <div role=\"tabpanel\" class=\"tab-pane fade $a1\" id=\"a1\">
                             <div class=\"table-responsive\">
                                <table class=\"table \">
                                  <tbody><tr><td >
                                    ".$this->EditContact()."
                                </td></tr></tbody></table></div>
                            </div>
                            <div role=\"tabpanel\" class=\"tab-pane fade $a2\"  id=\"a2\">
                                <div class=\"table-responsive\">
                                <table class=\"table table-striped\">
                                  <tbody><tr><td colspan='2'>
                                    ".$this->EditPropErrorDetect()."
                                </td></tr></tbody></table></div>
                            </div>
                            <div role=\"tabpanel\" class=\"tab-pane fade $a3\" id=\"a3\">
                                <div class=\"table-responsive\">
                                <table class=\"table table-striped\">
                                  <tbody><tr><td colspan='2'>
                                    ".$this->EditPropProzess()."
                                </td></tr></tbody></table></div>
                            
                            </div>
                             <div role=\"tabpanel\" class=\"tab-pane fade $a4\" id=\"a4\">
                            <div class=\"table-responsive\">
                                <table class=\"table table-striped\">
                                  <tbody><tr><td colspan='2'>
                                    ".$this->EditPropDecision()."
                                </td></tr></tbody></table></div>
                           
                            </div>
                            <div role=\"tabpanel\" class=\"tab-pane fade $a5\" id=\"a5\">
                            <div class=\"table-responsive\">
                                <table class=\"table table-striped\">
                                  <tbody><tr><td colspan='2'>
                                    ".$this->EditPropRepair()."
                                </td></tr></tbody></table></div>
                           
                            </div>
                            <div role=\"tabpanel\" class=\"tab-pane fade $a6\" id=\"a6\">
                            <div class=\"table-responsive\">
                                <table class=\"table table-striped\">
                                  <tbody><tr><td colspan='2'>
                                    <p>".$this->EditPropPick()."
                                </td></tr></tbody></table></div>
                            </div>
                           
                             <div role=\"tabpanel\" class=\"tab-pane fade $a7\" id=\"a7\">
                            <div class=\"table-responsive\">
                                <table class=\"table table-striped\">
                                  <tbody><tr><td colspan='2'>
                                    <p>".$this->EditPropControlling()."
                                </td></tr></tbody></table></div>
                            </div>
                            </div>
                        </div>
                    </div><!-- end col -->
                </div>
                    ";

        $h.="</td></tr></tbody></table></div>";


        return $h;
    }


    function ShowModalContacts(){
        $m="<div class='table-responsive'> Wählen Sie aus den Adressbuch aus:";
        $m.="<table class='table table-striped'><tbody><tr><td>";
        $m.=asSelect2Box("Rechnungempfänger","idk",GetCustomerName("select2",$this->data['idk']),10);
        $m.="</td></tr><tr><td>";
        $m.=asSelect2Box("Warenempfänger","idk2",GetCustomerName("select2",$this->data['idk2']),10,"");
        $m.="</td></tr><tr><td>";
        $m.=asSelect2Box("Partner","idk3",GetCustomerName("select2",$this->data['idk3']),10,"");
        $m.="</td></tr><tr><td>";
        $m.=asSelectBox("Rechnung und Warenempfänger gleich ?","same",GetYesNoList("select",1));
        $m.="</td></tr><tr><td>";
        $m.=asSelectBox("anderer Auftraggeber ausblenden ?","nopartner",GetYesNoList("select",1));
        $m.="</td></tr></tbody></table>";

        $m.="</div>";
        $link=" <button name=\"fm_new_contact\"  type=\"submit\" class=\"btn btn-danger waves-effect waves-light\" >Anschriften anlegen</button>";

        $h=asModal(2001,$m,$link,60,"Adressbuch");
        $h.=" <a href=\"#custom-width-modal2001\" data-toggle=\"modal\" data-target=\"#custom-width-modal2001\" 
              class=\"btn btn-custom waves-effect waves-light\" >Adressbuch öffnen</a>";
        return $h;
    }
    function EditContact(){
        $h="<div class=\"col-lg-12\">";
        $h .= asJSLoader("addressbook", "Addressbuch wird geladen...", "data.php", "datatype=addressbook&fmid=" . $this->fmid);

        $h .= "<div class=\"panel-group\" id=\"accordion\" role=\"tablist\"
                     aria-multiselectable=\"true\">
                    <div class=\"panel panel-default bx-shadow-none\">
                        <div class=\"panel-heading\" role=\"tab\" id=\"headingOne\">
                            <h4 class=\"panel-title\">
                                <a role=\"button\" data-toggle=\"collapse\"
                                   data-parent=\"#accordion\" href=\"#collapseOne\"
                                   aria-expanded=\"true\" aria-controls=\"collapseOne\">
                                    Rechnungsempfänger
                                </a>
                            </h4>
                        </div>
                        <div id=\"collapseOne\" class=\"panel-collapse collapse in\"
                             role=\"tabpanel\" aria-labelledby=\"headingOne\">
                            <div class=\"panel-body\">
                                     <div class=\"table-responsive\">
                                    <table class=\"table table-striped\">
                                      <tbody><tr><td>
                                        <p>".$this->EditPropCustomerR()."
                                    </td></tr></tbody></table></div>
                            </div>
                        </div>
                    </div>
                    <div class=\"panel panel-default bx-shadow-none\">
                        <div class=\"panel-heading\" role=\"tab\" id=\"headingTwo\">
                            <h4 class=\"panel-title\">
                                <a class=\"collapsed\" role=\"button\" data-toggle=\"collapse\"
                                   data-parent=\"#accordion\" href=\"#collapseTwo\"
                                   aria-expanded=\"false\" aria-controls=\"collapseTwo\">
                                    Warenempfänger
                                </a>
                            </h4>
                        </div>
                        <div id=\"collapseTwo\" class=\"panel-collapse collapse\"
                             role=\"tabpanel\" aria-labelledby=\"headingTwo\">
                            <div class=\"panel-body\">
                                    <div class=\"table-responsive\">
                                    <table class=\"table table-striped\">
                                      <tbody><tr><td>
                                        <p>".$this->EditPropCustomerL()."
                                    </td></tr></tbody></table></div> 
                            </div>
                        </div>
                    </div>
                    <div class=\"panel panel-default bx-shadow-none\">
                        <div class=\"panel-heading\" role=\"tab\" id=\"headingThree\">
                            <h4 class=\"panel-title\">
                                <a class=\"collapsed\" role=\"button\" data-toggle=\"collapse\"
                                   data-parent=\"#accordion\" href=\"#collapseThree\"
                                   aria-expanded=\"false\" aria-controls=\"collapseThree\">
                                    anderer Auftraggeber
                                </a>
                            </h4>
                        </div>
                        <div id=\"collapseThree\" class=\"panel-collapse collapse\"
                             role=\"tabpanel\" aria-labelledby=\"headingThree\">
                            <div class=\"panel-body\">
                                      <div class=\"table-responsive\">
                                    <table class=\"table table-striped\">
                                      <tbody><tr><td>
                                        <p>".$this->EditPropCustomerP()."
                                    </td></tr></tbody></table></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- end col -->

</div>";

        return $h;
    }
    function EditPropCustomerR(){
        $h=asTextarea("Firma","rechnung",($this->data['rechnung']),3,7,"");
        $h.="</td></tr><tr><td>";
        $h.=asTextField("Straße","rstrasse",($this->data['rstrasse']),7,"");
        $h.="</td></tr><tr><td>";
        $h.=asTextField("PLZ","rplz",($this->data['rplz']),7,"");
        $h.="</td></tr><tr><td>";
        $h.=asTextField("Ort","rort",($this->data['rort']),7,"");
        $h.="</td></tr><tr><td>";
        $h.=asTextField("Land","rland",($this->data['rland']),7,"");
        $h.="</td></tr><tr><td>";
        $h.=asTextField("Ansprechpartner","ansprechpartner",($this->data['ansprechpartner']),7,"");
        $h.="</td></tr><tr><td>";
        $h.=asTextField("Telefon","rtelefon",($this->data['rtelefon']),7,"");
        $h.="</td></tr><tr><td>";
        $h.=asTextField("Fax","faxummer",($this->data['faxummer']),7,"");
        $h.="</td></tr><tr><td>";
        $h.=asTextField("E-Mail","remail",($this->data['remail']),7,"");
        $h.="</td></tr><tr><td>";
        $h.=asTextField("Kundennummer","debitorennummer",($this->data['debitorennummer']),7,"");
        $h.="</td></tr><tr><td>";
        return $h;
    }
    function EditPropCustomerL(){
        $h=asTextarea("Firma","lieferung",($this->data['lieferung']),3,7,"");
        $h.="</td></tr><tr><td>";
        $h.=asTextField("Straße","lstrasse",($this->data['lstrasse']),7,"");
        $h.="</td></tr><tr><td>";
        $h.=asTextField("PLZ","lplz",($this->data['lplz']),7,"");
        $h.="</td></tr><tr><td>";
        $h.=asTextField("Ort","lort",($this->data['lort']),7,"");
        $h.="</td></tr><tr><td>";
        $h.=asTextField("Land","lland",($this->data['lland']),7,"");
        $h.="</td></tr><tr><td>";
        $h.=asTextField("Ansprechpartner","lansprechpartner",($this->data['lansprechpartner']),7,"");
        $h.="</td></tr><tr><td>";
        $h.=asTextField("Telefon","ltelefon",($this->data['ltelefon']),7,"");
        $h.="</td></tr><tr><td>";
        $h.=asTextField("Fax","lfax",($this->data['lfax']),7,"");
        $h.="</td></tr><tr><td>";
        $h.=asTextField("E-Mail","lemail",($this->data['lemail']),7,"");
        $h.="</td></tr><tr><td>";
        return $h;
    }
    function EditPropCustomerP(){
        $h=asTextarea("Firma","partner",($this->data['partner']),3,7,"");
        $h.="</td></tr><tr><td>";
        $h.=asTextField("Straße","pstrasse",($this->data['pstrasse']),7,"");
        $h.="</td></tr><tr><td>";
        $h.=asTextField("PLZ","pplz",($this->data['pplz']),7,"");
        $h.="</td></tr><tr><td>";
        $h.=asTextField("Ort","port",($this->data['port']),7,"");
        $h.="</td></tr><tr><td>";
        $h.=asTextField("Land","pland",($this->data['pland']),7,"");
        $h.="</td></tr><tr><td>";
        $h.=asTextField("Ansprechpartner","pansprechpartner",($this->data['pansprechpartner']),7,"");
        $h.="</td></tr><tr><td>";
        $h.=asTextField("Telefon","ptelefon",($this->data['ptelefon']),7,"");
        $h.="</td></tr><tr><td>";
        $h.=asTextField("Fax","pfax",($this->data['pfax']),7,"");
        $h.="</td></tr><tr><td>";
        $h.=asTextField("E-Mail","pemail",($this->data['pemail']),7,"");
        $h.="</td></tr><tr><td>";
        return $h;
    }

    function EditPropErrorDetect(){

        $h=asSwitchery("Fehleraufnahme abgeschlossen - letzte Änderung: ".$this->data['fm1datum'],"fm1",$this->data['fm1'],"00b19d",1);
        $h.="</td></tr><tr><td colspan='2'>";
        $h .= asSelect2Box("Material", "fmartikelid", GetArtikelName("select2", $this->data['fmartikelid']), 9);
        $h.="</td></tr><tr><td>";
        $h .= asTextField("Seriennummer", "fmsn", $this->data['fmsn'], 5);
        $h.="</td><td>";
        $h .= asTextField("Ursprüngliches Fertigungsdatum", "fmfd", $this->data['fmfd'], 6, "");
        $h.="</td></tr><tr><td colspan='2'>";
        $h .= asTextField("Sendungsnummer", "fm11notes", $this->data['fm11notes'], 9, "");
        $h.="</td></tr><tr><td colspan='2'>";
        $h .= asTextField("Gewicht in kg (ohne kg)", "gewichtvorher", $this->data['gewichtvorher'], 9, "");
        $h.="</td></tr><tr><td colspan='2'>";
        $h .= asTextarea("Lieferumfang", "fm12notes", ($this->data['fm12notes']), 1, 9, "");
        $h.="</td></tr><tr><td colspan='2'>";
        $h .= "<div class=\"col-sm-12 input-group \">";
        $h .= asTextarea("Reparaturmaßnahmen", "fmnotes", ($this->data['fmnotes']), 3, 9, "");

        $h .= "<div class=\"input-group-btn dropup\">
             <button type=\"button\" class=\"btn waves-effect waves-light btn-custom dropdown-toggle\" data-toggle=\"dropdown\" style=\"overflow: hidden; position: relative;\">Vorlagen<span class=\"caret\"></span></button>
             <ul class=\"dropdown-menu dropdown-menu-right\">";
        include('../config/templates.php');
        $h .= $FIS_RETURN_DROPBOX_REPAIR;
        $h .= "</ul></div></div>";


        $h.="</td></tr><tr><td colspan='2'>";
        $h.=asTextarea("Hinweise Retourenabwicklung intern","intern",($this->data['intern']),4,12,"");

        $_SESSION['endscripts'] .= "
         \$('#fmfd').datepicker({
                autoclose: true,
                todayHighlight: true,
                format: \"yyyy-mm-dd\",
               
            });
         ";

        return $h;
    }

    function EditPropProzess(){

        $m = " <button name=\"fm_new_varianten\"  type=\"submit\" class=\"btn btn-danger waves-effect waves-light pull-right\" >Varianten ersetzen</button>";
        $m.=" <button name=\"fm_add_varianten\"  type=\"submit\" class=\"btn btn-custom waves-effect waves-light pull-right\" >Varianten anfügen</button>";

        $m.="<br><br>Wählen Sie aus den Prozessvarianten aus und sortieren Sie sie nach rechts:";

        $m.=$this->ShowHtmlProcessList();
        $link=" <button name=\"fm_new_varianten\"  type=\"submit\" class=\"btn btn-danger waves-effect waves-light\" >Varianten anlegen</button>";
        $link.=" <button name=\"fm_add_varianten\"  type=\"submit\" class=\"btn btn-custom waves-effect waves-light pull-right\" >Varianten anfügen</button>";

        $h=asSwitchery("Kostenklärung abgeschlossen - letzte Änderung: ".$this->data['fm2datum'],"fm2",$this->data['fm2'],"00b19d",1);
        $h.="</td></tr><tr><td colspan='2'>";

        $h.=asModal(2000,$m,$link,90,"Prozess und Variantenauswahl");
        $h.=" <a href=\"#custom-width-modal2000\" data-toggle=\"modal\" data-target=\"#custom-width-modal2000\" 
              class=\"btn btn-custom waves-effect waves-light\" >Variantenauswahl öffnen</a>";

        $h.="</td></tr><tr><td>";
        $h.=asSelectBox("Verschrotten ?","vkz",GetYesNoList("select",$this->data['vkz']),7,"");
        $h.="</td><td>";
        $h.=asSelectBox("Gewährleistung ?","gwl",GetYesNoList("select",$this->data['gwl']),7,"");
        $h.="</td></tr><tr><td colspan='2'>";
        $h.=asTextField("Kommission","kommission",($this->data['kommission']),7,"");
        $h.="</td></tr><tr><td colspan='2'>";
        $h.=asTextField("Anrede im Kostenvoranschlag","anrede",($this->data['anrede']),7,"");
        $h.="</td></tr><tr><td colspan='2'>";

        $h.=asTextarea("Bemerkungen für Kostenvoranschlag","fm2notes",($this->data['fm2notes']),4,12,"");

        $h.="</td></tr><tr><td colspan='2'>";
        $h.=$this->EditPropVarianten();



        return $h;
    }

    function EditPropDecision(){

        $h=asSwitchery("Freigabe abgeschlossen - letzte Änderung: ".$this->data['fm3datum'],"fm3",$this->data['fm3'],"00b19d",1);
        $h.="</td></tr><tr><td colspan='2'>";
        $h.="Dokumentieren Sie den Kundenwunsch anhand der folgenden Varianten:";
        $h.="</td></tr><tr><td>";
        $h.=asSelectBox("Upgrade ?","upgrade",GetYesNoList("select",$this->data['upgrade']),7,"");
        $h.="</td><td>";
        $h.=asSelectBox("Austausch notwendig?","exchange",GetYesNoList("select",$this->data['exchange']),7,"");
        $h.="</td></tr><tr><td colspan='2'>";
        $h.=asSelectBox("Dringlichkeit / VIP setzen","vip",GetYesNoList("select",$this->data['vip']),7,"");
        $h.="</td></tr><tr><td colspan='2'>";
        $h.=asTextField("SAP Auftragsnummer","sapsd",$this->data['sapsd'],7,"");
        $h.="</td></tr><tr><td colspan='2'>";
        $h.=asTextField("SAP PCNA Nacharbeitsauftrag","sappcna",$this->data['sappcna'],7,"");
        $h.="</td></tr><tr><td colspan='2'>";
        $h.=$this->ShowHtmlVarianten();

        return $h;
    }


    function EditFID(){
        $h="neue Fertigungsmeldung mit gleicher Seriennumer";
        $h.="<input type=\"hidden\" name=\"fmartikelid\" value=\"".$this->data['fmartikelid']."\">";
        $h.="<input type=\"hidden\" name=\"fmsn\" value=\"".$this->data['fmsn']."\">";
        $h.=" <button name=\"fm4_new_fid\"  type=\"submit\"  class=\"btn btn-danger waves-effect waves-light pull-right\" >Instandsetzung dokumentieren</button>";

        return $h;
    }
    function EditFIDRepairWithNewSN(){
        $h="Rearatur mit neuer Seriennummmer melden";
        $h.=" <button name=\"fm4_fid_repairwithnewsn\"  type=\"submit\" 
            class=\"btn btn-danger waves-effect waves-light pull-right\" >Neue Seriennummer anlegen</button>";

        return $h;
    }
    function EditFIDvkz(){
        $h="Verschrottung der Seriennumer melden";
        //$h.=asSwitchery("Verschrottung durchgeführt ?","fid_vkz",0,"ef5350",7,"");
        $h.=" <button name=\"fm4_fid_vkz\"  type=\"submit\" 
            class=\"btn btn-danger waves-effect waves-light pull-right\" >Verschrottung speichern</button>";

        return $h;
    }


    function EditFIDnewType(){
        $h="neue Fertigungsmeldung mit neuer Seriennumer unter neuem Material";
        $h.=asSelect2Box("Material","new_material_type",GetArtikelName("select2",$this->data['fmartikelid']),7,"");
        $h.=" <button name=\"fm4_fid_material_type\"  type=\"submit\" 
            class=\"btn btn-danger waves-effect waves-light pull-right\" >unter neuen Gerätetyp speichern</button>";

        return $h;
    }


    function EditFIDexchange(){
        $h="Austausch bzw. Ersatzlieferung der Seriennumer melden";
        $h.=asTextField("Seriennummer des Ersatzgerätes","fid_sn_new",0,7,"");
        $h.=" <button name=\"fm4_fid_exchange\"  type=\"submit\" 
            class=\"btn btn-danger waves-effect waves-light pull-right\" >Austausch speichern</button>";

        return $h;
    }
    function EditFIDRepair(){

        $h="<div class=\"col-lg-12 table-responsive\">Wählen Sie aus den Optionen aus<br>";

        $visible1=true;$in1="in";
        $visible2=false;$in2="";
        $visible3=false;$in3="";
        $visible4=false;$in4="";

        if ($this->data['upgrade']==1){
            $visible1=false; $in1="";
            $visible2=true;  $in2="in";
            $visible3=false; $in3="";
            $visible4=false; $in4="";
        }
        if ($this->data['vkz']==1){
            $visible1=false;    $in1="";
            $visible2=false;    $in2="";
            $visible3=true;     $in3="in";
            $visible4=false;    $in4="";
        }
        if ($this->data['exchange']==1){
            $visible1=false;$in1="";
            $visible2=false;$in2="";
            $visible3=false;$in3="";
            $visible4=true;$in4="in";
        }

        $h.="
                           
                <div class=\"panel-group table-responsive\" id=\"accordionR\" role=\"tablist\"
                     aria-multiselectable=\"false\">
                    <div class=\"panel panel-default bx-shadow-none\">
                        <div class=\"panel-heading\" role=\"tab\" id=\"headingROne\">
                            <h4 class=\"panel-title\">
                                <a role=\"button\" data-toggle=\"collapse\"
                                   data-parent=\"#accordion\" href=\"#collapseROne\"
                                   aria-expanded=\"$visible1\" aria-controls=\"collapseROne\">
                                    Reparatur
                                </a>
                            </h4>
                        </div>
                        <div id=\"collapseROne\" class=\"panel-collapse collapse $in1\"
                             role=\"tabpanel\" aria-labelledby=\"headingOne\">
                            <div class=\"panel-body\">
                                     <div class=\"table-responsive\">
                                    <table class=\"table table-striped\">
                                      <tbody><tr><td>
                                        ".$this->EditFID()."
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
                                   Reparatur mit einer neuen Seriennummer 
                                </a>
                            </h4>
                        </div>
                        <div id=\"collapseRTwo\" class=\"panel-collapse collapse $in2\"
                             role=\"tabpanel\" aria-labelledby=\"headingRTwo\">
                            <div class=\"panel-body\">
                                    <div class=\"table-responsive\">
                                    <table class=\"table table-striped\">
                                      <tbody><tr><td>
                                        ".$this->EditFIDRepairWithNewSN()."
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
                                    Verschrottung
                                </a>
                            </h4>
                        </div>
                        <div id=\"collapseRThree\" class=\"panel-collapse collapse $in3\"
                             role=\"tabpanel\" aria-labelledby=\"headingRThree\">
                            <div class=\"panel-body\">
                                      <div class=\"table-responsive\">
                                    <table class=\"table table-striped\">
                                      <tbody><tr><td>
                                        ".$this->EditFIDvkz()."
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
                                    Upgrade in neuen Gerätetype
                                </a>
                            </h4>
                        </div>
                        <div id=\"collapseRFour\" class=\"panel-collapse collapse $in4\"
                             role=\"tabpanel\" aria-labelledby=\"headingFour\">
                            <div class=\"panel-body\">
                                      <div class=\"table-responsive\">
                                    <table class=\"table table-striped\">
                                      <tbody><tr><td>
                                         ".$this->EditFIDnewType()."
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
    function EditPropRepair(){


        $m=$this->EditFIDRepair();
        $h=asModal(2002,$m,"",90,"VDE-Prüfung");


        $m="<br>Scannen Sie die Materialnummers aus dem Katalog:<br>";
        $m.=asTextarea("Materialnummern","fm4_material","",10,7,"");
        $link=" <button name=\"fm_new_material\"  type=\"submit\" class=\"btn btn-danger waves-effect waves-light pull-right\" >Materialverwendung speichern</button>";



        $h.=asSwitchery("Reparatur abgeschlossen - letzte Änderung: ".$this->data['fm4datum'],"fm4",$this->data['fm4'],"00b19d",2);
        $h.="</td></tr><tr><td colspan='2'>";
        $h.=asModal(2003,$m,$link,90,"Reparaturrückmeldung");
        $h.="</td></tr><tr><td colspan='2'>";
        $h.="Der Kunde wünscht folgende Leistung:";
        $h.="</td></tr><tr><td colspan='2'>";
        $h.=$this->ShowHtmlVarianteRepair();
        $h.="</td></tr><tr><td colspan='2'>";
        $h .= asTextField("Seriennummer nach Reparatur", "fmsn2", $this->data['fmsn2'], 7, "");
        $h.="</td></tr><tr><td colspan='2'>";
        $h.=asTextField("Arbeitszeit in min","fm4notes",$this->data['fm4notes'],7,"");
        $h.="</td></tr><tr><td colspan='2'>";
        $h.=" <a href=\"#custom-width-modal2002\" data-toggle=\"modal\" data-target=\"#custom-width-modal2002\" 
              class=\"btn btn-custom waves-effect waves-light\" ><i class='fa fa-check'></i> Geräteprüfung oder Verschrottung durchführen</a>";
        $h.=" <a href=\"#custom-width-modal2003\" data-toggle=\"modal\" data-target=\"#custom-width-modal2003\" 
              class=\"btn btn-custom waves-effect waves-light\"><i class='zmdi zmdi-mail-reply-all'> </i> Materialrückmeldung öffnen</a>";
        $h.="</td></tr><tr><td colspan='2'>";
        $h.=$this->GetConfirmMaterialList();

        return $h;
    }

    function EditPropPick(){
        $h=asSwitchery("Versand abgeschlossen - letzte Änderung: ".$this->data['fm5datum'],"fm5",$this->data['fm5'],"00b19d",2);
        $h .= "</td></tr><tr><td colspan='2'>";
        $h .= "<script>
                    function CopyToClipboard(element) {
                      var copyText = document.getElementById(element);
                      copyText.select();
                      document.execCommand(\"copy\");
                      alert(\"Auftragsnummer wurde in Zwischenablage kopiert: \\n\" + copyText.value);
                    }
                    function CopyToClipboardDel() {
                      var copyText = document.getElementById('deltext');
                      copyText.select();
                      document.execCommand(\"copy\");
                      alert(\"Auftragsnummer wurde aus Zwischenablage gelöscht \\n\" + copyText.value);
                    }
                    </script>";
        $h .= asTextField("SAP-Auftrag", "sapsd2", $this->data['sapsd'], 7, "", "onClick=\"CopyToClipboard('sapsd2');\"");
        $h .= "</td></tr><tr><td colspan='2'>";
        $h .= asTextField("SAP-Lieferung", "fm5notes", $this->data['fm5notes'], 7, "");
        $h.="</td></tr><tr><td colspan='2'>";
        $h.=asTextField("Sendungsnummer","glsversand",$this->data['glsversand'],7,"");
        $h.="</td></tr><tr><td colspan='2'>";
        $h .= asTextField("VDE 0701 - Prufbericht-Nr. einscannen", "fm1text", $this->data['fm1text'], 7, "");



        return $h;
    }

    function EditPropControlling(){

        $h=asSwitchery("Abrechnung abgeschlossen - letzte Änderung: ".$this->data['fm6datum'],"fm6",$this->data['fm6'],"00b19d",2);
        $h.="</td></tr><tr><td colspan='2'>";
        $h.="Rückmeldung abgeschlossen - letzte Änderung: ".$this->data['fm7datum'];
        $h.="</td></tr><tr><td colspan='2'>";
        $h.=asTextField("Abrechnung","sappcnax",$this->data['sappcna'],7,"");
        $h.="</td></tr><tr><td colspan='2'>";
        $h.=asSelectBox("Abrechnungsstatus","fm7",GetControllingList("select",$this->data['fm7']),7);
        $h.="</td></tr><tr><td colspan='2'>";

        $h.=$this->GetConfirmMaterialList(false);

        return $h;
    }


    function ShowHtmlVarianteRepair($a = true, $id = 0)
    {
        if ($id > 0) {
            $this->fmid = $id;
        }
        $this->LoadSelectedRepairVariant();
        $h="";
        if (count($this->slectedvariant)>0) {
            foreach ($this->slectedvariant as $i => $item){
                if ($a) $h .= "<div class='alert alert-success'>";
                $h.=$item['varbeschreibung'];
                if ($a) $h.="</div>";
            }
        }else{
            if ($a) $h.="<div class='alert alert-danger'>";
            $h.="Variante noch nicht entschieden";
            if ($a) $h.="</div>";

        }
        return $h;
    }
    function EditPropVarianten(){
        $this->LoadSelectedVariants();
        $h="<div class=\"table-responsive\">
                <table class=\"table table-striped\">
                    <thead>
                    <tr>
                        <th width='15%'>Pos.</th>         
                        <th width='60%'>Beschreibung</th>
                        <th width='15%'>Preis</th>
                          <th>Löschen?</th>
                       
                      
                    </tr>
                    </thead>
                    <tbody>";

        if (count($this->slectedvariants)>0){
            foreach($this->slectedvariants as $item){
                $h.="<tr><td>".asTextField("","varname_".$item['varid'],$item['varname'],12,"") ."</td>";
                $h.="<td >".asTextArea("","varbeschreibung_".$item['varid'],$item['varbeschreibung'],4,12,"")."</td>";
                $h.="<td >".asTextField("","Kosten1_".$item['varid'],$item['Kosten1'],12,"")."</td>";
                $h .= "<td>" . asSwitchery("", "del_" . $item['varid'], "off", "ef5350", 12, "") . "</td></tr>";

            }
            $h.="</tbody>";

        }else{
            $h.="<tr><td colspan='4'>Noch keine Varianten vorhanden</td></tr>";
            $h.="</tbody>";
        }
        $h.="</table></div>";


        return $h;
    }
    function ShowHtmlVarianten(){
        $this->LoadSelectedVariants();
            $h="<div class=\"table-responsive\">
                <table class=\"table table-striped\">
                    <thead>
                    <tr>
                        <th>Pos.</th>         
                        <th width='65%' >Beschreibung</th>
                        <th>Preis</th>
                        <th>Auswahl</th>
                       
                      
                    </tr>
                    </thead>
                    <tbody>";

            if (count($this->slectedvariants)>0){
                foreach($this->slectedvariants as $item){
                    if ($item['entid'] > 0) {
                        $checked = 1;
                    } else {
                        $checked = "off";
                    }
                    $h.="<tr><td>".$item['varname']."</td>";
                    $h.="<td width='65%'>".$item['varbeschreibung']."</td>";
                    $h.="<td>".$item['Kosten1']."</td>";
                    $h.="<td>".asSwitchery("","checked_variant_".$item['varid'], $checked,"00b19d",12,"")."</td></tr>";
                }
                $h.="</tbody>";

            }else{
                $h.="<tr><td colspan='4'>keine Varianten vorhanden</td></tr>";
                $h.="</tbody>";
            }
            $h.="</table></div>";


        return $h;
    }



    function ShowHtmlSelectedErrors(){
        $this->LoadAviableErrors();
        $h="";
        if (count($this->allerrors)){
            foreach ($this->allerrors as $i=>$u){
                if ($u['fid2']>0) {$s="selected"; }else{$s="";}
                $h.=sprintf("<option value='%s' %s> %s </option>",$u['fid'],$s,$u['fkurz']."-".$u['fname']);
            }
        }
        return $h;
    }
    function ShowHtmlErrorList(){

        if ($this->data['fm1']==1){
            $this->LoadErrors();
            $h=$this->GetErrorList();
        }else {
            $h = "<select multiple=\"\" class=\"multi-select\" id=\"my_multi_selectError\" name=\"my_multi_selectError[]\"   >";
            $h .= $this->ShowHtmlSelectedErrors();
            $h .= "</select>";

            $_SESSION['endscripts'] .= " //advance multiselect start
        $('#my_multi_selectError').multiSelect({
            selectableHeader: \"<input type='text' class='form-control search-input' autocomplete='off' placeholder='suchen...'>\",
            selectionHeader: \"<input type='text' class='form-control search-input' autocomplete='off' placeholder='suchen...'>\",
            afterInit: function (ms) {
                var that = this,
                    \$selectableSearch = that.\$selectableUl.prev(),
                    \$selectionSearch = that.\$selectionUl.prev(),
                    selectableSearchString = '#' + that.\$container.attr('id') + ' .ms-elem-selectable:not(.ms-selected)',
                    selectionSearchString = '#' + that.\$container.attr('id') + ' .ms-elem-selection.ms-selected';

                that.qs1 = \$selectableSearch.quicksearch(selectableSearchString)
                    .on('keydown', function (e) {
                        if (e.which === 40) {
                            that.\$selectableUl.focus();
                            return false;
                        }
                    });

                that.qs2 = \$selectionSearch.quicksearch(selectionSearchString)
                    .on('keydown', function (e) {
                        if (e.which == 40) {
                            that.\$selectionUl.focus();
                            return false;
                        }
                    });
            },
            afterSelect: function () {
                this.qs1.cache();
                this.qs2.cache();
            },
            afterDeselect: function () {
                this.qs1.cache();
                this.qs2.cache();
            }
        });";
        }
        return $h;
    }

    function ShowHtmlSelectedProzess(){
        $this->LoadAviableProzess();
        $h="";
        if (count($this->allprozess)){
            $x="";
            foreach ($this->allprozess as $i=>$u){
                if ($u['selectedvarid']>0) {$s="selected"; }else{$s="";}
                    if ($u['entid']<>$x) {
                        if ($x<>""){$h.="</optgroup>";}
                        $h .= "<optgroup label=\"" . $u['entname'] . "\">";
                    }
                $h.=sprintf("<option value='%s' %s> %s </option>",$u['varid'],$s,$u['varname']."-".$u['varbeschreibung']." ".$u['Kosten1']." €");
                $x=$u['entid'];
            }
            $h.="</optgroup>";
        }
        return $h;
    }
    function ShowHtmlProcessList(){
        $h="<select multiple=\"\" class=\"multi-select\" id=\"my_multi_selectProzess\" name=\"my_multi_selectProzess[]\" data-selectable-optgroup=\"true\" >";
        $h.=$this->ShowHtmlSelectedProzess();
        $h.="</select>";

        $_SESSION['endscripts'].=" //advance multiselect start
        $('#my_multi_selectProzess').multiSelect({
            selectableHeader: \"<input type='text' class='form-control search-input' autocomplete='off' placeholder='suchen...'>\",
            selectionHeader: \"<input type='text' class='form-control search-input' autocomplete='off' placeholder='suchen...'>\",
            afterInit: function (ms) {
                var that = this,
                    \$selectableSearch = that.\$selectableUl.prev(),
                    \$selectionSearch = that.\$selectionUl.prev(),
                    selectableSearchString = '#' + that.\$container.attr('id') + ' .ms-elem-selectable:not(.ms-selected)',
                    selectionSearchString = '#' + that.\$container.attr('id') + ' .ms-elem-selection.ms-selected';

                that.qs1 = \$selectableSearch.quicksearch(selectableSearchString)
                    .on('keydown', function (e) {
                        if (e.which === 40) {
                            that.\$selectableUl.focus();
                            return false;
                        }
                    });

                that.qs2 = \$selectionSearch.quicksearch(selectionSearchString)
                    .on('keydown', function (e) {
                        if (e.which == 40) {
                            that.\$selectionUl.focus();
                            return false;
                        }
                    });
            },
            afterSelect: function () {
                this.qs1.cache();
                this.qs2.cache();
            },
            afterDeselect: function () {
                this.qs1.cache();
                this.qs2.cache();
            }
        });";
        return $h;
    }


    function GetErrorList(){
        //todo colors
        $h="<div class=\"table-responsive\">
                <table class=\"table table-striped\">
                    <thead>
                    <tr>
                        <th>Kurzbezeichnung</th>
                        <th>Fehler</th>
                       
                    </tr>
                    </thead>
                    <tbody>";

        if (count($this->errordata)>0){
            foreach($this->errordata as $item){
                if ($item['fcolor']=="danger"){
                    $h.="<tr><td><span class='label label-danger'>".substr($item['fkurz'],0,16)."</span></td>";

                }elseif ($item['fcolor']=="warning"){
                    $h.="<tr><td><span class='label label-warning'>".substr($item['fkurz'],0,16)."</span></td>";

                }else{
                    $h.="<tr><td><span class='label label-inverse'>".substr($item['fkurz'],0,16)."</span></td>";

                }
                $h.="<td>".$item['fname']."</td></tr>";
            }
            $h.="</tbody>";

        }else{
            $h.="<tr><td colspan='5'>keine Fehler vorhanden</td></tr>";
            $h.="</tbody>";
        }
        $h.="</table></div>";
        return $h;
    }


    function GetConfirmMaterialList($action=true){
        $this->LoadConfirmMaterial();
        $h="";
        if (count($this->materials)>0){
            foreach($this->materials as $i=>$item){
                $artikelid=GetArtikelName("searchbymaterial",$item['kompnr']);
                $m=new FisMaterial($artikelid);
                if ($i==0) {
                    $h = "<div class=\"table-responsive\">
                            <table class=\"table table-striped\">
                                <thead>
                                <tr>
                                    <th>Nummer</th>
                                    <th>Bild</th>
                                    <th>Material</th>
                                    <th>Bezeichnung</th>
                                    <th>Menge</th>
                                    <th>Lagerort</th>";
                    if ($action) {
                        $h .= "<th>Löschen?</th>";
                    }
                    $h .= "</tr></thead><tbody>";
                }

                $h.="<tr><td>".$item['kompnr']."</td>";
                $h.="<td><img src='".$m->GetPictureLink()."' class='thumb-sm'> </td>";
                $h.="<td>".$m->GetMaterial()."</td>";
                $h.="<td>".$m->GetMaterialDesc()."</td>";
                $h.="<td>".$item['anzahl']." ".$item['unit']."</td>";
                $h.="<td>".$item['lagerort']."</td>";
                if ($action) {
                    $h .= "<td>" . asSwitchery("", "del_material_" . $item['id_ruck'], "off", "ef5350", 12, "");
                }
                $h.="</td></tr>";
            }
            $h.="</tbody>";
            $h.="</table></div>";
        }else{
            $h.="<div class='alert alert-danger'>Noch keine Kompoente zurückgemeldet.</div>";
        }

        return $h;
    }


    function ListOfFids(){
        $this->LoadFids(($_REQUEST['fm_sn']*1),$_REQUEST['fm_artikelid']);
        $h.="";
        if (count($this->fids)>0){
            foreach($this->fids as $i=>$item){
                $fid=new FisFid($item['fid']);
                if ($i==0) {
                    $h = "<div class=\"table-responsive\">
                        <table class=\"table table-striped\">
                            <thead>
                            <tr>
                                <th>Datum</th>
                                <th>SN</th>
                                 <th>Prüfung</th>
                                 <th>Status</th>
                                 <th>" . $fid->GetPropertyDesc(1) . "</th>
                                 <th>" . $fid->GetPropertyDesc(2) . "</th>
                                 <th>" . $fid->GetPropertyDesc(3) . "</th>
                                 <th>" . $fid->GetPropertyDesc(4) . "</th>
                                <th>Arbeitsplatz</th>
                            </tr>
                            </thead>
                            <tbody>";
                }



                $h.="<tr><td>".substr($item['fdatum'],0,16)."</td>";
                $h.="<td>".sprintf("%06.0f",$item['fsn'])."</td>";
                $h.="<td>".$fid->GetFree("text").$fid->GetSpecial("text")."</td>";
                $h.="<td>".$fid->GetStatus("text")."</td>";
                $h.="<td>".$item['fsn1']."</td>";
                $h.="<td>".$item['fsn2']."</td>";
                $h.="<td>".$item['fsn3']."</td>";
                $h.="<td>".$item['fsn4']."</td>";
                $h.="<td>".$item['arbeitsplatz']."</td>";
                $h.="<td><a href=\"#\" class=\"btn btn-custom btn-sm\" onclick=\"SetDatum('".substr($item['fdatum'],0,10)."');\"> Datum übernehmen</a> </td></tr>";
            $_SESSION['functions']="
            function SetDatum(s){
                $(\"#fm_fd\").val(s);
              
            }
            
            ";
            }
            $h.="</tbody>";
            $h.="</table></div>";
        }
        return $h;
    }

    function ListOfMaterialTypes(){
        $h=asSelect2Box("Materialnummer und Bezeichnung","fm_artikelid",GetArtikelName("searchbysn",$_REQUEST['fm_artikelid'],intval($_REQUEST['fm_sn']*1) ),7,"");
        return $h;
    }

    function GetStarted(){
       $h="";
        if (($_REQUEST['fm_sn']+0)==0 ) {
            AddSessionMessage("info", "Keine Seriennummer eingeben, Ist die Seriennummer nicht bekannt, dann 999999 eingeben", "Eingabehilfe");
        }

        if (strlen($_REQUEST['fm_label'])>8) {
            $idk=new FisReturnDevice(0);
            $idk=$idk->GetCustomerNumberByDeliveryNumber($_REQUEST['fm_label']);
            if ($idk>0){
            $_REQUEST['fm_idk']=$idk;
            $k=new FisCustomer($idk);
            $_REQUEST['fm_fm1notes']=$k->GetCustomerName();
                AddSessionMessage("info", "Kundenadresse wurde aus der Lieferung geladen", "Eingabehilfe");

                $h .= "<div class='alert alert-info'>Kundenadresse wurde aus der Lieferung geladen</div>";
            }
        }
        $h .= "<div class=\"col-lg-6 card-box \"><h5>Sendungsinformationen</h5>
                <table class=\"table table-striped\">
                  <tbody>";
        $h.="<tr><td colspan='2'>";
        $h.=asTextField("Sendungsnummer einscannen","fm_label",$_REQUEST['fm_label'],7,"");
        $_SESSION['focus']="$('#fm_label').focus();";

        $h.="</td></tr><tr><td colspan='2'>";

        $h.="<div class=\"col-sm-12 input-group \">";

        $h.=asTextField("Lieferumfang","fm_deliverycontent",$_REQUEST['fm_deliverycontent'],6,"");

        $h .= "<div class=\"input-group-btn\">
             <button type=\"button\" class=\"btn waves-effect waves-light btn-custom dropdown-toggle\" data-toggle=\"dropdown\" style=\"overflow: hidden; position: relative;\">Auswahl <span class=\"caret\"></span></button>
             <ul class=\"dropdown-menu dropdown-menu-right\">";
        include('../config/templates.php');
        $h .= $FIS_RETURN_DROPBOX_CONTENT;
        $h .= "</ul></div></div>";




        $h.="</td></tr><tr><td colspan='2'>";
        $h.=asSelect2Box("Kunde","fm_idk",GetCustomerName("select2",$_REQUEST['fm_idk']),7,"");
        $h.="</td></tr><tr><td colspan='2'>";
        $h.=asTextField("Kundenkurztext","fm_fm1notes",$_REQUEST['fm_fm1notes'],7,"");

        $h.="</td></tr></tbody></table></div>";

        $h .= "<div class=\"col-lg-6 card-box\"><h5>Bauteilinformationen</h5>
                <table class=\"table table-striped\">
                  <tbody>";

        $h.="</td></tr><tr><td colspan='2'>";
        $h.=asTextField("Seriennummer eingeben","fm_sn",$_REQUEST['fm_sn'],7,"");

        $h.="</td></tr><tr><td colspan='2'>";
        $h.=asTextField("Fertigungsdatum eingeben","fm_fd",$_REQUEST['fm_fd'],7,"");
        $h.="</td></tr><tr><td colspan='2'>";

            $h.=$this->ListOfMaterialTypes();


            $h.="</td></tr><tr><td colspan='2'>";

            $_SESSION['endscripts'].="
            
            $('#fm_artikelid').change(function() {
                 refresh_site();
                 sendform.submit();
                });
            ";



        if ( ($_REQUEST['fm_label']+0)>0 && ($_REQUEST['fm_idk']+0)==0){
            $_REQUEST['fm_idk']=$this->LoadReturnDevice($_REQUEST['fm_label']);
            $idk=new FisCustomer($_REQUEST['fm_idk']);
            $_REQUEST['fm_fm1notes']=$idk->GetCustomerName();
        }else{

        }


        $h.="</td></tr></tbody></table></div>";

        $h .= "<div class=\"col-lg-12 table-responsive \">";
        if (($_REQUEST['fm_sn']+0)>0 or $_REQUEST['fm_sn']==999999) {

            if (($_REQUEST['fm_artikelid']+0)>0 ) {
                $h.=$this->ListOfFids();

            }

        }else{
            AddSessionMessage("warning", "Keine Fertigungsdaten gefunden", "Eingabehilfe");


            $h .= "<div class='alert alert-warning'>Keine Fertigungsdaten gefunden,</div>";

        }
        if ($_REQUEST['fm_artikelid']>0){

        }else{
            AddSessionMessage("warning", "Kein Material ausgewählt", "Eingabehilfe");

            $h .= "<div class='alert alert-warning'>Kein Material ausgewählt,</div>";
        }

        $h.="</div>";
        return $h;
    }

    function ShowHtmlSAP(){
        $h="Keine Rückmeldungen zur Bearbeitung gefunden.";

        $wp=new FisWorkplace($_SESSION['wp']);
        $confirmorderlist=$wp->data['fehlermeldungen'];

        if (count($confirmorderlist)>0){
            $h="<h4>Auftragsliste</h4>";
            foreach ($confirmorderlist as $item=>$order){
                $h.="<div class='row'>";

                $h.="<div class='col-lg-12 card-box'>";
                $h.="<h5>Auftragabrechnung für Auftrag ".$item." - ".$order['name']." GWL=".$order['gwl']."</h5>";
                $h.="
                    <script>
                    function CopyToClipboard(element) {
                      var copyText = document.getElementById(element);
                      copyText.select();
                      document.execCommand(\"copy\");
                      alert(\"Markierter Text wurde in Zwischenablage kopiert: \\n\" + copyText.value);
                    }
                    function CopyToClipboardDel() {
                      var copyText = document.getElementById('deltext');
                      copyText.select();
                      document.execCommand(\"copy\");
                      alert(\"Markierter Text wurde aus Zwischenablage gelöscht \\n\" + copyText.value);
                    }
                    </script>";

                $this->LoadReturnToConfirm($order['gwl'],$item);

                if (count($this->confirmlist)>0){

                    $txt="Abrechnung in SAP dokumentieren";

                    $txt.="<button name=\"fm7_confirm_".$item."\" type=\"submit\" class=\"btn btn-danger waves-effect waves-light\" >Abrechnen</button>";

                    $h.=asModal("x".$item,$txt,"",60,"Rückmeldung für Auftrag ".$item);

                    $h.=count($this->confirmlist)." Fehlermeldungen gefunden";
                    $h.="
                    <script>
                    function set_button$item(item) {
                        if (\$(\"#confirm_order_$item\").val()==''){
                           \$(\"#confirm_order_$item\").val(item)
                            \$(\"#confirm_$item\").html('<a href=\"#custom-width-modalx'+ item +'\" data-toggle=\"modal\" data-target=\"#custom-width-modalx'+ item +'\" class=\"btn btn-custom waves-effect waves-light\" >Abrechnung öffnen</a>');
                            alert('Datensicherung ausgeführt. Sie können jetzt die Abrechnung für Auftrag $item starten');
                           
                            CopyToClipboard('fmid_confirm$item');
                        }else{
                            \$(\"#confirm_order_$item\").val('')
                            \$(\"#confirm_$item\").html('');
                            alert('Abrechnung für Auftrag $item zurückgenommen');
                            CopyToClipboardDel();
                        }
                    }
                    </script>
                    ";


                    $js="onChange=\"set_button$item($item)\"";
                    $h.="<div class='col-lg-8'>";
                    $h.=asTextField("Auftragsnummer","confirm_order_$item","",7,"");
                    $h.=asTextField(" Minuten Rückmeldezeit","fmid_confirm_duration",$this->repduration,7,"");
                    $h.=asTextarea("Rückmeldung Material","fmid_confirm".$item,$this->confirmtxt,10,7,"");
                    $h.="</div><div class='col-lg-4'>";
                    $h .= asSwitchery("Abrechnen", "fid_confirm", "off", "00DD00", 10, "", $js);

                    $h.="<div id=\"confirm_$item\"> </div>";
                    $h.="</div>";

                }else{
                    $h.="keine Rückmeldungen gefunden.<br><br>";
                }
                $h.="</div>";
                $h.="</div>";
            }
        }
        $h.="<textarea id='deltext' rows='1' > </textarea>";
        return $h;
    }

    function ShowHtmlFmidConfirms(){

        $this->LoadOrderConfirmData();
        $h="";
        if (count($this->fmids)>0){
            foreach($this->fmids as $i=>$item){

                if ($i==0) {
                    $h = "<div class=\"table-responsive\">
                    <table class=\"table table-striped\">
                        <thead>
                        <tr>
                            <th>Datensicherung</th>
                            <th>Datum</th>
                            <th>Auftragsnummer</th>
                            <th>Storno</th>
                            <th>Aktion</th>
                        </tr>
                        </thead>
                        <tbody>";
                }



                $h.="<tr><td>".sprintf("%s",$item['fmoc'])."</td><td>".date("Y-m-d H:i",$item['datum'])."</td>";
                $h.="<td>".$item['order']."</td>";
                $h .= "<td>" . GetYesNoList("text", $item['lokz']) . "</td>";
                $h.="<td><form method='post'>
                        <input type='hidden' name='reset_order' value='".$item['fmoc']."'>
                        <button name='Reset_Order_Confirm' type='submit' class='btn btn-custom btn-sm' > Abrechnung widerrufen </button> 
                        </td>
                        </form>
                        </tr>";

            }
            $h.="</tbody>";
            $h.="</table></div>";
        }
        return $h;
    }

}