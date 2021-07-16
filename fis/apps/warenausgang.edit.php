<?php
/**
 * Created by PhpStorm.
 * User: andreasveltjens
 * Date: 25.12.16
 * Time: 18:54
 */

?>
<h4 class="header-title m-t-0 m-b-30">Warenausgangsdaten</h4>



<form method="post"  enctype="multipart/form-data" class="form-horizontal" role="form" data-parsley-validate novalidate>
    <input type="hidden" name="waid" value="<?php echo $w->waid; ?>">
    <div class="form-group">
        <label for="wdesc" class="col-sm-4 control-label">Beschreibung*</label>
        <div class="col-sm-7">
            <input type="text" required parsley-type="text" class="form-control" name="wdesc" id="wdesc" placeholder="Beschreibung" value="<?php echo $w->data['wdesc']; ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="auftragsnummer" class="col-sm-4 control-label">Auftragsnummer*</label>
        <div class="col-sm-7">
            <input name="auftragsnummer" id="auftragsnummer" type="text" placeholder="Auftragsnummer" required class="form-control" value="<?php echo $w->data['auftragsnummer']; ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="lieferscheinnummer" class="col-sm-4 control-label">Lieferscheinnummer*</label>
        <div class="col-sm-7">
            <input name="lieferscheinnummer" id="lieferscheinnummer" type="text" placeholder="Lieferscheinnummer" required class="form-control" value="<?php echo $w->data['lieferscheinnummer']; ?>">
        </div>
    </div>




    <div class="form-group">
        <label for="wcustomer" class="col-sm-4 control-label">Kunde*</label>
        <div class="col-sm-7">
            <input name="wcustomer" id="wcustomer" type="text" placeholder="Lieferant" required class="form-control" value="<?php echo $w->data['wcustomer']; ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="wdelivery" class="col-sm-4 control-label">Spedition
            *</label>
        <div class="col-sm-7">
            <input name="wdelivery" id="wdelivery" type="text" placeholder="Spedition" required class="form-control" value="<?php echo $w->data['wdelivery']; ?>">
        </div>
    </div>

    <div class="form-group">
        <label for="wlocation" class="col-sm-4 control-label">Außenlager*</label>
        <div class="col-sm-7">
            <input type="text"  class="form-control" name="wlocation" id="wlocation"  required placeholder="Ablageort"  value="<?php echo $w->data['wlocation']; ?>">
        </div>
    </div>



    <div class="form-group">
        <label for="nettols" class="col-sm-4 control-label">Gewicht lt.Lieferschein</label>
        <div class="col-sm-7">
            <input type="text"  class="form-control" name="nettols" id="nettols"   placeholder="Gewicht"  value="<?php echo $w->data['nettols']; ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="nettoproduktion" class="col-sm-4 control-label">Gewicht lt.Produktion</label>
        <div class="col-sm-7">
            <input type="text"  class="form-control" name="nettoproduktion" id="nettoproduktion"   placeholder="Gewicht"  value="<?php echo $w->data['nettoproduktion']; ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="anzahlve" class="col-sm-4 control-label">Anzahl der Verpackungseinheiten</label>
        <div class="col-sm-7">
            <input type="text"  class="form-control" name="anzahlve" id="anzahlve"  required placeholder="Anzahl der Verpackungseinheiten"  value="<?php echo $w->data['anzahlve']; ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="verpackungsart" class="col-sm-4 control-label">Art der Verpackung</label>
        <div class="col-sm-7">
            <input type="text"  class="form-control" name="verpackungsart" id="verpackungsart"  required placeholder="Verpackungsart"  value="<?php echo $w->data['verpackungsart']; ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="produkt" class="col-sm-4 control-label">Materialliste</label>
        <div class="col-sm-7">
            <input type="text"  class="form-control" name="produkt" id="produkt"   placeholder="Materialliste"  value="<?php echo $w->data['produkt']; ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="charge" class="col-sm-4 control-label">Charge</label>
        <div class="col-sm-7">
            <input type="text"  class="form-control" name="charge" id="charge"   placeholder="Charge"  value="<?php echo $w->data['charge']; ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="gewicht" class="col-sm-4 control-label">Gewicht</label>
        <div class="col-sm-7">
            <input type="text"  class="form-control" name="gewicht" id="gewicht"   placeholder="Gewicht"  value="<?php echo $w->data['gewicht']; ?>">
        </div>
    </div>

    <div class="form-group">
        <label for="wemail" class="col-sm-4 control-label">Ansprechpartner*</label>
        <div class="col-sm-7">
            <input type="email" required parsley-type="email" class="form-control" name="wemail" id="wemail" placeholder="Email" value="<?php echo $w->data['wemail']; ?>">
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-4 col-sm-8">
            <div class="checkbox">
                <input name="wdefective" id="wdefective" type="checkbox" value="1"  <?php if ($w->data['wdefective']==1) { echo "checked"; } ?>>
                <label for="wdefective"> Transportschaden vorhanden</label>
            </div>
        </div>
    </div>




    <div class="form-group">
        <label for="wnotes" class="col-sm-4 control-label">Bemerkungen*</label>
        <div class="col-sm-7">
            <input  type="text" class="form-control" name="wnotes" id="wnotes" placeholder="Bemerkungen" value="<?php echo $w->data['wnotes']; ?>">
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
            <?php if ($w->data['waid']>0) { ?>
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
