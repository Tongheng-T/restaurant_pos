<?php
require_once("../../config.php");



if(empty($_SESSION['num'])){
    $_SESSION['num'] = 0;
}
$alert = $_SESSION['num'];
$select = queryC("SELECT count(alert) as num  from tbl_payment where alert = 0");
confirm($select);
$row = $select->fetch_object();

if ($row->num > $_SESSION['num']) {
    echo '<script>
    if ("Notification" in window) {
        if (Notification.permission === "granted") {
            notify(); 
        } else {
            Notification.requestPermission().then(res => {
                if (res === "granted") {
                    notify();
                } else {
                    console.error("Did not receive permission for notifications");
                }
            })
        }
    } else {
        console.error("Browser does not support notifications");
    }
          playMusic();
</script>';

}
$num = '';
if ($row->num == 0) {
    $_SESSION['num'] = 0;
} else {
    $num = $row->num;
    $_SESSION['num'] = $row->num;
}


echo $num ;
