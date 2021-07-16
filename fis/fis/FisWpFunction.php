<?php
/**
 * Created by PhpStorm.
 * User: andreasveltjens
 * Date: 2019-01-11
 * Time: 12:46
 */

class FisWpFunction
{

    public $function=0;

    private $functions= array(
        1 => array("name" => "Wartungsrückmeldung", "icon" => "fa fa-check m-r-5"),
        2 => array("name" => "Störmeldung anlegen", "icon" => "fa fa-flash m-r-5"),
        3 => array("name" => "Verbesserung", "icon" => "zmdi zmdi-mood"),
        4 => array("name" => "Produktionsrückmeldung", "icon" => "fa fa-list m-r-5"),
        5 => array("name" => "History", "icon" => "fa fa-history m-r-5"),

        6 => array("name" => "Einstellungen", "icon" => "fa fa-gears m-r-5"),
        7 => array("name" => "Ordnungsgrad", "icon" => "zmdi zmdi-assignment-o m-r-5"),
        8 => array("name" => "Parameteränderung dokumentieren", "icon" => "zmdi zmdi-notifications-active"),
        9 => array("name" => "Werkzeugwechsel", "icon" => "zmdi zmdi-swap"),
        10 => array("name" => "Ereignis dokumentieren", "icon" => "zmdi zmdi-calendar-note"),
        11 => array("name" => "Ereignis anzeigen", "icon" => "zmdi zmdi-calendar-note"),
        12 => array("name" => "Produktprüfung durchführen", "icon" => "zmdi zmdi-eye"),

        13 => array("name" => "Kalibrierung durchführen", "icon" => "zmdi zmdi-check"),
        14 => array("name" => "Fertigungsmeldung anzeigen", "icon" => "zmdi zmdi-eye"),



        18 => array("name" => "Fertigungsmeldung bearbeiten", "icon" => "fa fa-edit"),
        19 => array("name" => "Druckereinstellungen", "icon" => "fa fa-print"),
        20 => array("name" => "Finde alles", "icon" => "fa fa-search"),
        21 => array("name" => "FIS Grundeinstellungen", "icon" => "fa fa-file-o","userright"=>5),

        22 => array("name" => "Übersicht Rückholung", "icon" => "fa fa-refresh", "links" => array(22, 23, 24, 27)),
        23 => array("name" => "alle Fehlermeldungen", "icon" => "fa fa-list", "links" => array(22, 23, 24, 27)),
        24 => array("name" => "Fehlermeldung anlegen", "icon" => "fa fa-plus", "links" => array(22, 23, 24, 27)),
        25 => array("name" => "Fehlermeldung bearbeiten", "icon" => "fa fa-plug", "links" => array(22, 23, 24, 27)),

        26 => array("name" => "Temperaturmessung anzeigen", "icon" => "fa fa-area-chart"),
        27 => array("name" => "Kundendaten", "icon" => "fa fa-user", "links" => array(22, 23, 24, 27)),
        28 => array("name" => "Kundendaten bearbeiten", "icon" => "fa fa-edit", "links" => array(22, 23, 24, 27)),
        29 => array("name" => "Rückholung bearbeiten", "icon" => "fa fa-edit", "userright" => 4, "links" => array(22, 23, 24, 27)),


        30 => array("name" => "Equipmentübersicht", "icon" => "fa fa-industry", "links" => array(30, 45, 47, 48, 65, 66, 67)),
        31 => array("name" => "Equipment bearbeiten", "icon" => "fa fa-edit", "userright" => 5, "links" => array(30, 45, 47, 48, 65, 66, 67)),

        32 => array("name" => "Benutzerübersicht", "icon" => "fa fa-users","userright"=>5),
        33 => array("name" => "Benutzer bearbeiten", "icon" => "zmdi zmdi-account-box-mail","userright"=>5),

        34 => array("name" => "Materialübersicht", "icon" => "zmdi zmdi-storage", "links" => array(37, 42, 34)),
        15 => array("name" => "Materialversion anzeigen", "icon" => "zmdi zmdi-eye", "links" => array(37, 42, 34)),
        16 => array("name" => "Materialdaten bearbeiten", "icon" => "fa fa-edit", "links" => array(37, 42, 34)),
        17 => array("name" => "Materialversion bearbeiten", "icon" => "fa fa-edit", "links" => array(37, 42, 34)),


        35 => array("name" => "Mitarbeiterübersicht", "icon" => "fa fa-users", "userright"=>5),
        36 => array("name" => "Mitarbeiter bearbeiten", "icon" => "fa fa-user", "userright"=>5),

        37 => array("name" => "alle Wareneingänge", "icon" => "zmdi zmdi-case-download", "userright" => 2, "links" => array(37, 42, 34)),
        38 => array("name" => "Wareneingang bearbeiten", "icon" => "fa fa-edit", "userright" => 2, "links" => array(37, 42, 34)),

        39 => array("name" => "Nachdruck", "icon" => "fa fa-print", "userright"=>1),
        40 => array("name" => "Druckeinstellungen", "icon" => "fa fa-print", "userright"=>1),

        41 => array("name" => "Hardwareübersicht", "icon" => "fa fa-list", "userright"=>5),

        42 => array("name" => "Fertigungsmeldungen", "icon" => "fa fa-list", "userright" => 2, "links" => array(37, 42, 34)),
        43 => array("name" => "Fertigungsmeldungen freigeben", "icon" => "fa fa-check", "userright" => 1, "links" => array(37, 42, 34)),

        44 => array("name" => "Barcode Generator", "icon" => "fa fa-code", "userright" => 1, "links" => array(74, 63, 44)),

        45 => array("name" => "Ereignisübersicht", "icon" => "zmdi zmdi-calendar-check", "userright" => 1, "links" => array(30, 45, 47, 48, 65, 66, 67)),
        46 => array("name" => "SAP Rückmeldung", "icon" => "fa fa-flag-checkered", "userright"=>3),

        47 => array("name" => "fällige Wartungen", "icon" => "fa fa-flash ", "userright" => 2, "links" => array(30, 45, 47, 48, 65, 66, 67)),
        48 => array("name" => "Wartungsmanager", "icon" => "fa fa-tasks", "userright" => 3, "links" => array(30, 45, 47, 48, 65, 66, 67)),

        49 => array("name" => "Funktionsübersicht", "icon" => "fa fa-list", "userright"=>1),


        50 => array("name" => "Reparatur bearbeiten", "icon" => "fa fa-plug"),
        51 => array("name" => "Bauteil-Upgrade bearbeiten", "icon" => "fa fa-plug"),
        52 => array("name" => "Bauteil-Austausch bearbeiten", "icon" => "fa fa-plug"),
        53 => array("name" => "Verschrottung bearbeiten", "icon" => "fa fa-plug"),
        54 => array("name" => "Neue Seriennumer für alte Seriennummer bearbeiten", "icon" => "fa fa-plug"),

        55 => array("name" => "Seriennumer nacharbeiten", "icon" => "fa fa-plug"),
        56 => array("name" => "Seriennumer verschrotten", "icon" => "fa fa-plug"),

        57 => array("name" => "Auftragsnummern für die Abrechnung ändern", "icon" => "fa fa-plug"),
        60 => array("name" => "Reparaturaufnahme", "icon" => "fa fa-plug"),
        61 => array("name" => "Reparatur und VDE-Prüfung", "icon" => "fa fa-plug"),
        62 => array("name" => "Versand", "icon" => "fa fa-plug"),

        63 => array("name" => "Etikett Generator", "icon" => "zmdi zmdi-shape", "userright" => 1, "links" => array(74, 63, 44)),

        64 => array("name" => "Wartung bearbeiten", "icon" => "fa fa-edit", "userright" => 3, "links" => array(30, 45, 47, 48, 65, 66, 67)),
        65 => array("name" => "anliegende Störungen", "icon" => "zmdi zmdi-wrench ", "userright" => 1, "links" => array(30, 45, 47, 48, 65, 66, 67)),

        66 => array("name" => "Messmittelverwaltung", "icon" => "zmdi zmdi-ruler ", "userright" => 1, "links" => array(30, 45, 47, 48, 65, 66, 67)),
        67 => array("name" => "nächste Kalibrierungen", "icon" => "zmdi zmdi-flag ", "userright" => 1, "links" => array(30, 45, 47, 48, 65, 66, 67)),

        68 => array("name" => "Reportmanager", "icon" => "zmdi zmdi-assignment ", "userright" => 1),
        69 => array("name" => "Auswertungsgruppen bearbeiten", "icon" => "fa fa-edit", "userright" => 1),
        70 => array("name" => "Auswertungen", "icon" => "zmdi zmdi-assignment-o ", "userright" => 1),

        71 => array("name" => "Fertigungsaufträge", "icon" => "fa fa-list", "userright" => 1, "links" => array(37, 42, 34, 71)),
        72 => array("name" => "Fertigungsauftrag bearbeiten", "icon" => "fa fa-edit ", "userright" => 1, "links" => array(37, 42, 34, 71)),
        73 => array("name" => "Fertigungsaufträge planen", "icon" => "fa fa-edit ", "userright" => 1, "links" => array(37, 42, 34, 71)),

        74 => array("name" => "Versandlabel erstellen", "icon" => "fa fa-cubes ", "userright" => 1, "links" => array(74, 63, 44)),

        99 => array("name" => "Arbeitsplatzgruppe anzeigen", "icon" => "zmdi zmdi-group", "userright" =>1),
        100 => array("name" => "FIS Softwareupdate", "icon" => "fa fa-cloud-download", "userright" =>5),




    );


    function __construct($i=0)
    {
        $this->function=$i;
    }

    function __toString()
    {
       return $this->function;
    }

    function GetFunctionsAsArray(){

        return $this->functions;
    }

    function SetFunctionID($i){
        $this->function=$i;
    }

    function GetFunctionProperties(){
        return $this->functions[$this->function];
    }
}