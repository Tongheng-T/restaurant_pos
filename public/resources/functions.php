<?php
function query($sql)
{
    global $connection;
    return mysqli_query($connection, $sql);
}
function convert_number_kh($day)
{
    $kh_day = ["០", "១", "២", "៣", "៤", "៥", "៦", "៧", "៨", "៩"];
    for ($i = 0; $i <= 9; $i++) {
        $day = str_replace($i, $kh_day[$i], $day);
    }
    return $day;
}
function service_list_dom()
{

    $select = query("SELECT * from tbl_service ");

    $i = 4;
    while ($row = $select->fetch_object()) {

        if ($row->free == 0) {
            $free = '<i class="ri-close-circle-line"></i>
                    ឥតគិតថ្លៃ';
        } else {
            $free = '<i class="ri-checkbox-circle-line"></i>
                    ឥតគិតថ្លៃ ' . convert_number_kh($row->free) . 'ខែ';
        }

        echo '<div class="card">
            <div class="content">
                <h4 class="fonkh">' . convert_number_kh($row->num_month) . 'ខែ</h4>
                <h3>$' . $row->price_show . '.99</h3>
                <p class="fonkh">
                    <i class="ri-checkbox-circle-line"></i>
                    សុពលភាព ១ខែ $9.99/mo.
                </p>
                <p class="fonkh">
                    ' . $free . '
                </p>
            </div>
            <button id="link' . $i . '" class="btn">Join Now</button>
        </div>';
        $i++;
    }
}