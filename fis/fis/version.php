<?php
/**
 * Created by PhpStorm.
 * User: andreasveltjens
 * Date: 2019-02-02
 * Time: 17:00
 */

$IFIS_VERSION=array(
    "version 12.0.22" => array(
        "desc" => "IFIS automatische Materialnummer- Vorschlagswert bei Version und Material anlegen. Autoprint funktion via http ergänzt.
<br><br>add automatic materialnumber if materialnumber is null, add autoprint.php to print documents via http
",
    ),
    "version 12.0.21" => array(
        "desc" => "IFIS 12 Assemblies",
        "changelog" => "Fehlerbeseitigung bei der Anzeige der EAN-Codes. 
        Arbeitsplan und Stücklistenübersicht in Fertigungsuafträgen, 
        Beispieldaten für Druckeinstellungen, Konfiguration und Arbeitsplaneinstellungen.
        Softwareupdate für Tabelle Fertigungsdaten.
        <br><br>Print Layout size is customize in ../config/setup. Bug fixing in EAN-Code view. Add some examples for print, config and process data. Add some attributes in fauf-table
                ",
    ),
    "version 12.0.20" => array(
        "desc" => "IFIS 12 ConfigPicture",
        "changelog" => "Logo and Etikett-Bilder befinden sich im Ordner ../onfig. Update config path and change reference path and global defines.
                ",
    ),
    "version 12.0.19" => array(
        "desc" => "IFIS 12 ChangeVersionName",
        "changelog" => "Änderungen von Versionsbezeichnungen. Add a function to change version description and modificate production data.
                ",
    ),

    "version 12.0.18" => array(
        "desc" => "IFIS 12 MenuOptimize",
        "changelog" => "Ihre Funktionen- Menü wurde überarbeitet. Change Menu layout and structure it by userright and context.
            ",
    ),
    "version 12.0.17" => array(
        "desc" => "IFIS 12 Barcodes",
        "changelog" => "Ergänzen von verschiedenen Links in der Titelleiste. Fehlermeldungen mit Standard-Reparaturtexten, die im Customzing unter Templates editiert werden können. 
        Add presentation links layout and make several short links in menu. add customizing templates.
            ",
    ),
    "version 12.0.16" => array(
        "desc" => "IFIS 12 Barcodes",
        "changelog" => "Anzeige der Barcodes für mehrere Artikel auf einem A4-Blatt, sofern diese in der gleichen Warengruppe sind.  
        ",
    ),
    "version 12.0.15" => array(
        "desc" => "IFIS 12 Printsettings",
        "changelog" => "Ergänzung von Funktion Schnelländerung Druckeinstellungen im Rückmeldedialog und in den Materialeinstellungen.
                        Änderungen von Version möglich, die nicht aktiv sind.
                        Änderung der Fertigungsdaten mit folgenden Funktionen: Freigabe, Korrektur, Nacharbeit, Wiederholungsprüfung, Verschrotten, Löschen. ACHTUNG: Diese neuen Funktion sind nicht mehr mit FIS9 oder FIS10 kompatibel.
                        Updatekorrektur Datum der Version wird auf den Stand der letzten Datensicherung aus 11/2018 zurückgesetzt.
        ",
    ),
    "version 12.0.14" => array(
        "desc" => "IFIS 12 OrderConfirm",
        "changelog" => "Ergänzung von Funktion erledigte Rückmeldungen, 
                        Ergänzung der Kundendaten mit Kundengruppen, 
                        Suchfunktion für Kundengruppen,
                        Lebensdauer- und Reparaturdauerberechnung in Fehlermeldungen,
                        Typenschild von Reparaturen (FIDTyp==4 oder FMType==4) werden mit R-m-Y dargestellt.
                        Universal-Etikett für Versandlabel A5 
        ",
    ),
    "version 12.0.13" => array(
        "desc" => "IFIS 12 Order",
        "changelog" => "Ergänzung von Funktion Fertigungsauftragsliste",
    ),
    "version 12.0.12" => array(
        "desc" => "IFIS 12 Support+Mail",
        "changelog" => "Ergänzung von Supportanfragen per E-Mail",
    ),
    "version 12.0.11" => array(
        "desc" => "IFIS 12 Version+",
        "changelog" => "Ergänzung von Arbeitsplan und Stückliste in App Version, Neue Version jetzt mit Vorlage anlegen. In den Versionseinstellung kann zwischen Texteingabe und Formateditor gewählt werden.",
    ),
    "version 12.0.10" => array(
        "desc" => "IFIS 12 Report2",
        "changelog" => "App Auswertungen für Retourenabwicklung für Gewährleistung",
    ),
    "version 12.0.9" => array(
        "desc" => "IFIS 12 Report",
        "changelog" => "App Report-Manager und Auswertungen für Retourenabwicklung",
    ),
    "version 12.0.8" => array(
        "desc" => "IFIS 12 Darksite",
        "changelog" => "Hinzufügen von Links und weiteren Funktionen innerhalb der Retourenabwicklung, Geschwindigkeitsoptimierung Fehlermeldung, Servicepartnersuche im Wartungsmanager, Fehlerbeseitigung beim Kundendaten anlegen",
    ),
    "version 12.0.7" => array(
        "desc" => "IFIS 12 Sunshine",
        "changelog" => "App aniegende Störungen und Messmittelübersicht sowie Kalibrierungsübersicht. Neue Ereignisstati: alles anzeigen=0, in Arbeit=0, abgeschlossen =2 "
    ),
    "version 12.0.6" => array(
        "desc" => "IFIS 12 Follow Up",
        "changelog" => "App Universal- Etiketten ergänzt. Neue, schnellere Überallsuche."
    ),
    "version 12.0.5" => array(
        "desc" => "IFIS 12 Saturday",
        "changelog" => "App Wartungsmanager und fällige Wartungen"
    ),
    "version 12.0.4" => array(
        "desc" => "IFIS 12 Luna",
        "changelog" => "SAP Rückmeldungen bei Reparaturen wurden ergänzt."
    ),
    "version 12.0.3" => array(
        "desc" => "IFIS 12 Tree",
        "changelog" => "Einführung von Fertigungsauftragstypen: Neulange=0, Nacharbeit=1, Reparatur=2, Reparatur mit neuer SN=3, Verschrottung=4, Upgrade=4, Austausch=5"
    ),
    "version 12.0.2" => array(
        "desc" => "IFIS 12 Monday",
        "changelog" => "App Barcodecreator"
    ),
    "version 12.0.1" => array(
        "desc" => "IFIS 12 Monday",
        "changelog" => "Fehlermeldungen mit Funktion Bauteilsuche"
    ),
    "version 12.0.0"=>array(
        "desc" => "IFIS 12 BASIC",
        "changelog" => "Rollout"
    ),
);