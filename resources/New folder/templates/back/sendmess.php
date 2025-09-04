<?php



if (isset($_POST['send'])) {
    date_default_timezone_set("asia/phnom_penh");
    $date = new DateTime('now', new DateTimeZone('Asia/bangkok'));
    $new_date =  $date->format('Y-m-d H:i:s');

    $num_month = 'ការបង់ប្រាក់ទទួលបានជោគជ័យ';
    $aus = 1;
    $insert = query("INSERT INTO tbl_message (messages,date,aus) values('{$num_month}','{$new_date}','{$aus}')");
}

?>

<form action="" method="post">
    <button type="submit" class="btn btn-primary" name="send">Edit</button>
</form>