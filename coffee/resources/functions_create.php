<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


function create_acc()
{

    require '../PHPMailer/src/Exception.php';
    require '../PHPMailer/src/PHPMailer.php';
    require '../PHPMailer/src/SMTP.php';
    if (isset($_POST['signup'])) {
        $username = trim($_POST['username']);
        $email = trim($_POST['ap_email']);
        $password = $_POST['password'];
        $password_re = $_POST['password_re'];
        $lat = $_POST['latitude'];
        $lng = $_POST['longitude'];

        // 1. Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            set_message("<div class='alert alert-danger text-center'>
                <p>Email format is invalid!</p></div>");
            return;
        }

        // 2. Check for duplicate username or email
        $email_check = query("SELECT 1 FROM tbl_user WHERE useremail = '$email'");
        $username_check = query("SELECT 1 FROM tbl_user WHERE username = '$username'");

        if (strlen($username) < 5) {
            set_message("<div class='alert alert-danger text-center'>
                <p>Username must be at least 5 characters!</p></div>");
            return;
        }

        if ($password !== $password_re) {
            set_message("<div class='alert alert-danger text-center'>
                <p>Passwords do not match!</p></div>");
            return;
        }

        if (mysqli_num_rows($email_check) > 0) {
            set_message("<div class='alert alert-danger text-center'>
                <p>Email already exists!</p></div>");
            return;
        }

        if (mysqli_num_rows($username_check) > 0) {
            set_message("<div class='alert alert-danger text-center'>
                <p>Username already exists!</p></div>");
            return;
        }

        // 3. Free Trial Logic
        $tbl_date_free = query("SELECT number_of_date FROM tbl_date_free LIMIT 1");
        $row = $tbl_date_free->fetch_assoc();
        $numdatefree = $row['number_of_date'] ?? 30; // fallback if not set

        $vkey = rand(111111, 999999);
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $user_id = $vkey + time();

        $createdate = (new DateTime('now', new DateTimeZone('Asia/Bangkok')))->format('Y-m-d H:i:s');

        // 1. Get the user's IP address
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
        $location = 'Unknown';

        // 2. Check if not localhost (127.0.0.1) or private IP
        if (!in_array($ip, ['127.0.0.1', '::1'])) {
            $url = "http://ip-api.com/json/{$ip}";
            $response = @file_get_contents($url);

            if ($response !== false) {
                $geo = json_decode($response, true);

                if (!empty($geo) && $geo['status'] === 'success') {
                    $city = $geo['city'] ?? '';
                    $region = $geo['regionName'] ?? '';
                    $country = $geo['country'] ?? '';
                    $location = "{$city}, {$region}, {$country}";
                }
            }
        }



        // 4. Email Verification First
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'mail.thposs.uk';
            $mail->SMTPAuth = true;
            $mail->Username = 'thpos@thposs.uk';
            $mail->Password = 'T0ngh3ng';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;


            $mail->setFrom('thpos@thposs.uk', 'THPOS Coffee');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = 'THPOS Coffee- Email Verification';
            $mail->Body = "Your verification code is: <h2>$vkey</h2>";
            $mail->send();


            // 5. Save to DB after email sent
            query("INSERT INTO tbl_user (username, useremail, userpassword, createdate, date_new, code, aus, tim, lat, lng,login_type,location_ip)
                    VALUES ('$username', '$email', '$hashed_password', '$createdate', '$createdate', '$vkey', '$user_id', '$numdatefree', '$lat', '$lng','manual','$location')");

            query("INSERT INTO tbl_change (aus) VALUES('$user_id')");
            query("INSERT INTO tbl_taxdis (aus) VALUES('$user_id')");
            query("INSERT INTO tbl_logo (name, img, aus) VALUES('TH POS', 'logo.png', '$user_id')");

            $_SESSION['useremail'] = $email;

            set_message_signin("<div class='alert alert-success text-center'>
                We've sent a verification code to your email - $email</div>");
            redirect("verify.php");
        } catch (Exception $e) {
            // ✅ Show generic error
            set_message("<div class='alert alert-danger text-center'>
                <p>Sorry, we couldn't send the verification email. Please try again later or use another email.</p></div>");
        }
    }
}

function verify()
{

    if (isset($_POST['cerify_code'])) {
        $vkey = $_POST['code'];

        if (empty($_SESSION['useremail'])) {
            redirect("coffee");
        } else {
            $email = $_SESSION['useremail'];
        }
        $query = query("SELECT verified,code,useremail from tbl_user where  code = '$vkey' and useremail='$email'");
        $queryt = query("SELECT * from tbl_user where  code=0");
        if (mysqli_num_rows($queryt) < 0) {
            set_message_signin("<div class='alert alert-danger text-center'>
            <p>Your account has been verified already.</p></div>");
            redirect("coffee");
        } elseif ($query->num_rows > 0) {
            $fetch_data = mysqli_fetch_assoc($query);
            $fetch_code = $fetch_data['code'];
            $email = $fetch_data['useremail'];
            $code = 0;
            $verified = 1;
            $update = query("UPDATE tbl_user set code = $code , verified = '$verified'  WHERE code = $fetch_code ");
            confirm($update);
            if ($update) {
                set_message_signin(" <script>
            $(function() {
                var Toast = Swal.mixin({
                    toast: true,
                    position: 'top',
                    showConfirmButton: false,
                    timer: 5000
                });
                Toast.fire({
                    icon: 'success',
                    title: 'verify code success. Now you can login password.'
                })
            });
          </script>");
                redirect("../");
            } else {
                set_message_signin("<div class='alert alert-danger text-center'>
                <p>Failed while updating code!</p></div>");
            }
        } else {
            set_message_signin("<div class='alert alert-danger text-center'>
            You've entered incorrect code!</div>");
        }
    }
}



function forgot_pass()
{
    require '../PHPMailer/src/Exception.php';
    require '../PHPMailer/src/PHPMailer.php';
    require '../PHPMailer/src/SMTP.php';
    //if user click continue button in forgot password form
    if (isset($_POST['check-email'])) {
        $email = escape_string($_POST['email']);
        $check_email = query("SELECT * FROM tbl_user WHERE useremail='$email'");
        if (mysqli_num_rows($check_email) > 0) {
            $rowmail = $check_email->fetch_object();
            $emailDB = $rowmail->role;
            if ($emailDB == "User") {
                set_message_signin(" <script>
                $(function() {
                    var Toast = Swal.mixin({
                        toast: true,
                        position: 'top',
                        showConfirmButton: false,
                        timer: 5000
                    });
                    Toast.fire({
                        icon: 'warning',
                        title: 'សូមទាក់ទងទៅអ្នកគ្រប់គ្រង!'
                    })
                });
              </script>");
            } else {


                $code = rand(999999, 111111);
                $insert_code = query("UPDATE tbl_user SET code = $code WHERE useremail = '$email'");
                if ($insert_code) {
                    // $subject = "Password Reset Code";
                    // $message = "Your password reset code is: <h2>$code</h2>";
                    // $headers = "From: mrrbean88@gmail.com \r\n";
                    // $headers .= "MIME-Version: 1.0" . "\r\n";
                    // $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

                    $_SESSION['useremail'] = $email;
                    $mail = new PHPMailer(true);
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'thpos.store@gmail.com';
                    $mail->Password = 'mkuq bumn cjzc pkyf';
                    $mail->Port     = 587;

                    $mail->setFrom('thpos.store@gmail.com');
                    $mail->addAddress($email);
                    $mail->isHTML(true);
                    $mail->Subject = 'THPOS ';
                    $mail->Body = "Your password reset code is: <h2>$code</h2>";
                    $mail->send();


                    if ($mail) {

                        set_message_signin(" <script>
                    $(function() {
                        var Toast = Swal.mixin({
                            toast: true,
                            position: 'top',
                            showConfirmButton: false,
                            timer: 5000
                        });
                        Toast.fire({
                            icon: 'success',
                            title: 'We sent a password reset otp to your email  $email'
                        })
                    });
                  </script>");

                        $_SESSION['useremail'] = $email;
                        header('location: reset-code');
                        exit();
                    } else {
                        set_message_signin(" <script>
                    $(function() {
                        var Toast = Swal.mixin({
                            toast: true,
                            position: 'top',
                            showConfirmButton: false,
                            timer: 5000
                        });
                        Toast.fire({
                            icon: 'error',
                            title: 'Failed while sending code!'
                        })
                    });
                  </script>");
                    }
                } else {
                    set_message_signin(" <script>
                $(function() {
                    var Toast = Swal.mixin({
                        toast: true,
                        position: 'top',
                        showConfirmButton: false,
                        timer: 5000
                    });
                    Toast.fire({
                        icon: 'warning',
                        title: 'Something went wrong!'
                    })
                });
              </script>");
                }
            }
        } else {

            set_message_signin(" <script>
                $(function() {
                    var Toast = Swal.mixin({
                        toast: true,
                        position: 'top',
                        showConfirmButton: false,
                        timer: 5000
                    });
                    Toast.fire({
                        icon: 'info',
                        title: 'This email address does not exist!'
                    })
                });
              </script>");
        }
    }
}


function reset_code()
{
    $email = $_SESSION['useremail'];
    //if user click check reset otp button
    if (isset($_POST['check-reset-otp'])) {

        $otp_code = escape_string($_POST['otp']);
        $check_code = query("SELECT * FROM tbl_user WHERE code = $otp_code and useremail = '$email'");
        if (mysqli_num_rows($check_code) > 0) {
            $fetch_data = mysqli_fetch_assoc($check_code);
            $email = $fetch_data['useremail'];
            $_SESSION['useremail'] = $email;

            set_message_signin(" <script>
            $(function() {
                var Toast = Swal.mixin({
                    toast: true,
                    position: 'top',
                    showConfirmButton: false,
                    timer: 5000
                });
                Toast.fire({
                    icon: 'info',
                    title: 'Please create a new password.'
                })
            });
          </script>");
            header('location: new-password');
            exit();
        } else {
            set_message_signin(" <script>
            $(function() {
                var Toast = Swal.mixin({
                    toast: true,
                    position: 'top',
                    showConfirmButton: false,
                    timer: 5000
                });
                Toast.fire({
                    icon: 'error',
                    title: 'You entered incorrect code!'
                })
            });
          </script>");
        }
    }
}



function change_pass()
{
    if (isset($_POST['change-password'])) {
        $password = escape_string($_POST['password']);
        $cpassword = escape_string($_POST['cpassword']);
        if ($password !== $cpassword) {
            set_message_signin(" <script>
            $(function() {
                var Toast = Swal.mixin({
                    toast: true,
                    position: 'top',
                    showConfirmButton: false,
                    timer: 5000
                });
                Toast.fire({
                    icon: 'error',
                    title: 'Confirm password not matched!'
                })
            });
          </script>");
        } else {
            $code = 0;
            $email = $_SESSION['useremail']; //getting this email using session
            $password = md5($password);
            $update_pass = query("UPDATE tbl_user SET code = $code, userpassword = '$password' WHERE useremail = '$email'");
            if ($update_pass) {

                set_message_signin(" <script>
            $(function() {
                var Toast = Swal.mixin({
                    toast: true,
                    position: 'top',
                    showConfirmButton: false,
                    timer: 5000
                });
                Toast.fire({
                    icon: 'success',
                    title: 'Your password changed. Now you can login with your new password.'
                })
            });
          </script>");
                header('Location: ../coffee');
            } else {
                $errors['db-error'] = "Failed to change your password!";
            }
        }
    }
}



// ////////////////////////////////////////

function savepay()
{
    if (isset($_POST['payuser'])) {

        $numberidpay = $_POST['numberidpay'];
        $id_service = $_POST['payuser'];

        $f_name = $_FILES['file']['name'];
        $image_temp_location = $_FILES['file']['tmp_name'];
        $aus = $_SESSION['aus'];
        $username = $_SESSION['username'];
        $f_extension   = explode('.', $f_name);
        $f_extension   = strtolower(end($f_extension));
        $f_newfile     = uniqid() . '.' . $f_extension;

        $select = query("SELECT * from tbl_service where id='$id_service'");
        confirm($select);
        $row =  $select->fetch_assoc();
        $tim = $row['tim'];
        $num_month = $row['num_month'];

        if (!empty($f_name)) {
            // move_uploaded_file($image_temp_location, "../resources/images/userpic/" . $f_name);
            move_uploaded_file($image_temp_location,  UPLOAD_DIRECTORY_IDPAY . DS . $f_newfile);
            $image = $f_newfile;
            $insert = query("INSERT INTO tbl_payment (user_name,id_service,numberidpay,img,num_month,tim,aus) values('{$username}','{$id_service}','{$numberidpay}','{$image}','{$num_month}','{$tim}','{$aus}')");
            confirm($insert);

            $id = $_SESSION['userid'];
            $query = query("SELECT * from tbl_user where user_id = '$id' limit 1");
            $row = $query->fetch_object();
            $showdate = $row->date_new;
            $date = new DateTime('now', new DateTimeZone('Asia/bangkok'));
            $new_date =  $date->format('Y-m-d');
            $datetime4 = new DateTime($new_date);
            $datetime3 = new DateTime($showdate);
            $intervall = $datetime3->diff($datetime4);
            $texttt =   $intervall->format('%a');
            $numdatee =  $texttt - $row->tim;

            $update = query("UPDATE tbl_user set date_new='$new_date', tim='1' where user_id='$id'");
            confirm($update);
            if ($insert) {

                set_message(' <script>
                Swal.fire({
                  title: "សូមរង់ចាំ... ",
                  text: "អ្នកអាចប្រើបណ្ដោះអាសន្នបាន ប្រព័ន្ធកំពុងធ្វើការអនុម័ត។ លទ្ធផលនឹងត្រូវបានផ្ដល់ឲ្យដឹងក្នុងរយៈពេល១៥នាទីបន្ទាប់។ សូមអរគុណ!",
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
        } else {
            set_message(' <script>
                Swal.fire({
                  icon: "warning",
                  title: "សូមបញ្ចូលរូបភាពបង់ប្រាក់"
                });
               </script>');
            redirect('itemt?pos');
        }
    }
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
             <div class="detailss">
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
