<?php
require_once("../config.php");
$uid=$_SESSION['userid'];
$date = new DateTime('now', new DateTimeZone('Asia/bangkok'));
$datee =  $date->format('Y-m-d H:i:s');
$time=time()+10;
$res = query("UPDATE tbl_admin set login_online='$time', last_login='$datee' where user_id=" . $_SESSION['userid']);
confirm($res);
