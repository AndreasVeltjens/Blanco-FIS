<?php
/**
 * Created by PhpStorm.
 * User: andreasveltjens
 * Date: 25.12.16
 * Time: 18:54
 */
if ($w->data['id_customer']>0) {

} else{
    $w->data['referenz']=$w->GetNextCustomer();
}
?>
<h4 class="header-title m-t-0 m-b-30">Kundendaten  bearbeiten</h4>

<form method="post"  class="form-horizontal" role="form" data-parsley-validate novalidate>
    <input type="hidden" name="customerid" value="<?php echo $w->data['id_customer']; ?>">



    <div class="form-group">
        <label for="referenz" class="col-sm-4 control-label">Referenznummer</label>
        <div class="col-sm-7">
            <input  type="text" required parsley-type="text" class="form-control" name="referenz" id="referenz" placeholder="Kundennummer" value="<?php echo $w->data['referenz']; ?>">
        </div>
    </div>

    <div class="form-group">
        <label for="firma" class="col-sm-4 control-label">Firma</label>
        <div class="col-sm-7">
            <input  type="text" class="form-control" name="firma" id="firma" placeholder="Firma" value="<?php echo $w->data['firma']; ?>">
        </div>
    </div>

    <div class="form-group">
        <label for="street" class="col-sm-4 control-label">Anschrift</label>
        <div class="col-sm-7">
            <input  type="date" required parsley-type="date" class="form-control" name="street" id="street" placeholder="Anschrift" value="<?php echo $w->data['street']; ?>">
        </div>
    </div>

    <div class="form-group">
        <label for="plz" class="col-sm-4 control-label">PLZ</label>
        <div class="col-sm-7">
            <input  type="text" required parsley-type="text" class="form-control" name="plz" id="plz" placeholder="PLZ" value="<?php echo $w->data['plz']; ?>">
        </div>
    </div>

    <div class="form-group">
        <label for="city" class="col-sm-4 control-label">Ort</label>
        <div class="col-sm-7">
            <input  type="text" required parsley-type="text" class="form-control" name="city" id="city" placeholder="Stadt" value="<?php echo $w->data['city']; ?>">
        </div>
    </div>

    <div class="form-group">
        <label for="land" class="col-sm-4 control-label">Land</label>
        <div class="col-sm-7">
            <input  type="text" required parsley-type="text" class="form-control" name="land" id="land" placeholder="Land" value="<?php echo $w->data['land']; ?>">
        </div>
    </div>
    <hr>

    <div class="form-group">
        <label for="tel" class="col-sm-4 control-label">Telefon</label>
        <div class="col-sm-7">
            <input  type="text" required parsley-type="text" class="form-control" name="tel" id="tel" placeholder="Telefon" value="<?php echo $w->data['tel']; ?>">
        </div>
    </div>

    <div class="form-group">
        <label for="fax" class="col-sm-4 control-label">Fax</label>
        <div class="col-sm-7">
            <input  type="text" required parsley-type="text" class="form-control" name="fax" id="fax" placeholder="Fax" value="<?php echo $w->data['fax']; ?>">
        </div>
    </div>

    <div class="form-group">
        <label for="email" class="col-sm-4 control-label">E-Mail</label>
        <div class="col-sm-7">
            <input  type="text" required parsley-type="text" class="form-control" name="email" id="email" placeholder="E-Mail" value="<?php echo $w->data['email']; ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="name" class="col-sm-4 control-label">Ansprechpartner</label>
        <div class="col-sm-7">
            <input  type="text" required parsley-type="text" class="form-control" name="name" id="name" placeholder="Ansprechpartner" value="<?php echo $w->data['name']; ?>">
        </div>
    </div>
    <hr>

    <div class="form-group">
        <label for="fnotes" class="col-sm-4 control-label">Bemerkungen / Kunde</label>
        <div class="col-sm-7">
            <textarea  type="text" rows="5" class="form-control" name="fnotes" id="fnotes" placeholder="Bemerkungen, Verpackungsart (bb, gb, Silo, Oktabin)" ><?php echo $w->data['fnotes']; ?></textarea>
        </div>
    </div>
    <hr>


    <div class="form-group">
        <div class="form-group clearfix">
            <div class="col-sm-12 padding-left-0 padding-right-0">
                <input type="file" name="files[]" id="filer_input1" multiple="multiple">
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-4 col-sm-8">
            <?php if ($w->data['id_customer']>0) { ?>
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
