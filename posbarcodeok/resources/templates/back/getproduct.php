<?php

include_once '../../config.php';

$productid = $_GET["id"];

$barcode = $_GET["id"];

$select = query("SELECT * from tbl_product where pid=$productid OR barcode=$barcode");
confirm($select);

$row = $select->fetch_assoc();

$response = $row;

header('Content-Type: application/json');

echo json_encode($response);
