<?php
/**
 * Created by PhpStorm.
 * User: andreasveltjens
 * Date: 07.11.18
 * Time: 14:33
 */

include("../config/mysqlconnection.php");


$mysqli = new mysqli($hostname_datenbank, $username_datenbank, $password_datenbank, $database_datenbank,3306);