<?php

/**
 * Created by PhpStorm.
 * User: Andreas Veltjens lok
 * Date: 26.03.2017
 * Time: 10:33
 */

class IfisFid
{
    public $conf=array();

    function __construct($p)
    {
        $this->conf=json_decode($p,JSON_OBJECT_AS_ARRAY);
    }

    function GetArtikelNummer(){
        return $this->conf['Artikel'];
    }
    function GetArtikelPlug(){
        if ($this->conf['Plug']=="CEE230V"){
            $this->conf['Plug']="CEE";
        }
        return $this->conf['Plug'];
    }
    function GetArtikelColor(){
        return $this->conf['Color'];
    }
    function GetArtikelLogo(){
        if ($this->conf['Logo']=="yes"){
            return "L";
        }else{
            return "";
        }

    }
}


class IfisArtikelVersionConfig
{
public $conf=array();

    function __construct($p)
    {
        $this->conf=json_decode($p,JSON_OBJECT_AS_ARRAY);
    }

    function LoadDefaults(){
        $this->conf=
            array("Color"=>array(
                                "grey"  =>array("Plug"=>array("DE"=>"5555555", "CH"=>"2345678", "GB"=>"2345678", "AUS"=>"2345678", "DK"=>"2345678", "CEE230V"=>"2345678")),
                                "blue"  =>array("Plug"=>array("DE"=>"5555555", "CH"=>"2345678", "GB"=>"2345678", "AUS"=>"2345678", "DK"=>"2345678","CEE230V"=>"2345678")),
                                "red"   =>array("Plug"=>array("DE"=>"5555555", "CH"=>"2345678", "GB"=>"2345678", "AUS"=>"2345678", "DK"=>"2345678","CEE230V"=>"2345678")),
                                "green" =>array("Plug"=>array("DE"=>"5555555", "CH"=>"2345678", "GB"=>"2345678", "AUS"=>"2345678", "DK"=>"2345678","CEE230V"=>"2345678")),
                                "yellow"=>array("Plug"=>array("DE"=>"5555555", "CH"=>"2345678", "GB"=>"2345678", "AUS"=>"2345678", "DK"=>"2345678","CEE230V"=>"2345678")),

                            ),
            "Logo"=>array("no"=>"123456","yes"=>"555544")
        );

    }

    function LoadConfigProfil($p){
        $this->conf=json_decode($p);
    }

    function SaveConfigProfilasJson(){
        return json_encode($this->conf);
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
                         foreach ($x as $s=>$z){
                             $h.="--|- ".$s."<br>";
                                 foreach ($z as $z1=>$z2){$h.="-----|---".$z2." ".$z1."<br>";}
                             $h.="<br>";
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
       if ($s){$select="checked";}
       $h="<label>
            <img src=\"./picture/update2/$value.png\" width=\"50\" height=\"50\" align=\"absmiddle\">
            <input type=\"radio\" name=\"$name\" value=\"$value\" $select>$value ($k)</label>";
       return $h;
   }

    function FindArtikelNummer($Color,$Plug,$Logo){
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

    function GetJson(){
        return json_encode($this->conf);
    }
}