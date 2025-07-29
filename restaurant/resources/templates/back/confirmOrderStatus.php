<?php

include_once '../../config.php';

$invoice_id = $_POST["sale_id"];


$UPDATE = query("UPDATE tbl_invoice_details set status = 'confirm' where invoice_id='$invoice_id'");
confirm($UPDATE);


$html = getSaleDetails($invoice_id);
echo $html;
