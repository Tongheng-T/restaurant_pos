<?php

require_once("../config.php");



if (isset($_GET['id'])) {
    $aus = $_SESSION['aus'];
    $change = query("SELECT * from tbl_change where aus='$aus'");
    confirm($change);
    $row_exchange = $change->fetch_object();
    $exchange = $row_exchange->exchange;
    $usd_or_real = $row_exchange->usd_or_real;

    $select = query("SELECT * from tbl_product where category_id =" . $_GET['id'] . "");
    confirm($select);
    $id = $_SESSION['userid'];
    $query = query("SELECT * from tbl_user where user_id = '$id' limit 1");
    $row = $query->fetch_object();
    $showdate = $row->date_new;
    $new_date = date('Y-m-d');
    $datetime4 = new DateTime($new_date);
    $datetime3 = new DateTime($showdate);
    $intervall = $datetime3->diff($datetime4);
    $texttt =   $intervall->format('%a');
    $numdatee = $row->tim - $texttt;
    $defaultt = '';
    if ($numdatee <= 0) {
        $defaultt = 'defaultt';
    }


    $html = '';
    foreach ($select as $menu) {
        if ($usd_or_real == "usd") {
            $USD_usd = "$";
            $USD_txt = "USD";
            $m_price = $USD_usd . number_format($menu["m_price"], 2);

            $saleprice = $USD_usd . number_format($menu["saleprice"], 2);
        } else {
            $USD_usd = "៛";
            $USD_txt = "KHR";
            $m_pricee = $menu["m_price"] * $exchange;
            $salepricee = $menu["saleprice"] * $exchange;
            $saleprice =  number_format($salepricee) . $USD_usd;
            $m_price = number_format($m_pricee) . $USD_usd;
        }

        $html .= '
            <div class="padd col-md-3 text-center">
                <a class="btn btn-outline-secondary btn-menu ' . $defaultt . '" data-id="' . $menu["pid"] . '">
                    <img class="img-fluid" src="../productimages/' . $menu["image"] . '">
                    <h6  style="padding-top: 5px;">
                    ' . $menu["product"] . '
                    </h6>
                    <b>' . $saleprice . ' / M ' . $m_price . '</b>
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
                    ៛' . number_format($menu->price, 2) . '
                </a>
            </div>
            
            ';
    }
    return $html;
}
