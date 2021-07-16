<?php

/**
 * Created by PhpStorm.
 * User: andreasveltjens
 * Date: 13.12.18
 * Time: 20:47
 */
class FisWpConfig
{
    public $FIS_WORKPLACE = array();
    function __construct()
    {
        $this->bks = FIS_BKS;
    }

    function __toString()
    {
    return "Config";
    }

    function GetWpDefaults(){
        include("../config/workplace.php");
        $this->FIS_WORKPLACE = $FIS_WORKPLACE;
        return $this->FIS_WORKPLACE;
    }
}