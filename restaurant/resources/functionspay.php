<?php

function savepay()
{
    if (!isset($_POST['payuser'])) {
        return; // មិនមានការបញ្ជូន form
    }

    $numberidpay = trim($_POST['numberidpay']);
    $id_service  = intval($_POST['payuser']); // បម្លែងជាចំនួនសុវត្ថិភាព

    // ពិនិត្យ File Upload
    if (empty($_FILES['file']['name']) || $_FILES['file']['error'] !== 0) {
        set_message('<script>
            Swal.fire({
              icon: "warning",
              title: "សូមបញ្ចូលរូបភាពបង់ប្រាក់"
            });
        </script>');
        redirect('itemt?pos');
        return;
    }

    // ពិនិត្យប្រភេទឯកសារ (MIME type)
    $allowed_types = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
    if (!in_array(mime_content_type($_FILES['file']['tmp_name']), $allowed_types)) {
        set_message('<script>
            Swal.fire({
              icon: "error",
              title: "ប្រភេទឯកសារមិនអនុញ្ញាត",
              text: "សូមជ្រើសរូបភាព JPG, PNG ឬ WebP"
            });
        </script>');
        redirect('itemt?pos');
        return;
    }

    // បង្កើតឈ្មោះ File
    $f_extension = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
    $user_photo  = uniqid("pay_", true) . '.' . $f_extension;
    $image_temp_location = $_FILES['file']['tmp_name'];

    $aus      = $_SESSION['aus'] ?? '';
    $username = $_SESSION['username'] ?? '';
    $userid   = $_SESSION['userid'] ?? 0;

    // ទាញព័ត៌មានសេវាកម្ម
    $select = query("SELECT * FROM tbl_service WHERE id='$id_service' LIMIT 1");
    confirm($select);
    $row_service = $select->fetch_assoc();
    if (!$row_service) {
        set_message('<script>
            Swal.fire({
              icon: "error",
              title: "មិនមានសេវាកម្មនេះទេ"
            });
        </script>');
        redirect('itemt?pos');
        return;
    }

    $tim       = $row_service['tim'];
    $num_month = $row_service['num_month'];
    $store = "../resources/images/userpay/". $user_photo;
    // ផ្ទេររូបភាព
    if (!move_uploaded_file($image_temp_location, $store )) {
        set_message('<script>
            Swal.fire({
              icon: "error",
              title: "បញ្ហា Upload File"
             
            });
        </script>');
        redirect('itemt?pos');
        return;
    }

    // Insert ទៅ tbl_payment
    $insert = query("
        INSERT INTO tbl_payment 
        (user_name, id_service, numberidpay, img, num_month, tim, aus) 
        VALUES 
        ('{$username}','{$id_service}','{$numberidpay}','{$user_photo}','{$num_month}','{$tim}','{$aus}')
    ");
    confirm($insert);

    // Update tbl_user
    $date_now = (new DateTime('now', new DateTimeZone('Asia/Bangkok')))->format('Y-m-d');
    query("UPDATE tbl_user SET date_new='{$date_now}', tim='1' WHERE user_id='{$userid}'");

    // Success Message
    set_message('<script>
        Swal.fire({
          title: "សូមរង់ចាំ... ",
          text: "អ្នកអាចប្រើបានបណ្ដោះអាសន្ន ប្រព័ន្ធកំពុងអនុម័ត។ លទ្ធផលនឹងបានក្នុង ១៥ នាទី!",
          width: 600,
          padding: "3em",
          color: "#716add",
          background: "#fff url(https://sweetalert2.github.io/images/trees.png)",
          backdrop: `
            rgba(0,0,123,0.4)
            url("https://sweetalert2.github.io/images/nyan-cat.gif")
            left top
            no-repeat
          `
        });
    </script>');
    redirect('itemt?pos');
}



function service_list()
{
    $aus = $_SESSION['aus'];
    $select = query("SELECT * from tbl_service ");
    confirm($select);

    while ($row = $select->fetch_object()) {

        if ($row->free == 0) {
            $free = '<span>ឥតគិតថ្លៃ</span>
             <i class="fas fa-times"></i>';
        } else {
            $free = '<span>ឥតគិតថ្លៃ ' . convert_number_kh($row->free) . 'ខែ</span>
             <i class="fas fa-check"></i>';
        }

        echo '<div class="card ' .  $row->class . '">
             <div class="top">
                 <div class="title">' . convert_number_kh($row->num_month) . 'ខែ</div>	
                 <div class="price-sec">
                     <span class="dollar">$</span>
                     <span class="price">' . $row->price_show . '</span>
                     <span class="decimal">.99</span>
                 </div>
             </div>
             <div class="info">' . $row->text . '</div>
             <div class="details">
                 <div class="one">
                     <span>' . $row->expires . '</span>
                     <i class="fas fa-check"></i>
                 </div>
                 <div class="one">
                     ' . $free . '
                 </div>

                 <button id=' . $row->id . ' type="button" class="id" data-toggle="modal" data-target="#exampleModalpay">Purchase</button>
             </div>
         </div>';
    }
}


function num_message()
{

    if (!empty($_SESSION['aus'])) {
        $aus = $_SESSION['aus'];
        $select = query("SELECT count(viw) as num from tbl_message where aus='$aus' and viw = 0");
        confirm($select);
        $row = $select->fetch_object();
        if ($row->num == 0) {
            $num = '';
        } else {
            $num = $row->num;
        }

        return $num;
    }
}



function timeago($date, $tense = 'ago')
{
    date_default_timezone_set("asia/phnom_penh");
    $time = date($date);
    static $periods = array('year', 'month', 'day', 'hour', 'minute', 'second');
    if (!(strtotime($time) > 0)) {
        return trigger_error("wrong time format: '$time'", E_USER_ERROR);
    }
    $now = new DateTime('now', new DateTimeZone('Asia/bangkok'));
    $time = new DateTime($time);
    $diff = $now->diff($time)->format('%y %m %d %h %i %s');
    $diff = explode(' ', $diff);
    $diff = array_combine($periods, $diff);
    $diff = array_filter($diff);

    $period = key($diff);
    $value = current($diff);
    if (!$value) {
        $period = '';
        $tense = '';
        $value = 'just now';
    } else {
        if ($period == 'day' && $value >= 7) {
            $period = 'week';
            $value = floor($value / 7);
        }
        if ($value > 1) {
            $period .= 's';
        }
    }
    return "$value $period $tense";
}


function show_message_pay()
{
    if (!empty($_SESSION['aus'])) {
        $aus = $_SESSION['aus'];
        $select = query("SELECT * from tbl_message where aus='$aus' order by id DESC");
        confirm($select);
        while ($row = $select->fetch_object()) {
            $active = '';
            $messages =  $row->messages;
            $danger = '';
            if ($messages == "ការទូទាត់បរាជ័យ") {
                $danger = 'text-danger';
            }
            if ($row->viw == 0) {
                $active = '<i class="fa fa-circle text-success"></i>';
                $messages =  '<b>' . $row->messages . '</b>';
                $text =  '<p></p>';
            }

            echo '   
        <button id=' . $row->id . ' class=" dropdown-item viw ' . $danger . '" type="button">
        <i class="fas fa-envelope mr-2"></i> ' . $messages . '
        <span class="float-right text-muted text-sm">' . $active . ' ' . timeago($row->date) . '</span>
      </button>';
        }
    }
}


function ket()
{
    $a = array("ខេត្តបាត់ដំបង", "ខេត្តកំពង់ចាម", "ខេត្តកំពង់ឆ្នាំង", "ខេត្តព្រះសីហនុ (កំពង់សោម)", "ខេត្តកំពង់ស្ពឺ", "ខេត្តកំពង់ធំ", "ខេត្តកំពត", "ខេត្តកោះកុង", "ខេត្តក្រចេះ", "ខេត្តប៉ៃលិន", "រាជធានីភ្នំពេញ", "ខេត្តតាកែវ", "ខេត្តព្រៃវែង", "ខេត្តពោធិ៍សាត់", "ខេត្តសៀមរាប", "ខេត្តស្ទឹងត្រែង", "ខេត្តស្វាយរៀង", "ខេត្តឧត្ដរមានជ័យ", "ខេត្តព្រះវិហារ", "ខេត្តបន្ទាយមានជ័យ", "ខេត្តកណ្តាល", "ខេត្តរតនៈគិរី", "ខេត្តមណ្ឌលគិរី", "ខេត្តត្បូងឃ្មុំ", "ខេត្តកែប");

    foreach ($a as $key => $value):

        echo '<option value="' . $key . '">' . $value . '</option>';
    endforeach;
}

function changepasswordd()
{
    if (isset($_POST['btnupdate'])) {

        $oldpassword_txt = md5($_POST['txt_oldpassword']);
        $newpassword_txt = md5($_POST['txt_newpassword']);
        $rnewpassword_txt = md5($_POST['txt_rnewpassword']);

        //echo $oldpassword_txt."-".$newpassword_txt."-".$rnewpassword_txt;


        // 2 Step) Using of select Query we will get out database records according to useremail.

        $email = $_SESSION['useremail'];

        $select = query("SELECT * from tbl_user where useremail='$email'");
        confirm($select);

        $row = $select->fetch_assoc();

        $useremail_db = $row['useremail'];
        $password_db = $row['userpassword'];



        // 3 Step) We will compare the user inputs values to database values.

        if ($oldpassword_txt == $password_db) {

            if ($newpassword_txt == $rnewpassword_txt) {

                // 4 Step) If values will match then we will run update Query. 


                $update = query("UPDATE tbl_user set userpassword='$rnewpassword_txt' where useremail='$email'");
                confirm($update);
                $_SESSION['change'] = 'active';

                if ($update) {
                    set_message(' <script>
                            Swal.fire({
                              icon: "success",
                              title: "Password Updated successfully"
                            });
                          </script>');
                    redirect('itemt?settings');
                } else {
                    set_message(' <script>
                    Swal.fire({
                      icon: "error",
                      title: "Password Not Updated successfully"
                    });
                  </script>');
                    redirect('itemt?settings');
                }

                // $_SESSION['status']="New Password Matched";
                // $_SESSION['status_code']="success";

            } else {
                set_message(' <script>
                    Swal.fire({
                      icon: "error",
                      title: "New Password Deos Not Matched"
                    });
                  </script>');
                redirect('itemt?settings');
            }
        } else {
            $_SESSION['change'] = 'active';


            set_message(' <script>
            Swal.fire({
              icon: "error",
              title: "Password Deos Not Matched"
            });
          </script>');
            redirect('itemt?settings');
        }
    }
}


function service_list_dom()
{

    $select = query("SELECT * from tbl_service ");
    confirm($select);
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


function show_usd()
{
    $aus = $_SESSION['aus'];
    $change = query("SELECT * from tbl_change where aus='$aus'");
    confirm($change);
    $row_exchange = $change->fetch_object();
    $exchange = $row_exchange->usd_or_real;
    if ($exchange == "usd") {
        echo '$';
    } else {
        echo '៛';
    }
}
