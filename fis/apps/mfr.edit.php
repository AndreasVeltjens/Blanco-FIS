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
    if ($w->data['mtype']=="") {$w->data['mtype']=1;};
    $w->data['fauf']="laufend";
    if ($w->data['mtype']==1) {$w->data['m7']="";};

}
?>

<h4 class="header-title m-t-0 m-b-30">Rückmeldung MFR-Messung  <?php echo  $w->data['gebindeid']; ?> bearbeiten</h4>

<form method="post"  class="form-horizontal" role="form" data-parsley-validate novalidate>
    <input type="hidden" name="fid" value="<?php echo $w->fid; ?>">
    <?php if ($w->data['mtype']==1) { ?>
    <h4>allgemein</h4>

    <div class="form-group">
        <label for="fnotes" class="col-sm-4 control-label">Prüflos (e)</label>
        <div class="col-sm-7">
            <textarea  type="text" class="form-control" name="fnotes" id="fnotes" placeholder="Bemerkungen" ><?php echo $w->data['fnotes']; ?></textarea>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-7">
            <input  type="hidden" parsley-type="text"  class="form-control" name="fauf" id="fauf" placeholder="Fertigungsauftragsnummer" value="<?php echo $w->data['fauf']; ?>">
        </div>
    </div>


        <div class="form-group">
            <label for="m7" class="col-sm-4 control-label">Ort der Abfüllung</label>
            <div class="col-sm-8">
                <select  class="form-control select2" name="m7" id="m7">
                    <?php echo GetListMFRLocation("select",$w->data['m7']);

                    ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="material" class="col-sm-4 control-label">Material</label>
            <div class="col-sm-8">
                <select  class="form-control select2" name="material" id="material">
                    <?php echo GetMaterialName("select2",$w->data['material']);

                    ?>
                </select>
            </div>
        </div>

    <h4>Prüfmethode</h4>


    <div class="form-group">
        <label for="mfimethode" class="col-sm-4 control-label">MFI- Prüfmethode </label>
        <div class="col-sm-7">

            <select  required parsley-type="text" class="form-control select2" name="mfimethode" id="mfimethode" >
                <?php echo GetListMFIMethode("select2", $w->data['mfimethode']); ?>
            </select>
        </div>

    </div>


    <h4>Messwerte MFR </h4>
        <div class="form-group">
            <label for="m5" class="col-sm-4 control-label">Probenanzahl </label>
            <div class="col-sm-7">
                <input  type="text" parsley-type="text" class="form-control" name="m7" id="m7" placeholder="Probenanzahl, wenn Probe verklebt" value="<?php echo $w->data['m7']; ?>">
            </div>
        </div>

        <div class="form-group">
        <label for="m1" class="col-sm-4 control-label">Masse Messung 1 </label>
        <div class="col-sm-7">
            <input  type="text" required parsley-type="text" class="form-control" name="m1" id="m1" placeholder="in g" value="<?php echo $w->data['m1']; ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="m1" class="col-sm-4 control-label">Masse Messung 2 </label>
        <div class="col-sm-7">
            <input  type="text"  parsley-type="text" class="form-control" name="m2" id="m2" placeholder="in g" value="<?php echo $w->data['m2']; ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="m1" class="col-sm-4 control-label">Masse Messung 3 </label>
        <div class="col-sm-7">
            <input  type="text"  parsley-type="text" class="form-control" name="m3" id="m3" placeholder="in g" value="<?php echo $w->data['m3']; ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="m4" class="col-sm-4 control-label">Masse Messung 4 </label>
        <div class="col-sm-7">
            <input  type="text"  parsley-type="text" class="form-control" name="m4" id="m4" placeholder="in g" value="<?php echo $w->data['m4']; ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="m5" class="col-sm-4 control-label">Masse Messung 5 </label>
        <div class="col-sm-7">
            <input  type="text"  parsley-type="text" class="form-control" name="m5" id="m5" placeholder="in g" value="<?php echo $w->data['m5']; ?>">
        </div>
    </div>

    <div class="form-group">
        <label for="m5" class="col-sm-4 control-label">Masse Messung 6 </label>
        <div class="col-sm-7">
            <input  type="text"  parsley-type="text" class="form-control" name="m6" id="m6" placeholder="in g" value="<?php echo $w->data['m6']; ?>">
        </div>
    </div>


    <h4>Qualitätsmerkmale</h4>

    <div class="form-group">
        <label for="fstate" class="col-sm-4 control-label">Strangqualität*</label>
        <div class="col-sm-7">
            <select  required class="form-control" name="strang" id="strang">
                <?php echo GetListStrangType("select",$w->data['strang']); ?>
            </select>
        </div>
    </div>

    <?php } ?>


    <?php if ($w->data['mtype']==2) { ?>
        <h4>Restfeuchtemessung</h4>
        <div class="form-group">
            <label for="material" class="col-sm-4 control-label">Material</label>
            <div class="col-sm-8">
                <select  class="form-control select2" name="material" id="material">
                    <?php echo GetMaterialName("select2",$w->data['material']);

                    ?>
                </select>
            </div>
        </div>
        <input type="hidden" name="fauf" value="laufend">
        <div class="form-group">
            <label for="m1" class="col-sm-4 control-label">Ort der Probeentnahme</label>
            <div class="col-sm-7">
                <select  required class="form-control" name="m1" id="m1">
                    <?php echo GetListMeasureLocations("select",$w->data['m1']); ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="m2" class="col-sm-4 control-label">Restfeuchte in % </label>
            <div class="col-sm-7">
                <input  type="text" required parsley-type="text" class="form-control" name="m2" id="m2" placeholder="in %" value="<?php echo $w->data['m2']; ?>">
            </div>
        </div>

    <?php } ?>

    <?php if ($w->data['mtype']==3) { ?>
        <h4>Schlammprüfung</h4>
        <input type="hidden" name="fauf" value="laufend">
        <div class="form-group">
            <label for="m1" class="col-sm-4 control-label">Schlamm Absiebung - Restfeuchte in % </label>
            <div class="col-sm-7">
                <input  type="text" required parsley-type="text" class="form-control" name="m1" id="m1" placeholder="in %" value="<?php echo $w->data['m1']; ?>">
            </div>
        </div>


        <div class="form-group">
            <label for="m2" class="col-sm-4 control-label">Schlamm Klärwerk - Restfeuchte in % </label>
            <div class="col-sm-7">
                <input  type="text" required parsley-type="text" class="form-control" name="m2" id="m2" placeholder="in %" value="<?php echo $w->data['m2']; ?>">
            </div>
        </div>

    <?php } ?>

    <?php if ($w->data['mtype']==4) { ?>
        <h4>Trennung</h4>
        <input type="hidden" name="fauf" value="laufend">
        <div class="form-group">
            <label for="m1" class="col-sm-4 control-label">Becken 1 - Trennung</label>
            <div class="col-sm-7">
                <select  required class="form-control" name="m1" id="m1">
                    <?php echo GetListBeckenType("select",$w->data['m1']); ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="m2" class="col-sm-4 control-label">Becken 2 - Trennung</label>
            <div class="col-sm-7">
                <select  required class="form-control" name="m2" id="m2">
                    <?php echo GetListBeckenType("select",$w->data['m2']); ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="m3" class="col-sm-4 control-label">Becken 3 - Trennung</label>
            <div class="col-sm-7">
                <select  required class="form-control" name="m3" id="m3">
                    <?php echo GetListBeckenType("select",$w->data['m3']); ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="m4" class="col-sm-4 control-label">Becken 4 - Trennung</label>
            <div class="col-sm-7">
                <select  required class="form-control" name="m4" id="m4">
                    <?php echo GetListBeckenType("select",$w->data['m4']); ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="m5" class="col-sm-4 control-label">Becken 5 - Trennung</label>
            <div class="col-sm-7">
                <select  required class="form-control" name="m5" id="m5">
                    <?php echo GetListBeckenType("select",$w->data['m5']); ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="m6" class="col-sm-4 control-label">Linie 3 - Trennung</label>
            <div class="col-sm-7">
                <select  required class="form-control" name="m6" id="m6">
                    <?php echo GetListBeckenType("select",$w->data['m6']); ?>
                </select>
            </div>
        </div>
    <?php } ?>

    <?php if ($w->data['mtype']==5) { ?>
        <h4>Schüttdichte</h4>
        <input type="hidden" name="fauf" value="laufend">
        <div class="form-group">
            <label for="material" class="col-sm-4 control-label">Material</label>
            <div class="col-sm-8">
                <select  class="form-control select2" name="material" id="material">
                    <?php echo GetMaterialName("select2",$w->data['material']);

                    ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="m1" class="col-sm-4 control-label">Ort der Probeentnahme</label>
            <div class="col-sm-7">
                <select  required class="form-control" name="m1" id="m1">
                    <?php echo GetListMeasureLocations("select",$w->data['m1']); ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="m2" class="col-sm-4 control-label">Schüttdichte in g/l</label>
            <div class="col-sm-7">
                <input  type="text" required parsley-type="text" class="form-control" name="m2" id="m2" placeholder="in g/l" value="<?php echo $w->data['m2']; ?>">
            </div>
        </div>

    <?php } ?>


    <?php if ($w->data['mtype']==6) { ?>
        <h4>CSB Wasseranalytik</h4>
        <input type="hidden" name="fauf" value="laufend">
        <div class="form-group">
            <label for="m1" class="col-sm-4 control-label">CSB Eingang</label>
            <div class="col-sm-7">
                <input  type="text" required parsley-type="text" class="form-control" name="m1" id="m1" placeholder="Messwert" value="<?php echo $w->data['m1']; ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="m2" class="col-sm-4 control-label">CSB Klarlauf</label>
            <div class="col-sm-7">
                <input  type="text" required parsley-type="text" class="form-control" name="m2" id="m2" placeholder="Messwert" value="<?php echo $w->data['m2']; ?>">
            </div>
        </div>

    <?php } ?>

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
    <hr>



    <h4>Prüfgerät</h4>

    <div class="form-group">
        <label for="mdevice" class="col-sm-4 control-label">Prüfgerät</label>
        <div class="col-sm-7">
            <select   class="form-control" name="mdevice" id="mdevice">
                <?php echo GetListMidDevice("select",$w->data['mdevice'],$w->data['mtype']); ?>
            </select>
        </div>
    </div>



    <input type="hidden" name="mtype" value="<?php echo $w->data['mtype']; ?>">
    <input type="hidden" name="ftype" value="<?php echo $w->data['ftype']; ?>">
    <input type="hidden" name="fstate" value="<?php echo $w->data['fstate']; ?>">



    <hr>


</form>
