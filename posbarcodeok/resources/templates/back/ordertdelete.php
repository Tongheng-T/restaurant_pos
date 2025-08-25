<?php
include_once '../../config.php';
session_start();

if ($_SESSION['useremail'] == "" || $_SESSION['role'] == "User") {
    header("Location: ../../../");
    exit();
}

$id = $_POST['piddd'] ?? null;

if (!$id) {
    echo "error: Invalid invoice ID";
    exit();
}

// 1. សួរទិន្នន័យ detail
$select = mysqli_query($connection, "SELECT * FROM tbl_invoice_details WHERE invoice_id='$id'");

// 2. បើមាន detail → update stock
if (mysqli_num_rows($select) > 0) {
    while ($row = mysqli_fetch_assoc($select)) {
        $qty   = $row['qty'];
        $pid   = $row['product_id'];

        mysqli_query($connection, "UPDATE tbl_product SET stock = stock + $qty WHERE pid='$pid'");
    }
}

// 3. លុប detail ទាំងអស់ (មាន / មិនមានក៏បាន)
mysqli_query($connection, "DELETE FROM tbl_invoice_details WHERE invoice_id='$id'");

// 4. លុប invoice
$delete = mysqli_query($connection, "DELETE FROM tbl_invoice WHERE invoice_id='$id'");

if ($delete) {
    echo "success";
} else {
    echo "error: cannot delete invoice";
}
