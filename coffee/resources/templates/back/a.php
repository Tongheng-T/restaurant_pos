<?php
require_once("../../config.php");

if (!empty($_SESSION['aus'])) {
    // echo num_message();
    if (empty($_SESSION['num'])) {
        $_SESSION['num'] = 0;
    }
    $aus = $_SESSION['aus'];
    $select = queryC("SELECT count(viw) as num  from tbl_message where aus='$aus' and viw = 0");
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

    echo $num;
}
