<?php
include_once '../../config.php';
$saleDetail_id = $_POST["saleDetail_id"];


$tbl_invoice_details = query("SELECT * from tbl_invoice_details where id=$saleDetail_id ");
confirm($tbl_invoice_details);

$row_inv = $tbl_invoice_details->fetch_object();
$saleprice = $row_inv->saleprice * $row_inv->qty;
$invoice_id = $row_inv->invoice_id;

$tbl_invoice = query("SELECT * from tbl_invoice where invoice_id=$invoice_id ");
confirm($tbl_invoice);

$row = $tbl_invoice->fetch_object();
$total = $row->total - $saleprice;
//update total price


$UPDATE = query("UPDATE tbl_invoice set total = $total where invoice_id='$invoice_id'");
confirm($UPDATE);



$delete = query("DELETE from tbl_invoice_details where id=" . $_POST['saleDetail_id']);
confirm($delete);

if($delete){
    $html = getSaleDetails($invoice_id);
}else{
    $html = "Not Found Any Sale Details for the Selected Table";
}
echo $html;