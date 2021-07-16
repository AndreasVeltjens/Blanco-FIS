<?php
/**
 * Created by PhpStorm.
 * User: andreasveltjens
 * Date: 25.12.16
 * Time: 18:54
 */

?>
<h4 class="header-title m-t-0 m-b-30">Wareneingangsdaten</h4>

<form method="post"  class="form-horizontal" role="form" data-parsley-validate novalidate>
    <input type="hidden" name="wid" value="<?php echo $w->wid; ?>">
    <div class="form-group">
        <label for="wdesc" class="col-sm-4 control-label">Beschreibung*</label>
        <div class="col-sm-7">
            <input type="text" required parsley-type="text" class="form-control" name="wdesc" id="wdesc" placeholder="Beschreibung" value="<?php echo $w->data['wdesc']; ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="wcustomer" class="col-sm-4 control-label">Lieferant*</label>
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
        <label for="wlocation" class="col-sm-4 control-label">Ablageort*</label>
        <div class="col-sm-7">
            <input type="text"  class="form-control" name="wlocation" id="wlocation"  required placeholder="Ablageort"  value="<?php echo $w->data['wlocation']; ?>">
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
            <?php if ($w->data['wid']>0) { ?>
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
