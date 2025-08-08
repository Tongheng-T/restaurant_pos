<?php

if ($_SESSION['useremail'] == ""  or $_SESSION['role'] == "") {

  header('location:../');
}


if (isset($_POST['send'])) {
  $aus = $_POST['txtaus'];
  $id_pay = $_POST['txtid_pay'];
  $txttim = $_POST['txttim'];
  date_default_timezone_set("asia/phnom_penh");
  $date = new DateTime('now', new DateTimeZone('Asia/bangkok'));
  $new_date =  $date->format('Y-m-d H:i:s');

  $messages = $_POST['send'];

  $insert = queryC("INSERT INTO tbl_message (messages,id_payment,date,aus) values('{$messages}','{$id_pay}','{$new_date}','{$aus}')");
  confirm($insert);
  $update = queryC("UPDATE tbl_payment set alert=1 where id='$id_pay'");
  $dd  = $_SESSION['num'] - 1;
  if ($messages !== "ការទូទាត់បរាជ័យ") {
    $update = queryC("UPDATE tbl_user set date_new='$new_date', tim='$txttim' where aus='$aus'");
  } else {
    $update = queryC("UPDATE tbl_user set date_new='$new_date', tim=0 where aus='$aus'");
  }
  redirect('itemt?viewpayC&id='.$id_pay.'');
}

?>


<main>
  <h1>payment List</h1>



  <div class="card card-info card-outline">
    <div class="card-header">
      <h5 class="m-0">View payment</h5>
    </div>
    <div class="card-body">

      <?php viewpayC(); ?>


    </div>
  </div>



</main>