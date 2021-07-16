<?php

/**
 * Created by PhpStorm.
 * User: andreasveltjens
 * Date: 29.10.18
 * Time: 19:13
 */
class FisProcess
{

    public $entid=0;
    public $data=array();
    public $datavarianten=array();

    function __construct($i)
    {
        if ($i>0){
            $this->LoadData();
        }
    }

    function __toString(){
        return $this->entid;
    }
    function LoadData(){

    }

    function LoadDataVarianten(){

    }

    function ShowHtmlAsTable(){

    }
}