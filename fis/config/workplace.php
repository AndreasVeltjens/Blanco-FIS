<?php
/**
 * Created by PhpStorm.
 * User: andreasveltjens
 * Date: 2019-02-14
 * Time: 09:59
 */

$FIS_WORKPLACE = array(
    "611401" => array(
        "name" => "Montage BLT",
        "group" => "Montage",
        "pplan"=>1,
        "cost" => "61140",
        "layout" => 3,
        "functions" => array(34,37),
        "mainfunctions" => array(42,44,63),
        "autologoff" => 480,
        "autologout"=>28800,
        "autoinactive"=>240,
        "degreeoforder" => 1,
        "defaultfidstatus" => 2,
        "fids" => array(
            1 => array(
                "fid" => 1320,
                "favoriten" => array(1,2, 4, 39,63),
                "functions" => array(1, 2 ,4,5, 6),
                "pplan" => 1,
                "pduration" => 36000,
                "www" => 1,
                "degreeoforder" => 1,
                "icon" => "fa fa-signing fa-2x"
            ),

        ),
        "materialgroups" => array(
            1 => array("name" => "BLT K", "kontierung" => "Fertigware", "alias" => "BLT unbeheizt"),
            3 => array("name" => "BLT KB", "kontierung" => "Fertigware", "alias" => "BLT 320 KBx beheizt"),
            4 => array("name" => "BLT KB UH", "kontierung" => "Fertigware", "alias" => "BLT KBUHx beheizt"),
            5 => array("name" => "BLT Sonder", "kontierung" => "Fertigware", "alias" => "BLT Sonder"),
            6 => array("name" => "BLT Rolli", "kontierung" => "Fertigware", "alias" => "BLT Rolli & Kuexhlplatten"),
            7 => array("name" => "BAS", "kontierung" => "Fertigware", "alias" => "BAS"),
            8 => array("name" => "Industrieteile", "kontierung" => "Fertigware", "alias" => "Industrieteile"),
            9 => array("name" => "Sonstige", "kontierung" => "Fertigware", "alias" => "Sonstige"),

        ),
        "printer" => array(
            "typenschild" => array("link" => "print/typenschild/drucker1", "top" => 4.5, "left" => 0, "layout"=>"Etikett 100x34"),
            "verpackung" => array("link" => "print/verpackung/drucker2", "top" => 1, "left" => 2, "layout"=>"Etikett 100x50"),
            "dokuset" => array("link" => "print/dokuset/drucker5", "top" => 0, "left" => 0, "layout"=>"A4"),
            "einbau" => array("link" => "print/einbau/drucker6", "top" => 0, "left" => 0, "layout"=>"Etikett 100x50"),
            "wareneingang" => array("link" => "print/wareneingang/drucker7", "top" => 0, "left" => 0, "layout"=>"Etikett 100x50"),
            "neutraletikett" => array("link" => "print/verpackung/drucker2", "top" => 0, "left" => 0, "layout"=>"Etikett 100x50"),

        ),


    ),
    "611402" => array(
        "name" => "Montage Umluftheizung",
        "group" => "Montage",
        "pplan"=>1,
        "cost" => "61140",
        "layout" => 3,
        "functions" => array(34,37),
        "mainfunctions" => array(42,44),
        "links"=>array(),
        "autologoff" => 480,
        "autologout"=>28800,
        "autoinactive"=>240,
        "defaultfidstatus" => 2,
        "fids" => array(
            1 => array(
                "fid" => 1321,
                "favoriten" => array(1, 2, 39,44),
                "functions" => array(1, 2 ,4,5, 6),
                "pplan" => 1,
                "pduration" => 36000,
                "www" => 1,
                "degreeoforder" => 1,
                "icon" => "fa fa-signing fa-2x"
            ),
        ),

        "materialgroups" => array(
            1 => array("name" => "BLT Heizmodul KBUH","kontierung" => "Halbteile", "alias" => "KBUH ohne Temperatureinstellung"),
            2 => array("name" => "BLT Heizmodul KBRUH","kontierung" => "Halbteile", "alias" => "KBRUH mit Temperatureinstellung"),

        ),
        "printer" => array(
            "einbau" => array("link" => "print/einbau/drucker6", "top" => 0, "left" => 0, "layout"=>"Etikett 100x50"),
            "dokuset" => array("link" => "print/dokuset/drucker5", "top" => 0, "left" => 0, "layout"=>"A4"),
            "neutraletikett" => array("link" => "print/einbau/drucker6", "top" => 0, "left" => 0, "layout" => "Etikett 100x50"),

        ),


    ),
    "611403" => array(
        "name" => "Montage BLT320 KBx Heizsystem",
        "group" => "Montage",
        "pplan"=>1,
        "cost" => "61140",
        "layout" => 3,
        "functions" => array(),
        "mainfunctions" => array(44),
        "links"=>array(),
        "autologoff" => 480,
        "autologout"=>28800,
        "autoinactive"=>240,
        "defaultfidstatus" => 2,
        "fids" => array(
            1 => array(
                "fid" => 1322,
                "favoriten" => array(1, 2, 39,44),
                "functions" => array(1, 2 ,4,5, 6),
                "pplan" => 1,
                "pduration" => 36000,
                "www" => 1,
                "degreeoforder" => 1,
                "icon" => "fa fa fa-signing fa-2x"
            ),
        ),

        "materialgroups" => array(
            1 => array("name" => "Heizsystem KB", "kontierung" => "Halbteile", "alias" => "BLT 320 KB"),
            2 => array("name" => "Heizsystem KBR", "kontierung" => "Halbteile","alias" => "BLT 320 KBR"),
        ),
        "printer" => array(
            "einbau" => array("link" => "print/einbau/drucker6", "top" => 0, "left" => 0, "layout"=>"Etikett 100x50"),
            "dokuset" => array("link" => "print/dokuset/drucker5", "top" => 0, "left" => 0, "layout"=>"A4"),
            "neutraletikett" => array("link" => "print/einbau/drucker6", "top" => 0, "left" => 0, "layout" => "Etikett 100x50"),

        ),

    ),
    "611404" => array(
        "name" => "Montage Ersatzteile",
        "group" => "Montage",
        "count" => 5,
        "pplan"=>1,
        "cost" => "61140",
        "layout" => 3,
        "functions" => array(),
        "mainfunctions" => array(44),
        "links"=>array(),
        "autologoff" => 480,
        "autologout"=>500,
        "autoinactive"=>240,
        "fids" => array(
            1 => array(
                "fid" => 1323,
                "favoriten" => array(1, 2,39,44),
                "functions" => array(1, 2 ,4,5, 6),
                "pplan" => 1,
                "pduration" => 36000,
                "www" => 1,
                "degreeoforder" => 1,
                "icon" => "fa fa fa-signing fa-2x"
            ),
        ),

        "defaultfidstatus" => 2,
        "materialgroups" => array(
            1 => array("name" => "Beschlagteile",  "kontierung" => "Ersatzteile", "alias" => "Beschlagteile"),
            2 => array("name" => "Deckel",  "kontierung" => "Ersatzteile", "alias" => "Deckel"),
            3 => array("name" => "Grundk", "kontierung" => "Ersatzteile", "alias" => "Grundkoexrper"),
            4 => array("name" => "Elektro",  "kontierung" => "Ersatzteile", "alias" => "Elektroartikel"),

            6 => array("name" => "BLT Rolli", "kontierung" => "Fertigware", "alias" => "BLT Rolli & Kuexhlplatten"),
            7 => array("name" => "BAS", "kontierung" => "Fertigware", "alias" => "BAS"),
            8 => array("name" => "Industrieteile", "kontierung" => "Fertigware", "alias" => "Industrieteile"),
            9 => array("name" => "Sonstige", "kontierung" => "Fertigware", "alias" => "Sonstige"),

           
        ),
        "printer" => array(
            "typenschild" => array("link" => "print/typenschild/drucker3", "top" => 4.5, "left" => 0, "layout"=>"Etikett 100x34"),
            "verpackung" => array("link" => "print/verpackung/drucker4", "top" => 1, "left" => 2, "layout"=>"Etikett 100x50"),
            "dokuset" => array("link" => "print/dokuset/drucker5", "top" => 0, "left" => 0, "layout"=>"A4"),
            "einbau" => array("link" => "print/verpackung/drucker4", "top" => 0, "left" => 0, "layout" => "Etikett 100x50"),
            "wareneingang" => array("link" => "print/wareneingang/drucker7", "top" => 0, "left" => 0, "layout"=>"Etikett 100x50"),
            "neutraletikett" => array("link" => "print/verpackung/drucker4", "top" => 0, "left" => 0, "layout"=>"Etikett 100x50"),

        ),

    ),
    "611406" => array(
        "name" => "Montage WSE Heizmodul",
        "group" => "Montage",
        "pplan"=>1,
        "cost" => "61140",
        "layout" => 3,
        "functions" => array(34,37,42,44),
        "mainfunctions" => array(44),
        "links"=>array(),
        "autologoff" => 480,
        "autologout"=>28800,
        "autoinactive"=>240,
        "defaultfidstatus" => 2,
        "fids" => array(
            1 => array(
                "fid" => 1321,
                "favoriten" => array(1, 2, 39,44),
                "functions" => array(1, 2 ,4,5, 6),
                "pplan" => 1,
                "pduration" => 36000,
                "www" => 1,
                "degreeoforder" => 1,
                "icon" => "fa fa-signing fa-2x"
            ),
        ),

        "materialgroups" => array(
            3 => array("name" => "MD Heizmodul","kontierung" => "Halbteile", "alias" => "MD Heizmodul"),
        ),
        "printer" => array(

            "typenschild" => array("link" => "print/typenschild/drucker3", "top" => 4.5, "left" => 0, "layout"=>"Etikett 100x34"),
            "verpackung" => array("link" => "print/verpackung/drucker4", "top" => 1, "left" => 2, "layout"=>"Etikett 100x50"),
            "dokuset" => array("link" => "print/dokuset/drucker5", "top" => 0, "left" => 0, "layout"=>"A4"),
            "einbau" => array("link" => "print/verpackung/drucker4", "top" => 0, "left" => 0, "layout" => "Etikett 100x50"),
            "wareneingang" => array("link" => "print/wareneingang/drucker7", "top" => 0, "left" => 0, "layout"=>"Etikett 100x50"),
            "neutraletikett" => array("link" => "print/verpackung/drucker4", "top" => 0, "left" => 0, "layout"=>"Etikett 100x50"),

        ),


    ),
    "611405" => array(
        "name" => "Montage BLT Temp-Test Fertigware",
        "group" => "Pruexfung",

        "cost" => "61140",
        "layout" => 5,
        "functions" => array(),
        "mainfunctions" => array(),
        "links"=>array(),
        "autologoff" => 120,
        "autologout" => 28800,
        "autoinactive"=>120,
        "fids" => array(
            1 => array(
                "fid" => 1328,
                "favoriten" => array(2),
                "functions" => array(41,1, 2 ,5, 6, 31),
                "pplan" => 1,
                "pduration" => 31536000,
                "www" => 1,
                "degreeoforder" => 1,
                "icon" => "fa fa-area-chart fa-2x"
            ),
            2 => array(
                "fid" => 1329,
                "favoriten" => array(2),
                "functions" => array(41,1, 2 ,5, 6,31),
                "pplan" => 1,
                "pduration" => 31536000,
                "www" => 1,
                "degreeoforder" => 1,
                "icon" => "fa fa-area-chart fa-2x"
            ),

            /**
             *
             *
            3 => array(
            "fid" => 1333,
            "favoriten" => array(2),
            "functions" => array(41,1, 2 ,5, 6,31),
            "pplan" => 1,
            "pduration" => 31536000,
            "www" => 1,
            "degreeoforder" => 1,
            "icon" => "fa fa-area-chart fa-2x"
            ),

             */


            4 => array(
                "fid" => 1330,
                "favoriten" => array(2),
                "functions" => array(41,1, 2 ,5, 6,31),
                "pplan" => 1,
                "pduration" => 31536000,
                "www" => 1,
                "degreeoforder" => 1,
                "icon" => "fa fa-area-chart fa-2x"
            ),

        ),

    ),


    "611101" => array(
        "name" => "Thermoformen T8",
        "group" => "Thermoformen",
        "cost" => "61110",
        "layout" => 2,
        "functions" => array(),
        "mainfunctions" => array(),
        "links"=>array(),
        "autologoff" => 480,
        "autologout" => 28800,
        "autoinactive"=>60,
        "fids" => array(
            1 => array(
                "fid" => 852,
                "name" => "GEISS DU T8",
                "favoriten" => array(1, 2),
                "functions" => array(1, 2 ,5, 6,8,31),
                "pplan" => 3,
                "pduration" => 36000,
                "www" => 1,
                "degreeoforder" => 1,
                "icon" => "fa fa-ils fa-1x",
                "col" => 3,
            ),
            2 => array(
                "fid" => 1318,
                "name" => "Temperaturverlauf",
                "favoriten" => array(1, 2),
                "functions" => array(1, 2 ,5, 6,31),
                "pplan" => 1,
                "pduration" => 31536000,
                "www" => 1,
                "degreeoforder" => 1,
                "icon" => "fa fa-area-chart",
                "col" => 3,
                "dataset"=>1,
                "interval"=>24,

            ),
            3 => array(
                "fid" => 1324,
                "name" => "Werkzeugwechsel",
                "favoriten" => array(9),
                "functions" => array(9, 8, 5,31),
                "pplan" => 4,
                "pduration" => 315360000,
                "www" => 1,
                "degreeoforder" => 1,
                "icon" => "fa fa-indent fa-1x",
                "col" => 9,
            ),

            /**
            4 => array(
            "fid" => 852,
            "name" => "Ordnungsgrad",
            "favoriten" => array(7),
            "functions" => array(7, 8, 5,31),
            "pplan" => 3,
            "pduration" => 36000,
            "www" => 1,
            "degreeoforder" => 1,
            "icon" => "fa fa-sliders fa-2x",
            "col" => 3,
            ),
             */

        ),

    ),
    "611102" => array(
        "name" => "Thermoformen T6 + T5",
        "group" => "Thermoformen",
        "cost" => "61120",
        "layout" => 1,
        "functions" => array(),
        "mainfunctions" => array(),
        "favoriten" => array(7),
        "links"=>array(),
        "autologoff" => 60,
        "fids" => array(
            1 => array(
                "fid" => 9,
                "favoriten" => array(1, 2),
                "functions" => array(1, 2 ,5, 6,31),
                "pplan" => 3,
                "pduration" => 36000,
                "www" => 3,
                "degreeoforder" => 1,
                "icon" => "fa fa-ils fa-2x"
            ),
            2 => array(
                "fid" => 1271,
                "favoriten" => array(1, 2),
                "functions" => array(1, 2 ,5, 6,31),
                "pplan" => 6,
                "pduration" => 36000,
                "www" => 0,
                "degreeoforder" => 1,
                "icon" => "fa fa-ioxhost fa-2x"
            ),
            3 => array(
                "fid" => 22,
                "favoriten" => array(1, 2),
                "functions" => array(1, 2 ,5, 6,31),
                "pplan" => 6,
                "pduration" => 36000,
                "www" => 0,
                "degreeoforder" => 1,
                "icon" => "fa fa-ioxhost fa-2x"
            ),
            4 => array(
                "fid" => 5,
                "favoriten" => array(1, 2),
                "functions" => array(1, 2 ,5, 6,31),
                "pplan" => 3,
                "pduration" => 36000,
                "www" => 2,
                "degreeoforder" => 1,
                "icon" => "fa fa-ils fa-2x"
            ),
        ),

    ),

    "0" => array(
        "name" => "Verwaltung",
        "group" => "Verwaltung",
        "cost" => "71110",
        "layout" => 1,

        "autologoff" => 28800,
        "autologout"=> 28800,
        "autoinactive"=>28800,
        "functions" => array(22,23,27,39,0,30,45,47,48,65,0,66,67,0,34,37,42),
        "mainfunctions" => array(35, 49, 0, 41, 42, 40, 44, 63, 0, 21, 100, 0, 68, 70, 0, 46, 71),
        "favoriten" => array(46),
        "links"=>array(
            1=>array(
                "href"=>"http://www.vekutech.de",
                "alias"=>"Sharepoint 1",
                "target"=>"_blank",
                "btn"=>"btn-custom",
            ),
            2=>array(
                "href"=>"http://www.vekutech.de",
                "alias"=>"Sharepoint 2",
                "target"=>"_blank",
                "btn"=>"btn-custom",
            ),
            3=>array(
                "href"=>"http://cde9aw01/qsdatenbank/",
                "alias"=>"FIS 9 anzeigen",
                "target"=>"_self",
                "btn"=>"btn-custom",
            ),
        ),
        "fids" => array(
        ),
        "printer" => array(
            "typenschild" => array("link" => "print/typenschild/drucker1", "top" => 4.5, "left" => 0, "layout"=>"Etikett 100x34"),
            "verpackung" => array("link" => "print/verpackung/drucker2", "top" => 0, "left" => 0, "layout"=>"Etikett 100x50"),
            "dokuset" => array("link" => "print/dokuset/drucker5", "top" => 0, "left" => 0, "layout"=>"A4"),
            "einbau" => array("link" => "print/einbau/drucker6", "top" => 0, "left" => 0, "layout"=>"Etikett 100x50"),
            "wareneingang" => array("link" => "print/wareneingang/drucker7", "top" => 0, "left" => 0, "layout"=>"Etikett 100x50"),
            "fehlermeldung" => array("link" => "print/wareneingang/drucker8", "top" => 0, "left" => 0, "layout"=>"Etikett 100x50"),
            "gls" => array("link" => "print/wareneingang/drucker9", "top" => 0, "left" => 0, "layout"=>"A5"),
            "neutraletikett" => array("link" => "print/verpackung/drucker2", "top" => 0, "left" => 0, "layout"=>"Etikett 100x50"),
        ),
        "address"=>array(
            "firma"=>"BLANCO Professional Kunststofftechnik GmbH",
            "strasse" => "Werkstaexttenstrasse 31",
            "plz"=>"04319",
            "ort"=>"Leipzig",
            "land"=>"Deutschland",
            "signatur" => "BLANCO Professional Kunststofftechnik GmbH - Werkstaexttenstr. 31 - 04319 Leipzig",
            "HRB" => "Geschaexftsfuexhrer: Michael Huber   Amtsgericht Leipzig HRB 3307"
        ),
        "fehlermeldungen"=>array(
            9014855=>array(
                "gwl"=>0,
                "name"=>"Kostenstelle Sonstige",
            ),
            9014856=>array(
                "gwl"=>1,
                "name"=>"Kostenstelle Sonstige",
            ),

            9014851=>array(
                "gwl"=>0,
                "name"=>"Kostenstelle Rep 320 KBx",
            ),
            9014852=>array(
                "gwl"=>1,
                "name"=>"Kostenstelle GWL 320 KBx",
            ),
            9014853=>array(
                "gwl"=>0,
                "name"=>"Kostenstelle Rep 420/620 KBUHx",
            ),
            9014854=>array(
                "gwl"=>1,
                "name"=>"Kostenstelle GWL 420/620 KBUHx",
            ),

        ),

    ),

    "611407" => array(
        "name" => "Reparatur Aufnahme",
        "group" => "Reparatur",
        "cost" => "61140",
        "layout" => 6,
        "functions" => array(60,23,22,27),
        "mainfunctions" => array(39, 42, 0, 68, 70),
        "favoriten" => array(),
        "links"=>array(),
        "autologoff" => 28800,
        "autologout"=> 28800,
        "autoinactive"=>28800,
        "fids" => array(
            1 => array(
                "fid" => 1326,
                "favoriten" => array(60,34,22,27),
                "functions" => array(34,22,27,1, 2, 3, 8, 9, 4, 5, 6, 7,31),
                "pplan" => 1,
                "pduration" => 31536000,
                "www" => 1,
                "degreeoforder" => 1,
                "icon" => "fa fa-industry fa-2x"
            ),
        ),
        "printer" => array(
            "typenschild" => array("link" => "print/typenschild/drucker10", "top" => 4.5, "left" => 0, "layout"=>"Etikett 100x34"),
            "verpackung" => array("link" => "print/verpackung/drucker8", "top" => 0, "left" => 0, "layout"=>"Etikett 100x50"),
            "fehlermeldung" => array("link" => "print/wareneingang/drucker8", "top" => 0, "left" => 0, "layout"=>"Etikett 100x50"),
            "gls" => array("link" => "print/wareneingang/drucker9", "top" => 0, "left" => 0, "layout"=>"A5"),
        ),
        "address"=>array(
            "firma"=>"BLANCO Professional Kunststofftechnik GmbH",
            "strasse" => "Werkstaexttenstrasse 31",
            "plz"=>"04319",
            "ort"=>"Leipzig",
            "land"=>"Deutschland",
            "signatur" => "BLANCO Professional Kunststofftechnik GmbH - Werkstaexttenstr. 31 - 04319 Leipzig",
            "HRB" => "Geschaexftsfuexhrer: Michael Huber   Amtsgericht Leipzig HRB 3307"
        ),
    ),
    "6114011" => array(
        "name" => "Reparatur Temp-Test - Aufnahme",
        "group" => "Reparatur",
        "cost" => "61140",
        "layout" => 7,

        "functions" => array(),
        "mainfunctions" => array(),
        "favoriten" => array(),
        "links"=>array(),
        "autologoff" => 120,
        "autologout"=> 28800,
        "autoinactive"=>120,
        "fids" => array(
            1 => array(
                "fid" => 1331,
                "favoriten" => array(2),
                "functions" => array(41,1, 2 ,5, 6, 31),
                "pplan" => 1,
                "pduration" => 31536000,
                "www" => 1,
                "degreeoforder" => 1,
                "icon" => "fa fa-area-chart fa-2x"
            ),
            2 => array(
                "fid" => 1332,
                "favoriten" => array(2),
                "functions" => array(41,1, 2 ,5, 6,31),
                "pplan" => 1,
                "pduration" => 31536000,
                "www" => 1,
                "degreeoforder" => 1,
                "icon" => "fa fa-area-chart fa-2x"
            ),


        ),
        "printer" => array(
        ),
        "address"=>array(
            "firma"=>"BLANCO Professional Kunststofftechnik GmbH",
            "strasse" => "Werkstaexttenstrasse 31",
            "plz"=>"04319",
            "ort"=>"Leipzig",
            "land"=>"Deutschland",
            "signatur" => "BLANCO Professional Kunststofftechnik GmbH - Werkstaexttenstr. 31 - 04319 Leipzig",
            "HRB" => "Geschaexftsfuexhrer: Michael Huber   Amtsgericht Leipzig HRB 3307"
        ),

    ),
    "611408" => array(
        "name" => "Reparatur VDE",
        "group" => "Reparatur",
        "cost" => "61140",
        "layout" => 6,
        "functions" => array(61,23,22,27,39,34,42),
        "mainfunctions" => array(),
        "favoriten" => array(),
        "links"=>array(),
        "autologoff" => 28800,
        "autologout"=> 28800,
        "autoinactive"=>28800,
        "fids" => array(
            1 => array(
                "fid" => 1327,
                "favoriten" => array(61,34,22,27),
                "functions" => array(34,22,27,1, 2, 3, 8, 9, 4, 5, 6, 7, 31),
                "pplan" => 1,
                "pduration" => 31536000,
                "www" => 1,
                "degreeoforder" => 1,
                "icon" => "fa fa-industry fa-2x"
            ),
        ),
        "printer" => array(
            "typenschild" => array("link" => "print/typenschild/drucker10", "top" => 4.5, "left" => 0, "layout"=>"Etikett 100x34"),
            "verpackung" => array("link" => "print/verpackung/drucker8", "top" => 0, "left" => 0, "layout"=>"Etikett 100x50"),
            "fehlermeldung" => array("link" => "print/wareneingang/drucker8", "top" => 0, "left" => 0, "layout"=>"Etikett 100x50"),
            "gls" => array("link" => "print/wareneingang/drucker9", "top" => 0, "left" => 0, "layout"=>"A5"),
        ),
        "address"=>array(
            "firma"=>"BLANCO Professional Kunststofftechnik GmbH",
            "strasse" => "Werkstaexttenstrasse 31",
            "plz"=>"04319",
            "ort"=>"Leipzig",
            "land"=>"Deutschland",
            "signatur" => "BLANCO Professional Kunststofftechnik GmbH - Werkstaexttenstr. 31 - 04319 Leipzig",
            "HRB" => "Geschaexftsfuexhrer: Michael Huber   Amtsgericht Leipzig HRB 3307"
        ),
    ),
    "611412" => array(
        "name" => "Reparatur Versand",
        "group" => "Reparatur",
        "cost" => "61140",
        "layout" => 6,
        "functions" => array(62,23,22,27,39,34,42),
        "mainfunctions" => array(),
        "favoriten" => array(),
        "links"=>array(),
        "autologoff" => 28800,
        "autologout"=> 28800,
        "autoinactive"=>28800,
        "fids" => array(
            1 => array(
                "fid" => 1327,
                "favoriten" => array(62,34,22,27),
                "functions" => array(34,22,27,1, 2, 3, 8, 9, 4, 5, 6, 7, 31),
                "pplan" => 1,
                "pduration" => 31536000,
                "www" => 1,
                "degreeoforder" => 1,
                "icon" => "fa fa-industry fa-2x"
            ),
        ),
        "printer" => array(
            "typenschild" => array("link" => "print/typenschild/drucker10", "top" => 4.5, "left" => 0, "layout"=>"Etikett 100x34"),
            "verpackung" => array("link" => "print/verpackung/drucker8", "top" => 0, "left" => 0, "layout"=>"Etikett 100x50"),
            "fehlermeldung" => array("link" => "print/wareneingang/drucker8", "top" => 0, "left" => 0, "layout"=>"Etikett 100x50"),
            "gls" => array("link" => "print/wareneingang/drucker9", "top" => 0, "left" => 0, "layout"=>"A5"),
        ),
        "address"=>array(
            "firma"=>"BLANCO Professional Kunststofftechnik GmbH",
            "strasse" => "Werkstaexttenstrasse 31",
            "plz"=>"04319",
            "ort"=>"Leipzig",
            "land"=>"Deutschland",
            "signatur" => "BLANCO Professional Kunststofftechnik GmbH - Werkstaexttenstr. 31 - 04319 Leipzig",
            "HRB" => "Geschaexftsfuexhrer: Michael Huber   Amtsgericht Leipzig HRB 3307"
        ),
    ),

    "611409" => array(
        "name" => "Reparatur Temp-Test Ebene 2",
        "group" => "Reparatur",
        "cost" => "61140",
        "layout" => 1,

        "functions" => array(),
        "mainfunctions" => array(),
        "favoriten" => array(),
        "links"=>array(),
        "autologoff" => 120,
        "autologout"=> 28800,
        "autoinactive"=>120,
        "fids" => array(
            1 => array(
                "fid" => 1347,
                "favoriten" => array(2),
                "functions" => array(41, 1, 2, 5, 6, 31),
                "pplan" => 1,
                "pduration" => 31536000,
                "www" => 1,
                "degreeoforder" => 1,
                "icon" => "fa fa-area-chart fa-2x"
            ),
            2 => array(
                "fid" => 1336,
                "favoriten" => array(2),
                "functions" => array(41,1, 2 ,5, 6,31),
                "pplan" => 1,
                "pduration" => 31536000,
                "www" => 1,
                "degreeoforder" => 1,
                "icon" => "fa fa-area-chart fa-2x"
            ),
            3 => array(
                "fid" => 1335,
                "favoriten" => array(2),
                "functions" => array(41,1, 2 ,5, 6,31),
                "pplan" => 1,
                "pduration" => 31536000,
                "www" => 1,
                "degreeoforder" => 1,
                "icon" => "fa fa-area-chart fa-2x"
            ),
            4 => array(
                "fid" => 1334,
                "favoriten" => array(2),
                "functions" => array(41, 1, 2, 5, 6, 31),
                "pplan" => 1,
                "pduration" => 31536000,
                "www" => 1,
                "degreeoforder" => 1,
                "icon" => "fa fa-area-chart fa-2x"
            ),


        ),
        "printer" => array(
        ),
        "address"=>array(
            "firma"=>"BLANCO Professional Kunststofftechnik GmbH",
            "strasse" => "Werkstaexttenstrasse 31",
            "plz"=>"04319",
            "ort"=>"Leipzig",
            "land"=>"Deutschland",
            "signatur" => "BLANCO Professional Kunststofftechnik GmbH - Werkstaexttenstr. 31 - 04319 Leipzig",
            "HRB" => "Geschaexftsfuexhrer: Michaexl Huber   Amtsgericht Leipzig HRB 3307"
        ),

    ),
    "6114010" => array(
        "name" => "Reparatur Temp-Test Ebene 1",
        "group" => "Reparatur",
        "cost" => "61140",
        "layout" => 1,

        "functions" => array(),
        "mainfunctions" => array(),
        "favoriten" => array(1, 2),
        "links"=>array(),
        "autologoff" => 120,
        "autologout"=> 28800,
        "autoinactive"=>120,
        "fids" => array(
            1 => array(
                "fid" => 1340,
                "favoriten" => array(2),
                "functions" => array(41,1, 2 ,5, 6,31),
                "pplan" => 1,
                "pduration" => 31536000,
                "www" => 1,
                "degreeoforder" => 1,
                "icon" => "fa fa-area-chart fa-2x"
            ),
            2 => array(
                "fid" => 1339,
                "favoriten" => array(2),
                "functions" => array(41,1, 2 ,5, 6,31),
                "pplan" => 1,
                "pduration" => 31536000,
                "www" => 1,
                "degreeoforder" => 1,
                "icon" => "fa fa-area-chart fa-2x"
            ),
            3 => array(
                "fid" => 1338,
                "favoriten" => array(2),
                "functions" => array(41,1, 2 ,5, 6,31),
                "pplan" => 1,
                "pduration" => 31536000,
                "www" => 1,
                "degreeoforder" => 1,
                "icon" => "fa fa-area-chart fa-2x"
            ),
            4 => array(
                "fid" => 1337,
                "favoriten" => array(2),
                "functions" => array(41,1, 2 ,5, 6,31),
                "pplan" => 1,
                "pduration" => 31536000,
                "www" => 1,
                "degreeoforder" => 1,
                "icon" => "fa fa-area-chart fa-2x"
            ),


        ),
        "printer" => array(
        ),
        "address"=>array(
            "firma"=>"BLANCO Professional Kunststofftechnik GmbH",
            "strasse" => "Werkstaexttenstrasse 31",
            "plz"=>"04319",
            "ort"=>"Leipzig",
            "land"=>"Deutschland",
            "signatur" => "BLANCO Professional Kunststofftechnik GmbH - Werkstaexttenstr. 31 - 04319 Leipzig",
            "HRB" => "Geschaexftsfuexhrer: Michael Huber   Amtsgericht Leipzig HRB 3307"
        ),

    ),


    "6114012" => array(
        "name" => "Wareneingang BLANCO",
        "group" => "Wareneingang",
        "cost" => "61140",
        "layout" => 6,
        "functions" => array(37),
        "mainfunctions" => array(),
        "favoriten" => array(),
        "links"=>array(),
        "autologoff" => 480,
        "autologout"=> 28800,
        "autoinactive"=>480,
        "fids" => array(
            1 => array(
                "fid" => 1325,
                "favoriten" => array(37,40),
                "functions" => array(37,34,40,1,2,31),
                "pplan" => 1,
                "pduration" => 31536000,
                "www" => 1,
                "degreeoforder" => 1,
                "icon" => "fa fa-industry fa-2x"
            ),
        ),
        "printer" => array(
            "wareneingang" => array("link" => "print/wareneingang/drucker7", "top" => 0, "left" => 0, "layout"=>"Etikett 100x50"),
        ),
        "address"=>array(
            "firma"=>"BLANCO Professional Kunststofftechnik GmbH",
            "strasse" => "Werkstaexttenstrasse 31",
            "plz"=>"04319",
            "ort"=>"Leipzig",
            "land"=>"Deutschland",
            "signatur" => "BLANCO Professional Kunststofftechnik GmbH - Werkstaexttenstr. 31 - 04319 Leipzig",
            "HRB" => "Geschaexftsfuexhrer: Michaexl Huber   Amtsgericht Leipzig HRB 3307"
        ),

    ),

    "611303" => array(
        "name" => "Schaexumen",
        "group" => "Schaexumen",
        "cost" => "61130",
        "layout" => 2,
        "functions" => array(),
        "mainfunctions" => array(),
        "favoriten" => array(),
        "links"=>array(),
        "autologoff" => 60,
        "autologout"=> 28800,
        "autoinactive"=>120,
        "fids" => array(
            1 => array(
                "fid" => 1076,
                "favoriten" => array(1, 2),
                "functions" => array(1, 2 ,5, 6,31),
                "pplan" => 2,
                "pduration" => 36000,
                "www" => 1,
                "degreeoforder" => 1,
                "icon" => "zmdi zmdi-washing-machine fa-1x",
                "col" => 3,
            ),
            2 => array(
                "fid" => 1319,
                "name" => "Temperaturverlauf",
                "favoriten" => array(1, 2),
                "functions" => array(1, 2 ,5, 6,31),
                "pplan" => 2,
                "pduration" => 31536000,
                "www" => 9,
                "degreeoforder" => 1,
                "icon" => "fa fa-area-chart",
                "col" => 9,
                "dataset"=>1,
                "interval"=>24,
            ),
            3 => array(
                "fid" => 27,
                "name" => "Mischkopf 1",
                "favoriten" => array(13, 12),
                "functions" => array(13, 12, 9, 5,31),
                "pplan" => 2,
                "pduration" => 604800,
                "calibration" => 1,
                "www" => 9,
                "degreeoforder" => 1,
                "icon" => "fa fa-industry fa-1x",
                "col" => 3,
            ),
            4 => array(
                "fid" => 28,
                "name" => "Mischkopf 2",
                "favoriten" => array(13, 12),
                "functions" => array(13, 12, 9, 5,31),
                "pplan" => 2,
                "pduration" => 604800,
                "calibration" => 1,
                "www" => 10,
                "degreeoforder" => 1,
                "icon" => "fa fa-industry fa-1x",
                "col" => 3,
            ),
            5 => array(
                "fid" => 31,
                "name" => "Mischkopf 3",
                "favoriten" => array(13, 12),
                "functions" => array(13, 12, 9, 5,31),
                "pplan" => 2,
                "pduration" => 604800,
                "calibration" => 1,
                "www" => 11,
                "degreeoforder" => 1,
                "icon" => "fa fa-industry fa-1x",
                "col" => 3,
            ),
        ),

    ),

    /**
     * "611202" => array(
     * "name" => "Fraexsen",
     * "group" => "Schweissen & Fraexsen",
     * "cost" => "61120",
     * "layout" => 1,
     *
     * "functions" => array(),
     * "mainfunctions" => array(),
     * "favoriten" => array(),
     * "links"=>array(),
     * "autologoff" => 60,
     * "autologout"=> 28800,
     * "autoinactive"=>120,
     * "fids" => array(
     * 1 => array(
     * "fid" => 1286,
     * "col"=>12,
     * "favoriten" => array(1, 2),
     * "functions" => array(1, 2, 5, 6,31),
     * "pplan" => 4,
     * "pduration" => 36000,
     * "www" => 1,
     * "degreeoforder" => 1,
     * "icon" => "fa fa-industry fa-2x"
     * ),
     * ),
     *
     * ),
     */

    "611201" => array(
        "name" => "Schweissen & Fraexsen",
        "group" => "Schweissen & Fraexsen",
        "cost" => "61120",
        "layout" => 1,
        "functions" => array(),
        "mainfunctions" => array(),
        "favoriten" => array(),
        "autologoff" => 60,
        "autologout"=> 28800,
        "autoinactive"=>120,
        "links"=>array(),
        "fids" => array(
            1 => array(
                "fid" => 21,
                "favoriten" => array(1, 2),
                "functions" => array(1, 2, 5, 6,31),
                "pplan" => 1,
                "pduration" => 36000,
                "www" => 1,
                "degreeoforder" => 1,
                "icon" => "fa fa-ioxhost fa-2x"
            ),
            2 => array(
                "fid" => 20,
                "favoriten" => array(1, 2),
                "functions" => array(1, 2 ,5, 6,31),
                "pplan" => 1,
                "pduration" => 36000,
                "www" => 1,
                "degreeoforder" => 1,
                "icon" => "fa fa-ioxhost fa-2x"
            ),

            /**
            3 => array(
            "fid" => 1253,
            "favoriten" => array(1, 2),
            "functions" => array(1, 2 ,5, 6,31),
            "pplan" => 1,
            "pduration" => 36000,
            "www" => 1,
            "degreeoforder" => 1,
            "icon" => "fa fa-ioxhost fa-2x"
            ),
             *
             */
            3 => array(
                "fid" => 1286,
                "favoriten" => array(1, 2),
                "functions" => array(1, 2, 5, 6,31),
                "pplan" => 4,
                "pduration" => 36000,
                "www" => 1,
                "degreeoforder" => 1,
                "icon" => "fa fa-industry fa-2x"
            ),

            4 => array(
                "fid" => 6,
                "favoriten" => array(1, 2),
                "functions" => array(1, 2 ,5, 6,31),
                "pplan" => 1,
                "pduration" => 36000,
                "www" => 1,
                "degreeoforder" => 1,
                "icon" => "fa fa-ioxhost fa-2x"
            ),
        ),

    ),

//Hardware

    "1" => array(
        "name" => "Etikettendrucker Montage",
        "group" => "Drucker",
        "cost" => "71110",
        "col"=>3,
        "layout" => 1,
        "autologoff" => 480,
        "functions" => array(),
        "links"=>array(

        ),
        "fids" => array(
            1 => array(
                "fid" => 1341,
                "favoriten" => array(1, 2),
                "functions" => array(41,1, 2, 5, 6,31),
                "pplan" => 1,
                "pduration" => 31536000,
                "www" => 1,
                "degreeoforder" => 1,
                "icon" => "fa fa-print fa-2x"
            ),
            2 => array(
                "fid" => 1342,
                "favoriten" => array(1, 2),
                "functions" => array(41,1, 2, 5, 6,31),
                "pplan" => 1,
                "pduration" => 31536000,
                "www" => 1,
                "degreeoforder" => 1,
                "icon" => "fa fa-print fa-2x"
            ),
            3 => array(
                "fid" => 1343,
                "favoriten" => array(1, 2),
                "functions" => array(41,1, 2, 5, 6,31),
                "pplan" => 1,
                "pduration" => 31536000,
                "www" => 1,
                "degreeoforder" => 1,
                "icon" => "fa fa-print fa-2x"
            ),
            4 => array(
                "fid" => 1344,
                "favoriten" => array(1, 2),
                "functions" => array(41,1, 2, 5, 6,31),
                "pplan" => 1,
                "pduration" => 31536000,
                "www" => 1,
                "degreeoforder" => 1,
                "icon" => "fa fa-print fa-2x"
            ),
            5 => array(
                "fid" => 1345,
                "favoriten" => array(1, 2),
                "functions" => array(41,1, 2, 5, 6,31),
                "pplan" => 1,
                "pduration" => 31536000,
                "www" => 1,
                "degreeoforder" => 1,
                "icon" => "fa fa-print fa-2x"
            ),
            6 => array(
                "fid" => 1346,
                "favoriten" => array(1, 2),
                "functions" => array(41,1, 2, 5, 6,31),
                "pplan" => 1,
                "pduration" => 31536000,
                "www" => 1,
                "degreeoforder" => 1,
                "icon" => "fa fa-print fa-2x"
            ),
            7 => array(
                "fid" => 1317,
                "favoriten" => array(1, 2),
                "functions" => array(41,1, 2, 5, 6,31),
                "pplan" => 1,
                "pduration" => 31536000,
                "www" => 1,
                "degreeoforder" => 1,
                "icon" => "fa fa-print fa-2x"
            ),
        ),

    ),

    "2" => array(
        "name" => "Drucker Reparatur",
        "group" => "Drucker",
        "cost" => "61140",
        "col"=>6,
        "layout" => 1,
        "autologoff" => 480,
        "functions" => array(),
        "links"=>array(

        ),
        "fids" => array(
            1 => array(
                "fid" => 1348,
                "favoriten" => array(1, 2),
                "functions" => array(41,1, 2, 5, 6,31),
                "pplan" => 1,
                "pduration" => 31536000,
                "www" => 1,
                "degreeoforder" => 1,
                "icon" => "fa fa-print fa-2x"
            ),
            2 => array(
                "fid" => 1349,
                "favoriten" => array(1, 2),
                "functions" => array(41,1, 2, 5, 6,31),
                "pplan" => 1,
                "pduration" => 31536000,
                "www" => 1,
                "degreeoforder" => 1,
                "icon" => "fa fa-print fa-2x"
            ),


        ),

    ),

    "3" => array(
        "name" => "Temperaturmessung",
        "group" => "Datenlogger",
        "cost" => "61140",
        "col"=>3,
        "layout" => 1,
        "autologoff" => 480,
        "functions" => array(),
        "links"=>array(

        ),
        "fids" => array(
            1 => array(
                "fid" => 1350,
                "favoriten" => array(1, 2),
                "functions" => array(41,1, 2, 5, 6,31),
                "pplan" => 1,
                "pduration" => 31536000,
                "www" => 1,
                "degreeoforder" => 1,
                "icon" => "fa fa-area-chart fa-2x"
            ),
            2 => array(
                "fid" => 1351,
                "favoriten" => array(1, 2),
                "functions" => array(41,1, 2, 5, 6,31),
                "pplan" => 1,
                "pduration" => 31536000,
                "www" => 1,
                "degreeoforder" => 1,
                "icon" => "fa fa-area-chart fa-2x"
            ),
            3 => array(
                "fid" => 1352,
                "favoriten" => array(1, 2),
                "functions" => array(41,1, 2, 5, 6,31),
                "pplan" => 1,
                "pduration" => 31536000,
                "www" => 1,
                "degreeoforder" => 1,
                "icon" => "fa fa-area-chart fa-2x"
            ),
            4 => array(
                "fid" => 1353,
                "favoriten" => array(1, 2),
                "functions" => array(41,1, 2, 5, 6,31),
                "pplan" => 1,
                "pduration" => 31536000,
                "www" => 1,
                "degreeoforder" => 1,
                "icon" => "fa fa-area-chart fa-2x"
            ),
            5 => array(
                "fid" => 1354,
                "favoriten" => array(1, 2),
                "functions" => array(41,1, 2, 5, 6,31),
                "pplan" => 1,
                "pduration" => 31536000,
                "www" => 1,
                "degreeoforder" => 1,
                "icon" => "fa fa-area-chart fa-2x"
            ),
            6 => array(
                "fid" => 1355,
                "favoriten" => array(1, 2),
                "functions" => array(41,1, 2, 5, 6,31),
                "pplan" => 1,
                "pduration" => 31536000,
                "www" => 1,
                "degreeoforder" => 1,
                "icon" => "fa fa-area-chart fa-2x"
            ),


        ),

    ),

    "4" => array(
        "name" => "IPAD's Montage",
        "group" => "Ruexckmeldeterminals",
        "cost" => "61140",
        "col"=>3,
        "layout" => 1,
        "autologoff" => 480,
        "functions" => array(),
        "links"=>array(

        ),
        "fids" => array(
            1 => array(
                "fid" => 1000,
                "favoriten" => array(1, 2),
                "functions" => array(1, 2, 5, 6),
                "pplan" => 1,
                "pduration" => 31536000,
                "www" => 1,
                "degreeoforder" => 1,
                "icon" => "fa fa-print fa-2x"
            ),
            2 => array(
                "fid" => 1000,
                "favoriten" => array(1, 2),
                "functions" => array(1, 2, 5, 6),
                "pplan" => 1,
                "pduration" => 31536000,
                "www" => 1,
                "degreeoforder" => 1,
                "icon" => "fa fa-print fa-2x"
            ),
            3 => array(
                "fid" => 1000,
                "favoriten" => array(1, 2),
                "functions" => array(1, 2, 5, 6),
                "pplan" => 1,
                "pduration" => 31536000,
                "www" => 1,
                "degreeoforder" => 1,
                "icon" => "fa fa-print fa-2x"
            ),
            4 => array(
                "fid" => 1000,
                "favoriten" => array(1, 2),
                "functions" => array(1, 2, 5, 6),
                "pplan" => 1,
                "pduration" => 31536000,
                "www" => 1,
                "degreeoforder" => 1,
                "icon" => "fa fa-print fa-2x"
            ),


        ),

    ),

    "5" => array(
        "name" => "IPAD's Produktion",
        "group" => "Ruexckmeldeterminals",
        "cost" => "61140",
        "col"=>3,
        "layout" => 1,
        "autologoff" => 480,
        "functions" => array(),
        "links"=>array(

        ),
        "fids" => array(
            1 => array(
                "fid" => 1000,
                "favoriten" => array(1, 2),
                "functions" => array(1, 2, 5, 6),
                "pplan" => 1,
                "pduration" => 31536000,
                "www" => 1,
                "degreeoforder" => 1,
                "icon" => "fa fa-print fa-2x"
            ),
            2 => array(
                "fid" => 1000,
                "favoriten" => array(1, 2),
                "functions" => array(1, 2, 5, 6),
                "pplan" => 1,
                "pduration" => 31536000,
                "www" => 1,
                "degreeoforder" => 1,
                "icon" => "fa fa-print fa-2x"
            ),
            3 => array(
                "fid" => 1000,
                "favoriten" => array(1, 2),
                "functions" => array(1, 2, 5, 6),
                "pplan" => 1,
                "pduration" => 31536000,
                "www" => 1,
                "degreeoforder" => 1,
                "icon" => "fa fa-print fa-2x"
            ),
            4 => array(
                "fid" => 1000,
                "favoriten" => array(1, 2),
                "functions" => array(1, 2, 5, 6),
                "pplan" => 1,
                "pduration" => 31536000,
                "www" => 1,
                "degreeoforder" => 1,
                "icon" => "fa fa-print fa-2x"
            ),


        ),

    ),

    "6" => array(
        "name" => "IPAD's Reparatur",
        "group" => "Ruexckmeldeterminals",
        "cost" => "61140",
        "col"=>3,
        "layout" => 1,
        "autologoff" => 480,
        "functions" => array(),
        "links"=>array(

        ),
        "fids" => array(
            1 => array(
                "fid" => 1000,
                "favoriten" => array(1, 2),
                "functions" => array(1, 2, 5, 6),
                "pplan" => 1,
                "pduration" => 31536000,
                "www" => 1,
                "degreeoforder" => 1,
                "icon" => "fa fa-print fa-2x"
            ),
            2 => array(
                "fid" => 1000,
                "favoriten" => array(1, 2),
                "functions" => array(1, 2, 5, 6),
                "pplan" => 1,
                "pduration" => 31536000,
                "www" => 1,
                "degreeoforder" => 1,
                "icon" => "fa fa-print fa-2x"
            ),
            3 => array(
                "fid" => 1000,
                "favoriten" => array(1, 2),
                "functions" => array(1, 2, 5, 6),
                "pplan" => 1,
                "pduration" => 31536000,
                "www" => 1,
                "degreeoforder" => 1,
                "icon" => "fa fa-print fa-2x"
            ),
            4 => array(
                "fid" => 1000,
                "favoriten" => array(1, 2),
                "functions" => array(1, 2, 5, 6),
                "pplan" => 1,
                "pduration" => 31536000,
                "www" => 1,
                "degreeoforder" => 1,
                "icon" => "fa fa-print fa-2x"
            ),


        ),

    ),
);