<?php
/**
 * Created by PhpStorm.
 * User: andreasveltjens
 * Date: 21.10.18
 * Time: 20:02
 */


/** Layout Standard */
function debuglog($s){
    return str_replace("`"," ",$s);
}

function FisTranslate($str){

    // this function is need to print html-code with windows apache + mysql webserver
    $str=str_replace("sz","ß",$str);
    $str = str_replace("aex", "ä", $str);
    $str = str_replace("uex", "ü", $str);
    $str = str_replace("oex", "ö", $str);

    $str=str_replace("o00fc","ö",$str);
    $str=str_replace("ufffd","ß",$str);
    $str=str_replace("u00e4","ä",$str);
    $str=str_replace("u00fc","ü",$str);

    $str=str_replace("o00dc","Ö",$str);
    //$str=str_replace("sz","ß",$str);
    $str=str_replace("a00dc","Ä",$str);
    $str=str_replace("u00dc","Ü",$str);


    return $str;
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
         role=\"progressbar\" aria-valuenow=\" ".round($value,0)."\" aria-valuemin=\"0\" aria-valuemax=\"100\" 
         style=\"width: ".round($value,0)."%; visibility: visible; animation-name: animationProgress;\">
                                      </div><!-- /.progress-bar .progress-bar-danger -->
                                    </div><!-- /.progress .no-rounded -->";

    return $h.$valuetxt.$h1.$value.$h2;
}


function asCircleDiagramm(){
    $h="<div data-plugin=\"circliful\" class=\"circliful-chart\" data-startdegree=\"45\"
                 data-dimension=\"200\" data-text=\"50%\" data-info=\"New Clients\" data-width=\"30\"
                 data-fontsize=\"24\" data-percent=\"50\" data-fgcolor=\"#64b5f6\"
                 data-bgcolor=\"#eeeeee\" data-type=\"half\" data-fill=\"#f1f1f1\">
         </div>
       ";
    return $h;
}

function asSwitchery($label = "Label", $name = "name", $value = "2", $color = "00b19d", $col = 7, $required = "required", $js = "")
{
    /**
     * 00b19d
        ffaa00
        3bafda
        3DDCF7
        7266ba
        f76397
        4c5667
        98a6ad
        ef5350
     */

    $r="";
    if ($value == "on") {
        $value = "1";
    }
    if ($value == 1) {
        $value = "1";
    }

    //  if ($value=="" or $value==0 or $value=="off" or is_null($value)){ $checked=""; }else {$checked="checked";}
    if ($value == "1") {
        $checked = "checked";
    } else {
        $checked = "";
    }
    if ($required == "" && strlen($label) > 0) {
        $r = "*";
    } else {
        $t = "text text-danger";
    }
    $h=" <div class=\"switchery-$name\">
            <label for=\"$name\" class=\"col-sm-" . (12 - $col) . " control-label $t\">$label $r </label>
            <input type=\"checkbox\" name=\"$name\" id=\"$name\" " . $checked . " data-plugin=\"switchery\" data-color=\"#$color\" $js/>
        </div>";
    return $h;
}
function asSelectBox($label="Label",$name="name",$option="<option value=''>x</option>",$col=7,$required="required",$js=""){
    $r="";
    if ($required == "" && strlen($label) > 0) {
        $r = "*";
    } else {
        $t = "text text-danger";
    }
    $h=" <div class=\"form-group\">
            <label for=\"$name\" class=\"col-sm-" . (12 - $col) . " control-label $t\">$label $r </label>
            <div class=\"col-sm-$col\">
                <select $required class=\"form-control\" name=\"$name\" id=\"$name\" $js>
                    $option
                </select>
            </div>
        </div>";
    return $h;
}
function asSelect2Box($label="Label",$name="name",$option="<option value=''>x</option>",$col=7,$required="required",$js=""){
    $r="";
    if ($required == "" && strlen($label) > 0) {
        $r = "*";
    } else {
        $t = "text text-danger";
    }
    $h=" <div class=\"form-group\">
            <label for=\"$name\" class=\"col-sm-" . (12 - $col) . " $t\">$label $r </label>
            <div class=\"col-sm-$col\">
                <select $required class=\"form-control select2\" name=\"$name\" id=\"$name\" $js>
                    $option
                </select>
            </div>
        </div>";
    $_SESSION['script'].="  
    $(\"#$name\").select2();
    
    $('select').on('select2-opening', function() {
        var container = $(this).select2('container')
        var position = $(this).select2('container').offset().top
        var avail_height = $(window).height() - container.offset().top - container.outerHeight()
        $('ul.select2-results').css('max-height', (avail_height - 50))
      
    })
    ";
    return $h;
}
function asTextField($label="Label",$name="name",$value="",$col=7,$required="required parsley-type=\"text\"",$js=""){
    $r="";
    if ($required == "" && strlen($label) > 0) {
        $r = "*";
    } else {
        $t = "text text-danger";
    }
    $h=" <div class=\"form-group\">
            <label for=\"$name\" class=\"col-sm-" . (12 - $col) . " control-label $t\">$label $r </label>
            <div class=\"col-sm-$col\">
                <input  type=\"text\" $required class=\"form-control\" name=\"$name\" id=\"$name\" placeholder=\"$label\" value=\"$value\" $js>
            </div>
        </div>";
    return $h;
}

function asTextarea($label = "Label", $name = "name", $value = "", $rows = 10, $col = 7, $required = "required parsley-type=\"text\"", $js = "")
{
    $r="";
    if ($required == "" && strlen($label) > 0) {
        $r = "*";
    } else {
        $t = "text text-danger";
    }
    $h=" <div class=\"form-group\">
            <label for=\"$name\" class=\"col-sm-" . (12 - $col) . " control-label $t\">$label $r </label>
            <div class=\"col-sm-$col\">
                <textarea $required class=\"form-control\" name=\"$name\" id=\"$name\" placeholder=\"$label\" rows=\"$rows\" $js >$value</textarea>
            </div>
        </div>";
    return $h;
}

function asModal($id=3,$txt,$link,$size=55,$title="Neu anlegen"){
    $h="<div id=\"custom-width-modal$id\" class=\"modal fade\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"custom-width-modalLabel\" aria-hidden=\"true\" style=\"display: none;\">
            <div class=\"modal-dialog\" style=\"width:$size%;\">
                <div class=\"modal-content\">
                    <div class=\"modal-header\">
                        <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">×</button>
                        <h4 class=\"modal-title\" id=\"custom-width-modalLabel\">$title</h4>
                    </div>
                    <div class=\"modal-body table-responsive\">
                        $txt
                        <br>
                    </div>
                    <div class=\"modal-footer\">
                        <button type=\"button\" class=\"btn btn-default waves-effect\" data-dismiss=\"modal\">Abbrechen</button>
                        $link
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->";
    return $h;
}

function ShowHtmlWaitingAfterClick(){
    $h="<div id='progress' class=\"col-sm-12 \"> 
            <div class='card-box table-responsive'>
                <div class='text-center'>
                <i class='fa fa-spinner fa-spin fa-5x'> </i><br><br>Funktion wird ausgeführt. Bitte warten.
                <div class=\"progress\">
                    <div class=\"progress-bar progress-bar-primary\" role=\"progressbar\" aria-valuenow=\"60\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width: 60%;\">
                        <span class=\"sr-only\">60% Complete</span>
                    </div>
                   
                </div>
                <br><br>
                 <a href='#' class='btn btn-custom' onclick='Javascript:reset_site()'> Abbrechen </a>
                </div>
            </div>
        </div>";
    $_SESSION['script'].="  
        $(\"#progress\").show();
          $(\"#change\").hide();  
        ";

    return $h;
}

function asJSLoader($divid,$txt="Es wird geladen...",$datasource="searchall.php",$request="datatype=returndevices"){
    $h="<div id='$divid'></div>";
    $h .= "<script>
          
                function load_jsloader_".$divid."_layout(){   
                    $(\"#$divid\").html('$txt <i class=\"fa fa-refresh fa-spin\"><i> ');                          
                    \$(\"#$divid\").load(\"$datasource?sess=" . $_SESSION['sess'] . "&".$request."\" , function(responseTxt, statusTxt, xhr){
                 });
                
                 }
                </script>
                ";
    $_SESSION['endscripts'] .= "
                    load_jsloader_".$divid."_layout();
                    ";

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

/** Equipment Functions */
function GetEventTypeName($type,$value){
    $h="";
    switch ($type){
        case "select":{
            include ("./connection.php");
            $sql="SELECT * FROM fhm_ereignisart ORDER BY fhm_ereignisart.name ASC";
            $rst=$mysqli->query($sql);
            if (mysqli_affected_rows($mysqli)>0) {
                $row_rst = $rst->fetch_assoc();
                do {

                    if ($value==$row_rst['id_fhmea'] ){$s="selected";}else{$s="";}
                    $h.="<option value=\"".$row_rst['id_fhmea']."\" $s>".utf8_decode($row_rst['name'])."</option>";

                } while ($row_rst = $rst->fetch_assoc() );
            }
            break;
        }
        case "select2":{
            include ("./connection.php");
            $sql="SELECT * FROM fhm_ereignisart ORDER BY fhm_ereignisart.name ASC";
            $rst=$mysqli->query($sql);
            $ok=false;
            $h.="<option value=\"\">nichts ausgewählt</option>";
            if (mysqli_affected_rows($mysqli)>0) {
                $row_rst = $rst->fetch_assoc();
                do {

                    if ($value==$row_rst['id_fhmea'] ){$s="selected";$ok=true;}else{$s="";}
                    $h.="<option value=\"".$row_rst['id_fhmea']."\" $s>".utf8_decode($row_rst['name'])."</option>";

                } while ($row_rst = $rst->fetch_assoc() );
                if (!$ok){$s="selected";}else{$s="";}
                $h.="<option value=\"".$value."\" $s>".$value."</option>";
            }
            break;
        }
        case "text":{
            include ("./connection.php");
            $sql="SELECT * FROM fhm_ereignisart WHERE fhm_ereignisart.id_fhmea='$value'  ";
            $rst=$mysqli->query($sql);
            if (mysqli_affected_rows($mysqli)>0){
                $row_rst=$rst->fetch_assoc();
                $h=utf8_decode($row_rst['name']);
            }
            break;
        }
        case "deb":{
            include ("./connection.php");
            $sql="SELECT * FROM fhm_ereignisart WHERE fhm_ereignisart.id_fhmea='$value'  ";
            $rst=$mysqli->query($sql);
            if (mysqli_affected_rows($mysqli)>0){
                $row_rst=$rst->fetch_assoc();
                $h=utf8_decode($row_rst['name']);
            }
            break;
        }
        case "data":{
            include ("./connection.php");
            $sql="SELECT * FROM kundendaten WHERE kundendaten.idk='$value'  ";
            $rst=$mysqli->query($sql);
            if (mysqli_affected_rows($mysqli)>0){
                $row_rst=$rst->fetch_assoc();
                $h=$row_rst;
            }
            break;
        }
    }
    return $h;
}
function GetEquipmentGroupName($type,$value){
    $h="";
    switch ($type){
        case "select":{
            include ("./connection.php");
            $sql="SELECT * FROM fhm_gruppe ORDER BY fhm_gruppe.name ASC";
            $rst=$mysqli->query($sql);
            if (mysqli_affected_rows($mysqli)>0) {
                $row_rst = $rst->fetch_assoc();
                do {

                    if ($value==$row_rst['id_fhm_gruppe'] ){$s="selected";}else{$s="";}
                    $h.="<option value=\"".$row_rst['id_fhm_gruppe']."\" $s>".utf8_decode($row_rst['name'])."</option>";

                } while ($row_rst = $rst->fetch_assoc() );
            }
            break;
        }
        case "select2":{
            include ("./connection.php");
            $sql="SELECT * FROM fhm_gruppe ORDER BY fhm_gruppe.name ASC";
            $rst=$mysqli->query($sql);
            $ok=false;
            $h.="<option value=\"\">nichts ausgewählt</option>";
            if (mysqli_affected_rows($mysqli)>0) {
                $row_rst = $rst->fetch_assoc();
                do {

                    if ($value==$row_rst['id_fhm_gruppe'] ){$s="selected";$ok=true;}else{$s="";}
                    $h.="<option value=\"".$row_rst['id_fhm_gruppe']."\" $s>".utf8_decode($row_rst['name'])."</option>";

                } while ($row_rst = $rst->fetch_assoc() );
                if (!$ok){$s="selected";}else{$s="";}
                $h.="<option value=\"".$value."\" $s>".$value."</option>";
            }
            break;
        }
        case "text":{
            include ("./connection.php");
            $sql="SELECT * FROM fhm_gruppe WHERE fhm_gruppe.id_fhm_gruppe='$value'  ";
            $rst=$mysqli->query($sql);
            if (mysqli_affected_rows($mysqli)>0){
                $row_rst=$rst->fetch_assoc();
                $h=utf8_decode($row_rst['name']);
            }
            break;
        }
        case "deb":{
            include ("./connection.php");
            $sql="SELECT * FROM fhm_gruppe WHERE fhm_gruppe.id_fhm_gruppe='$value'  ";
            $rst=$mysqli->query($sql);
            if (mysqli_affected_rows($mysqli)>0){
                $row_rst=$rst->fetch_assoc();
                $h=utf8_decode($row_rst['name']);
            }
            break;
        }
        case "data":{
            include ("./connection.php");
            $sql="SELECT * FROM kundendaten WHERE kundendaten.idk='$value'  ";
            $rst=$mysqli->query($sql);
            if (mysqli_affected_rows($mysqli)>0){
                $row_rst=$rst->fetch_assoc();
                $h=$row_rst;
            }
            break;
        }
    }
    return $h;
}
function GetEquipmentName($type,$value){
    $h="";
    switch ($type){
        case "select":{
            include ("./connection.php");
            $sql="SELECT * FROM fhm ORDER BY fhm.name ASC";
            $rst=$mysqli->query($sql);
            if (mysqli_affected_rows($mysqli)>0) {
                $row_rst = $rst->fetch_assoc();
                do {

                    if ($value==$row_rst['id_fhm'] ){$s="selected";}else{$s="";}
                    $h.="<option value=\"".$row_rst['id_fhm']."\" $s>".utf8_decode($row_rst['name'])." - ".utf8_decode($row_rst['sn'])." - ".utf8_decode($row_rst['Inventarnummer'])."</option>";

                } while ($row_rst = $rst->fetch_assoc() );
            }
            break;
        }
        case "select2":{
            include ("./connection.php");
            $sql="SELECT * FROM fhm ORDER BY fhm.name ASC";
            $rst=$mysqli->query($sql);
            $ok=false;
            $h.="<option value=\"\">nichts ausgewählt</option>";
            if (mysqli_affected_rows($mysqli)>0) {
                $row_rst = $rst->fetch_assoc();
                do {

                    if ($value==$row_rst['id_fhm'] ){$s="selected";$ok=true;}else{$s="";}
                    $h.="<option value=\"".$row_rst['id_fhm']."\" $s>".utf8_decode($row_rst['name'])." - ".utf8_decode($row_rst['sn'])." - ".utf8_decode($row_rst['Inventarnummer'])."</option>";

                } while ($row_rst = $rst->fetch_assoc() );
                if (!$ok){$s="selected";}else{$s="";}
                $h.="<option value=\"".$value."\" $s>".$value."</option>";
            }
            break;
        }
        case "text":{
            include ("./connection.php");
            $sql="SELECT * FROM fhm WHERE fhm.id_fhm='$value'  ";
            $rst=$mysqli->query($sql);
            if (mysqli_affected_rows($mysqli)>0){
                $row_rst=$rst->fetch_assoc();
                $h=utf8_decode($row_rst['name']);
            }
            break;
        }
        case "deb":{
            include ("./connection.php");
            $sql="SELECT * FROM fhm WHERE fhm.id_fhm='$value'  ";
            $rst=$mysqli->query($sql);
            if (mysqli_affected_rows($mysqli)>0){
                $row_rst=$rst->fetch_assoc();
                $h=utf8_decode($row_rst['name']);
            }
            break;
        }
        case "data":{
            include ("./connection.php");
            $sql="SELECT * FROM kundendaten WHERE kundendaten.idk='$value'  ";
            $rst=$mysqli->query($sql);
            if (mysqli_affected_rows($mysqli)>0){
                $row_rst=$rst->fetch_assoc();
                $h=$row_rst;
            }
            break;
        }
    }
    return $h;
}


function GetFhmCalibrationText($type, $value)
{
    $r = array(
        "Testprogramm MK1" => "TESTPROGRAM 630 g",
        "Testprogramm MK2 / MK3" => "TESTPROGRAM 500 g  (MK2 / MK3) ",
        "anderes Programm" => "anderes Programm");
    $h = "";
    switch ($type) {
        case "select":
            {
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
        case "text":
            {
                $h = $r[$value];
                break;
            }
    }
    return $h;
}

/** Customer Functions */
function GetCustomerName($type,$value){
    $h="";
    switch ($type){
        case "select":{
            include ("./connection.php");
            $sql="SELECT * FROM kundendaten ORDER BY kundendaten.Firma ASC";
            $rst=$mysqli->query($sql);
            if (mysqli_affected_rows($mysqli)>0) {
                $row_rst = $rst->fetch_assoc();
                do {

                    if ($value==$row_rst['idk'] ){$s="selected";}else{$s="";}
                    $h.="<option value=\"".$row_rst['idk']."\" $s>".utf8_decode($row_rst['firma'])." - ".utf8_decode($row_rst['plz'])." - ".utf8_decode($row_rst['ort'])."</option>";

                } while ($row_rst = $rst->fetch_assoc() );
            }
            break;
        }
        case "select2":{
            include ("./connection.php");
            $sql="SELECT * FROM kundendaten ORDER BY kundendaten.Firma ASC";
            $rst=$mysqli->query($sql);
            $ok=false;
            $h.="<option value=\"\">nichts ausgewählt</option>";
            if (mysqli_affected_rows($mysqli)>0) {
                $row_rst = $rst->fetch_assoc();
                do {

                    if ($value==$row_rst['idk'] ){$s="selected";$ok=true;}else{$s="";}
                    $h.="<option value=\"".$row_rst['idk']."\" $s>".utf8_decode($row_rst['firma'])." - ".utf8_decode($row_rst['plz'])." - ".utf8_decode($row_rst['ort'])."</option>";

                } while ($row_rst = $rst->fetch_assoc() );
                if (!$ok){$s="selected";}else{$s="";}
                $h.="<option value=\"".$value."\" $s>".$value."</option>";
            }
            break;
        }
        case "select2service":
            {
                include("./connection.php");
                $sql = "SELECT * FROM kundendaten WHERE kundendaten.service=1 ORDER BY kundendaten.Firma ASC";
                $rst = $mysqli->query($sql);
                $ok = false;
                $h .= "<option value=\"\">nichts ausgewählt</option>";
                if (mysqli_affected_rows($mysqli) > 0) {
                    $row_rst = $rst->fetch_assoc();
                    do {

                        if ($value == $row_rst['idk']) {
                            $s = "selected";
                            $ok = true;
                        } else {
                            $s = "";
                        }
                        $h .= "<option value=\"" . $row_rst['idk'] . "\" $s>" . utf8_decode($row_rst['firma']) . " - " . utf8_decode($row_rst['plz']) . " - " . utf8_decode($row_rst['ort']) . "</option>";

                    } while ($row_rst = $rst->fetch_assoc());
                    if (!$ok) {
                        $s = "selected";
                    } else {
                        $s = "";
                    }
                    $h .= "<option value=\"" . $value . "\" $s>" . $value . "</option>";
                }
                break;
            }
        case "text":{
            include ("./connection.php");
            $sql="SELECT * FROM kundendaten WHERE kundendaten.idk='$value'  ";
            $rst=$mysqli->query($sql);
            if (mysqli_affected_rows($mysqli)>0){
                $row_rst=$rst->fetch_assoc();
                $h=utf8_decode($row_rst['firma']);
            }
            break;
        }
        case "deb":{
            include ("./connection.php");
            $sql="SELECT * FROM kundendaten WHERE kundendaten.kundenummer='$value'  ";
            $rst=$mysqli->query($sql);
            if (mysqli_affected_rows($mysqli)>0){
                $row_rst=$rst->fetch_assoc();
                $h=utf8_decode($row_rst['firma']);
            }
            break;
        }
        case "data":{
            include ("./connection.php");
            $sql="SELECT * FROM kundendaten WHERE kundendaten.idk='$value'  ";
            $rst=$mysqli->query($sql);
            if (mysqli_affected_rows($mysqli)>0){
                $row_rst=$rst->fetch_assoc();
                $h=$row_rst;
            }
            break;
        }
    }
    return $h;
}
/** Material Functions */
function GetArtikelGroup($type,$value){
    $h="";
    switch ($type){
        case "select":{
            include ("./connection.php");
            $sql="SELECT distinct `artikeldaten`.Gruppe FROM `artikeldaten` ORDER BY  `artikeldaten`.Gruppe ASC";
            $rst=$mysqli->query($sql);
            if (mysqli_affected_rows($mysqli)>0) {
                $row_rst = $rst->fetch_assoc();
                do {

                    if ($value==$row_rst['Gruppe'] ){$s="selected";}else{$s="";}
                    $h.="<option value=\"".$row_rst['Gruppe']."\" $s>".$row_rst['Gruppe']."</option>";

                } while ($row_rst = $rst->fetch_assoc() );
            }
            break;
        }
        case "select2":{
            include ("./connection.php");
            $sql="SELECT distinct `artikeldaten`.Gruppe FROM `artikeldaten` ORDER BY  `artikeldaten`.Gruppe ASC";
            $rst=$mysqli->query($sql);
            $ok=false;
            $h.="<option value=\"\">nichts ausgewählt</option>";
            if (mysqli_affected_rows($mysqli)>0) {
                $row_rst = $rst->fetch_assoc();
                do {

                    if ($value==$row_rst['Gruppe'] ){$s="selected";$ok=true;}else{$s="";}
                    $h.="<option value=\"".$row_rst['Gruppe']."\" $s>".$row_rst['Gruppe']."</option>";

                } while ($row_rst = $rst->fetch_assoc() );
                if (!$ok){$s="selected";}else{$s="";}
                $h.="<option value=\"".$value."\" $s>".$value." (zuletzt ausgewählt)</option>";
            }
            break;
        }
        case "text":{
            include ("./connection.php");
            $sql="SELECT distinct `artikeldaten`.Gruppe FROM `artikeldaten` WHERE  `artikeldaten`.Gruppe ='$value'  ";
            $rst=$mysqli->query($sql);
            if (mysqli_affected_rows($mysqli)>0){
                $row_rst=$rst->fetch_assoc();
                $h=$row_rst['Gruppe'];
            }
            break;
        }
        case "material":{
            include ("./connection.php");
            $sql="SELECT distinct `artikeldaten`.Gruppe FROM `artikeldaten` WHERE  `artikeldaten`.Gruppe ='$value' ";
            $rst=$mysqli->query($sql);
            if (mysqli_affected_rows($mysqli)>0){
                $row_rst=$rst->fetch_assoc();
                $h=$row_rst['Gruppe'];
            }
            break;
        }
    }
    return $h;
}
function GetArtikelKontierung($type,$value){
    $h="";
    switch ($type){
        case "select":{
            include ("./connection.php");
            $sql="SELECT distinct `artikeldaten`.Kontierung FROM `artikeldaten` ORDER BY  `artikeldaten`.Kontierung ASC ";
            $rst=$mysqli->query($sql);
            if (mysqli_affected_rows($mysqli)>0) {
                $row_rst = $rst->fetch_assoc();
                do {

                    if ($value==$row_rst['Kontierung'] ){$s="selected";}else{$s="";}
                    $h.="<option value=\"".$row_rst['Kontierung']."\" $s>".$row_rst['Kontierung']."</option>";

                } while ($row_rst = $rst->fetch_assoc() );
            }
            break;
        }
        case "select2":{
            include ("./connection.php");
            $sql="SELECT distinct `artikeldaten`.Kontierung FROM `artikeldaten` ORDER BY  `artikeldaten`.Kontierung ASC ";
            $rst=$mysqli->query($sql);
            $ok=false;
            $h.="<option value=\"\">nichts ausgewählt</option>";
            if (mysqli_affected_rows($mysqli)>0) {
                $row_rst = $rst->fetch_assoc();
                do {

                    if ($value==$row_rst['Kontierung'] ){$s="selected";$ok=true;}else{$s="";}
                    $h.="<option value=\"".$row_rst['Kontierung']."\" $s>".$row_rst['Kontierung']."</option>";

                } while ($row_rst = $rst->fetch_assoc() );
                if (!$ok){$s="selected";}else{$s="";}
                $h.="<option value=\"".$value."\" $s>".$value." (zuletzt ausgewählt)</option>";
            }
            break;
        }
        case "text":{
            include ("./connection.php");
            $sql="SELECT * FROM artikeldaten WHERE artikeldaten.artikelid='$value'  ";
            $rst=$mysqli->query($sql);
            if (mysqli_affected_rows($mysqli)>0){
                $row_rst=$rst->fetch_assoc();
                $h=$row_rst['Kontierung'];
            }
            break;
        }
        case "material":{
            include ("./connection.php");
            $sql="SELECT distinct `artikeldaten`.Kontierung FROM `artikeldaten` WHERE  `artikeldaten`.Kontierung ='$value'   ";
            $rst=$mysqli->query($sql);
            if (mysqli_affected_rows($mysqli)>0){
                $row_rst=$rst->fetch_assoc();
                $h=$row_rst['Kontierung'];
            }
            break;
        }
        case "data":{
            include ("./connection.php");
            $sql="SELECT distinct `artikeldaten`.Kontierung FROM `artikeldaten` ORDER BY  `artikeldaten`.Kontierung ASC ";
            $rst=$mysqli->query($sql);
            if (mysqli_affected_rows($mysqli)>0) {
                $row_rst = $rst->fetch_assoc();
                do {
                   $h[]=$row_rst;
                } while ($row_rst = $rst->fetch_assoc() );
            }else{
                $h=array();
            }
            break;
        }
    }
    return $h;
}
function GetArtikelProductfamily($type,$value){
    $h="";
    switch ($type){
        case "select":{
            include ("./connection.php");
            $sql="SELECT distinct `artikeldaten`.productfamily FROM `artikeldaten` ORDER BY  `artikeldaten`.productfamily ASC ";
            $rst=$mysqli->query($sql);
            $h.="<option value=\"\">nichts ausgewählt</option>";
            if (mysqli_affected_rows($mysqli)>0) {
                $row_rst = $rst->fetch_assoc();

                do {

                    if ($value==$row_rst['productfamily'] ){$s="selected";}else{$s="";}
                    $h.="<option value=\"".$row_rst['productfamily']."\" $s>".$row_rst['productfamily']."</option>";

                } while ($row_rst = $rst->fetch_assoc() );
            }
            break;
        }
        case "select2":{
            include ("./connection.php");
            $sql="SELECT distinct `artikeldaten`.productfamily FROM `artikeldaten` ORDER BY  `artikeldaten`.productfamily ASC ";
            $rst=$mysqli->query($sql);
            $ok=false;
            $h.="<option value=\"\">nichts ausgewählt</option>";

            if (mysqli_affected_rows($mysqli)>0) {
                $row_rst = $rst->fetch_assoc();
                do {

                    if ($value==$row_rst['productfamily'] ){$s="selected";$ok=true;}else{$s="";}
                    $h.="<option value=\"".$row_rst['productfamily']."\" $s>".$row_rst['productfamily']."</option>";

                } while ($row_rst = $rst->fetch_assoc() );
                if (!$ok){$s="selected";}else{$s="";}
                $h.="<option value=\"".$value."\" $s>".$value."(zuletzt ausgewählt)</option>";
            }
            break;
        }

    }
    return $h;
}

function GetArtikelName($type,$value,$search=""){
    $h="";
    switch ($type){
        case "select":{
            include ("./connection.php");
            $sql="SELECT * FROM artikeldaten ORDER BY artikeldaten.Bezeichnung ASC";
            $rst=$mysqli->query($sql);
            if (mysqli_affected_rows($mysqli)>0) {
                $row_rst = $rst->fetch_assoc();
                do {

                    if ($value==$row_rst['artikelid'] ){$s="selected";}else{$s="";}
                    $h.="<option value=\"".$row_rst['artikelid']."\" $s>".$row_rst['Bezeichnung']." (".$row_rst['Nummer'].")</option>";

                } while ($row_rst = $rst->fetch_assoc() );
            }
            break;
        }
        case "select2":{
            include ("./connection.php");
            $sql="SELECT * FROM artikeldaten ORDER BY artikeldaten.Bezeichnung ASC";
            $rst=$mysqli->query($sql);
            $ok=false;
            $h.="<option value=\"0\">nichts ausgewählt</option>";
            if (mysqli_affected_rows($mysqli)>0) {
                $row_rst = $rst->fetch_assoc();
                do {

                    if ($value==$row_rst['artikelid'] ){$s="selected";$ok=true;}else{$s="";}
                    $h.="<option value=\"".$row_rst['artikelid']."\" $s>".$row_rst['Bezeichnung']." (".$row_rst['Nummer'].")</option>";

                } while ($row_rst = $rst->fetch_assoc() );
                if (!$ok){$s="selected";}else{$s="";}
                $h.="<option value=\"".$value."\" $s>".$value."</option>";
                $h.="<option value=\"\">nichts ausgewählt</option>";

            }
            break;
        }
        case "searchbysn":{
            include ("./connection.php");
            if ($search==999999 or $search==0){
                $sql="SELECT DISTINCT artikeldaten.artikelid, artikeldaten.Nummer,artikeldaten.Bezeichnung FROM artikeldaten, fertigungsmeldungen 
            WHERE fertigungsmeldungen.fartikelid=artikeldaten.artikelid
          
            ORDER BY artikeldaten.Bezeichnung ASC";

            }else{


            $sql="SELECT DISTINCT artikeldaten.artikelid, artikeldaten.Nummer,artikeldaten.Bezeichnung FROM artikeldaten, fertigungsmeldungen 
            WHERE fertigungsmeldungen.fartikelid=artikeldaten.artikelid
            AND fertigungsmeldungen.fsn='".intval($search*1)."'
            ORDER BY artikeldaten.Bezeichnung ASC";
            }

            $rst=$mysqli->query($sql);
            $ok=false;

            //$h.="<option value=\"0\">nichts ausgewählt</option>";
            if (mysqli_affected_rows($mysqli)>0) {
                $row_rst = $rst->fetch_assoc();
                do {

                    if ($value==$row_rst['artikelid'] ){$s="selected";$ok=true;}else{$s="";}
                    $h.="<option value=\"".$row_rst['artikelid']."\" $s>".$row_rst['Bezeichnung']." (".$row_rst['Nummer'].")</option>";

                } while ($row_rst = $rst->fetch_assoc() );
                if (!$ok){$s="selected";}else{$s="";}
                $h.="<option value=\"".$value."\" $s>bitte auswählen</option>";
                //$h.="<option value=\"\">nichts ausgewählt</option>";

            }
            break;
        }
        case "searchbymaterial":{
            include ("./connection.php");
            $sql="SELECT * FROM artikeldaten WHERE artikeldaten.Nummer LIKE '%$value%' LIMIT 0,1 ";
            $rst=$mysqli->query($sql);
            if (mysqli_affected_rows($mysqli)>0){
                $row_rst=$rst->fetch_assoc();
                $h=$row_rst['artikelid'];
            }else{
                $h="Material-Nr. im FIS nicht angelegt.";
            }
            break;
        }
        case "text":{
            include ("./connection.php");
            $sql="SELECT * FROM artikeldaten WHERE artikeldaten.artikelid='$value' LIMIT 0,1 ";
            $rst=$mysqli->query($sql);
            if (mysqli_affected_rows($mysqli)>0){
                $row_rst=$rst->fetch_assoc();
                $h=$row_rst['Bezeichnung']." (".$row_rst['Nummer'].")";
            }
            break;
        }
        case "material":{
            include ("./connection.php");
            $sql="SELECT * FROM artikeldaten WHERE artikeldaten.artikelid='$value'  ";
            $rst=$mysqli->query($sql);
            if (mysqli_affected_rows($mysqli)>0){
                $row_rst=$rst->fetch_assoc();
                $h=$row_rst['Bezeichnung']." (".$row_rst['Nummer'].")";
            }
            break;
        }
        case "number":{
            include ("./connection.php");
            $sql="SELECT * FROM artikeldaten WHERE artikeldaten.artikelid='$value'  ";
            $rst=$mysqli->query($sql);
            if (mysqli_affected_rows($mysqli)>0){
                $row_rst=$rst->fetch_assoc();
                $h=$row_rst['Nummer'];
            }
            break;
        }
        case "lagerort":
            {
                include("./connection.php");
                $sql = "SELECT * FROM artikeldaten WHERE artikeldaten.artikelid='$value'  ";
                $rst = $mysqli->query($sql);
                if (mysqli_affected_rows($mysqli) > 0) {
                    $row_rst = $rst->fetch_assoc();
                    $h = $row_rst['lagerplatz'];
                }
                break;
            }
        case "isFisArtikel":
            {
                include("./connection.php");
                $value += 0;
                $sql = "SELECT * FROM artikeldaten WHERE artikeldaten.Nummer ='$value' ";
                $rst = $mysqli->query($sql);
                if (mysqli_affected_rows($mysqli) > 0) {

                    $h = true;
                } else {
                    $h = false;
                }
                break;
            }
        case "isFisArtikelReturnArtikelid":
            {
                include("./connection.php");
                $value += 0;
                $sql = "SELECT artikeldaten.artikelid FROM artikeldaten WHERE artikeldaten.Nummer ='$value' LIMIT 0,1";
                $rst = $mysqli->query($sql);
                if (mysqli_affected_rows($mysqli) > 0) {

                    $row_rst = $rst->fetch_assoc();
                    $h = $row_rst['artikelid'];
                } else {
                    $h = false;
                }
                break;
            }
        case "isFisRep":
            {
                include("./connection.php");
                $value += 0;
                $sql = "SELECT * FROM artikeldaten WHERE artikeldaten.Nummer = '$value' AND artikeldaten.aktivrep=1";
                $rst = $mysqli->query($sql);
                if (mysqli_affected_rows($mysqli) > 0) {

                    $h = true;
                } else {
                    $h = false;
                }
                break;
            }
        case "data":{
            include ("./connection.php");
            $sql="SELECT * FROM artikeldaten WHERE artikeldaten.Nummer like '%".intval($value)."%'  LIMIT 0,1";
            $rst=$mysqli->query($sql);
            if (mysqli_affected_rows($mysqli)>0){
                $row_rst=$rst->fetch_assoc();
                $h=$row_rst['artikelid'];
            }
            break;
        }
    }
    return $h;
}
function GetYesNoList($type,$value){
    $r=array(1=>"ja",0=>"nein");
    $h="";
    switch ($type){
        case "select":{

            foreach ($r as $i=>$item){
                if ($value==$i ){$s="selected";}else{$s="";}
                $h.="<option value=\"".$i."\" $s>".$item."</option>";
            }

            break;
        }
        case "text":{
            $h=$r[$value];
            break;
        }
    }
    return $h;
}
/** Return Shipment Functions */
function GetControllingList($type,$value){
    $r=array(3=>"SAP R3",2=>"ABAS EKS Rückmeldung",1=>"fehlerhaft",0=>"nicht abgerechnet");
    $h="";
    switch ($type){
        case "select":{

            foreach ($r as $i=>$item){
                if ($value==$i ){$s="selected";}else{$s="";}
                $h.="<option value=\"".$i."\" $s>".$item."</option>";
            }

            break;
        }
        case "text":{
            $h=$r[$value];
            break;
        }
    }
    return $h;
}

function GetPrinterDefaults(){
    $p=array(
        'typenschild'=>array(
            "active"=>1,
            "count"=>1,
            "layout"=>array(
                "logo"=>1,
                "info"=>1,
                "eac"=>0,
                "ipx4"=>0,
                "ipx6"=>0,
                "vde"=>0,
                "vdegs"=>0,
                "weee"=>0,
                "ce"=>0,
            ),
        ),
        'verpackung'=>array(
            "active"=>1,
            "count"=>1,
            "layout"=>array(
                "logo"=>1,
                "info"=>0,
                "eac"=>0,
                "ipx4"=>0,
                "ipx6"=>0,
                "vde"=>0,
                "vdegs"=>0,
                "weee"=>0,
                "ce"=>0,
            ),
        ),
        'dokuset'=>array(
            "active"=>1,
            "count"=>1
        ),
        'einbau'=>array(
            "active"=>1,
            "count"=>1,
            "layout"=>array(
                "logo"=>1,
                "info"=>1,
                "eac"=>0,
                "ipx4"=>0,
                "ipx6"=>0,
                "vde"=>0,
                "vdegs"=>0,
                "weee"=>0,
                "ce"=>0,
            ),
        ),
        'fehlermeldung'=>array(
            "active"=>1,
            "count"=>2
        ),
        'wareneingang'=>array(
            "active"=>1,
            "count"=>2
        ),
    );
    return $p;
}

function GetFMState($type,$value){
    $r=array(
        0=>array(
            "desc"=>"alle",
            "icon"=>"fa fa-list",
            "color"=>"success",
            "inactive"=>"danger",

        ),
        1=>array(
            "desc"=>"Fehleraufnahme",
            "icon"=>"fa fa-stethoscope",
            "color"=>"success",
            "inactive"=>"danger",
        ),
        2=>array(
            "desc"=>"Kostenklärung",
            "icon"=>"zmdi zmdi-email",
            "color"=>"success",
            "inactive"=>"danger",
        ),
        3=>array(
            "desc"=>"Freigabe",
            "icon"=>"zmdi zmdi-email-open",
            "color"=>"success",
            "inactive"=>"danger",
        ),
        4=>array(
            "desc"=>"Reparatur",
            "icon"=>"zmdi zmdi-wrench",
            "color"=>"success",
            "inactive"=>"danger",
            ),
        5=>array(
            "desc"=>"Verpacken",
            "icon"=>"fa fa-cubes",
            "color"=>"success",
            "inactive"=>"danger",
            ),
        6=>array(
            "desc"=>"Abrechnung",
            "icon"=>"fa fa-check",
            "color"=>"success",
            "inactive"=>"danger",
            ),
        7=>array(
            "desc"=>"Rückmeldung",
            "icon"=>"fa fa-check",
            "color"=>"success",
            "inactive"=>"danger",
        ),
        8 => array(
            "desc" => "erledigte Rückmeldungen",
            "icon" => "fa fa-check",
            "color" => "success",
            "inactive" => "danger",
        ),
        96=>array(
            "desc"=>"Gewährleistung",
            "icon"=>"zmdi zmdi-card-giftcard",
            "color"=>"info",
            "inactive"=>"danger",
        ),
        97=>array(
            "desc"=>"Ersatz",
            "icon"=>"fa fa-refresh",
            "color"=>"success",
            "inactive"=>"danger",
        ),
        98=>array(
            "desc"=>"Upgrade",
            "icon"=>"fa fa-upload",
            "color"=>"success",
            "inactive"=>"danger",
            ),
        99=>array(
            "desc"=>"Verschrottung",
            "icon"=>"fa fa-times",
            "color"=>"warning",
            "inactive"=>"danger",
    )
    );
    $h="";
    switch ($type){
        case "select":{

            foreach ($r as $i=>$item){
                if ($value==$i ){$s="selected";}else{$s="";}
                $h.="<option value=\"".$i."\" $s>".$item['desc']."</option>";
            }

            break;
        }
        case "text":{
            $h=$r[$value]['desc'];
            break;
        }
        case "icon":{
            $h=$r[$value]['icon'];
            break;
        }
        case "data":{
            $h=$r;
            break;
        }
    }
    return $h;
}


function GetFidType($type, $value)
{
    $r = array(0 => "Neuanlage", 1 => "Nacharbeit", 2 => "Reparatur", 3 => "Upgrade", 4 => "Verschrottung", 5 => "Austausch", 99 => "gelöscht");
    $h = "";
    switch ($type) {
        case "select":
            {
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
        case "text":
            {
                $h = $r[$value];
                break;
            }
    }
    return $h;
}

function GetReturnContentText($type, $value)
{
    include("../config/templates.php");
    $r = $FIS_RETURN_TXT_TEMPLATE_CONTENT;
    $h = "";
    switch ($type) {
        case "select":
            {
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
        case "text":
            {
                $h = $r[$value];
                break;
            }
    }
    return $h;
}

function GetReturnRepairText($type, $value)
{
    include("../config/templates.php");
    $r = $FIS_RETURN_TXT_TEMPLATE_REPAIR;
    $h = "";
    switch ($type) {
        case "select":
            {
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
        case "text":
            {
                $h = $r[$value];
                break;
            }
    }
    return $h;
}

function GetFaufStates($type, $value)
{
    $r = array(0 => "alles anzeigen", 1 => "in Arbeit", 2 => "erledigt", 3 => "Nacharbeit");
    $h = "";
    switch ($type) {
        case "select":
            {
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
        case "text":
            {
                $h = $r[$value];
                break;
            }
    }
    return $h;
}

function GetFaufType($type, $value)
{
    $r = array(0 => "alles anzeigen", 1 => "Kundenauftrag", 2 => "Lagerfertigung", 3 => "Musterbau");
    $h = "";
    switch ($type) {
        case "select":
            {
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
        case "text":
            {
                $h = $r[$value];
                break;
            }
    }
    return $h;
}

function GetReportLimit($type, $value)
{
    $r = array(0 => "alle Werte", 5 => "TOP 5", 10 => "TOP 10", 15 => "TOP15", 20 => "TOP 20");
    $h = "";
    switch ($type) {
        case "select":
            {
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
        case "text":
            {
                $h = $r[$value];
                break;
            }
    }
    return $h;
}

function GetUserRights($type,$value){
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
        case "text":{ $h=$r[$value]; break;}
    }
    return $h;
}


function GetMaintenanceTimeDurations($type,$value){
    $r=array(0=>"nicht erinnern",1=>"Tag",7=>"1 Woche",14=>"14 Tage",21=>"3 Wochen",30=>"1 Monat",90=>"Quartal");
    $h="";
    switch ($type){
        case "select":{
            foreach ($r as $index => $item) {
                if ($value==$index ){$s="selected";}else{$s="";}
                $h.="<option value=\"".$index."\" $s>".$item."</option>";
            }
            break;
        }
        case "text":{ $h=$r[$value]; break;}
    }
    return $h;
}

function GetMaintenanceFrequency($type,$value){
    $r=array(0=>"manuell",1=>"täglich",7=>"wöchentlich",14=>"alle 14 Tage",21=>"alle 3 Wochen",
            30=>"monatlich",90=>"Quartalsweise",182=>"halbjährlich",
            365=>"jährlich", 730=>"alle 2 Jahre", 1095=>"alle 3 Jahre", 1460=>"alle 4 Jahre", 1825=>"alle 5 Jahre" );
    $h="";
    switch ($type){
        case "select":{
            foreach ($r as $index => $item) {
                if ($value==$index ){$s="selected";}else{$s="";}
                $h.="<option value=\"".$index."\" $s>".$item."</option>";
            }
            break;
        }
        case "text":{ $h=$r[$value]; break;}
    }
    return $h;
}

function GetMaintenanceTypes($type,$value){
    $r=array(0=>"alles anzeigen",1=>"Messmittelverwaltung",2=>"Gebäude",3=>"Maschinen & Anlagen");
    $h="";
    switch ($type){
        case "select":{
            foreach ($r as $index => $item) {
                if ($value==$index ){$s="selected";}else{$s="";}
                $h.="<option value=\"".$index."\" $s>".$item."</option>";
            }
            break;
        }
        case "text":{ $h=$r[$value]; break;}
    }
    return $h;
}

function GetEventStates($type,$value){
    $r=array(0=>"alles anzeigen",1=>"in Arbeit",2=>"erledigt");
    $h="";
    switch ($type){
        case "select":{
            foreach ($r as $index => $item) {
                if ($value==$index ){$s="selected";}else{$s="";}
                $h.="<option value=\"".$index."\" $s>".$item."</option>";
            }
            break;
        }
        case "text":{ $h=$r[$value]; break;}
    }
    return $h;
}

function GetReportFrequency($type, $value)
{
    $r = array(0 => "manuell", 1 => "täglich", 7 => "wöchentlich", 14 => "alle 14 Tage", 21 => "alle 3 Wochen",
        30 => "monatlich", 90 => "Quartalsweise", 182 => "halbjährlich",
        365 => "jährlich", 730 => "alle 2 Jahre", 1095 => "alle 3 Jahre", 1460 => "alle 4 Jahre", 1825 => "alle 5 Jahre");
    $h = "";
    switch ($type) {
        case "select":
            {
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
        case "text":
            {
                $h = $r[$value];
                break;
            }
    }
    return $h;
}

function GetReportTypes($type, $value)
{
    $r = array(0 => "alles anzeigen", 1 => "Artikeldaten", 2 => "Equipmentdaten", 3 => "Vorgangsdaten");
    $h = "";
    switch ($type) {
        case "select":
            {
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
        case "text":
            {
                $h = $r[$value];
                break;
            }
    }
    return $h;
}

function GetReportFormat($type, $value)
{
    $r = array(0 => array("desc" => "Jahr-Monat", "val" => "%Y-%m"), 1 => array("desc" => "Jahr-Monat-Tag", "val" => "%Y-%m-%d"));
    $h = "";
    switch ($type) {
        case "select":
            {
                foreach ($r as $index => $item) {
                    if ($value == $index) {
                        $s = "selected";
                    } else {
                        $s = "";
                    }
                    $h .= "<option value=\"" . $index . "\" $s>" . $item['desc'] . "</option>";
                }
                break;
            }
        case "text":
            {
                $h = $r[$value]['desc'];
                break;
            }
        case "data":
            {
                $h = $r[$value]['val'];
                break;
            }
    }
    return $h;
}

function GetReportAspectRatio($type, $value)
{

    $r = array(
        "ct-square" => "1",
        "ct-minor-second" => "15:16",
        "ct-major-second" => "8:9",
        "ct-minor-third" => "5:6",
        "ct-major-third" => "4:5",
        "ct-perfect-fourth" => "3:4",
        "ct-perfect-fifth" => "2:3",
        "ct-minor-sixth" => "5:8",
        "ct-golden-section" => "1:1.618",
        "ct-major-sixth" => "3:5",
        "ct-minor-seventh" => "9:16",
        "ct-major-seventh" => "8:15",
        "ct-octave" => "1:2",
        "ct-major-tenth" => "2:5",
        "ct-major-eleventh" => "3:8",
        "ct-major-twelfth" => "1:3",
        "ct-double-octave" => "1:4",
    );

    $h = "";
    switch ($type) {
        case "select":
            {
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
        case "text":
            {
                $h = $r[$value];
                break;
            }
        case "data":
            {
                $h = $r[$value];
                break;
            }
    }
    return $h;
}

function GetReportSizeHeight($type, $value)
{

    $r = array(
        100 => "100px",
        200 => "200px",
        300 => "300px",
        400 => "400px",
        500 => "500px",
        600 => "600px",
        700 => "700px",
        800 => "800px",
        900 => "900px",
        1000 => "1000px",

    );

    $h = "";
    switch ($type) {
        case "select":
            {
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
        case "text":
            {
                $h = $r[$value];
                break;
            }
        case "data":
            {
                $h = $r[$value];
                break;
            }
    }
    return $h;
}




/**
 * Gets list of months between two dates
 * @param  int $start Unix timestamp
 * @param  int $end Unix timestamp
 * @return array
 */
function echoMonth($start, $end)
{
    $current = $start - (60 * 60 * 24);
    $ret = array();
    while ($current < $end) {
        $next = @date('Y-M-01', $current) . "+1 month";
        $current = @strtotime($next);
        $ret[] = date("Y-m", $current);
    }
    return ($ret);
}

function echoDate($start, $end)
{
    $current = $start - (60 * 60 * 24);
    $ret = array();
    while ($current < $end) {
        $next = @date('Y-M-d', $current) . "+1 day";
        $current = @strtotime($next);
        $ret[] = date("Y-m-d", $current);
    }
    return ($ret);
}

function GetReportName($type, $value)
{
    $h = "";
    switch ($type) {
        case "select":
            {
                include("./connection.php");
                $sql = "SELECT * FROM fisreport ORDER BY fisreport.desc ASC";
                $rst = $mysqli->query($sql);
                if (mysqli_affected_rows($mysqli) > 0) {
                    $row_rst = $rst->fetch_assoc();
                    do {

                        if ($value == $row_rst['rid']) {
                            $s = "selected";
                        } else {
                            $s = "";
                        }
                        $h .= "<option value=\"" . $row_rst['rid'] . "\" $s>" . utf8_decode($row_rst['desc']) . "</option>";

                    } while ($row_rst = $rst->fetch_assoc());
                }
                break;
            }
        case "select2":
            {
                include("./connection.php");
                $sql = "SELECT * FROM fisreport ORDER BY fisreport.desc ASC";
                $rst = $mysqli->query($sql);
                $ok = false;
                $h .= "<option value=\"\">nichts ausgewählt</option>";
                if (mysqli_affected_rows($mysqli) > 0) {
                    $row_rst = $rst->fetch_assoc();
                    do {

                        if ($value == $row_rst['rid']) {
                            $s = "selected";
                            $ok = true;
                        } else {
                            $s = "";
                        }
                        $h .= "<option value=\"" . $row_rst['rid'] . "\" $s>" . utf8_decode($row_rst['desc']) . "</option>";

                    } while ($row_rst = $rst->fetch_assoc());
                    if (!$ok) {
                        $s = "selected";
                    } else {
                        $s = "";
                    }
                    $h .= "<option value=\"" . $value . "\" $s>" . $value . "</option>";
                }
                break;
            }
        case "text":
            {
                include("./connection.php");
                $sql = "SELECT * FROM fisreport WHERE fisreport.rid='$value'  ";
                $rst = $mysqli->query($sql);
                if (mysqli_affected_rows($mysqli) > 0) {
                    $row_rst = $rst->fetch_assoc();
                    $h = utf8_decode($row_rst['desc']);
                }
                break;
            }

    }
    return $h;
}


function linear_regression($x, $y)
{

    $n = count($x);     // number of items in the array
    $x_sum = array_sum($x); // sum of all X values
    $y_sum = array_sum($y); // sum of all Y values

    $xx_sum = 0;
    $xy_sum = 0;

    for ($i = 0; $i < $n; $i++) {
        $xy_sum += ($x[$i] * $y[$i]);
        $xx_sum += ($x[$i] * $x[$i]);
    }

    // Slope
    $slope = (($n * $xy_sum) - ($x_sum * $y_sum)) / (($n * $xx_sum) - ($x_sum * $x_sum));

    // calculate intercept
    $intercept = ($y_sum - ($slope * $x_sum)) / $n;

    return array(
        'slope' => $slope,
        'intercept' => $intercept,
    );
}

function linear_regression_date($x, $y)
{

    $n = count($x);     // number of items in the array

    $x_sum = (count($x) * (count($x) + 1)) / 2;// sum of all X values

    $y_sum = array_sum($y); // sum of all Y values

    $xx_sum = 0;
    $xy_sum = 0;

    for ($i = 0; $i < $n; $i++) {
        $xy_sum += ($i * $y[$i]);
        $xx_sum += ($i * $i);
    }

    // Slope
    $slope = (($n * $xy_sum) - ($x_sum * $y_sum)) / (($n * $xx_sum) - ($x_sum * $x_sum));

    // calculate intercept
    $intercept = ($y_sum - ($slope * $x_sum)) / $n;

    return array(
        'slope' => $slope,
        'intercept' => $intercept,
    );
}

function GetCurancy($value, $currancy = "EUR")
{
    $v = sprintf("%1\$.2f", $value);
    return $v . " " . $currancy;
}