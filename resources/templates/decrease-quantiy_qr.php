<?php

require_once("../config.php");



if (isset($_POST['saleDetail_id'])) {
    $aus = $_POST['aus'];

    $saleDetail_id = $_POST['saleDetail_id'];
    $select_inv = query("SELECT * from tbl_invoice_details where id= $saleDetail_id ");
    confirm($select_inv);
    $row_inv = $select_inv->fetch_object();
    $invoice_id = $row_inv->invoice_id;
    $UPDATE = query("UPDATE tbl_invoice_details set qty = qty-1 , sale_total = sale_total-$row_inv->saleprice where id='$saleDetail_id'");
    confirm($UPDATE);



    $UPDATE_invoice = query("UPDATE tbl_invoice set total = total-$row_inv->saleprice where invoice_id='$invoice_id'");
    confirm($UPDATE_invoice);

    $html = getSaleDetails_qr($invoice_id,$aus);

    echo $html;
}
