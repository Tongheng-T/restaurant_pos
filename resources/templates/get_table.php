<?php 

require_once("../config.php");
$aus = $_SESSION['aus'];
$tables = query("SELECT * from tables where aus='$aus'");
confirm($tables);
$html = '';
foreach($tables as $table){
    $html .= '<div class="col-md-1 mb-4">';
    $html .= 
    '<button class="btn btn-primary btn-table" data-id="'.$table["id"].'" data-name="'.$table["name"].'">
    <img class="img-fluid" src="../productimages/table/table.svg"/>
    <br>';
    if($table["status"] == "available"){
        $html .= '<span class="badge badge-success">'.$table["name"].'</span>';
    }else{ // a table is not available
        $html .= '<span class="badge badge-danger">'.$table["name"].'</span>';
    }
    $html .='</button>';
    $html .= '</div>';
}
echo $html;








?>