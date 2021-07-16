<?php

/**
 * Created by PhpStorm.
 * User: andreasveltjens
 * Date: 08.10.18
 * Time: 20:37
 *
 * CREATE TABLE  `db437050_8`.`link_wp_user` (
`lwu` BIGINT NOT NULL ,
`wpid` INT NOT NULL ,
`userid` INT NOT NULL ,
`t` INT NOT NULL ,
PRIMARY KEY (  `lwu` )
) ENGINE = MYISAM
 */
class FisUser
{
    public $bks=6101;
    public $userid=0;
    public $wp=0;
    public $allowed_user=array();
    public $users=array();
    public $data=array();
    public $allusers=array();
    public $history=array();
    public $sessiontime=0;
    public $lastactiontime=28800;

    public $lokz=false;
    public $limit=500;
    public $search="";
    public $no_free_search_value_allowed=0;

    function __construct($id=0)
    {
        if ($id>0){
            $this->userid=$id;
        }else{
            $this->userid=$_SESSION['active_user'];
        }
        if  ($this->userid==""){ $this->userid=0;}
        $this->wp=$_SESSION['wp'];
        $this->LoadData();
        $this->LoadHistory();
        $this->LoadSessionDuration();
    }

    function __toString(){
        if (is_null( $this->userid)) {
            return " ";
        }else{
            return $this->userid;
        }
    }



    function LoadData(){
        include ("./connection.php");
        $sql="SELECT * FROM `user` WHERE `user`.userid='$this->userid' LIMIT 0,1";
        $rst=$mysqli->query($sql);
        if (mysqli_affected_rows($mysqli)>0){
            $this->data=$rst->fetch_assoc();
            $this->LoadHistory();
        }else{
            $this->data=array();
        }
    }

    function LoadAllUserData(){
        include ("./connection.php");
        $s="";
        if (!$this->lokz){ $s="AND `user`.lokz=0";}

        $sql="SELECT * FROM `user`  
            WHERE (`user`.name like '%" . $this->search . "%'
                        OR `user`.email like '%" . $this->search . "%'
                        OR `user`.abteilung like '%" . $this->search . "%'
             ) AND `user`.werk='$this->bks'  $s 
          ORDER BY `user`.name  LIMIT 0, ".$this->limit." ";
        $rst=$mysqli->query($sql);


        if (mysqli_affected_rows($mysqli)>0){
            $row_rst = $rst->fetch_assoc();
            do {
                $this->data[] = $row_rst;
            } while ($row_rst = $rst->fetch_assoc());

        }else{
            $this->data=array();
        }
    }

    function Add($s="Neuer Mitarbeiter"){
        include("./connection.php");

        $updateSQL = sprintf("INSERT INTO `user` (`name`, werk ) 
                                            VALUES (%s, %s)",
            GetSQLValueString(utf8_decode($s),"text"),
            GetSQLValueString($this->bks,"int")

        );


        $rst = $mysqli->query($updateSQL);
        if ($mysqli->error) {

            AddSessionMessage("error", "Mitarbeiter  <code> ID " . $s . "</code> konnte nicht durchgeführt werden.", "Fehlermeldung DB-Error");
        } else {

            $txt = "Mitarbeiter  <code> ID " . $s . "</code> wurde angelegt.";
            AddSessionMessage("success", $txt, "Mitarbeiter gespeichert");
            $this->userid = mysqli_insert_id($mysqli);
            $w = new FisWorkflow($txt, "userid", $this->userid);
            $w->Add();
            $_SESSION['userid']=$this->userid;

        }


    }

    function Save(){
        include ("./connection.php");

        if (isset($_REQUEST['user_new'])){
            $this->Add($_POST['name']);
        }

        $updateSQL=sprintf("
            UPDATE `user` SET `user`.name=%s,`user`.password=%s,`user`.passwordactive=%s, `user`.shortname=%s, 
            `user`.email=%s,`user`.abteilung=%s,
            `user`.anschrift=%s, `user`.plz=%s, `user`.ort=%s, `user`.land=%s,
            `user`.telefon=%s, `user`.fax=%s, `user`.lokz=%s, `user`.userright=%s
           
            WHERE `user`.userid='".$this->userid."' ",
            GetSQLValueString(utf8_decode($_POST['name']),"text"),
            GetSQLValueString(utf8_decode($_POST['password']), "text"),
            GetSQLValueString($_POST['passwordactive'], "int"),

            GetSQLValueString(utf8_decode($_POST['shortname']),"text"),
            GetSQLValueString(utf8_decode($_POST['email']),"text"),
            GetSQLValueString(utf8_decode($_POST['abteilung']),"text"),
            GetSQLValueString(utf8_decode($_POST['anschrift']),"text"),
            GetSQLValueString(utf8_decode($_POST['plz']),"text"),
            GetSQLValueString(utf8_decode($_POST['ort']),"text"),
            GetSQLValueString(utf8_decode($_POST['land']),"text"),
            GetSQLValueString(utf8_decode($_POST['telefon']),"text"),
            GetSQLValueString(utf8_decode($_POST['fax']),"text"),

            GetSQLValueString($_POST['lokz'],"int"),
            GetSQLValueString($_POST['userright'],"int")


        );


        $rst = $mysqli->query($updateSQL);
        if ($mysqli->error) {
            
            AddSessionMessage("error","Mitarbeiter <code> " . $_POST['name'] ."</code> konnte nicht gespeichert werden.","Fehlermeldung");
        }else{
            $txt="Mitarbeiter <code> " . $_POST['name'] ."</code> wurde gespeichert.";
            AddSessionMessage("success", $txt, "Update Materialdaten");
            $w=new FisWorkflow($txt,"userid",$this->userid);
            $w->Add();

        }
        $d=new FisDocument("Dokumente zum Mitarbeiter","userid",$this->userid);
        $d->AddNewDocument();

    }

    function LoadSessionDuration(){
        include ("./connection.php");
        $sql="SELECT * FROM link_wp_user WHERE link_wp_user.userid='$this->userid' ORDER BY link_wp_user.t DESC LIMIT 0,1 ";
        $rst=$mysqli->query($sql);
        if (mysqli_affected_rows($mysqli)>0){
            $row_rst = $rst->fetch_assoc();
            $this->sessiontime=time()-$row_rst['t'];
            $this->lastactiontime=28800;
        }else{
            $this->sessiontime=0;
            $this->lastactiontime=0;
        }
    }

    function CheckPassword($pass)
    {
        include("./connection.php");
        $sql = sprintf("SELECT * FROM `user` WHERE `user`.userid='$this->userid' AND `user`.password=%s",
            GetSQLValueString(utf8_decode($pass), "text")
        );

        $rst = $mysqli->query($sql);
        if (mysqli_affected_rows($mysqli) > 0) {
            return true;
        } else {
            return false;
        }
    }

    function SetSearchString($s=""){
        $this->search=utf8_encode($s);

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



    function GetUsername(){
        if (strlen($this->data['name'])==0){
            $n="unbekannt";
        }else{
            $n=$this->data['name'];
        }
        return $n;
    }

    function GetTelefon(){
        if (strlen($this->data['telefon'])==0){
            $n="unbekannt";
        }else{
            $n=$this->data['telefon'];
        }
        return $n;
    }
    function GetFax(){
        if (strlen($this->data['fax'])==0){
            $n="unbekannt";
        }else{
            $n=$this->data['fax'];
        }
        return $n;
    }
    function GetEMail(){
        if (strlen($this->data['email'])==0){
            $n="unbekannt";
        }else{
            $n=$this->data['email'];
        }
        return $n;
    }

    function GetAnrede(){
        if ($this->data['anrede']=="") $this->data['anrede']="Herr";
        return $this->data['anrede'];
    }

    function GetUserRight(){
        if (strlen($this->data['name'])==0){
            $n=0;
        }else{
            $n=$this->data['userright'];
        }
        return $n;
    }


    function UpdateLogon(){
        include ("./connection.php");
        $updateSQL=sprintf("
            UPDATE link_wp_user SET link_wp_user.t=%s 
            WHERE link_wp_user.userid='$this->userid' AND link_wp_user.wpid='$this->wp'",
            time()
        );
        $rst = $mysqli->query($updateSQL);
        if ($mysqli->error) {
            
            AddSessionMessage("error","Updatefehler.","Fehlermeldung");
        }else{
            AddSessionMessage("success", "Mitarbeiter <code> " . $this->GetUsername() . "</code> wurde erfolgreich angemeldet.", "Update Logon");
        }
    }

    function LoadUsersByWp(){
        include ("./connection.php");
        $sql="SELECT `user`.userid, `user`.name, link_wp_user.t 
              FROM `user` LEFT JOIN link_wp_user ON link_wp_user.userid = `user`.userid 
              AND link_wp_user.wpid='$this->wp' WHERE `user`.lokz=0 AND `user`.werk='".$this->bks."'
              ORDER BY `user`.name";
        $rst=$mysqli->query($sql);
        if (mysqli_affected_rows($mysqli)>0){
            $row_rst = $rst->fetch_assoc();
            do{
                $this->users[]=$row_rst;
            } while ($row_rst = $rst->fetch_assoc());
        }else{
            $this->users=array();
        }
        return $this->users;
    }

    function GetHtmlSelectUsers(){
        $this->LoadUsersByWp();
        $h="";
        if (count($this->users)){
            foreach ($this->users as $i=>$u){
                if ($u['t']>0) {$s="selected"; }else{$s="";}
                $h.=sprintf("<option value='%s' %s> %s </option>",$u['userid'],$s,$u['name']);
            }
        }
        return $h;
    }


    function LoadAllowedUser(){
        include ("./connection.php");
        $sql="SELECT link_wp_user.userid FROM link_wp_user WHERE link_wp_user.wpid='$this->wp'  ORDER BY link_wp_user.t DESC";
        $rst=$mysqli->query($sql);
        if (mysqli_affected_rows($mysqli)>0){
            $this->allowed_user=array();
            $row_rst = $rst->fetch_assoc();
            do{
                $this->allowed_user[]=$row_rst;
            } while ($row_rst = $rst->fetch_assoc());
        }else{
            $this->allowed_user=array();
        }
        return $this->allowed_user;
    }

    function AddWpUser($wp,$userid){
        include ("./connection.php");
        $updateSQL = sprintf("
                INSERT INTO  link_wp_user (wpid,userid,t) VALUES (%s, %s,%s)",
            GetSQLValueString(utf8_decode($wp), "int"),
            GetSQLValueString(utf8_decode($userid), "int"),
            time()
        );
        $rst = $mysqli->query($updateSQL);
        if ($mysqli->error) {
            
            AddSessionMessage("error","Mitarbeiter wurde nicht angelegt, da ein Fehler aufgetreten ist.","Fehlermeldung");
        }else{
            AddSessionMessage("success", "Mitarbeiter <code> " . $mysqli->insert_id . "</code> wurde gespeichert.", "Neu anlegen");
        }
    }


    function DeleteWpAllUser($wp){
        include ("./connection.php");
        $updateSQL = sprintf("
                DELETE FROM link_wp_user WHERE link_wp_user.wpid='%s'",
            GetSQLValueString($wp, "int")
        );
        $rst = $mysqli->query($updateSQL);
        if ($mysqli->error) {
            
            AddSessionMessage("error","Mitarbeiter wurde nicht gelöscht, da ein Fehler aufgetreten ist.","Fehlermeldung");
        }
    }

    function CheckWpUser($userid = 1, $wp)
    {
        include("./connection.php");
        $updateSQL = sprintf("
                SELECT * FROM link_wp_user WHERE link_wp_user.userid=%s AND link_wp_user.wpid=%s",
            GetSQLValueString($userid, "int"),
            GetSQLValueString($wp, "int")
        );
        $rst = $mysqli->query($updateSQL);
        if ($mysqli->error) {
            AddSessionMessage("error", "Mitarbeiter konnten nicht gefunden werden, da ein Fehler aufgetreten ist.", "Fehlermeldung");
        }
        if (mysqli_affected_rows($mysqli) > 0) {

            return true;
        } else {
            return false;
        }
    }

    function LoadDataByEmail($email){
        include ("./connection.php");
        $sql="SELECT * FROM `user` WHERE `user`.email='".GetSQLValueString($email,"text")."' ";
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



    function ShowPropertyAsTable(){


        $h="<div class=\"table-responsive\">
                <table class=\"table table-striped\">
                  <tbody><tr>";
        $h.="</tr><tr>";
        $h.="<td>Bild</td>";
        $h.="<td>".$this->GetUserPicture()."</td>";
        $h.="</tr><tr>";
        $h.="<td>Mitarbeitername</td>";
        $h.="<td>".$this->data['name']."</td>";
        $h.="</tr><tr>";
        $h.="<td>E-Mail-Adresse</td>";
        $h.="<td>".$this->data['email']."</td>";
        $h.="</tr><tr>";
        $h.="<td>Kürzel</td>";
        $h.="<td>".$this->data['shortname']."</td>";
        $h.="</tr><tr>";
        $h.="<td>Abteilung</td>";
        $h.="<td>".$this->data['abteilung']."</td>";
        $h.="</tr><tr>";
        $h.="<td>Anschrift</td>";
        $h.="<td>".$this->data['anschrift']."</td>";
        $h.="</tr><tr>";
        $h.="<td>PLZ</td>";
        $h.="<td>".$this->data['plz']."</td>";
        $h.="</tr><tr>";
        $h.="<td>Ort</td>";
        $h.="<td>".$this->data['ort']."</td>";
        $h.="</tr><tr>";
        $h.="<td>Land</td>";
        $h.="<td>".$this->data['land']."</td>";
        $h.="</tr><tr>";
        $h.="<td>Telefon-Nr.</td>";
        $h.="<td>".$this->data['telefon']."</td>";
        $h.="</tr><tr>";
        $h.="<td>Fax-Nr.</td>";
        $h.="<td>".$this->data['fax']."</td>";
        $h.="</tr><tr>";
        $h.="<td>Berechtigung</td>";
        $h.="<td>".GetUserRights("text",$this->data['userright'])."</td>";
        $h.="</tr></tbody></table></div>";


        return $h;
    }
    function EditPropertyAsTable($edit=true){
        $d=new FisDocument("Mitarbeiter","userid",$this->userid);

        $h="<div class=\"table-responsive\">
                <table class=\"table table-striped\">
                  <tbody><tr>";
        $h.="<td>".asTextField("Name","name",$this->data['name'],7)."</td>";
        $h.="</tr><tr>";
        $h.="<td>".asTextField("E-Mail","email",$this->data['email'],7)."</td>";
        $h.="</tr><tr>";
        $h .= "<td>" . asSelectBox("Passwort aktivieren.", "passwordactive", GetYesNoList("select", $this->data['passwordactive']), 7) . "</td>";
        $h .= "</tr><tr>";
        $h .= "<td>" . asTextField("Passwort", "password", $this->data['password'], 7, "") . "</td>";
        $h .= "</tr><tr>";
        $h.="<td>".asTextField("Kürzel","shortname",$this->data['shortname'],7,"")."</td>";
        $h.="</tr><tr>";
        $h.="<td>".asTextField("Abteilung","abteilung",$this->data['abteilung'],7,"")."</td>";
        $h.="</tr><tr>";
        $h.="<td>".asTextField("Anschrift","anschrift",$this->data['anschrift'],7)."</td>";
        $h.="</tr><tr>";
        $h.="<td>".asTextField("PLZ","plz",$this->data['plz'],7)."</td>";
        $h.="</tr><tr>";
        $h.="<td>".asTextField("Ort","ort",$this->data['ort'],7)."</td>";
        $h.="</tr><tr>";
        $h.="<td>".asTextField("Land","land",$this->data['land'],7,"")."</td>";

        $h.="</tr><tr>";
        $h.="<td>".asTextField("Telefon-Nr.","telefon",$this->data['telefon'],7)."</td>";
        $h.="</tr><tr>";
        $h.="<td>".asTextField("Fax-Nr.","fax",$this->data['fax'],7,"")."</td>";
        $h.="</tr><tr>";
        $h.="<td>".asSelect2Box("Berechtigung.","userright",GetUserRights("select",$this->data['userright']),7,"")."</td>";
        $h.="</tr><tr>";
        $h.="<td>".asSelectBox("Löschkennzeichen.","lokz",GetYesNoList("select",$this->data['lokz']),7,"")."</td>";
        if ($edit) {
            $h .= "</tr><tr>";
            $h .= "<td>" . $d->ShowHtmlInputField() . "</td>";
        }
        $h.="</tr></tbody></table></div>";
        return $h;
    }
    function ShowHtmlAllAsTable($status="1",$limit=50,$search="",$no_free_search_value_allowed=0){
        $this->data=array();
        $this->SetSearchString($search);
        $this->SetLimit($limit);
        $this->SetNoFreeValueAllowed($no_free_search_value_allowed);
        $this->LoadAllUserData();

        $h=asModal(1000,"<div id='preview'></div>","",60,"Vorschau");
        $h.= "<h4 class=\"header-title m-t-0 m-b-30\">Mitarbeiterliste  Suchbegriff:(".$this->GetSearchString().") </h4>";

        if (count($this->data) > 0) {

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
                         <th>Name</th>
                         <th>Abteilung</th>
                         <th>Email</th>
                         <th>Berechtigung</th>
                         <th>Anschrift</th>
                         <th>PLZ</th>
                         <th>Ort</th>
                         <th>Land</th>
                          <th>lokz</th>
                         <th>Aktion</th>
                        </tr>
                    </thead>
                    <tbody>
                                       ";

            foreach ($this->data as $d => $i) {
                if ($i['userid']>0) {
                    $e= new FisUser($i['userid']);
                    $link = "<a class='btn btn-custom btn-sm' href=\"index.php?sess=" . $_SESSION['sess'] . "&wp=" . $_SESSION['wp'] . "&action=36&userid=" . $i['userid'] . "&equip=&materialgroup=&material=&version=&fid=\" onclick='refresh_site()'> Bearbeiten </a>";


                    $h .= "<tr>
                        <td><a href=\"#custom-width-modal1000\" data-toggle=\"modal\" data-target=\"#custom-width-modal1000\" 
                           onclick=\"ShowPreview('".$e->GetUserPictureLink()."');\" >           
                        ".$e->GetUserPicture("thumb-md")."</a></td>
                        <td>" . ($i['name']) . "</td>
                        <td>" . ($i['abteilung']) . "</td>
                        <td>" . ($i['email']) . "</td>
                     
                        <td>" .GetUserRights("text",$i['userright']) . "</td>
                        <td>" . ($i['anschrift']) . "</td>
                        <td>" . ($i['plz']) . "</td>
                        <td>" . ($i['ort']) . "</td>
                        <td>" . ($i['land']) . "</td>
                        <td>" . GetYesNoList("text",$i['lokz']) . "</td>

                       ";
                    $h .= "  <td>" . "$link" . "</td>";
                    $h .= "</tr>";
                }
            }
            $h.="</tbody></table>";
        }else {
            $h .= "keine Mitarbeiter gefunden.";
        }
        return $h;
    }


    function LoadHistory($wstate=""){
        include ("./connection.php");
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

    function ShowUserLogonDialog()
    {
        $h = "";
        if ($this->data['passwordactive'] == 1) {
            $txt = "<form method=\"post\" id=\"logon_user" . $this->userid . "\">";
            $txt .= "<div class='col-lg-8'>";
            $txt .= asTextField("Passwort eingeben", "password" . $this->userid);
            $txt .= "<input type=\"hidden\" name=\"active_userid\" value=\"" . $this->userid . "\">";
            $txt .= "<input type=\"hidden\" name=\"active_userid_action" . $this->userid . "\" id=\"active_userid_action" . $this->userid . "\"  value=\"\">";
            $txt .= "</div>";
            $txt .= "<div class='col-lg-4'>";
            $txt .= "<button id=\"logon_user_login\" name=\"logon_user_login\" type=\"submit\" class=\"btn btn-primary waves-effect waves-light pull-right\" >Anmelden</button>";
            $txt .= "</div>";
            $txt .= "</form>";
            $h = asModal("-user" . $this->userid, $txt, "", 55, "Kennworteingabe für " . $this->GetUsername() . " ist erforderlich");
        }
        return $h;
    }
    function ShowUserProperties(){
        if ($this->data['passwordactive'] == 0) {
            $h = "<li class=\"list-group-item\">
                <a href=\"./" . $_SESSION['wp'] . ".php?sess=" . $_SESSION['sess'] . "&wp=" . $_SESSION['wp'] . "&active_userid=" . $this->userid . "\" class=\"user-list-item\">
                    <div class=\"avatar\">";
            $h .= $this->GetUserPicture();
            $h .= " </div>
                    <div class=\"user-desc\">
                        <span class=\"name\"><h4>" . $this->GetUsername() . "</h4></span>
                
                    </div>
                </a>
            </li>";
        } else {

            $h = "<li class=\"list-group-item\">";

            $h .= "<a href=\"#custom-width-modal-user" . $this->userid . "\" data-toggle=\"modal\" data-target=\"#custom-width-modal-user" . $this->userid . "\"
                    onclick=\"
                    $('#custom-width-modal2').modal('hide'); 
                    $('#password" . $this->userid . "').val(''); 
                    $('#custom-width-modal-user" . $this->userid . "').on('shown.bs.modal', function() {
                      $('#password" . $this->userid . "').focus();
                    });
\" class=\"user-list-item\"> 
                  <div class=\"avatar\">";
            $h .= $this->GetUserPicture();
            $h .= " </div>
                    <div class=\"user-desc\">
                        <span class=\"name\"><h4>" . $this->GetUsername() . "</h4></span>
                
                    </div>
                </a>
            </li>";

        }




        return $h;
    }

    function GetUserPictureLink(){
        $d=new FisDocument($this->userid,"userid",$this->userid);
        $l=$d->GetLatestPictureLink();
        if (strlen($l)<4) {
            $l = "../picture/no-user.png";
        }
        return $l;
    }

    function GetUserPicture($size=""){
        $h="<img src=\"".$this->GetUserPictureLink()."\" alt=\"".$this->GetUsername()."\" class=\"img-circle user-img $size\">";
        return $h;
    }
    function GetUserIcon($li=true){
        $h="";
        if ($li) $h=" <li class=\"dropdown user-box\">
            <a href=\"\" class=\"dropdown-toggle waves-effect waves-light profile \" data-toggle=\"dropdown\" aria-expanded=\"true\">";

        $h.=$this->GetUserPicture();


        return $h;
    }
}