<?php
/**
 * Created by PhpStorm.
 * User: andreasveltjens
 * Date: 29.12.16
 * Time: 20:28
 */

?>
<div class="side-bar right-bar">
    <a href="javascript:void(0);" class="right-bar-toggle">
        <i class="zmdi zmdi-close-circle-o"></i>
    </a>
    <h4 class="">Notifications</h4>
    <div class="notification-list nicescroll">
        <ul class="list-group list-no-border user-list">
            <?php echo $e->GetNotifications(); ?>
        </ul>
    </div>
</div>
