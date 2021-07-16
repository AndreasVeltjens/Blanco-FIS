<?php
/**
 * Created by PhpStorm.
 * User: andreasveltjens
 * Date: 25.12.16
 * Time: 17:09
 */

function DoSoftwareUpdate()
{
    include("../apps/dbconnection.php");

    $update[0] = "CREATE TABLE IF NOT EXISTS `wareneingang` (
  `wid` bigint(20) NOT NULL,
  `wdesc` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `wemail` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `wdatum` datetime NOT NULL,
  `wstate` int(11) NOT NULL,
  `wlokz` tinyint(4) NOT NULL,
  `wdefective` tinyint(4) NOT NULL,
  `wlocation` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `wnotes` varchar(255) COLLATE utf8_unicode_ci NULL,
  `wuser` int(11) NOT NULL,
  `wuserto` int(11) NOT NULL,
  `werk` int(11) NOT NULL,
  `wcustomer` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `wdelivery` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `wcount` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`wid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Wareneingang';
";

    $update[1] = "ALTER TABLE  `wareneingang` CHANGE  `wid`  `wid` BIGINT( 20 ) NOT NULL AUTO_INCREMENT";

    $update[2] = "CREATE TABLE `mitarbeiter` (
  `userid` bigint(20) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `udatum` datetime NOT NULL,
  `ustate` int(11) NOT NULL,
  `lokz` tinyint(4) NOT NULL,
  `userright` tinyint(4) NOT NULL,
  `abteilung` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `anschrift` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `plz` varchar(255) COLLATE utf8_unicode_ci NULL,
  `ort` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `land` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `telefon` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `fax` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `werk` int(11) NOT NULL,
  PRIMARY KEY (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Mitarbeiter';
";
    $update[3] = "ALTER TABLE  `mitarbeiter` CHANGE  `userid`  `userid` BIGINT( 20 ) NOT NULL AUTO_INCREMENT";


    $update[4] = "CREATE TABLE `wareneingangworkflow` (
  `wwid` bigint(20) NOT NULL,
  `wid` bigint(20) NOT NULL,
  `wuser` bigint(20) NOT NULL,
  `wdatum` datetime NOT NULL,
  `wstate` int(11) NOT NULL,
  `lokz` tinyint(4) NOT NULL,
  `wnotes` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `werk` int(11) NOT NULL,
  PRIMARY KEY (`wwid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Wareneingangworkflow';
";
    $update[5] = "ALTER TABLE  `wareneingangworkflow` CHANGE  `wwid`  `wwid` BIGINT( 20 ) NOT NULL AUTO_INCREMENT";

    $update[6] = "CREATE TABLE `warenausgangworkflow` (
  `wwaid` bigint(20) NOT NULL,
  `waid` bigint(20) NOT NULL,
  `wuser` bigint(20) NOT NULL,
  `wdatum` datetime NOT NULL,
  `wstate` int(11) NOT NULL,
  `lokz` tinyint(4) NOT NULL,
  `wnotes` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `werk` int(11) NOT NULL,
  PRIMARY KEY (`wwaid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Wareneingangworkflow';
";
    $update[7] = "ALTER TABLE  `warenausgangworkflow` CHANGE  `wwaid`  `wwid` BIGINT( 20 ) NOT NULL AUTO_INCREMENT";

    /*
     * Bewegungsdatum
Bewegungsart
Auftrags-Nr
Lieferschein-Nr.
Aussenlager
Nr. der Umlagerung
Kunde/ Lieferant
Netto lt. LS
Gewicht lt. Prod.
Anzahl VE
Verpackungsart
Verpackungsgewicht
Produkt
Charge
Linie
Abfüllung
Gewicht
Bemerkungen

     */

    $update[8] = "CREATE TABLE IF NOT EXISTS `warenausgang` (
  `waid` bigint(20) NOT NULL,
  `wdesc` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `wemail` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  
  `auftragsnummer` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `lieferscheinnummer` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `aussenlager` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `lieferung` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `nettols` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `nettoproduktion` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `anzahlve` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `verpackungsart` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  `verpackungsgewicht` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  `produkt` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  `charge` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  `linie` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  `abfuellung` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  `gewicht` varchar(255) COLLATE utf8_unicode_ci NOT NULL,    
  
  `wdatum` datetime NOT NULL,
  `wstate` int(11) NOT NULL,
  `wlokz` tinyint(4) NOT NULL,
  `wdefective` tinyint(4) NOT NULL,
  `wlocation` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `wnotes` varchar(255) COLLATE utf8_unicode_ci NULL,
  `wuser` int(11) NOT NULL,
  `wuserto` int(11) NOT NULL,
  `werk` int(11) NOT NULL,
  `wcustomer` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `wdelivery` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `wcount` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`waid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Warenausgang';
";

    $update[9] = "ALTER TABLE  `warenausgang` CHANGE  `waid`  `waid` BIGINT( 20 ) NOT NULL AUTO_INCREMENT";


    $update[10] = "CREATE TABLE `dokumente` (
  `did` bigint(20) NOT NULL,
  `linkedid` bigint(20) NOT NULL,
  `dobject` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `duser` bigint(20) NOT NULL,
  `ddatum` datetime NOT NULL,
  `dstate` int(11) NOT NULL,
  `lokz` tinyint(4) NOT NULL,
  `dnotes` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `documentlink` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `werk` int(11) NOT NULL,
  PRIMARY KEY (`did`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Dokumente';
";
    $update[11] = "ALTER TABLE  `dokumente` CHANGE  `did`  `did` BIGINT( 20 ) NOT NULL AUTO_INCREMENT";



    $update[12] = "CREATE TABLE IF NOT EXISTS `inventur` (
  `invid` bigint(20) NOT NULL,
  `gebindeid` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `material` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `aussenlager` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `verpackungsart` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  `verpackungsgewicht` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  `produkt` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  `charge` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  `linie` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  `fertigungsauftrag` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  `gewicht` varchar(255) COLLATE utf8_unicode_ci NOT NULL,    
  
  `idatum` datetime NOT NULL,
  `istate` int(11) NOT NULL,
  `ilokz` tinyint(4) NOT NULL,
  `inotes` varchar(255) COLLATE utf8_unicode_ci NULL,
  `iuser` int(11) NOT NULL,
  `iuserto` int(11) NOT NULL,
  `werk` int(11) NOT NULL,

  
  PRIMARY KEY (`invid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Inventur';
";

    $update[13] = "ALTER TABLE  `inventur` CHANGE  `invid`  `invid` BIGINT( 20 ) NOT NULL AUTO_INCREMENT";


    $update[14] = "CREATE TABLE IF NOT EXISTS `fertigungsmeldungen` (
  `fid` bigint(20) NOT NULL,
  `sap` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `fauf` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `gebindeid` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `material` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `aussenlager` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `verpackungsart` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  `verpackungsgewicht` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  `produkt` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  `charge` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  `linie` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  `extrusion` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  `fertigungsauftrag` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  `gewicht` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
    
   `prozesshinweise` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  `inputquali` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  

  `mfi` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  `mfibis` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  `mfi2` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  `mfibis2` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  `mfimethode` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  `mfimethode2` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  `emodul` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  `emodulbis` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  `farbe` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  `farbebis` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  `schlag` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  `schlagbis` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  
  `ifeuchte` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  `ifeuchtebis` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  `afeuchte` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  `afeuchtebis` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  
  `ende` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  
  `ftype` int(11) NOT NULL,
  `fdatum` datetime NOT NULL,
  `fstate` int(11) NOT NULL,
  `flokz` tinyint(4) NOT NULL,
  `fnotes` varchar(255) COLLATE utf8_unicode_ci NULL,
  `fuser` int(11) NOT NULL,
  `fuserto` int(11) NOT NULL,
  `werk` int(11) NOT NULL,

  
  PRIMARY KEY (`fid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Fertigungsmeldungen';
";

    $update[15] = "ALTER TABLE  `fertigungsmeldungen` CHANGE  `fid`  `fid` BIGINT( 20 ) NOT NULL AUTO_INCREMENT";

    $update[16] = "CREATE TABLE IF NOT EXISTS `fauf` (
  `faufid` bigint(20) NOT NULL,
  `fauf` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sap` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `material` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `aussenlager` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `verpackungsart` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  `verpackungsgewicht` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  `produkt` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  `charge` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  `linie` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  `extrusion` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  `fertigungsauftrag` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  `gewicht` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  `start` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  `ende` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  
  `prozesshinweise` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  `inputquali` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  
  `mfi` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  `mfibis` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  `mfi2` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  `mfibis2` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  `mfimethode` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  `mfimethode2` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  
  `emodul` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  `emodulbis` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  `farbe` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  `farbebis` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  `schlag` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  `schlagbis` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  
  `ifeuchte` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  `ifeuchtebis` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  `afeuchte` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  `afeuchtebis` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  `schuettdichte` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  `schuettdichtebis` varchar(255) COLLATE utf8_unicode_ci NOT NULL, 
  
  `ftype` int(11) NOT NULL,
  
  `fdatum` datetime NOT NULL,
  `fstate` int(11) NOT NULL,
  `flokz` tinyint(4) NOT NULL,
  `fnotes` varchar(255) COLLATE utf8_unicode_ci NULL,
  `fuser` int(11) NOT NULL,
  `fuserto` int(11) NOT NULL,
  `werk` int(11) NOT NULL,

  
  PRIMARY KEY (`faufid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Fertigungsauftrage';
";

    $update[17] = "ALTER TABLE  `fauf` CHANGE  `faufid`  `faufid` BIGINT( 20 ) NOT NULL AUTO_INCREMENT";


    $update[18] = "CREATE TABLE `faufworkflow` (
  `wfaufid` bigint(20) NOT NULL,
  `faufid` bigint(20) NOT NULL,
  `wuser` bigint(20) NOT NULL,
  `wdatum` datetime NOT NULL,
  `wstate` int(11) NOT NULL,
  `lokz` tinyint(4) NOT NULL,
  `wnotes` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `werk` int(11) NOT NULL,
  PRIMARY KEY (`wfaufid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='faufworkflow';
";
    $update[19] = "ALTER TABLE  `faufworkflow` CHANGE  `wfaufid`  `wfaufid` BIGINT( 20 ) NOT NULL AUTO_INCREMENT";




    $update[20] = "CREATE TABLE `fidworkflow` (
  `wfid` bigint(20) NOT NULL,
  `fid` bigint(20) NOT NULL,
  `wuser` bigint(20) NOT NULL,
  `wdatum` datetime NOT NULL,
  `wstate` int(11) NOT NULL,
  `lokz` tinyint(4) NOT NULL,
  `wnotes` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `werk` int(11) NOT NULL,
  PRIMARY KEY (`wfid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='fidworkflow';
";
    $update[21] = "ALTER TABLE  `fidworkflow` CHANGE  `wfid`  `wfid` BIGINT( 20 ) NOT NULL AUTO_INCREMENT";






    $update[22]="
CREATE TABLE `ImportGebindeData` (
IDGebinde bigint(20) NOT NULL,  
GebindeI bigint(20) NOT NULL,   
ChargeID varchar(100) COLLATE utf8_unicode_ci NOT NULL,  
Gewicht varchar(10) COLLATE utf8_unicode_ci NOT NULL,     
ProductionsDatum varchar(20) COLLATE utf8_unicode_ci NOT NULL,
Zeit varchar(10) COLLATE utf8_unicode_ci NOT NULL,
VerpackungID varchar(20) COLLATE utf8_unicode_ci NOT NULL,
SchmelzefiltrationID varchar(20) COLLATE utf8_unicode_ci NOT NULL,     
InputID varchar(20) COLLATE utf8_unicode_ci NOT NULL,
SchichtID  varchar(20) COLLATE utf8_unicode_ci NOT NULL,     
SchichtzeitID varchar(20) COLLATE utf8_unicode_ci NOT NULL,   
AbfullortID varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
MFRID varchar(20) COLLATE utf8_unicode_ci NOT NULL,
MFRID2 varchar(20) COLLATE utf8_unicode_ci NOT NULL,
MFRAuswertung2 varchar(20) COLLATE utf8_unicode_ci NOT NULL,    
MFRAuswertung varchar(20) COLLATE utf8_unicode_ci NOT NULL,      
StrangID varchar(20) COLLATE utf8_unicode_ci NOT NULL,      
Dichte varchar(20) COLLATE utf8_unicode_ci NOT NULL,
Schuettgewicht varchar(20) COLLATE utf8_unicode_ci NOT NULL,
AussereRestfeuchte varchar(20) COLLATE utf8_unicode_ci NOT NULL,
Wassergehalt varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
Aschegehalt varchar(20) COLLATE utf8_unicode_ci NOT NULL,    
PruferMFR varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
PruferRF varchar(20) COLLATE utf8_unicode_ci NOT NULL,      
PruferMA2 varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
PruferMA varchar(20) COLLATE utf8_unicode_ci NOT NULL,    
PruferF varchar(20) COLLATE utf8_unicode_ci NOT NULL,
PruferQ varchar(20) COLLATE utf8_unicode_ci NOT NULL,
Zugdehnung  varchar(20) COLLATE utf8_unicode_ci NOT NULL,    
Zugfestigestigkeit varchar(20) COLLATE utf8_unicode_ci NOT NULL,    
EModul varchar(20) COLLATE utf8_unicode_ci NOT NULL,      
Bruchspannung varchar(20) COLLATE utf8_unicode_ci NOT NULL,
Bruchdehnnung varchar(20) COLLATE utf8_unicode_ci NOT NULL,
CharpyKerbschlag varchar(20) COLLATE utf8_unicode_ci NOT NULL,    
FarbeID varchar(20) COLLATE utf8_unicode_ci NOT NULL,
FarbeinstufungVisuellID varchar(20) COLLATE utf8_unicode_ci NOT NULL,
FarbwertA varchar(20) COLLATE utf8_unicode_ci NOT NULL,   
FarbwertB varchar(20) COLLATE utf8_unicode_ci NOT NULL,   
HelligkeitswertL varchar(20) COLLATE utf8_unicode_ci NOT NULL,
QualitatID varchar(20) COLLATE utf8_unicode_ci NOT NULL,   
Feinpartikel varchar(20) COLLATE utf8_unicode_ci NOT NULL,
Oberflachendefekte varchar(20) COLLATE utf8_unicode_ci NOT NULL,
Materialunvertraglichkeit varchar(20) COLLATE utf8_unicode_ci NOT NULL,     
VerbranntesGranulat varchar(20) COLLATE utf8_unicode_ci NOT NULL,
FarbigeSilikonpartikel  varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
Farbausfall varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
Materialubergange varchar(20) COLLATE utf8_unicode_ci NOT NULL, 
QualitatsKommentar  varchar(20) COLLATE utf8_unicode_ci NOT NULL,   
Geschaumt varchar(20) COLLATE utf8_unicode_ci NOT NULL, 
Bemerkungen  varchar(20) COLLATE utf8_unicode_ci NOT NULL,
Abgezogen varchar(20) COLLATE utf8_unicode_ci NOT NULL, 
AbgezogeneMenge varchar(20) COLLATE utf8_unicode_ci NOT NULL, 
MFRm1 varchar(10) COLLATE utf8_unicode_ci NOT NULL,
MFRm2 varchar(10) COLLATE utf8_unicode_ci NOT NULL,
MFRm3 varchar(10) COLLATE utf8_unicode_ci NOT NULL,
MFRm4 varchar(10) COLLATE utf8_unicode_ci NOT NULL,
MFRm5 varchar(10) COLLATE utf8_unicode_ci NOT NULL,
MFRm6 varchar(10) COLLATE utf8_unicode_ci NOT NULL,
MFRachnitt varchar(10) COLLATE utf8_unicode_ci NOT NULL, 
MFRSekunden varchar(10) COLLATE utf8_unicode_ci NOT NULL,
Kugelkonstante varchar(10) COLLATE utf8_unicode_ci NOT NULL,
AblesungsWert varchar(10) COLLATE utf8_unicode_ci NOT NULL,
ErechneteKonstante  varchar(10) COLLATE utf8_unicode_ci NOT NULL,    
BezeichnungBatch varchar(255) COLLATE utf8_unicode_ci NOT NULL,   
DosierungBatch varchar(255) COLLATE utf8_unicode_ci NOT NULL,
DosierungAdditiv varchar(255) COLLATE utf8_unicode_ci NOT NULL,     
BezeichnungAdditiv varchar(255) COLLATE utf8_unicode_ci NOT NULL, 
ReservierungKundeName varchar(100) COLLATE utf8_unicode_ci NOT NULL,     
ReservierungKunde varchar(100) COLLATE utf8_unicode_ci NOT NULL, 
ReservierungAuftrag varchar(100) COLLATE utf8_unicode_ci NOT NULL,    
LagerplatzID  varchar(20) COLLATE utf8_unicode_ci NOT NULL,   
HalleID varchar(20) COLLATE utf8_unicode_ci NOT NULL,
UmlagerungDatum varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
UmlagerungLagerortID varchar(20) COLLATE utf8_unicode_ci NOT NULL,
Ladungsnummer varchar(20) COLLATE utf8_unicode_ci NOT NULL,      
UmlagerungsmengePG  varchar(20) COLLATE utf8_unicode_ci NOT NULL,
Verlader varchar(20) COLLATE utf8_unicode_ci NOT NULL,      
RetoureDatum varchar(20) COLLATE utf8_unicode_ci NOT NULL,
BewegungsDatum varchar(20) COLLATE utf8_unicode_ci NOT NULL,   
BewegungsartID varchar(20) COLLATE utf8_unicode_ci NOT NULL,      
Ziel varchar(100) COLLATE utf8_unicode_ci NOT NULL, 
DurchfuhrendePerson varchar(20) COLLATE utf8_unicode_ci NOT NULL,   
AbfullungDatum varchar(20) COLLATE utf8_unicode_ci NOT NULL,
KundeName varchar(100) COLLATE utf8_unicode_ci NOT NULL,
KundeID varchar(15) COLLATE utf8_unicode_ci NOT NULL,      
AuftragsNr varchar(15) COLLATE utf8_unicode_ci NOT NULL,  
LieferscheinNr  varchar(15) COLLATE utf8_unicode_ci NOT NULL,
VersandmengePG varchar(15) COLLATE utf8_unicode_ci NOT NULL,    
VersandmengeWS varchar(15) COLLATE utf8_unicode_ci NOT NULL,   
WSNr varchar(15) COLLATE utf8_unicode_ci NOT NULL,
VersandMonat varchar(15) COLLATE utf8_unicode_ci NOT NULL, 
Verlader2 varchar(25) COLLATE utf8_unicode_ci NOT NULL,    
AufLager varchar(25) COLLATE utf8_unicode_ci NOT NULL,     
Verkauft varchar(2) COLLATE utf8_unicode_ci NOT NULL,      
LadelisteErstellt varchar(2) COLLATE utf8_unicode_ci NOT NULL,
Umgelagert varchar(2) COLLATE utf8_unicode_ci NOT NULL,
Bewegt varchar(2) COLLATE utf8_unicode_ci NOT NULL,
bemerkungVS varchar(255) COLLATE utf8_unicode_ci NOT NULL,
alteGebindeNr_MG varchar(255) COLLATE utf8_unicode_ci NOT NULL,
PRIMARY KEY (`IDGebinde`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='ImportFromAccess';

    ";



    $update[23]="
CREATE TABLE `ImportChargenData` (
ID bigint(20) NOT NULL,  
ArtikelnummerID bigint(20) NOT NULL,  
Charge varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
ProduktgruppeID bigint(20) NOT NULL,  
ChargeDatum varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
Linie varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
Abfulung varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
Extruder varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
Bemerkungen varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
ProductionsDatum varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
Zeit varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
InputID varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
SchichtID varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
SchichtzeitID varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
AbfullortID varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
MFRID varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
MFRAuswertung varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
MFRID2 varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
MFRAuswertung2 varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
StrangID varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
Dichte varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
Schüttgewicht varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
AussereRestfeuchte varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
Wassergehalt varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
Aschegehalt varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
PruferMFR varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
PruferRF varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
PruferMA2 varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
PruferMA varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
PruferF varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
PruferQ varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
Zugdehnung varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
Zugfestigestigkeit varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
EModul varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
Bruchspannung varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
Bruchdehnnung varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
CharpyKerbschlag varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
FarbeID varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
FarbeinstufungVisuellID varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
FarbwertA varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
FarbwertB varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
HelligkeitswertL varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
QualitatID varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
Feinpartikel varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
Oberflachendefekte varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
Materialunvertraglichkeit varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
VerbranntesGranulat varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
FarbigeSilikonpartikel varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
Materialubergange varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
Farbausfall varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
QualitatsKommentar varchar(100) COLLATE utf8_unicode_ci NOT NULL,  
Geschaumt varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
BemerkungenAuswertung varchar(100) COLLATE utf8_unicode_ci NOT NULL,  
Abgezogen varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
AbgezogeneMenge varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
MFRm1 varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
MFRm2 varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
MFRm3 varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
MFRm4 varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
MFRm5 varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
MFRm6 varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
MFRachnitt varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
MFRSekunden varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
Kugelkonstante varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
AblesungsWert varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
ErechneteKonstante varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='ImportFromAccessChargen';

";


    $update[24]="ALTER TABLE  `fertigungsmeldungen` 
ADD  `farbel` VARCHAR( 5 ) NOT NULL AFTER  `farbebis` ,
ADD  `farbea` VARCHAR( 5 ) NOT NULL AFTER  `farbel` ,
ADD  `farbeb` VARCHAR( 5 ) NOT NULL AFTER  `farbea` ,
ADD  `strang` VARCHAR( 10 ) NOT NULL AFTER  `farbeb` ,
ADD  `zugdehnung` VARCHAR( 5 ) NOT NULL AFTER  `strang` ,
ADD  `zugfestigkeit` VARCHAR( 5 ) NOT NULL AFTER  `zugdehnung` ,
ADD  `dichte` VARCHAR( 5 ) NOT NULL AFTER  `zugfestigkeit` ;";

    $update[25]="ALTER TABLE  `fertigungsmeldungen` 
ADD  `Feinpartikel` TINYINT( 1 ) NOT NULL DEFAULT  '0' AFTER  `afeuchtebis` ,
ADD  `Oberflachendefekte` TINYINT( 1 ) NOT NULL DEFAULT  '0' AFTER  `Feinpartikel` ,
ADD  `Materialunvertraglichkeit` TINYINT( 1 ) NOT NULL DEFAULT  '0' AFTER  `Oberflachendefekte` ,
ADD  `VerbranntesGranulat` TINYINT( 1 ) NOT NULL DEFAULT  '0' AFTER  `Materialunvertraglichkeit` ,
ADD  `FarbigeSilikonpartikel` TINYINT( 1 ) NOT NULL DEFAULT  '0' AFTER  `VerbranntesGranulat` ,
ADD  `Materialubergange` TINYINT( 1 ) NOT NULL DEFAULT  '0' AFTER  `FarbigeSilikonpartikel` ,
ADD  `Farbausfall` TINYINT( 1 ) NOT NULL DEFAULT  '0' AFTER  `Materialubergange` ;";



     $update[26]="CREATE TABLE `MFRData` (
ID bigint(20) NOT NULL,  
Prueflos1 bigint(20) NOT NULL, 
Prueflos2 bigint(20) NOT NULL, 
material bigint(20) NOT NULL,
Bemerkungen varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
 `mfi` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  `mfibis` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  `mfi2` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  `mfibis2` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  `mfimethode` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
  `mfimethode2` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
Geschaumt varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
BemerkungenAuswertung varchar(100) COLLATE utf8_unicode_ci NOT NULL,  
Abgezogen varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
AbgezogeneMenge varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
MFRm1 varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
MFRm2 varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
MFRm3 varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
MFRm4 varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
MFRm5 varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
MFRm6 varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
MFRabchnitt varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
MFRSekunden varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
Kugelkonstante varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
AblesungsWert varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
ErechneteKonstante varchar(20) COLLATE utf8_unicode_ci NOT NULL,  
PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='MFR Data';

";

    $update[27] = "CREATE TABLE `midworkflow` (
  `wfid` bigint(20) NOT NULL,
  `fid` bigint(20) NOT NULL,
  `wuser` bigint(20) NOT NULL,
  `wdatum` datetime NOT NULL,
  `wstate` int(11) NOT NULL,
  `lokz` tinyint(4) NOT NULL,
  `wnotes` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `werk` int(11) NOT NULL,
  PRIMARY KEY (`wfid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='midworkflow';
";

    $update[28] = "ALTER TABLE  `midworkflow` CHANGE  `wfid`  `wfid` BIGINT( 20 ) NOT NULL AUTO_INCREMENT";

    $update[29] = "CREATE TABLE IF NOT EXISTS `messwertmeldungen` (
    `fid` bigint(20) NOT NULL,
    `sap` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
    `fauf` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
    `gebindeid` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
    `material` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
    `aussenlager` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
    `verpackungsart` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
    `verpackungsgewicht` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
    `produkt` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
    `charge` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
    `linie` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
    `extrusion` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
    `fertigungsauftrag` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
    `gewicht` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
    
    `prozesshinweise` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
    `inputquali` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
    
    `m1` varchar(20) COLLATE utf8_unicode_ci NOT NULL, 
    `m2` varchar(20) COLLATE utf8_unicode_ci NOT NULL, 
    `m3` varchar(20) COLLATE utf8_unicode_ci NOT NULL, 
    `m4` varchar(20) COLLATE utf8_unicode_ci NOT NULL, 
    `m5` varchar(20) COLLATE utf8_unicode_ci NOT NULL, 
    `m6` varchar(20) COLLATE utf8_unicode_ci NOT NULL, 
    `m7` varchar(20) COLLATE utf8_unicode_ci NOT NULL, 
    `m8` varchar(20) COLLATE utf8_unicode_ci NOT NULL, 
    `m9` varchar(20) COLLATE utf8_unicode_ci NOT NULL, 
    `m10` varchar(20) COLLATE utf8_unicode_ci NOT NULL, 
    
    `farbel` varchar(20) COLLATE utf8_unicode_ci NOT NULL, 
    `farbea` varchar(20) COLLATE utf8_unicode_ci NOT NULL, 
    `farbeb` varchar(20) COLLATE utf8_unicode_ci NOT NULL, 
    `strang` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
    `farbel1` varchar(20) COLLATE utf8_unicode_ci NOT NULL, 
    `farbea1` varchar(20) COLLATE utf8_unicode_ci NOT NULL, 
    `farbeb1` varchar(20) COLLATE utf8_unicode_ci NOT NULL, 
    
    `zugdehnung` varchar(20) COLLATE utf8_unicode_ci NOT NULL, 
    `zugfestigkeit` varchar(20) COLLATE utf8_unicode_ci NOT NULL, 
    `dichte` varchar(20) COLLATE utf8_unicode_ci NOT NULL, 
        
    `mfi` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
    `mfibis` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
    `mfi2` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
    `mfibis2` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
    `mfimethode` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
    `mfimethode2` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
    `emodul` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
    `emodulbis` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
    `farbe` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
    `farbebis` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
    `schlag` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
    `schlagbis` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
    
    `ifeuchte` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
    `ifeuchtebis` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
    `afeuchte` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
    `afeuchtebis` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
    
    `ende` varchar(255) COLLATE utf8_unicode_ci NOT NULL,  
    
    `ftype` int(11) NOT NULL,
    `fdatum` datetime NOT NULL,
    `fstate` int(11) NOT NULL,
    `flokz` tinyint(4) NOT NULL,
    `fnotes` varchar(255) COLLATE utf8_unicode_ci NULL,
    `fuser` int(11) NOT NULL,
    `fuserto` int(11) NOT NULL,
    `werk` int(11) NOT NULL,

  
  PRIMARY KEY (`fid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Messwertmeldungen';
";

    $update[30] = "ALTER TABLE  `messwertmeldungen` CHANGE  `fid`  `fid` BIGINT( 20 ) NOT NULL AUTO_INCREMENT";

    $update[31]="ALTER TABLE  `messwertmeldungen` 
ADD  `Feinpartikel` TINYINT( 1 ) NOT NULL DEFAULT  '0' AFTER  `afeuchtebis` ,
ADD  `Oberflachendefekte` TINYINT( 1 ) NOT NULL DEFAULT  '0' AFTER  `Feinpartikel` ,
ADD  `Materialunvertraglichkeit` TINYINT( 1 ) NOT NULL DEFAULT  '0' AFTER  `Oberflachendefekte` ,
ADD  `VerbranntesGranulat` TINYINT( 1 ) NOT NULL DEFAULT  '0' AFTER  `Materialunvertraglichkeit` ,
ADD  `FarbigeSilikonpartikel` TINYINT( 1 ) NOT NULL DEFAULT  '0' AFTER  `VerbranntesGranulat` ,
ADD  `Materialubergange` TINYINT( 1 ) NOT NULL DEFAULT  '0' AFTER  `FarbigeSilikonpartikel` ,
ADD  `Farbausfall` TINYINT( 1 ) NOT NULL DEFAULT  '0' AFTER  `Materialubergange` ;";

    $update[32]="ALTER TABLE  `messwertmeldungen` ADD  `mtype` TINYINT( 4 ) NOT NULL DEFAULT  '1',
ADD  `mdevice` VARCHAR( 100 ) NOT NULL ";



    $update[33] = "ALTER TABLE  `mitarbeiter` ADD  `email2` VARCHAR ( 100 ) NOT NULL DEFAULT  'none' ";

    $update[34]="CREATE TABLE `message_tbl` (
  `messageid` bigint(20) NOT NULL AUTO_INCREMENT,
  `fnotes1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fnotes2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fnotes3` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fnotes4` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `m1` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `m2` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `m3` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `m4` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `m5` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `m6` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `m7` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `m8` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `m9` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `m10` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `m11` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `m12` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `m13` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `m14` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `m15` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `m16` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `m17` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `m18` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `m19` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `m20` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `m21` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `m22` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `m23` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `m24` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `m25` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `m26` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `m27` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `m28` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `m29` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `m30` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `fdatum1` datetime NOT NULL,
  `fdatum2` datetime NOT NULL,
  `fdatum3` datetime NOT NULL,
  `fdatum4` datetime NOT NULL,
  
  `ftype` int(11) NOT NULL,
  `fdatum` datetime NOT NULL,
  `fstate` int(11) NOT NULL,
  `flokz` tinyint(4) NOT NULL,
  `fnotes` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fuser` int(11) NOT NULL,
  `fuserto` int(11) NOT NULL,
  `werk` int(11) NOT NULL,
  `mtype` tinyint(4) NOT NULL DEFAULT '1',
  `mdevice` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`messageid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Messages' AUTO_INCREMENT=1; ";


    foreach ($update as $i => $step) {
        $mysqli->query($step);
        if ($mysqli->error) {
            AddSessionMessage("warning", "Fehler im Update $i.", "Update $i");
        }
    }
    AddSessionMessage("success","Softwareupdate durchgeführt.","Update");
    return "Software update fertig.";
}





function DoImportData($limit){
    include("../apps/dbconnection.php");
    $anzahl=200;
    $limit2=$limit+$anzahl;
    AddSessionMessage("success","Import ".$limit." bis ". $limit2." gestartet.","Import");

    $selectSQL="SELECT count(ImportGebindeData.IDGebinde) AS anzahl FROM ImportGebindeData ";
    $mysqli = new mysqli($hostname_datenbank, $username_datenbank, $password_datenbank, $database_datenbank);
    $rst=$mysqli->query($selectSQL);
    if ($mysqli->affected_rows>0) {
        $row_rst = $rst->fetch_assoc();
        $all = $row_rst['anzahl'];
    }

    $selectSQL="SELECT ImportGebindeData.IDGebinde,ImportGebindeData.ProductionsDatum ,ImportGebindeData.SchichtzeitID  
FROM ImportGebindeData LIMIT $limit, $limit2";

    $mysqli = new mysqli($hostname_datenbank, $username_datenbank, $password_datenbank, $database_datenbank);
    $rst=$mysqli->query($selectSQL);
    if ($mysqli->error) {
        AddSessionMessage("warning","Import wurde nicht geladen, da ein Fehler aufgetreten ist.","Fehlermeldung");
    }else{
        if ($mysqli->affected_rows>0) {
            $i=0;
            $x=$mysqli->affected_rows;
            $row_rst = $rst->fetch_assoc();
            do{
                $i++;
                $selectSQL1="SELECT *  FROM fertigungsmeldungen WHERE fertigungsmeldungen.gebindeid='".$row_rst['IDGebinde']."' LIMIT 1";
                $mysqli = new mysqli($hostname_datenbank, $username_datenbank, $password_datenbank, $database_datenbank);
                $rstf=$mysqli->query($selectSQL1);
                if ($mysqli->error) {

                }else {
                    if ($mysqli->affected_rows>0) {
                        $a = 0;
                        $row_rstf = $rstf->fetch_assoc();
                        // AddSessionMessage("success","Gebinde ".$row_rstf['gebindeid'] ,"Gebinde gefunden");

                        $selectSQL3="SELECT *  FROM ImportGebindeData WHERE ImportGebindeData.IDGebinde='".$row_rstf['gebindeid']."' LIMIT 1";
                        $mysqli = new mysqli($hostname_datenbank, $username_datenbank, $password_datenbank, $database_datenbank);
                        $rst3=$mysqli->query($selectSQL3);

                        if ($mysqli->affected_rows>0) {
                            $row_rst3 = $rst3->fetch_assoc();

                            $selectSQL4="SELECT *  FROM ImportChargenData WHERE ImportChargenData.ID='".$row_rst3['ChargeID']."' LIMIT 1";
                            $mysqli = new mysqli($hostname_datenbank, $username_datenbank, $password_datenbank, $database_datenbank);
                            $rst4=$mysqli->query($selectSQL4);

                            if ($mysqli->affected_rows>0) {
                                $row_rst4 = $rst4->fetch_assoc();

                                $state = 9;
                                $f = new ifisFid($row_rstf['fid']);
                                //$f->ChangeLog("Cancel Import data form " . date('Y-m-d H:i') . ". Data already exits", $state);
                                //$f->ChangeLog("gehört zu Charge " . $row_rst4['Charge'] . " (ID".$row_rst3['ChargeID'].") ", 8);
                                $f->UpdateMaterial(GetMaterialName('material',$row_rst4['ArtikelnummerID']));
                                $f->UpdateFnotes($row_rst4['Charge']);
                                $f->UpdateEndedatum(date("Y-m-d H:i:s",$row_rst3['Zeit']));

                                // AddSessionMessage("success","Charge ".$row_rst4['Charge'] ,"Charge gefunden");
                            }
                        }

                    }else{

                        $selectSQL3="SELECT *  FROM ImportGebindeData WHERE ImportGebindeData.IDGebinde='".$row_rst['IDGebinde']."' LIMIT 1";
                        $mysqli3 = new mysqli($hostname_datenbank, $username_datenbank, $password_datenbank, $database_datenbank);
                        $rst3=$mysqli->query($selectSQL3);
                        if ($mysqli->error) {
                            AddSessionMessage("error","Importdaten konnten nicht geladen werden.","Fehlermeldung");
                        }else {

                            $row_rst3 = $rst3->fetch_assoc();
                           $updateSQL2 = sprintf("
                                INSERT INTO  fertigungsmeldungen (gebindeid, sap,`fauf`, material,linie, gewicht,ende,fstate,ftype,
                                 verpackungsart,inputquali, prozesshinweise, mfi, mfibis, emodul, emodulbis, farbe, farbebis,
                                 farbel,farbea,farbeb,strang, zugdehnung,zugfestigkeit,dichte,
                                 
                                 Feinpartikel,Oberflachendefekte,Materialunvertraglichkeit,VerbranntesGranulat,FarbigeSilikonpartikel,Materialubergange,Farbausfall,
                                 
                                 schlag, schlagbis,
                                 ifeuchte, ifeuchtebis, afeuchte, afeuchtebis, mfi2, mfibis2, mfimethode, mfimethode2,
                                 fnotes, fdatum, fuser, werk) 
                                VALUES (
                                %s, %s,%s,%s,%s,%s,%s,
                                %s, %s,%s,%s,%s,%s,%s,
                                %s, %s,%s,%s,%s,%s,%s,
                                %s,%s,%s,%s,%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                                GetSQLValueString($row_rst3['IDGebinde'], "text"),
                                GetSQLValueString($row_rst3['sap'], "text"),
                                GetSQLValueString($row_rst3['fauf'], "text"),
                                GetSQLValueString($row_rst3['material'], "text"),
                                GetSQLValueString($row_rst3['linie'], "text"),
                                GetSQLValueString($row_rst3['Gewicht'], "text"),
                                GetSQLValueString($row_rst3['ende'], "text"),
                                GetSQLValueString(0, "int"),
                                GetSQLValueString($row_rst3['ftype'], "text"),

                                GetSQLValueString($row_rst3['VerpackungID'], "text"),
                                GetSQLValueString($row_rst3['InputID'], "text"),
                                GetSQLValueString($row_rst3['QualitatsKommentar'], "text"),
                                GetSQLValueString($row_rst3['MFRAuswertung'], "text"),
                                GetSQLValueString($row_rst3['MFRID2'], "text"),
                                GetSQLValueString($row_rst3['EModul'], "text"),
                                GetSQLValueString($row_rst3['emodulbis'], "text"),
                                GetSQLValueString($row_rst3['FarbeinstufungVisuellID'], "text"),
                                GetSQLValueString($row_rst3['FarbeID'], "text"),

                               GetSQLValueString($row_rst3['HelligkeitswertL'], "text"),
                               GetSQLValueString($row_rst3['FarbwertA'], "text"),
                               GetSQLValueString($row_rst3['FarbwertB'], "text"),
                               GetSQLValueString($row_rst3['Strang'], "text"),
                               GetSQLValueString($row_rst3['Zugdehnung'], "text"),
                               GetSQLValueString($row_rst3['Zugfestigestigkeit'], "text"),
                               GetSQLValueString($row_rst3['Dichte'], "text"),


                               GetSQLValueString($row_rst3['Feinpartikel'], "int"),
                               GetSQLValueString($row_rst3['Oberflachendefekte'], "int"),
                               GetSQLValueString($row_rst3['Materialunvertraglichkeit'], "int"),
                               GetSQLValueString($row_rst3['VerbranntesGranulat'], "int"),
                               GetSQLValueString($row_rst3['FarbigeSilikonpartikel'], "int"),
                               GetSQLValueString($row_rst3['Materialubergange'],"int"),
                               GetSQLValueString($row_rst3['Farbausfall'], "int"),

                                GetSQLValueString($row_rst3['CharpyKerbschlag'], "text"),
                                GetSQLValueString($row_rst3['schlagbis'], "text"),

                                GetSQLValueString($row_rst3['ifeuchte'], "text"),
                                GetSQLValueString($row_rst3['ifeuchtebis'], "text"),
                                GetSQLValueString($row_rst3['AussereRestfeuchte'], "text"),
                                GetSQLValueString($row_rst3['afeuchtebis'], "text"),

                                GetSQLValueString($row_rst3['mfi2'], "text"),
                                GetSQLValueString($row_rst3['mfibis2'], "text"),
                                GetSQLValueString($row_rst3['MFRID'], "text"),
                                GetSQLValueString($row_rst3['mfimethode2'], "text"),

                                GetSQLValueString($row_rst3['fnotes'], "text"),
                                GetSQLValueString(date('Y-m-d H:i:s',$row_rst3['Zeit']), "text"),
                                GetSQLValueString($_SESSION['userid'], "int"),
                                GetSQLValueString($_SESSION['classifiedDirectoryID'], "int")
                            );
                            $mysqli = new mysqli($hostname_datenbank, $username_datenbank, $password_datenbank, $database_datenbank);
                            $mysqli->query($updateSQL2);

                            $f = new ifisFid($mysqli->insert_id);
                            $f->ChangeLog("Import data from " . date('Y-m-d H:i'), 1);
                            $f->ChangeLog("Herstellungsdatum " . date('Y-m-d H:i:s',$row_rst3['Zeit']), 2);
                            $f->ChangeLog("Verwendungsentscheid als " . $row_rst3['QualitatID'], 8);


                            $selectSQL4="SELECT *  FROM ImportChargenData WHERE ImportChargenData.ID='".$row_rst3['ChargeID']."' LIMIT 1";
                            $mysqli = new mysqli($hostname_datenbank, $username_datenbank, $password_datenbank, $database_datenbank);
                            $rst4=$mysqli->query($selectSQL4);

                            if ($mysqli->affected_rows>0) {
                                $row_rst4 = $rst4->fetch_assoc();
                                $f->ChangeLog("gehört zu Charge " . $row_rst4['Charge'] . " ", 8);
                                $f->ChangeLog("Qualitätsprüfung Produktgruppe " . GetMaterialName('text',$row_rst4['ProduktgruppeID']), 7,"",date("Y-m-d H:i:s",$row_rst3['Zeit']));
                                $f->ChangeLog("Verwendungsentscheid nach Chargenprüfung " . $row_rst4['QualitatID'], 7,"", date("Y-m-d H:i:s",$row_rst3['Zeit']+(24*60*60)) );
                                $f->ChangeLog("im Lager unter Material-Nr. " . $row_rst4['ArtikelnummerID'], 8,"",date("Y-m-d H:i:s",$row_rst3['Zeit']+(36*60*60)) );

                                $f->UpdateMaterial(GetMaterialName('material',$row_rst4['ArtikelnummerID']));
                                $f->UpdateFnotes($row_rst4['Charge']);
                                $f->UpdateEndedatum(date("Y-m-d H:i:s",$row_rst3['Zeit']));

                                //AddSessionMessage("warning","Charge ".$row_rst4['Charge'] ,"Charge gefunden");
                            }

                            $f->ChangeLog("Warenausgang aufgrund Inventur 05/2017 " . date('Y-m-d H:i'), 9);
                            //AddSessionMessage("success","FID (".$mysqli->insert_id.") importiert","Import");
                        }
                    }
                }



            }while ($row_rst=$rst->fetch_assoc() and ($i<$anzahl));
            AddSessionMessage("success",$i."x Import Gebinde and Verlaufsdaten (noch offen ".$x." von ".$all.")","Fertig");
        }
    }
    $mysqli->close();


    $_SESSION['limit']=$limit2;
    $_SESSION['recordstoimport']=$x;
    $_SESSION['allrecordstoimport']=$all;

    return 1;
}

function DoRepairImportData(){
    include("../apps/dbconnection.php");

    AddSessionMessage("success","Import ".$limit." bis ". $limit2." gestartet.","Import");
    $selectSQL="SELECT ImportGebindeData.IDGebinde,ImportGebindeData.ProductionsDatum ,ImportGebindeData.SchichtzeitID  
FROM ImportGebindeData";

    $mysqli = new mysqli($hostname_datenbank, $username_datenbank, $password_datenbank, $database_datenbank);
    $rst=$mysqli->query($selectSQL);
    if ($mysqli->error) {
        AddSessionMessage("warning","Import wurde nicht geladen, da ein Fehler aufgetreten ist.","Fehlermeldung");
    }else{
        if ($mysqli->affected_rows>0) {
            $i=0;
            $row_rst = $rst->fetch_assoc();
            do{
                $i++;
                $str=$row_rst['ProductionsDatum'];
                if ($str=="") {
                    AddSessionMessage("error","Gebinde ".$row_rst['IDGebinde']." fehlerhaft, da kein Datum vorhanden.","Fehlermeldung");
                }
                switch ($row_rst['SchichtzeitID'][0]){
                    case "S":    {$schicht=" 18:00:00 ";break;}
                    case "F":    {$schicht=" 11:00:00 ";break;}
                    case "N":   {$schicht=" 23:00:00 ";break;}
                    default:{$schicht=" 06:00:00";}
                }

                $updateSQL = sprintf("
                UPDATE ImportGebindeData SET Zeit=%s WHERE IDGebinde=%s",
                    GetSQLValueString(strtotime(substr($str,6,4)."-".substr($str,3,2)."-".substr($str,0,2).$schicht),"text"),
                    $row_rst['IDGebinde']);
                $mysqli->query($updateSQL);
                if ($mysqli->error) {
                    AddSessionMessage("error","Reparatur fehlerhaft, da ein Fehler aufgetreten ist.","Fehlermeldung");
                }




            }while ($row_rst=$rst->fetch_assoc());
            AddSessionMessage("success",$i."x Reparatur Fertigungsdatum ","Fertig");
        }
    }
    $mysqli->close();



    return 1;
}


function DoRepairUsernamesData($limit,$j=0){
    include("../apps/dbconnection.php");
    $job[0]=array(0=>"PruferMFR",   1=>"MFR Prüfung",  2=>10);
    $job[1]=array(0=>"PruferRF",    1=>"RF-Prüfung",   2=>11);
    $job[2]=array(0=>"PruferMA2",   1=>"MA2-Prüfung",  2=>12);
    $job[3]=array(0=>"PruferMA",    1=>"MA-Prüfung",   2=>13);
    $job[4]=array(0=>"PruferF",     1=>"Farbprüfung",  2=>14);
    $job[5]=array(0=>"PruferQ",     1=>"Q-Freigabe",   2=>15);

    AddSessionMessage("success", "Update ".$job[$j][1]." ","Job #".$j);

    $anzahl=1;
    $limit2=$limit+$anzahl;

    $selectSQL="SELECT count(ImportGebindeData.IDGebinde) AS anzahl FROM ImportGebindeData WHERE ImportGebindeData.".$job[$j][0]."<>''";
    $mysqli = new mysqli($hostname_datenbank, $username_datenbank, $password_datenbank, $database_datenbank);
    $rst1=$mysqli->query($selectSQL);
    if ($mysqli->affected_rows>0) {
        $row_rst = $rst1->fetch_assoc();
        $all = $row_rst['anzahl'];
        $_SESSION['allrecordstoimport']=$all;
    }

    AddSessionMessage("success","Import ".$limit." bis ". $limit2." gestartet.","Import");

    $selectSQL="SELECT ImportGebindeData.IDGebinde, ImportGebindeData.".$job[$j][0]."
            FROM ImportGebindeData WHERE ImportGebindeData.".$job[$j][0]."<>'' LIMIT $limit, $limit2";

    $mysqli = new mysqli($hostname_datenbank, $username_datenbank, $password_datenbank, $database_datenbank);
    $rst=$mysqli->query($selectSQL);
    if ($mysqli->error) {
        AddSessionMessage("warning","Import wurde nicht geladen, da ein Fehler aufgetreten ist.","Fehlermeldung");
    }else{
        if ($mysqli->affected_rows>0) {
            $i=0;
            $yy=0;
            $x=$mysqli->affected_rows;
            $row_rst = $rst->fetch_assoc();
            do{
                $i++;
                $selectSQL1="SELECT *  FROM fertigungsmeldungen WHERE fertigungsmeldungen.gebindeid='".$row_rst['IDGebinde']."' LIMIT 0,1";
                $mysqli1 = new mysqli($hostname_datenbank, $username_datenbank, $password_datenbank, $database_datenbank);
                $rstf=$mysqli1->query($selectSQL1);

                if ($mysqli1->error) {

                }else {
                    if ($mysqli1->affected_rows>0) {
                        if (strlen($row_rst["".$job[$j][0].""])>0){
                            $u=new ifisUser(0);
                            $r=$u->LoadDataByShortName(trim($row_rst["".$job[$j][0].""]));
                            if ($r>0){
                                $row_rstf=$rstf->fetch_assoc();
                                $f=new ifisFid($row_rstf['fid']);
                                if ($f->LoadHistory($job[$j][2])==0) {
                                    $f->ChangeLog($job[$j][1]." am " . $row_rstf['fdatum'], $job[$j][2], "", $row_rstf['fdatum'], $u->userid);
                                    $x++;
                                    AddSessionMessage("success", "FID " . $row_rstf['fid'], "Mitarbeiter ".$job[$j][1]." identifiziert");
                                }else{
                                    AddSessionMessage("warning", "Message ID ".$job[$j][1]." für GebindeID ". $row_rstf['fid'], " bereits vorhanden");
                                }
                            } else{
                                $yy++;
                                AddSessionMessage("warning", "FID " . $row_rstf['fid'], "Mitarbeiter ".$row_rst["".$job[$j][0].""]." in Quelldaten nicht gespeichert");
                            }
                        }else{
                            AddSessionMessage("error", "FID " . $row_rstf['fid']." mit Gebinde ID ".$row_rst['IDGebinde'], "nicht gefunden");
                        }
                    }
                }

            }while ($row_rstf=$rstf->fetch_assoc() && ($i < $anzahl));
            AddSessionMessage("success",$i."x Reparatur Mitarbeiter QS-Prüfer (updated: $limit von $all ) $yy nicht gefunden - $x angelegt","Fertig");
        }
    }
    $mysqli->close();


    $_SESSION['limit']=$limit2;
    $_SESSION['recordstoimport']=$x;



    return 1;
}



function DoCleanMaterialNames(){
    include ("../apps/dbconnection.php");
    $sql="SELECT * FROM materialdaten ORDER BY materialdaten.materialtxt ASC";
    $rst=$mysqli->query($sql);
    if ($mysqli->affected_rows>0) {
        $i = 0;
        $row_rst = $rst->fetch_assoc();
        do {

            $i++;
            $updateSQL = sprintf("
                UPDATE materialdaten SET 
                materialtxt=%s, color=%s,  warengruppe=%s,  produktgruppe=%s
                WHERE idmaterial=%s",

                GetSQLValueString(utf8_decode(utf8_encode($row_rst['materialtxt'])), "text"),
                GetSQLValueString(utf8_decode(utf8_encode($row_rst['color'])), "text"),
                GetSQLValueString(utf8_decode(utf8_encode($row_rst['warengruppe'])), "text"),
                GetSQLValueString(utf8_decode(utf8_encode($row_rst['produktgruppe'])), "text"),
                $row_rst['idmaterial']);
            $mysqli->query($updateSQL);


        } while ($row_rst = $rst->fetch_assoc());
        AddSessionMessage("success", $i . "x Reparatur Materialnamen ", "Fertig");
    } else{
        AddSessionMessage("error", "keine Materialnummer gefunden", "Fehlermeldung");
    }
    return 1;
}



function DoInventur($limit){
    $anzahl=1;
    $limit2=$limit+$anzahl;
    include ("../apps/dbconnection.php");


    $selectSQL="SELECT count(fertigungsmeldungen.fid) AS anzahl FROM fertigungsmeldungen";
    $mysqli = new mysqli($hostname_datenbank, $username_datenbank, $password_datenbank, $database_datenbank);
    $rst1=$mysqli->query($selectSQL);
    if ($mysqli->affected_rows>0) {
        $row_rst = $rst1->fetch_assoc();
        $all = $row_rst['anzahl'];
        $_SESSION['allrecordstoimport']=$all;
    }

    AddSessionMessage("success","Import ".$limit." bis ". $limit2." gestartet.","Import");

    $sql="SELECT * FROM fertigungsmeldungen ORDER BY fertigungsmeldungen.fid ASC LIMIT $limit,$limit2";
    $rst=$mysqli->query($sql);
    if ($mysqli->affected_rows>0) {
        $i = 0;
        $x=$mysqli->affected_rows;
        $row_rst = $rst->fetch_assoc();
        do {

            $i++;
            $f= new ifisFid($row_rst['fid']);
            $f->ChangeLog("Warenausgang aufgrund Inventur" . date('Y-m-d H:i'), 999);

        } while ($row_rst = $rst->fetch_assoc() && $i<$anzahl);
        AddSessionMessage("success", $i . "x Inventur FID  ( $x von ".$all." )", "Inventurbuchung");
    } else{
        AddSessionMessage("error", "keine FID gefunden", "Fehlermeldung");
    }

    $mysqli->close();
    $_SESSION['limit']=$limit2;
    $_SESSION['recordstoimport']=$x;
    return 1;
}


function DoMitarbeiterUpdate($limit){
    $anzahl=1;
    $limit2=$limit+$anzahl;
    include ("../apps/dbconnection.php");


    $selectSQL="SELECT count(mitarbeiter.userid) AS anzahl FROM mitarbeiter";
    $mysqli = new mysqli($hostname_datenbank, $username_datenbank, $password_datenbank, $database_datenbank);
    $rst1=$mysqli->query($selectSQL);
    if ($mysqli->affected_rows>0) {
        $row_rst = $rst1->fetch_assoc();
        $all = $row_rst['anzahl'];
        $_SESSION['allrecordstoimport']=$all;
    }

    AddSessionMessage("success","Mitarbeiterüberprüfung ".$limit." bis ". $limit2." gestartet.","Check");

    $sql="SELECT * FROM mitarbeiter ORDER BY mitarbeiter.userid ASC LIMIT $limit,$limit2";
    $mysqli = new mysqli($hostname_datenbank, $username_datenbank, $password_datenbank, $database_datenbank);
    $rst=$mysqli->query($sql);
    if ($mysqli->affected_rows>0) {
        $i = 0;
        $x=$mysqli->affected_rows;
        $row_rst = $rst->fetch_assoc();
        do {

            $i++;
            $f= new ifisUser($row_rst['userid']);
            $f->UpdateEmail2();

        } while ($row_rst = $rst->fetch_assoc() && $i<$anzahl);
        AddSessionMessage("success", $i . " ".$row_rst['email']." ( $x von ".$all." )", "Überprüfung");
    } else{
        AddSessionMessage("error", "keine UserID gefunden", "Fehlermeldung");
    }

    $mysqli->close();
    $_SESSION['limit']=$limit2;
    $_SESSION['recordstoimport']=$x;
    return 1;
}