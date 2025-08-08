<?php 

require_once("../config.php");



if (isset($_GET['id'])) {

    $select = query("SELECT * from tbl_product where category_id =" . $_GET['id'] . "");
    confirm($select);
   
    
    $html = '';
    foreach ($select as $menu) {
        $html .= '
            <div class="col-md-3 text-center padd">
                <a class="btn btn-outline-secondary btn-menu" data-id="' . $menu["pid"] . '">
                    <img class="img-fluid" src="../productimages/' . $menu["image"] . '">
                    <br>
                    ' . $menu["product"] . '
                    <br>
                    ៛' . number_format($menu["saleprice"]) . '
                </a>
            </div>
            
            ';
    }
    echo $html;

}






function getMenuByCategory($category_id)
{
    $select = query("SELECT * from tbl_product where category_id = $category_id");
    confirm($select);
   
    
    $html = '';
    foreach ($select as $menu) {
        $html .= '
            <div class="col-md-3 text-center">
                <a class="btn btn-outline-secondary btn-menu" data-id="' . $menu->catid . '">
                    <img class="img-fluid" src="../productimages/' . $menu->image . '">
                    <br>
                    ' . $menu->category . '
                    <br>
                    ៛' . number_format($menu->price) . '
                </a>
            </div>
            
            ';
    }
    return $html;
}

?>