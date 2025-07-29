<?php

include_once '../../config.php';



if (!empty($_GET["id"])) {
    $barcode = $_GET["id"];
    $query =  query("SELECT * from tbl_product WHERE barcode = $barcode");
    confirm($query);

    if (mysqli_num_rows($query) == 1) {
        $row = $query->fetch_object();
        $img = $row->image;
        echo '<img width="300" src="../productimages/' . $img . '" alt="">';
    }
} elseif (!empty($_GET["pid"])) {

    $id = $_GET["pid"];
    $query =  query("SELECT * from tbl_product WHERE pid = $id");
    confirm($query);

    if (mysqli_num_rows($query) == 1) {
        $row = $query->fetch_object();
        $img = $row->image;
        echo '<img width="300" src="../productimages/' . $img . '" alt="">';
    }
}
