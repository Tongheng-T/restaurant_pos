<?php

include_once '../../config.php';

$Change = $_POST["Change"];
$total = $_POST["total"];
$totalAmountt = $_POST["totalAmount"];
$discountp = $_POST["discountp"];
$discountKHR = $_POST["discountKHR"];
$recievedAmount = $_POST["recievedAmount"];
$paymentType = $_POST["paymentType"];
$invoice_id = $_POST["saleId"];
$aus = $_SESSION['aus'];
$change = query("SELECT * from tbl_change where aus='$aus'");
confirm($change);
$row_exchange = $change->fetch_object();
$exchange = $row_exchange->exchange;
$usd_or_real = $row_exchange->usd_or_real;
if ($usd_or_real == "usd") {
    $totalAmount = $totalAmountt;
    $subtotal = $total ;
    $discountKHRk = $discountKHR;
  
  } else {
    $totalAmount = $totalAmountt / $exchange;
    $subtotal = $total / $exchange;
    $discountKHRk = $discountKHR / $exchange;
  }


$UPDATE = query("UPDATE tbl_invoice set subtotal='$subtotal',discount='$discountKHRk',discountp='$discountp',total = '$totalAmount',payment_type ='$paymentType',due='$Change',paid='$recievedAmount',sale_status= 'paid' where invoice_id='$invoice_id'");
confirm($UPDATE);
$tbl_invoice = query("SELECT * from tbl_invoice where invoice_id=$invoice_id ");
confirm($tbl_invoice);

$row = $tbl_invoice->fetch_object();
$table_id = $row->table_id;

$UPDATE = query("UPDATE tables set status = 'available' where id='$table_id'");
confirm($UPDATE);

return "itemt?pos";