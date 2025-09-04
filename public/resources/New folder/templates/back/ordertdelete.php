<?php

include_once '../../config.php';

if ($_SESSION['useremail'] == "" or $_SESSION['role'] == "User") {
    header("Location: ../../../");
}


$id = $_POST['pid'];


$delete = query("DELETE tbl_invoice, tbl_invoice_details from tbl_invoice INNER JOIN tbl_invoice_details ON tbl_invoice.invoice_id = tbl_invoice_details.invoice_id WHERE tbl_invoice.invoice_id='$id'");
confirm($delete);



if ($delete) {
    echo 'ok';
} else {

    echo 'error: Failed to delete';
}
