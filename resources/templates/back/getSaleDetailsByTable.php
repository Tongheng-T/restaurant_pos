<?php

include_once '../../config.php';

$table_id = $_GET["table_id"];
$aus = $_SESSION['aus'];
$tbl_invoice = query("SELECT * from tbl_invoice where table_id=$table_id and aus='$aus'and sale_status= 'unpaid'");
confirm($tbl_invoice);
$html = '';
if (mysqli_num_rows($tbl_invoice) > 0) {
    $row = $tbl_invoice->fetch_object();
    $invoice_id = $row->invoice_id;
    $html .= getSaleDetails_qr($invoice_id,$aus);
} else {
    $html .= "Not Found Any Sale Details for the Selected Table";
}
echo $html;
