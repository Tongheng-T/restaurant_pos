<?php
require_once("../config.php");
$uid = $_SESSION['userid'];

$date = new DateTime('now', new DateTimeZone('Asia/bangkok'));
$datee =  $date->format('Y-m-d h:i:s');
$time = time();
$select = query("SELECT * from tbl_user ");
confirm($select);
$i = 1;
$html = '';
while ($row = $select->fetch_assoc()) {
	$date = date($row['last_login']);
	$timeago = timeago($date);
	$status = $timeago;

	$class = "text-danger";
	if ($row['login_online'] > $time) {
		$status = 'Online';
		$class = "text-success";
	} 
	$html .= '<div class="user">
           	<img src="../productimages/user/' . $row['img'] . '">
        	<h2> ' . $row['username'] . ' </h2>
	        <p class="' . $class . '"> <i class="fas fa-signal"></i> ' . $status . '</p>
            </div>';
	$i++;
}
$html .= '<div class="user">
<a href="itemt?registration">
<img src="../productimages/plus.png">
<h2>More</h2>
<p>New User</p></a>
</div>';
echo $html;
