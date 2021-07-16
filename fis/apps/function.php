<?php
/**
 * Created by PhpStorm.
 * User: andreasveltjens
 * Date: 25.12.16
 * Time: 12:23
 */

define(IFIS_PATH_UPLOAD_WAID,"../warenausgang/");
define(IFIS_PATH_UPLOAD_WID,"../warenaeingang/");

include_once ('../apps/mailer.php');
/** Layout Standard */
function debuglog($s){
    return str_replace("`"," ",$s);
}
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "")
{
    $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

    switch ($theType) {
        case "text":
            $theValue = ($theValue != "") ? "'" . utf8_encode($theValue) . "'" : "''";
            break;
        case "long":
        case "int":
            $theValue = ($theValue != "") ? intval($theValue) : "NULL";
            break;
        case "double":
            $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
            break;
        case "date":
            $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
            break;
        case "defined":
            $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
            break;
    }
    return $theValue;
}
function GetHtmlProgressbar($valuetxt,$value){
    $h="<p class=\"font-600 m-b-5\">";
    $h1="<span class=\"text-custom pull-right\">";
    $h2="</span></p><div class=\"progress progress-bar-custom-alt progress-sm m-b-20\">
         <div class=\"progress-bar progress-bar-custom progress-animated wow animated animated\" 
         role=\"progressbar\" aria-valuenow=\" ".round($value,0)."\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width: 80%; visibility: visible; animation-name: animationProgress;\">
                                      </div><!-- /.progress-bar .progress-bar-danger -->
                                    </div><!-- /.progress .no-rounded -->";

    return $h.$valuetxt.$h1.$value.$h2;
}
function GetWareneingangsstatus($state){
    switch ($state){
        case 0:{
            return "Neu";
            break;
        }
        case 1:{
            return "in Arbeit";
            break;
        }
        case 2:{
            return "erledigt";
            break;
        }
    }
}
/** User Functions */
function GetUserStatus($state){
    switch ($state){
        case 0:{
            return "Neuanlage";
            break;
        }
        case 1:{
            return "Aktiviert";
            break;
        }
        case 2:{
            return "Deaktiviert";
            break;
        }
    }
}
function GetStatus($state){
    switch ($state){
        case 0:{
            return "Neuanlage";
            break;
        }
        case 1:{
            return "Info";
            break;
        }
        case 2:{
            return "Warnung";
            break;
        }
        case 3:{
            return "Problem";
            break;
        }
        case 4:{
            return "Datenspeicherung";
            break;
        }
    }
}
function GetListUserRights($type,$value){
    $r=array(0=>"Gast",1=>"Mitarbeiter",2=>"Abteilungsleiter",3=>"Betriebsleiter",4=>"Geschäftsführer",5=>"Administrator");
    $h="";
    switch ($type){
        case "select":{
            foreach ($r as $index => $item) {
                if ($value==$index ){$s="selected";}else{$s="";}
                $h.="<option value=\"".$index."\" $s>".$item."</option>";
            }
            break;
        }
    }
    return $h;
}
function GetListUserStatus($type,$value){
    $r=array(0=>"Neuanlage",1=>"Datenimport Access",2=>"Eignungsprüfung",3=>"Fahrauftrag Stapler",4=>"Fahrauftrag Hubarbeitsbühne",5=>"Fahrauftrag Krananlage",
        6=>"Passwort per E-Mail", 7=>"Datenänderung",8=>"Druckvorschau", 9=>"Mitarbeiteranmeldung",10=>"Berechtigung geändert",
        999=>"gelöscht"
    );


    $h="";
    switch ($type){
        case "select":{
            foreach ($r as $index => $item) {
                if ($value==$index ){$s="selected";}else{$s="";}
                $h.="<option value=\"".$index."\" $s>".$item."</option>";
            }
            break;
        }
        case "text":{
            foreach ($r as $index => $item) {
                if ($value==$index ){$h=$item;}
            }
            break;
        }
        case "data":{$h=$r; break;}
    }
    return $h;
}

/** FAUF Functions */
function GetFaufStatus($state){
    switch ($state){
        case 0:{
            return "Neuanlage";
            break;
        }
        case 1:{
            return "eingeplant";
            break;
        }
        case 2:{
            return "in Arbeit";
            break;
        }
        case 3:{
            return "in Homogenisierung";
            break;
        }
        case 4:{
            return "fertig";
            break;
        }
        case 5:{
            return "unklare Spezifikation";
            break;
        }
        case 6:{
            return "gesperrt";
            break;
        }
    }
}
function GetListFaufType($type,$value){
    $r=array(0=>"bitte auswählen",
            1=>"Granulat",
            2=>"Mahlgut",
            3=>"Agglomerat",
            4=>"Granulat - Homogenisierung ",
            5=>"Granulat - Siloverladung",
            6=>"Granulat - Verpackungsart tauschen",
            7=>"Granulat - Verwiegen und Packzettel erneuern",
    );
    $h="";
    if ($type=="data"){
        return $r;
    }else {
        switch ($type) {
            case "select": {
                foreach ($r as $index => $item) {
                    if ($value == $index) {
                        $s = "selected";
                    } else {
                        $s = "";
                    }
                    $h .= "<option value=\"" . $index . "\" $s>" . $item . "</option>";
                }
                break;
            }
            case "text": {
                foreach ($r as $index => $item) {
                    if ($value == $index) {
                        $h = $item;
                    }
                }
                break;
            }
        }
        return $h;
    }
}
function GetListFaufStatus($type,$value){
    $r=array(0=>"Neuanlage",1=>"eingeplant",2=>"in Arbeit",3=>"in Homogenisierung",4=>"fertig",5=>"unklare Spezifikation",6=>"gesperrt");
    $h="";
    switch ($type){
        case "select":{
            foreach ($r as $index => $item) {
                if ($value==$index ){$s="selected";}else{$s="";}
                $h.="<option value=\"".$index."\" $s>".$item."</option>";
            }
            break;
        }
        case "text":{
            foreach ($r as $index => $item) {
                if ($value==$index ){$h=$item;}
            }
            break;
        }
        case "data":{$h=$r; break;}
    }
    return $h;
}
function GetListFidType($type,$value){
    $r=array(
        0=>"bitte auswählen",
        1=>"Abfüllung Silo 1-6",
            2=>"Direktbefüllung in Silo A,B oder E,F",
            3=>"Abziehen aus Silo A,B oder E,F",
            4=>"Abfüllung aus Linie",
            5=>"Siloverladung aus Silo A,B oder E,F"
        );
    $h="";
    switch ($type){
        case "select":{
            foreach ($r as $index => $item) {
                if ($value==$index ){$s="selected";}else{$s="";}
                $h.="<option value=\"".$index."\" $s>".$item."</option>";
            }
            break;
        }
        case "text":{
            foreach ($r as $index => $item) {
                if ($value==$index ){$h=$item;}
            }
            break;
        }
    }
    return $h;
}
function GetListFidStatus($type,$value){
    $r=array(0=>"Neuanlage",1=>"Datenimport Access",2=>"in Arbeit",3=>"in Homogenisierung",4=>"fertig",
            5=>"unklare Spezifikation",6=>"gesperrt",7=>"in Qualitätsprüfung", 8=>"im Lager",9=>"Warenausgang",
            10=>"MFR Prüfung",11=>"Restfeuchteprüfung",12=>"Mechanik 2 Prüfung",13=>"Mechanik 1 Prüfung", 14=>"Farbprüfung", 15=>"Q-Gesamtprüfung",
            20=>"Druckansicht",21=>"Clouddruck erstellt",22=>"E-Mailversand",
        999=>"Inventur"
        );


    $h="";
    switch ($type){
        case "select":{
            foreach ($r as $index => $item) {
                if ($value==$index ){$s="selected";}else{$s="";}
                $h.="<option value=\"".$index."\" $s>".$item."</option>";
            }
            break;
        }
        case "text":{
            foreach ($r as $index => $item) {
                if ($value==$index ){$h=$item;}
            }
            break;
        }
        case "data":{$h=$r; break;}
    }
    return $h;
}

/** QS Functions */
function GetListBeckenType($type,$value){
    $r=array(
        0=>"außer Betrieb",
        1=>"0 %",
        2=>"<=0,5 %",
        3=>"< 1%",

        5=>">1 %"
    );
    $h="";
    switch ($type){
        case "select":{
            foreach ($r as $index => $item) {
                if ($value==$index ){$s="selected";}else{$s="";}
                $h.="<option value=\"".$index."\" $s>".$item."</option>";
            }
            break;
        }
        case "text":{
            foreach ($r as $index => $item) {
                if ($value==$index ){$h=$item;}
            }
            break;
        }
    }
    return $h;
}
function GetListMFRLocation($type,$value){
    $r=array(
        0=>"bitte auswählen",
        1=>"E1",
        2=>"E2",
        5=>"E3",
        6=>"E4",
        99=>"------",
        100=>"Silo A",
        101=>"Silo B",
        102=>"Silo E",
        103=>"Silo F",
        199=>"------",
        200=>"Silo C",
        201=>"Silo D",

    );
    $h="";
    switch ($type){
        case "select":{
            foreach ($r as $index => $item) {
                if ($value==$index ){$s="selected";}else{$s="";}
                $h.="<option value=\"".$index."\" $s>".$item."</option>";
            }
            break;
        }
        case "text":{
            foreach ($r as $index => $item) {
                if ($value==$index ){$h=$item;}
            }
            break;
        }
    }
    return $h;
}
function GetListMeasureLocations($type,$value){
    $r=array(
        1=>"Silo 11 - Extruder 5",
        2=>"Silo 8 - Extruder 2",
        3=>"Silo 10 - Extruder 5",
        4=>"Silo 6 - ZZ Folie",
        5=>"Silo 12 - COREMA"
    );
    $h="";
    switch ($type){
        case "select":{
            foreach ($r as $index => $item) {
                if ($value==$index ){$s="selected";}else{$s="";}
                $h.="<option value=\"".$index."\" $s>".$item."</option>";
            }
            break;
        }
        case "text":{
            foreach ($r as $index => $item) {
                if ($value==$index ){$h=$item;}
            }
            break;
        }
    }
    return $h;
}
function GetListMidType($type,$value){
    $r=array(
        0=>"bitte auswählen",
        1=>"MFR Messung",
        2=>"Restfeuchtemessung",
        3=>"Schlammprüfung",
        4=>"Trennung Becken",
        5=>"Schüttdichtemessung",
        6=>"CSB - Wasseranalytik"
    );
    $h="";
    switch ($type){
        case "select":{
            foreach ($r as $index => $item) {
                if ($value==$index ){$s="selected";}else{$s="";}
                $h.="<option value=\"".$index."\" $s>".$item."</option>";
            }
            break;
        }
        case "text":{
            foreach ($r as $index => $item) {
                if ($value==$index ){$h=$item;}
            }
            break;
        }
    }
    return $h;
}
function GetListMidDevice($type,$value,$midtype){
    $r=array(
        0=>array("MFR Gerät 1",1),
        1=>array("MFR Gerät 2",1),
        2=>array("Restfeuchte Messgerät 1",2),
        3=>array("Restfeuchte Messgerät 1",3),
        4=>array("Sichtprüfung",4),
        5=>array("Waage 1",5),
        6=>array("Wasseranalytik 1",6),
        7=>array("Schlagpendel 1",7),
        8=>array("Universal-Zugprüfung 1",8),
        9=>array("Universal-Farbmessung 1",9),
    );
    $h="";
    switch ($type){
        case "select":{
            foreach ($r as $index => $item) {
                if ($item[1] == $midtype) {
                    if ($value == $index) {
                        $s = "selected";
                    } else {
                        $s = "";
                    }
                    $h .= "<option value=\"" . $index . "\" $s>" . $item[0] . "</option>";
                }
            }
            break;
        }
        case "text":{
            foreach ($r as $index => $item) {
                if ($value==$index ){$h=$item[0];}
            }
            break;
        }
    }
    return $h;
}
function GetListStrangType($type,$value){
    $r=array(
        0=>"bitte auswählen",
        1=>"glatt",
        2=>"rauh"
    );
    $h="";
    switch ($type){
        case "select":{
            foreach ($r as $index => $item) {
                if ($value==$index ){$s="selected";}else{$s="";}
                $h.="<option value=\"".$index."\" $s>".$item."</option>";
            }
            break;
        }
        case "text":{
            foreach ($r as $index => $item) {
                if ($value==$index ){$h=$item;}
            }
            break;
        }
    }
    return $h;
}
function GetListMFIMethode($type,$value){
    $r=array(
        0=>array("bitte auswählen",             1000, -50, +50),
        1=>array("PE -> 190 °C / 5 / 60",       60 , -0.5, +0.5),
        2=>array("PP10 -> 230 °C / 2,16 / 10",  10, -2.5, +2.5),
        3=>array("PP30 -> 230 °C / 2,16 / 5",   5,-5, +5),
        4=>array("PP50 -> 230 °C / 2,16 / 3",   3, -10, 10),
        5=>array("PO -> 230 °C / 2,16 / 20",    20,-2.5, +2.5),
        6=>array("PO -> 190 °C / 5 / 20",       20,-2,5,+2,5),

    );
    $h="";
    switch ($type){
        case "select":{
            foreach ($r as $index => $item) {
                if ($value==$index ){$s="selected";}else{$s="";}
                $h.="<option value=\"".$index."\" $s>".$item[0]."</option>";
            }
            break;
        }
        case "select2":{
            foreach ($r as $index => $item) {
                if ($value==$index ){$s="selected";}else{$s="";}
                $h.="<option value=\"".$index."\" $s>".$item[0]."</option>";
            }
            break;
        }
        case "text":{
            foreach ($r as $index => $item) {
                if ($value==$index ){$h=$item[0];}
            }
            break;
        }
        case "time":{
            foreach ($r as $index => $item) {
                if ($value==$index ){$h=$item[1];}
            }
            break;
        }
        case "offsetplus":{
            foreach ($r as $index => $item) {
                if ($value==$index ){$h=$item[3];}
            }
            break;
        }
        case "offsetminus":{
            foreach ($r as $index => $item) {
                if ($value==$index ){$h=$item[2];}
            }
            break;
        }
    }
    return $h;
}

/** Material Functions */
function GetMaterialName($type,$value){
    $h="";
    switch ($type){
        case "select":{
            include ("../apps/dbconnection.php");
            $sql="SELECT * FROM materialdaten ORDER BY materialdaten.materialtxt ASC";
            $rst=$mysqli->query($sql);
            if (mysqli_affected_rows($mysqli)>0) {
                do {
                    $row_rst = $rst->fetch_assoc();
                    if ($value==$row_rst['idmaterial'] ){$s="selected";}else{$s="";}
                    $h.="<option value=\"".$row_rst['idmaterial']."\" $s>".$row_rst['materialtxt']."</option>";

                } while ($row_rst = $rst->fetch_assoc() );
            }
            break;
        }
        case "select2":{
            include ("../apps/dbconnection.php");
            $sql="SELECT * FROM materialdaten ORDER BY materialdaten.materialtxt ASC";
            $rst=$mysqli->query($sql);
            $ok=false;
            $h.="<option value=\"\">unbekannt</option>";
            if (mysqli_affected_rows($mysqli)>0) {
                do {
                    $row_rst = $rst->fetch_assoc();
                    if ($value==$row_rst['materialtxt'] ){$s="selected";$ok=true;}else{$s="";}
                    $h.="<option value=\"".$row_rst['materialtxt']."\" $s>".$row_rst['materialtxt']."</option>";

                } while ($row_rst = $rst->fetch_assoc() );
                if (!$ok){$s="selected";}else{$s="";}
                $h.="<option value=\"".$value."\" $s>".$value."</option>";
            }
            break;
        }
        case "text":{
            include ("../apps/dbconnection.php");
            $sql="SELECT * FROM materialdaten WHERE materialdaten.idmaterial='$value'  ";
            $rst=$mysqli->query($sql);
            if (mysqli_affected_rows($mysqli)>0){
                $row_rst=$rst->fetch_assoc();
                $h=$row_rst['materialtxt'];
            }
            break;
        }
        case "material":{
            include ("../apps/dbconnection.php");
            $sql="SELECT * FROM materialdaten WHERE materialdaten.material='$value'  ";
            $rst=$mysqli->query($sql);
            if (mysqli_affected_rows($mysqli)>0){
                $row_rst=$rst->fetch_assoc();
                $h=$row_rst['materialtxt'];
            }
            break;
        }
    }
    return $h;
}

/** Message Functions */
function GetMessageStatus($state){
    switch ($state){
        case 0:{
            return "Neuanlage";
            break;
        }
        case 1:{
            return "Aktiviert";
            break;
        }
        case 2:{
            return "Deaktiviert";
            break;
        }
    }
}
function GetListMessageType($type,$value){
    $r=array(0=>"unbekannt",1=>"Unfallmeldung",2=>"Schadenmeldung",3=>"Fremdfirmenanmeldung",4=>"Erlaubnisschein",5=>"Besucheranmeldung");
    $h="";
    switch ($type){
        case "select":{
            foreach ($r as $index => $item) {
                if ($value==$index ){$s="selected";}else{$s="";}
                $h.="<option value=\"".$index."\" $s>".$item."</option>";
            }
            break;
        }
        case "text":{
            foreach ($r as $index => $item) {
                if ($value==$index ){$h=$item;}
            }
            break;
        }
    }
    return $h;
}
function ShowStatusIcon($m1=0,$m2=0,$m3=0,$m4=0,$m5=0,$m6=0){
    $h="";
    if ($m1==1){$h="<i class='zmdi zmdi-truck'>  </i> LKW <br>";}
    if ($m2==1){$h.="<i class='zmdi zmdi-wrench'> </i> Reparatur <br>";}
    if ($m3==1){$h.="<i class='fa fa-external-link'> </i> Stapler <br>";}
    if ($m4==1){$h.="<i class='fa fa-upload'> </i> Hubarbeitsbühne <br>";}
    if ($m5==1){$h.="<i class='fa fa-arrows'> </i> Kran <br>";}
    if ($m6==1){$h.="<i class='fa fa-fire-extinguisher'> </i> Erlaubnisschein <br>";}
    return $h;
}

class ifisNotification{

    function GetNotifications(){
        $h="<li class=\"list-group-item\">
                <a href=\"#\" class=\"user-list-item\">
                   <div class=\"avatar\">
                        <i class=\"fa fa-check-circle\"> </i>
                    </div>
                    <div class=\"user-desc\">
                        <span class=\"name\">%s</span>
                        <span class=\"desc\">%s</span>
                        <span class=\"time\">%s</span>
                    </div>
                </a>
            </li>";

        $e= $_SESSION['e'];
        if (count($e)>0) {
            foreach ($e as $item) {
                if (strlen($item['type'])>0) {
                    $n[] = sprintf($h,$item['header'],$item['text'],$item['type']);
                }
            }
            return implode("\n",$n);
        }else{
            $n="";
        }
    }
    function ClearNotification(){
        $_SESSION['e']=array();
    }
}

class ifisAllUser{
    public $werk=0;
    public $data=array();
    public $users=array();

    function __construct($id=1501)
    {
        if ($id>0){
            $this->werk=$id;
        }
        $this->LoadData();
    }
    function LoadData(){
        include ("../apps/dbconnection.php");
        $limit="";
        $this->data=array();
        if (strlen($_SESSION['datumvon'])>0 && $_SESSION['datumvon']<>"0000-00-00"){ $limit.=" AND mitarbeiter.udatum>='".$_SESSION['datumvon']." 00:00:00'";}
        if (strlen($_SESSION['datumbis'])>0 && $_SESSION['datumbis']<>"0000-00-00"){ $limit.=" AND mitarbeiter.udatum<='".$_SESSION['datumbis']." 23:59:99'";}

        $sql="SELECT * FROM mitarbeiter WHERE mitarbeiter.werk='$this->werk' AND mitarbeiter.lokz=0 $limit";
        $rst=$mysqli->query($sql);
        if (mysqli_affected_rows($mysqli)>0){
            $row_rst = $rst->fetch_assoc();
            do{
                $this->data[]=$row_rst;
            } while ($row_rst = $rst->fetch_assoc());

        }else{
            $this->data=array();
        }
    }

    function LoadUserData($logedUser)
    {
        include("../apps/dbconnection.php");
        $this->users=array();
        $sql="SELECT * FROM mitarbeiter WHERE mitarbeiter.werk='$this->werk' AND mitarbeiter.lokz=0 ORDER BY mitarbeiter.abteilung";
        $rst=$mysqli->query($sql);
        if (mysqli_affected_rows($mysqli)>0){
            $row_rst = $rst->fetch_assoc();
            do{
                $this->users[]=$row_rst;
            } while ($row_rst = $rst->fetch_assoc());

        }else{
            $this->users=array();
        }
    }

    function LoadLastLoggedUserData()
    {
        include("../apps/dbconnection.php");
        $this->users=array();
        $sql="SELECT MAX( lastuserlogin.datum ) as lastlogin, mitarbeiter.userid, mitarbeiter.name, mitarbeiter.abteilung, mitarbeiter.email 
          FROM mitarbeiter, lastuserlogin 
          WHERE mitarbeiter.werk='$this->werk' AND mitarbeiter.lokz=0 
          AND mitarbeiter.userid=lastuserlogin.userid
          GROUP BY mitarbeiter.userid
          ORDER BY lastlogin DESC 
          LIMIT 0, 15";
        $rst=$mysqli->query($sql);
        if (mysqli_affected_rows($mysqli)>0){
            $row_rst = $rst->fetch_assoc();
            do{
                $this->users[]=$row_rst;
            } while ($row_rst = $rst->fetch_assoc());

        }else{
            $this->users=array();
        }
    }
    function GetChangeUserModals(){
        $h="";
        if (count($this->users)>0) {
            foreach ($this->users as $i=>$user) {

                $h .= "<div id=\"user-width-modal" . $user['userid'] . "\" class=\"modal fade\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"user-width-modalLabel\" aria-hidden=\"true\" style=\"display: none;\">
                <div class=\"modal-dialog\" >
                    <div class=\"modal-content\">
                   
                        <div class=\"modal-header\">
                            <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">×</button>
                            <h4 class=\"modal-title\" id=\"custom-width-modalLabel\">Passworteingabe für  " . $user['name'] . "</h4>
                        </div>
                        <div class=\"modal-body\">
   
                            <form name=\"Sender\"  method=\"post\"  class=\"form-horizontal\" role=\"form\" data-parsley-validate novalidate>
                 
                                <div >
                                <label for=\"email\" class=\"col-sm-5 control-label\">Username (E-Mailadresse)*</label>". $user['email']."
                                
                                    <input type=\"hidden\" required parsley-type=\"email\" name=\"email\" id=\"email\" placeholder=\"Email\" value=\"". $user['email']."\">
                                </div>
                                
                                <hr>
                                 <div >
                                    <label for=\"password\" class=\"col-sm-5 control-label\">Password*</label>
                                
                                    <input type=\"password\" required  name=\"password\" id=\"password\" placeholder=\"*******\" value=\"\">
                                </div>
                               <br><br>
                                <input type=\"submit\" name=\"login\" id=\"login\" class=\"btn btn-primary\" value=\"Anmelden\">
                                
                               </div>
                        <div class=\"modal-footer\">
                               
                            
                        
                        <button type=\"button\" class=\"btn btn-default waves-effect\" data-dismiss=\"modal\">Abbrechen</button>
                       
                          
                          </form>
                        </div>
                      
                    </div><!-- /.modal-content -->
                    
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->";
            }}
        return $h;
    }

    function GetChangeUserList(){

        if (count($this->users)>0) {
            $h = "<table id=\"userlist\" class=\"table table-striped\" cellspacing=\"0\" width=\"100%\">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Abteilung</th>
                                            </tr>
                                        </thead><tbody>";
            foreach ($this->users as $i=>$user) {
                $h.="<tr><td>";
                $h.=$user['name']." <a href=\"#user-width-modal".$user['userid']."\" class=\"btn btn-custom btn-xs\" data-toggle=\"modal\" data-target=\"#user-width-modal".$user['userid']."\">auswählen</a></td><td>";
                $h.=$user['email']."</td><td>";
                $h.=$user['abteilung']."</td><td>";

                $h.="</td></tr>";
            }

            $h .= "</tbody></table>";
        }else{
            $h="keine freigebenen Benutzer gefunden.";
        }
        return $h;
    }

    function CheckUsername($name){
        include ("../apps/dbconnection.php");
        $sql="SELECT * FROM mitarbeiter WHERE mitarbeiter.email='$name' ";
        $rst=$mysqli->query($sql);
        if (mysqli_affected_rows($mysqli)>0){
            return false;
        }else{
            return true;
        }
    }
    function GetListUsers($type,$value){
        $h="";
        switch ($type){
            case "select":{
                foreach ($this->data as $index => $item) {
                    if ($value==$index){$s="selected";}else{$s="";}
                    $h.="<option value=\"".$index."\" $s>".$item['name']."</option>";

                }
                break;
            }
            case "name":{
                foreach ($this->data as $index => $item) {
                    if ($value==$index){
                        $h=$item['name'];
                    }

                }
                break;
            }
        }
        return $h;
    }
    function GetJsonUserslist(){
        $this->LoadData();
        if (count($this->data)>0){
            foreach ($this->data as $item){
                $r['aaData'][]=array(
                    ($item['userid']),
                    utf8_encode($item['name']),
                    utf8_encode($item['abteilung']),
                    ($item['anschrift']),
                    ($item['ort']),
                    GetUserStatus($item['ustate']),
                    $item['email'],
                    $this->GetDatum($item['datumg25']),
                    $this->GetDatum($item['datumstapler']),
                    $this->GetDatum($item['datumhub']),
                    $this->GetDatum($item['datumkran']),
                    $this->GetNextVorsorge($item['datumg25']),
                    );
            }
        } else{
            $r['aaData']=array();
        }

        return json_encode($r);
    }
    function GetDatum($value){
        if ($value=="0000-00-00"){ $value="n.v.";}
        return $value;
    }

    function GetNextVorsorge($value){
        if ($value=="0000-00-00"){ $value="n.v.";} else {
            if (strtotime($value) > 0) {
                $value = date('Y-m-d', strtotime($value) + (2 * 365 * 24 * 60 * 60));
            }
        }
        return $value;
    }

}
class ifisUser{
    public $userid=0;
    public $data=array();
    public $history=array();

    function __construct($id=0)
    {
        if ($id>0){
            $this->userid=$id;
        }
        $this->LoadData();
        $this->LoadHistory();
    }


    function GetUsername(){
        return $this->data['name'];
    }

    function GetAnrede(){
        if ($this->data['anrede']=="") $this->data['anrede']="Herr";
        return $this->data['anrede'];
    }

    function LoadData(){
        include ("../apps/dbconnection.php");
        $sql="SELECT * FROM mitarbeiter WHERE mitarbeiter.userid='$this->userid' ";
        $rst=$mysqli->query($sql);
        if (mysqli_affected_rows($mysqli)>0){
            $this->data=$rst->fetch_assoc();
            $this->LoadHistory();
        }else{
            $this->data=array();
        }
    }
    function LoadDataByShortName($s){
        include ("../apps/dbconnection.php");
        $sql="SELECT * FROM mitarbeiter WHERE mitarbeiter.shortname='$s' LIMIT 1";
        $rst=$mysqli->query($sql);
        if (mysqli_affected_rows($mysqli)>0){
            $this->data=$rst->fetch_assoc();
            $this->userid=$this->data['userid'];

            return $this->userid;
        }else{
            $this->data=array();
            return 0;
        }
    }

    function CheckUserLogin($email,$password){
        include ("../apps/dbconnection.php");

        if (strstr($email,"albaservices.com")){
            $sql="SELECT * FROM mitarbeiter WHERE mitarbeiter.email2='".$email."' AND mitarbeiter.password='".$password."' LIMIT 0,1 ";
        }else{
            $sql="SELECT * FROM mitarbeiter WHERE mitarbeiter.email='".$email."' AND mitarbeiter.password='".$password."' LIMIT 0,1 ";
        }
        $rst=$mysqli->query($sql);
        if (mysqli_affected_rows($mysqli)==1){
            $this->data=$rst->fetch_assoc();
            $this->userid=$this->data['userid'];
            return true;
        }else{
            $this->userid=0;
            return false;
        }
    }
    function GetUserID(){
        return $this->userid;
    }

    function LoadDataByEmail($email){
        include ("../apps/dbconnection.php");
        $sql="SELECT * FROM mitarbeiter WHERE mitarbeiter.email='".GetSQLValueString($email,"text")."' ";
        $rst=$mysqli->query($sql);
        if (mysqli_affected_rows($mysqli)==1){
            $this->data=$rst->fetch_assoc();
            $this->LoadHistory();
            $this->userid=$this->data['userid'];
            return true;
        }else{
            return false;
        }
    }
    function LoadHistory($wstate=""){
        include ("../apps/dbconnection.php");
        if ($wstate=="") {
            $sql = "SELECT * FROM mitarbeiterworkflow WHERE mitarbeiterworkflow.lokz='0' 
              AND mitarbeiterworkflow.userid='" . $this->userid . "' ORDER BY mitarbeiterworkflow.wdatum DESC LIMIT 0,100";
        }else{
            $sql = "SELECT * FROM mitarbeiterworkflow WHERE mitarbeiterworkflow.lokz='0' 
              AND mitarbeiterworkflow.userid='" . $this->userid . "' AND mitarbeiterworkflow.wstate='" . $wstate . "'
             ORDER BY mitarbeiterworkflow.wdatum DESC LIMIT 0,100";

        }
        $this->history=array();
        $rst=$mysqli->query($sql);
        if ($mysqli->affected_rows>0) {
            $row_rst = $rst->fetch_assoc();
            $this->data['currentstate']=GetListUserStatus("text",$row_rst['wstate']);
            do{
                $this->history[]=$row_rst;
            } while ($row_rst = $rst->fetch_assoc());
            return $mysqli->affected_rows;
        }else{
            $this->history=array();
            return 0;
        }
    }

    function SaveLogin(){
        if ($this->userid>0) {
            include("../apps/dbconnection.php");
            $updateSQL = sprintf("
                 INSERT INTO  lastuserlogin (userid, datum) VALUES (%s, %s)",
                $this->userid, "now()" );
            $rst = $mysqli->query($updateSQL);
            if ($mysqli->error) {
                $_SESSION['x'].=$updateSQL;

                return 0;
            }else{
                // $this->ChangeLog("Login erfolgreich",9);
                return 1;
            }
        }
        return 0;
    }

    function SaveData($POST){
        if (isset($POST['userid']) && $POST['userid']>0){
            $this->userid=$POST['userid'];
            include ("../apps/dbconnection.php");

            $this->data['name']=utf8_decode($POST['name']);
            $this->data['email']=$POST['email'];
            $this->data['email2']=$POST['email2'];
            $this->data['password']=utf8_decode($POST['password']);
            $this->data['abteilung']=utf8_decode($POST['abteilung']);
            $this->data['anschrift']=utf8_decode($POST['anschrift']);
            $this->data['plz']=utf8_decode($POST['plz']);
            $this->data['ort']=utf8_decode($POST['ort']);
            $this->data['land']=utf8_decode($POST['land']);
            $this->data['telefon']=utf8_decode($POST['telefon']);
            $this->data['fax']=utf8_decode($POST['fax']);

            if ($this->data['datumg25']=="0000-00-00" && strtotime($POST['datumg25'])>0){
                $this->ChangeLog("Erste Eignungsprüfung G25",2,"",($POST['datumg25']));
            }
            $this->data['datumg25']=utf8_decode($POST['datumg25']);

            if ($this->data['datumstapler']=="0000-00-00" && strtotime($POST['datumstapler'])>0){
                $this->ChangeLog("Erste Staplerscheinbefähigung",3,"",($POST['datumstapler']));
            }
            $this->data['datumstapler']=utf8_decode($POST['datumstapler']);

            if ($this->data['datumhub']=="0000-00-00" && strtotime($POST['datumhub'])>0){
                $this->ChangeLog("Erste Hubarbeitsbühnenbefähigung",4,"",($POST['datumhub']));
            }

            $this->data['datumhub']=utf8_decode($POST['datumhub']);
            if ($this->data['datumkran']=="0000-00-00" && (strtotime($POST['datumkran']))>0){

                $this->ChangeLog("Erste Krananlagenbefähigung",5,"",($POST['datumkran']));
            }
            $this->data['datumkran']=utf8_decode($POST['datumkran']);


            $updateSQL = sprintf("
                UPDATE mitarbeiter SET `name` =%s , email=%s, email2=%s, password=%s, abteilung=%s, anschrift=%s, 
                plz=%s, ort=%s, land=%s, telefon=%s, fax=%s,
                datumg25=%s, datumstapler=%s, datumhub=%s, datumkran=%s
                WHERE userid=%s",
                GetSQLValueString($this->data['name'],"text"),
                GetSQLValueString($this->data['email'],"text"),
                GetSQLValueString($this->data['email2'],"text"),
                GetSQLValueString($this->data['password'],"text"),
                GetSQLValueString($this->data['abteilung'],"text"),
                GetSQLValueString($this->data['anschrift'],"text"),
                GetSQLValueString($this->data['plz'],"text"),
                GetSQLValueString($this->data['ort'],"text"),
                GetSQLValueString($this->data['land'],"text"),
                GetSQLValueString($this->data['telefon'],"text"),
                GetSQLValueString($this->data['fax'],"text"),

                GetSQLValueString($this->data['datumg25'],"text"),
                GetSQLValueString($this->data['datumstapler'],"text"),
                GetSQLValueString($this->data['datumhub'],"text"),
                GetSQLValueString($this->data['datumkran'],"text"),
                $this->userid);
            $rst = $mysqli->query($updateSQL);
            if ($mysqli->error) {
                $_SESSION['x'].=$updateSQL;
                AddSessionMessage("error","Änderung wurde nicht gespeichert, da ein Fehler aufgetreten ist.","Fehlermeldung");
            }else{
                AddSessionMessage("success", "Mitarbeiteränderung  wurde gespeichert.", "Speichern");
                $this->ChangeLog("Stammdaten geändert ".strtotime("now()"),7);
            }
        }
    }
    function ChangeUserRight($POST){
        if (isset($POST['userid']) && $POST['userid']>0){
            $this->userid=$POST['userid'];
            include ("../apps/dbconnection.php");
            $this->data['userright']=$POST['userright'];
            $updateSQL = sprintf("
                UPDATE mitarbeiter SET `userright` =%s 
                WHERE userid=%s",
                GetSQLValueString($this->data['userright'],"int"),
                $this->userid);
            $rst = $mysqli->query($updateSQL);
            if ($mysqli->error) {
                $_SESSION['x'].=$updateSQL;
                AddSessionMessage("error","Änderung wurde nicht gespeichert, da ein Fehler aufgetreten ist.","Fehlermeldung");
            }else{
                AddSessionMessage("success", "Benutzerrecht  wurde gespeichert.", "Speichern");
                $this->ChangeLog("Berechtigung auf (".$this->data['userright'].") geändert",10);
            }
        }
    }

    function UpdateEmail2(){
        include ("../apps/dbconnection.php");
        $this->data['email2']=substr($this->data['email'],0,strlen($this->data['email'])-5)."services.com";
        $this->data['userright']=1;
        $updateSQL = sprintf("
        UPDATE mitarbeiter SET 
        `email2` =%s
        WHERE userid=%s",

            GetSQLValueString($this->data['email2'],"text"),
        $this->userid);
        $mysqli->query($updateSQL);
        $this->ChangeLog("Carve Out Alias auf (".$this->data['email2'].") geändert",7);

    }

    function SendUserPassword(){
        if ($this->userid>0){
            $text="Sie haben über die Einstellungen das Passwort Ihres Zuganges angefordert.";
            mail($this->data['email'], "ALBAFIS Zugangspasswort  ".$_SERVER['REMOTE_HOST']." ".$this->data['email']." ", " ".date("Y-m-d - H:i ",time()).
                "\nE-Mail: ".$this->data['email']."\n ".$text.
                "\nIPv4: ".$_POST["hipv4"]."-".$_SERVER["REMOTE_ADDR"]." - ".$_SERVER['HTTP_X_FORWARDED_FOR'].
                "\nBKS:".$_REQUEST['bks'].
                "\nUSER:".$this->data['name'].
                "\nPASSWORD:".$this->data['password'].
                "\nSESS:".$_SESSION['sess']);

            AddSessionMessage("success", "E-Mail mit Passwortanforderung wurde gesendet","Info" );
            $this->ChangeLog("E-Mail mit Passwortanforderung wurde gesendet",6);
        }else{
            AddSessionMessage("error","E-Mail konnte nicht gesendet werden","Abbruch");
        }
    }
    function InsertData($POST){
        if (strlen($POST['email'])>=1){
            include ("../apps/dbconnection.php");
            $updateSQL = sprintf("
                INSERT INTO  mitarbeiter (`name`,email,password, abteilung, udatum, userright, werk) VALUES (%s, %s,%s,%s, %s,%s,%s)",
                GetSQLValueString(utf8_decode($POST['name']), "text"),
                GetSQLValueString(utf8_decode($POST['email']), "text"),
                GetSQLValueString(utf8_decode($POST['password']), "text"),
                GetSQLValueString(utf8_decode($POST['abteilung']), "text"),

                "now()",
                "0",
                GetSQLValueString($_SESSION['classifiedDirectoryID'], "int")
            );
            $rst = $mysqli->query($updateSQL);
            if ($mysqli->error) {
                $_SESSION['x'].=$updateSQL;
                AddSessionMessage("warning","Mitarbeiter wurde nicht angelegt, da ein Fehler aufgetreten ist.","Fehlermeldung");
            }else{
                AddSessionMessage("success", "Mitarbeiter <code> " . $mysqli->insert_id . "</code> wurde gespeichert.", "Neu anlegen");
                $this->ChangeLog("Neuanlage",0,$mysqli->insert_id);
            }
        }else{
            AddSessionMessage("error","Mitarbeiter wurde nicht angelegt.","Fehlermeldung");
        }
    }

    function LoadUploadData(){
        $folder="../tmpupload/".$_SESSION['userid'].'/';
        $files=glob($folder."*.*");
        if (count($files)>0){
        foreach ($files as $file){
            $r[]=array( 'name'=>$file,
                        'size'=>145,
                        'file'=>$file
            );
        }
        }else{
            $r=array();
        }
        return "files:".json_encode($r);
    }

    function ChangeLog($notes,$state=1, $userid="",$datum="",$user=""){
        if ($userid==""){ $userid=$this->userid;}

        if (strlen($notes)>=1){

            include ("../apps/dbconnection.php");

            if ($datum==""){$datum="now()";} else{ $datum=GetSQLValueString($datum,"text");}
            if ($user==""){$user=$_SESSION['userid'];}
            $us=new ifisUser($user);

            $updateSQL = sprintf("
                INSERT INTO mitarbeiterworkflow (wnotes, wstate, wdatum, wuser, username, werk, userid) VALUES (%s,%s,%s,%s,%s,%s,%s)",
                GetSQLValueString(utf8_decode($notes), "text"),
                GetSQLValueString($state, "int"),
                $datum,
                GetSQLValueString($user, "int"),
                GetSQLValueString(utf8_decode($us->data['name']), "text"),
                GetSQLValueString($_SESSION['classifiedDirectoryID'], "int"),
                GetSQLValueString($userid, "int")
            );
            $rst = $mysqli->query($updateSQL);
            if ($mysqli->error) {
                $_SESSION['x'].=$updateSQL;
                AddSessionMessage("warning","Status wurde nicht angelegt, da ein Fehler aufgetreten ist.","Fehlermeldung");
            }else{
                AddSessionMessage("success", "Status <code> " . $mysqli->insert_id . "</code> wurde gespeichert.", "Änderung speichern");
            }
        }

    }
    function GetHistoryAsTableBody(){
        $h="<tbody>";
        if (count($this->history)>0){
            foreach($this->history as $item){
                $h.="<tr><td>".substr($item['wdatum'],0,16)."</td>";
                $h.="<td>".$item['wnotes']."</td>";
                $h.="<td>".GetListUserStatus("text",$item['wstate'])."</td>";
                $h.="<td>".$item['username']."</td></tr>";
            }
            $h.="</tbody>";

        }else{
            $h.="<tr><td colspan='4'>keine Verlaufinformationen vorhanden</td></tr>";
            $h.="</tbody>";
        }
        return $h;
    }


    function GetDatum($wstate){
        $this->LoadHistory($wstate);
        if (count($this->history)>0){
            return substr($this->history[0]['wdatum'],0,10);
        }else{
            return false;
        }
    }

    function GetCurrentState(){
        if (is_null($this->data['currentstate'])){
            return "kein Status vorhanden";
        }else{
            return $this->data['currentstate'];
        }
    }
}

class ifisAllWareneingang{

    private $werk=0;
    public $data=array();

    function __construct($werk=0)
    {
        if ($werk>0){
            $this->werk=$werk;
        }
        $this->LoadData();
    }
    function LoadData($date=""){
        $this->data=array();
        include ("../apps/dbconnection.php");
        $limit="";
        if (strlen($_SESSION['datumvon'])>0 && $_SESSION['datumvon']<>"0000-00-00"){ $limit.=" AND wareneingang.wdatum>='".$_SESSION['datumvon']." 00:00:00'";}
        if (strlen($_SESSION['datumbis'])>0 && $_SESSION['datumbis']<>"0000-00-00"){ $limit.=" AND wareneingang.wdatum<='".$_SESSION['datumbis']." 23:59:99'";}

        if (strlen($date)>8){
            $limit="AND wareneingang.wdatum>='".$date." 00:00:00' AND  wareneingang.wdatum<='".$date." 23:59:99' ";
        }

        $sql="SELECT * FROM wareneingang WHERE wareneingang.wlokz='0' ".$limit. " ORDER BY wareneingang.wdatum ASC LIMIT 0,1000";
        $rst=$mysqli->query($sql);
        if (mysqli_num_rows($rst) > 0) {
            $row_rst = $rst->fetch_assoc();
            do{
                $this->data[]=$row_rst;
            } while ($row_rst = $rst->fetch_assoc());

        }else{
            $this->data=array();
        }
    }
    function ReportCountWareneingange($duration=1){
        include ("../apps/dbconnection.php");
        $limit="";
        $time1=date ('Y-m-d',time());
        $time2=date ('Y-m-d',time()-( 24*60*60*$duration) );

        if (strlen($time2)>0 && $time2<>"0000-00-00"){ $limit.=" AND wareneingang.wdatum>='".$time2." 00:00:00'";}
        if (strlen($time1)>0 && $time1<>"0000-00-00"){ $limit.=" AND wareneingang.wdatum<='".$time1." 23:59:99'";}


        $sql="SELECT COUNT(wareneingang.wid) as anzahl FROM wareneingang WHERE wareneingang.wlokz='0' ".$limit. " ";
        $rst=$mysqli->query($sql);
        if (mysqli_num_rows($rst) > 0) {
            $row_rst = $rst->fetch_assoc();
            return $row_rst['anzahl'];

        }else{
            return 0;
        }
    }
    function GetJsonWareneingangslist(){

        if (count($this->data)>0){
            foreach ($this->data as $item){
                //"ID","Datum","Beschreibung","Lieferant","Abladeort","Status"
                $w= new ifisWareneingang( $item['wid']);
                $r['aaData'][]=array(
                    $item['wid'],
                    substr($item['wdatum'],0,16),
                    $item['wdesc'],
                    $item['wcustomer'],
                    $item['wlocation'],
                    $w->GetCurrentState(),
                    $item['wemail']);
            }
        } else{
            $r['aaData']=array();
        }

        return json_encode($r);
    }
    function GetCountWareneingangToday(){
        $this->LoadData( date("Y-m-d",time()) );
        if (count($this->data)>0){
            $h="<span class=\"label label-default pull-right\">".count($this->data)."</span> ";
            AddSessionMessage("info","Wareneingänge von heute geladen","Datenbankabfrage");
        }else{
            $h="";
        }
        return $h;
    }

}
class ifisWareneingang{
    public $wid=0;
    public $data=array();
    public $history=array();
    public $documents=array();
    function __construct($id=0)
    {
        if ($id>0){
            $this->wid=$id;
        }
        $this->LoadData();
    }
    function LoadData(){
        include ("../apps/dbconnection.php");
        $sql="SELECT * FROM wareneingang WHERE wareneingang.wid='$this->wid'  ";
        $rst=$mysqli->query($sql);
        if (mysqli_affected_rows($mysqli)>0){
            $this->data=$rst->fetch_assoc();
            $this->LoadHistory();
            $this->LoadDocumentData();
        }else{
            $this->data=array();
        }
    }
    function LoadHistory(){
        include ("../apps/dbconnection.php");
        $sql="SELECT * FROM wareneingangworkflow WHERE wareneingangworkflow.lokz='0' AND wareneingangworkflow.wid='".$this->wid."' ORDER BY wareneingangworkflow.wdatum DESC LIMIT 0,1000";
        $rst=$mysqli->query($sql);
        if (mysqli_num_rows($rst) > 0) {
            $row_rst = $rst->fetch_assoc();
            $this->data['currentstate']=$row_rst['wnotes'];
            do{
                $this->history[]=$row_rst;
            } while ($row_rst = $rst->fetch_assoc());

        }else{
            $this->history=array();
        }
    }
    function SaveData($POST){
        if (isset($POST['wid']) && $POST['wid']>0){
            $this->wid=$POST['wid'];
            include ("../apps/dbconnection.php");

            $this->data['wdesc']=utf8_decode($POST['wdesc']);
            $this->data['wcustomer']=utf8_decode($POST['wcustomer']);
            $this->data['wemail']=$POST['wemail'];
            $this->data['wlocation']=utf8_decode($POST['wlocation']);
            $this->data['wdelivery']=utf8_decode($POST['wdelivery']);

            $this->data['wdefective']=$POST['wdefective'];
            $this->data['wnotes']=utf8_decode($POST['wnotes']);


            $updateSQL = sprintf("
                UPDATE wareneingang SET wdesc=%s, wemail=%s, wcustomer=%s, wlocation=%s, wdelivery=%s, wdefective=%s, wnotes=%s
                WHERE wid=%s",
                GetSQLValueString($this->data['wdesc'],"text"),
                GetSQLValueString($this->data['wemail'],"text"),
                GetSQLValueString($this->data['wcustomer'],"text"),
                GetSQLValueString($this->data['wlocation'],"text"),
                GetSQLValueString($this->data['wdelivery'],"text"),
                GetSQLValueString($this->data['wdefective'],"text"),
                GetSQLValueString($this->data['wnotes'],"text"),

                $this->wid);
            $rst = $mysqli->query($updateSQL);
            if ($mysqli->error) {
                $_SESSION['x'].=$updateSQL;
                AddSessionMessage("danger","Änderung wurde nicht angelegt, da ein Fehler aufgetreten ist.","Fehlermeldung");
            }else{
                AddSessionMessage("success", "Wareneingang wurde gespeichert.", "Speichern");
                $this->ChangeLog("Änderung",1);
                $this->AddNewDocument('Zustand bei Wareneingang');
            }
        }
    }
    function InsertData($POST){
        if (strlen($POST['wdesc'])>=1){
            include ("../apps/dbconnection.php");
            $updateSQL = sprintf("
                INSERT INTO  wareneingang (`wdesc`,wemail,wcustomer, wdelivery, wlocation, wdefective, wnotes, wdatum, wuser, werk) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
                GetSQLValueString(utf8_decode($POST['wdesc']), "text"),
                GetSQLValueString(utf8_decode($POST['wemail']), "text"),
                GetSQLValueString(utf8_decode($POST['wcustomer']), "text"),
                GetSQLValueString(utf8_decode($POST['wdelivery']), "text"),
                GetSQLValueString(utf8_decode($POST['wlocation']), "text"),
                GetSQLValueString($this->data['wdefective'],"text"),
                GetSQLValueString($this->data['wnotes'],"text"),
                "now()",
                GetSQLValueString($_SESSION['userid'], "int"),
                GetSQLValueString($_SESSION['classifiedDirectoryID'], "int")
            );
            $rst = $mysqli->query($updateSQL);
            if ($mysqli->error) {
                $_SESSION['x'].=$updateSQL;
                AddSessionMessage("warning","Wareneingang wurde nicht angelegt, da ein Fehler aufgetreten ist.","Fehlermeldung");
            }else{
                AddSessionMessage("success", "Wareneingang <code> " . $mysqli->insert_id . "</code> wurde gespeichert.", "Neu anlegen");
            $this->ChangeLog("Neuanlage",0,$mysqli->insert_id);
                $this->wid=$mysqli->insert_id;
                $this->AddNewDocument('Zustand bei Wareneingang');
            }
        }else{
            AddSessionMessage("error","Wareneingang wurde nicht angelegt.","Fehlermeldung");
        }
    }
    function SendEmail($mode="callback"){
        $text = "Die Sendung ist im Wareneingang hinterlegt.";
        if ($mode=="callback"){
            return ("Sie haben eine Sendung im Wareneingang von " . $this->data['wcustomer']. " erhalten.".chr(10).
                    " " . date("Y-m-d - H:i ", time()) .
                    "\n " . $text .
                    "\nSendungdetails:" . $this->data['wdesc'] .

                    "\nAblageort:" . $this->data['wlocation'] .
                    "\nSpedition:" . $this->data['wdelivery'] .
                    "\nIPv4: " . $_POST["hipv4"] . "-" . $_SERVER["REMOTE_ADDR"] . " - " . $_SERVER['HTTP_X_FORWARDED_FOR']);
        }else {
            if (strlen(($this->data['wemail'])) > 4) {

                mail(($this->data['wemail']),
                    "ALBA Wareneingang " . $this->data['wcustomer'],
                    " " . date("Y-m-d - H:i ", time()) .
                    "\nHallo" . "\n " . $text .
                    "\nSendungdetails:" . $this->data['wdesc'] .

                    "\nAblageort:" . $this->data['wlocation'] .
                    "\nSpedition:" . $this->data['wdelivery'] .
                    "\nIPv4: " . $_POST["hipv4"] . "-" . $_SERVER["REMOTE_ADDR"] . " - " . $_SERVER['HTTP_X_FORWARDED_FOR']);

                AddSessionMessage("success", "E-Mail mit Wareneingangsnachricht an <code> " . $this->data['wemail'] . " </code> wurde gesendet", "Info");
                $this->ChangeLog("E-Mailversand an " . $this->data['wemail'], 1);
            } else {
                AddSessionMessage("error", "E-Mail konnte nicht gesendet werden, da keine E-Mail-Adresse für <code> WID" . $this->wid . " " . $this->data['wemail'] . " </code> vorhanden ist.", "Abbruch");
            }
        }
    }
    function ChangeLog($notes,$state=1, $id=""){
        if ($id==""){ $id=$this->wid;}
        /*
         * 0=neuanlage
         * 1=info
         * 2=warnung
         * 3=danger
         */
        // wareneingangworkflow
        if (strlen($notes)>=1){
            $us=new ifisUser($_SESSION['userid']);
            include ("../apps/dbconnection.php");
            $updateSQL = sprintf("
                INSERT INTO  wareneingangworkflow (wnotes, wstate, wdatum, wuser, username, werk, wid) VALUES (%s,%s,%s,%s,%s,%s,%s)",
                GetSQLValueString(utf8_decode($notes), "text"),
                GetSQLValueString($state, "int"),
                "now()",
                GetSQLValueString($_SESSION['userid'], "int"),
                GetSQLValueString(utf8_decode($us->data['name']), "text"),
                GetSQLValueString($_SESSION['classifiedDirectoryID'], "int"),
                GetSQLValueString($id, "int")
            );
            $rst = $mysqli->query($updateSQL);
            if ($mysqli->error) {
                $_SESSION['x'].=$updateSQL;
                AddSessionMessage("warning","Status wurde nicht angelegt, da ein Fehler aufgetreten ist.","Fehlermeldung");
            }else{
                AddSessionMessage("success", "Status <code> " . $mysqli->insert_id . "</code> wurde gespeichert.", "Neu anlegen");
            }
        }

    }
    function GetHistoryAsTableBody(){
        $h="<tbody>";
        if (count($this->history)>0){
            foreach($this->history as $item){
                $h.="<tr><td>".substr($item['wdatum'],0,16)."</td>";
                $h.="<td>".$item['wnotes']."</td>";
                $h.="<td>".GetStatus($item['wstate'])."</td>";
                $h.="<td>".$item['username']."</td></td>";
            }
            $h.="</tbody>";

        }else{
            $h.="<tr><td colspan='4'>keine Verlaufinformationen vorhanden</td></tr>";
            $h.="</tbody>";
        }
        return $h;
    }
    function GetCurrentState(){
        if (is_null($this->data['currentstate'])){
            return "Neuanlage";
        }else{
            return $this->data['currentstate'];
        }
    }

    function AddNewDocument($notes=" "){
        include("../apps/dbconnection.php");
        if (strlen($notes)>=1){
            $us=new ifisUser($_SESSION['userid']);
            include ("../apps/dbconnection.php");

            $state=1;
            $folder="../tmpupload/";
            $files = glob($folder."*.*");
            foreach($files as $value){

                $file = $value;
                // AddSessionMessage("success", "Dokument  <code> " . $file. "</code> wurde hochgeladen und gespeichert.", "Dokumentupload");
                $filename=str_replace($folder,"../wareneingang/".time(),$file);

                if(file_exists($folder.$file)){
                    rename($file,$filename);
                    $updateSQL = sprintf("
                        INSERT INTO  dokumente (dnotes, dstate, ddatum, duser, username, werk, linkedid, dobject, documentlink) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s)",
                        GetSQLValueString(utf8_decode($notes), "text"),
                        GetSQLValueString($state, "int"),
                        "now()",
                        GetSQLValueString($_SESSION['userid'], "int"),
                        GetSQLValueString(utf8_decode($us->data['name']), "text"),
                        GetSQLValueString($_SESSION['classifiedDirectoryID'], "int"),
                        GetSQLValueString($this->wid, "int"),
                        GetSQLValueString("wareneingang", "text"),
                        GetSQLValueString($filename, "text")
                    );
                    $rst = $mysqli->query($updateSQL);

                    if ($mysqli->error) {
                        $_SESSION['x'].=$updateSQL;
                        AddSessionMessage("warning","Dokument wurde nicht angelegt, da ein Fehler aufgetreten ist.","Fehlermeldung");
                    }else{
                        AddSessionMessage("success", "Dokument  <code> " . $filename. "</code> wurde hochgeladen und gespeichert.", "Dokumentupload");
                        $this->ChangeLog("Dokument $filename wurde hochgeladen",1);
                    }
                }
            }
        }

    }
    function LoadDocumentData(){
        if ($this->wid > 0){
            include("../apps/dbconnection.php");
            $updateSQL="SELECT * FROM dokumente
                        WHERE dokumente.linkedid='".$this->wid."' AND dokumente.dobject='wareneingang'
                        ORDER BY dokumente.ddatum ASC";

            $mysqli = new mysqli($hostname_datenbank, $username_datenbank, $password_datenbank, $database_datenbank);
            $rst=$mysqli->query($updateSQL);
            if ($mysqli->error) {
                AddSessionMessage("warning","Dokumente wurden nicht geladen, da ein Fehler aufgetreten ist.","Fehlermeldung");
            }else{
                if ($mysqli->affected_rows>0) {
                    $this->documents=array();
                    $row_rst = $rst->fetch_assoc();
                    do{
                        $this->documents[]= $row_rst;
                    }while ($row_rst=$rst->fetch_assoc());
                }
            }
            $mysqli->close();
        }
    }

    function GetDocumentList(){
        $h="<tbody>";
        if (count($this->documents)>0){
            foreach($this->documents as $item){
                $h.="<tr><td>".substr($item['ddatum'],0,16)."</td>";
                $h.="<td><img src=' ".$item['documentlink']."' width='145'></td>";
                $h.="<td>".$item['dnotes']."</td>";
                $h.="<td>".GetStatus($item['dstate'])."</td>";
                $h.="<td>".$item['username']."</td></tr>";
            }
            $h.="</tbody>";

        }else{
            $h.="<tr><td colspan='5'>keine Dokumentinformationen vorhanden</td></tr>";
            $h.="</tbody>";
        }
        return $h;
    }
    function UpdatePicture($POST){
        if (count($this->documents)>0){
            include('../apps/dbconnection.php');
            foreach ($this->documents as $i=>$row) {
                if (empty($POST['editdeletepic_'.$i.$this->wid])){
                    $updateSQL = sprintf("UPDATE dokumente SET dnotes=%s WHERE did=%s",
                        GetSQLValueString($POST['editpicnotes_'.$i.$this->wid],"text"),
                        $row['did']);
                    $mysqli = new mysqli($hostname_datenbank, $username_datenbank, $password_datenbank, $database_datenbank);
                    $mysqli->query($updateSQL);
                    if ($mysqli->error) {
                        $_SESSION['x'].=$updateSQL;
                        AddSessionMessage("warning","Dokumente wurde nicht gespeichert, da ein Fehler aufgetreten ist.","Fehlermeldung");
                    }else{
                        AddSessionMessage("success", "Dokument  <code> " . $row['did']. "</code> wurde gespeichert.", "Dokumentverwaltung");
                    }
                }
            }
        }
    }
    function DeletePicture($POST){
        if (count($this->documents)>0){
            include('../apps/dbconnection.php');
            foreach ($this->documents as $i=>$row) {
                if (!empty($POST['editdeletepic_'.$i.$this->waid])){
                    $updateSQL = sprintf("DELETE FROM dokumente WHERE did=%s",$row['did']);
                    $mysqli = new mysqli($hostname_datenbank, $username_datenbank, $password_datenbank, $database_datenbank);
                    $mysqli->query($updateSQL);

                    if (is_file(IFIS_PATH_UPLOAD_WID . $row['documentlink']) ) {
                        unlink(IFIS_PATH_UPLOAD_WID . $row['documentlink']);
                    }
                    if ($mysqli->error) {
                        $_SESSION['x'].=$updateSQL;
                        AddSessionMessage("warning","Dokument wurde nicht gelöscht, da ein Fehler aufgetreten ist.","Fehlermeldung");
                    }else{
                        AddSessionMessage("success", "Dokument  <code> " . $row['did']. "</code> wurde gelöscht.", "Dokumentverwaltung");
                    }
                }
            }
        }
    }
}


class ifisAllMaterial{
    public $werk=0;
    public $data=array();

    function __construct($id=1501,$state="")
    {
        if ($id>0){
            $this->werk=$id;
        }
        $this->LoadData($state);
    }
    function LoadData($state="",$ftype=""){
        include ("../apps/dbconnection.php");
        $this->data=array();
        $limit="";
        $statesql="";

        if (strlen($_SESSION['datumvon'])>0 && $_SESSION['datumvon']<>"0000-00-00"){ $limit.=" AND kundendaten.datum>='".$_SESSION['datumvon']." 00:00:00'";}
        if (strlen($_SESSION['datumbis'])>0 && $_SESSION['datumbis']<>"0000-00-00"){ $limit.=" AND kundendaten.datum<='".$_SESSION['datumbis']." 23:59:99'";}


        $sql="SELECT * FROM materialdaten WHERE materialdaten.lokz=0 ORDER BY materialdaten.material ASC";
        $rst=$mysqli->query($sql);
        if (mysqli_affected_rows($mysqli)>0){
            $row_rst = $rst->fetch_assoc();
            do{
                $this->data[]=$row_rst;
            } while ($row_rst = $rst->fetch_assoc());

        }else{
            $this->data=array();
        }
    }
    function GetJsonMaterialList($state){
        $this->LoadData($state);
        if (count($this->data)>0){
            foreach ($this->data as $item){
                $r['aaData'][]=array(
                    ($item['idmaterial']),
                    ($item['material']),
                    ($item['materialtxt']),
                    ($item['color']),
                    ($item['warengruppe']),
                    ($item['produktgruppe'])

                );

            }
        } else{
            $r['aaData']=array();
        }

        echo  json_encode($r);
    }

    function GetMaterialList($fstate=""){
        $this->LoadData($fstate);
        $h="<tbody>";
        if (count($this->data)>0){
            foreach($this->data as $item){
                $h.="<tr><td>".$item['idmaterial']."</td>";
                $h.="<td>".substr($item['datum'],0,10)."</td>";
                $h.="<td>".$item['material']." </td>";
                $h.="<td>".$item['materialtxt']."</td>";
                $h.="<td>".$item['color']."</td>";
                $h.="<td>".$item['fnotes']."</td></tr>";
            }
            $h.="</tbody>";

        }else{
            $h.="<tr><td colspan='6'>keine Materialdaten vorhanden</td></tr>";
            $h.="</tbody>";
        }
        return $h;
    }

}
class ifisMaterial{
    public $materialid=0;
    public $data=array();
    public $soll=array();
    public $fids=array();
    public $history=array();
    public $documents=array();
    function __construct($id=0)
    {
        if ($id>0){
            $this->materialid=$id;
        }
        $this->LoadData();
    }
    function LoadData(){
        include ("../apps/dbconnection.php");
        $sql="SELECT * FROM materialdaten WHERE materialdaten.idmaterial='$this->materialid'  ";
        $rst=$mysqli->query($sql);
        if (mysqli_affected_rows($mysqli)>0){
            $this->data=$rst->fetch_assoc();
            $this->LoadHistory();
            $this->LoadDocumentData();
            $this->LoadFids();
            $this->SetSollValues();
        }else{
            $this->data=array();
        }
    }
    function LoadDataByReferenz($ref){
        include ("../apps/dbconnection.php");
        $sql="SELECT * FROM kundendaten WHERE kundendaten.id_customer='$ref'  ";
        $rst=$mysqli->query($sql);
        if (mysqli_affected_rows($mysqli)>0){
            $this->data=$rst->fetch_assoc();
            $this->faufid=$this->data['faufid'];
            $this->LoadHistory();
            $this->LoadDocumentData();
            $this->LoadFids();
            $this->SetSollValues();
        }else{
            $this->data=array();
        }
    }
    function LoadHistory(){
        if ($this->materialid>0) {
            include("../apps/dbconnection.php");
            $sql = "SELECT * FROM materialdatenworkflow WHERE materialdatenworkflow.lokz='0' AND materialdatenworkflow.faufid='" . $this->materialid . "' 
            ORDER BY materialdatenworkflow.wdatum DESC LIMIT 0,1000";
            $rst = $mysqli->query($sql);
            if ($mysqli->error) {
                $_SESSION['x'] .= $sql;
                AddSessionMessage("error", "Fehler beim Laden des Verlaufs", "Fehlermeldung");
                $this->history = array();
            } else {
                if (mysqli_num_rows($rst) > 0) {
                    $row_rst = $rst->fetch_assoc();
                    $this->data['currentstate'] = $row_rst['wnotes'];
                    do {
                        $this->history[] = $row_rst;
                    } while ($row_rst = $rst->fetch_assoc());

                }
            }
        }
    }
    function LoadFids(){
        include ("../apps/dbconnection.php");
        $sql="SELECT * FROM fertigungsmeldungen WHERE fertigungsmeldungen.flokz='0' AND fertigungsmeldungen.material='".$this->materialid."' ORDER BY fertigungsmeldungen.fdatum DESC LIMIT 0,1000";
        $rst=$mysqli->query($sql);
        if (mysqli_num_rows($rst) > 0) {
            $row_rst = $rst->fetch_assoc();
            do{
                $this->fids[]=$row_rst;
            } while ($row_rst = $rst->fetch_assoc());

        }else{
            $this->fids=array();
        }
    }
    function GetNextMaterial(){
        include ("../apps/dbconnection.php");
        $sql="SELECT materialdaten.material FROM materialdaten  ORDER BY materialdaten.material DESC LIMIT 0,1";
        $rst=$mysqli->query($sql);
        if (mysqli_affected_rows($mysqli)>0){
            $row=$rst->fetch_assoc();
            return $row['material']+1;
        }else{
            return 1;
        }
    }
    function Validate($POST){
        $this->data['material']=(utf8_decode($POST['material']));
        $this->data['materialtxt']=(utf8_decode($POST['materialtxt']));
        $this->data['color']=(utf8_decode($POST['color']));

        $this->data['warengruppe']=(utf8_decode($POST['warengruppe']));
        $this->data['produktgruppe']=(utf8_decode($POST['produktgruppe']));


        $this->data['fnotes']=(utf8_decode($POST['fnotes']));

    }
    function SaveData($POST){
        if (isset($POST['materialid']) && $POST['materialid']>0){
            $this->materialid=$POST['materialid'];
            include ("../apps/dbconnection.php");
            $this->Validate($POST);

            $updateSQL = sprintf("
                UPDATE materialdaten SET 
                material=%s, materialtxt=%s, color=%s, warengruppe=%s, produktgruppe=%s, fnotes=%s
                WHERE idmaterial=%s",
                GetSQLValueString($this->data['material'],"text"),
                GetSQLValueString($this->data['materialtxt'],"text"),
                GetSQLValueString($this->data['color'],"text"),
                GetSQLValueString($this->data['warengruppe'],"text"),
                GetSQLValueString($this->data['produktgruppe'],"text"),
                GetSQLValueString($this->data['fnotes'],"text"),


                $this->materialid);
            $rst = $mysqli->query($updateSQL);
            if ($mysqli->error) {
                $_SESSION['x'].=$updateSQL;
                AddSessionMessage("error","Änderung wurde nicht angelegt, da ein Fehler aufgetreten ist.","Fehlermeldung");
            }else{
                AddSessionMessage("success", "Materialdaten wurden gespeichert.", "Speichern");
                $this->ChangeLog("Änderung am ".date('Y-m-d H:i',time())." ".$POST['material']." ".$POST['materialtxt'],1);
                $this->ChangeLog("Änderung am ".date('Y-m-d H:i',time())." ".$POST['warengruppe']." ".$POST['produktgruppe'],1);
                $this->AddNewDocument("Dokumentablage");

            }
        }
    }
    function InsertData($POST){
        if (strlen($POST['materialtxt'])>=1){
            include ("../apps/dbconnection.php");
            $this->Validate($POST);

            $updateSQL = sprintf("
                INSERT INTO  materialdaten (material,`materialtxt`, fnotes,datum, `werk`) 
                VALUES (%s,%s,%s,%s,%s)",
                GetSQLValueString($this->data['material'],"text"),
                GetSQLValueString($this->data['materialtxt'],"text"),
                GetSQLValueString($this->data['fnotes'],"text"),
                "now()",
                $_SESSION['classifiedDirectoryID']
            );

            $rst = $mysqli->query($updateSQL);
            if ($mysqli->error) {
                $_SESSION['x'].=$updateSQL;
                AddSessionMessage("warning","Material wurde nicht angelegt, da ein Fehler aufgetreten ist.","Fehlermeldung");
            }else{
                AddSessionMessage("success", "Material <code> " . $mysqli->insert_id . "</code> wurde gespeichert.", "Neu anlegen");
                $this->ChangeLog("Neuanlage",0,$mysqli->insert_id);
                $this->customerid=$mysqli->insert_id;
                $this->AddNewDocument("Dokumentablage");
            }
        }else{
            AddSessionMessage("error","Kunde wurde nicht angelegt.","Fehlermeldung");
        }
    }
    function SendEmail($mode="", $email=""){
        $this->data['email']=$email;
        $text = "Der Status der Materialdaten wurde geändert.".chr(10).chr(10);

        $text.="Material: ".$this->data['material'].chr(10);
        $text.="Bezeichnung: ".$this->data['materialtxt'].chr(10);



        if ($mode=="callback"){
            return ("hiermit erhalten Sie eine Information für " . $this->data['fauf']. ".".chr(10).
                " " . date("Y-m-d - H:i ", time()) .
                "\n " . $text );
        }else {
            if (strlen(($this->data['email'])) > 4) {

                mail(($this->data['email']),
                    "ALBA Recycling Materialdatenverwaltung " . $this->data['fauf'],
                    " " . date("Y-m-d - H:i ", time()) .
                    "\n" . "\n " . $text .

                    "\nIPv4: " . $_POST["hipv4"] . "-" . $_SERVER["REMOTE_ADDR"] . " - " . $_SERVER['HTTP_X_FORWARDED_FOR']);

                AddSessionMessage("success", "E-Mail mit Materialdatenstatus an <code> " . $this->data['email'] . " </code> wurde gesendet", "Info");
                $this->ChangeLog("E-Mailversand an " . $this->data['email'], 1);
            } else {
                AddSessionMessage("error", "E-Mail konnte nicht gesendet werden, da keine E-Mail-Adresse für <code> CUSTOMER ID" . $this->customerid . " " . $this->data['email'] . " </code> vorhanden ist.", "Abbruch");
            }
        }
    }
    function ChangeLog($notes,$state=1, $id=""){
        if ($id==""){ $id=$this->materialid;}
        /*
         * 0=neuanlage
         * 1=info
         * 2=warnung
         * 3=danger
         */
        // wareneingangworkflow
        if (strlen($notes)>=1){
            $us=new ifisUser($_SESSION['userid']);
            include ("../apps/dbconnection.php");
            $updateSQL = sprintf("
                INSERT INTO  materialdatenworkflow (wnotes, wstate, wdatum, wuser, username, werk, faufid) VALUES (%s,%s,%s,%s,%s,%s,%s)",
                GetSQLValueString(utf8_decode($notes), "text"),
                GetSQLValueString($state, "int"),
                "now()",
                GetSQLValueString($_SESSION['userid'], "int"),
                GetSQLValueString(utf8_decode($us->data['name']), "text"),
                GetSQLValueString($_SESSION['classifiedDirectoryID'], "int"),
                GetSQLValueString($id, "int")
            );
            $rst = $mysqli->query($updateSQL);
            if ($mysqli->error) {
                $_SESSION['x'].=$updateSQL;
                AddSessionMessage("warning","Status wurde nicht angelegt, da ein Fehler aufgetreten ist.","Fehlermeldung");
            }else{
                AddSessionMessage("success", "Status <code> " . $mysqli->insert_id . "</code> wurde gespeichert.", "Neu anlegen");
            }
        }

    }
    function GetHistoryAsTableBody(){
        $h="<tbody>";
        if (count($this->history)>0){
            foreach($this->history as $item){
                $h.="<tr><td>".substr($item['wdatum'],0,16)."</td>";
                $h.="<td>".$item['wnotes']."</td>";
                $h.="<td>".GetStatus($item['wstate'])."</td>";
                $h.="<td>".$item['username']."</td></tr>";
            }
            $h.="</tbody>";

        }else{
            $h.="<tr><td colspan='4'>keine Verlaufinformationen vorhanden</td></tr>";
            $h.="</tbody>";
        }
        return $h;
    }
    function GetFidsAsTableBody(){
        $h="<tbody>";
        if (count($this->fids)>0){
            foreach($this->fids as $item){
                $h.="<tr><td>".substr($item['wdatum'],0,16)."</td>";
                $h.="<td>".$item['gebindeid']."</td>";
                $h.="<td>".$item['material']."</td>";
                $h.="<td>".$item['fnotes']."</td>";
                $h.="<td>".$item['gewicht']."</td>";
                $h.="<td>".$item['sap']."</td></tr>";
            }
            $h.="</tbody>";

        }else{
            $h.="<tr><td colspan='6'>keine Rückmeldungen vorhanden</td></tr>";
            $h.="</tbody>";
        }
        return $h;
    }
    function GetCurrentState(){
        if (is_null($this->data['currentstate'])){
            return "Neuanlage";
        }else{
            return $this->data['currentstate'];
        }
    }
    function GetFaufType(){
        return GetListFaufType("text",$this->data['ftype']);
    }

    function SetSollValues(){
        $this->soll['mfi']= $this->data['mfi']." bis ".$this->data['mfibis'];
        $this->soll['ifeuchte']= $this->data['ifeuchte']." bis ".$this->data['ifeuchtebis'];
        $this->soll['afeuchte']= $this->data['afeuchte']." bis ".$this->data['afeuchtebis'];
        $this->soll['schlag']= $this->data['schlag']." bis ".$this->data['schlagbis'];
        $this->soll['emodul']= $this->data['emodul']." bis ".$this->data['emodulbis'];
        $this->soll['schuettdichte']= $this->data['schuettdichte']." bis ".$this->data['schuettdichtebis'];
        $this->soll['farbe']= $this->data['farbe']." bis ".$this->data['farbebis'];
    }
    function AddNewDocument($notes=" "){
        include("../apps/dbconnection.php");
        if (strlen($notes)>=1){
            $us=new ifisUser($_SESSION['userid']);
            include ("../apps/dbconnection.php");

            $state=1;
            $folder="../tmpupload/";
            $files = glob($folder."*.*");
            foreach($files as $value){

                $file = $value;
                // AddSessionMessage("success", "Dokument  <code> " . $file. "</code> wurde hochgeladen und gespeichert.", "Dokumentupload");
                $filename=str_replace($folder,"../material/".time(),$file);

                if(file_exists($folder.$file)){
                    rename($file,$filename);
                    $updateSQL = sprintf("
                        INSERT INTO  dokumente (dnotes, dstate, ddatum, duser, username, werk, linkedid, dobject, documentlink) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s)",
                        GetSQLValueString(utf8_decode($notes), "text"),
                        GetSQLValueString($state, "int"),
                        "now()",
                        GetSQLValueString($_SESSION['userid'], "int"),
                        GetSQLValueString(utf8_decode($us->data['name']), "text"),
                        GetSQLValueString($_SESSION['classifiedDirectoryID'], "int"),
                        GetSQLValueString($this->materialid, "int"),
                        GetSQLValueString("material", "text"),
                        GetSQLValueString($filename, "text")
                    );
                    $rst = $mysqli->query($updateSQL);

                    if ($mysqli->error) {
                        $_SESSION['x'].=$updateSQL;
                        AddSessionMessage("warning","Dokument wurde nicht angelegt, da ein Fehler aufgetreten ist.","Fehlermeldung");
                    }else{
                        AddSessionMessage("success", "Dokument  <code> " . $filename. "</code> wurde hochgeladen und gespeichert.", "Dokumentupload");
                        $this->ChangeLog("Dokument $filename wurde hochgeladen",1);
                    }
                }
            }
        }

    }
    function LoadDocumentData(){
        if ($this->materialid > 0){
            include("../apps/dbconnection.php");
            $updateSQL="SELECT * FROM dokumente
                        WHERE dokumente.linkedid='".$this->materialid."' AND dokumente.dobject='material'
                        ORDER BY dokumente.ddatum ASC";

            $mysqli = new mysqli($hostname_datenbank, $username_datenbank, $password_datenbank, $database_datenbank);
            $rst=$mysqli->query($updateSQL);
            if ($mysqli->error) {
                AddSessionMessage("warning","Dokumente wurden nicht geladen, da ein Fehler aufgetreten ist.","Fehlermeldung");
            }else{
                if ($mysqli->affected_rows>0) {
                    $this->documents=array();
                    $row_rst = $rst->fetch_assoc();
                    do{
                        $this->documents[]= $row_rst;
                    }while ($row_rst=$rst->fetch_assoc());
                }
            }
            $mysqli->close();
        }
    }
    function GetDocumentFiles(){

        if (count($this->documents)>0){
            foreach ($this->documents as $item){
                $r[]=array(
                    'name'=> $item['did'].substr($item['documentlink'],0,-3),
                    'size'=> 145,
                    'type'=> "image/jpg",
                    'file'=> "../fauf/".$item['documentlink']);
            }
        }
        return "files:".json_encode($r);
    }
    function GetDocumentList(){
        $h="<tbody>";
        if (count($this->documents)>0){
            foreach($this->documents as $item){
                $h.="<tr><td>".substr($item['ddatum'],0,16)."</td>";
                $h.="<td><img src=' ".$item['documentlink']."' width='145'></td>";
                $h.="<td>".$item['dnotes']."</td>";
                $h.="<td>".GetStatus($item['dstate'])."</td>";
                $h.="<td>".$item['username']."</td></tr>";
            }
            $h.="</tbody>";

        }else{
            $h.="<tr><td colspan='5'>keine Dokumentinformationen vorhanden</td></tr>";
            $h.="</tbody>";
        }
        return $h;
    }
}

class ifisAllCustomer{
    public $werk=0;
    public $data=array();

    function __construct($id=1501,$state="")
    {
        if ($id>0){
            $this->werk=$id;
        }
        $this->LoadData($state);
    }
    function LoadData($state="",$ftype=""){
        include ("../apps/dbconnection.php");
        $this->data=array();
        $limit="";
        $statesql="";

        if (strlen($_SESSION['datumvon'])>0 && $_SESSION['datumvon']<>"0000-00-00"){ $limit.=" AND kundendaten.datum>='".$_SESSION['datumvon']." 00:00:00'";}
        if (strlen($_SESSION['datumbis'])>0 && $_SESSION['datumbis']<>"0000-00-00"){ $limit.=" AND kundendaten.datum<='".$_SESSION['datumbis']." 23:59:99'";}


        $sql="SELECT * FROM kundendaten WHERE kundendaten.lokz=0 ORDER BY kundendaten.firma ASC";
        $rst=$mysqli->query($sql);
        if (mysqli_affected_rows($mysqli)>0){
            $row_rst = $rst->fetch_assoc();
            do{
                $this->data[]=$row_rst;
            } while ($row_rst = $rst->fetch_assoc());

        }else{
            $this->data=array();
        }
    }
    function GetJsonCustomerList($state){
        $this->LoadData($state);
        if (count($this->data)>0){
            foreach ($this->data as $item){
                $r['aaData'][]=array(
                    utf8_decode($item['id_customer']),
                    utf8_decode($item['referenz']),
                    utf8_decode($item['firma']),
                    utf8_decode($item['street']),
                    utf8_decode($item['plz']),
                    utf8_decode($item['city']),
                    utf8_decode($item['tel']),
                    utf8_decode($item['fax']),
                    utf8_decode($item['email']),
                    utf8_decode($item['name'])
                );

            }
        } else{
            $r['aaData']=array();
        }

        echo  json_encode($r);
    }

    function GetCustomerList($fstate=""){
        $this->LoadData($fstate);
        $h="<tbody>";
        if (count($this->data)>0){
            foreach($this->data as $item){
                $h.="<tr><td>".$item['id_customer']."</td>";
                $h.="<td>".substr($item['datum'],0,10)."</td>";
                $h.="<td>".$item['firma']." </td>";
                $h.="<td>".$item['street']."</td>";
                $h.="<td>".$item['plz']."</td>";
                $h.="<td>".$item['city']."</td></tr>";
            }
            $h.="</tbody>";

        }else{
            $h.="<tr><td colspan='6'>keine Kundendaten vorhanden</td></tr>";
            $h.="</tbody>";
        }
        return $h;
    }

}
class ifisCustomer{
    public $customerid=0;
    public $data=array();
    public $soll=array();
    public $fids=array();
    public $history=array();
    public $documents=array();
    function __construct($id=0)
    {
        if ($id>0){
            $this->customerid=$id;
        }
        $this->LoadData();
    }
    function LoadData(){
        include ("../apps/dbconnection.php");
        $sql="SELECT * FROM kundendaten WHERE kundendaten.id_customer='$this->customerid'  ";
        $rst=$mysqli->query($sql);
        if (mysqli_affected_rows($mysqli)>0){
            $this->data=$rst->fetch_assoc();
            $this->LoadHistory();
            $this->LoadDocumentData();
            $this->LoadFids();
            $this->SetSollValues();
        }else{
            $this->data=array();
        }
    }
    function LoadDataByReferenz($ref){
        include ("../apps/dbconnection.php");
        $sql="SELECT * FROM kundendaten WHERE kundendaten.id_customer='$ref'  ";
        $rst=$mysqli->query($sql);
        if (mysqli_affected_rows($mysqli)>0){
            $this->data=$rst->fetch_assoc();
            $this->faufid=$this->data['faufid'];
            $this->LoadHistory();
            $this->LoadDocumentData();
            $this->LoadFids();
            $this->SetSollValues();
        }else{
            $this->data=array();
        }
    }
    function LoadHistory(){
        if ($this->customerid>0) {
            include("../apps/dbconnection.php");
            $sql = "SELECT * FROM kundendatenworkflow WHERE kundendatenworkflow.lokz='0' AND kundendatenworkflow.faufid='" . $this->customerid . "' ORDER BY kundendatenworkflow.wdatum DESC LIMIT 0,1000";
            $rst = $mysqli->query($sql);
            if ($mysqli->error) {
                $_SESSION['x'] .= $sql;
                AddSessionMessage("error", "Fehler beim Laden des Verlaufs", "Fehlermeldung");
                $this->history = array();
            } else {
                if (mysqli_num_rows($rst) > 0) {
                    $row_rst = $rst->fetch_assoc();
                    $this->data['currentstate'] = $row_rst['wnotes'];
                    do {
                        $this->history[] = $row_rst;
                    } while ($row_rst = $rst->fetch_assoc());

                }
            }
        }
    }
    function LoadFids(){
        include ("../apps/dbconnection.php");
        $sql="SELECT * FROM fertigungsmeldungen WHERE fertigungsmeldungen.flokz='0' AND fertigungsmeldungen.fauf='".$this->faufid."' ORDER BY fertigungsmeldungen.fdatum DESC LIMIT 0,1000";
        $rst=$mysqli->query($sql);
        if (mysqli_num_rows($rst) > 0) {
            $row_rst = $rst->fetch_assoc();
            do{
                $this->fids[]=$row_rst;
            } while ($row_rst = $rst->fetch_assoc());

        }else{
            $this->fids=array();
        }
    }
    function GetNextCustomer(){
        include ("../apps/dbconnection.php");
        $sql="SELECT kundendaten.referenz FROM kundendaten  ORDER BY kundendaten.referenz DESC LIMIT 0,1";
        $rst=$mysqli->query($sql);
        if (mysqli_affected_rows($mysqli)>0){
            $row=$rst->fetch_assoc();
            return $row['referenz']+1;
        }else{
            return 1;
        }
    }
    function Validate($POST){
        $this->data['referenz']=utf8_decode($POST['referenz']);
        $this->data['firma']=utf8_decode($POST['firma']);
        $this->data['street']=utf8_decode($POST['street']);
        $this->data['plz']=utf8_decode($POST['plz']);
        $this->data['city']=utf8_decode($POST['city']);
        $this->data['land']=utf8_decode($POST['land']);
        $this->data['tel']=utf8_decode($POST['tel']);
        $this->data['fax']=utf8_decode($POST['fax']);
        $this->data['name']=utf8_decode($POST['name']);
        $this->data['email']=utf8_decode($POST['email']);
        $this->data['fnotes']=utf8_decode($POST['fnotes']);

    }
    function SaveData($POST){
        if (isset($POST['customerid']) && $POST['customerid']>0){
            $this->customerid=$POST['customerid'];
            include ("../apps/dbconnection.php");
            $this->Validate($POST);

            $updateSQL = sprintf("
                UPDATE kundendaten SET 
                referenz=%s, firma=%s, street=%s,plz=%s,city=%s,land=%s,tel=%s,fax=%s,email=%s,`name`=%s,fnotes=%s
                WHERE id_customer=%s",
                GetSQLValueString($this->data['referenz'],"text"),
                GetSQLValueString($this->data['firma'],"text"),
                GetSQLValueString($this->data['street'],"text"),
                GetSQLValueString($this->data['plz'],"text"),
                GetSQLValueString($this->data['city'],"text"),
                GetSQLValueString($this->data['land'],"text"),
                GetSQLValueString($this->data['tel'],"text"),
                GetSQLValueString($this->data['fax'],"text"),
                GetSQLValueString($this->data['email'],"text"),
                GetSQLValueString($this->data['name'],"text"),
                GetSQLValueString($this->data['fnotes'],"text"),


                $this->customerid);
            $rst = $mysqli->query($updateSQL);
            if ($mysqli->error) {
                $_SESSION['x'].=$updateSQL;
                AddSessionMessage("error","Änderung wurde nicht angelegt, da ein Fehler aufgetreten ist.","Fehlermeldung");
            }else{
                AddSessionMessage("success", "Kundendaten wurde gespeichert.", "Speichern");
                $this->ChangeLog("Änderung am ".date('Y-m-d H:i',time()),1);
                $this->AddNewDocument("Dokumentablage");

            }
        }
    }
    function InsertData($POST){
        if (strlen($POST['firma'])>=1){
            include ("../apps/dbconnection.php");
            $this->Validate($POST);

            $updateSQL = sprintf("
                INSERT INTO  kundendaten (referenz,`firma`, street, plz,city, land,tel,fax,
                 email,`name`, fnotes,datum, `werk`) 
                VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s, %s, %s)",
                GetSQLValueString($this->data['referenz'],"text"),
                GetSQLValueString($this->data['firma'],"text"),
                GetSQLValueString($this->data['street'],"text"),
                GetSQLValueString($this->data['plz'],"text"),
                GetSQLValueString($this->data['city'],"text"),
                GetSQLValueString($this->data['land'],"text"),
                GetSQLValueString($this->data['tel'],"text"),
                GetSQLValueString($this->data['fax'],"text"),
                GetSQLValueString($this->data['email'],"text"),
                GetSQLValueString($this->data['name'],"text"),
                GetSQLValueString($this->data['fnotes'],"text"),
                "now()",
                $_SESSION['classifiedDirectoryID']
            );

            $rst = $mysqli->query($updateSQL);
            if ($mysqli->error) {
                $_SESSION['x'].=$updateSQL;
                AddSessionMessage("warning","Kunde wurde nicht angelegt, da ein Fehler aufgetreten ist.","Fehlermeldung");
            }else{
                AddSessionMessage("success", "Kunde <code> " . $mysqli->insert_id . "</code> wurde gespeichert.", "Neu anlegen");
                $this->ChangeLog("Neuanlage",0,$mysqli->insert_id);
                $this->customerid=$mysqli->insert_id;
                $this->AddNewDocument("Dokumentablage");
            }
        }else{
            AddSessionMessage("error","Kunde wurde nicht angelegt.","Fehlermeldung");
        }
    }
    function SendEmail($mode=""){
        $text = "Der Status der Kundendaten wurde geändert.".chr(10).chr(10);

        $text.="Firma: ".$this->data['firma'].chr(10);
        $text.="Straße: ".$this->data['street'].chr(10);
        $text.="PLZ: ".$this->data['plz'].chr(10);
        $text.="Ort: ".$this->data['city'].chr(10);
        $text.="Land: ".$this->data['land'].chr(10);

        $text.="Tel: ".$this->data['tel'].chr(10);
        $text.="fax: ".$this->data['fax'].chr(10);
        $text.="Ansprechpartner: ".$this->data['name'].chr(10);
        $text.="E-Mail: ".$this->data['email'].chr(10);


        if ($mode=="callback"){
            return ("hiermit erhalten Sie eine Information für " . $this->data['fauf']. ".".chr(10).
                " " . date("Y-m-d - H:i ", time()) .
                "\n " . $text );
        }else {
            if (strlen(($this->data['email'])) > 4) {

                mail(($this->data['email']),
                    "ALBA Recycling Kundendatenverwaltung " . $this->data['fauf'],
                    " " . date("Y-m-d - H:i ", time()) .
                    "\n" . "\n " . $text .

                    "\nIPv4: " . $_POST["hipv4"] . "-" . $_SERVER["REMOTE_ADDR"] . " - " . $_SERVER['HTTP_X_FORWARDED_FOR']);

                AddSessionMessage("success", "E-Mail mit Fertigungsstatus an <code> " . $this->data['email'] . " </code> wurde gesendet", "Info");
                $this->ChangeLog("E-Mailversand an " . $this->data['email'], 1);
            } else {
                AddSessionMessage("error", "E-Mail konnte nicht gesendet werden, da keine E-Mail-Adresse für <code> CUSTOMER ID" . $this->customerid . " " . $this->data['email'] . " </code> vorhanden ist.", "Abbruch");
            }
        }
    }
    function ChangeLog($notes,$state=1, $id=""){
        if ($id==""){ $id=$this->customerid;}
        /*
         * 0=neuanlage
         * 1=info
         * 2=warnung
         * 3=danger
         */
        // wareneingangworkflow
        if (strlen($notes)>=1){
            $us=new ifisUser($_SESSION['userid']);
            include ("../apps/dbconnection.php");
            $updateSQL = sprintf("
                INSERT INTO  kundendatenworkflow (wnotes, wstate, wdatum, wuser, username, werk, faufid) VALUES (%s,%s,%s,%s,%s,%s,%s)",
                GetSQLValueString(utf8_decode($notes), "text"),
                GetSQLValueString($state, "int"),
                "now()",
                GetSQLValueString($_SESSION['userid'], "int"),
                GetSQLValueString(utf8_decode($us->data['name']), "text"),
                GetSQLValueString($_SESSION['classifiedDirectoryID'], "int"),
                GetSQLValueString($id, "int")
            );
            $rst = $mysqli->query($updateSQL);
            if ($mysqli->error) {
                $_SESSION['x'].=$updateSQL;
                AddSessionMessage("warning","Status wurde nicht angelegt, da ein Fehler aufgetreten ist.","Fehlermeldung");
            }else{
                AddSessionMessage("success", "Status <code> " . $mysqli->insert_id . "</code> wurde gespeichert.", "Neu anlegen");
            }
        }

    }
    function GetHistoryAsTableBody(){
        $h="<tbody>";
        if (count($this->history)>0){
            foreach($this->history as $item){
                $h.="<tr><td>".substr($item['wdatum'],0,16)."</td>";
                $h.="<td>".$item['wnotes']."</td>";
                $h.="<td>".GetStatus($item['wstate'])."</td>";
                $h.="<td>".$item['username']."</td></tr>";
            }
            $h.="</tbody>";

        }else{
            $h.="<tr><td colspan='4'>keine Verlaufinformationen vorhanden</td></tr>";
            $h.="</tbody>";
        }
        return $h;
    }
    function GetFidsAsTableBody(){
        $h="<tbody>";
        if (count($this->fids)>0){
            foreach($this->fids as $item){
                $h.="<tr><td>".substr($item['wdatum'],0,16)."</td>";
                $h.="<td>".$item['gebindeid']."</td>";
                $h.="<td>".$item['material']."</td>";
                $h.="<td>".$item['fnotes']."</td>";
                $h.="<td>".$item['gewicht']."</td>";
                $h.="<td>".$item['sap']."</td></tr>";
            }
            $h.="</tbody>";

        }else{
            $h.="<tr><td colspan='6'>keine Rückmeldungen vorhanden</td></tr>";
            $h.="</tbody>";
        }
        return $h;
    }
    function GetCurrentState(){
        if (is_null($this->data['currentstate'])){
            return "Neuanlage";
        }else{
            return $this->data['currentstate'];
        }
    }
    function GetFaufType(){
        return GetListFaufType("text",$this->data['ftype']);
    }

    function SetSollValues(){
        $this->soll['mfi']= $this->data['mfi']." bis ".$this->data['mfibis'];
        $this->soll['ifeuchte']= $this->data['ifeuchte']." bis ".$this->data['ifeuchtebis'];
        $this->soll['afeuchte']= $this->data['afeuchte']." bis ".$this->data['afeuchtebis'];
        $this->soll['schlag']= $this->data['schlag']." bis ".$this->data['schlagbis'];
        $this->soll['emodul']= $this->data['emodul']." bis ".$this->data['emodulbis'];
        $this->soll['schuettdichte']= $this->data['schuettdichte']." bis ".$this->data['schuettdichtebis'];
        $this->soll['farbe']= $this->data['farbe']." bis ".$this->data['farbebis'];
    }
    function AddNewDocument($notes=" "){
        include("../apps/dbconnection.php");
        if (strlen($notes)>=1){
            $us=new ifisUser($_SESSION['userid']);
            include ("../apps/dbconnection.php");

            $state=1;
            $folder="../tmpupload/";
            $files = glob($folder."*.*");
            foreach($files as $value){

                $file = $value;
                // AddSessionMessage("success", "Dokument  <code> " . $file. "</code> wurde hochgeladen und gespeichert.", "Dokumentupload");
                $filename=str_replace($folder,"../customer/".time(),$file);

                if(file_exists($folder.$file)){
                    rename($file,$filename);
                    $updateSQL = sprintf("
                        INSERT INTO  dokumente (dnotes, dstate, ddatum, duser, username, werk, linkedid, dobject, documentlink) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s)",
                        GetSQLValueString(utf8_decode($notes), "text"),
                        GetSQLValueString($state, "int"),
                        "now()",
                        GetSQLValueString($_SESSION['userid'], "int"),
                        GetSQLValueString(utf8_decode($us->data['name']), "text"),
                        GetSQLValueString($_SESSION['classifiedDirectoryID'], "int"),
                        GetSQLValueString($this->customerid, "int"),
                        GetSQLValueString("customer", "text"),
                        GetSQLValueString($filename, "text")
                    );
                    $rst = $mysqli->query($updateSQL);

                    if ($mysqli->error) {
                        $_SESSION['x'].=$updateSQL;
                        AddSessionMessage("warning","Dokument wurde nicht angelegt, da ein Fehler aufgetreten ist.","Fehlermeldung");
                    }else{
                        AddSessionMessage("success", "Dokument  <code> " . $filename. "</code> wurde hochgeladen und gespeichert.", "Dokumentupload");
                        $this->ChangeLog("Dokument $filename wurde hochgeladen",1);
                    }
                }
            }
        }

    }
    function LoadDocumentData(){
        if ($this->customerid > 0){
            include("../apps/dbconnection.php");
            $updateSQL="SELECT * FROM dokumente
                        WHERE dokumente.linkedid='".$this->customerid."' AND dokumente.dobject='customer'
                        ORDER BY dokumente.ddatum ASC";

            $mysqli = new mysqli($hostname_datenbank, $username_datenbank, $password_datenbank, $database_datenbank);
            $rst=$mysqli->query($updateSQL);
            if ($mysqli->error) {
                AddSessionMessage("warning","Dokumente wurden nicht geladen, da ein Fehler aufgetreten ist.","Fehlermeldung");
            }else{
                if ($mysqli->affected_rows>0) {
                    $this->documents=array();
                    $row_rst = $rst->fetch_assoc();
                    do{
                        $this->documents[]= $row_rst;
                    }while ($row_rst=$rst->fetch_assoc());
                }
            }
            $mysqli->close();
        }
    }
    function GetDocumentFiles(){

        if (count($this->documents)>0){
            foreach ($this->documents as $item){
                $r[]=array(
                    'name'=> $item['did'].substr($item['documentlink'],0,-3),
                    'size'=> 145,
                    'type'=> "image/jpg",
                    'file'=> "../fauf/".$item['documentlink']);
            }
        }
        return "files:".json_encode($r);
    }
    function GetDocumentList(){
        $h="<tbody>";
        if (count($this->documents)>0){
            foreach($this->documents as $item){
                $h.="<tr><td>".substr($item['ddatum'],0,16)."</td>";
                $h.="<td><img src=' ".$item['documentlink']."' width='145'></td>";
                $h.="<td>".$item['dnotes']."</td>";
                $h.="<td>".GetStatus($item['dstate'])."</td>";
                $h.="<td>".$item['username']."</td></tr>";
            }
            $h.="</tbody>";

        }else{
            $h.="<tr><td colspan='5'>keine Dokumentinformationen vorhanden</td></tr>";
            $h.="</tbody>";
        }
        return $h;
    }
}


class ifisAllWarenausgang{

    private $werk=0;
    public $data=array();

    function __construct($werk=0)
    {
        if ($werk>0){
            $this->werk=$werk;
        }
        $this->LoadData();
    }
    function LoadData($date=""){
        $this->data=array();
        include ("../apps/dbconnection.php");
        $limit="";
        if (strlen($_SESSION['datumvon'])>0 && $_SESSION['datumvon']<>"0000-00-00"){ $limit.=" AND warenausgang.wdatum>='".$_SESSION['datumvon']." 00:00:00'";}
        if (strlen($_SESSION['datumbis'])>0 && $_SESSION['datumbis']<>"0000-00-00"){ $limit.=" AND warenausgang.wdatum<='".$_SESSION['datumbis']." 23:59:99'";}

        if (strlen($date)>8){
            $limit="AND warenausgang.wdatum>='".$date." 00:00:00' AND  warenausgang.wdatum<='".$date." 23:59:99' ";
        }

        $sql="SELECT * FROM warenausgang WHERE warenausgang.wlokz='0' ".$limit. " ORDER BY warenausgang.wdatum ASC LIMIT 0,1000";
        $rst=$mysqli->query($sql);
        if (mysqli_num_rows($rst) > 0) {
            $row_rst = $rst->fetch_assoc();
            do{
                $this->data[]=$row_rst;
            } while ($row_rst = $rst->fetch_assoc());

        }else{
            $this->data=array();
        }
    }
    function ReportCountWareneingange($duration=1){
        include ("../apps/dbconnection.php");
        $limit="";
        $time1=date ('Y-m-d',time());
        $time2=date ('Y-m-d',time()-( 24*60*60*$duration) );

        if (strlen($time2)>0 && $time2<>"0000-00-00"){ $limit.=" AND warenausgang.wdatum>='".$time2." 00:00:00'";}
        if (strlen($time1)>0 && $time1<>"0000-00-00"){ $limit.=" AND warenausgang.wdatum<='".$time1." 23:59:99'";}


        $sql="SELECT COUNT(warenausgang.waid) as anzahl FROM warenausgang WHERE warenausgang.wlokz='0' ".$limit. " ";
        $rst=$mysqli->query($sql);
        if (mysqli_num_rows($rst) > 0) {
            $row_rst = $rst->fetch_assoc();
            return $row_rst['anzahl'];

        }else{
            return 0;
        }
    }
    function GetJsonWareneingangslist(){

        if (count($this->data)>0){
            foreach ($this->data as $item){
                //"ID","Datum","Beschreibung","Lieferant","Abladeort","Status"
                $w= new ifisWarenausgang( $item['waid']);
                $r['aaData'][]=array(
                    $item['waid'],
                    substr($item['wdatum'],0,16),
                    $item['wdesc'],
                    $item['wcustomer'],
                    $item['wlocation'],
                    $item['auftragsnummer'],
                    $item['lieferscheinnummer'],
                    $w->GetCurrentState(),
                    $item['wemail']);
            }
        } else{
            $r['aaData']=array();
        }

        return json_encode($r);
    }
    function GetCountWareneingangToday(){
        $this->LoadData( date("Y-m-d",time()) );
        if (count($this->data)>0){
            $h="<span class=\"label label-default pull-right\">".count($this->data)."</span> ";
            AddSessionMessage("info","Warenausgänge von heute geladen","Datenbankabfrage");
        }else{
            $h="";
        }
        return $h;
    }

}
class ifisWarenausgang{
    public $waid=0;
    public $data=array();
    public $history=array();
    public $documents=array();
    function __construct($id=0)
    {
        if ($id>0){
            $this->waid=$id;
        }
        $this->LoadData();
    }
    function LoadData(){
        include ("../apps/dbconnection.php");
        $sql="SELECT * FROM warenausgang WHERE warenausgang.waid='$this->waid'  ";
        $rst=$mysqli->query($sql);
        if (mysqli_affected_rows($mysqli)>0){
            $this->data=$rst->fetch_assoc();
            $this->LoadHistory();
            $this->LoadDocumentData();
        }else{
            $this->data=array();
        }
    }
    function LoadHistory(){
        include ("../apps/dbconnection.php");
        $sql="SELECT * FROM warenausgangworkflow WHERE warenausgangworkflow.lokz='0' AND warenausgangworkflow.waid='".$this->waid."' ORDER BY warenausgangworkflow.wdatum DESC LIMIT 0,1000";
        $rst=$mysqli->query($sql);
        if (mysqli_num_rows($rst) > 0) {
            $row_rst = $rst->fetch_assoc();
            $this->data['currentstate']=$row_rst['wnotes'];
            do{
                $this->history[]=$row_rst;
            } while ($row_rst = $rst->fetch_assoc());

        }else{
            $this->history=array();
        }
    }
    function SaveData($POST){
        if (isset($POST['waid']) && $POST['waid']>0){
            $this->waid=$POST['waid'];
            include ("../apps/dbconnection.php");

            $this->data['wdesc']=utf8_decode($POST['wdesc']);
            $this->data['wcustomer']=utf8_decode($POST['wcustomer']);
            $this->data['wemail']=$POST['wemail'];
            $this->data['wlocation']=utf8_decode($POST['wlocation']);
            $this->data['wdelivery']=utf8_decode($POST['wdelivery']);

            $this->data['wdefective']=$POST['wdefective'];
            $this->data['wnotes']=utf8_decode($POST['wnotes']);

            $this->data['auftragsnummer']=utf8_decode($POST['auftragsnummer']);
            $this->data['lieferscheinnummer']=utf8_decode($POST['lieferscheinnummer']);
            $this->data['nettols']=utf8_decode($POST['nettols']);
            $this->data['nettoproduktion']=utf8_decode($POST['nettoproduktion']);
            $this->data['anzahlve']=utf8_decode($POST['anzahlve']);
            $this->data['verpackungsart']=utf8_decode($POST['verpackungsart']);
            $this->data['produkt']=utf8_decode($POST['produkt']);
            $this->data['charge']=utf8_decode($POST['charge']);
            $this->data['gewicht']=utf8_decode($POST['gewicht']);



            //auftragsnummer		aussenlager	lieferung	nettols
            //nettoproduktion	anzahlve	verpackungsart	verpackungsgewicht	produkt	charge	linie	abfuellung	gewicht

            $updateSQL = sprintf("
                UPDATE warenausgang SET wdesc=%s, wemail=%s, wcustomer=%s, wlocation=%s, wdelivery=%s, wdefective=%s, wnotes=%s,
                auftragsnummer=%s, lieferscheinnummer=%s,nettols=%s,nettoproduktion=%s,anzahlve=%s,verpackungsart=%s,produkt=%s,
                charge=%s, gewicht=%s
                WHERE waid=%s",
                GetSQLValueString($this->data['wdesc'],"text"),
                GetSQLValueString($this->data['wemail'],"text"),
                GetSQLValueString($this->data['wcustomer'],"text"),
                GetSQLValueString($this->data['wlocation'],"text"),
                GetSQLValueString($this->data['wdelivery'],"text"),
                GetSQLValueString($this->data['wdefective'],"text"),
                GetSQLValueString($this->data['wnotes'],"text"),

                GetSQLValueString($this->data['auftragsnummer'],"text"),
                GetSQLValueString($this->data['lieferscheinnummer'],"text"),
                GetSQLValueString($this->data['nettols'],"text"),
                GetSQLValueString($this->data['nettoproduktion'],"text"),
                GetSQLValueString($this->data['anzahlve'],"text"),
                GetSQLValueString($this->data['verpackungsart'],"text"),
                GetSQLValueString($this->data['produkt'],"text"),
                GetSQLValueString($this->data['charge'],"text"),
                GetSQLValueString($this->data['gewicht'],"text"),


                $this->waid);
            $rst = $mysqli->query($updateSQL);
            if ($mysqli->error) {
                $_SESSION['x'].=$updateSQL;
                AddSessionMessage("danger","Änderung wurde nicht angelegt, da ein Fehler aufgetreten ist.","Fehlermeldung");
            }else{
                AddSessionMessage("success", "Wareneingang wurde gespeichert.", "Speichern");
                $this->ChangeLog("Änderung am ".date('Y-m-d H:i',time()),1);
                $this->AddNewDocument("Zustand bei Warenübergabe");

            }
        }
    }
    function InsertData($POST){
        if (strlen($POST['wdesc'])>=1){
            include ("../apps/dbconnection.php");
            $updateSQL = sprintf("
                INSERT INTO  warenausgang (`wdesc`,wemail,wcustomer, wdelivery, wlocation, wdefective, wnotes, wdatum, wuser, werk) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
                GetSQLValueString(utf8_decode($POST['wdesc']), "text"),
                GetSQLValueString(utf8_decode($POST['wemail']), "text"),
                GetSQLValueString(utf8_decode($POST['wcustomer']), "text"),
                GetSQLValueString(utf8_decode($POST['wdelivery']), "text"),
                GetSQLValueString(utf8_decode($POST['wlocation']), "text"),
                GetSQLValueString($this->data['wdefective'],"text"),
                GetSQLValueString($this->data['wnotes'],"text"),
                "now()",
                GetSQLValueString($_SESSION['userid'], "int"),
                GetSQLValueString($_SESSION['classifiedDirectoryID'], "int")
            );
            $rst = $mysqli->query($updateSQL);
            if ($mysqli->error) {
                $_SESSION['x'].=$updateSQL;
                AddSessionMessage("warning","Warenausgang wurde nicht angelegt, da ein Fehler aufgetreten ist.","Fehlermeldung");
            }else{
                AddSessionMessage("success", "Warenausgang <code> " . $mysqli->insert_id . "</code> wurde gespeichert.", "Neu anlegen");
                $this->ChangeLog("Neuanlage",0,$mysqli->insert_id);
                $this->waid=$mysqli->insert_id;
                $this->AddNewDocument("Zustand bei Warenübergabe");
            }
        }else{
            AddSessionMessage("error","Warenausgang wurde nicht angelegt.","Fehlermeldung");
        }
    }
    function SendEmail($mode=""){
        $text = "Die Ware wurde der Spedition übergeben.";
        if ($mode=="callback"){
            return ("hiermit erhalten Sie eine Versandbestätigung für " . $this->data['wcustomer']. ".".chr(10).
                " " . date("Y-m-d - H:i ", time()) .
                "\n " . $text .
                "\nSendungdetails:" . $this->data['wdesc'] .
                "\nAuftrag:" . $this->data['auftragsnummer'] .
                "\nLieferung:" . $this->data['lieferscheinnumer'] .
                "\nZiel:" . $this->data['wlocation'] .
                "\nSpedition:" . $this->data['wdelivery']);
        }else {
            if (strlen(($this->data['wemail'])) > 4) {

                mail(($this->data['wemail']),
                    "ALBA Wareneingang " . $this->data['wcustomer'],
                    " " . date("Y-m-d - H:i ", time()) .
                    "\nHallo" . "\n " . $text .
                    "\nSendungdetails:" . $this->data['wdesc'] .

                    "\nAblageort:" . $this->data['wlocation'] .
                    "\nSpedition:" . $this->data['wdelivery'] .
                    "\nIPv4: " . $_POST["hipv4"] . "-" . $_SERVER["REMOTE_ADDR"] . " - " . $_SERVER['HTTP_X_FORWARDED_FOR']);

                AddSessionMessage("success", "E-Mail mit Wareneingangsnachricht an <code> " . $this->data['wemail'] . " </code> wurde gesendet", "Info");
                $this->ChangeLog("E-Mailversand an " . $this->data['wemail'], 1);
            } else {
                AddSessionMessage("error", "E-Mail konnte nicht gesendet werden, da keine E-Mail-Adresse für <code> WID" . $this->wid . " " . $this->data['wemail'] . " </code> vorhanden ist.", "Abbruch");
            }
        }
    }
    function ChangeLog($notes,$state=1, $id=""){
        if ($id==""){ $id=$this->waid;}
        /*
         * 0=neuanlage
         * 1=info
         * 2=warnung
         * 3=danger
         */
        // wareneingangworkflow
        if (strlen($notes)>=1){
            $us=new ifisUser($_SESSION['userid']);
            include ("../apps/dbconnection.php");
            $updateSQL = sprintf("
                INSERT INTO  warenausgangworkflow (wnotes, wstate, wdatum, wuser, username, werk, waid) VALUES (%s,%s,%s,%s,%s,%s,%s)",
                GetSQLValueString(utf8_decode($notes), "text"),
                GetSQLValueString($state, "int"),
                "now()",
                GetSQLValueString($_SESSION['userid'], "int"),
                GetSQLValueString(utf8_decode($us->data['name']), "text"),
                GetSQLValueString($_SESSION['classifiedDirectoryID'], "int"),
                GetSQLValueString($id, "int")
            );
            $rst = $mysqli->query($updateSQL);
            if ($mysqli->error) {
                $_SESSION['x'].=$updateSQL;
                AddSessionMessage("warning","Status wurde nicht angelegt, da ein Fehler aufgetreten ist.","Fehlermeldung");
            }else{
                AddSessionMessage("success", "Status <code> " . $mysqli->insert_id . "</code> wurde gespeichert.", "Neu anlegen");
            }
        }

    }
    function GetHistoryAsTableBody(){
        $h="<tbody>";
        if (count($this->history)>0){
            foreach($this->history as $item){
                $h.="<tr><td>".substr($item['wdatum'],0,16)."</td>";
                $h.="<td>".$item['wnotes']."</td>";
                $h.="<td>".GetStatus($item['wstate'])."</td>";
                $h.="<td>".$item['username']."</td></tr>";
            }
            $h.="</tbody>";

        }else{
            $h.="<tr><td colspan='4'>keine Verlaufinformationen vorhanden</td></tr>";
            $h.="</tbody>";
        }
        return $h;
    }
    function GetCurrentState(){
        if (is_null($this->data['currentstate'])){
            return "Neuanlage";
        }else{
            return $this->data['currentstate'];
        }
    }

    function AddNewDocument($notes=" "){
        include("../apps/dbconnection.php");
        if (strlen($notes)>=1){
            $us=new ifisUser($_SESSION['userid']);
            include ("../apps/dbconnection.php");

            $state=1;
            $folder="../tmpupload/";
            $files = glob($folder."*.*");
            foreach($files as $value){

                $file = $value;
                // AddSessionMessage("success", "Dokument  <code> " . $file. "</code> wurde hochgeladen und gespeichert.", "Dokumentupload");
                $filename=str_replace($folder,"../warenausgang/".time(),$file);

                if(file_exists($folder.$file)){
                    rename($file,$filename);
                    $updateSQL = sprintf("
                        INSERT INTO  dokumente (dnotes, dstate, ddatum, duser, username, werk, linkedid, dobject, documentlink) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s)",
                        GetSQLValueString(utf8_decode($notes), "text"),
                        GetSQLValueString($state, "int"),
                        "now()",
                        GetSQLValueString($_SESSION['userid'], "int"),
                        GetSQLValueString(utf8_decode($us->data['name']), "text"),
                        GetSQLValueString($_SESSION['classifiedDirectoryID'], "int"),
                        GetSQLValueString($this->waid, "int"),
                        GetSQLValueString("warenausgang", "text"),
                        GetSQLValueString($filename, "text")
                    );
                    $rst = $mysqli->query($updateSQL);

                    if ($mysqli->error) {
                        $_SESSION['x'].=$updateSQL;
                        AddSessionMessage("warning","Dokument wurde nicht angelegt, da ein Fehler aufgetreten ist.","Fehlermeldung");
                    }else{
                        AddSessionMessage("success", "Dokument  <code> " . $filename. "</code> wurde hochgeladen und gespeichert.", "Dokumentupload");
                    $this->ChangeLog("Dokument $filename wurde hochgeladen",1);
                    }
                }
            }
        }

    }
    function LoadDocumentData(){
        if ($this->waid > 0){
            include("../apps/dbconnection.php");
            $updateSQL="SELECT * FROM dokumente
                        WHERE dokumente.linkedid='".$this->waid."' AND dokumente.dobject='warenausgang'
                        ORDER BY dokumente.ddatum ASC";

            $mysqli = new mysqli($hostname_datenbank, $username_datenbank, $password_datenbank, $database_datenbank);
            $rst=$mysqli->query($updateSQL);
            if ($mysqli->error) {
                AddSessionMessage("warning","Dokumente wurden nicht geladen, da ein Fehler aufgetreten ist.","Fehlermeldung");
            }else{
                if ($mysqli->affected_rows>0) {
                    $this->documents=array();
                    $row_rst = $rst->fetch_assoc();
                    do{
                        $this->documents[]= $row_rst;
                    }while ($row_rst=$rst->fetch_assoc());
                }
            }
            $mysqli->close();
        }
    }
    function GetDocumentFiles(){

        if (count($this->documents)>0){
            foreach ($this->documents as $item){
                $r[]=array(
                    'name'=> $item['did'].substr($item['documentlink'],0,-3),
                    'size'=> 145,
                    'type'=> "image/jpg",
                    'file'=> "../warenausgang/".$item['documentlink']);
            }
        }
        return "files:".json_encode($r);
    }
    function GetDocumentList(){
        $h="<tbody>";
        if (count($this->documents)>0){
            foreach($this->documents as $item){
                $h.="<tr><td>".substr($item['ddatum'],0,16)."</td>";
                $h.="<td><img src=' ".$item['documentlink']."' width='145'></td>";
                $h.="<td>".$item['dnotes']."</td>";
                $h.="<td>".GetStatus($item['dstate'])."</td>";
                $h.="<td>".$item['username']."</td></tr>";
            }
            $h.="</tbody>";

        }else{
            $h.="<tr><td colspan='5'>keine Dokumentinformationen vorhanden</td></tr>";
            $h.="</tbody>";
        }
        return $h;
    }
    function UpdatePicture($POST){
        if (count($this->documents)>0){
            include('../apps/dbconnection.php');
            foreach ($this->documents as $i=>$row) {
                if (empty($POST['editdeletepic_'.$i.$this->waid])){
                    $updateSQL = sprintf("UPDATE dokumente SET dnotes=%s WHERE did=%s",
                        GetSQLValueString($POST['editpicnotes_'.$i.$this->waid],"text"),
                        $row['did']);
                    $mysqli = new mysqli($hostname_datenbank, $username_datenbank, $password_datenbank, $database_datenbank);
                    $mysqli->query($updateSQL);
                    if ($mysqli->error) {
                        $_SESSION['x'].=$updateSQL;
                        AddSessionMessage("warning","Dokumente wurde nicht gespeichert, da ein Fehler aufgetreten ist.","Fehlermeldung");
                    }else{
                        AddSessionMessage("success", "Dokument  <code> " . $row['did']. "</code> wurde gespeichert.", "Dokumentverwaltung");
                    }
                }
            }
        }
    }
    function DeletePicture($POST){
        if (count($this->documents)>0){
            include('../apps/dbconnection.php');
            foreach ($this->documents as $i=>$row) {
                if (!empty($POST['editdeletepic_'.$i.$this->waid])){
                    $updateSQL = sprintf("DELETE FROM dokumente WHERE did=%s",$row['did']);
                    $mysqli = new mysqli($hostname_datenbank, $username_datenbank, $password_datenbank, $database_datenbank);
                    $mysqli->query($updateSQL);

                    if (is_file(IFIS_PATH_UPLOAD_WAID . $row['documentlink']) ) {
                        unlink(IFIS_PATH_UPLOAD_WAID . $row['documentlink']);
                    }
                    if ($mysqli->error) {
                        $_SESSION['x'].=$updateSQL;
                        AddSessionMessage("warning","Dokument wurde nicht gelöscht, da ein Fehler aufgetreten ist.","Fehlermeldung");
                    }else{
                        AddSessionMessage("success", "Dokument  <code> " . $row['did']. "</code> wurde gelöscht.", "Dokumentverwaltung");
                    }
                }
            }
        }
    }
}


class ifisAllFauf{
    public $werk=0;
    public $data=array();

    function __construct($id=1501,$state="")
    {
        if ($id>0){
            $this->werk=$id;
        }
        $this->LoadData($state);
    }
    function LoadData($state="",$ftype=""){
        include ("../apps/dbconnection.php");
        $limit="";
        $statesql="";
        $this->data=array();
        if (strlen($_SESSION['datumvon'])>0 && $_SESSION['datumvon']<>"0000-00-00"){ $limit.=" AND fauf.fdatum>='".$_SESSION['datumvon']." 00:00:00'";}
        if (strlen($_SESSION['datumbis'])>0 && $_SESSION['datumbis']<>"0000-00-00"){ $limit.=" AND fauf.fdatum<='".$_SESSION['datumbis']." 23:59:99'";}

        if ($state<>""){ $statesql=" AND fauf.fstate='$state' ";}
        if ($ftype<>""){ $statesql.=" AND fauf.ftype='$ftype' ";}

        $sql="SELECT * FROM fauf WHERE fauf.werk='$this->werk' $statesql AND fauf.flokz=0 $limit";
        $rst=$mysqli->query($sql);
        if (mysqli_affected_rows($mysqli)>0){
            $row_rst = $rst->fetch_assoc();
            do{
                $this->data[]=$row_rst;
            } while ($row_rst = $rst->fetch_assoc());

        }else{
            $this->data=array();
        }
    }
    function GetJsonFaufList($state){
        $this->LoadData($state);
        if (count($this->data)>0){
            foreach ($this->data as $item){
                $r['aaData'][]=array(
                    $item['faufid'],
                    $item['fauf'],
                    $item['material'],
                    GetListFaufType("text",$item['ftype']),
                    $item['fnotes'],
                    $item['gewicht'],
                    $item['ende'],
                    $item['linie'],
                    $item['sap'],
                    GetFaufStatus($item['fstate'])
                    );
            }
        } else{
            $r['aaData']=array();
        }

        return json_encode($r);
    }

    function GetFaufList($fstate=""){
        $this->LoadData($fstate);
        $h="<tbody>";
        if (count($this->data)>0){
            foreach($this->data as $item){
                $h.="<tr><td>".$item['fauf']."</td>";
                $h.="<td>".substr($item['fdatum'],0,10)."</td>";
                $h.="<td>".$item['material']." </td>";
                $h.="<td>".$item['fnotes']."</td>";
                $h.="<td>".$item['gewicht']."</td>";
                $h.="<td>".$item['sap']."</td></tr>";
            }
            $h.="</tbody>";

        }else{
            $h.="<tr><td colspan='6'>keine Fertigungsaufträge vorhanden</td></tr>";
            $h.="</tbody>";
        }
        return $h;
    }
    function GetFaufTypeList($ftype=""){
        $this->LoadData("",$ftype);
        $h="<tbody>";
        if (count($this->data)>0){
            foreach($this->data as $item){
                $h.="<tr><td>".$item['fauf']."</td>";
                $h.="<td>".substr($item['fdatum'],0,10)."</td>";
                $h.="<td>".$item['material']." </td>";
                $h.="<td>".$item['fnotes']."</td>";
                $h.="<td>".$item['gewicht']."</td>";
                $h.="<td>".$item['sap']."</td></tr>";
            }
            $h.="</tbody>";

        }else{
            $h.="<tr><td colspan='6'>keine Fertigungsaufträge vorhanden</td></tr>";
            $h.="</tbody>";
        }
        return $h;
    }

}
class ifisFauf{
    public $faufid=0;
    public $data=array();
    public $soll=array();
    public $fids=array();
    public $history=array();
    public $documents=array();
    function __construct($id=0)
    {
        if ($id>0){
            $this->faufid=$id;
        }
        $this->LoadData();
    }
    function LoadData(){
        include ("../apps/dbconnection.php");
        $sql="SELECT * FROM fauf WHERE fauf.faufid='$this->faufid'  ";
        $rst=$mysqli->query($sql);
        if (mysqli_affected_rows($mysqli)>0){
            $this->data=$rst->fetch_assoc();
            $this->LoadHistory();
            $this->LoadDocumentData();
            $this->LoadFids();
            $this->SetSollValues();
        }else{
            $this->data=array();
        }
    }
    function LoadDataByFauf($fauf){
        include ("../apps/dbconnection.php");
        $sql="SELECT * FROM fauf WHERE fauf.fauf='$fauf'  ";
        $rst=$mysqli->query($sql);
        if (mysqli_affected_rows($mysqli)>0){
            $this->data=$rst->fetch_assoc();
            $this->faufid=$this->data['faufid'];
            $this->LoadHistory();
            $this->LoadDocumentData();
            $this->LoadFids();
            $this->SetSollValues();
        }else{
            $this->data=array();
        }
    }
    function LoadHistory(){
        include ("../apps/dbconnection.php");
        $sql="SELECT * FROM faufworkflow WHERE faufworkflow.lokz='0' AND faufworkflow.faufid='".$this->faufid."' ORDER BY faufworkflow.wdatum DESC LIMIT 0,1000";
        $rst=$mysqli->query($sql);
        if (mysqli_num_rows($rst) > 0) {
            $row_rst = $rst->fetch_assoc();
            $this->data['currentstate']=$row_rst['wnotes'];
            do{
                $this->history[]=$row_rst;
            } while ($row_rst = $rst->fetch_assoc());

        }else{
            $this->history=array();
        }
    }
    function LoadFids(){
        include ("../apps/dbconnection.php");
        $sql="SELECT * FROM fertigungsmeldungen WHERE fertigungsmeldungen.flokz='0' AND fertigungsmeldungen.fauf='".$this->faufid."' ORDER BY fertigungsmeldungen.fdatum DESC LIMIT 0,1000";
        $rst=$mysqli->query($sql);
        if (mysqli_num_rows($rst) > 0) {
            $row_rst = $rst->fetch_assoc();
            do{
                $this->fids[]=$row_rst;
            } while ($row_rst = $rst->fetch_assoc());

        }else{
            $this->fids=array();
        }
    }
    function GetNextFauf(){
        include ("../apps/dbconnection.php");
        $sql="SELECT fauf.fauf FROM fauf  ORDER BY fauf.fauf DESC LIMIT 0,1";
        $rst=$mysqli->query($sql);
        if (mysqli_affected_rows($mysqli)>0){
            $row=$rst->fetch_assoc();
            return $row['fauf']+1;
        }else{
            return 1;
        }
    }
    function Validate($POST){
        $this->data['ftype']=utf8_decode($POST['ftype']);
        $this->data['sap']=utf8_decode($POST['sap']);
        $this->data['material']=utf8_decode($POST['material']);
        $this->data['linie']=utf8_decode($POST['linie']);
        $this->data['gewicht']=utf8_decode($POST['gewicht']);
        $this->data['fnotes']=utf8_decode($POST['fnotes']);

        $this->data['fstate']=utf8_decode($POST['fstate']);

        $this->data['verpackungsart']=utf8_decode($POST['verpackungsart']);
        $this->data['inputquali']=utf8_decode($POST['inputquali']);
        $this->data['prozesshinweise']=utf8_decode($POST['prozesshinweise']);
        $this->data['mfi']=utf8_decode($POST['mfi']);
        $this->data['mfibis']=utf8_decode($POST['mfibis']);
        $this->data['emodul']=utf8_decode($POST['emodul']);
        $this->data['emodulbis']=utf8_decode($POST['emodulbis']);
        $this->data['farbe']=utf8_decode($POST['farbe']);
        $this->data['farbebis']=utf8_decode($POST['farbebis']);
        $this->data['schlag']=utf8_decode($POST['schlag']);
        $this->data['schlagbis']=utf8_decode($POST['schlagbis']);

        $this->data['ifeuchte']=utf8_decode($POST['ifeuchte']);
        $this->data['ifeuchtebis']=utf8_decode($POST['ifeuchtebis']);
        $this->data['afeuchte']=utf8_decode($POST['afeuchte']);
        $this->data['afeuchtebis']=utf8_decode($POST['afeuchtebis']);

        $this->data['schuettdichte']=utf8_decode($POST['schuettdichte']);
        $this->data['schuettdichtebis']=utf8_decode($POST['schuettdichtebis']);





    }
    function SaveData($POST){
        if (isset($POST['faufid']) && $POST['faufid']>0){
            $this->faufid=$POST['faufid'];
            include ("../apps/dbconnection.php");
            $this->Validate($POST);

            $updateSQL = sprintf("
                UPDATE fauf SET sap=%s,ftype=%s,material=%s,linie=%s,
                gewicht=%s, fnotes=%s, fstate=%s, verpackungsart=%s, inputquali=%s, prozesshinweise=%s, 
                mfi=%s, mfibis=%s, emodul=%s,emodulbis=%s,farbe=%s, farbebis=%s, schlag=%s, schlagbis=%s,
                ifeuchte=%s, ifeuchtebis=%s, afeuchte=%s, afeuchtebis=%s, schuettdichte=%s, schuettdichtebis=%s
                WHERE faufid=%s",
                GetSQLValueString($this->data['sap'],"text"),
                GetSQLValueString($this->data['ftype'],"text"),
                GetSQLValueString($this->data['material'],"text"),
                GetSQLValueString($this->data['linie'],"text"),
                GetSQLValueString($this->data['gewicht'],"text"),
                GetSQLValueString($this->data['fnotes'],"text"),

                GetSQLValueString($this->data['fstate'],"int"),

                GetSQLValueString($this->data['verpackungsart'],"text"),
                GetSQLValueString($this->data['inputquali'],"text"),
                GetSQLValueString($this->data['prozesshinweise'],"text"),
                GetSQLValueString($this->data['mfi'],"text"),
                GetSQLValueString($this->data['mfibis'],"text"),
                GetSQLValueString($this->data['emodul'],"text"),
                GetSQLValueString($this->data['emodulbis'],"text"),
                GetSQLValueString($this->data['farbe'],"text"),
                GetSQLValueString($this->data['farbebis'],"text"),
                GetSQLValueString($this->data['schlag'],"text"),
                GetSQLValueString($this->data['schlagbis'],"text"),

                GetSQLValueString($this->data['ifeuchte'],"text"),
                GetSQLValueString($this->data['ifeuchtebis'],"text"),
                GetSQLValueString($this->data['afeuchte'],"text"),
                GetSQLValueString($this->data['afeuchtebis'],"text"),
                GetSQLValueString($this->data['schuettdichte'],"text"),
                GetSQLValueString($this->data['schuettdichtebis'],"text"),

                $this->faufid);
            $rst = $mysqli->query($updateSQL);
            if ($mysqli->error) {
                $_SESSION['x'].=$updateSQL;
                AddSessionMessage("danger","Änderung wurde nicht angelegt, da ein Fehler aufgetreten ist.","Fehlermeldung");
            }else{
                AddSessionMessage("success", "Fertigungsauftrag wurde gespeichert.", "Speichern");
                $this->ChangeLog("Änderung am ".date('Y-m-d H:i',time()),1);
                $this->AddNewDocument("Dokumentablage");

            }
        }
    }
    function InsertData($POST){
        if (strlen($POST['ftype'])>=1){
            include ("../apps/dbconnection.php");
            $this->Validate($POST);
            $POST['fauf']=$this->GetNextFauf();
            $updateSQL = sprintf("
                INSERT INTO  fauf (sap,`fauf`, ftype, material,linie, gewicht,ende,fstate,
                 verpackungsart,inputquali, prozesshinweise, mfi, mfibis, emodul, emodulbis, farbe, farbebis,schlag, schlagbis,
                 ifeuchte, ifeuchtebis, afeuchte, afeuchtebis, schuettdichte,schuettdichtebis,
                 fnotes, fdatum, fuser, werk) 
                VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s, %s,  %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                GetSQLValueString($POST['sap'],"text"),
                GetSQLValueString($POST['fauf'],"text"),
                GetSQLValueString($this->data['ftype'],"text"),
                GetSQLValueString($POST['material'],"text"),
                GetSQLValueString($POST['linie'],"text"),
                GetSQLValueString($POST['gewicht'],"text"),
                GetSQLValueString($POST['ende'],"text"),
                GetSQLValueString(0,"int"),

                GetSQLValueString($this->data['verpackungsart'],"text"),
                GetSQLValueString($this->data['inputquali'],"text"),
                GetSQLValueString($this->data['prozesshinweise'],"text"),
                GetSQLValueString($this->data['mfi'],"text"),
                GetSQLValueString($this->data['mfibis'],"text"),
                GetSQLValueString($this->data['emodul'],"text"),
                GetSQLValueString($this->data['emodulbis'],"text"),
                GetSQLValueString($this->data['farbe'],"text"),
                GetSQLValueString($this->data['farbebis'],"text"),
                GetSQLValueString($this->data['schlag'],"text"),
                GetSQLValueString($this->data['schlagbis'],"text"),

                GetSQLValueString($this->data['ifeuchte'],"text"),
                GetSQLValueString($this->data['ifeuchtebis'],"text"),
                GetSQLValueString($this->data['afeuchte'],"text"),
                GetSQLValueString($this->data['afeuchtebis'],"text"),

                GetSQLValueString($this->data['schuettdichte'],"text"),
                GetSQLValueString($this->data['schuettdichtebis'],"text"),

                GetSQLValueString($POST['fnotes'],"text"),
                "now()",
                GetSQLValueString($_SESSION['userid'], "int"),
                GetSQLValueString($_SESSION['classifiedDirectoryID'], "int")
            );

            $rst = $mysqli->query($updateSQL);
            if ($mysqli->error) {
                $_SESSION['x'].=$updateSQL;
                AddSessionMessage("warning","Fertigungsauftrag wurde nicht angelegt, da ein Fehler aufgetreten ist.","Fehlermeldung");
            }else{
                AddSessionMessage("success", "Fertigungsauftrag <code> " . $mysqli->insert_id . "</code> wurde gespeichert.", "Neu anlegen");
                $this->ChangeLog("Neuanlage",0,$mysqli->insert_id);
                $this->faufid=$mysqli->insert_id;
                $this->AddNewDocument("Dokumentablage");
            }
        }else{
            AddSessionMessage("error","Fertigungsauftrag wurde nicht angelegt.","Fehlermeldung");
        }
    }
    function SendEmail($mode=""){
        $text = "Der Status des Fertigungsauftrages wurde geändert.";
        if ($mode=="callback"){
            return ("hiermit erhalten Sie eine Information für " . $this->data['fauf']. ".".chr(10).
                " " . date("Y-m-d - H:i ", time()) .
                "\n " . $text );
        }else {
            if (strlen(($this->data['wemail'])) > 4) {

                mail(($this->data['wemail']),
                    "ALBA Fertigungsplanung " . $this->data['fauf'],
                    " " . date("Y-m-d - H:i ", time()) .
                    "\n" . "\n " . $text .

                    "\nIPv4: " . $_POST["hipv4"] . "-" . $_SERVER["REMOTE_ADDR"] . " - " . $_SERVER['HTTP_X_FORWARDED_FOR']);

                AddSessionMessage("success", "E-Mail mit Fertigungsstatus an <code> " . $this->data['wemail'] . " </code> wurde gesendet", "Info");
                $this->ChangeLog("E-Mailversand an " . $this->data['wemail'], 1);
            } else {
                AddSessionMessage("error", "E-Mail konnte nicht gesendet werden, da keine E-Mail-Adresse für <code> WID" . $this->wid . " " . $this->data['wemail'] . " </code> vorhanden ist.", "Abbruch");
            }
        }
    }
    function ChangeLog($notes,$state=1, $id=""){
        if ($id==""){ $id=$this->faufid;}
        /*
         * 0=neuanlage
         * 1=info
         * 2=warnung
         * 3=danger
         */
        // wareneingangworkflow
        if (strlen($notes)>=1){
            $us=new ifisUser($_SESSION['userid']);
            include ("../apps/dbconnection.php");
            $updateSQL = sprintf("
                INSERT INTO  faufworkflow (wnotes, wstate, wdatum, wuser, username, werk, faufid) VALUES (%s,%s,%s,%s,%s,%s,%s)",
                GetSQLValueString(utf8_decode($notes), "text"),
                GetSQLValueString($state, "int"),
                "now()",
                GetSQLValueString($_SESSION['userid'], "int"),
                GetSQLValueString(utf8_decode($us->data['name']), "text"),
                GetSQLValueString($_SESSION['classifiedDirectoryID'], "int"),
                GetSQLValueString($id, "int")
            );
            $rst = $mysqli->query($updateSQL);
            if ($mysqli->error) {
                $_SESSION['x'].=$updateSQL;
                AddSessionMessage("warning","Status wurde nicht angelegt, da ein Fehler aufgetreten ist.","Fehlermeldung");
            }else{
                AddSessionMessage("success", "Status <code> " . $mysqli->insert_id . "</code> wurde gespeichert.", "Neu anlegen");
            }
        }

    }
    function GetHistoryAsTableBody(){
        $h="<tbody>";
        if (count($this->history)>0){
            foreach($this->history as $item){
                $h.="<tr><td>".substr($item['wdatum'],0,16)."</td>";
                $h.="<td>".$item['wnotes']."</td>";
                $h.="<td>".GetStatus($item['wstate'])."</td>";
                $h.="<td>".$item['username']."</td></tr>";
            }
            $h.="</tbody>";

        }else{
            $h.="<tr><td colspan='4'>keine Verlaufinformationen vorhanden</td></tr>";
            $h.="</tbody>";
        }
        return $h;
    }
    function GetFidsAsTableBody(){
        $h="<tbody>";
        if (count($this->fids)>0){
            foreach($this->fids as $item){
                $h.="<tr><td>".substr($item['wdatum'],0,16)."</td>";
                $h.="<td>".$item['gebindeid']."</td>";
                $h.="<td>".$item['material']."</td>";
                $h.="<td>".$item['fnotes']."</td>";
                $h.="<td>".$item['gewicht']."</td>";
                $h.="<td>".$item['sap']."</td></tr>";
            }
            $h.="</tbody>";

        }else{
            $h.="<tr><td colspan='6'>keine Rückmeldungen vorhanden</td></tr>";
            $h.="</tbody>";
        }
        return $h;
    }
    function GetCurrentState(){
        if (is_null($this->data['currentstate'])){
            return "kein Status vorhanden";
        }else{
            return $this->data['currentstate'];
        }
    }
    function GetFaufType(){
        return GetListFaufType("text",$this->data['ftype']);
    }

    function SetSollValues(){
        $this->soll['mfi']= $this->data['mfi']." bis ".$this->data['mfibis'];
        $this->soll['ifeuchte']= $this->data['ifeuchte']." bis ".$this->data['ifeuchtebis'];
        $this->soll['afeuchte']= $this->data['afeuchte']." bis ".$this->data['afeuchtebis'];
        $this->soll['schlag']= $this->data['schlag']." bis ".$this->data['schlagbis'];
        $this->soll['emodul']= $this->data['emodul']." bis ".$this->data['emodulbis'];
        $this->soll['schuettdichte']= $this->data['schuettdichte']." bis ".$this->data['schuettdichtebis'];
        $this->soll['farbe']= $this->data['farbe']." bis ".$this->data['farbebis'];
    }
    function AddNewDocument($notes=" "){
        include("../apps/dbconnection.php");
        if (strlen($notes)>=1){
            $us=new ifisUser($_SESSION['userid']);
            include ("../apps/dbconnection.php");

            $state=1;
            $folder="../tmpupload/";
            $files = glob($folder."*.*");
            foreach($files as $value){

                $file = $value;
                // AddSessionMessage("success", "Dokument  <code> " . $file. "</code> wurde hochgeladen und gespeichert.", "Dokumentupload");
                $filename=str_replace($folder,"../fauf/".time(),$file);

                if(file_exists($folder.$file)){
                    rename($file,$filename);
                    $updateSQL = sprintf("
                        INSERT INTO  dokumente (dnotes, dstate, ddatum, duser, username, werk, linkedid, dobject, documentlink) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s)",
                        GetSQLValueString(utf8_decode($notes), "text"),
                        GetSQLValueString($state, "int"),
                        "now()",
                        GetSQLValueString($_SESSION['userid'], "int"),
                        GetSQLValueString(utf8_decode($us->data['name']), "text"),
                        GetSQLValueString($_SESSION['classifiedDirectoryID'], "int"),
                        GetSQLValueString($this->faufid, "int"),
                        GetSQLValueString("fauf", "text"),
                        GetSQLValueString($filename, "text")
                    );
                    $rst = $mysqli->query($updateSQL);

                    if ($mysqli->error) {
                        $_SESSION['x'].=$updateSQL;
                        AddSessionMessage("warning","Dokument wurde nicht angelegt, da ein Fehler aufgetreten ist.","Fehlermeldung");
                    }else{
                        AddSessionMessage("success", "Dokument  <code> " . $filename. "</code> wurde hochgeladen und gespeichert.", "Dokumentupload");
                        $this->ChangeLog("Dokument $filename wurde hochgeladen",1);
                    }
                }
            }
        }

    }
    function LoadDocumentData(){
        if ($this->faufid > 0){
            include("../apps/dbconnection.php");
            $updateSQL="SELECT * FROM dokumente
                        WHERE dokumente.linkedid='".$this->faufid."' AND dokumente.dobject='fauf'
                        ORDER BY dokumente.ddatum ASC";

            $mysqli = new mysqli($hostname_datenbank, $username_datenbank, $password_datenbank, $database_datenbank);
            $rst=$mysqli->query($updateSQL);
            if ($mysqli->error) {
                AddSessionMessage("warning","Dokumente wurden nicht geladen, da ein Fehler aufgetreten ist.","Fehlermeldung");
            }else{
                if ($mysqli->affected_rows>0) {
                    $this->documents=array();
                    $row_rst = $rst->fetch_assoc();
                    do{
                        $this->documents[]= $row_rst;
                    }while ($row_rst=$rst->fetch_assoc());
                }
            }
            $mysqli->close();
        }
    }
    function GetDocumentFiles(){

        if (count($this->documents)>0){
            foreach ($this->documents as $item){
                $r[]=array(
                    'name'=> $item['did'].substr($item['documentlink'],0,-3),
                    'size'=> 145,
                    'type'=> "image/jpg",
                    'file'=> "../fauf/".$item['documentlink']);
            }
        }
        return "files:".json_encode($r);
    }
    function GetDocumentList(){
        $h="<tbody>";
        if (count($this->documents)>0){
            foreach($this->documents as $item){
                $h.="<tr><td>".substr($item['ddatum'],0,16)."</td>";
                $h.="<td><img src=' ".$item['documentlink']."' width='145'></td>";
                $h.="<td>".$item['dnotes']."</td>";
                $h.="<td>".GetStatus($item['dstate'])."</td>";
                $h.="<td>".$item['username']."</td></tr>";
            }
            $h.="</tbody>";

        }else{
            $h.="<tr><td colspan='5'>keine Dokumentinformationen vorhanden</td></tr>";
            $h.="</tbody>";
        }
        return $h;
    }
}


class ifisAllFid{
    public $werk=0;
    public $data=array();

    function __construct($id=1501,$state="")
    {
        if ($id>0){
            $this->werk=$id;
        }
        $this->LoadData($state);
    }
    function LoadData($state=""){
        include ("../apps/dbconnection.php");
        $limit="";
        $statesql="";
        $this->data=array();
        if (strlen($_SESSION['datumvon'])>0 && $_SESSION['datumvon']<>"0000-00-00"){ $limit.=" AND fertigungsmeldungen.fdatum>='".$_SESSION['datumvon']." 00:00:00'";}
        if (strlen($_SESSION['datumbis'])>0 && $_SESSION['datumbis']<>"0000-00-00"){ $limit.=" AND fertigungsmeldungen.fdatum<='".$_SESSION['datumbis']." 23:59:99'";}

        if (strlen($_SESSION['gebindeID'])>0 ){ $limit.=" AND fertigungsmeldungen.gebindeid='".$_SESSION['gebindeID']."'";}

        if (strlen($_SESSION['Smaterial'])>0 ){ $limit.=" AND fertigungsmeldungen.material='".$_SESSION['Smaterial']."'";}



        if ($state<>""){ $statesql=" AND fertigungsmeldungen.fstate='$state' ";}


        $sql="SELECT * FROM fertigungsmeldungen WHERE fertigungsmeldungen.werk='$this->werk' $statesql AND fertigungsmeldungen.flokz=0 $limit 
          ORDER BY fertigungsmeldungen.gebindeid DESC LIMIT 1000";
        $rst=$mysqli->query($sql);
        if (mysqli_affected_rows($mysqli)>0){
            $row_rst = $rst->fetch_assoc();
            do{
                $this->data[]=$row_rst;
            } while ($row_rst = $rst->fetch_assoc());

        }else{
            $this->data=array();
        }
    }
    function GetJsonList($state){
        $this->LoadData($state);
        if (count($this->data)>0){
            foreach ($this->data as $item){
                $r['aaData'][]=array(
                    $item['fid'],
                    $item['gebindeid'],
                    $item['fauf'],
                    $item['material'],
                    $item['fnotes'],
                    $item['gewicht'],
                    $item['ende'],
                    $item['linie'],
                    $item['sap'],
                    $item['q'],
                    GetListFidStatus("text",($item['fstate']))
                );
            }
        } else{
            $r['aaData']=array();
        }

        return json_encode($r);
    }
    function GetJsonQSList($state){
        $this->LoadData($state);
        if (count($this->data)>0){
            foreach ($this->data as $item){
                $r['aaData'][]=array(
                    $item['fid'],
                    $item['gebindeid'],
                    $item['fauf'],
                    $item['material'],
                    $item['gewicht'],
                    $item['mfi'],
                    $item['farbe'],
                    $item['ifeuchte'],
                    $item['afeuchte'],
                    $item['emodul'],
                    $item['schlag'],
                    $item['q'],
                    GetListFidStatus("text",$item['fstate'])
                );
            }
        } else{
            $r['aaData']=array();
        }

        return json_encode($r);
    }
    function GetList($fstate=""){
        $this->LoadData($fstate);
        $h="<tbody>";
        if (count($this->data)>0){
            foreach($this->data as $item){
                $h.="<tr><td>".$item['gebindeid']."</td>";
                $h.="<td>".substr($item['datum'],0,16)."</td>";
                $h.="<td>".$item['material']." </td>";
                $h.="<td>".$item['fauf']." </td>";
                $h.="<td>".$item['fnotes']."</td>";
                $h.="<td>".$item['gewicht']."</td>";
                $h.="<td>".$item['sap']."</td></tr>";
            }
            $h.="</tbody>";

        }else{
            $h.="<tr><td colspan='7'>keine Rückmeldungen vorhanden</td></tr>";
            $h.="</tbody>";
        }
        return $h;
    }

}
class ifisFid{
    public $fid=0;
    public $data=array();
    public $soll=array();
    public $history=array();
    public $documents=array();
    function __construct($id=0)
    {
        if ($id>0){
            $this->fid=$id;
        }
        $this->LoadData();
    }
    function LoadData(){
        include ("../apps/dbconnection.php");
        $sql="SELECT * FROM fertigungsmeldungen WHERE fertigungsmeldungen.fid='$this->fid'  ";
        $rst=$mysqli->query($sql);
        if (mysqli_affected_rows($mysqli)>0){
            $this->data=$rst->fetch_assoc();
            $this->LoadHistory();
            $this->LoadDocumentData();
            $this->SetFaufSollValues();
        }else{
            $this->data=array();
        }
    }
    function GetNextGebindeId(){
        include ("../apps/dbconnection.php");
        $sql="SELECT fertigungsmeldungen.gebindeid FROM fertigungsmeldungen  ORDER BY fertigungsmeldungen.gebindeid DESC LIMIT 1";
        $rst=$mysqli->query($sql);
        if (mysqli_affected_rows($mysqli)>0){
            $row=$rst->fetch_assoc();
            return intval($row['gebindeid']+1);
        }else{
            return 1;
        }
    }
    function GetFidFaufType(){
        $fauf= new ifisFauf();
        $fauf->LoadDataByFauf($this->data['fauf']);
        return $fauf->GetFaufType();
    }
    function SetFaufSollValues(){
        $fauf= new ifisFauf();
        $fauf->LoadDataByFauf($this->data['fauf']);
        $this->soll=$fauf->soll;
    }
    function LoadHistory($wstate=""){
        include ("../apps/dbconnection.php");
        if ($wstate=="") {
            $sql = "SELECT * FROM fidworkflow WHERE fidworkflow.lokz='0' 
              AND fidworkflow.fid='" . $this->fid . "' ORDER BY fidworkflow.wdatum DESC LIMIT 0,100";
        }else{
            $sql = "SELECT * FROM fidworkflow WHERE fidworkflow.lokz='0' 
              AND fidworkflow.fid='" . $this->fid . "' AND fidworkflow.wstate='" . $wstate . "'
             ORDER BY fidworkflow.wdatum DESC LIMIT 0,100";

        }
        $rst=$mysqli->query($sql);
        if ($mysqli->affected_rows>0) {
            $row_rst = $rst->fetch_assoc();
            $this->data['currentstate']=GetListFidStatus("text",$row_rst['wstate']);
            do{
                $this->history[]=$row_rst;
            } while ($row_rst = $rst->fetch_assoc());
            return $mysqli->affected_rows;
        }else{
            $this->history=array();
            return 0;
        }
    }

    function UpdateMaterial($material){
        include ("../apps/dbconnection.php");
        $updateSQL=sprintf("UPDATE fertigungsmeldungen SET material=%s WHERE fid=%s",
            GetSQLValueString($material,"text"),
            $this->fid);
        $mysqli->query($updateSQL);
        if ($mysqli->error) {
            $_SESSION['x'].=$updateSQL;
            AddSessionMessage("danger","Materialänderung wurde nicht angelegt, da ein Fehler aufgetreten ist.","Fehlermeldung");
        }
    }

    function UpdateFstate($wstate){
        include ("../apps/dbconnection.php");
        $updateSQL=sprintf("UPDATE fertigungsmeldungen SET fstate=%s WHERE fid=%s",
            GetSQLValueString($wstate,"text"),
            $this->fid);
        $mysqli->query($updateSQL);
        if ($mysqli->error) {
            $_SESSION['x'].=$updateSQL;
            AddSessionMessage("danger","Status $wstate wurde nicht angelegt, da ein Fehler aufgetreten ist.","Fehlermeldung");
        }
    }

    function UpdateEndedatum($datum){
        include ("../apps/dbconnection.php");
        $updateSQL=sprintf("UPDATE fertigungsmeldungen SET ende=%s WHERE fid=%s",
            GetSQLValueString($datum,"text"),
            $this->fid);
        $mysqli->query($updateSQL);
        if ($mysqli->error) {
            $_SESSION['x'].=$updateSQL;
            AddSessionMessage("danger","Fertigungsdatum wurde nicht geändert, da ein Fehler aufgetreten ist.","Fehlermeldung");
        }
    }

    function UpdateFnotes($notes){
        include ("../apps/dbconnection.php");
        $updateSQL=sprintf("UPDATE fertigungsmeldungen SET fnotes=%s WHERE fid=%s",
            GetSQLValueString($notes,"text"),
            $this->fid);
        $mysqli->query($updateSQL);
        if ($mysqli->error) {
            $_SESSION['x'].=$updateSQL;
            AddSessionMessage("danger","Materialänderung wurde nicht angelegt, da ein Fehler aufgetreten ist.","Fehlermeldung");
        }
    }

    function Validate($POST){
        $this->data['ftype']=utf8_decode($POST['ftype']);
        $this->data['sap']=utf8_decode($POST['sap']);
        $this->data['fauf']=utf8_decode($POST['fauf']);
        $this->data['material']=utf8_decode($POST['material']);
        $this->data['linie']=utf8_decode($POST['linie']);
        $this->data['gewicht']=utf8_decode($POST['gewicht']);
        $this->data['fnotes']=utf8_decode($POST['fnotes']);

        $this->data['fstate']=utf8_decode($POST['fstate']);

        $this->data['verpackungsart']=utf8_decode($POST['verpackungsart']);
        $this->data['inputquali']=utf8_decode($POST['inputquali']);
        $this->data['prozesshinweise']=utf8_decode($POST['prozesshinweise']);
        $this->data['mfi']=utf8_decode($POST['mfi']);
        $this->data['mfibis']=utf8_decode($POST['mfibis']);
        $this->data['emodul']=utf8_decode($POST['emodul']);
        $this->data['emodulbis']=utf8_decode($POST['emodulbis']);
        $this->data['farbe']=utf8_decode($POST['farbe']);
        $this->data['farbebis']=utf8_decode($POST['farbebis']);
        $this->data['schlag']=utf8_decode($POST['schlag']);
        $this->data['schlagbis']=utf8_decode($POST['schlagbis']);

        $this->data['ifeuchte']=utf8_decode($POST['ifeuchte']);
        $this->data['ifeuchtebis']=utf8_decode($POST['ifeuchtebis']);
        $this->data['afeuchte']=utf8_decode($POST['afeuchte']);
        $this->data['afeuchtebis']=utf8_decode($POST['afeuchtebis']);

        $this->data['mfimethode']=utf8_decode($POST['mfimethode']);
        $this->data['mfimethode2']=utf8_decode($POST['mfimethode2']);
        $this->data['mfi2']=utf8_decode($POST['mfi2']);
        $this->data['mfibis2']=utf8_decode($POST['mfibis2']);

        if ($POST['Feinpartikel']==1 ) {$this->data['Feinpartikel']=1; } else{ $this->data['Feinpartikel']=0;}
        if ($POST['Oberflachendefekte']==1 ) {$this->data['Oberflachendefekte']=1; } else{ $this->data['Oberflachendefekte']=0;}
        if ($POST['Materialunvertraglichkeit']==1 ) {$this->data['Materialunvertraglichkeit']=1; } else{ $this->data['Materialunvertraglichkeit']=0;}
        if ($POST['VerbranntesGranulat']==1 ) {$this->data['VerbranntesGranulat']=1; } else{ $this->data['VerbranntesGranulat']=0;}
        if ($POST['FarbigeSilikonpartikel']==1 ) {$this->data['FarbigeSilikonpartikel']=1; } else{ $this->data['FarbigeSilikonpartikel']=0;}
        if ($POST['Materialubergange']==1 ) {$this->data['Materialubergange']=1; } else{ $this->data['Materialubergange']=0;}
        if ($POST['Farbausfall']==1 ) {$this->data['Farbausfall']=1; } else{ $this->data['Farbausfall']=0;}


        $this->data['farbel']=utf8_decode($POST['farbel']);
        $this->data['farbea']=utf8_decode($POST['farbea']);
        $this->data['farbeb']=utf8_decode($POST['farbeb']);
        $this->data['strang']=utf8_decode($POST['strang']);
        $this->data['zugdehnung']=utf8_decode($POST['zugdehnung']);
        $this->data['zugfestigkeit']=utf8_decode($POST['zugfestigkeit']);
        $this->data['dichte']=utf8_decode($POST['dichte']);
    }
    function SaveData($POST){
        if (isset($POST['fid']) && $POST['fid']>0){
            $this->faufid=$POST['fid'];
            include ("../apps/dbconnection.php");
            $this->Validate($POST);

            $updateSQL = sprintf("
                UPDATE fertigungsmeldungen SET ftype=%s, sap=%s,fauf=%s,material=%s,linie=%s,
                gewicht=%s, fnotes=%s, fstate=%s, verpackungsart=%s, inputquali=%s, prozesshinweise=%s, 
                mfi=%s, mfibis=%s, emodul=%s,emodulbis=%s,farbe=%s, farbebis=%s, schlag=%s, schlagbis=%s,
                ifeuchte=%s, ifeuchtebis=%s, afeuchte=%s, afeuchtebis=%s, mfi2=%s, mfibis2=%s, mfimethode=%s,mfimethode2=%s,
                
                farbel=%s,farbea=%s,farbeb=%s,strang=%s, zugdehnung=%s,zugfestigkeit=%s,dichte=%s,
                 
                 Feinpartikel=%s,Oberflachendefekte=%s,Materialunvertraglichkeit=%s,VerbranntesGranulat=%s,
                 FarbigeSilikonpartikel=%s,Materialubergange=%s,Farbausfall=%s
  
 
                WHERE fid=%s",
                GetSQLValueString($this->data['ftype'],"text"),
                GetSQLValueString($this->data['sap'],"text"),
                GetSQLValueString($this->data['fauf'],"text"),
                GetSQLValueString($this->data['material'],"text"),
                GetSQLValueString($this->data['linie'],"text"),
                GetSQLValueString($this->data['gewicht'],"text"),
                GetSQLValueString($this->data['fnotes'],"text"),

                GetSQLValueString($this->data['fstate'],"int"),

                GetSQLValueString($this->data['verpackungsart'],"text"),
                GetSQLValueString($this->data['inputquali'],"text"),
                GetSQLValueString($this->data['prozesshinweise'],"text"),
                GetSQLValueString($this->data['mfi'],"text"),
                GetSQLValueString($this->data['mfibis'],"text"),
                GetSQLValueString($this->data['emodul'],"text"),
                GetSQLValueString($this->data['emodulbis'],"text"),
                GetSQLValueString($this->data['farbe'],"text"),
                GetSQLValueString($this->data['farbebis'],"text"),
                GetSQLValueString($this->data['schlag'],"text"),
                GetSQLValueString($this->data['schlagbis'],"text"),

                GetSQLValueString($this->data['ifeuchte'],"text"),
                GetSQLValueString($this->data['ifeuchtebis'],"text"),
                GetSQLValueString($this->data['afeuchte'],"text"),
                GetSQLValueString($this->data['afeuchtebis'],"text"),

                GetSQLValueString($this->data['mfi2'],"text"),
                GetSQLValueString($this->data['mfibis2'],"text"),
                GetSQLValueString($this->data['mfimethode'],"text"),
                GetSQLValueString($this->data['mfimethode2'],"text"),

                GetSQLValueString($this->data['farbel'],"text"),
                GetSQLValueString($this->data['farbea'],"text"),
                GetSQLValueString($this->data['farbeb'],"text"),
                GetSQLValueString($this->data['strang'],"text"),
                GetSQLValueString($this->data['zugdehnung'],"text"),
                GetSQLValueString($this->data['zugfestigkeit'],"text"),
                GetSQLValueString($this->data['dichte'],"text"),

                GetSQLValueString($this->data['Feinpartikel'],"int"),
                GetSQLValueString($this->data['Oberflachendefekte'],"int"),
                GetSQLValueString($this->data['Materialunvertraglichkeit'],"int"),
                GetSQLValueString($this->data['VerbranntesGranulat'],"int"),
                GetSQLValueString($this->data['FarbigeSilikonpartikel'],"int"),
                GetSQLValueString($this->data['Materialubergange'],"int"),
                GetSQLValueString($this->data['Farbausfall'],"int"),

                $this->fid);
            $rst = $mysqli->query($updateSQL);
            if ($mysqli->error) {
                $_SESSION['x'].=$updateSQL;
                AddSessionMessage("danger","Änderung wurde nicht angelegt, da ein Fehler aufgetreten ist.","Fehlermeldung");
            }else{
                AddSessionMessage("success", "Rückmeldung wurde gespeichert.", "Speichern");
                $this->ChangeLog("Änderung am ".date('Y-m-d H:i',time()),$this->data['fstate']);
                $this->AddNewDocument("Dokumentablage");

            }
        }
    }
    function InsertData($POST){
        if (strlen($POST['fauf'])>=1){
            include ("../apps/dbconnection.php");
            $this->Validate($POST);
            $POST['gebindeid']=$this->GetNextGebindeId();
            $updateSQL = sprintf("
                INSERT INTO  fertigungsmeldungen (gebindeid, sap,`fauf`, material,linie, gewicht,ende,fstate,ftype,
                 verpackungsart,inputquali, prozesshinweise, mfi, mfibis, emodul, emodulbis, farbe, farbebis,schlag, schlagbis,
                 ifeuchte, ifeuchtebis, afeuchte, afeuchtebis, mfi2, mfibis2, mfimethode, mfimethode2,
                 fnotes, fdatum, fuser, werk) 
                VALUES (%s, %s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                GetSQLValueString($POST['gebindeid'],"text"),
                GetSQLValueString($POST['sap'],"text"),
                GetSQLValueString($POST['fauf'],"text"),
                GetSQLValueString($POST['material'],"text"),
                GetSQLValueString($POST['linie'],"text"),
                GetSQLValueString($POST['gewicht'],"text"),
                GetSQLValueString($POST['ende'],"text"),
                GetSQLValueString(0,"int"),
                GetSQLValueString($this->data['ftype'],"text"),

                GetSQLValueString($this->data['verpackungsart'],"text"),
                GetSQLValueString($this->data['inputquali'],"text"),
                GetSQLValueString($this->data['prozesshinweise'],"text"),
                GetSQLValueString($this->data['mfi'],"text"),
                GetSQLValueString($this->data['mfibis'],"text"),
                GetSQLValueString($this->data['emodul'],"text"),
                GetSQLValueString($this->data['emodulbis'],"text"),
                GetSQLValueString($this->data['farbe'],"text"),
                GetSQLValueString($this->data['farbebis'],"text"),
                GetSQLValueString($this->data['schlag'],"text"),
                GetSQLValueString($this->data['schlagbis'],"text"),

                GetSQLValueString($this->data['ifeuchte'],"text"),
                GetSQLValueString($this->data['ifeuchtebis'],"text"),
                GetSQLValueString($this->data['afeuchte'],"text"),
                GetSQLValueString($this->data['afeuchtebis'],"text"),

                GetSQLValueString($this->data['mfi2'],"text"),
                GetSQLValueString($this->data['mfibis2'],"text"),
                GetSQLValueString($this->data['mfimethode'],"text"),
                GetSQLValueString($this->data['mfimethode2'],"text"),

                GetSQLValueString($POST['fnotes'],"text"),
                "now()",
                GetSQLValueString($_SESSION['userid'], "int"),
                GetSQLValueString($_SESSION['classifiedDirectoryID'], "int")
            );
            $rst = $mysqli->query($updateSQL);
            if ($mysqli->error) {
                $_SESSION['x'].=$updateSQL;
                AddSessionMessage("warning","Rückmeldung wurde nicht angelegt, da ein Fehler aufgetreten ist.","Fehlermeldung");
            }else{
                AddSessionMessage("success", "Rückmeldung <code> " . $mysqli->insert_id . "</code> wurde gespeichert.", "Neu anlegen");
                $this->ChangeLog("Neuanlage",0,$mysqli->insert_id);
                $this->faufid=$mysqli->insert_id;
                $this->AddNewDocument("Dokumentablage");
            }
        }else{
            AddSessionMessage("error","Rückmeldung wurde nicht angelegt.","Fehlermeldung");
        }
    }
    function SendEmail($mode=""){
        $text = "Der Status des Rückmeldung wurde geändert.";
        if ($mode=="callback"){
            return ("hiermit erhalten Sie eine Information für " . $this->data['fauf']. ".".chr(10).
                " " . date("Y-m-d - H:i ", time()) .
                "\n " . $text );
        }else {
            if (strlen(($this->data['wemail'])) > 4) {

                mail(($this->data['wemail']),
                    "ALBA Fertigungsplanung " . $this->data['fauf']. " Gebinde-ID: ".$this->data['gebnindeid'],
                    " " . date("Y-m-d - H:i ", time()) .
                    "\n" . "\n " . $text .

                    "\nIPv4: " . $_POST["hipv4"] . "-" . $_SERVER["REMOTE_ADDR"] . " - " . $_SERVER['HTTP_X_FORWARDED_FOR']);

                AddSessionMessage("success", "E-Mail mit Fertigungsstatus an <code> " . $this->data['wemail'] . " </code> wurde gesendet", "Info");
                $this->ChangeLog("E-Mailversand an " . $this->data['wemail'], 1);
            } else {
                AddSessionMessage("error", "E-Mail konnte nicht gesendet werden, da keine E-Mail-Adresse für <code> WID" . $this->wid . " " . $this->data['wemail'] . " </code> vorhanden ist.", "Abbruch");
            }
        }
    }
    function ChangeLog($notes,$state=1, $fid="",$datum="",$user=""){
        if ($fid==""){ $fid=$this->fid;}

        if (strlen($notes)>=1){

            include ("../apps/dbconnection.php");

            if ($datum==""){$datum="now()";} else{ $datum=GetSQLValueString($datum,"text");}
            if ($user==""){$user=$_SESSION['userid'];}
            $us=new ifisUser($user);

            $updateSQL = sprintf("
                INSERT INTO  fidworkflow (wnotes, wstate, wdatum, wuser, username, werk, fid) VALUES (%s,%s,%s,%s,%s,%s,%s)",
                GetSQLValueString(utf8_decode($notes), "text"),
                GetSQLValueString($state, "int"),
                $datum,
                GetSQLValueString($user, "int"),
                GetSQLValueString(utf8_decode($us->data['name']), "text"),
                GetSQLValueString($_SESSION['classifiedDirectoryID'], "int"),
                GetSQLValueString($fid, "int")
            );
            $rst = $mysqli->query($updateSQL);
            if ($mysqli->error) {
                $_SESSION['x'].=$updateSQL;
                AddSessionMessage("warning","Status wurde nicht angelegt, da ein Fehler aufgetreten ist.","Fehlermeldung");
            }else{
                $this->UpdateFstate($state);
                // AddSessionMessage("success", "Status <code> " . $mysqli->insert_id . "</code> wurde gespeichert.", "Neu anlegen");
            }
        }

    }
    function GetHistoryAsTableBody(){
        $h="<tbody>";
        if (count($this->history)>0){
            foreach($this->history as $item){
                $h.="<tr><td>".substr($item['wdatum'],0,16)."</td>";
                $h.="<td>".$item['wnotes']."</td>";
                $h.="<td>".GetListFidStatus("text",$item['wstate'])."</td>";
                $h.="<td>".$item['username']."</td></tr>";
            }
            $h.="</tbody>";

        }else{
            $h.="<tr><td colspan='4'>keine Verlaufinformationen vorhanden</td></tr>";
            $h.="</tbody>";
        }
        return $h;
    }
    function GetFidsAsTableBody(){
        $h="<tbody>";
        if (count($this->fids)>0){
            foreach($this->fids as $item){
                $h.="<tr><td>".substr($item['wdatum'],0,16)."</td>";
                $h.="<td>".$item['gebindeid']."</td>";
                $h.="<td>".$item['material']."</td>";
                $h.="<td>".$item['fnotes']."</td>";
                $h.="<td>".$item['gewicht']."</td>";
                $h.="<td>".$item['sap']."</td></tr>";
            }
            $h.="</tbody>";

        }else{
            $h.="<tr><td colspan='6'>keine Rückmeldungen vorhanden</td></tr>";
            $h.="</tbody>";
        }
        return $h;
    }
    function GetCurrentState(){
        if (is_null($this->data['currentstate'])){
            return "Neuanlage";
        }else{
            return $this->data['currentstate'];
        }
    }

    function AddNewDocument($notes=" "){
        include("../apps/dbconnection.php");
        if (strlen($notes)>=1){
            $us=new ifisUser($_SESSION['userid']);
            include ("../apps/dbconnection.php");

            $state=1;
            $folder="../tmpupload/";
            $files = glob($folder."*.*");
            foreach($files as $value){

                $file = $value;
                // AddSessionMessage("success", "Dokument  <code> " . $file. "</code> wurde hochgeladen und gespeichert.", "Dokumentupload");
                $filename=str_replace($folder,"../fid/".time(),$file);

                if(file_exists($folder.$file)){
                    rename($file,$filename);
                    $updateSQL = sprintf("
                        INSERT INTO  dokumente (dnotes, dstate, ddatum, duser, username, werk, linkedid, dobject, documentlink) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s)",
                        GetSQLValueString(utf8_decode($notes), "text"),
                        GetSQLValueString($state, "int"),
                        "now()",
                        GetSQLValueString($_SESSION['userid'], "int"),
                        GetSQLValueString(utf8_decode($us->data['name']), "text"),
                        GetSQLValueString($_SESSION['classifiedDirectoryID'], "int"),
                        GetSQLValueString($this->fid, "int"),
                        GetSQLValueString("fid", "text"),
                        GetSQLValueString($filename, "text")
                    );
                    $rst = $mysqli->query($updateSQL);

                    if ($mysqli->error) {
                        $_SESSION['x'].=$updateSQL;
                        AddSessionMessage("warning","Dokument wurde nicht angelegt, da ein Fehler aufgetreten ist.","Fehlermeldung");
                    }else{
                        AddSessionMessage("success", "Dokument  <code> " . $filename. "</code> wurde hochgeladen und gespeichert.", "Dokumentupload");
                        $this->ChangeLog("Dokument $filename wurde hochgeladen",1);
                    }
                }
            }
        }

    }
    function LoadDocumentData(){
        if ($this->fid > 0){
            include("../apps/dbconnection.php");
            $updateSQL="SELECT * FROM dokumente
                        WHERE dokumente.linkedid='".$this->fid."' AND dokumente.dobject='fid'
                        ORDER BY dokumente.ddatum ASC";

            $mysqli = new mysqli($hostname_datenbank, $username_datenbank, $password_datenbank, $database_datenbank);
            $rst=$mysqli->query($updateSQL);
            if ($mysqli->error) {
                AddSessionMessage("warning","Dokumente wurden nicht geladen, da ein Fehler aufgetreten ist.","Fehlermeldung");
            }else{
                if ($mysqli->affected_rows>0) {
                    $this->documents=array();
                    $row_rst = $rst->fetch_assoc();
                    do{
                        $this->documents[]= $row_rst;
                    }while ($row_rst=$rst->fetch_assoc());
                }
            }
            $mysqli->close();
        }
    }
    function GetDocumentFiles(){

        if (count($this->documents)>0){
            foreach ($this->documents as $item){
                $r[]=array(
                    'name'=> $item['did'].substr($item['documentlink'],0,-3),
                    'size'=> 145,
                    'type'=> "image/jpg",
                    'file'=> "../fid/".$item['documentlink']);
            }
        }
        return "files:".json_encode($r);
    }
    function GetDocumentList(){
        $h="<tbody>";
        if (count($this->documents)>0){
            foreach($this->documents as $item){
                $h.="<tr><td>".substr($item['ddatum'],0,16)."</td>";
                $h.="<td><img src=' ".$item['documentlink']."' width='145'></td>";
                $h.="<td>".$item['dnotes']."</td>";
                $h.="<td>".GetStatus($item['dstate'])."</td>";
                $h.="<td>".$item['username']."</td></tr>";
            }
            $h.="</tbody>";

        }else{
            $h.="<tr><td colspan='5'>keine Dokumentinformationen vorhanden</td></tr>";
            $h.="</tbody>";
        }
        return $h;
    }
}


class ifisAllMid{
    public $werk=0;
    public $data=array();

    function __construct($id=1501,$state="")
    {
        if ($id>0){
            $this->werk=$id;
        }
        $this->LoadData($state);
    }
    function LoadData($state=""){
        include ("../apps/dbconnection.php");
        $limit="";
        $statesql="";
        $this->data=array();
        if (strlen($_SESSION['datumvon'])>0 && $_SESSION['datumvon']<>"0000-00-00"){ $limit.=" AND messwertmeldungen.fdatum>='".$_SESSION['datumvon']." 00:00:00'";}
        if (strlen($_SESSION['datumbis'])>0 && $_SESSION['datumbis']<>"0000-00-00"){ $limit.=" AND messwertmeldungen.fdatum<='".$_SESSION['datumbis']." 23:59:99'";}

        if (strlen($_SESSION['gebindeID'])>0 ){ $limit.=" AND messwertmeldungen.gebindeid='".$_SESSION['gebindeID']."'";}

        if (strlen($_SESSION['Smaterial'])>0 ){ $limit.=" AND messwertmeldungen.material='".$_SESSION['Smaterial']."'";}

        if (($_SESSION['Smtype'])>0 ){ $limit.=" AND messwertmeldungen.mtype='".$_SESSION['Smtype']."'";}


        if ($state<>""){ $statesql=" AND messwertmeldungen.fstate='$state' ";}


        $sql="SELECT * FROM messwertmeldungen WHERE messwertmeldungen.werk='$this->werk' $statesql AND messwertmeldungen.flokz=0 $limit 
          ORDER BY messwertmeldungen.fdatum DESC LIMIT 100";
        $rst=$mysqli->query($sql);
        if (mysqli_affected_rows($mysqli)>0){
            $row_rst = $rst->fetch_assoc();
            do{
                $this->data[]=$row_rst;
            } while ($row_rst = $rst->fetch_assoc());

        }else{
            $this->data=array();
        }
    }
    function GetJsonList($state){
        $this->LoadData($state);
        if (count($this->data)>0){
            foreach ($this->data as $item){
                $us=new ifisUser($item['fuser']);

                $r['aaData'][]=array(
                    $item['fid'],
                    $item['fnotes'],
                    $item['material'],
                    $item['mfi'],
                    $item['fauf'],
                    $item['gewicht'],
                    $item['ende'],
                    $item['linie'],
                    $item['sap'],
                    $item['fdatum'],
                    $us->data['name'],
                    GetListMidType("text",($item['mtype']))
                );
            }
        } else{
            $r['aaData']=array();
        }

        return json_encode($r);
    }
    function GetJsonQSList($state){
        $this->LoadData($state);
        if (count($this->data)>0){
            foreach ($this->data as $item){
                $r['aaData'][]=array(
                    $item['fid'],
                    $item['fnotes'],
                    $item['material'],
                    $item['mfi'],
                    $item['fauf'],
                    $item['gewicht'],
                    $item['farbe'],
                    $item['ifeuchte'],
                    $item['afeuchte'],
                    $item['emodul'],
                    $item['schlag'],
                    $item['q'],
                    GetListFidStatus("text",$item['fstate']),
                    GetListMidType("text",($item['mtype']))
                );
            }
        } else{
            $r['aaData']=array();
        }

        return json_encode($r);
    }
    function GetList($fstate=""){
        $this->LoadData($fstate);
        $h="<tbody>";
        if (count($this->data)>0){
            foreach($this->data as $item){
                $h.="<tr><td>".$item['gebindeid']."</td>";
                $h.="<td>".substr($item['datum'],0,16)."</td>";
                $h.="<td>".$item['material']." </td>";
                $h.="<td>".$item['fauf']." </td>";
                $h.="<td>".$item['fnotes']."</td>";
                $h.="<td>".$item['gewicht']."</td>";
                $h.="<td>".$item['sap']."</td></tr>";
            }
            $h.="</tbody>";

        }else{
            $h.="<tr><td colspan='7'>keine Rückmeldungen vorhanden</td></tr>";
            $h.="</tbody>";
        }
        return $h;
    }


    function GetMFRDiagrammData($device=""){
        include ("../apps/dbconnection.php");
        $limit="5";
        $this->data=array();

        $sql="SELECT * FROM messwertmeldungen WHERE messwertmeldungen.fnotes like '$device' AND messwertmeldungen.mtype=1 AND messwertmeldungen.werk='$this->werk'  AND messwertmeldungen.flokz=0 
          ORDER BY messwertmeldungen.fdatum DESC LIMIT $limit ";
        $rst=$mysqli->query($sql);
        if (mysqli_affected_rows($mysqli)>0){
            $row_rst = $rst->fetch_assoc();
            do{
                $this->data[]=$row_rst;
            } while ($row_rst = $rst->fetch_assoc());

        }else{
            $this->data=array();
        }
    }

    function GetListMFR($device=""){
        $this->GetMFRDiagrammData($device);
        $h="<tbody>";
        if (count($this->data)>0){
            foreach($this->data as $item){
                $h.="<tr><td>".$item['fid']."</td>";
                $h.="<td>".substr($item['fdatum'],0,16)."</td>";
                $h.="<td>".$item['mfi']." </td>";
                $h.="<td>".$item['fnotes']." </td>";
                $h.="</tr>";
            }
            $h.="</tbody>";

        }else{
            $h.="<tr><td colspan='4'>keine Meldungen vorhanden.</td></tr>";
            $h.="</tbody>";
        }
        return $h;
    }
    function GetDiagrammMFR($device=""){
        $this->GetMFRDiagrammData($device);
        $i=0;
        if (count($this->data)>0){
            foreach($this->data as $item){
                $i++;
                $h[$i]=array(substr($item['fdatum'],0,16), $item['mfr']);
            }
        }else{
           $h=array();
        }
        return $h;
    }

}
class ifisMid{
    public $fid=0;
    public $data=array();
    public $soll=array();
    public $history=array();
    public $documents=array();
    function __construct($id=0)
    {
        if ($id>0){
            $this->fid=$id;
        }
        $this->LoadData();
    }
    function LoadData(){
        include ("../apps/dbconnection.php");
        $sql="SELECT * FROM messwertmeldungen WHERE messwertmeldungen.fid='$this->fid'  ";
        $rst=$mysqli->query($sql);
        if (mysqli_affected_rows($mysqli)>0){
            $this->data=$rst->fetch_assoc();
            $this->LoadHistory();
            $this->LoadDocumentData();
            $this->SetFaufSollValues();
        }else{
            $this->data=array();
        }
    }
    function GetNextGebindeId(){
        include ("../apps/dbconnection.php");
        $sql="SELECT messwertmeldungen.gebindeid FROM messwertmeldungen  ORDER BY messwertmeldungen.gebindeid DESC LIMIT 1";
        $rst=$mysqli->query($sql);
        if (mysqli_affected_rows($mysqli)>0){
            $row=$rst->fetch_assoc();
            return intval($row['gebindeid']+1);
        }else{
            return 1;
        }
    }
    function GetFidFaufType(){
        $fauf= new ifisFauf();
        $fauf->LoadDataByFauf($this->data['fauf']);
        return $fauf->GetFaufType();
    }
    function SetFaufSollValues(){
        $fauf= new ifisFauf();
        $fauf->LoadDataByFauf($this->data['fauf']);
        $this->soll=$fauf->soll;
    }
    function LoadHistory($wstate=""){
        include ("../apps/dbconnection.php");
        if ($wstate=="") {
            $sql = "SELECT * FROM midworkflow WHERE midworkflow.lokz='0' 
              AND midworkflow.fid='" . $this->fid . "' ORDER BY midworkflow.wdatum DESC LIMIT 0,100";
        }else{
            $sql = "SELECT * FROM midworkflow WHERE midworkflow.lokz='0' 
              AND midworkflow.fid='" . $this->fid . "' AND midworkflow.wstate='" . $wstate . "'
             ORDER BY midworkflow.wdatum DESC LIMIT 0,100";

        }
        $rst=$mysqli->query($sql);
        if ($mysqli->affected_rows>0) {
            $row_rst = $rst->fetch_assoc();
            $this->data['currentstate']=GetListFidStatus("text",$row_rst['wstate']);
            do{
                $this->history[]=$row_rst;
            } while ($row_rst = $rst->fetch_assoc());
            return $mysqli->affected_rows;
        }else{
            $this->history=array();
            return 0;
        }
    }

    function UpdateMaterial($material){
        include ("../apps/dbconnection.php");
        $updateSQL=sprintf("UPDATE messwertmeldungen SET material=%s WHERE fid=%s",
            GetSQLValueString($material,"text"),
            $this->fid);
        $mysqli->query($updateSQL);
        if ($mysqli->error) {
            $_SESSION['x'].=$updateSQL;
            AddSessionMessage("danger","Materialänderung wurde nicht angelegt, da ein Fehler aufgetreten ist.","Fehlermeldung");
        }
    }

    function UpdateFstate($wstate){
        include ("../apps/dbconnection.php");
        $updateSQL=sprintf("UPDATE messwertmeldungen SET fstate=%s WHERE fid=%s",
            GetSQLValueString($wstate,"text"),
            $this->fid);
        $mysqli->query($updateSQL);
        if ($mysqli->error) {
            $_SESSION['x'].=$updateSQL;
            AddSessionMessage("danger","Status $wstate wurde nicht angelegt, da ein Fehler aufgetreten ist.","Fehlermeldung");
        }
    }

    function UpdateEndedatum($datum){
        include ("../apps/dbconnection.php");
        $updateSQL=sprintf("UPDATE messwertmeldungen SET ende=%s WHERE fid=%s",
            GetSQLValueString($datum,"text"),
            $this->fid);
        $mysqli->query($updateSQL);
        if ($mysqli->error) {
            $_SESSION['x'].=$updateSQL;
            AddSessionMessage("danger","Fertigungsdatum wurde nicht geändert, da ein Fehler aufgetreten ist.","Fehlermeldung");
        }
    }

    function UpdateFnotes($notes){
        include ("../apps/dbconnection.php");
        $updateSQL=sprintf("UPDATE messwertmeldungen SET fnotes=%s WHERE fid=%s",
            GetSQLValueString($notes,"text"),
            $this->fid);
        $mysqli->query($updateSQL);
        if ($mysqli->error) {
            $_SESSION['x'].=$updateSQL;
            AddSessionMessage("danger","Bemerkungenänderungen wurde nicht angelegt, da ein Fehler aufgetreten ist.","Fehlermeldung");
        }
    }

    function Validate($POST){
        $this->data['ftype']=utf8_decode($POST['ftype']);
        $this->data['sap']=utf8_decode($POST['sap']);
        $this->data['fauf']=utf8_decode($POST['fauf']);
        $this->data['material']=utf8_decode($POST['material']);
        $this->data['linie']=utf8_decode($POST['linie']);
        $this->data['gewicht']=utf8_decode($POST['gewicht']);
        $this->data['fnotes']=utf8_decode($POST['fnotes']);

        $this->data['fstate']=utf8_decode($POST['fstate']);

        $this->data['verpackungsart']=utf8_decode($POST['verpackungsart']);
        $this->data['inputquali']=utf8_decode($POST['inputquali']);
        $this->data['prozesshinweise']=utf8_decode($POST['prozesshinweise']);
        $this->data['mfi']=utf8_decode($POST['mfi']);
        $this->data['mfibis']=utf8_decode($POST['mfibis']);
        $this->data['emodul']=utf8_decode($POST['emodul']);
        $this->data['emodulbis']=utf8_decode($POST['emodulbis']);
        $this->data['farbe']=utf8_decode($POST['farbe']);
        $this->data['farbebis']=utf8_decode($POST['farbebis']);
        $this->data['schlag']=utf8_decode($POST['schlag']);
        $this->data['schlagbis']=utf8_decode($POST['schlagbis']);

        $this->data['ifeuchte']=utf8_decode($POST['ifeuchte']);
        $this->data['ifeuchtebis']=utf8_decode($POST['ifeuchtebis']);
        $this->data['afeuchte']=utf8_decode($POST['afeuchte']);
        $this->data['afeuchtebis']=utf8_decode($POST['afeuchtebis']);

        $this->data['mfimethode']=utf8_decode($POST['mfimethode']);
        $this->data['mfimethode2']=utf8_decode($POST['mfimethode2']);
        $this->data['mfi2']=utf8_decode($POST['mfi2']);
        $this->data['mfibis2']=utf8_decode($POST['mfibis2']);

        $this->data['mtype']=utf8_decode($POST['mtype']);
        $this->data['mdevice']=utf8_decode($POST['mdevice']);

        if ($POST['Feinpartikel']==1 ) {$this->data['Feinpartikel']=1; } else{ $this->data['Feinpartikel']=0;}
        if ($POST['Oberflachendefekte']==1 ) {$this->data['Oberflachendefekte']=1; } else{ $this->data['Oberflachendefekte']=0;}
        if ($POST['Materialunvertraglichkeit']==1 ) {$this->data['Materialunvertraglichkeit']=1; } else{ $this->data['Materialunvertraglichkeit']=0;}
        if ($POST['VerbranntesGranulat']==1 ) {$this->data['VerbranntesGranulat']=1; } else{ $this->data['VerbranntesGranulat']=0;}
        if ($POST['FarbigeSilikonpartikel']==1 ) {$this->data['FarbigeSilikonpartikel']=1; } else{ $this->data['FarbigeSilikonpartikel']=0;}
        if ($POST['Materialubergange']==1 ) {$this->data['Materialubergange']=1; } else{ $this->data['Materialubergange']=0;}
        if ($POST['Farbausfall']==1 ) {$this->data['Farbausfall']=1; } else{ $this->data['Farbausfall']=0;}


        $this->data['farbel']=str_replace(",",".",utf8_decode($POST['farbel']) );
        $this->data['farbea']=str_replace(",",".",utf8_decode($POST['farbea']) );
        $this->data['farbeb']=str_replace(",",".",utf8_decode($POST['farbeb']) );
        $this->data['strang']=utf8_decode($POST['strang']);

        $this->data['farbel1']=str_replace(",",".",utf8_decode($POST['farbel1']) );
        $this->data['farbea1']=str_replace(",",".",utf8_decode($POST['farbea1']) );
        $this->data['farbeb1']=str_replace(",",".",utf8_decode($POST['farbeb1']) );

        $this->data['m1']=str_replace(",",".",utf8_decode($POST['m1']) );
        $this->data['m2']=str_replace(",",".",utf8_decode($POST['m2']) );
        $this->data['m3']=str_replace(",",".",utf8_decode($POST['m3']) );
        $this->data['m4']=str_replace(",",".",utf8_decode($POST['m4']) );
        $this->data['m5']=str_replace(",",".",utf8_decode($POST['m5']) );
        $this->data['m6']=str_replace(",",".",utf8_decode($POST['m6']) );
        $this->data['m7']=str_replace(",",".",utf8_decode($POST['m7']) );
        $this->data['m8']=str_replace(",",".",utf8_decode($POST['m8']) );
        $this->data['m9']=str_replace(",",".",utf8_decode($POST['m9']) );
        $this->data['m10']=str_replace(",",".",utf8_decode($POST['m10']) );

        $this->data['zugdehnung']=      str_replace(",",".",utf8_decode($POST['zugdehnung']) );
        $this->data['zugfestigkeit']=   str_replace(",",".",utf8_decode($POST['zugfestigkeit']) );
        $this->data['dichte']=          str_replace(",",".",utf8_decode($POST['dichte'])  );


        if ($this->data['fauf']==""){
            $this->data['fauf']="laufend";
        }

        if ($this->data['mtype']==1) {
            $this->data['mfi'] = $this->CalculateMFI();
        }
        if ($this->data['mtype']==2) {
            $this->data['mfi'] = $this->CalculateRestfeuchte();
        }
        if ($this->data['mtype']==3) {
            $this->data['mfi'] = $this->CalculateSchlamm();
        }

        if ($this->data['mtype']==4) {
            $this->data['mfi'] = $this->CalculateTrennung();
        }

        if ($this->data['mtype']==5) {
            $this->data['mfi'] = $this->CalculateSchuettdichte();
        }
        if ($this->data['mtype']==6) {
            $this->data['mfi'] = $this->CalculateCSB();
        }

    }
    function SaveData($POST){
        if (isset($POST['fid']) && $POST['fid']>0){
            $this->fid=$POST['fid'];
            include ("../apps/dbconnection.php");
            $this->Validate($POST);

            $updateSQL = sprintf("
                UPDATE messwertmeldungen SET ftype=%s, sap=%s,fauf=%s,material=%s,linie=%s,
                gewicht=%s, fnotes=%s, fstate=%s, verpackungsart=%s, inputquali=%s, prozesshinweise=%s, 
                mfi=%s, mfibis=%s, emodul=%s,emodulbis=%s,farbe=%s, farbebis=%s, schlag=%s, schlagbis=%s,
                ifeuchte=%s, ifeuchtebis=%s, afeuchte=%s, afeuchtebis=%s, mfi2=%s, mfibis2=%s, mfimethode=%s,mfimethode2=%s,
                
                farbel=%s,farbea=%s,farbeb=%s,strang=%s, 
                farbel1=%s,farbea1=%s,farbeb1=%s,
                m1=%s,m2=%s,m3=%s,m4=%s,m5=%s,m6=%s,m7=%s,m8=%s,m9=%s,m10=%s,
                
                zugdehnung=%s,zugfestigkeit=%s,dichte=%s,
                 
                 Feinpartikel=%s,Oberflachendefekte=%s,Materialunvertraglichkeit=%s,VerbranntesGranulat=%s,
                 FarbigeSilikonpartikel=%s,Materialubergange=%s,Farbausfall=%s,
                 
                 mdevice=%s, mtype=%s 
  
 
                WHERE fid=%s",
                GetSQLValueString($this->data['ftype'],"text"),
                GetSQLValueString($this->data['sap'],"text"),
                GetSQLValueString($this->data['fauf'],"text"),
                GetSQLValueString($this->data['material'],"text"),
                GetSQLValueString($this->data['linie'],"text"),
                GetSQLValueString($this->data['gewicht'],"text"),
                GetSQLValueString($this->data['fnotes'],"text"),

                GetSQLValueString($this->data['fstate'],"int"),

                GetSQLValueString($this->data['verpackungsart'],"text"),
                GetSQLValueString($this->data['inputquali'],"text"),
                GetSQLValueString($this->data['prozesshinweise'],"text"),
                GetSQLValueString($this->data['mfi'],"text"),
                GetSQLValueString($this->data['mfibis'],"text"),
                GetSQLValueString($this->data['emodul'],"text"),
                GetSQLValueString($this->data['emodulbis'],"text"),
                GetSQLValueString($this->data['farbe'],"text"),
                GetSQLValueString($this->data['farbebis'],"text"),
                GetSQLValueString($this->data['schlag'],"text"),
                GetSQLValueString($this->data['schlagbis'],"text"),

                GetSQLValueString($this->data['ifeuchte'],"text"),
                GetSQLValueString($this->data['ifeuchtebis'],"text"),
                GetSQLValueString($this->data['afeuchte'],"text"),
                GetSQLValueString($this->data['afeuchtebis'],"text"),

                GetSQLValueString($this->data['mfi2'],"text"),
                GetSQLValueString($this->data['mfibis2'],"text"),
                GetSQLValueString($this->data['mfimethode'],"text"),
                GetSQLValueString($this->data['mfimethode2'],"text"),

                GetSQLValueString($this->data['farbel'],"text"),
                GetSQLValueString($this->data['farbea'],"text"),
                GetSQLValueString($this->data['farbeb'],"text"),
                GetSQLValueString($this->data['strang'],"text"),

                GetSQLValueString($this->data['farbel1'],"text"),
                GetSQLValueString($this->data['farbea2'],"text"),
                GetSQLValueString($this->data['farbeb3'],"text"),

                GetSQLValueString($this->data['m1'],"text"),
                GetSQLValueString($this->data['m2'],"text"),
                GetSQLValueString($this->data['m3'],"text"),
                GetSQLValueString($this->data['m4'],"text"),
                GetSQLValueString($this->data['m5'],"text"),
                GetSQLValueString($this->data['m6'],"text"),
                GetSQLValueString($this->data['m7'],"text"),
                GetSQLValueString($this->data['m8'],"text"),
                GetSQLValueString($this->data['m9'],"text"),
                GetSQLValueString($this->data['m10'],"text"),

                GetSQLValueString($this->data['zugdehnung'],"text"),
                GetSQLValueString($this->data['zugfestigkeit'],"text"),
                GetSQLValueString($this->data['dichte'],"text"),

                GetSQLValueString($this->data['Feinpartikel'],"int"),
                GetSQLValueString($this->data['Oberflachendefekte'],"int"),
                GetSQLValueString($this->data['Materialunvertraglichkeit'],"int"),
                GetSQLValueString($this->data['VerbranntesGranulat'],"int"),
                GetSQLValueString($this->data['FarbigeSilikonpartikel'],"int"),
                GetSQLValueString($this->data['Materialubergange'],"int"),
                GetSQLValueString($this->data['Farbausfall'],"int"),

                GetSQLValueString($this->data['mdevice'],"int"),
                GetSQLValueString($this->data['mtype'],"int"),
                $this->fid);
            $rst = $mysqli->query($updateSQL);
            if ($mysqli->error) {
                $_SESSION['x'].=$updateSQL;
                AddSessionMessage("danger","Messwertänderung wurde nicht angelegt, da ein Fehler aufgetreten ist.","Fehlermeldung");
            }else{
                AddSessionMessage("success", "Messwerte wurde gespeichert.", "Speichern");
                $this->ChangeLog("Änderung am ".date('Y-m-d H:i',time()),$this->data['fstate']);
                $this->AddNewDocument("Dokumentablage");

            }
        }
    }
    function InsertData($POST){
        if (strlen($POST['fauf'])>=1){
            include ("../apps/dbconnection.php");
            $this->Validate($POST);
            $POST['gebindeid']=$this->GetNextGebindeId();
            $updateSQL = sprintf("
                INSERT INTO  messwertmeldungen (gebindeid, sap,`fauf`, material,linie, gewicht,ende,fstate,ftype,
                 verpackungsart,inputquali, prozesshinweise, mfi, mfibis, emodul, emodulbis, farbe, farbebis,schlag, schlagbis,
                 ifeuchte, ifeuchtebis, afeuchte, afeuchtebis, mfi2, mfibis2, mfimethode, mfimethode2,
                 m1,m2,m3,m4,m5,m6,m7,m8,m9,m10,
                 farbel,farbea,farbeb,strang, 
                farbel1,farbea1,farbeb1,
                 mtype,
                 fnotes, fdatum, fuser, werk) 
                VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,
                %s,%s,%s,%s,%s,%s,%s,%s,
                %s, %s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                GetSQLValueString($POST['gebindeid'],"text"),
                GetSQLValueString($POST['sap'],"text"),
                GetSQLValueString($POST['fauf'],"text"),
                GetSQLValueString($POST['material'],"text"),
                GetSQLValueString($POST['linie'],"text"),
                GetSQLValueString($POST['gewicht'],"text"),
                GetSQLValueString($POST['ende'],"text"),
                GetSQLValueString(0,"int"),
                GetSQLValueString($this->data['ftype'],"text"),

                GetSQLValueString($this->data['verpackungsart'],"text"),
                GetSQLValueString($this->data['inputquali'],"text"),
                GetSQLValueString($this->data['prozesshinweise'],"text"),
                GetSQLValueString($this->data['mfi'],"text"),
                GetSQLValueString($this->data['mfibis'],"text"),
                GetSQLValueString($this->data['emodul'],"text"),
                GetSQLValueString($this->data['emodulbis'],"text"),
                GetSQLValueString($this->data['farbe'],"text"),
                GetSQLValueString($this->data['farbebis'],"text"),
                GetSQLValueString($this->data['schlag'],"text"),
                GetSQLValueString($this->data['schlagbis'],"text"),

                GetSQLValueString($this->data['ifeuchte'],"text"),
                GetSQLValueString($this->data['ifeuchtebis'],"text"),
                GetSQLValueString($this->data['afeuchte'],"text"),
                GetSQLValueString($this->data['afeuchtebis'],"text"),

                GetSQLValueString($this->data['mfi2'],"text"),
                GetSQLValueString($this->data['mfibis2'],"text"),
                GetSQLValueString($this->data['mfimethode'],"text"),
                GetSQLValueString($this->data['mfimethode2'],"text"),

                GetSQLValueString($this->data['m1'],"text"),
                GetSQLValueString($this->data['m2'],"text"),
                GetSQLValueString($this->data['m3'],"text"),
                GetSQLValueString($this->data['m4'],"text"),
                GetSQLValueString($this->data['m5'],"text"),
                GetSQLValueString($this->data['m6'],"text"),
                GetSQLValueString($this->data['m7'],"text"),
                GetSQLValueString($this->data['m8'],"text"),
                GetSQLValueString($this->data['m9'],"text"),
                GetSQLValueString($this->data['m10'],"text"),

                GetSQLValueString($this->data['farbel'],"text"),
                GetSQLValueString($this->data['farbea'],"text"),
                GetSQLValueString($this->data['farbeb'],"text"),
                GetSQLValueString($this->data['strang'],"text"),

                GetSQLValueString($this->data['farbel1'],"text"),
                GetSQLValueString($this->data['farbea2'],"text"),
                GetSQLValueString($this->data['farbeb3'],"text"),
                GetSQLValueString($POST['mtype'],"text"),
                GetSQLValueString(utf8_decode($POST['fnotes']),"text"),
                "now()",
                GetSQLValueString($_SESSION['userid'], "int"),
                GetSQLValueString($_SESSION['classifiedDirectoryID'], "int")
            );
            $rst = $mysqli->query($updateSQL);
            if ($mysqli->error) {
                $_SESSION['x'].=$updateSQL;
                AddSessionMessage("warning","Messwerte wurden nicht angelegt, da ein Fehler aufgetreten ist.","Fehlermeldung");
            }else{
                AddSessionMessage("success", "Messwertrückmeldung <code> " . $mysqli->insert_id . "</code> wurde gespeichert.", "Neu anlegen");
                $this->ChangeLog("Neuanlage",0,$mysqli->insert_id);
                $this->faufid=$mysqli->insert_id;
                $this->AddNewDocument("Dokumentablage");
                $_SESSION['mid']=$mysqli->insert_id;
            }
        }else{
            AddSessionMessage("error","Messwerte wurden nicht angelegt, da kein Fertigungsauftrag zugeordnet.","Fehlermeldung");
        }
    }
    function SendEmail($mode=""){
        $text = "Der Status des Messwertmeldung wurde geändert.";
        if ($mode=="callback"){
            return ("hiermit erhalten Sie eine Information für " . $this->data['fauf']. ".".chr(10).
                " " . date("Y-m-d - H:i ", time()) .
                "\n " . $text );
        }else {
            if (strlen(($this->data['wemail'])) > 4) {

                mail(($this->data['wemail']),
                    "ALBA Fertigungsplanung " . $this->data['fauf']. " Gebinde-ID: ".$this->data['gebnindeid'],
                    " " . date("Y-m-d - H:i ", time()) .
                    "\n" . "\n " . $text .

                    "\nIPv4: " . $_POST["hipv4"] . "-" . $_SERVER["REMOTE_ADDR"] . " - " . $_SERVER['HTTP_X_FORWARDED_FOR']);

                AddSessionMessage("success", "E-Mail mit Fertigungsstatus an <code> " . $this->data['wemail'] . " </code> wurde gesendet", "Info");
                $this->ChangeLog("E-Mailversand an " . $this->data['wemail'], 1);
            } else {
                AddSessionMessage("error", "E-Mail konnte nicht gesendet werden, da keine E-Mail-Adresse für <code> WID" . $this->wid . " " . $this->data['wemail'] . " </code> vorhanden ist.", "Abbruch");
            }
        }
    }
    function ChangeLog($notes,$state=1, $fid="",$datum="",$user=""){
        if ($fid==""){ $fid=$this->fid;}

        if (strlen($notes)>=1){

            include ("../apps/dbconnection.php");

            if ($datum==""){$datum="now()";} else{ $datum=GetSQLValueString($datum,"text");}
            if ($user==""){$user=$_SESSION['userid'];}
            $us=new ifisUser($user);

            $updateSQL = sprintf("
                INSERT INTO  midworkflow (wnotes, wstate, wdatum, wuser, username, werk, fid) VALUES (%s,%s,%s,%s,%s,%s,%s)",
                GetSQLValueString(utf8_decode($notes), "text"),
                GetSQLValueString($state, "int"),
                $datum,
                GetSQLValueString($user, "int"),
                GetSQLValueString(utf8_decode($us->data['name']), "text"),
                GetSQLValueString($_SESSION['classifiedDirectoryID'], "int"),
                GetSQLValueString($fid, "int")
            );
            $rst = $mysqli->query($updateSQL);
            if ($mysqli->error) {
                $_SESSION['x'].=$updateSQL;
                AddSessionMessage("warning","Status wurde nicht angelegt, da ein Fehler aufgetreten ist.","Fehlermeldung");
            }else{
                $this->UpdateFstate($state);
                // AddSessionMessage("success", "Status <code> " . $mysqli->insert_id . "</code> wurde gespeichert.", "Neu anlegen");
            }
        }

    }
    function GetHistoryAsTableBody(){
        $h="<tbody>";
        if (count($this->history)>0){
            foreach($this->history as $item){
                $h.="<tr><td>".substr($item['wdatum'],0,16)."</td>";
                $h.="<td>".$item['wnotes']."</td>";
                $h.="<td>".GetListFidStatus("text",$item['wstate'])."</td>";
                $h.="<td>".$item['username']."</td></tr>";
            }
            $h.="</tbody>";

        }else{
            $h.="<tr><td colspan='4'>keine Verlaufinformationen vorhanden</td></tr>";
            $h.="</tbody>";
        }
        return $h;
    }
    function GetFidsAsTableBody(){
        $h="<tbody>";
        if (count($this->fids)>0){
            foreach($this->fids as $item){
                $h.="<tr><td>".substr($item['wdatum'],0,16)."</td>";
                $h.="<td>".$item['gebindeid']."</td>";
                $h.="<td>".$item['material']."</td>";
                $h.="<td>".$item['fnotes']."</td>";
                $h.="<td>".$item['gewicht']."</td>";
                $h.="<td>".$item['sap']."</td></tr>";
            }
            $h.="</tbody>";

        }else{
            $h.="<tr><td colspan='6'>keine Rückmeldungen vorhanden</td></tr>";
            $h.="</tbody>";
        }
        return $h;
    }
    function GetCurrentState(){
        if (is_null($this->data['currentstate'])){
            return "Neuanlage";
        }else{
            return $this->data['currentstate'];
        }
    }

    function GetMFITime(){
        $t=GetListMFIMethode("time",$this->data['mfimethode']);
        if ($t<0){$t=3;}
        return $t;
    }

    function GetMFIOffset(){
        $to=GetListMFIMethode("offsetplus",$this->data['mfimethode']);
        if ($to==0){$to=0.1;}
        $tu=GetListMFIMethode("offsetminus",$this->data['mfimethode']);
        if ($tu==0){$tu=-0.1;}

        return "". $tu. " bis +".$to;
    }

    function CheckMFIMeasure(){
        $i=0;
        if ($this->data['m1']>0) { $i++;}
        if ($this->data['m2']>0) { $i++;}
        if ($this->data['m3']>0) { $i++;}
        if ($this->data['m4']>0) { $i++;}
        if ($this->data['m5']>0) { $i++;}
        if ($this->data['m6']>0) { $i++;}
        return $i;
    }

    function CalulateDurchschnittsprobe(){
        if ($this->data['m7']>=1) {
            $m = $this->data['m1']/ $this->data['m7'];
        } else{
            $m= ($this->data['m1'] + $this->data['m2'] + $this->data['m3'] + $this->data['m4'] + $this->data['m5'] + $this->data['m6']) / $this->CheckMFIMeasure() ;
        }
        return round($m,2). " g";
    }

    function CalculateMFI(){
        $mfr=0;
        if ($this->CheckMFIMeasure()>0) {

            if ($this->CheckMFIMeasure()==1) {
                if ($this->data['m7']>=1) {
                    $mfr = $this->data['m1'] / $this->data['m7'] * 600/ $this->GetMFITime();
                }
            }else {
                $mfr = 600 * (($this->data['m1'] + $this->data['m2'] + $this->data['m3'] + $this->data['m4'] + $this->data['m5'] + $this->data['m6']) / $this->CheckMFIMeasure()) / $this->GetMFITime();
            }
            return  round($mfr,2);
        }
    }
    function GetMFI(){
        if ( $this->CheckMFIMeasure()==0){
            return "Messwerte fehlen";
        }else{

            return "MFR= ". $this->CalculateMFI() ." ";
        }
    }
    function CalculateRestfeuchte(){
        $s=GetListMeasureLocations("text",$this->data['m1'])." ".$this->data['m2'];
        if ( strlen($s)==1){
            return "Messwerte fehlen";
        }else{

            return "Restfeuchte ". $s." %";
        }
    }

    function CalculateTrennung(){
        $s="B1 ".GetListBeckenType("text",$this->data['m1']);
        $s=$s." B2 ".GetListBeckenType("text",$this->data['m2']);
        $s=$s." B3 ".GetListBeckenType("text",$this->data['m3']);
        $s=$s." B4 ".GetListBeckenType("text",$this->data['m4']);
        $s=$s." B5 ".GetListBeckenType("text",$this->data['m5']);
        $s=$s." L3 ".GetListBeckenType("text",$this->data['m6']);

        if ( strlen($s)==24){
            return "Messwerte fehlen";
        }else{

            return "". utf8_decode($s)."";
        }
    }

    function CalculateSchlamm(){
        $s=0;
        if ($this->data['m1']>0) {
            $s = "Absiebung:".$this->data['m1']."% ";
        }
        if ($this->data['m2']>0) {
            $s =$s. "Klärwerk:".$this->data['m2']."% ";
        }
        return utf8_decode($s);
    }

    function CalculateCSB(){
        $csb=0;
        if ($this->data['m1']>0) {
            $csb = (1 - ($this->data['m2'] / $this->data['m1'])) * 100;
        }
        return "CSB-Abbaurate= ".round($csb,0)." %";
    }

    function CalculateSchuettdichte(){
        return GetListMeasureLocations('text',$this->data['m1'])."= ".round($this->data['m2'],0)." g/l";
    }


    function AddNewDocument($notes=" "){
        include("../apps/dbconnection.php");
        if (strlen($notes)>=1){
            $us=new ifisUser($_SESSION['userid']);
            include ("../apps/dbconnection.php");

            $state=1;
            $folder="../tmpupload/";
            $files = glob($folder."*.*");
            foreach($files as $value){

                $file = $value;
                // AddSessionMessage("success", "Dokument  <code> " . $file. "</code> wurde hochgeladen und gespeichert.", "Dokumentupload");
                $filename=str_replace($folder,"../fid/".time(),$file);

                if(file_exists($folder.$file)){
                    rename($file,$filename);
                    $updateSQL = sprintf("
                        INSERT INTO  dokumente (dnotes, dstate, ddatum, duser, username, werk, linkedid, dobject, documentlink) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s)",
                        GetSQLValueString(utf8_decode($notes), "text"),
                        GetSQLValueString($state, "int"),
                        "now()",
                        GetSQLValueString($_SESSION['userid'], "int"),
                        GetSQLValueString(utf8_decode($us->data['name']), "text"),
                        GetSQLValueString($_SESSION['classifiedDirectoryID'], "int"),
                        GetSQLValueString($this->fid, "int"),
                        GetSQLValueString("mid", "text"),
                        GetSQLValueString($filename, "text")
                    );
                    $rst = $mysqli->query($updateSQL);

                    if ($mysqli->error) {
                        $_SESSION['x'].=$updateSQL;
                        AddSessionMessage("warning","Dokument wurde nicht angelegt, da ein Fehler aufgetreten ist.","Fehlermeldung");
                    }else{
                        AddSessionMessage("success", "Dokument  <code> " . $filename. "</code> wurde hochgeladen und gespeichert.", "Dokumentupload");
                        $this->ChangeLog("Dokument $filename wurde hochgeladen",1);
                    }
                }
            }
        }

    }
    function LoadDocumentData(){
        if ($this->fid > 0){
            include("../apps/dbconnection.php");
            $updateSQL="SELECT * FROM dokumente
                        WHERE dokumente.linkedid='".$this->fid."' AND dokumente.dobject='mid'
                        ORDER BY dokumente.ddatum ASC";

            $mysqli = new mysqli($hostname_datenbank, $username_datenbank, $password_datenbank, $database_datenbank);
            $rst=$mysqli->query($updateSQL);
            if ($mysqli->error) {
                AddSessionMessage("warning","Dokumente wurden nicht geladen, da ein Fehler aufgetreten ist.","Fehlermeldung");
            }else{
                if ($mysqli->affected_rows>0) {
                    $this->documents=array();
                    $row_rst = $rst->fetch_assoc();
                    do{
                        $this->documents[]= $row_rst;
                    }while ($row_rst=$rst->fetch_assoc());
                }
            }
            $mysqli->close();
        }
    }
    function GetDocumentFiles(){

        if (count($this->documents)>0){
            foreach ($this->documents as $item){
                $r[]=array(
                    'name'=> $item['did'].substr($item['documentlink'],0,-3),
                    'size'=> 145,
                    'type'=> "image/jpg",
                    'file'=> "../fid/".$item['documentlink']);
            }
        }
        return "files:".json_encode($r);
    }
    function GetDocumentList(){
        $h="<tbody>";
        if (count($this->documents)>0){
            foreach($this->documents as $item){
                $h.="<tr><td>".substr($item['ddatum'],0,16)."</td>";
                $h.="<td><img src=' ".$item['documentlink']."' width='145'></td>";
                $h.="<td>".$item['dnotes']."</td>";
                $h.="<td>".GetStatus($item['dstate'])."</td>";
                $h.="<td>".$item['username']."</td></tr>";
            }
            $h.="</tbody>";

        }else{
            $h.="<tr><td colspan='5'>keine Dokumentinformationen vorhanden</td></tr>";
            $h.="</tbody>";
        }
        return $h;
    }
}

class ifisAllMessageid{
    public $werk=0;
    public $mtype=0;
    public $data=array();
    public $txt="";

    function __construct($werk=1501,$state="")
    {
        if ($werk>0){
            $this->werk=$werk;
        }
        $this->LoadData($state);
    }
    function LoadData($state=""){
        include ("../apps/dbconnection.php");
        $limit="";
        $statesql="";
        $this->data=array();
        if (strlen($_SESSION['datumvon'])>0 && $_SESSION['datumvon']<>"0000-00-00"){ $limit.=" AND message_tbl.fdatum>='".$_SESSION['datumvon']." 00:00:00'";}
        if (strlen($_SESSION['datumbis'])>0 && $_SESSION['datumbis']<>"0000-00-00"){ $limit.=" AND message_tbl.fdatum<='".$_SESSION['datumbis']." 23:59:99'";}
        if (($_SESSION['Smessagetype'])>0 ){ $limit.=" AND message_tbl.mtype='".$_SESSION['Smessagetype']."'";}
        if ($state<>""){ $statesql=" AND message_tbl.fstate='$state' ";}


        $sql="SELECT * FROM message_tbl WHERE message_tbl.werk='$this->werk' $statesql AND message_tbl.flokz=0 $limit 
          ORDER BY message_tbl.fdatum DESC LIMIT 100";

        $rst=$mysqli->query($sql);
        if (mysqli_affected_rows($mysqli)>0){
            $row_rst = $rst->fetch_assoc();
            do{
                $this->data[]=$row_rst;
            } while ($row_rst = $rst->fetch_assoc());

        }else{
            $this->data=array();
        }
    }
    function SetDatum($mtype=""){
        include ("../apps/dbconnection.php");
        $limit="";
        $statesql="";
        $this->data=array();

        $timestamp1=date("Y-m-d H:i",time() - (60*60*24));
        $timestamp2=date("Y-m-d H:i",time() + (60*60*24));

        if (strlen($timestamp1)>0){ $limit.=" AND message_tbl.fdatum1>='".$timestamp1.":00'";}
        if (strlen($timestamp2)>0){ $limit.=" AND message_tbl.fdatum1<='".$timestamp2.":99'";}
        if ($mtype<>""){ $statesql=" AND message_tbl.mtype='$mtype' ";}


        $sql="SELECT * FROM message_tbl WHERE message_tbl.werk='$this->werk' $statesql AND message_tbl.flokz=0 $limit 
          ORDER BY message_tbl.fdatum DESC LIMIT 100";
        $this->txt=$sql;
        $rst=$mysqli->query($sql);
        if (mysqli_affected_rows($mysqli)>0){
            $row_rst = $rst->fetch_assoc();
            do{
                $this->data[]=$row_rst;
            } while ($row_rst = $rst->fetch_assoc());

        }else{
            $this->data=array();
        }
    }
    function GetJsonList($state){
        $this->LoadData($state);
        if (count($this->data)>0){
            foreach ($this->data as $item){
                $us=new ifisUser($item['fuser']);

                $r['aaData'][]=array(
                    $item['messageid'],
                    $item['fnotes1'],
                    $item['fnotes2'],
                    $item['fnotes3'],
                    $item['fnotes4'],

                    $item['fdatum1'],
                    $item['fdatum2'],
                    $item['fdatum3'],
                    $item['fnotes'],
                    $item['fdatum'],
                    $us->data['name'],
                    GetListMessageType("text",($item['mtype']))
                );
            }
        } else{
            $r['aaData']=array();
        }

        return json_encode($r);
    }
    function GetJsonQSList($state){
        $this->LoadData($state);
        if (count($this->data)>0){
            foreach ($this->data as $item){
                $r['aaData'][]=array(
                    $item['messageid'],
                    $item['fnotes'],
                    $item['fnotes1'],
                    $item['fnotes2'],
                    $item['fnotes3'],
                    $item['fnotes4'],

                    $item['fdatum1'],
                    $item['fdatum2'],
                    $item['fdatum3'],
                    $item['fnotes'],
                    $item['fdatum'],
                    $us->data['name'],
                    GetListMessageType("text",($item['mtype']))
                );
            }
        } else{
            $r['aaData']=array();
        }

        return json_encode($r);
    }

    function GetList($mtype=""){
        $this->SetDatum($mtype);
        $h="<tbody>";
        if (count($this->data)>0){
            foreach($this->data as $item){
                $h.="<tr><td>".$item['messageid']."</td>";
                $h.="<td>".substr($item['fdatum1'],0,16)."</td>";
                $h.="<td>".$item['fnotes2']." </td>";
                $h.="<td>".$item['fnotes3']."</td>";
                if ($mtype<=4) {
                    $h .= "<td>" . $item['fnotes4'] . "</td>";
                    $h .= "<td>" . ShowStatusIcon($item['m10'], $item['m11'], $item['m12'], $item['m13'], $item['m14'], $item['m15']) . "</td>";
                }
                $h.="</tr>";
            }
            $h.="</tbody>";

        }else{
            $h.="<tr><td colspan='7'>keine Meldungen vorhanden.</td></tr>";
            $h.="</tbody>";
        }
        return $h;
    }

    function LoadLastTimeData(){
        include ("../apps/dbconnection.php");
        $this->data=array();
        $sql="SELECT * FROM message_tbl WHERE message_tbl.werk='$this->werk'  AND message_tbl.flokz=0 AND message_tbl.mtype='$this->mtype' 
          ORDER BY message_tbl.fdatum1 DESC LIMIT 1";

        $rst=$mysqli->query($sql);
        if (mysqli_affected_rows($mysqli)>0){
            $row_rst = $rst->fetch_assoc();
            do{
                $this->data[]=$row_rst;
            } while ($row_rst = $rst->fetch_assoc());

        }else{
            $this->data=array();
        }
    }

    function GetLastTimeOfMessage($mtype){
        $this->mtype=$mtype;
        $this->LoadLastTimeData();

        if (count($this->data)>0) {
            foreach ($this->data as $item) {
                $l = ($item['fdatum1']);
            }
        }else{
               $l=('2018-01-01 0:00');
        }


        $txt="";
        if ($mtype==1){$txt="unfallfrei";}
        if ($mtype==2){$txt="schadensfrei";}


        $jetzt = new DateTime($l);
        $letzte = new DateTime(date("Y-m-d H:i",time()) );
        $interval = $letzte->diff($jetzt);


        return array(
            'txt'=>$txt,
            'days'=>$interval->format('%a '),
            'hours'=>$interval->format('%h '),
            'minuten'=>$interval->format('%i '),
            'prozent'=>round($interval->format('%a ')/365*4*100),
            'mtype'=>$mtype,

        );
    }

}
class ifisMessageid{
    public $fid=0;
    public $data=array();
    public $soll=array();
    public $history=array();
    public $documents=array();
    function __construct($id=0)
    {
        if ($id>0){
            $this->fid=$id;
        }
        $this->LoadData();
    }
    function LoadData(){
        include ("../apps/dbconnection.php");
        $sql="SELECT * FROM message_tbl WHERE message_tbl.messageid='$this->fid'  ";
        $rst=$mysqli->query($sql);
        if (mysqli_affected_rows($mysqli)>0){
            $this->data=$rst->fetch_assoc();
            $this->LoadHistory();
            $this->LoadDocumentData();

        }else{
            $this->data=array();
        }
    }
    function GetNextGebindeId(){
        include ("../apps/dbconnection.php");
        $sql="SELECT message_tbl.fnotes1 FROM message_tbl  ORDER BY message_tbl.fnotes1 DESC LIMIT 1";
        $rst=$mysqli->query($sql);
        if (mysqli_affected_rows($mysqli)>0){
            $row=$rst->fetch_assoc();
            return intval($row['fnotes1']+1);
        }else{
            return 1;
        }
    }

    function LoadHistory($wstate=""){
        include ("../apps/dbconnection.php");
        if ($wstate=="") {
            $sql = "SELECT * FROM messageworkflow WHERE messageworkflow.lokz='0' 
              AND messageworkflow.fid='" . $this->fid . "' ORDER BY messageworkflow.wdatum DESC LIMIT 0,100";
        }else{
            $sql = "SELECT * FROM messageworkflow WHERE messageworkflow.lokz='0' 
              AND messageworkflow.fid='" . $this->fid . "' AND messageworkflow.wstate='" . $wstate . "'
             ORDER BY messageworkflow.wdatum DESC LIMIT 0,100";

        }
        $rst=$mysqli->query($sql);
        if ($mysqli->affected_rows>0) {
            $row_rst = $rst->fetch_assoc();
            $this->data['currentstate']=GetStatus("text",$row_rst['wstate']);
            do{
                $this->history[]=$row_rst;
            } while ($row_rst = $rst->fetch_assoc());
            return $mysqli->affected_rows;
        }else{
            $this->history=array();
            return 0;
        }
    }



    function UpdateFstate($wstate){
        include ("../apps/dbconnection.php");
        $updateSQL=sprintf("UPDATE message_tbl SET fstate=%s WHERE fid=%s",
            GetSQLValueString($wstate,"text"),
            $this->fid);
        $mysqli->query($updateSQL);
        if ($mysqli->error) {
            $_SESSION['x'].=$updateSQL;
            AddSessionMessage("danger","Status $wstate wurde nicht angelegt, da ein Fehler aufgetreten ist.","Fehlermeldung");
        }
    }



    function UpdateFnotes($notes){
        include ("../apps/dbconnection.php");
        $updateSQL=sprintf("UPDATE message_tbl SET fnotes=%s WHERE fid=%s",
            GetSQLValueString($notes,"text"),
            $this->fid);
        $mysqli->query($updateSQL);
        if ($mysqli->error) {
            $_SESSION['x'].=$updateSQL;
            AddSessionMessage("error","Bemerkungenänderungen wurde nicht angelegt, da ein Fehler aufgetreten ist.","Fehlermeldung");
        }
    }

    function Validate($POST){

        /**  $this->data['m1']=str_replace(",",".",utf8_decode($POST['m1']) ); */

        if ($POST['m1']==1 ) {$this->data['m1']=1; } else{ $this->data['m1']=0;}
        if ($POST['m2']==1 ) {$this->data['m2']=1; } else{ $this->data['m2']=0;}
        if ($POST['m3']==1 ) {$this->data['m3']=1; } else{ $this->data['m3']=0;}
        if ($POST['m4']==1 ) {$this->data['m4']=1; } else{ $this->data['m4']=0;}
        if ($POST['m5']==1 ) {$this->data['m5']=1; } else{ $this->data['m5']=0;}
        if ($POST['m6']==1 ) {$this->data['m6']=1; } else{ $this->data['m6']=0;}
        if ($POST['m7']==1 ) {$this->data['m7']=1; } else{ $this->data['m7']=0;}
        if ($POST['m8']==1 ) {$this->data['m8']=1; } else{ $this->data['m8']=0;}
        if ($POST['m9']==1 ) {$this->data['m9']=1; } else{ $this->data['m9']=0;}
        if (isset($POST['m10'])) {$this->data['m10']=1; } else{ $this->data['m10']=0;}

        if (isset($POST['m11'])) {$this->data['m11']=1; } else{ $this->data['m11']=0;}
        if (isset($POST['m12'])) {$this->data['m12']=1; } else{ $this->data['m12']=0;}
        if (isset($POST['m13'])) {$this->data['m13']=1; } else{ $this->data['m13']=0;}
        if (isset($POST['m14'])) {$this->data['m14']=1; } else{ $this->data['m14']=0;}
        if (isset($POST['m15'])) {$this->data['m15']=1; } else{ $this->data['m15']=0;}
        if (isset($POST['m16'])) {$this->data['m16']=1; } else{ $this->data['m16']=0;}
        if (isset($POST['m17'])) {$this->data['m17']=1; } else{ $this->data['m17']=0;}
        if (isset($POST['m18'])) {$this->data['m18']=1; } else{ $this->data['m18']=0;}
        if (isset($POST['m19'])) {$this->data['m19']=1; } else{ $this->data['m19']=0;}
        if (isset($POST['m20'])) {$this->data['m20']=1; } else{ $this->data['m20']=0;}


        if (isset($POST['m21'])) {$this->data['m21']=1; } else{ $this->data['m21']=0;}
        if (isset($POST['m22'])) {$this->data['m22']=1; } else{ $this->data['m22']=0;}
        if (isset($POST['m23'])) {$this->data['m23']=1; } else{ $this->data['m23']=0;}
        if (isset($POST['m24'])) {$this->data['m24']=1; } else{ $this->data['m24']=0;}
        if (isset($POST['m25'])) {$this->data['m25']=1; } else{ $this->data['m25']=0;}
        if (isset($POST['m26'])) {$this->data['m26']=1; } else{ $this->data['m26']=0;}
        if (isset($POST['m27'])) {$this->data['m27']=1; } else{ $this->data['m27']=0;}
        if (isset($POST['m28'])) {$this->data['m28']=1; } else{ $this->data['m28']=0;}
        if (isset($POST['m29'])) {$this->data['m29']=1; } else{ $this->data['m29']=0;}
        if (isset($POST['m20'])) {$this->data['m30']=1; } else{ $this->data['m30']=0;}

        $this->data['fstate']=1;
    }
    function SaveData($POST){
        if (isset($POST['fid']) && $POST['fid']>0){
            $this->fid=$POST['fid'];
            include ("../apps/dbconnection.php");
            $this->Validate($POST);

            $updateSQL = sprintf("
                UPDATE message_tbl SET 
                fnotes1=%s,fnotes2=%s, fnotes3=%s,fnotes4=%s, fdatum1=%s, fnotes=%s,  fstate=%s ,          
                m1=%s,m2=%s,m3=%s,m4=%s,m5=%s,m6=%s,m7=%s,m8=%s,m9=%s,m10=%s,
                m11=%s,m12=%s,m13=%s,m14=%s,m15=%s,m16=%s,m17=%s,m18=%s,m19=%s,m20=%s,
                 m21=%s,m22=%s,m23=%s,m24=%s,m25=%s,m26=%s,m27=%s,m28=%s,m29=%s,m30=%s
                
                WHERE messageid=%s",

                GetSQLValueString(utf8_decode($POST['fnotes1']),"text"),
                GetSQLValueString(utf8_decode($POST['fnotes2']),"text"),
                GetSQLValueString(utf8_decode($POST['fnotes3']),"text"),
                GetSQLValueString(utf8_decode($POST['fnotes4']),"text"),
                GetSQLValueString(utf8_decode($POST['fdatum1']),"date"),
                GetSQLValueString(utf8_decode($POST['fnotes']),"text"),

                GetSQLValueString($this->data['fstate'],"int"),

                GetSQLValueString($this->data['m1'],"text"),
                GetSQLValueString($this->data['m2'],"text"),
                GetSQLValueString($this->data['m3'],"text"),
                GetSQLValueString($this->data['m4'],"text"),
                GetSQLValueString($this->data['m5'],"text"),
                GetSQLValueString($this->data['m6'],"text"),
                GetSQLValueString($this->data['m7'],"text"),
                GetSQLValueString($this->data['m8'],"text"),
                GetSQLValueString($this->data['m9'],"text"),
                GetSQLValueString($this->data['m10'],"text"),

                GetSQLValueString($this->data['m11'],"text"),
                GetSQLValueString($this->data['m12'],"text"),
                GetSQLValueString($this->data['m13'],"text"),
                GetSQLValueString($this->data['m14'],"text"),
                GetSQLValueString($this->data['m15'],"text"),
                GetSQLValueString($this->data['m16'],"text"),
                GetSQLValueString($this->data['m17'],"text"),
                GetSQLValueString($this->data['m18'],"text"),
                GetSQLValueString($this->data['m19'],"text"),
                GetSQLValueString($this->data['m20'],"text"),

                GetSQLValueString($this->data['m21'],"text"),
                GetSQLValueString($this->data['m22'],"text"),
                GetSQLValueString($this->data['m23'],"text"),
                GetSQLValueString($this->data['m24'],"text"),
                GetSQLValueString($this->data['m25'],"text"),
                GetSQLValueString($this->data['m26'],"text"),
                GetSQLValueString($this->data['m27'],"text"),
                GetSQLValueString($this->data['m28'],"text"),
                GetSQLValueString($this->data['m29'],"text"),
                GetSQLValueString($this->data['m30'],"text"),

                $this->fid);
            $rst = $mysqli->query($updateSQL);
            if ($mysqli->error) {
                $_SESSION['x'].=$updateSQL;
                AddSessionMessage("warning","Meldungsänderung wurde nicht angelegt, da ein Fehler aufgetreten ist.","Fehlermeldung");
            }else{
                AddSessionMessage("success", "Meldung wurde gespeichert.", "Speichern");
                $this->ChangeLog("Änderung am ".date('Y-m-d H:i',time()),$this->data['fstate']);
                $this->AddNewDocument("Dokumentablage");

            }

        } else{
            AddSessionMessage("error","Meldungsänderung wurde nicht angelegt, da ein Fehler aufgetreten ist.","Fehlermeldung");
        }

    }
    function InsertData($POST){
        if (strlen($POST['fnotes1'])>=1){
            include ("../apps/dbconnection.php");
            $this->Validate($POST);
            $POST['m1']=$this->GetNextGebindeId();
            $POST['fnotes1']=$this->GetNextGebindeId();
            $updateSQL = sprintf("
                INSERT INTO  message_tbl (
                fnotes1,fnotes2,fnotes3,fnotes4,fdatum1,
                 m1, ftype, mtype,fnotes, fdatum, fuser, werk) 
                VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",

                GetSQLValueString(utf8_decode($POST['fnotes1']),"text"),
                GetSQLValueString(utf8_decode($POST['fnotes2']),"text"),
                GetSQLValueString(utf8_decode($POST['fnotes3']),"text"),
                GetSQLValueString(utf8_decode($POST['fnotes4']),"text"),
                GetSQLValueString(utf8_decode($POST['fdatum1']),"date"),


                GetSQLValueString($this->data['m1'],"text"),
                GetSQLValueString($POST['ftype'],"text"),
                GetSQLValueString($POST['mtype'],"text"),
                GetSQLValueString(utf8_decode($POST['fnotes']),"text"),
                "now()",
                GetSQLValueString($_SESSION['userid'], "int"),
                GetSQLValueString($_SESSION['classifiedDirectoryID'], "int")
            );
            $rst = $mysqli->query($updateSQL);
            if ($mysqli->error) {
                $_SESSION['x'].=$updateSQL;
                AddSessionMessage("warning","Meldungen wurden nicht angelegt, <code>".$updateSQL."</code> da ein Fehler aufgetreten ist.","Fehlermeldung");
            }else{
                AddSessionMessage("success", "Meldung <code> " . $mysqli->insert_id . "</code> wurde gespeichert.", "Neu anlegen");
                $this->ChangeLog("Neuanlage",1,$mysqli->insert_id);
                $this->fid=$mysqli->insert_id;
                $this->AddNewDocument("Dokumentablage");
                $_SESSION['mid']=$mysqli->insert_id;
            }
        }else{
            AddSessionMessage("error","Meldung wurden nicht angelegt, da keine Werte ausgefüllt wurden nicht zugeordnet wurde.","Fehlermeldung");
        }
    }
    function SendEmail($mode=""){
        $text = "Der Status des Messwertmeldung wurde geändert.";
        if ($mode=="callback"){
            return ("hiermit erhalten Sie eine Information für Meldung" . $this->data['m1']. ".".chr(10).
                " " . date("Y-m-d - H:i ", time()) .
                "\n " . $text );
        }else {
            if (strlen(($this->data['wemail'])) > 4) {

                mail(($this->data['wemail']),
                    "ALBA Meldung " . $this->data['mtype']. " Gebinde-ID: ".$this->data['m1'],
                    " " . date("Y-m-d - H:i ", time()) .
                    "\n" . "\n " . $text .

                    "\nIPv4: " . $_POST["hipv4"] . "-" . $_SERVER["REMOTE_ADDR"] . " - " . $_SERVER['HTTP_X_FORWARDED_FOR']);

                AddSessionMessage("success", "E-Mail mit Meldungsstatus an <code> " . $this->data['wemail'] . " </code> wurde gesendet", "Info");
                $this->ChangeLog("E-Mailversand an " . $this->data['wemail'], 1);
            } else {
                AddSessionMessage("error", "E-Mail konnte nicht gesendet werden, da keine E-Mail-Adresse für <code> WID" . $this->wid . " " . $this->data['wemail'] . " </code> vorhanden ist.", "Abbruch");
            }
        }
    }
    function ChangeLog($notes,$state=1, $fid="",$datum="",$user=""){
        if ($fid==""){ $fid=$this->fid;}

        if (strlen($notes)>=1){

            include ("../apps/dbconnection.php");

            if ($datum==""){$datum="now()";} else{ $datum=GetSQLValueString($datum,"text");}
            if ($user==""){$user=$_SESSION['userid'];}
            $us=new ifisUser($user);

            $updateSQL = sprintf("
                INSERT INTO  messageworkflow (wnotes, wstate, wdatum, wuser, username, werk, fid) VALUES (%s,%s,%s,%s,%s,%s,%s)",
                GetSQLValueString(utf8_decode($notes), "text"),
                GetSQLValueString($state, "int"),
                $datum,
                GetSQLValueString($user, "int"),
                GetSQLValueString(utf8_decode($us->data['name']), "text"),
                GetSQLValueString($_SESSION['classifiedDirectoryID'], "int"),
                GetSQLValueString($fid, "int")
            );
            $rst = $mysqli->query($updateSQL);
            if ($mysqli->error) {
                $_SESSION['x'].=$updateSQL;
                AddSessionMessage("warning","Changelog wurde nicht angelegt, da ein Fehler aufgetreten ist.","Fehlermeldung");
            }else{
                $this->UpdateFstate($state);
                // AddSessionMessage("success", "Status <code> " . $mysqli->insert_id . "</code> wurde gespeichert.", "Neu anlegen");
            }
        }

    }
    function GetHistoryAsTableBody(){
        $h="<tbody>";
        if (count($this->history)>0){
            foreach($this->history as $item){
                $h.="<tr><td>".substr($item['wdatum'],0,16)."</td>";
                $h.="<td>".$item['wnotes']."</td>";
                $h.="<td>".GetMessageStatus("text",$item['wstate'])."</td>";
                $h.="<td>".$item['username']."</td></tr>";
            }
            $h.="</tbody>";

        }else{
            $h.="<tr><td colspan='4'>keine Verlaufinformationen vorhanden</td></tr>";
            $h.="</tbody>";
        }
        return $h;
    }
    function GetFidsAsTableBody(){
        $h="<tbody>";
        if (count($this->fids)>0){
            foreach($this->fids as $item){
                $h.="<tr><td>".substr($item['wdatum'],0,16)."</td>";
                $h.="<td>".$item['gebindeid']."</td>";
                $h.="<td>".$item['material']."</td>";
                $h.="<td>".$item['fnotes']."</td>";
                $h.="<td>".$item['gewicht']."</td>";
                $h.="<td>".$item['sap']."</td></tr>";
            }
            $h.="</tbody>";

        }else{
            $h.="<tr><td colspan='6'>keine Ereignisse vorhanden</td></tr>";
            $h.="</tbody>";
        }
        return $h;
    }
    function GetCurrentState(){
        if (is_null($this->data['currentstate'])){
            return "Neuanlage";
        }else{
            return $this->data['currentstate'];
        }
    }

    function ShowStatusIcon(){
        return ShowStatusIcon($this->data['m10'],$this->data['m11'],$this->data['m12'],$this->data['m13'],$this->data['m14'],$this->data['m15']);
    }


    function AddNewDocument($notes=" "){
        include("../apps/dbconnection.php");
        if (strlen($notes)>=1){
            $us=new ifisUser($_SESSION['userid']);
            include ("../apps/dbconnection.php");

            $state=1;
            $folder="../tmpupload/";
            $files = glob($folder."*.*");
            foreach($files as $value){

                $file = $value;
                // AddSessionMessage("success", "Dokument  <code> " . $file. "</code> wurde hochgeladen und gespeichert.", "Dokumentupload");
                $filename=str_replace($folder,"../messageid/".time(),$file);

                if(file_exists($folder.$file)){
                    rename($file,$filename);
                    $updateSQL = sprintf("
                        INSERT INTO  dokumente (dnotes, dstate, ddatum, duser, username, werk, linkedid, dobject, documentlink) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s)",
                        GetSQLValueString(utf8_decode($notes), "text"),
                        GetSQLValueString($state, "int"),
                        "now()",
                        GetSQLValueString($_SESSION['userid'], "int"),
                        GetSQLValueString(utf8_decode($us->data['name']), "text"),
                        GetSQLValueString($_SESSION['classifiedDirectoryID'], "int"),
                        GetSQLValueString($this->fid, "int"),
                        GetSQLValueString("messageid", "text"),
                        GetSQLValueString($filename, "text")
                    );
                    $rst = $mysqli->query($updateSQL);

                    if ($mysqli->error) {
                        $_SESSION['x'].=$updateSQL;
                        AddSessionMessage("warning","Dokument wurde nicht angelegt, da ein Fehler aufgetreten ist.","Fehlermeldung");
                    }else{
                        AddSessionMessage("success", "Dokument  <code> " . $filename. "</code> wurde hochgeladen und gespeichert.", "Dokumentupload");
                        $this->ChangeLog("Dokument $filename wurde hochgeladen",1);
                    }
                }
            }
        }

    }
    function LoadDocumentData(){
        if ($this->fid > 0){
            include("../apps/dbconnection.php");
            $updateSQL="SELECT * FROM dokumente
                        WHERE dokumente.linkedid='".$this->fid."' AND dokumente.dobject='messageid'
                        ORDER BY dokumente.ddatum ASC";

            $mysqli = new mysqli($hostname_datenbank, $username_datenbank, $password_datenbank, $database_datenbank);
            $rst=$mysqli->query($updateSQL);
            if ($mysqli->error) {
                AddSessionMessage("warning","Dokumente wurden nicht geladen, da ein Fehler aufgetreten ist.","Fehlermeldung");
            }else{
                if ($mysqli->affected_rows>0) {
                    $this->documents=array();
                    $row_rst = $rst->fetch_assoc();
                    do{
                        $this->documents[]= $row_rst;
                    }while ($row_rst=$rst->fetch_assoc());
                }
            }
            $mysqli->close();
        }
    }
    function GetDocumentFiles(){

        if (count($this->documents)>0){
            foreach ($this->documents as $item){
                $r[]=array(
                    'name'=> $item['did'].substr($item['documentlink'],0,-3),
                    'size'=> 145,
                    'type'=> "image/jpg",
                    'file'=> "../messageid/".$item['documentlink']);
            }
        }
        return "files:".json_encode($r);
    }
    function GetDocumentList(){
        $h="<tbody>";
        if (count($this->documents)>0){
            foreach($this->documents as $item){
                $h.="<tr><td>".substr($item['ddatum'],0,16)."</td>";
                $h.="<td><img src=' ".$item['documentlink']."' width='145'></td>";
                $h.="<td>".$item['dnotes']."</td>";
                $h.="<td>".GetStatus($item['dstate'])."</td>";
                $h.="<td>".$item['username']."</td></tr>";
            }
            $h.="</tbody>";

        }else{
            $h.="<tr><td colspan='5'>keine Dokumentinformationen vorhanden</td></tr>";
            $h.="</tbody>";
        }
        return $h;
    }
}
class ifisInfo{

    function ShowAllFidStatus(){
        $a=GetListFidStatus("data","");
        $r="";
        foreach ($a as $i=>$item){
            $r.="<br><h4>".$item."</h4><br>";
            $r.=$this->ShowAllFidDataByMaterialByStatus($i);
        }
        return $r;
    }

    function ShowAllFidDataByMaterialByStatus($wstate){
        include("../apps/dbconnection.php");
        $selectSQL="SELECT DISTINCT fertigungsmeldungen.material, COUNT( fertigungsmeldungen.fid ) AS anzahl, SUM( fertigungsmeldungen.gewicht ) AS menge
        FROM fertigungsmeldungen, fidworkflow
        WHERE fidworkflow.fid = fertigungsmeldungen.fid
        AND fidworkflow.wstate ='$wstate'
        GROUP BY fertigungsmeldungen.material ASC 
        ORDER BY anzahl DESC";
        $mysqli = new mysqli($hostname_datenbank, $username_datenbank, $password_datenbank, $database_datenbank);
        $rst=$mysqli->query($selectSQL);
        if ($mysqli->affected_rows>0) {
            $row=$rst->fetch_assoc();
            do {
                $r[]=$row;
            } while ($row=$rst->fetch_assoc() );

            if (count($r)>0){
                //echo print_r($s);
                $h="<table class='table table-striped'>";
                foreach ($r as $i=>$item){
                    $h.="<tr>".
                        "<td>".($i+1).
                        "</td><td>".$item['material'].
                        "</td><td>".$item['anzahl']." Gebinde ".
                        "</td><td>".($item['menge']/1000)." t".
                        "</td>";
                    $h.="</tr>";
                }
                $h.="</table>";

                return $h;
            }
        } else{
            return "keine Daten vorhanden";
        }
    }

    function ShowAllFidDataByMaterial($fstate=0){
        include("../apps/dbconnection.php");

        $str="";
        if ($fstate>0) {
            $str="WHERE fertigungsmeldungen.fstate = '".$fstate."'";
        }
        $selectSQL="SELECT DISTINCT fertigungsmeldungen.material, count(fertigungsmeldungen.fid) AS anzahl, sum(fertigungsmeldungen.gewicht) AS menge 
                    FROM fertigungsmeldungen $str group by fertigungsmeldungen.material ASC order by anzahl desc";


        $mysqli = new mysqli($hostname_datenbank, $username_datenbank, $password_datenbank, $database_datenbank);
        $rst=$mysqli->query($selectSQL);
        if ($mysqli->affected_rows>0) {
            $row=$rst->fetch_assoc();
            do {
                $r[]=$row;
            } while ($row=$rst->fetch_assoc() );

            if (count($r)>0){
                //echo print_r($s);
                $h="<table class='table table-striped'>";
                foreach ($r as $i=>$item){
                    $h.="<tr>".
                    "<td>".($i+1).
                    "</td><td>".utf8_decode($item['material']).
                    "</td><td>".$item['anzahl']." Gebinde ".
                    "</td><td>".($item['menge']/1000)." t".
                    "</td>";
                    $h.="</tr>";
                }
                $h.="</table>";

                return $h;
            }
        } else{
            return "keine Daten vorhanden";
        }
    }


    function ShowAllFidData(){
        include("../apps/dbconnection.php");
        $selectSQL="SELECT count(fertigungsmeldungen.fid) AS anzahl FROM fertigungsmeldungen";
        $mysqli = new mysqli($hostname_datenbank, $username_datenbank, $password_datenbank, $database_datenbank);
        $rst=$mysqli->query($selectSQL);
        if ($mysqli->affected_rows>0) {
            $row_rst = $rst->fetch_assoc();
            return $row_rst['anzahl'];
        } else{
            return 0;
        }
    }

    function ShowFidWstateData($j){
        include("../apps/dbconnection.php");
        $selectSQL="SELECT count(fertigungsmeldungen.fid) AS anzahl FROM fertigungsmeldungen, fidworkflow 
        WHERE fidworkflow.fid = fertigungsmeldungen.fid AND fidworkflow.wstate='".$j."'";
        $mysqli = new mysqli($hostname_datenbank, $username_datenbank, $password_datenbank, $database_datenbank);
        $rst1=$mysqli->query($selectSQL);
        if ($mysqli->affected_rows>0) {
            $row_rst = $rst1->fetch_assoc();
            return $row_rst['anzahl'];
        } else{
            return 0;
        }
    }

    function ShowFidData($j){
        include("../apps/dbconnection.php");
        $job[0]=array(0=>"PruferMFR",   1=>"MFR Prüfung",  2=>10);
        $job[1]=array(0=>"PruferRF",    1=>"RF-Prüfung",   2=>11);
        $job[2]=array(0=>"PruferMA2",   1=>"MA2-Prüfung",  2=>12);
        $job[3]=array(0=>"PruferMA",    1=>"MA-Prüfung",   2=>13);
        $job[4]=array(0=>"PruferF",     1=>"Farbprüfung",  2=>14);
        $job[5]=array(0=>"PruferQ",     1=>"Q-Freigabe",   2=>15);


        $selectSQL="SELECT count(fertigungsmeldungen.fid) AS anzahl FROM fertigungsmeldungen, fidworkflow 
        WHERE fidworkflow.fid = fertigungsmeldungen.fid AND fidworkflow.wstate='".$job[$j][2]."'";
        $mysqli = new mysqli($hostname_datenbank, $username_datenbank, $password_datenbank, $database_datenbank);
        $rst1=$mysqli->query($selectSQL);
        if ($mysqli->affected_rows>0) {
            $row_rst = $rst1->fetch_assoc();
            return $row_rst['anzahl'];
        } else{
            return 0;
        }
    }



}
