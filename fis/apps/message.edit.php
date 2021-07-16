<?php
/**
 * Created by PhpStorm.
 * User: andreasveltjens
 * Date: 25.12.16
 * Time: 18:54
 */
if ($w->fid>0) {

} else{

    if ($w->data['mtype']=="") {$w->data['mtype']=1;};
    $w->data['fnotes1']="wird automatisch vergeben";
    $w->data['fdatum1']=date('Y-m-d H:i',time());

    if ($w->data['mtype']==5){$w->data['fnotes3']="Werksbesichtung";}

}
?>

<h4 class="header-title m-t-0 m-b-30"> Meldung  bearbeiten</h4>

<form method="post"  class="form-horizontal" role="form" data-parsley-validate novalidate>
    <input type="hidden" name="fid" value="<?php echo $w->fid; ?>">
    <input type="hidden" name="messageid" value="<?php echo $w->data['messageid']; ?>">





    <?php if ($w->data['mtype']==1) { ?>
        <h4>Unfallmeldung</h4>
        <div class="form-group">
            <label for="fnotes1" class="col-sm-4 control-label">interne Nummer</label>
            <div class="col-sm-7">
                <input  type="text" class="form-control" name="fnotes1" id="fnotes1" placeholder="interne Nummer" value="<?php echo $w->data['fnotes1']; ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="fdatum1" class="col-sm-4 control-label">Wann?</label>
            <div class="col-sm-7">
                <input  type="text" class="form-control" name="fdatum1" id="fdatum1" placeholder="Wann" value="<?php echo $w->data['fdatum1']; ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="fnotes2" class="col-sm-4 control-label">Wer?</label>
            <div class="col-sm-7">
                <textarea  type="text" class="form-control" name="fnotes2" id="fnotes2" placeholder="Wer" ><?php echo $w->data['fnotes2']; ?></textarea>
            </div>
        </div>
        <div class="form-group">
            <label for="fnotes3" class="col-sm-4 control-label">Wo?</label>
            <div class="col-sm-7">
                <textarea  type="text" class="form-control" name="fnotes3" id="fnotes3" placeholder="Wo?" ><?php echo $w->data['fnotes3']; ?></textarea>
            </div>
        </div>
        <div class="form-group">
            <label for="fnotes3" class="col-sm-4 control-label">Was genau ist passiert?</label>
            <div class="col-sm-7">
                <textarea  type="text" class="form-control" name="fnotes4" id="fnotes4" placeholder="genaue Beschreibung" ><?php echo $w->data['fnotes4']; ?></textarea>
            </div>
        </div>

        <div class="form-group">
            <div class="checkbox checkbox-primary m-b-15">
                <input  type="checkbox" class="checkbox-custom" name="m10" id="m10" <?php if ($w->data['m10']==1){ echo "checked";}; ?>>
                <label for="m10" >erneute Vorstellung in der Sanitätsstation erforderlich ?</label>
            </div>
        </div>
        <h3>Verletzte Körperteile</h3>
    <div class="row">
        <div class="col-sm-3">

            <h4>Kopf</h4>

            <div class="form-group">
                <div class="checkbox checkbox-primary m-b-15">
                    <input  type="checkbox" class="checkbox-custom" name="m11" id="m11" <?php if ($w->data['m11']==1){ echo "checked";}; ?>>
                    <label for="m11" >hinten</label>
                </div>
            </div>

            <div class="form-group">
                <div class="checkbox checkbox-primary m-b-15">
                    <input  type="checkbox" class="checkbox-custom" name="m12" id="m12" <?php if ($w->data['m12']==1){ echo "checked";}; ?>>
                    <label for="m12" >oben</label>
                </div>
            </div>
            <div class="form-group">
                <div class="checkbox checkbox-primary m-b-15">
                    <input  type="checkbox" class="checkbox-custom" name="m13" id="m13" <?php if ($w->data['m13']==1){ echo "checked";}; ?>>
                    <label for="m13" >Hals</label>
                </div>
            </div>

            <br>
            <h4>Körper</h4>

            <div class="form-group">
                <div class="checkbox checkbox-primary m-b-15">
                    <input  type="checkbox" class="checkbox-custom" name="m14" id="m14" <?php if ($w->data['m14']==1){ echo "checked";}; ?>>
                    <label for="m14" >Brust</label>
                </div>
            </div>

            <div class="form-group">
                <div class="checkbox checkbox-primary m-b-15">
                    <input  type="checkbox" class="checkbox-custom" name="m15" id="m15" <?php if ($w->data['m15']==1){ echo "checked";}; ?>>
                    <label for="m15" >Rücken</label>
                </div>
            </div>
            <div class="form-group">
                <div class="checkbox checkbox-primary m-b-15">
                    <input  type="checkbox" class="checkbox-custom" name="m16" id="m16" <?php if ($w->data['m16']==1){ echo "checked";}; ?>>
                    <label for="m16" >Hals</label>
                </div>
            </div>


        </div>
        <div class="col-sm-3">
            <h4>Gesicht</h4>
            <div class="form-group">
                <div class="checkbox checkbox-primary m-b-15">
                    <input  type="checkbox" class="checkbox-custom" name="m17" id="m17" <?php if ($w->data['m17']==1){ echo "checked";}; ?>>
                    <label for="m17" >Augen</label>
                </div>
            </div>

            <div class="form-group">
                <div class="checkbox checkbox-primary m-b-15">
                    <input  type="checkbox" class="checkbox-custom" name="m18" id="m18" <?php if ($w->data['m18']==1){ echo "checked";}; ?>>
                    <label for="m18" >Mund</label>
                </div>
            </div>

            <div class="form-group">
                <div class="checkbox checkbox-primary m-b-15">
                    <input  type="checkbox" class="checkbox-custom" name="m19" id="m19" <?php if ($w->data['m19']==1){ echo "checked";}; ?>>
                    <label for="m19" >Nase</label>
                </div>
            </div>
            <div class="form-group">
                <div class="checkbox checkbox-primary m-b-15">
                    <input  type="checkbox" class="checkbox-custom" name="m20" id="m20" <?php if ($w->data['m20']==1){ echo "checked";}; ?>>
                    <label for="m20" >Ohren</label>
                </div>
            </div>


        </div>

        <div class="col-sm-3">
            <h4>Arme</h4>
            <div class="form-group">
                <div class="checkbox checkbox-primary m-b-15">
                    <input  type="checkbox" class="checkbox-custom" name="m21" id="m21" <?php if ($w->data['m21']==1){ echo "checked";}; ?>>
                    <label for="m21" >Oberarm</label>
                </div>
            </div>

            <div class="form-group">
                <div class="checkbox checkbox-primary m-b-15">
                    <input  type="checkbox" class="checkbox-custom" name="m22" id="m22" <?php if ($w->data['m22']==1){ echo "checked";}; ?>>
                    <label for="m22" >Unterarm</label>
                </div>
            </div>

            <div class="form-group">
                <div class="checkbox checkbox-primary m-b-15">
                    <input  type="checkbox" class="checkbox-custom" name="m23" id="m23" <?php if ($w->data['m23']==1){ echo "checked";}; ?>>
                    <label for="m23" >Hand</label>
                </div>
            </div>
            <div class="form-group">
                <div class="checkbox checkbox-primary m-b-15">
                    <input  type="checkbox" class="checkbox-custom" name="m24" id="m24" <?php if ($w->data['m24']==1){ echo "checked";}; ?>>
                    <label for="m24" >Finger</label>
                </div>
            </div>
            <div class="form-group">
                <div class="checkbox checkbox-primary m-b-15">
                    <input  type="checkbox" class="checkbox-custom" name="m25" id="m25" <?php if ($w->data['m25']==1){ echo "checked";}; ?>>
                    <label for="m25" >Daumen</label>
                </div>
            </div>


        </div>
    </div>



    <?php } ?>


    <?php if ($w->data['mtype']==2) { ?>
        <h4>Schadenmeldung</h4>
        <div class="form-group">
            <label for="fnotes1" class="col-sm-4 control-label">interne Nummer</label>
            <div class="col-sm-7">
                <input  type="text" class="form-control" name="fnotes1" id="fnotes1" placeholder="interne Nummer" value="<?php echo $w->data['fnotes1']; ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="fdatum1" class="col-sm-4 control-label">Wann?</label>
            <div class="col-sm-7">
                <input  type="text" class="form-control" name="fdatum1" id="fdatum1" placeholder="Wann" value="<?php echo $w->data['fdatum1']; ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="fnotes2" class="col-sm-4 control-label">Wer?</label>
            <div class="col-sm-7">
                <textarea  type="text" class="form-control" name="fnotes2" id="fnotes2" placeholder="Wer" ><?php echo $w->data['fnotes2']; ?></textarea>
            </div>
        </div>
        <div class="form-group">
            <label for="fnotes2" class="col-sm-4 control-label">Wo?</label>
            <div class="col-sm-7">
                <textarea  type="text" class="form-control" name="fnotes3" id="fnotes3" placeholder="Wo?" ><?php echo $w->data['fnotes3']; ?></textarea>
            </div>
        </div>
        <div class="form-group">
            <label for="fnotes2" class="col-sm-4 control-label">Was genau ist passiert?</label>
            <div class="col-sm-7">
                <textarea  type="text" class="form-control" name="fnotes4" id="fnotes4" placeholder="genaue Beschreibung" ><?php echo $w->data['fnotes4']; ?></textarea>
            </div>
        </div>

    <?php } ?>

    <?php if ($w->data['mtype']==3) { ?>

        <h4>Fremdfirmenanmeldung</h4>
        <div class="form-group">
            <label for="fnotes1" class="col-sm-4 control-label">interne Nummer</label>
            <div class="col-sm-7">
                <input  type="text" class="form-control" name="fnotes1" id="fnotes1" placeholder="interne Nummer" value="<?php echo $w->data['fnotes1']; ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="fdatum1" class="col-sm-4 control-label">Wann?</label>
            <div class="col-sm-7">
                <input  type="text" class="form-control" name="fdatum1" id="fdatum1" placeholder="Wann" value="<?php echo $w->data['fdatum1']; ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="fnotes2" class="col-sm-4 control-label">Wer?</label>
            <div class="col-sm-7">
                <textarea  type="text" class="form-control" name="fnotes2" id="fnotes2" placeholder="Wer" ><?php echo $w->data['fnotes2']; ?></textarea>
            </div>
        </div>
        <div class="form-group">
            <label for="fnotes3" class="col-sm-4 control-label">Wo?</label>
            <div class="col-sm-7">
                <textarea  type="text" class="form-control" name="fnotes3" id="fnotes3" placeholder="Wo?" ><?php echo $w->data['fnotes3']; ?></textarea>
            </div>
        </div>

        <div class="form-group">
            <label for="fnotes4" class="col-sm-4 control-label">RFID Chip</label>
            <div class="col-sm-7">
                <input  type="text" class="form-control" name="fnotes4" id="fnotes4" placeholder="RFID Chip" value="<?php echo $w->data['fnotes4']; ?>">
            </div>
        </div>



        <div class="form-group">
            <div class="checkbox checkbox-primary m-b-15">
                <input  type="checkbox" class="checkbox-custom" name="m10" id="m10" <?php if ($w->data['m10']==1){ echo "checked";}; ?>>
                <label for="m10" >Einfahrt auf Betriebsgelände</label>
            </div>
        </div>

        <div class="form-group">
            <div class="checkbox checkbox-primary m-b-15">
                <input  type="checkbox" class="checkbox-custom" name="m11" id="m11" <?php if ($w->data['m11']==1){ echo "checked";}; ?>>
                <label for="m11" >Reparatur oder Montagearbeiten</label>
            </div>
        </div>
        <div class="form-group">
            <div class="checkbox checkbox-primary m-b-15">
                <input  type="checkbox" class="checkbox-custom" name="m12" id="m12" <?php if ($w->data['m12']==1){ echo "checked";}; ?>>
                <label for="m12" >Stapler wird verwendet</label>
            </div>
        </div>

        <div class="form-group">
            <div class="checkbox checkbox-primary m-b-15">
                <input  type="checkbox" class="checkbox-custom" name="m13" id="m13" <?php if ($w->data['m13']==1){ echo "checked";}; ?>>
                <label for="m13" >Hubarbeitsbühne wird verwendet</label>
            </div>
        </div>
        <div class="form-group">
            <div class="checkbox checkbox-primary m-b-15">
                <input  type="checkbox" class="checkbox-custom" name="m14" id="m14" <?php if ($w->data['m14']==1){ echo "checked";}; ?>>
                <label for="m14" >Hallenkran/ Hebezeuge wird verendet</label>
            </div>
        </div>
        <div class="form-group">
            <div class="checkbox checkbox-primary m-b-15">
                <input  type="checkbox" class="checkbox-custom" name="m15" id="m15" <?php if ($w->data['m15']==1){ echo "checked";}; ?>>
                <label for="m15" >Erlaubsschein für funkenerzeugende Arbeiten / Schweißerlaubnis</label>
            </div>
        </div>


    <?php } ?>


    <?php if ($w->data['mtype']==4) { ?>
        <h4>Erlaubnisschein</h4>
        <div class="form-group">
            <label for="fnotes1" class="col-sm-4 control-label">interne Nummer</label>
            <div class="col-sm-7">
                <input  type="text" class="form-control" name="fnotes1" id="fnotes1" placeholder="interne Nummer" value="<?php echo $w->data['fnotes1']; ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="fdatum1" class="col-sm-4 control-label">Wann?</label>
            <div class="col-sm-7">
                <input  type="text" class="form-control" name="fdatum1" id="fdatum1" placeholder="Wann" value="<?php echo $w->data['fdatum1']; ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="fnotes2" class="col-sm-4 control-label">Wer?</label>
            <div class="col-sm-7">
                <textarea  type="text" class="form-control" name="fnotes2" id="fnotes2" placeholder="Wer" ><?php echo $w->data['fnotes2']; ?></textarea>
            </div>
        </div>
        <div class="form-group">
            <label for="fnotes3" class="col-sm-4 control-label">Wo?</label>
            <div class="col-sm-7">
                <textarea  type="text" class="form-control" name="fnotes3" id="fnotes3" placeholder="Wo?" ><?php echo $w->data['fnotes3']; ?></textarea>
            </div>
        </div>
        <div class="form-group">
            <label for="fnotes4" class="col-sm-4 control-label">Brandschutz</label>
            <div class="col-sm-7">
                <textarea  type="text" class="form-control" name="fnotes4" id="fnotes4" placeholder="genaue Beschreibung" ><?php echo $w->data['fnotes4']; ?></textarea>
            </div>
        </div>



    <?php } ?>

    <?php if ($w->data['mtype']==5) { ?>
        <h4>Besucheranmeldung</h4>
        <div class="form-group">
            <label for="fnotes1" class="col-sm-4 control-label">interne Nummer</label>
            <div class="col-sm-7">
                <input  type="text" class="form-control" name="fnotes1" id="fnotes1" placeholder="interne Nummer" value="<?php echo $w->data['fnotes1']; ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="fdatum1" class="col-sm-4 control-label">Wann?</label>
            <div class="col-sm-7">
                <input  type="text" class="form-control" name="fdatum1" id="fdatum1" placeholder="Wann" value="<?php echo $w->data['fdatum1']; ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="fnotes2" class="col-sm-4 control-label">Wer?</label>
            <div class="col-sm-7">
                <textarea  type="text" class="form-control" name="fnotes2" id="fnotes2" placeholder="Wer" ><?php echo $w->data['fnotes2']; ?></textarea>
            </div>
        </div>
        <div class="form-group">
            <label for="fnotes3" class="col-sm-4 control-label">Wo?</label>
            <div class="col-sm-7">
                <textarea  type="text" class="form-control" name="fnotes3" id="fnotes3" placeholder="Wo?" ><?php echo $w->data['fnotes3']; ?></textarea>
            </div>
        </div>


        <h4>Sicherheitsunterweisung</h4>

        <div class="form-group">
            <div class="checkbox checkbox-primary m-b-15">
                <input  type="checkbox" class="checkbox-custom" name="m11" id="m11" <?php if ($w->data['m11']==1){ echo "checked";}; ?>>
                <label for="m11" >Sicherheitsunterweisung und Werksordnung durchgeführt</label>
            </div>
        </div>

    <?php } ?>

    <div class="form-group">
        <div class="col-sm-offset-4 col-sm-8">
            <?php if ($w->fid>0) { ?>
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






    <input type="hidden" name="mtype" value="<?php echo $w->data['mtype']; ?>">
    <input type="hidden" name="ftype" value="<?php echo $w->data['ftype']; ?>">
    <input type="hidden" name="fstate" value="<?php echo $w->data['fstate']; ?>">



    <hr>

    <h4>interne Notizen</h4>

    <div class="form-group">
        <label for="fnotes" class="col-sm-4 control-label">interne Notiz</label>
        <div class="col-sm-7">
            <textarea  type="text" class="form-control" name="fnotes" id="fnotes" placeholder="Bemerkungen" ><?php echo $w->data['fnotes']; ?></textarea>
        </div>
    </div>

    <div class="form-group">
        <div class="form-group clearfix">
            <div class="col-sm-12 padding-left-0 padding-right-0">
                <input type="file" name="files[]" id="filer_input1" multiple="multiple">
            </div>
        </div>
    </div>

</form>
