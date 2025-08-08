<?php
$upload_directory = "uploads";

// helper function


use Google\Client;
use Google\Service\Oauth2;

require_once __DIR__ . '/../../vendor/autoload.php';

function redirect_to_google()
{
    $client = new Client();
    $client->setClientId('678847511198-c7rhe1h2udm2urs3j1hsul7srm0i0m1q.apps.googleusercontent.com');
    $client->setClientSecret('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');
    $client->setRedirectUri('http://thpos.com/restaurant/google/google-callback.php');
    $client->addScope('email');
    $client->addScope('profile');

    // Redirect to Google
    $authUrl = $client->createAuthUrl();
    header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
    exit;
}

// Call this function instead of create_acc()

function last_id()
{
    global $connection;
    return mysqli_insert_id($connection);
}

function set_message($msg)
{
    if (!empty($msg)) {
        $_SESSION['message'] = $msg;
    } else {
        $msg = "";
    }
}

function display_message()
{
    if (isset($_SESSION['message'])) {
        echo $_SESSION['message'];
        unset($_SESSION['message']);
    }
}
function actr($path)
{
    if (isset($_GET[$path])) {
        $active = "active";
        echo $active;
    }
}

function redirect($location)
{
    header("Location: $location");
}

function query($sql)
{
    global $connection;
    return mysqli_query($connection, $sql);
}

function confirm($result)
{
    global $connection;
    if (!$result) {
        die("QUERY FAILED" . mysqli_error($connection));
    }
}

function escape_string($string)
{
    global $connection;
    return mysqli_real_escape_string($connection, $string);
}


function fetch_array($result)
{

    return ($row = mysqli_fetch_array($result));
}
function convert_number_kh($day)
{
    $kh_day = ["០", "១", "២", "៣", "៤", "៥", "៦", "៧", "៨", "៩"];
    for ($i = 0; $i <= 9; $i++) {
        $day = str_replace($i, $kh_day[$i], $day);
    }
    return $day;
}
/*********************************FRONT END FUNCTIONS************************************/

function login_user()
{
    if (isset($_POST['btn_login'])) {
        $useremail = trim($_POST['txt_email']);
        $inputPassword = $_POST['txt_password'];

        $query = query("SELECT * FROM tbl_user WHERE useremail = '$useremail'");
        confirm($query);

        if (mysqli_num_rows($query) == 0) {
            show_error();
            return;
        }

        $row = $query->fetch_assoc();
        $hashedPassword = $row['userpassword'];
        $role = $row['role'];
        $verified = $row['verified'];

        if (!password_verify($inputPassword, $hashedPassword)) {
            show_error();
            return;
        }

        // Verified check for Admin only
        if ($role == 'Admin' && $verified != 1) {
            $_SESSION['useremail'] = $row['useremail'];
            $_SESSION['aus'] = $row['aus'];
            set_message_signin("<div class='alert alert-warning text-center'>
                Please verify your email before logging in - {$row['useremail']}</div>");
            header('Location: verify.php');
            return;
        }

        // Set session
        $_SESSION['userid'] = $row['user_id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['useremail'] = $row['useremail'];
        $_SESSION['role'] = $row['role'];
        $_SESSION['aus'] = $row['aus'];
        $_SESSION['location'] = $row['location_ip'];
        $_SESSION['login_type'] = $row['login_type'];

        // Update login time
        $date = new DateTime('now', new DateTimeZone('Asia/Bangkok'));
        $datee =  $date->format('Y-m-d H:i:s');
        $time = time() + 10;
        $res = query("UPDATE tbl_user SET login_online='$time', last_login='$datee' WHERE user_id=" . $_SESSION['userid']);
        confirm($res);

        // SweetAlert message
        $roleText = ($role == 'Admin') ? 'Admin' : 'User';
        set_message("<script>
            $(function() {
                Swal.fire({
                    toast: true,
                    position: 'top',
                    icon: 'success',
                    title: 'Login success By $roleText',
                    showConfirmButton: false,
                    timer: 5000
                });
            });
        </script>");

        header('refresh:2;url=../' . (($role == 'Admin') ? 'ui/' : 'user/'));
    }
}

function show_error()
{
    set_message("<script>
        $(function() {
            Swal.fire({
                toast: true,
                position: 'top',
                icon: 'error',
                title: 'Email or password incorrect!',
                showConfirmButton: false,
                timer: 5000
            });
        });
    </script>");
    redirect("");
}


function check_login()
{
    if (isset($_SESSION['userid'])) {

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

        if ($query && mysqli_num_rows($query) > 0) {

            $user_data = mysqli_fetch_assoc($query);
            return $user_data;
        } elseif ($numdatee <= 0) {
            set_message("<script>
           alert(' $numdatee')
          </script>");
        }
    }

    //redirect to login
    header("Location: ../");
    die;
}
function name_user()
{
    if (isset($_SESSION['userid'])) {
        $id = $_SESSION['userid'];
        $query =  query("SELECT * FROM tbl_user WHERE user_id =  $id ");
        confirm($query);
        while ($row = fetch_array($query)) {

            $name = <<<DELIMETER
        {$row['username']}

        DELIMETER;
        }
        echo $name;
    } else {
        echo "Hello";
    }
}

/*********************************BACK END FUNCTIONS************************************/

function changepassword()
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

                if ($update) {
                    set_message(' <script>
                            Swal.fire({
                              icon: "success",
                              title: "Password Updated successfully"
                            });
                          </script>');
                    redirect('itemt?changepassword');
                } else {
                    set_message(' <script>
                    Swal.fire({
                      icon: "error",
                      title: "Password Not Updated successfully"
                    });
                  </script>');
                    redirect('itemt?changepassword');
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
                redirect('itemt?changepassword');
            }
        } else {

            set_message(' <script>
            Swal.fire({
              icon: "error",
              title: "Password Deos Not Matched"
            });
          </script>');
            redirect('itemt?changepassword');
        }
    }
}



use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;



function create_acc()
{

    require '../../PHPMailer/src/Exception.php';
    require '../../PHPMailer/src/PHPMailer.php';
    require '../../PHPMailer/src/SMTP.php';
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
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'mrrbean88@gmail.com';
            $mail->Password = 'lxrs caql ygwf xxol';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;


            $mail->setFrom('thpos.store@gmail.com', 'THPOS');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = 'THPOS - Email Verification';
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
            redirect("login");
        } else {
            $email = $_SESSION['useremail'];
        }
        $query = query("SELECT verified,code,useremail from tbl_user where  code = '$vkey' and useremail='$email'");
        $queryt = query("SELECT * from tbl_user where  code=0");
        if (mysqli_num_rows($queryt) < 0) {
            set_message_signin("<div class='alert alert-danger text-center'>
            <p>Your account has been verified already.</p></div>");
            redirect("login");
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
                redirect("login");
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


function qr($idd, $aus, $user)
{
    $query =  query("SELECT * FROM tables WHERE id='$idd'");
    confirm($query);
    $row = $query->fetch_object();
    $name = $row->name;

    $uel = "http://192.168.1.5/coffee%20-%20Copy/order_qr?aus=$aus&TABLE_ID=$idd&TABLE_NAME=$name&user=$user";
    // $qrcode = 'https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl=' . $uel . '&choe=UTF-8';

    $qrcode = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=$uel";

    return $qrcode;
}

function forgot_pass()
{
    require '../../PHPMailer/src/Exception.php';
    require '../../PHPMailer/src/PHPMailer.php';
    require '../../PHPMailer/src/SMTP.php';
    //if user click continue button in forgot password form
    if (isset($_POST['check-email'])) {
        $email = escape_string($_POST['email']);
        $check_email = query("SELECT * FROM tbl_user WHERE useremail='$email'");
        if (mysqli_num_rows($check_email) > 0) {
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
                $mail->Username = 'mrrbean88@gmail.com';
                $mail->Password = 'lxrs caql ygwf xxol';
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
                    title: 'Please create a new password that you don't use on any other site.'
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
                header('Location: login');
            } else {
                $errors['db-error'] = "Failed to change your password!";
            }
        }
    }
}


function set_message_signin($msgg)
{
    if (!empty($msgg)) {
        $_SESSION['messagee'] = $msgg;
    } else {
        $msgg = "";
    }
}
function display_messag_signin()
{
    if (isset($_SESSION['messagee'])) {
        echo $_SESSION['messagee'];
    }
}

function random_num($length)
{

    $text = "";
    if ($length < 5) {
        $length = 5;
    }

    $len = rand(4, $length);

    for ($i = 0; $i < $len; $i++) {
        # code...

        $text .= rand(0, 14);
    }

    return $text;
}
function registration()
{
    if (isset($_POST['btnsave'])) {

        $username = $_POST['txtname'];
        $useremail = $_POST['txtemail'];
        $userpassword = md5($_POST['txtpassword']);
        $userrole = $_POST['txtselect_option'];

        $f_name                 = $_FILES['file']['name'];
        $image_temp_location    = $_FILES['file']['tmp_name'];
        $f_size                 = $_FILES['file']['size'];
        $f_extension            = explode('.', $f_name);
        $f_extension            = strtolower(end($f_extension));
        $user_photo             = uniqid() . '.' . $f_extension;
        $verified = 1;
        $aus = $_SESSION['aus'];
        $time = new DateTime('now', new DateTimeZone('Asia/bangkok'));
        $datee =  $time->format('Y-m-d H:i:s');

        $select_andmin = query("SELECT * from tbl_user where aus='$aus'");
        confirm($select_andmin);
        $rowadmin = $select_andmin->fetch_object();
        $date_new = $rowadmin->date_new;
        $tim = $rowadmin->tim;

        if (!empty($f_name)) {

            if ($f_extension == 'jpg' || $f_extension == 'jpeg' ||   $f_extension == 'png' || $f_extension == 'gif') {

                if ($f_size >= 1000000) {

                    set_message(' <script>
                Swal.fire({
                  icon: "warning",
                  title: "Max file should be 1MB"
                });
              </script>');
                    redirect('itemt?registration');
                } else {
                    move_uploaded_file($image_temp_location,  UPLOAD_DIRECTORY_UDER . DS . $user_photo);
                    $image = $user_photo;

                    if (isset($_POST['txtemail'])) {

                        $select = query("SELECT useremail from tbl_user where useremail='$useremail'");
                        confirm($select);


                        if (mysqli_num_rows($select) > 0) {
                            set_message(' <script>
                            Swal.fire({
                              icon: "warning",
                              title: "Email already exists. Create Account From New Email"
                            });
                           </script>');
                            redirect('itemt?registration');
                        } else {

                            $insert = query("INSERT into tbl_user (username,useremail,userpassword,img,role,createdate,date_new,verified,aus,tim) values('{$username}','{$useremail}','{$userpassword}','{$image}','{$userrole}','{$datee}','{$date_new}','{$verified}','{$aus}','{$tim}')");
                            confirm($insert);
                            if ($insert) {

                                set_message(' <script>
                                  Swal.fire({
                                  icon: "success",
                                  title: "Insert successfully the user into the database"
                                  });
                                 </script>');
                                redirect('itemt?registration');
                            } else {
                                set_message(' <script>
                                  Swal.fire({
                                  icon: "error",
                                  title: "Error inserting the user into the database"
                                  });
                                 </script>');
                                redirect('itemt?registration');
                            }
                        }
                    }
                }
            } else {

                set_message(' <script>
                Swal.fire({
                  icon: "warning",
                  title: "only jpg, jpeg, png and gif can be upload"
                });
              </script>');
                redirect('itemt?registration');
            }
        } else {
            $image = 'user.png';

            if (isset($_POST['txtemail'])) {

                $select = query("SELECT useremail from tbl_user where useremail='$useremail'");
                confirm($select);


                if (mysqli_num_rows($select) > 0) {
                    set_message(' <script>
                Swal.fire({
                  icon: "warning",
                  title: "Email already exists. Create Account From New Email"
                });
               </script>');
                    redirect('itemt?registration');
                } else {

                    $insert = query("INSERT into tbl_user (username,useremail,userpassword,img,role,createdate,date_new,verified,aus,tim) values('{$username}','{$useremail}','{$userpassword}','{$image}','{$userrole}','{$datee}','{$date_new}','{$verified}','{$aus}','{$tim}')");
                    confirm($insert);
                    if ($insert) {

                        set_message(' <script>
                      Swal.fire({
                      icon: "success",
                      title: "Insert successfully the user into the database"
                      });
                     </script>');
                        redirect('itemt?registration');
                    } else {
                        set_message(' <script>
                      Swal.fire({
                      icon: "error",
                      title: "Error inserting the user into the database"
                      });
                     </script>');
                        redirect('itemt?registration');
                    }
                }
            }
        }
    }





    if (isset($_POST['btnupdate'])) {

        $username = $_POST['txtname'];
        $useremail = $_POST['txtemail'];
        $password = $_POST['txtpassword'];
        $userpassword = md5($_POST['txtpassword']);
        $userrole = $_POST['txtselect_option'];
        $id = $_POST['btnupdate'];

        $f_name                = $_FILES['file']['name'];
        $image_temp_location   = $_FILES['file']['tmp_name'];
        $f_size                = $_FILES['file']['size'];
        $f_extension           = explode('.', $f_name);
        $f_extension           = strtolower(end($f_extension));
        $user_photo            = uniqid() . '.' . $f_extension;

        $select_img = query("SELECT img from tbl_user where user_id = $id");
        confirm($select_img);
        $row = $select_img->fetch_assoc();


        if (!empty($f_name)) {
            if ($f_extension == 'jpg' || $f_extension == 'jpeg' ||   $f_extension == 'png' || $f_extension == 'gif') {

                if ($f_size >= 1000000) {

                    set_message(' <script>
                Swal.fire({
                  icon: "warning",
                  title: "Max file should be 1MB"
                });
              </script>');
                    redirect('itemt?registration');
                } else {
                    move_uploaded_file($image_temp_location,  UPLOAD_DIRECTORY_UDER . DS . $user_photo);
                    $dbimage = $row['img'];
                    if ($dbimage != 'user.png') {
                        unlink("../resources/images/userpic/$dbimage");
                    }
                    $image = $user_photo;

                    if (isset($_POST['txtemail'])) {

                        $select = query("SELECT useremail from tbl_user where useremail='$useremail'");
                        confirm($select);


                        if (mysqli_num_rows($select) < 0) {
                            set_message(' <script>
                            Swal.fire({
                              icon: "warning",
                              title: "Email already exists. Create Account From New Email"
                            });
                           </script>');
                            redirect('itemt?registration');
                        } elseif (empty($password)) {

                            $insert = query("UPDATE tbl_user set username='$username' , useremail='$useremail', img='$image' , role='$userrole' where user_id='$id'");
                            confirm($insert);
                            if ($insert) {

                                set_message(' <script>
                                  Swal.fire({
                                  icon: "success",
                                  title: "Update User successfully"
                                  });
                                 </script>');
                                redirect('itemt?registration');
                            } else {
                                set_message(' <script>
                                  Swal.fire({
                                  icon: "error",
                                  title: "Error inserting the user into the database"
                                  });
                                 </script>');
                                redirect('itemt?registration');
                            }
                        } else {
                            $insert = query("UPDATE tbl_user set username='$username' , useremail='$useremail', userpassword='$userpassword', img='$image' , role='$userrole' where user_id='$id'");
                            confirm($insert);
                            if ($insert) {

                                set_message(' <script>
                                  Swal.fire({
                                  icon: "success",
                                  title: "Update User successfully..."
                                  });
                                 </script>');
                                redirect('itemt?registration');
                            } else {
                                set_message(' <script>
                                  Swal.fire({
                                  icon: "error",
                                  title: "Error inserting the user into the database"
                                  });
                                 </script>');
                                redirect('itemt?registration');
                            }
                        }
                    }
                }
            }
        } else {

            if (isset($_POST['txtemail'])) {

                $select = query("SELECT useremail from tbl_user where useremail='$useremail'");
                confirm($select);

                if (mysqli_num_rows($select) < 0) {
                    set_message(' <script>
                Swal.fire({
                  icon: "warning",
                  title: "Email already exists. Create Account From New Email"
                });
               </script>');
                    redirect('itemt?registration');
                } elseif (empty($password)) {

                    $insert = query("UPDATE tbl_user set username='$username' , useremail='$useremail' , role='$userrole' where user_id='$id'");
                    confirm($insert);
                    if ($insert) {

                        set_message(' <script>
                      Swal.fire({
                      icon: "success",
                      title: "Update User successfully "
                      });
                     </script>');
                        redirect('itemt?registration');
                    } else {
                        set_message(' <script>
                      Swal.fire({
                      icon: "error",
                      title: "Error inserting the user into the database"
                      });
                     </script>');
                        redirect('itemt?registration');
                    }
                } else {
                    $insert = query("UPDATE tbl_user set username='$username' , useremail='$useremail', userpassword='$userpassword', role='$userrole' where user_id='$id'");
                    confirm($insert);
                    if ($insert) {

                        set_message(' <script>
                      Swal.fire({
                      icon: "success",
                      title: "Update User successfully..."
                      });
                     </script>');
                        redirect('itemt?registration');
                    } else {
                        set_message(' <script>
                      Swal.fire({
                      icon: "error",
                      title: "Error inserting the user into the database"
                      });
                     </script>');
                        redirect('itemt?registration');
                    }
                }
            }
        }
    }
}

function show_delete($invoice_id)
{
    if ($_SESSION['useremail'] == "" or $_SESSION['role'] == "Admin") {

        return '
        <button id=' . $invoice_id . ' class="btn btn-danger  btndelete_orderlist"><span class="fa fa-trash" style="color:#ffffff" data-toggle="tooltip" title="Delete Order"></span></button>
        ';
    }
}


function edit_registration()
{
    if (isset($_POST['btnedit'])) {
        $aus = $_SESSION['aus'];
        $select = query("SELECT * from tbl_user where user_id =" . $_POST['btnedit']);
        confirm($select);

        $selectt = query("SELECT min(user_id) as user from tbl_user where aus= '$aus' ");
        $roww = $selectt->fetch_object();
        $user = $roww->user;
        $selectk = query("SELECT * from tbl_user where user_id = '$user'");
        $rows = $selectk->fetch_object();
        $useremail = $rows->useremail;

        if ($select) {
            $row = $select->fetch_object();

            if ($row->useremail == $useremail) {
                $default = 'defaultt';
            } else {
                $default = '';
            }

            echo ' <div class="col-md-4">
               <form action="" method="post" enctype="multipart/form-data">
              <div class="form-group">
                <label>រូបថត រូបភាព</label>
                <input type="file" class="input-group" name="file" onchange="displayImage(this)" id="profilImg">
                <img  src="../resources/images/userpic/' . $row->img . ' " onclick="triggerClick()" id="profiledisplay">
               </div>

                <div class="form-group">
                    <label for="exampleInputEmail1">Name</label>
                    <input type="text" class="form-control" placeholder="Enter Name" name="txtname" value="' . $row->username . '" required>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Email address</label>
                    <input type="email" class="form-control" placeholder="Enter email" name="txtemail" value="' . $row->useremail . '" required>
                </div>
                <div class="form-group">
                <label for="exampleInputPassword1">Password</label>
                <div class="input-group">
                    <input type="password" placeholder="Password" id="pwd" class="form-control" placeholder="Password" name="txtpassword" >
                    <button type="button" class="input-group-text" id="eye">
                    <i class="fa fa-eye" aria-hidden="true"></i>
                   </button>
                </div>
                </div>

                <div class="form-group">
                    <label>Role</label>
                    <select class="form-control ' . $default . '" name="txtselect_option" required>
                        <option value="" disabled selected>Select Role</option>
                        <option selected>' . $row->role . '</option>
                        <option>Admin</option>
                        <option>User</option>

                    </select>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-info" value="' . $row->user_id . '" name="btnupdate">Update</button>
                </div>
            </form>

        </div>';
        }
    } else {
        echo '<div class="col-md-4">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
               <label>រូបថត រូបភាព</label>
               <input type="file" class="input-group" name="file" onchange="displayImage(this)" id="profilImg">
               <img  src="../productimages/user/display.jpg " onclick="triggerClick()" id="profiledisplay">
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">Name</label>
                <input type="text" class="form-control" placeholder="Enter Name" name="txtname" required>
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">Email address</label>
                <input type="email" class="form-control" placeholder="Enter email" name="txtemail" required>
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1">Password</label>
                <input type="password" class="form-control" placeholder="Password" name="txtpassword" required>
            </div>

            <div class="form-group">
                <label>Role</label>
                <select class="form-control" name="txtselect_option" required>
                    <option value="" disabled selected>Select Role</option>
                    <option>Admin</option>
                    <option>User</option>

                </select>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary" name="btnsave">Save</button>
            </div>
        </form>

    </div>';
    }
}


function img_user()
{
    if (isset($_SESSION['userid'])) {
        $id = $_SESSION['userid'];
        $query =  query("SELECT * FROM tbl_user WHERE user_id =  $id ");
        confirm($query);
        while ($row = fetch_array($query)) {

            $name = <<<DELIMETER
        {$row['img']}

        DELIMETER;
        }
        echo $name;
    } else {
        echo "Hello.png";
    }
}










function edit_category()
{
    if (isset($_POST['btnedit'])) {

        $select = query("SELECT * from tbl_category where catid =" . $_POST['btnedit']);
        confirm($select);

        if ($select) {
            $row = $select->fetch_object();

            echo '<div class="col-md-4">

                <div class="form-group">
               <label for="exampleInputEmail1">Category</label>

               <input type="hidden" class="form-control" placeholder="Enter Category"  value="' . $row->catid . '" name="txtcatid" >

               <input type="text" class="form-control" placeholder="Enter Category"  value="' . $row->category . '" name="txtcategory" >
               </div>


               <div class="card-footer">
               <button type="submit" class="btn btn-info" name="btnupdate">Update</button>
               </div>
               </div>';
        }
    } else {

        echo '<div class="col-md-4">

         <div class="form-group">
         <label for="exampleInputEmail1">Category</label>
         <input type="text" class="form-control" placeholder="Enter Category"  name="txtcategory" >
         </div>
         <div class="card-footer">
         <button type="submit" class="btn btn-warning" name="btnsave">Save</button>
         </div>

         </div>';
    }
}

function edit_table()
{
    if (isset($_POST['btnedit'])) {

        $select = query("SELECT * from tables where id =" . $_POST['btnedit']);
        confirm($select);

        if ($select) {
            $row = $select->fetch_object();

            echo '<div class="col-md-4">

                <div class="form-group">
               <label for="exampleInputEmail1">table</label>

               <input type="hidden" class="form-control" placeholder="Enter table"  value="' . $row->id . '" name="txtcatid" >

               <input type="text" class="form-control" placeholder="Enter table"  value="' . $row->name . '" name="txtcategory" >
               </div>


               <div class="card-footer">
               <button type="submit" class="btn btn-info" name="btnupdate">Update</button>
               </div>
               </div>';
        }
    } else {

        echo '<div class="col-md-4">

         <div class="form-group">
         <label for="exampleInputEmail1">table</label>
         <input type="text" class="form-control" placeholder="Enter table"  name="txtcategory" >
         </div>
         <div class="card-footer">
         <button type="submit" class="btn btn-success" name="btnsave">Save</button>
         </div>

         </div>';
    }
}

function query_category()
{
    $aus = $_SESSION['aus'];
    $select = query("SELECT * from tbl_category where aus='$aus' order by catid ASC");
    confirm($select);
    $no = 1;
    while ($row = $select->fetch_object()) {
        echo '
        <tr>
        <td>' . $no . '</td>
        <td>' . $row->category . '</td>
        <td>
        <button type="submit" class="btn btn-primary" value="' . $row->catid . '" name="btnedit">Edit</button>
        </td>
        <td>
        <button type="submit" class="btn btn-danger" value="' . $row->catid . '" name="btndelete">Delete</button>
       </td>
      </tr>';
        $no++;
    }
}

function query_table()
{
    $aus = $_SESSION['aus'];
    $userid = $_SESSION['userid'];
    $select = query("SELECT * from tables where aus='$aus' order by id ASC");
    confirm($select);
    $no = 1;
    while ($row = $select->fetch_object()) {
        echo '
        <tr>
        <td>' . $no . '</td>
     
        <td>' . $row->name . '</td>
        <td>
        <button type="submit" class="btn btn-warning" value="' . $row->id . '" name="btnedit">Edit</button>
        </td>
        <td>
        <button type="submit" class="btn btn-danger" value="' . $row->id . '" name="btndelete">Delete</button>
       </td>
      </tr>';
        $no++;
    }
}
// <td><img height=100 src="' . qr($row->id, $aus, $userid) . '"></td>

function insert_update_delete()
{
    if (isset($_POST['btnsave'])) {
        $aus = $_SESSION['aus'];
        $category = $_POST['txtcategory'];

        if (empty($category)) {
            set_message(' <script>
            Swal.fire({
            icon: "warning",
            title: "Category Feild is Empty"
            });
           </script>');
            redirect('itemt?category');
        } else {

            $insert = query("INSERT into tbl_category (category,aus) values('{$category}','{$aus}') ");
            confirm($insert);
            if ($insert) {
                set_message(' <script>
                Swal.fire({
                icon: "success",
                title: "Category Added successfully"
                });
               </script>');
                redirect('itemt?category');
            } else {
                set_message(' <script>
                Swal.fire({
                icon: "warning",
                title: "Category Added Failed"
                });
               </script>');
                redirect('itemt?category');
            }
        }
    }



    if (isset($_POST['btnupdate'])) {

        $category = $_POST['txtcategory'];
        $id = $_POST['txtcatid'];

        if (empty($category)) {
            set_message(' <script>
            Swal.fire({
            icon: "warning",
            title: "Category Feild is Empty"
            });
           </script>');
            redirect('itemt?category');
        } else {

            $update = query("UPDATE tbl_category set category='$category' where catid=" . $id);
            confirm($update);

            if ($update) {
                set_message(' <script>
                Swal.fire({
                icon: "success",
                title: "Category Update successfully"
                });
               </script>');
                redirect('itemt?category');
            } else {
                set_message(' <script>
                Swal.fire({
                icon: "warning",
                title: "Category Update Failed"
                });
               </script>');
                redirect('itemt?category');
            }
        }
    }


    if (isset($_POST['btndelete'])) {

        $delete = query("DELETE from tbl_category where catid=" . $_POST['btndelete']);
        confirm($delete);
        if ($delete) {
            set_message(' <script>
            Swal.fire({
            icon: "success",
            title: "Deleted"
            });
           </script>');
            redirect('itemt?category');
        } else {
            set_message(' <script>
            Swal.fire({
            icon: "warning",
            title: "Delete Failed"
            });
           </script>');
            redirect('itemt?category');
        }
    } else {
    }
}

function insert_update_delete_table()
{
    if (isset($_POST['btnsave'])) {

        $tables = $_POST['txtcategory'];

        if (empty($tables)) {
            set_message(' <script>
            Swal.fire({
            icon: "warning",
            title: "tables Feild is Empty"
            });
           </script>');
            redirect('itemt?tables');
        } else {
            $aus = $_SESSION['aus'];
            $insert = query("INSERT into tables (name,aus) values('{$tables}','{$aus}')");
            confirm($insert);
            if ($insert) {
                set_message(' <script>
                Swal.fire({
                icon: "success",
                title: "tables Added successfully"
                });
               </script>');
                redirect('itemt?tables');
            } else {
                set_message(' <script>
                Swal.fire({
                icon: "warning",
                title: "tables Added Failed"
                });
               </script>');
                redirect('itemt?tables');
            }
        }
    }



    if (isset($_POST['btnupdate'])) {

        $tables = $_POST['txtcategory'];
        $id = $_POST['txtcatid'];

        if (empty($tables)) {
            set_message(' <script>
            Swal.fire({
            icon: "warning",
            title: "tables Feild is Empty"
            });
           </script>');
            redirect('itemt?tables');
        } else {

            $update = query("UPDATE tables set name='$tables' where id=" . $id);
            confirm($update);

            if ($update) {
                set_message(' <script>
                Swal.fire({
                icon: "success",
                title: "tables Update successfully"
                });
               </script>');
                redirect('itemt?tables');
            } else {
                set_message(' <script>
                Swal.fire({
                icon: "warning",
                title: "tables Update Failed"
                });
               </script>');
                redirect('itemt?tables');
            }
        }
    }


    if (isset($_POST['btndelete'])) {

        $delete = query("DELETE from tables where id=" . $_POST['btndelete']);
        confirm($delete);
        if ($delete) {
            set_message(' <script>
            Swal.fire({
            icon: "success",
            title: "Deleted"
            });
           </script>');
            redirect('itemt?tables');
        } else {
            set_message(' <script>
            Swal.fire({
            icon: "warning",
            title: "Delete Failed"
            });
           </script>');
            redirect('itemt?tables');
        }
    } else {
    }
}


function addproduct()
{
    if (isset($_POST['btnsave'])) {

        $product       = $_POST['txtproductname'];
        $category      = $_POST['txtselect_option'];
        $description   = $_POST['txtdescription'];

        $num_category     = $_POST['r1'];
        $saleprice     = $_POST['txtsaleprice'];
        $txtm_price     = $_POST['txtm_price'];

        //Image Code or File Code Start Here..
        $f_name        = $_FILES['myfile']['name'];
        $f_tmp         = $_FILES['myfile']['tmp_name'];
        $f_size        = $_FILES['myfile']['size'];
        $f_extension   = explode('.', $f_name);
        $f_extension   = strtolower(end($f_extension));
        $f_newfile     = uniqid() . '.' . $f_extension;
        $aus = $_SESSION['aus'];

        $change = query("SELECT * from tbl_change where aus='$aus'");
        confirm($change);
        $row_exchange = $change->fetch_object();
        $exchange = $row_exchange->exchange;
        $usd_or_real = $row_exchange->usd_or_real;
        if ($usd_or_real == "usd") {
            $salepricee = $saleprice;
            $txtm_pricee = $txtm_price;
        } else {
            $salepricee = $saleprice / $exchange;
            $txtm_pricee = $txtm_price / $exchange;
        }

        $store = "/www/wwwroot/restaurant_pos/restaurant/productimages/" . $f_newfile;

        if ($f_extension == 'jpg' || $f_extension == 'jpeg' ||   $f_extension == 'png' || $f_extension == 'gif') {

            if ($f_size >= 1000000) {

                set_message(' <script>
            Swal.fire({
              icon: "warning",
              title: "Max file should be 1MB"
            });
          </script>');
                redirect('itemt?addproduct');
            } else {

                if (move_uploaded_file($f_tmp, $store)) {

                    $productimage = $f_newfile;

                    $insert = query("INSERT into tbl_product ( product,category_id,description, saleprice,m_price,num_category,image,aus) 
                        values('{$product}','{$category}','{$description}','{$salepricee}','{$txtm_pricee}','{$num_category}','{$productimage}','{$aus}')");
                    confirm($insert);
                    $pid = last_id(); // which was the 5
                    if ($insert) {

                        set_message(' <script>
                            Swal.fire({
                              icon: "success",
                              title: "Product Inserted Successfully"
                            });
                          </script>');
                        redirect('itemt?addproduct');
                    } else {
                        set_message(' <script>
                            Swal.fire({
                              icon: "error",
                              title: "Product Inserted Failed"
                            });
                          </script>');
                        redirect('itemt?addproduct');
                    }
                }
            }
        } else {

            set_message(' <script>
            Swal.fire({
              icon: "warning",
              title: "only jpg, jpeg, png and gif can be upload"
            });
          </script>');
            redirect('itemt?addproduct');
        }
    }
}



function productlist()
{
    $aus = $_SESSION['aus'];
    $select = query("SELECT * from tbl_product where aus='$aus' order by pid ASC");
    confirm($select);

    $change = query("SELECT * from tbl_change where aus='$aus'");
    confirm($change);
    $row_exchange = $change->fetch_object();
    $exchange = $row_exchange->exchange;
    $usd_or_real = $row_exchange->usd_or_real;


    while ($row = $select->fetch_object()) {

        if ($usd_or_real == "usd") {
            $USD_usd = "$";
            $m_price = number_format($row->m_price, 2) . $USD_usd;
            $saleprice = number_format($row->saleprice, 2) . $USD_usd;
        } else {
            $USD_usd = "៛";
            $m_pricee = $row->m_price * $exchange;
            $salepricee = $row->saleprice * $exchange;
            $m_price = number_format($m_pricee) . $USD_usd;
            $saleprice = number_format($salepricee) . $USD_usd;
        }

        echo '
           <tr>

           <td>' . $row->product . '</td>
           <td>' . show_category_name($row->category_id) . '</td>
           <td>' . $row->description . '</td>
           
           <td>' . $saleprice . '</td>
           <td>' . $m_price . '</td>
           
           <td><image src="../productimages/' . $row->image . '" class="img-rounded" width="40px" height="40px/"></td>
           
           <td>
           
           <div class="btn-group">
                      
           
           <a href="itemt?viewproduct&id=' . $row->pid . '" class="btn btn-warning btn-xs" role="button"><span class="fa fa-eye" style="color:#ffffff" data-toggle="tooltip" title="View Product"></span></a>
           
           
           <a href="itemt?editproduct&id=' . $row->pid . '" class="btn btn-success btn-xs" role="button"><span class="fa fa-edit" style="color:#ffffff" data-toggle="tooltip" title="Edit Product"></span></a>
           
           <button id=' . $row->pid . '  class="btn btn-danger btn-xs btndelete"><span class="fa fa-trash" style="color:#ffffff" data-toggle="tooltip" title="Delete Product"></span></button>
           
           </div>
           
           </td>
           
           </tr>';
    }
}

function viewproduct()
{
    $id = $_GET['id'];

    $select = query("SELECT * from tbl_product where pid = $id");
    confirm($select);

    while ($row = $select->fetch_object()) {

        echo '
<div class="row">
<div class="col-md-6">

<ul class="list-group">

<center><p class="list-group-item list-group-item-info"><b>PRODUCT DETAILS</b></p></center>  

  <li class="list-group-item"><b>Product Name</b><span class="badge badge-warning float-right">' . $row->product . '</span></li>
  <li class="list-group-item"><b>Category</b> <span class="badge badge-success float-right">' . show_category_name($row->category_id) . '</span></li>
  <li class="list-group-item"><b>Description </b><span class="badge badge-primary float-right">' . $row->description . '</span></li>
  <li class="list-group-item"><b>Sale Price</b> <span class="badge badge-dark float-right">' . $row->saleprice . '</span></li>
</ul>
</div>

<div class="col-md-6">
<ul class="list-group">
<center><p class="list-group-item list-group-item-info"><b>PRODUCT IMAGE</b></p></center>  
<img src="../productimages/' . $row->image . '" class="img-thumbnail"/>
</ul>
</div>
</div>



';
    }
}

function fill_product()
{

    $output = '';
    $select = query("SELECT * from tbl_product order by product asc");
    confirm($select);

    foreach ($select as $row) {
        $output .= '<option value="' . $row["pid"] . '">' . $row["product"] . '</option>';
    }

    return $output;
}

function edit_product()
{

    $id = $_GET['id'];

    $select = query("SELECT * from tbl_product where pid=$id");
    confirm($select);

    $row = $select->fetch_assoc();

    $image_db = $row['image'];


    if (isset($_POST['btneditproduct'])) {

        // $barcode_txt       =$_POST['txtbarcode'];
        $product_txt       = $_POST['txtproductname'];
        $category_txt      = $_POST['txtselect_option'];
        $description_txt   = $_POST['txtdescription'];

        $saleprice_txt     = $_POST['txtsaleprice'];
        $m_price_txt     = $_POST['txtm_price'];
        $num_category     = $_POST['r1'];

        //Image Code or File Code Start Here..
        $f_name        = $_FILES['myfile']['name'];

        if (!empty($f_name)) {

            $f_tmp         = $_FILES['myfile']['tmp_name'];
            $f_size        = $_FILES['myfile']['size'];
            $f_extension   = explode('.', $f_name);
            $f_extension   = strtolower(end($f_extension));
            $f_newfile     = uniqid() . '.' . $f_extension;

            $store = "../productimages/" . $f_newfile;

            if ($f_extension == 'jpg' || $f_extension == 'jpeg' ||   $f_extension == 'png' || $f_extension == 'gif') {

                if ($f_size >= 1000000) {


                    set_message(' <script>
                     Swal.fire({
                       icon: "warning",
                       title: "Max file should be 1MB"
                     });
                   </script>');
                    redirect('itemt?productlist');
                } else {

                    if (move_uploaded_file($f_tmp, $store)) {

                        $f_newfile;
                        unlink("../productimages/$image_db");

                        $update = query("UPDATE tbl_product set product='$product_txt' , category_id='$category_txt' , description='$description_txt' , saleprice='$saleprice_txt',m_price='$m_price_txt' ,num_category='$num_category', image='$f_newfile' where pid=$id");
                        confirm($update);

                        if ($update) {
                            set_message(' <script>
                      Swal.fire({
                        icon: "success",
                        title: "Product Updated Successfully With New Image"
                      });
                    </script>');
                            redirect('itemt?productlist');
                        } else {
                            set_message(' <script>
                            Swal.fire({
                           icon: "error",
                           title: "Product Update Failed"
                            });
                          </script>');
                            redirect('itemt?productlist');
                        }
                    }
                }
            } else {
                set_message(' <script>
                 Swal.fire({
                   icon: "warning",
                   title: "only jpg, jpeg, png and gif can be upload"
                 });
               </script>');
                redirect('itemt?productlist');
            }
        } else {

            $update = query("UPDATE tbl_product set product='$product_txt' , category_id='$category_txt' , description='$description_txt'  , saleprice='$saleprice_txt', m_price='$m_price_txt',num_category='$num_category' , image='$image_db' where pid=$id");
            confirm($update);
            if ($update) {
                set_message(' <script>
                   Swal.fire({
                  icon: "success",
                  title: "Product Updated Successfully"
                   });
                 </script>');
                redirect('itemt?productlist');
            } else {
                set_message(' <script>
                   Swal.fire({
                  icon: "error",
                  title: "Product Update Failed"
                   });
                 </script>');
                redirect('itemt?productlist');
            }
        }
    }
}


function show_name_user($catid)
{
    $tblcategory = query("SELECT * from tbl_user where user_id = '$catid'");
    $row = $tblcategory->fetch_object();
    echo '
    <option selected  value="' . $row->user_id . '">' . $row->username . '</option>
';
}


function show_category_name($catid)
{
    $tblcategory = query("SELECT * from tbl_category where catid = '$catid'");
    $row = $tblcategory->fetch_object();

    return $row->category;
}



// //////////////////////////////////////////////oder table///////////////////////

function getSaleDetails($invoice_id)

{
    $aus = $_SESSION['aus'];
    $tbl_invoice = query("SELECT MAX(receipt_id) as receipt_num from tbl_invoice where aus='$aus'");
    confirm($tbl_invoice);
    $row = $tbl_invoice->fetch_object();
    $receipt_id = $row->receipt_num + 1;

    // list all saledetail
    $html = '<p>Sale ID: ' . $receipt_id . '</p>';
    $tbl_invoice_details = query("SELECT * from tbl_invoice_details where invoice_id=$invoice_id ");
    confirm($tbl_invoice_details);
    $html .= '<div class="table-responsive-md" style="overflow-y:scroll; height: 400px; border: 1px solid #343A40">
    <table class="table table-bordered table-hover text-center">
    <thead>
        <tr>
            <th scope="col">N0</th>
            <th scope="col">Menu</th>
            <th scope="col">Quantity</th>
            <th scope="col">Size</th>
            <th scope="col">Price</th>
            <th scope="col">Total</th>
            <th scope="col">Status</th>
        </tr>
    </thead>
    <tbody>';
    $showBtnPayment = true;
    $no = 1;
    foreach ($tbl_invoice_details as $invoice_details) {
        $aus = $_SESSION['aus'];
        $change = query("SELECT * from tbl_change where aus='$aus'");
        confirm($change);
        $row_exchange = $change->fetch_object();
        $exchange = $row_exchange->exchange;
        $usd_or_real = $row_exchange->usd_or_real;

        if ($usd_or_real == "usd") {
            $USD_usd = "$";
            $total = $invoice_details["saleprice"] * $invoice_details["qty"];
            $saleprice = $invoice_details["saleprice"];
        } else {
            $USD_usd = "៛";
            $total = $invoice_details["saleprice"] * $invoice_details["qty"] * $exchange;;
            $saleprice = $invoice_details["saleprice"] * $exchange;;
        }

        $decreaseButton = '<button class="btn btn-danger btn-sm btn-decrease-quantiy" disabled>-</button>';
        if ($invoice_details["qty"] > 1) {
            $decreaseButton = '<button data-id="' . $invoice_details["id"] . '" class="btn btn-danger btn-sm btn-decrease-quantiy">-</button>';
        }

        $product_id = $invoice_details["product_id"];


        $tbl_product = query("SELECT * from tbl_product where pid= $product_id ");
        confirm($tbl_product);
        $row = $tbl_product->fetch_object();
        if ($row->m_price > 0) {
            $disabled = '';
        } else {
            $disabled = 'disabled';
        }

        if ($invoice_details["size"] == 'M') {
            $color = 'info';
        } else {
            $color = 'success';
        }

        $html .= '
        <tr>
            <td>' . $no . '</td>
            <td>' . $invoice_details["product_name"] . '</td>
            <td>' . $decreaseButton . ' ' . $invoice_details["qty"] . ' <button data-id="' . $invoice_details["id"] . '" class="btn btn-primary btn-sm btn-increase-quantiy">+</button></td>
            <td><button data-id="' . $invoice_details["id"] . '" class="btn btn-' . $color . ' btn-sm btn-size" ' . $disabled . '> ' . $invoice_details["size"] . '</button></td>
            <td>' . $saleprice . ' </td>
            <td>' . $total . '</td>';
        if ($invoice_details["status"] == "noConfirm") {
            $showBtnPayment = false;
            $html .= '<td><a data-id="' . $invoice_details["id"] . '" class="btn btn-danger btn-delete-saledetail"><i class="far fa-trash-alt"></a></td>';
        } else { // status == "confirm"
            $html .= '<td><i class="fas fa-check-circle"></i></td>';
        }
        $html .= '</tr>';
        $no++;
    }
    $html .= '</tbody></table></div>';

    $tbl_invoice = query("SELECT * from tbl_invoice where invoice_id=$invoice_id ");
    confirm($tbl_invoice);
    $row = $tbl_invoice->fetch_object();
    $aus = $_SESSION['aus'];
    $change = query("SELECT * from tbl_change where aus='$aus'");
    confirm($change);
    $row_exchange = $change->fetch_object();
    $exchange = $row_exchange->exchange;
    $usd_or_real = $row_exchange->usd_or_real;

    if ($usd_or_real == "usd") {
        $USD_usd = "$";
        $totall = $USD_usd . number_format($row->total, 2);
    } else {
        $USD_usd = "៛";
        $totalll = $row->total * $exchange;;
        $totall = number_format($totalll) . $USD_usd;
    }

    $html .= '<hr>';
    $html .= '<h3>Total Amount: ' . $totall . '</h3>';

    if ($showBtnPayment) {
        $html .= '<button data-id="' . $invoice_id . '" data-totalAmount="' . $row->total . '" class="btn btn-success btn-block btn-payment" data-toggle="modal" data-target="#exampleModal">Payment</button>';
    } else {
        $html .= '<button data-id="' . $invoice_id . '" class="btn btn-warning btn-block btn-confirm-order">Confirm Order</button>';
    }


    return $html;
}


function get_IP_address()
{
    foreach (
        array(
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR'
        ) as $key
    ) {
        if (array_key_exists($key, $_SERVER) === true) {
            foreach (explode(',', $_SERVER[$key]) as $IPaddress) {
                $IPaddress = trim($IPaddress); // Just to be safe

                if (
                    filter_var(
                        $IPaddress,
                        FILTER_VALIDATE_IP,
                        FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
                    )
                    !== false
                ) {

                    return $IPaddress;
                }
            }
        }
    }
}


// ///////////////////////////////////////





function hang()
{

    if (isset($_POST['btnedit_ch'])) {

        $id       = $_POST['btnedit_ch'];

        $select = query("SELECT * from tbl_logo where id=$id");
        confirm($select);
        $row = $select->fetch_assoc();
        $image_db = $row['img'];

        // $barcode_txt       =$_POST['txtbarcode'];

        $txtname       = $_POST['txtname'];
        $txtaddres      = $_POST['txtaddres'];
        $txtrod      = $_POST['txtrod'];
        $txtphone      = $_POST['txtphone'];


        //Image Code or File Code Start Here..
        $f_name        = $_FILES['file']['name'];

        if (!empty($f_name)) {

            $f_tmp         = $_FILES['file']['tmp_name'];
            $f_size        = $_FILES['file']['size'];
            $f_extension   = explode('.', $f_name);
            $f_extension   = strtolower(end($f_extension));
            $f_newfile     = uniqid() . '.' . $f_extension;

            $store = "../productimages/logo/" . $f_newfile;

            if ($f_extension == 'jpg' || $f_extension == 'jpeg' ||   $f_extension == 'png' || $f_extension == 'gif') {

                if ($f_size >= 1000000) {


                    set_message(' <script>
                          Swal.fire({
                            icon: "warning",
                            title: "Max file should be 1MB"
                          });
                        </script>');
                    redirect('itemt?exchange');
                } else {

                    if (move_uploaded_file($f_tmp, $store)) {
                        $f_newfile;
                        if ($image_db != 'logo.png') {
                            unlink("../productimages/logo/$image_db");
                        }

                        $update = query("UPDATE tbl_logo set  name='$txtname',addres='$txtaddres' ,img='$f_newfile',road='$txtrod',phone='$txtphone' where id=$id");
                        confirm($update);

                        if ($update) {
                            set_message(' <script>
            Swal.fire({
              icon: "success",
              title: "Product Updated Successfully With New Image"
            });
          </script>');
                            redirect('itemt?exchange');
                        } else {
                            set_message(' <script>
           Swal.fire({
          icon: "error",
          title: "Product Update Failed"
           });
         </script>');
                            redirect('itemt?exchange');
                        }
                    }
                }
            } else {
                set_message(' <script>
            Swal.fire({
              icon: "warning",
              title: "only jpg, jpeg, png and gif can be upload"
            });
          </script>');
                redirect('itemt?exchange');
            }
        } else {

            $update = query("UPDATE tbl_logo set name='$txtname',addres='$txtaddres',road='$txtrod',phone='$txtphone'  where id=$id");
            confirm($update);
            if ($update) {

                set_message(' <script>
      Swal.fire({
     icon: "success",
     title: "Product Updated Successfully"
      });
    </script>');
                redirect('itemt?exchange');
            } else {
                set_message(' <script>
      Swal.fire({
     icon: "error",
     title: "Product Update Failed"
      });
    </script>');
                redirect('itemt?exchange');
            }
        }
    }
}



function save_st()
{

    if (isset($_POST['save_st'])) {

        $id            = $_SESSION['userid'];

        $select = query("SELECT * from tbl_user where user_id=$id");
        confirm($select);
        $row = $select->fetch_assoc();
        $image_db = $row['img'];

        // $barcode_txt       =$_POST['txtbarcode'];

        $birthdayy      = $_POST['birthday'];
        $birthday = date("Y-m-d", strtotime($birthdayy));
        $province      = $_POST['province'];
        $username      = $_POST['username'];


        //Image Code or File Code Start Here..
        $f_name        = $_FILES['file']['name'];

        if (!empty($f_name)) {

            $f_tmp         = $_FILES['file']['tmp_name'];
            $f_size        = $_FILES['file']['size'];
            $f_extension   = explode('.', $f_name);
            $f_extension   = strtolower(end($f_extension));
            $f_newfile     = uniqid() . '.' . $f_extension;

            $store = "../resources/images/userpic/" . $f_newfile;

            if ($f_extension == 'jpg' || $f_extension == 'jpeg' ||   $f_extension == 'png' || $f_extension == 'gif') {

                if ($f_size >= 1000000) {


                    set_message(' <script>
                          Swal.fire({
                            icon: "warning",
                            title: "Max file should be 1MB"
                          });
                        </script>');
                    redirect('itemt?settings');
                } else {

                    if (move_uploaded_file($f_tmp, $store)) {
                        $f_newfile;
                        if ($image_db != 'user.png') {
                            unlink("../resources/images/userpic/$image_db");
                        }

                        $update = query("UPDATE tbl_user set  username='$username',img='$f_newfile',location='$province' ,birthday='$birthday' where user_id=$id");
                        confirm($update);

                        if ($update) {
                            set_message(' <script>
            Swal.fire({
              icon: "success",
              title: "General Updated Successfully With New Image"
            });
          </script>');
                            redirect('itemt?settings');
                        } else {
                            set_message(' <script>
           Swal.fire({
          icon: "error",
          title: "General Update Failed"
           });
         </script>');
                            redirect('itemt?settings');
                        }
                    }
                }
            } else {
                set_message(' <script>
            Swal.fire({
              icon: "warning",
              title: "only jpg, jpeg, png and gif can be upload"
            });
          </script>');
                redirect('itemt?settings');
            }
        } else {

            $update = query("UPDATE tbl_user set  username='$username',location='$province' ,birthday='$birthday' where user_id=$id");
            confirm($update);
            if ($update) {

                set_message(' <script>
      Swal.fire({
     icon: "success",
     title: "General Updated Successfully"
      });
    </script>');
                redirect('itemt?settings');
            } else {
                set_message(' <script>
      Swal.fire({
     icon: "error",
     title: "General Update Failed"
      });
    </script>');
                redirect('itemt?settings');
            }
        }
    }
}



// 



function add_expense()
{
    global $connection; // ប្រើតំណភ្ជាប់ DB

    if (isset($_POST['btnsave_expense'])) {
        $aus             = $_SESSION['aus'];
        $expense_name    = trim($_POST['txt_expense_name']);
        $expense_category = $_POST['txt_expense_category'];
        $expense_date    = $_POST['txt_date'];
        $amount          = $_POST['txt_amount'];
        $description     = $_POST['txt_description'];

        // Upload File
        $receipt_path = null;
        if (!empty($_FILES['txt_receipt']['name'])) {
            $target_dir  = "../productimages/receipts/";
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            $file_name   = time() . "_" . basename($_FILES["txt_receipt"]["name"]);
            $target_file = $target_dir . $file_name;

            $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $allowed   = ['jpg', 'jpeg', 'png', 'pdf'];

            if (in_array($file_type, $allowed)) {
                if (move_uploaded_file($_FILES["txt_receipt"]["tmp_name"], $target_file)) {
                    $receipt_path = $file_name;
                } else {
                    set_message("<div class='alert alert-danger'>Upload file បរាជ័យ!</div>");
                    return;
                }
            } else {
                set_message("<div class='alert alert-danger'>ប្រភេទឯកសារមិនត្រឹមត្រូវ!</div>");
                return;
            }
        }

        // បញ្ចូលទៅក្នុង Database
        $stmt = $connection->prepare("INSERT INTO tbl_expense (aus, expense_name, expense_category, expense_date, amount, description, receipt_path) VALUES (?,?,?,?,?,?,?)");
        $stmt->bind_param("sssssss", $aus, $expense_name, $expense_category, $expense_date, $amount, $description, $receipt_path);

        if ($stmt->execute()) {

            set_message(' <script>
             Swal.fire({
             icon: "success",
             title: "បានបញ្ចូលចំណាយថ្មីដោយជោគជ័យ!"
             });
            </script>');
        } else {

            set_message(' <script>
             Swal.fire({
             icon: "error",
             title: "មានបញ្ហា: $stmt->error ' . $stmt->error . '"
             });
            </script>');
        }

        $stmt->close();
    }
}

function update_expense()
{
    if (isset($_POST['btnupdate_expense'])) {
        $id = escape_string($_POST['expense_id']);
        $name = escape_string($_POST['edit_expense_name']);
        $cat = escape_string($_POST['edit_expense_category']);
        $date = escape_string($_POST['edit_date']);
        $amount = escape_string($_POST['edit_amount']);
        $desc = escape_string($_POST['edit_description']);

        query("UPDATE tbl_expense SET 
      expense_name='$name',
      expense_category='$cat',
      expense_date='$date',
      amount='$amount',
      description='$desc'
      WHERE id='$id'");
        set_message(' <script>
             Swal.fire({
             icon: "success",
             title: "បានកែប្រែចំណាយ!"
             });
            </script>');
        redirect("itemt?expense");
    }
}


function delete_expense()
{
    if (isset($_GET['delete_expense'])) {
        $id = escape_string($_GET['delete_expense']);

        // ទាញ path រូបភាពមុនពេលលុប
        $result = query("SELECT receipt_path FROM tbl_expense WHERE id='$id'");
        confirm($result);

        if ($row = fetch_array($result)) {
            $file_path = "../productimages/receipts/" . $row['receipt_path'];

            // បើមានរូបភាព និងមាន file នៅក្នុង folder ទើបលុប
            if (!empty($row['receipt_path']) && file_exists($file_path)) {
                unlink($file_path);
            }
        }

        // លុបចំណាយចេញពី Database
        query("DELETE FROM tbl_expense WHERE id='$id'");

        // Message
        set_message(' 
            <script>
                Swal.fire({
                    icon: "success",
                    title: "បានលុបចំណាយ និងរូបភាពជោគជ័យ!"
                });
            </script>');

        redirect("itemt?expense");
    }
}
