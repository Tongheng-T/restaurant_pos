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
                header('location: ../-/verify');
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

function query_category()
{
    $aus = $_SESSION['aus'];
    $select = query("SELECT * from tbl_category where aus=$aus order by catid ASC");
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


function insert_update_delete()
{
    $aus = $_SESSION['aus'];
    if (isset($_POST['btnsave'])) {

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

            $insert = query("INSERT into tbl_category (category,aus) values('{$category}','{$aus}')");
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


function addproduct()
{
    $aus = $_SESSION['aus'];
    if (isset($_POST['btnsave'])) {

        $barcode       = $_POST['txtbarcode'];
        $product       = $_POST['txtproductname'];
        $category      = $_POST['txtselect_option'];
        $description   = $_POST['txtdescription'];
        $stock         = $_POST['txtstock'];
        $purchasepricee = $_POST['txtpurchaseprice'];
        $salepricee     = $_POST['txtsaleprice'];

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
            $saleprice = $salepricee;
            $purchaseprice = $purchasepricee;
        } else {
            $saleprice = $salepricee / $exchange;
            $purchaseprice = $purchasepricee / $exchange;
        }

        $store = "../productimages/" . $f_newfile;

        if ($f_extension == 'jpg' || $f_extension == 'jpeg' ||   $f_extension == 'png' || $f_extension == 'gif') {

            if ($f_size >= 1000000) {

                $_SESSION['status'] = "Max file should be 1MB";
                $_SESSION['status_code'] = "warning";
            } else {

                if (move_uploaded_file($f_tmp, $store)) {

                    $productimage = $f_newfile;

                    if (empty($barcode)) {

                        $insert = query("INSERT into tbl_product ( product,category,description,stock, purchaseprice, saleprice,image,aus) 
                        values('{$product}','{$category}','{$description}','{$stock}','{$purchaseprice}','{$saleprice}','{$productimage}','{$aus}')");
                        confirm($insert);

                        $pid = last_id(); // which was the 5
                        $inserts = query("INSERT into tbl_product_stock ( product_id,stock,price,aus) values('{$pid}','{$stock}','{$purchaseprice}','{$aus}')");

                        date_default_timezone_set("Asia/Bangkok");
                        $newbarcode = $pid . date('his');

                        $update = query("UPDATE tbl_product SET barcode='$newbarcode' where pid='" . $pid . "'");

                        if ($update and $inserts) {

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
                    } else {


                        $insert = query("INSERT into tbl_product (barcode,product,category,description,stock,purchaseprice, saleprice,image,aus) 
                        values('{$barcode}','{$product}','{$category}','{$description}','{$stock}','{$purchaseprice}','{$saleprice}','{$productimage}','{$aus}')");
                        confirm($insert);


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
    $select = query("SELECT * from tbl_product where aus=$aus order by pid ASC");
    confirm($select);

    $change = query("SELECT * from tbl_change where aus='$aus'");
    confirm($change);
    $row_exchange = $change->fetch_object();
    $exchange = $row_exchange->exchange;
    $usd_or_real = $row_exchange->usd_or_real;

    while ($row = $select->fetch_object()) {

        if ($usd_or_real == "usd") {
            $USD_usd = "$";
            // $m_price = number_format($row->m_price, 2) . $USD_usd;
            $saleprice = number_format($row->saleprice, 2) . $USD_usd;
            $purchaseprice = number_format($row->purchaseprice, 2) . $USD_usd;
        } else {
            $USD_usd = "៛";
            // $m_pricee = $row->m_price * $exchange;
            $salepricee = $row->saleprice * $exchange;
            $purchaseprice = $row->purchaseprice * $exchange;
            // $m_price = number_format($m_pricee) . $USD_usd;
            $saleprice = number_format($salepricee) . $USD_usd;
            $purchaseprice = number_format($purchaseprice) . $USD_usd;
        }

        echo '
           <tr>
           <td>' . $row->barcode . '</td>
           <td>' . $row->product . '</td>
           <td>' . $row->category . '</td>
           <td>' . $row->description . '</td>
           <td>' . $row->stock . '</td>
           
           <td>' . $purchaseprice . '</td>
           <td>' . $saleprice . '</td>
           
           <td><image src="../productimages/' . $row->image . '" class="img-rounded" width="40px" height="40px/"></td>
           
           <td>
           
           <div class="btn-group">
           
           <a href="itemt?printbarcode&id=' . $row->pid . '" class="btn btn-dark btn-xs" role="button"><span class="fa fa-barcode" style="color:#ffffff" data-toggle="tooltip" title="PrintBarcode"></span></a>
           
           
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

  <li class="list-group-item"><b>Barcode</b> <span class="badge badge-light float-right">' . bar128($row->barcode) . '</span></li>
  <li class="list-group-item"><b>Product Name</b><span class="badge badge-warning float-right">' . $row->product . '</span></li>
  <li class="list-group-item"><b>Category</b> <span class="badge badge-success float-right">' . $row->category . '</span></li>
  <li class="list-group-item"><b>Description </b><span class="badge badge-primary float-right">' . $row->description . '</span></li>
  <li class="list-group-item"><b>Stock</b> <span class="badge badge-danger float-right">' . $row->stock . '</span></li>
  <li class="list-group-item"><b>Purchase Price </b><span class="badge badge-secondary float-right">' . $row->purchaseprice . '</span></li>
  <li class="list-group-item"><b>Sale Price</b> <span class="badge badge-dark float-right">' . $row->saleprice . '</span></li>
  <li class="list-group-item"><b>Product Profit</b> <span class="badge badge-success float-right">' . ($row->saleprice - $row->purchaseprice) . '</span></li>
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
    $aus = $_SESSION['aus'];
    $output = '';
    $select = query("SELECT * from tbl_product where aus=$aus order by product asc");
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
        $stock_txt         = $_POST['txtstock'];
        $purchaseprice_txt = $_POST['txtpurchaseprice'];
        $saleprice_txt     = $_POST['txtsaleprice'];

        //Image Code or File Code Start Here..
        $f_name        = $_FILES['myfile']['name'];

        $aus = $_SESSION['aus'];

        $change = query("SELECT * from tbl_change where aus='$aus'");
        confirm($change);
        $row_exchange = $change->fetch_object();
        $exchange = $row_exchange->exchange;
        $usd_or_real = $row_exchange->usd_or_real;
        if ($usd_or_real == "usd") {
            $saleprice_txtt = $saleprice_txt;
            $purchaseprice_txtt = $purchaseprice_txt;
        } else {
            $saleprice_txtt = $saleprice_txt / $exchange;
            $purchaseprice_txtt = $purchaseprice_txt / $exchange;
        }

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

                        $update = query("UPDATE tbl_product set product='$product_txt' , category='$category_txt' , description='$description_txt' , stock='$stock_txt' , purchaseprice='$purchaseprice_txtt' , saleprice='$saleprice_txtt' , image='$f_newfile' where pid=$id");
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

            $update = query("UPDATE tbl_product set product='$product_txt' , category='$category_txt' , description='$description_txt' , stock='$stock_txt' , purchaseprice='$purchaseprice_txtt' , saleprice='$saleprice_txtt' , image='$image_db' where pid=$id");
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


function show_name_category($catid)
{
    $tblcategory = query("SELECT * from tbl_user where user_id = '$catid'");
    $row = $tblcategory->fetch_object();
    echo '
    <option selected  value="' . $row->user_id . '">' . $row->username . '</option>
';
}

function show_name_product($id)
{
    $tblcategory = query("SELECT * from tbl_product where pid = '$id'");
    $row = $tblcategory->fetch_object();
    return  $row->product;
}


// /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
                    redirect('itemt?taxdis');
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
              title: " Updated Successfully With New Image"
            });
          </script>');
                            redirect('itemt?taxdis');
                        } else {
                            set_message(' <script>
           Swal.fire({
          icon: "error",
          title: " Update Failed"
           });
         </script>');
                            redirect('itemt?taxdis');
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
                redirect('itemt?taxdis');
            }
        } else {

            $update = query("UPDATE tbl_logo set name='$txtname',addres='$txtaddres',road='$txtrod',phone='$txtphone'  where id=$id");
            confirm($update);
            if ($update) {

                set_message(' <script>
      Swal.fire({
     icon: "success",
     title: " Updated Successfully"
      });
    </script>');
                redirect('itemt?taxdis');
            } else {
                set_message(' <script>
      Swal.fire({
     icon: "error",
     title: " Update Failed"
      });
    </script>');
                redirect('itemt?taxdis');
            }
        }
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
                    <span class="fas fa-eye-slash" id="eyeicon"></span>
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

function update_discount()
{
    if (isset($_POST['btnupdate'])) {

        $discount = $_POST['txtdiscount'];


        $id = $_POST['txtid'];

        if ($discount < 0) {
            set_message(' <script>
          Swal.fire({
            icon: "warning",
            title: "Feild is Empty"
          });
        </script>');
            redirect('itemt?taxdis');
        } else {

            $update = query("UPDATE tbl_taxdis set discount='{$discount}' where taxdis_id =" . $id);
            confirm($update);

            if ($update) {
                set_message(' <script>
            Swal.fire({
              icon: "success",
              title: "Discount Update successfully"
            });
          </script>');
                redirect('itemt?taxdis');
            } else {
                set_message(' <script>
            Swal.fire({
              icon: "warning",
              title: "Failed"
            });
          </script>');
                redirect('itemt?taxdis');
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


function addstock()
{
    if (isset($_POST['btnaddpro'])) {

        $txtbarcode = $_POST['txtbarcode'];
        $txtselect_option = $_POST['txtselect_option'];
        $txtstock = $_POST['txtstock'];
        $txtpurchaseprice = $_POST['txtpurchaseprice'];
        $txtsaleprice = $_POST['txtsaleprice'];
        $aus = $_SESSION['aus'];
        $change = query("SELECT * from tbl_change where aus='$aus'");
        confirm($change);
        $row_exchange = $change->fetch_object();
        $exchange = $row_exchange->exchange;
        $usd_or_real = $row_exchange->usd_or_real;
        if ($usd_or_real == "usd") {
            $saleprice_txtt = $txtsaleprice;
            $purchaseprice_txtt = $txtpurchaseprice;
        } else {
            $saleprice_txtt = $txtsaleprice / $exchange;
            $purchaseprice_txtt = $txtpurchaseprice / $exchange;
        }


        $update = query("UPDATE tbl_product set stock=stock+'{$txtstock}',purchaseprice='{$purchaseprice_txtt}' ,saleprice='{$saleprice_txtt}' where barcode=" . $txtbarcode);
        confirm($update);

        $inserts = query("INSERT into tbl_product_stock ( product_id,stock,price,aus) values('{$txtselect_option}','{$txtstock}','{$purchaseprice_txtt}','{$aus}')");
        if ($update) {
            set_message(' <script>
            Swal.fire({
              icon: "success",
              title: "Add Stock successfully"
            });
          </script>');
            redirect('itemt?Add_stock');
        } else {
            set_message(' <script>
            Swal.fire({
              icon: "warning",
              title: "Failed"
            });
          </script>');
            redirect('itemt?Add_stock');
        }
    }
}


function stock_list()
{
    $aus = $_SESSION['aus'];
    $select = query("SELECT * from tbl_product_stock where aus=$aus order by id desc");
    confirm($select);
    $change = query("SELECT * from tbl_change where aus='$aus'");
    confirm($change);
    $row_exchange = $change->fetch_object();
    $exchange = $row_exchange->exchange;
    $usd_or_real = $row_exchange->usd_or_real;
    $subtotal = 0;
    $totals = 0;
    $subtotall = 0;
    $totalss = 0;
    $no = 1;
    while ($row = $select->fetch_object()) {
        $selectt = query("SELECT * from tbl_product where pid= $row->product_id ");
        $roww = $selectt->fetch_object();

        if ($usd_or_real == "usd") {
            $USD_usd = "$";
            $price = number_format($row->price, 2) . $USD_usd;
            $total = number_format($row->price * $row->stock) . $USD_usd;
            $totall = $row->price * $row->stock;
            $subtotal += $row->price;
            $totals += $totall;
            $totalss = number_format($totals, 2) . $USD_usd;
            $subtotall = number_format($subtotal, 2) . $USD_usd;
        } else {
            $USD_usd = "៛";
            $pricee = $row->price * $exchange;
            $price = number_format($pricee);
            $total = number_format($pricee * $row->stock);
            $totall = $pricee * $row->stock;
            $subtotal += $pricee;
            $totals += $totall;
            $totalss = number_format($totals) . $USD_usd;
            $subtotall = number_format($subtotal) . $USD_usd;
        }

        echo '
           <tr>
           <td>' . $no     . '</td>
           <td>' . $row->id     . '</td>
           <td>' . show_name_product($row->product_id) . '</td>
           <td>' . $row->stock     . '</td>
           <td>' . $price . '</td>
           <td>' . $total . '</td>
           
           <td><image src="../productimages/' . $roww->image . '" class="img-rounded" width="40px" height="40px/"></td>
           
           <td>
           
           <div class="btn-group">
                                         
           <a href="itemt?editstock&id=' . $row->id . '" class="btn btn-success " role="button"><span class="fa fa-edit" style="color:#ffffff" data-toggle="tooltip" title="Edit Product"></span></a>
           
           <button id=' . $row->id . '  class="btn btn-danger  btndelete"><span class="fa fa-trash" style="color:#ffffff" data-toggle="tooltip" title="Delete Product"></span></button>
           
           </div>
           
           </td>
           
           </tr> 
           
           ';
        $no++;
    }

    echo '<tr>
            <td></td>
            <td></td>
            <td></td>
            <th>សរុប</th>
            <td><span class="badge badgeth badge-danger">' . $subtotall . '</span></td>
            <td><span class="badge badgeth badge-success">' . $totalss . '</span></td>
            <td></td>
            <td></td>
           </tr>';
}

function update_stock()
{
    if (isset($_POST['btnupdates'])) {
        $idp = $_POST['btnupdates'];
        $aus = $_SESSION['aus'];
        $selects = query("SELECT * from tbl_product_stock where id= $idp");
        $row = $selects->fetch_object();
        $dbstock = $row->stock;
        $dbid = $row->product_id;
        // $selectp = query("SELECT * from tbl_product where pid= $idp");

        $txtbarcode = $_POST['txtbarcode'];
        $txtselect_option = $_POST['txtselect_option'];
        $txtstock = $_POST['txtstock'];
        $txtpurchaseprice = $_POST['txtpurchaseprice'];
        $txtsaleprice = $_POST['txtsaleprice'];

        $change = query("SELECT * from tbl_change where aus='$aus'");
        confirm($change);
        $row_exchange = $change->fetch_object();
        $exchange = $row_exchange->exchange;
        $usd_or_real = $row_exchange->usd_or_real;
        if ($usd_or_real == "usd") {
            $saleprice_txtt = $txtsaleprice;
            $purchaseprice_txtt = $txtpurchaseprice;
        } else {
            $saleprice_txtt = $txtsaleprice / $exchange;
            $purchaseprice_txtt = $txtpurchaseprice / $exchange;
        }

        $update = query("UPDATE tbl_product set stock=stock-'{$dbstock}'+'$txtstock',purchaseprice='{$purchaseprice_txtt}',saleprice='{$saleprice_txtt}' where pid =" . $dbid);

        $update = query("UPDATE tbl_product_stock set stock='{$txtstock}',price='{$purchaseprice_txtt}' where id =" . $idp);
        confirm($update);

        if ($update) {
            set_message(' <script>
            Swal.fire({
              icon: "success",
              title: "Stock Update successfully"
            });
          </script>');
            redirect('itemt?stock_list');
        } else {
            set_message(' <script>
            Swal.fire({
              icon: "warning",
              title: "Failed"
            });
          </script>');
            redirect('itemt?stock_list');
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

function display_messag_signin()
{
    if (isset($_SESSION['messagee'])) {
        echo $_SESSION['messagee'];
    }
}
