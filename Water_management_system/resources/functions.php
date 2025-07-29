<?php
$upload_directory = "uploads";

// helper function

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

// function queryC($sql)
// {
//     global $connectionC;
//     return mysqli_query($connectionC, $sql);
// }

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

/*********************************FRONT END FUNCTIONS************************************/

function login_user()
{
    if (isset($_POST['btn_login'])) {

        $useremail = $_POST['txt_email'];
        $password = md5($_POST['txt_password']);

        $query = query("SELECT * from tbl_user where useremail='$useremail' AND userpassword='$password' and role ='Admin'");
        confirm($query);

        if (mysqli_num_rows($query) == 0) {
            $query2 = query("SELECT * from tbl_user where useremail='$useremail' AND userpassword='$password' and role ='User'");
            confirm($query2);
            if (mysqli_num_rows($query2) == 0) {
                set_message(" <script>
                $(function() {
                    var Toast = Swal.mixin({
                        toast: true,
                        position: 'top',
                        showConfirmButton: false,
                        timer: 5000
                    });
                    Toast.fire({
                        icon: 'error',
                        title: 'អ៊ីមែល ឬពាក្យសម្ងាត់ខុស ឬវាលគឺទទេ!'
                    })
                });
              </script>");
                redirect("");
            } else {
                $row =  $query2->fetch_assoc();
                $_SESSION['userid'] = $row['user_id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['useremail'] = $row['useremail'];
                $_SESSION['role'] = $row['role'];
                $_SESSION['aus'] = $row['aus'];

                $date = new DateTime('now', new DateTimeZone('Asia/bangkok'));
                $datee =  $date->format('Y-m-d H:i:s');
                $time = time() + 10;
                $res = query("UPDATE tbl_user set login_online='$time', last_login='$datee' where user_id=" . $_SESSION['userid']);
                confirm($res);

                set_message(" <script>
                $(function() {
                    var Toast = Swal.mixin({
                        toast: true,
                        position: 'top',
                        showConfirmButton: false,
                        timer: 5000
                    });
                    Toast.fire({
                        icon: 'success',
                        title: 'Login success By User'
                    })
                });
              </script>");
                header('refresh:2;user/');
            }
        } else {
            $row =  $query->fetch_assoc();
            $verified = $row['verified'];
            $email = $row['useremail'];
            $date = $row['createdate'];
            if ($verified == 1) {

                $_SESSION['userid'] = $row['user_id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['useremail'] = $row['useremail'];
                $_SESSION['role'] = $row['role'];
                $_SESSION['aus'] = $row['aus'];

                $date = new DateTime('now', new DateTimeZone('Asia/bangkok'));
                $datee =  $date->format('Y-m-d H:i:s');
                $time = time() + 10;
                $res = query("UPDATE tbl_user set login_online='$time', last_login='$datee' where user_id=" . $_SESSION['userid']);
                confirm($res);

                set_message(" <script>
            $(function() {
                var Toast = Swal.mixin({
                    toast: true,
                    position: 'top',
                    showConfirmButton: false,
                    timer: 5000
                });
                Toast.fire({
                    icon: 'success',
                    title: 'Login success By Admin'
                })
            });
          </script>");

                header('refresh:2;ui/');
            } else {

                $_SESSION['useremail'] = $row['useremail'];
                $_SESSION['aus'] = $row['aus'];
                set_message_signin("<div class='alert alert-success text-center'>
                It's look like you haven't still verify your email - $email on $date</div>");
                header('location: ../apii/verify');
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

function check_login()
{
    if (isset($_SESSION['userid'])) {

        $id = $_SESSION['userid'];
        $query = query("SELECT * from tbl_user where user_id = '$id' limit 1");

        if ($query && mysqli_num_rows($query) > 0) {

            $user_data = mysqli_fetch_assoc($query);
            return $user_data;
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









function convert_month_kh($value)
{
    $kh_month =
        '{
            "01": "មករា",
            "1": "មករា",
            "02": "កុម្ភៈ",
            "2": "កុម្ភៈ",
            "03": "មិនា",
            "3": "មិនា",
            "04": "មេសា",
            "4": "មេសា",
            "05": "ឧសភា",
            "5": "ឧសភា",
            "06": "មិថុនា",
            "6": "មិថុនា",
            "07": "កក្កដា",
            "7": "កក្កដា",
            "08": "សីហា",
            "8": "សីហា",
            "09": "កញ្ញា",
            "9": "កញ្ញា",
            "10": "តុលា",
            "11": "វិចិ្ឆកា",
            "12": "ធ្នូ"
        }';


    $month = json_decode($kh_month);
    return $month->$value;
}
function convert_date($date)
{
    $dates = explode("-", $date);
    $month = convert_month_kh($dates[1]);
    return "$month";
}


function convert_number_kh($day)
{
    $kh_day = ["០", "១", "២", "៣", "៤", "៥", "៦", "៧", "៨", "៩"];
    for ($i = 0; $i <= 9; $i++) {
        $day = str_replace($i, $kh_day[$i], $day);
    }
    return $day;
}


function date_rank()
{


    if (isset($_POST['btnrank'])) {
        $date = $_POST['date'];
        $monthly = date('m', strtotime($date));
        $year = date('Y', strtotime($date));
        echo convert_month_kh($monthly);
    } else {
        $date = $_SESSION['date'];
        $monthly = date('m', strtotime($date));
        $year = date('Y', strtotime($date));
        echo convert_month_kh($monthly);
    }
}

function date_rankk()
{


    if (isset($_POST['btnrank'])) {
        $date = $_POST['date'];
        $monthly = date('m', strtotime($date));
        $year = date('Y', strtotime($date));
        echo convert_month_kh($monthly) . convert_number_kh($year);
    } else {
        $date = $_SESSION['date'];
        $monthly = date('m', strtotime($date));
        $year = date('Y', strtotime($date));
        echo convert_month_kh($monthly) . " " . convert_number_kh($year);
    }
}

function show_categoryname($pid)
{
    $tbl_product = query("SELECT * from tbl_user where user_id ='$pid'");
    $row = $tbl_product->fetch_object();
    return $row->username;
}




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


