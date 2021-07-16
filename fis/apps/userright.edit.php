<?php
/**
 * Created by PhpStorm.
 * User: andreasveltjens
 * Date: 25.12.16
 * Time: 18:54
 */

?>
<h4 class="header-title m-t-0 m-b-30">Mitarbeiterberechtigung für <?php echo $u->data['name']; ?></h4>

<form method="post"  class="form-horizontal" role="form" data-parsley-validate novalidate>
    <input type="hidden" name="userid" value="<?php echo $u->userid; ?>">

    <?php if ($u->data['userid']>0) { ?>

        <div class="form-group">
            <label for="userright" class="col-sm-4 control-label">Userright*</label>
            <div class="col-sm-7">
                <select  required class="form-control" name="userright" id="userright">
                    <?php echo GetListUserRights("select",$u->data['userright']); ?>
                </select>
            </div>
        </div>

    <?php } ?>
    <div class="form-group">
        <div class="col-sm-offset-4 col-sm-8">
            <?php if ($u->data['userid']>0) { ?>
            <button name="setuserright" type="submit" class="btn btn-primary waves-effect waves-light">
                Berechtigung setzen
            </button>
            <?php } ?>
            <button type="reset"
                    class="btn btn-link waves-effect m-l-5">
                Formular zurücksetzen
            </button>
        </div>
    </div>
</form>
