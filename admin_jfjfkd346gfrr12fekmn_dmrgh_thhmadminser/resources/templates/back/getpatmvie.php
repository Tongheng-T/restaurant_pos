<?php

include_once '../../config.php';

$productid = $_GET["id"];


$query =  query("SELECT MAX(pat)+1 as minimum from sub_products WHERE product_id = $productid" );
confirm($query);
$row = $query->fetch_assoc();

$response = $row;

header('Content-Type: application/json');

echo json_encode($response);
