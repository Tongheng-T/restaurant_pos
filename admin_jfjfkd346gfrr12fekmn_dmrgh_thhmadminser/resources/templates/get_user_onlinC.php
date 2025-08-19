<?php
require_once("../config.php");
$uid = $_SESSION['userid'];

$date = new DateTime('now', new DateTimeZone('Asia/bangkok'));
$datee =  $date->format('Y-m-d h:i:s');
$time = time();
$select = queryC("SELECT * from tbl_user ");
confirm($select);
$i = 1;
$html = '';
while ($row = $select->fetch_assoc()) {
	$aus = $row['aus'];
	$selectt = queryC("SELECT min(user_id) as user from tbl_user where aus= '$aus' ");
	$roww = $selectt->fetch_object();
	$user = $roww->user;
	$selectk = queryC("SELECT * from tbl_user where user_id = '$user'");
	$rows = $selectk->fetch_object();
	$user_id = $rows->user_id;

	$classs = '';
	$id = $row['user_id'];
	if ($id == $user_id) {
		$classs = 'text-warning';
	}

	$showdate = $row['date_new'];
	$new_date = date('Y-m-d');
	$datetime4 = new DateTime($new_date);
	$datetime3 = new DateTime($showdate);
	$intervall = $datetime3->diff($datetime4);
	$texttt =   $intervall->format('%a');
	$numdatee = $row['tim'] - $texttt;
	if ($numdatee >= 10) {
		$color = 'success';
	} elseif ($numdatee >= 5) {
		$color = 'warning';
	} else {
		$color = 'danger';
	}

	$date = date($row['last_login']);
	$timeago = timeago($date);
	$status = $timeago;

	$class = "text-danger";
	if ($row['login_online'] > $time) {
		$status = 'Online';
		$class = "text-success";
	}
	$html .= '<div class="user">
           	<img src="../../resources/images/userpic/' . $row['img'] . '">
        	<h2> ' . $row['username'] . ' ' . $numdatee . 'day </h2>
	        <p class="' . $classs . '"> <i class="fas fa-id-card"></i> ' . $row['aus'] . '</p>
	        <p class="' . $classs . '"> <i class="fas fa-id-card"></i> ' . $row['useremail'] . '</p>
			<p class="text-info"> <i class="fas fa-map-pin"></i> ' . $row['location_ip'] . '</p>
	        <p class="' . $class . '"> <i class="fas fa-signal"></i> ' . $status . '</p>
            </div>';
	$i++;
}

echo $html;
