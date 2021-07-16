<?php
/**
 * Created by PhpStorm.
 * User: andreasveltjens
 * Date: 25.12.16
 * Time: 18:54
 */

?>
<h4 class="header-title m-t-0 m-b-30">Mitarbeiterdaten</h4>

<form method="post"  class="form-horizontal" role="form" data-parsley-validate novalidate>
    <input type="hidden" name="userid" value="<?php echo $u->userid; ?>">
    <div class="form-group">
        <label for="name" class="col-sm-4 control-label">Vor- und Nachname*</label>
        <div class="col-sm-7">
            <input type="text" required parsley-type="text" class="form-control" name="name" id="name" placeholder="Name" value="<?php echo $u->data['name']; ?>">
        </div>
    </div>

    <div class="form-group">
        <label for="email" class="col-sm-4 control-label">Username (E-Mailadresse)*</label>
        <div class="col-sm-7">
            <input type="email" required parsley-type="email" class="form-control" name="email" id="email" placeholder="Email" value="<?php echo $u->data['email']; ?>">
        </div>
    </div>

    <div class="form-group">
        <label for="email" class="col-sm-4 control-label">Alias (E-Mailadresse)*</label>
        <div class="col-sm-7">
            <input type="email2" required parsley-type="email" class="form-control" name="email2" id="email2" placeholder="Alias" value="<?php echo $u->data['email2']; ?>">
        </div>
    </div>

    <div class="form-group">
        <label for="password" class="col-sm-4 control-label">Password*</label>
        <div class="col-sm-7">
            <input  type="password" required class="form-control" name="password" id="password" placeholder="" value="<?php echo $u->data['password']; ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="abteilung" class="col-sm-4 control-label">Abteilung*</label>
        <div class="col-sm-7">
            <input  type="text" required class="form-control" name="abteilung" id="abteilung" placeholder="Abteilung" value="<?php echo $u->data['abteilung']; ?>">
        </div>
    </div>
    <?php if ($u->data['userid']>0) { ?>
        <hr>
        <div class="form-group">
            <label for="anschrift" class="col-sm-4 control-label">Anschrift*</label>
            <div class="col-sm-7">
                <input  type="text" required class="form-control" name="anschrift" id="anschrift" placeholder="Straße" value="<?php echo $u->data['anschrift']; ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="plz" class="col-sm-4 control-label">PLZ*</label>
            <div class="col-sm-7">
                <input  type="text" required class="form-control" name="plz" id="plz" placeholder="PLZ" value="<?php echo $u->data['plz']; ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="ort" class="col-sm-4 control-label">Ort*</label>
            <div class="col-sm-7">
                <input  type="text" required class="form-control" name="ort" id="ort" placeholder="Ort" value="<?php echo $u->data['ort']; ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="land" class="col-sm-4 control-label">Land*</label>
            <div class="col-sm-7">
                <input  type="text" required class="form-control" name="land" id="land" placeholder="Land" value="<?php echo $u->data['land']; ?>">
            </div>
        </div>
        <hr>
        <div class="form-group">
            <label for="telefon" class="col-sm-4 control-label">Telefon*</label>
            <div class="col-sm-7">
                <input  type="text" required class="form-control" name="telefon" id="telefon" placeholder="Telefon" value="<?php echo $u->data['telefon']; ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="fax" class="col-sm-4 control-label">Fax*</label>
            <div class="col-sm-7">
                <input  type="text" required class="form-control" name="fax" id="fax" placeholder="Fax" value="<?php echo $u->data['fax']; ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="userright" class="col-sm-4 control-label">Userright*</label>
            <div class="col-sm-7">
                <select  required class="form-control" name="userright" id="userright">
                    <?php echo GetListUserRights("select",$u->data['userright']); ?>
                </select>
            </div>
        </div>

        <hr>
        <div class="form-group">
            <label for="fax" class="col-sm-4 control-label">Datum G25 Eignung</label>
            <div class="col-sm-7">
                <input  type="date"  class="form-control" name="datumg25" id="datumg25" placeholder="JJJJ-MM-TT" value="<?php echo $u->data['datumg25']; ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="fax" class="col-sm-4 control-label">Datum Staplerschein</label>
            <div class="col-sm-7">
                <input  type="date"  class="form-control" name="datumstapler" id="datumstapler" placeholder="JJJJ-MM-TT" value="<?php echo $u->data['datumstapler']; ?>">
            </div>
        </div>

        <div class="form-group">
            <label for="fax" class="col-sm-4 control-label">Datum Hubarbeitsbühne</label>
            <div class="col-sm-7">
                <input  type="date"  class="form-control" name="datumhub" id="datumhub" placeholder="JJJJ-MM-TT" value="<?php echo $u->data['datumhub']; ?>">
            </div>
        </div>

        <div class="form-group">
            <label for="fax" class="col-sm-4 control-label">Datum Kranschein</label>
            <div class="col-sm-7">
                <input  type="date"  class="form-control" name="datumkran" id="datumkran" placeholder="JJJJ-MM-TT" value="<?php echo $u->data['datumkran']; ?>">
            </div>
        </div>

    <?php } ?>
    <div class="form-group">
        <div class="col-sm-offset-4 col-sm-8">
            <?php if ($u->data['userid']>0) { ?>
            <button name="edit" type="submit" class="btn btn-primary waves-effect waves-light">
                Speichern
            </button>

            <?php if ($us->data['userright']>4) { ?>
                <button name="copy" type="submit" class="btn btn-warning waves-effect waves-light">
                    Kopieren
                </button>
                <?php } ?>
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
