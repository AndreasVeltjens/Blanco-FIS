<?php

/**
 * Created by PhpStorm.
 * User: andreasveltjens
 * Date: 04.12.18
 * Time: 19:54
 */
class FisThumbnail extends FisImaging
{

    /**
     * $thumb = new thumbnail('./image_dir/sub_dir/myimage.jpg',100,100);
     * echo '<img src=\''.$thumb.'\' alt=\'myimage\' title=\'myimage\'/>';
     *
     */

    private $image;
    private $type;
    private $width;
    private $saveto;
    private $height;
    private $thumbnail="";

    function __construct($image,$saveto="../thumbnail/",$width,$height) {

        parent::set_img($image);
        parent::set_quality(80);
        parent::set_size($width,$height);
        $this->thumbnail= $saveto.pathinfo($image, PATHINFO_FILENAME).'.'.pathinfo($image, PATHINFO_EXTENSION);
        parent::save_img($this->thumbnail);
        parent::clear_cache();

    }
    function __toString() {
        return $this->thumbnail;
    }
}