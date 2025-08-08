<?php
include_once '../../config.php';

if ($_SESSION['useremail'] == "" or $_SESSION['role'] == "User") {
    header("Location: ../../");
}

$id = $_POST['pidd'];
$select = query("SELECT * FROM products where product_id='$id'");
confirm($select);
$row =  $select->fetch_object();
$image = $row->product_image;
$delete = query("DELETE from products where product_id =$id");
confirm($delete);
$delete = query("DELETE from sub_products where product_id =$id");
confirm($delete);

if ($delete) {
    unlink("../../../../productimages/movi/$image");
} else {

    echo "Error in deleting product";
}

?>