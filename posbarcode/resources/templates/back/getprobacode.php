<?php

include_once '../../config.php';

$productid = $_GET["id"];


$query =  query("SELECT barcode as bacode from tbl_product WHERE pid = $productid" );
confirm($query);
$row = $query->fetch_assoc();

$response = $row;

header('Content-Type: application/json');

echo json_encode($response);
