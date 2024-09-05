<?php

include_once '../../config.php';

$productid = $_POST["id"];
$table_id = $_POST["table_id"];
$table_name = $_POST["table_name"];
$qty = $_POST["qty"];
$saler_id = $_SESSION['userid'];
$saler_name = $_SESSION['username'];
$aus = $_SESSION['aus'];

$select = query("SELECT * from tbl_product where pid=$productid and aus='$aus'");
confirm($select);
$row = $select->fetch_object();
$product_name = $row->product;

$salepric = $row->saleprice;

$select_inv = query("SELECT * from tbl_invoice where table_id= $table_id and aus='$aus' and sale_status='unpaid' ");
confirm($select_inv);
$row_inv = $select_inv->fetch_object();

$change = query("SELECT * from tbl_change where aus='$aus'");
confirm($change);
$row_exchange = $change->fetch_object();
$exchange = $row_exchange->exchange;
$usd_or_real = $row_exchange->usd_or_real;

if ($usd_or_real == "usd") {
    $USD_usd = "$";
    $saleprice = $salepric;
} else {
    $USD_usd = "៛";

    $saleprice = $salepric;
}
$tbl_invoice = query("SELECT MAX(receipt_id) as receipt_num from tbl_invoice where aus='$aus'");
confirm($tbl_invoice);
$row = $tbl_invoice->fetch_object();
$receipt_id = $row->receipt_num + 1;

$tbl_product = query("SELECT * from tbl_product where pid= $productid and aus='$aus'");
confirm($tbl_product);
$row = $tbl_product->fetch_object();

if ($row->m_price > 0 and $row->num_category == 1) {
    $size = 'S';
} elseif ($row->m_price > 0 and $row->num_category == 2) {
    $size = 'កំប៉ុង';
} else {
    $size = 'N';
}


if (mysqli_num_rows($select_inv) == 0) {

    $insert = query("INSERT into tbl_invoice (receipt_id,table_id,table_name,saler_id,saler_name,aus) values('{$receipt_id}','{$table_id}','{$table_name}','{$saler_id}','{$saler_name}','{$aus}')");
    confirm($insert);
    $invoice_id = last_id();
    // update table status
    $UPDATE = query("UPDATE tables set status = 'unavailable' where id='$table_id'");
    confirm($UPDATE);
} else { // if there is a sale on the selected table

    $invoice_id = $row_inv->invoice_id;
}
$orderdate     = date('Y-m-d');
$total = 0;
$total = $saleprice * $qty;
$insert = query("INSERT into tbl_invoice_details (invoice_id,product_id,product_name,size,qty,saleprice,sale_total,saler_id,aus) values ('{$invoice_id}','{$productid}','{$product_name}','{$size}','{$qty}','{$saleprice}','{$total}','{$saler_id}','{$aus}')");
confirm($insert);

$UPDATE = query("UPDATE tbl_invoice set total = total+$total where invoice_id='$invoice_id'");
confirm($UPDATE);

$html = getSaleDetails($invoice_id);
echo $html; //testing 
