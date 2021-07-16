<?php
/**
 * Created by PhpStorm.
 * User: andreasveltjens
 * Date: 25.12.16
 * Time: 18:54
 */
if ($w->data['fid']>0) {

} else{
    $w->data['fauf']=$w->GetNextFauf();
    $w->data['ende']=date('Y-d-m',time()+24*60*60);
}
?>
<h4 class="header-title m-t-0 m-b-30">Fertigungsauftrag <?php echo $w->data['fauf']; ?> bearbeiten</h4>

<form method="post"  class="form-horizontal" role="form" data-parsley-validate novalidate>
    <input type="hidden" name="faufid" value="<?php echo $w->faufid; ?>">

    <div class="form-group">
        <label for="ftype" class="col-sm-4 control-label">Art der Herstellung*</label>
        <div class="col-sm-7">
            <select  required class="form-control" name="ftype" id="ftype">
                <?php echo GetListFaufType("select",$w->data['ftype']); ?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="fstate" class="col-sm-4 control-label">Auftragsstatus*</label>
        <div class="col-sm-7">
            <select  required class="form-control" name="fstate" id="fstate">
                <?php echo GetListFaufStatus("select",$w->data['fstate']); ?>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label for="material" class="col-sm-4 control-label">Material*</label>
        <div class="col-sm-7">
            <input  type="text" required parsley-type="text" class="form-control" name="material" id="material" placeholder="Material" value="<?php echo $w->data['material']; ?>">
        </div>
    </div>

    <div class="form-group">
        <label for="material" class="col-sm-4 control-label">SAP Materialnummer</label>
        <div class="col-sm-7">
            <input  type="text" class="form-control" name="sap" id="sap" placeholder="SAP Materialnummer" value="<?php echo $w->data['sap']; ?>">
        </div>
    </div>

    <div class="form-group">
        <label for="ende" class="col-sm-4 control-label">Kundenwunschtermin*</label>
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
        <label for="fnotes" class="col-sm-4 control-label">Bemerkungen / Kunde</label>
        <div class="col-sm-7">
            <input  type="text" class="form-control" name="fnotes" id="fnotes" placeholder="Bemerkungen" value="<?php echo $w->data['fnotes']; ?>">
        </div>
    </div>
    <hr>
    <h4>Verarbeitungshinweise</h4>
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
    <div id="granulat">
    <h4>Werkstoffeigenschaften bei Granulat</h4>
    <div class="form-group">
        <label for="mfi" class="col-sm-4 control-label">MFI von</label>
        <div class="col-sm-7">
            <input  type="text" class="form-control" name="mfi" id="mfi" placeholder="min. MFI" value="<?php echo $w->data['mfi']; ?>">
        </div>
        <label for="mfibis" class="col-sm-4 control-label">MFI bis</label>
        <div class="col-sm-7">
            <input  type="text" class="form-control" name="mfibis" id="mfibis" placeholder=" max. MFI" value="<?php echo $w->data['mfibis']; ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="emodul" class="col-sm-4 control-label">E-Modul von</label>
        <div class="col-sm-7">
            <input  type="text" class="form-control" name="emodul" id="emodul" placeholder="min. E-Modul" value="<?php echo $w->data['emodul']; ?>">
        </div>
        <label for="emodulbis" class="col-sm-4 control-label">E-Modul bis</label>
        <div class="col-sm-7">
            <input  type="text" class="form-control" name="emodulbis" id="emodulbis" placeholder="max. E-Modul" value="<?php echo $w->data['emodulbis']; ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="farbe" class="col-sm-4 control-label">Farbe von</label>
        <div class="col-sm-7">
            <input  type="text" class="form-control" name="farbe" id="farbe" placeholder="-L " value="<?php echo $w->data['farbe']; ?>">
        </div>
        <label for="farbebis" class="col-sm-4 control-label">Farbe bis</label>
        <div class="col-sm-7">
            <input  type="text" class="form-control" name="farbebis" id="farbebis" placeholder="+L" value="<?php echo $w->data['farbebis']; ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="schlag" class="col-sm-4 control-label">Schlag von</label>
        <div class="col-sm-7">
            <input  type="text" class="form-control" name="schlag" id="schlag" placeholder="-" value="<?php echo $w->data['schlag']; ?>">
        </div>
        <label for="schlagbis" class="col-sm-4 control-label">Schlag bis</label>
        <div class="col-sm-7">
            <input  type="text" class="form-control" name="schlagbis" id="schlagbis" placeholder="+" value="<?php echo $w->data['schlagbis']; ?>">
        </div>
    </div>

    <div class="form-group">
        <label for="afeuchte" class="col-sm-4 control-label">äußere Restfeuchte</label>
        <div class="col-sm-7">
            <input  type="text" class="form-control" name="afeuchte" id="afeuchte" placeholder="-" value="<?php echo $w->data['afeuchte']; ?>">
        </div>
        <label for="afeuchtebis" class="col-sm-4 control-label">äußere Restfeuchte bis</label>
        <div class="col-sm-7">
            <input  type="text" class="form-control" name="afeuchtebis" id="afeuchtebis" placeholder="+" value="<?php echo $w->data['afeuchtebis']; ?>">
        </div>
    </div>

    <div class="form-group">
        <label for="ifeuchte" class="col-sm-4 control-label">innere Restfeuchte von</label>
        <div class="col-sm-7">
            <input  type="text" class="form-control" name="ifeuchte" id="ifeuchte" placeholder="-" value="<?php echo $w->data['ifeuchte']; ?>">
        </div>
        <label for="ifeuchtebis" class="col-sm-4 control-label">innere Restfeuchte bis</label>
        <div class="col-sm-7">
            <input  type="text" class="form-control" name="ifeuchtebis" id="ifeuchtebis" placeholder="+" value="<?php echo $w->data['ifeuchtebis']; ?>">
        </div>
    </div>
    </div>

    <div id="agglomahl">
        <div id="agglomerat">
            <h4>Werkstoffeigenschaften bei Agglomerat</h4>
        </div>
        <div id="mahlgut">
                <h4>Werkstoffeigenschaften bei Mahlgut</h4>
        </div>
        <div class="form-group">
            <label for="schuettdichte" class="col-sm-4 control-label">Schüttdichte von</label>
            <div class="col-sm-7">
                <input  type="text" class="form-control" name="schuettdichte" id="schuettdichte" placeholder="min. Schüttdichte" value="<?php echo $w->data['schuettdichte']; ?>">
            </div>
            <label for="schuettdichtebis" class="col-sm-4 control-label">Schüttdichte bis</label>
            <div class="col-sm-7">
                <input  type="text" class="form-control" name="schuettdichtebis" id="schuettdichtebis" placeholder=" max. Schüttdichte" value="<?php echo $w->data['schuettdichtebis']; ?>">
            </div>
        </div>
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
            <?php if ($w->data['faufid']>0) { ?>
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
