<?php
/**
 * Created by PhpStorm.
 * User: andreasveltjens
 * Date: 25.12.16
 * Time: 18:54
 */
if ($w->data['idmaterial']>0) {

} else{
    $w->data['material']=$w->GetNextMaterial();
}
?>
<h4 class="header-title m-t-0 m-b-30">Material bearbeiten</h4>

<form method="post"  class="form-horizontal" role="form" data-parsley-validate novalidate>
    <input type="hidden" name="materialid" value="<?php echo $w->data['idmaterial']; ?>">



    <div class="form-group">
        <label for="material" class="col-sm-4 control-label">Material</label>
        <div class="col-sm-7">
            <input  type="text" required parsley-type="text" class="form-control" name="material" id="material" placeholder="Material" value="<?php echo ($w->data['material']); ?>">
        </div>
    </div>

    <div class="form-group">
        <label for="materialtxt" class="col-sm-4 control-label">Bezeichnung</label>
        <div class="col-sm-7">
            <input  type="text" class="form-control" name="materialtxt" id="materialtxt" placeholder="Bezeichnung" value="<?php echo ($w->data['materialtxt']); ?>">
        </div>
    </div>

    <div class="form-group">
        <label for="color" class="col-sm-4 control-label">Farbe</label>
        <div class="col-sm-7">
            <input  type="text" class="form-control" name="color" id="color" placeholder="Farbe" value="<?php echo ($w->data['color']); ?>">
        </div>
    </div>

    <div class="form-group">
        <label for="warengruppe" class="col-sm-4 control-label">Bezeichnung</label>
        <div class="col-sm-7">
            <input  type="text" class="form-control" name="warengruppe" id="warengruppe" placeholder="Warengruppe (z.B. recythen)" value="<?php echo ($w->data['warengruppe']); ?>">
        </div>
    </div>

    <div class="form-group">
        <label for="produktgruppe" class="col-sm-4 control-label">Bezeichnung</label>
        <div class="col-sm-7">
            <input  type="text" class="form-control" name="produktgruppe" id="produktgruppe" placeholder="Produktgruppe (z.B. PP)" value="<?php echo ($w->data['produktgruppe']); ?>">
        </div>
    </div>



    <hr>

    <div class="form-group">
        <label for="fnotes" class="col-sm-4 control-label">Bemerkungen </label>
        <div class="col-sm-7">
            <textarea  type="text" rows="5" class="form-control" name="fnotes" id="fnotes" placeholder="Bemerkungen" ><?php echo $w->data['fnotes']; ?></textarea>
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
            <?php if ($w->data['idmaterial']>0) { ?>
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
