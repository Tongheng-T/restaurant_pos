<?php

require_once("../config.php");



if (isset($_POST['saleDetail_id'])) {


    $saleDetail_id = $_POST['saleDetail_id'];
    $select_inv = query("SELECT * from tbl_invoice_details where id= $saleDetail_id ");
    confirm($select_inv);
    $row_inv = $select_inv->fetch_object();
    $invoice_id = $row_inv->invoice_id;
    $product_id = $row_inv->product_id;
    $qty = $row_inv->qty;
    $sale_total = $row_inv->sale_total;
    $size = $row_inv->size;


    $tbl_product = query("SELECT * from tbl_product where pid= $product_id ");
    confirm($tbl_product);
    $row = $tbl_product->fetch_object();
    
    if ($size == 'M' AND $row->num_category == 1) {
        $size = 'S';
        $saleprice = $row->saleprice;
        $total = $row->saleprice * $qty;
    }elseif($size == 'ដប់' AND $row->num_category == 2){
        $size = 'កំប៉ុង';
        $saleprice = $row->saleprice;
        $total = $row->saleprice * $qty;
    }elseif($size == 'កំប៉ុង' AND $row->num_category == 2){
        $saleprice = $row->m_price;
        $total = $row->m_price * $qty;
        $size = 'ដប់';
    }
     else {
        $saleprice = $row->m_price;
        $total = $row->m_price * $qty;
        $size = 'M';
    }

    $UPDATE_invoice = query("UPDATE tbl_invoice set total = total-$sale_total+$total where invoice_id='$invoice_id'");
    confirm($UPDATE_invoice);

    $UPDATE = query("UPDATE tbl_invoice_details set size = '$size',saleprice='$saleprice',sale_total= '$total' where id='$saleDetail_id'");
    confirm($UPDATE);


    $html = getSaleDetails($invoice_id);

    echo $html;
}
