<?php
/**
 * Created by PhpStorm.
 * User: andreasveltjens
 * Date: 25.12.16
 * Time: 18:54
 */
if ($w->data['fid']>0) {

} else{
    $w->data['gebindeid']=$w->GetNextGebindeId();
    $w->data['fauf']= $_SESSION['last_faufid'];
    $w->data['ende']=date('Y-d-m',time());
}
?>

<h4 class="header-title m-t-0 m-b-30">Rückmeldung Gebinde-ID  <?php echo  $w->data['gebindeid']; ?> bearbeiten</h4>

<form method="post"  class="form-horizontal" role="form" data-parsley-validate novalidate>
    <input type="hidden" name="fid" value="<?php echo $w->fid; ?>">
    <div class="form-group">
        <label for="ftype" class="col-sm-4 control-label">Art der Gebindeherstellung*</label>
        <div class="col-sm-7">
            <select  required class="form-control" name="ftype" id="ftype">
                <?php echo GetListFidType("select",$w->data['ftype']); ?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="wdesc" class="col-sm-4 control-label">FAUF*</label>
        <div class="col-sm-7">
            <input type="text" required parsley-type="text" class="form-control" name="fauf" id="fauf" placeholder="Fertigungsauftragsnummer" value="<?php echo $w->data['fauf']; ?>">
        </div>
    </div>



    <div class="form-group">
        <label for="material" class="col-sm-4 control-label">Materialbezeichnung*</label>
        <div class="col-sm-7">
            <select  required parsley-type="text" class="form-control select2" name="material" id="material" >
                <?php echo GetMaterialName("select2", $w->data['material']); ?>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label for="material" class="col-sm-4 control-label">SAP Materialnummer</label>
        <div class="col-sm-7">
            <input  type="text" class="form-control" name="sap" id="sap" placeholder="SAP Materialnummer" value="<?php echo $w->data['sap']; ?>">
        </div>
    </div>

    <div class="form-group">
        <label for="ende" class="col-sm-4 control-label">Fertigungsdatum*</label>
        <div class="col-sm-7">
            <input  type="date" required parsley-type="date" class="form-control" name="ende" id="ende" placeholder="YYYY-MM-DD" value="<?php echo $w->data['ende']; ?>">
        </div>
    </div>

    <div class="form-group">
        <label for="gewicht" class="col-sm-4 control-label">Menge*</label>
        <div class="col-sm-7">
            <input  type="text" required parsley-type="text" class="form-control" name="gewicht" id="gewicht" placeholder="Menge" value="<?php echo $w->data['gewicht']; ?>">
        </div>
    </div>

    <div class="form-group">
        <label for="verpackungsart" class="col-sm-4 control-label">Verpackung*</label>
        <div class="col-sm-7">
            <input  type="text" required parsley-type="text" class="form-control" name="verpackungsart" id="verpackungsart" placeholder="Verpackungsart (bb, Silo, Oktabin)" value="<?php echo $w->data['verpackungsart']; ?>">
        </div>
    </div>

    <div class="form-group">
        <label for="linie" class="col-sm-4 control-label">Anlage/ Extuder / Linie*</label>
        <div class="col-sm-7">
            <input  type="text" required parsley-type="text" class="form-control" name="linie" id="linie" placeholder="Anlage" value="<?php echo $w->data['linie']; ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="fstate" class="col-sm-4 control-label">Gebinde-ID Status*</label>
        <div class="col-sm-7">
            <select  required class="form-control" name="fstate" id="fstate">
                <?php echo GetListFidStatus("select",$w->data['fstate']); ?>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label for="fnotes" class="col-sm-4 control-label">Bemerkungen / Kunde</label>
        <div class="col-sm-7">
            <input  type="text" class="form-control" name="fnotes" id="fnotes" placeholder="Bemerkungen" value="<?php echo $w->data['fnotes']; ?>">
        </div>
    </div>
    <hr>
    <h4>Verabeitungshinweise</h4>
    <div class="form-group">
        <label for="inputquali" class="col-sm-4 control-label">Inputqualität</label>
        <div class="col-sm-7">
            <input  type="text" class="form-control" name="inputquali" id="inputquali" placeholder="Sortieranlage" value="<?php echo $w->data['inputquali']; ?>">
        </div>
    </div>

    <div class="form-group">
        <label for="prozesshinweise" class="col-sm-4 control-label">Prozessfestlegung</label>
        <div class="col-sm-7">
            <textarea  rows="5" class="form-control" name="prozesshinweise" id="prozesshinweise" placeholder="Bemerkungen"><?php echo $w->data['prozesshinweise']; ?></textarea>
        </div>
    </div>

    <h4>Werkstoffeigenschaften bei Mahlgut</h4>

    <div class="form-group">
        <label for="schuettdichte" class="col-sm-4 control-label">Schüttdichte</label>
        <div class="col-sm-7">
            <input  type="text" class="form-control" name="schuettdichte" id="schuettdichte" placeholder="Schüttdichte" value="<?php echo $w->data['schuettdichte']; ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="afeuchte" class="col-sm-4 control-label">Restfeuchte <?php echo "(SOLL ".$w->soll['afeuchte'].")"; ?></label>
        <div class="col-sm-7">
            <input  type="text" class="form-control" name="afeuchte" id="afeuchte" placeholder="-" value="<?php echo $w->data['afeuchte']; ?>">
        </div>

    </div>


    <h4>Werkstoffeigenschaften bei Granulat</h4>

    <div class="form-group">
        <label for="strang" class="col-sm-4 control-label">Strang</label>
        <div class="col-sm-7">
            <input  type="text" class="form-control" name="strang" id="strang" placeholder="Stangqualität" value="<?php echo $w->data['strang']; ?>">
        </div>

    </div>

    <div class="form-group">
        <label for="dichte" class="col-sm-4 control-label">Dichte</label>
        <div class="col-sm-7">
            <input  type="text" class="form-control" name="dichte" id="dichte" placeholder="Dichte" value="<?php echo $w->data['dichte']; ?>">
        </div>

    </div>

    <div class="form-group">
        <label for="mfimethode" class="col-sm-4 control-label">MFI- Prüfmethode </label>
        <div class="col-sm-7">
            <input  type="text" class="form-control" name="mfimethode" id="mfimethode" placeholder="Methode" value="<?php echo $w->data['mfimethode']; ?>">
        </div>

    </div>
    <div class="form-group">
        <label for="mfi" class="col-sm-4 control-label">MFI IST <?php echo "(SOLL ".$w->soll['mfi'].")"; ?></label>
        <div class="col-sm-7">
            <input  type="text" class="form-control" name="mfi" id="mfi" placeholder="min. MFI" value="<?php echo $w->data['mfi']; ?>">
        </div>

    </div>
    <h4>Farbe</h4>
    <div class="form-group">
        <label for="farbel" class="col-sm-4 control-label">Helligkeit</label>
        <div class="col-sm-7">
            <input  type="text" class="form-control" name="farbel" id="farbel" placeholder="Helligkeit" value="<?php echo $w->data['farbel']; ?>">
        </div>
    </div>

    <div class="form-group">
        <label for="farbea" class="col-sm-4 control-label">Farbwert a</label>
        <div class="col-sm-7">
            <input  type="text" class="form-control" name="farbea" id="farbea" placeholder="Farbwert a" value="<?php echo $w->data['farbea']; ?>">
        </div>
    </div>

    <div class="form-group">
        <label for="farbeb" class="col-sm-4 control-label">Farbwert b</label>
        <div class="col-sm-7">
            <input  type="text" class="form-control" name="farbeb" id="farbeb" placeholder="Farbwert b" value="<?php echo $w->data['farbeb']; ?>">
        </div>
    </div>

    <div class="form-group">
        <label for="farbe" class="col-sm-4 control-label">Farbe IST <?php echo "(SOLL ".$w->soll['farbe'].")"; ?></label>
        <div class="col-sm-7">
            <input  type="text" class="form-control" name="farbe" id="farbe" placeholder="-L " value="<?php echo $w->data['farbe']; ?>">
        </div>

    </div>




    <h4>Mechanik</h4>
    <div class="form-group">
        <label for="emodul" class="col-sm-4 control-label">E-Modul IST <?php echo "(SOLL ".$w->soll['emodul'].")"; ?></label>
        <div class="col-sm-7">
            <input  type="text" class="form-control" name="emodul" id="emodul" placeholder="min. E-Modul" value="<?php echo $w->data['emodul']; ?>">
        </div>

    </div>

    <div class="form-group">
        <label for="schlag" class="col-sm-4 control-label">Schlag IST <?php echo "(SOLL ".$w->soll['schlag'].")"; ?></label>
        <div class="col-sm-7">
            <input  type="text" class="form-control" name="schlag" id="schlag" placeholder="-" value="<?php echo $w->data['schlag']; ?>">
        </div>

    </div>

    <div class="form-group">
        <label for="zugdehnung" class="col-sm-4 control-label">Zugdehnung IST <?php echo "(SOLL ".$w->soll['zugdehnung'].")"; ?></label>
        <div class="col-sm-7">
            <input  type="text" class="form-control" name="zugdehnung" id="zugdehnung" placeholder="-" value="<?php echo $w->data['zugdehnung']; ?>">
        </div>

    </div>
    <div class="form-group">
        <label for="zugfestigkeit" class="col-sm-4 control-label">Zugfestigkeit IST <?php echo "(SOLL ".$w->soll['schlag'].")"; ?></label>
        <div class="col-sm-7">
            <input  type="text" class="form-control" name="zugfestigkeit" id="zugfestigkeit" placeholder="-" value="<?php echo $w->data['zugfestigkeit']; ?>">
        </div>

    </div>

    <div class="form-group">
        <label for="ifeuchte" class="col-sm-4 control-label">innere Restfeuchte IST <?php echo "(SOLL ".$w->soll['ifeuchte'].")"; ?></label>
        <div class="col-sm-7">
            <input  type="text" class="form-control" name="ifeuchte" id="ifeuchte" placeholder="-" value="<?php echo $w->data['ifeuchte']; ?>">
        </div>

    </div>

    <h4>Qualitätsmerkmale</h4>

    <div class="checkbox checkbox-danger m-b-15">
        <input name="Feinpartikel" id="Feinpartikel" type="checkbox" value="1" <?php if ($w->data['Feinpartikel']==1){ echo "checked"; }; ?>>
        <label for="Feinpartikel">
            Feinpartikel vorhanden ?
        </label>
    </div>
    <div class="checkbox checkbox-danger m-b-15">
        <input name="Oberflachendefekte" id="Oberflachendefekte" type="checkbox" value="1" <?php if ($w->data['Oberflachendefekte']==1){ echo "checked"; }; ?>>
        <label for="Oberflachendefekte">
            Oberflächendefekte vorhanden ?
        </label>
    </div>
    <div class="checkbox checkbox-danger m-b-15">
        <input name="Materialunvertraglichkeit" id="Materialunvertraglichkeit" type="checkbox" value="1" <?php if ($w->data['Materialunvertraglichkeit']==1){ echo "checked"; }; ?>>
        <label for="Materialunvertraglichkeit">
            Materialunvertraglichkeit vorhanden ?
        </label>
    </div>
    <div class="checkbox checkbox-danger m-b-15">
        <input name="VerbranntesGranulat" id="VerbranntesGranulat" type="checkbox" value="1" <?php if ($w->data['VerbranntesGranulat']==1){ echo "checked"; }; ?>>
        <label for="VerbranntesGranulat">
            Verbranntes Granulat vorhanden ?
        </label>
    </div>
    <div class="checkbox checkbox-danger m-b-15">
        <input  name="FarbigeSilikonpartikel" id="FarbigeSilikonpartikel" type="checkbox" value="1" <?php if ($w->data['FarbigeSilikonpartikel']==1){ echo "checked"; }; ?>>
        <label for="FarbigeSilikonpartikel">
            Farbige Silikonpartikel vorhanden ?
        </label>
    </div>
    <div class="checkbox checkbox-danger m-b-15">
        <input name="Materialubergange" id="Materialubergange" type="checkbox" value="1" <?php if ($w->data['Materialubergange']==1){ echo "checked"; }; ?>>
        <label for="Materialubergange">
            Farbige Silikonpartikel vorhanden ?
        </label>
    </div>
    <div class="checkbox checkbox-danger m-b-15">
        <input name="Farbausfall"  id="Farbausfall" type="checkbox" value="1" <?php if ($w->data['Farbausfall']==1){ echo "checked"; }; ?>>
        <label for="Farbausfall">
            Farbausfall vorhanden ?
        </label>
    </div>


    <div class="form-group">
        <div class="form-group clearfix">
            <div class="col-sm-12 padding-left-0 padding-right-0">
                <input type="file" name="files[]" id="filer_input1" multiple="multiple">
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-4 col-sm-8">
            <?php if ($w->data['fid']>0) { ?>
            <button name="edit" type="submit" class="btn btn-primary waves-effect waves-light">
                Speichern
            </button>
                <button name="copy" type="submit" class="btn btn-warning waves-effect waves-light">
                    Kopieren
                </button>
            <?php } else{ ?>
            <button name="new" type="submit" class="btn btn-primary waves-effect waves-light">
                Neu anlegen
            </button><?php } ?>
            <button type="reset"
                    class="btn btn-link waves-effect m-l-5">
                Formular zurücksetzen
            </button>
        </div>
    </div>
</form>
