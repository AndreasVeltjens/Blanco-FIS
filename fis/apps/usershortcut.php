<?php
/**
 * Created by PhpStorm.
 * User: andreasveltjens
 * Date: 15.01.17
 * Time: 14:44
 */
?>
<li class="dropdown user-box">
    <a href="" class="dropdown-toggle waves-effect waves-light profile " data-toggle="dropdown" aria-expanded="true">
        <img src="../layout-2_blue/assets/images/users/avatar-1.jpg" alt="user-img" class="img-circle user-img">
        <div class="user-status away"><i class="zmdi zmdi-dot-circle"></i></div>
    </a>

    <ul class="dropdown-menu">
        <li><a href="../apps/users.e.php?sess=<?php echo $_SESSION['sess']; ?>&useridedit=<?php echo $us->userid; ?>"><i class="ti-user m-r-5"></i> Account</a></li>
        <li><a href="#custom-width-modal2" data-toggle="modal" data-target="#custom-width-modal2"><i class="fa fa-users"></i> Benutzerwechsel</a></li>
        <li><a href="#custom-width-modal1" data-toggle="modal" data-target="#custom-width-modal1"><i class="ti-power-off m-r-5"></i> Logout</a></li>


    </ul>
</li>