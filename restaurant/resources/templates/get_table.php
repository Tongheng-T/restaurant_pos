<?php

require_once("../config.php");

$aus = $_SESSION['aus'];
$tables = query("SELECT * FROM tables WHERE aus='$aus'");
confirm($tables);

$html = '';
foreach ($tables as $table) {
    $statusClass = ($table["status"] == "available") ? "badge-success" : "badge-danger";

    $html .= '<div class="col-md-1 col-sm-3 col-4 mb-4">';
    $html .= '<button class="btn btn-primary btn-table w-100" 
                   data-id="' . $table["id"] . '" 
                   data-name="' . $table["name"] . '">';
    $html .= '<img class="img-fluid md-2" src="../productimages/table/table.svg" />';
    $html .= '<span class="badge ' . $statusClass . '">' . $table["name"] . '</span>';
    $html .= '</button>';
    $html .= '</div>';
}
echo $html;


// $aus = $_SESSION['aus'];
// $tables = query("SELECT * from tables where aus='$aus'");
// confirm($tables);
// $html = '';
// foreach($tables as $table){
//     $html .= '<div class="col-md-1 col-sm-3 col-4 mb-4">';
//     $html .= 
//     '<button class="btn btn-primary btn-table" data-id="'.$table["id"].'" data-name="'.$table["name"].'">
//     <img class="img-fluid" src="../productimages/table/table.svg"/>
//     <br>';
//     if($table["status"] == "available"){
//         $html .= '<span class="badge badge-success">'.$table["name"].'</span>';
//     }else{ // a table is not available
//         $html .= '<span class="badge badge-danger">'.$table["name"].'</span>';
//     }
//     $html .='</button>';
//     $html .= '</div>';
// }
// echo $html;
