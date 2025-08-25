<?php
include_once '../../config.php';

if ($_SESSION['useremail'] == "" || $_SESSION['role'] == "User") {
    header("Location: ../../../");
    exit();
}

$id = $_POST['piddd'] ?? null;

if (!$id) {
    echo "error: Invalid invoice ID";
    exit();
}

// 1. Select detail (if exists)
$select = query("SELECT * FROM tbl_invoice_details WHERE invoice_id=?", [$id]);
$details = $select->fetchAll(PDO::FETCH_ASSOC);

// 2. Update stock if details exist
if ($details) {
    foreach ($details as $product_invoice_details) {
        $update = query(
            "UPDATE tbl_product SET stock = stock + ? WHERE pid=?",
            [$product_invoice_details['qty'], $product_invoice_details['product_id']]
        );
    }
}

// 3. Delete details (safe, even if no row)
query("DELETE FROM tbl_invoice_details WHERE invoice_id=?", [$id]);

// 4. Delete invoice itself
$delete = query("DELETE FROM tbl_invoice WHERE invoice_id=?", [$id]);

if ($delete->rowCount() > 0) {
    echo "success";
} else {
    echo "error: Invoice not found or already deleted";
}
