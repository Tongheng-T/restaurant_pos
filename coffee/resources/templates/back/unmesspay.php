<?php
require_once("../../config.php");



if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $select = queryC("SELECT * from tbl_service where id= '{$id}'");
    confirm($select);
}
$dd  = $_SESSION['num'] - 1;
$_SESSION['num'] = $dd;
$tbl_message = queryC("UPDATE tbl_message set viw = 1 where id='$id'");
confirm($tbl_message);
