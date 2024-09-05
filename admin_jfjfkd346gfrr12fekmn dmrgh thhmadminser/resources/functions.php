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
        $password = $_POST['txt_password'];

        $query = query("SELECT * from tbl_admin where useremail='$useremail' AND userpassword='$password' and role ='Admin'");
        confirm($query);

        if (mysqli_num_rows($query) == 0) {
            $query2 = query("SELECT * from tbl_admin where useremail='$useremail' AND userpassword='$password' and role ='User'");
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


                $date = new DateTime('now', new DateTimeZone('Asia/bangkok'));
                $datee =  $date->format('Y-m-d H:i:s');
                $time = time() + 10;
                $res = query("UPDATE tbl_admin set login_online='$time', last_login='$datee' where user_id=" . $_SESSION['userid']);
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
            $_SESSION['userid'] = $row['user_id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['useremail'] = $row['useremail'];
            $_SESSION['role'] = $row['role'];

            $date = new DateTime('now', new DateTimeZone('Asia/bangkok'));
            $datee =  $date->format('Y-m-d H:i:s');
            $time = time() + 10;
            $res = query("UPDATE tbl_admin set login_online='$time', last_login='$datee' where user_id=" . $_SESSION['userid']);
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
        }
    }
}

function check_login()
{
    if (isset($_SESSION['userid'])) {

        $id = $_SESSION['userid'];
        $query = query("SELECT * from tbl_admin where user_id = '$id' limit 1");

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
        $query =  query("SELECT * FROM tbl_admin WHERE user_id =  $id ");
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

        $oldpassword_txt = $_POST['txt_oldpassword'];
        $newpassword_txt = $_POST['txt_newpassword'];
        $rnewpassword_txt = $_POST['txt_rnewpassword'];

        //echo $oldpassword_txt."-".$newpassword_txt."-".$rnewpassword_txt;


        // 2 Step) Using of select Query we will get out database records according to useremail.

        $email = $_SESSION['useremail'];

        $select = query("SELECT * from tbl_admin where useremail='$email'");
        confirm($select);

        $row = $select->fetch_assoc();

        $useremail_db = $row['useremail'];
        $password_db = $row['userpassword'];



        // 3 Step) We will compare the user inputs values to database values.

        if ($oldpassword_txt == $password_db) {

            if ($newpassword_txt == $rnewpassword_txt) {

                // 4 Step) If values will match then we will run update Query. 


                $update = query("UPDATE tbl_admin set userpassword='$rnewpassword_txt' where useremail='$email'");
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
        $userpassword = $_POST['txtpassword'];
        $userrole = $_POST['txtselect_option'];

        $user_photo = $_FILES['file']['name'];
        $image_temp_location = $_FILES['file']['tmp_name'];

        if (!empty($user_photo)) {
            // move_uploaded_file($image_temp_location, "../resources/images/userpic/" . $user_photo);
            move_uploaded_file($image_temp_location,  UPLOAD_DIRECTORY_UDER . DS . $user_photo);
            $image = $user_photo;
        } else {
            $image = 'user.png';
        }


        if (isset($_POST['txtemail'])) {

            $select = query("SELECT useremail from tbl_admin where useremail='$useremail'");
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

                $insert = query("INSERT into tbl_admin (username,useremail,userpassword,role,img) values('{$username}','{$useremail}','{$userpassword}','{$userrole}','{$image}')");
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





    if (isset($_POST['btnupdate'])) {

        $username = $_POST['txtname'];
        $useremail = $_POST['txtemail'];
        $userpassword = $_POST['txtpassword'];
        $userrole = $_POST['txtselect_option'];
        $id = $_POST['btnupdate'];
        $user_photo = $_FILES['file']['name'];
        $image_temp_location = $_FILES['file']['tmp_name'];

        $select_img = query("SELECT img from tbl_admin where user_id = $id");
        confirm($select_img);
        $row = $select_img->fetch_assoc();


        if (!empty($user_photo)) {
            move_uploaded_file($image_temp_location,  UPLOAD_DIRECTORY_UDER . DS . $user_photo);
            $dbimage = $row['img'];
            $image = $user_photo;
            if ($dbimage != "user.png") {
                unlink("../productimages/user/$dbimage");
            }
        } else {
            $image = $row['img'];
        }


        if (isset($_POST['txtemail'])) {

            $select = query("SELECT useremail from tbl_admin where useremail='$useremail'");
            confirm($select);


            if (mysqli_num_rows($select) < 0) {
                set_message(' <script>
                Swal.fire({
                  icon: "warning",
                  title: "Email already exists. Create Account From New Email"
                });
               </script>');
                redirect('itemt?registration');
            } else {

                $insert = query("UPDATE tbl_admin set username='$username' , useremail='$useremail', useremail='$useremail', userpassword='$userpassword', img='$image' , role='$userrole' where user_id='$id'");
                confirm($insert);
                if ($insert) {

                    set_message(' <script>
                      Swal.fire({
                      icon: "success",
                      title: "UPDATE successfully the user into the database"
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

function show_delete($invoice_id)
{
    if ($_SESSION['useremail'] == "" or $_SESSION['role'] == "Admin") {

        return '
        <button id=' . $invoice_id . '  class="btn btn-danger btn-xs btndelete"><span class="fa fa-trash" style="color:#ffffff" data-toggle="tooltip" title="Delete Product"></span></button>
        ';
    }
}


function edit_registration()
{
    if (isset($_POST['btnedit'])) {

        $select = query("SELECT * from tbl_admin where user_id =" . $_POST['btnedit']);
        confirm($select);

        if ($select) {
            $row = $select->fetch_object();

            echo ' <div class="col-md-4">
               <form action="" method="post" enctype="multipart/form-data">
              <div class="form-group">
                <label>រូបថត រូបភាព</label>
                <input type="file" class="input-group" name="file" onchange="displayImage(this)" id="profilImg">
                <img  src="../productimages/user/' . $row->img . ' " onclick="triggerClick()" id="profiledisplay">
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
                    <input type="password" placeholder="Password" id="pwd" class="form-control" placeholder="Password" name="txtpassword" value="' . $row->userpassword . '"  required>
                    <button type="button" class="input-group-text" id="eye">
                    <i class="fa fa-eye" aria-hidden="true"></i>
                   </button>
                </div>
                </div>

                <div class="form-group">
                    <label>Role</label>
                    <select class="form-control" name="txtselect_option" required>
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
        $query =  query("SELECT * FROM tbl_admin WHERE user_id =  $id ");
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

function query_category()
{
    $select = query("SELECT * from tbl_category order by catid ASC");
    confirm($select);

    while ($row = $select->fetch_object()) {
        echo '
        <tr>
        <td>' . $row->catid . '</td>
        <td>' . $row->category . '</td>
        <td>
        <button type="submit" class="btn btn-primary" value="' . $row->catid . '" name="btnedit">Edit</button>
        </td>
        <td>
        <button type="submit" class="btn btn-danger" value="' . $row->catid . '" name="btndelete">Delete</button>
       </td>
      </tr>';
    }
}


function insert_update_delete()
{
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

            $insert = query("INSERT into tbl_category (category) values('{$category}')");
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
    if (isset($_POST['btnsave'])) {

        $product       = $_POST['txtproductname'];
        $category      = $_POST['txtselect_option'];
        $description   = $_POST['txtdescription'];
        $user_id = $_SESSION['userid'];
        $txtvideo     = $_POST['txtvideo'];

        $select = query("SELECT product_title from products where product_title='$product'");
        confirm($select);


        if (mysqli_num_rows($select) > 0) {
            set_message(' <script>
                Swal.fire({
                  icon: "warning",
                  title: "ចំណងជើងរឿងនេះមានរួចហើយ"
                });
               </script>');
            redirect('itemt?addmovies');
        } else {

            //Image Code or File Code Start Here..
            $f_name        = $_FILES['myfile']['name'];
            $f_tmp         = $_FILES['myfile']['tmp_name'];
            $f_size        = $_FILES['myfile']['size'];
            $f_extension   = explode('.', $f_name);
            $f_extension   = strtolower(end($f_extension));
            $f_newfile     = uniqid() . '.' . $f_extension;

            $store = "../../productimages/movi/" . $f_newfile;

            if ($f_extension == 'jpg' || $f_extension == 'jpeg' ||   $f_extension == 'png' || $f_extension == 'gif') {

                if ($f_size >= 1000000) {

                    set_message(' <script>
            Swal.fire({
              icon: "warning",
              title: "Max file should be 1MB"
            });
          </script>');
                    redirect('itemt?addmovies');
                } else {

                    if (move_uploaded_file($f_tmp, $store)) {

                        $productimage = $f_newfile;

                        $insert = query("INSERT into products ( product_title,product_category_id,product_description, product_image,user_id) 
                        values('{$product}','{$category}','{$description}','{$productimage}','{$user_id}')");
                        confirm($insert);
                        $product_id = last_id();
                        $pat = 1;

                        $insertt = query("INSERT into sub_products (product_id,video,pat) 
                    values('{$product_id}','{$txtvideo}','{$pat}')");
                        confirm($insertt);

                        if ($insert) {

                            set_message(' <script>
                            Swal.fire({
                              icon: "success",
                              title: "Movies Inserted Successfully"
                            });
                          </script>');
                            redirect('itemt?addmovies');
                        } else {
                            set_message(' <script>
                            Swal.fire({
                              icon: "error",
                              title: "Movies Inserted Failed"
                            });
                          </script>');
                            redirect('itemt?addmovies');
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
                redirect('itemt?addmovies');
            }
        }
    }
}


function addproduct_pat()
{

    if (isset($_POST['btnsave'])) {
        $id = $_POST['txtcatid'];

        $product       = $_POST['txtproductname'];
        $pat           = $_POST['txtpat'];
        $txtvideo      = $_POST['txtvideo'];
        $end      = $_POST['end'];

        $user_id = $_SESSION['userid'];

        if ($product) {

            $select = query("SELECT pat,product_id from sub_products where pat='$pat' and product_id='$product'");
            confirm($select);
            if (mysqli_num_rows($select) > 0) {
                set_message(' <script>
                Swal.fire({
                  icon: "warning",
                  title: "មានរូចហើយ"
                });
               </script>');
                redirect('itemt?movies=' . $id . '');
            } else {
                $insert = query("INSERT into sub_products (product_id,video,pat,user_id) values('{$product}','{$txtvideo}','{$pat}','{$user_id}')");
                confirm($insert);
                $product_id = last_id();
                $date = new DateTime('now', new DateTimeZone('Asia/bangkok'));
                $datee =  $date->format('Y-m-d h:i:s');
                $update = query("UPDATE products set date_update='$datee' ,end='$end' where product_id=" . $product);
                confirm($update);
                if ($insert) {

                    set_message(' <script>
                            Swal.fire({
                              icon: "success",
                              title: "Movies Inserted Successfully"
                            });
                          </script>');
                    redirect('itemt?movies=' . $id . '');
                } else {
                    set_message(' <script>
                            Swal.fire({
                              icon: "error",
                              title: "Movies Inserted Failed"
                            });
                          </script>');
                    redirect('itemt?movies=' . $id . '');
                }
            }
        }
    }
}

function payment_lis()
{
    $select = query("SELECT * from tbl_payment order by id DESC");
    confirm($select);
    $no = 1;
    while ($row = $select->fetch_object()) {
        $success = '';
        if ($row->alert == 0) {
            $success = '<i class="fa fa-circle text-warning"></i>';
        }

        echo '
           <tr class="">
            <td>' . $row->id . '</td>
            <td><span class="badge badge-dark">' . $row->user_name . '</span>' . $success . '</td>
            
            <td><span class="badge badge-success">' . $row->numberidpay . '</span></td>
            <td>  <img  src="../../resources/images/userpay/' . $row->img . '" alt="" style="width: 100px;"></td>
            <td>' . $row->date . '</td>
            <td>' . $row->num_month . '</td>
            <td>' . $row->tim . '</td>
            <td>' . $row->aus . '</td>
            <td>' . $row->alert . '</td>  
           
           <td>
           
           <div class="btn-group">
                      
           
           <a href="itemt?viewpay&id=' . $row->id  . '" class="btn btn-warning btn-xs" role="button"><span class="fa fa-eye" style="color:#ffffff" data-toggle="tooltip" title="View Product"></span></a>
           
           
           <a href="itemt?editpay&id=' . $row->id  . '" class="btn btn-success btn-xs" role="button"><span class="fa fa-edit" style="color:#ffffff" data-toggle="tooltip" title="Edit Product"></span></a>
           
           ' . show_delete($row->id) . '
           </div>
           
           </td>
           
           </tr>';
        $no++;
    }
}

function viewpay()
{
    $id_pay = $_GET['id'];

    $select = query("SELECT * from tbl_payment where id = $id_pay");
    $sow = '';

    while ($row = $select->fetch_object()) {
        // $minpat = show_pat($id);
        $id_service = $row->id_service;
        $aus = $row->aus;
        $tbl_user = query("SELECT * from tbl_user where aus = $aus");
        $rowu = $tbl_user->fetch_object();
        $timu = $rowu->tim;
        $select2 = query("SELECT * from tbl_service where id = $id_service");
        confirm($select);
        $select_message = query("SELECT * from tbl_message where id_payment = $id_pay");
        $sow .= '
<div class="row">
<div class="col-md-6 movi_view">

<ul class="list-group">

<center><p class="list-group-item list-group-item-info"><b>MOVIES DETAILS</b></p></center>  

  <li class="list-group-item text-align"><b>User Name:</b><span class="badge badge-warning float-right">' . $row->user_name . '</span></li>
  <li class="list-group-item text-align"><b>IDPay:</b> <span class="badge badge-success float-right">' . $row->numberidpay . '</span></li>

  <li class="list-group-item text-align"><b>ចំនួនថ្ងៃនៅសល់:</b> <span class="badge badge-dark float-right">' . $timu . '</span></li>
  <li class="list-group-item text-align"><b>ថ្ងៃខែបានបញ្ជាទិញ:</b> <span class="badge badge-dark float-right">' . $row->date . '</span></li>
  <li class="list-group-item text-align"><b>ចំនួនខែ:</b> <span class="badge badge-danger float-right">' . $row->num_month . '</span></li>
';


        while ($roww = $select2->fetch_object()) {

            $sow .= '
            <li class="list-group-item text-align"><b>Price:</b> <span class="badge badge-primary float-right">' . $roww->price . '</span></li>
            <li class="list-group-item text-align"><b>Text:</b> <span class="badge badge-info float-right">' . $roww->expires . '</span></li>
            

            ';
        }
        while ($rowmes = $select_message->fetch_object()) {
            $viw = $rowmes->viw;
            if ($viw == 0) {
                $alert = '<li class="list-group-item text-align"><b>View :</b> <span class="badge badge-danger float-right"><i class="fas fa-eye-slash"></i> មិនបានមើល</span></li> ';
            } else {
                $alert = '<li class="list-group-item text-align"><b>View :</b> <span class="badge badge-primary float-right"><i class="fas fa-eye"></i> បានមើល</span></li> ';
            }

            $sow .= '  
            <li class="list-group-item text-align"><b>Messages:</b> <span class="badge badge-success float-right">' . $rowmes->messages . '</span></li>
            <li class="list-group-item text-align"><b>Date:</b> <span class="badge badge-warning float-right">' . $rowmes->date . '</span></li>
            ' . $alert . '
            <li class="list-group-item text-align"><b class="text-info">User AUS:</b> <span class="badge badge-secondary float-right">' . $rowmes->aus . '</span></li>

            ';
        }

        $sow .= '
        
        <li class="list-group-item text-align">
        <form action="" method="post">
        <button type="submit" class="btn btn-primary" name="send" value="ការទូទាត់ទទួលបានជោគជ័យ">ការទូទាត់ទទួលបានជោគជ័យ</button>
        <button type="submit" class="btn btn-danger" name="send" value="ការទូទាត់បរាជ័យ">ការទូទាត់បរាជ័យ</button>
        <input type="hidden" class="form-control" placeholder="Enter Category"  value="' . $aus . '" name="txtaus" >
        <input type="hidden" class="form-control" placeholder="Enter Category"  value="' . $row->tim . '" name="txttim" >
        <input type="hidden" class="form-control" placeholder="Enter Category"  value="' . $row->id . '" name="txtid_pay" >
        </form></li>
</ul>

</div>

<div class="col-md-6">
<ul class="list-group">
<center><p class="list-group-item list-group-item-info"><b>MOVIES IMAGE</b></p></center>  
<img src="../../resources/images/userpay/' . $row->img . '" class="img-thumbnail"/>
</ul>
</div>
</div>


';
    }
    echo $sow;
}



function show_pat($id)
{
    $query =  query("SELECT MAX(pat) as minimum from sub_products WHERE product_id = $id");
    confirm($query);
    $result = $query->fetch_array();
    $res = $result['minimum'];
    return $res;
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
function deletepat()
{
    if (isset($_POST['btndeletepat'])) {


        $id = $_GET['id'];

        $patid = $_POST['btndeletepat'];

        $delete = query("DELETE from sub_products where product_id='$id' and pat='$patid'");
        confirm($delete);
        if ($delete) {

            set_message(' <script>
            Swal.fire({
            icon: "success",
            title: "Pat ' . $patid . ' deleted successfully"
            });
           </script>');
            // redirect('../../../ui/itemt?registration');
        }
    }
}

function edit_product()
{



    if (isset($_POST['btneditMovie'])) {


        $id = $_GET['id'];

        $select = query("SELECT * from products where product_id=$id");
        confirm($select);

        $row = $select->fetch_assoc();

        $image_db = $row['product_image'];
        $date = new DateTime('now', new DateTimeZone('Asia/bangkok'));
        $datee =  $date->format('Y-m-d h:i:s');

        // $barcode_txt       =$_POST['txtbarcode'];
        $product       = $_POST['txtproductname'];
        $category      = $_POST['txtselect_option'];
        $description   = escape_string($_POST['txtdescription']);
        $user_id = $_SESSION['userid'];
        $txtvideo     = $_POST['txtvideo'];
        $end     = $_POST['end'];


        $pat = $_POST['pat'];
        $video = $_POST['video'];

        //Image Code or File Code Start Here..
        $f_name        = $_FILES['myfile']['name'];

        if (!empty($f_name)) {

            $f_tmp         = $_FILES['myfile']['tmp_name'];
            $f_size        = $_FILES['myfile']['size'];
            $f_extension   = explode('.', $f_name);
            $f_extension   = strtolower(end($f_extension));
            $f_newfile     = uniqid() . '.' . $f_extension;

            $store = "../../productimages/movi/" . $f_newfile;

            if ($f_extension == 'jpg' || $f_extension == 'jpeg' ||   $f_extension == 'png' || $f_extension == 'gif') {

                if ($f_size >= 1000000) {


                    set_message(' <script>
                      Swal.fire({
                        icon: "warning",
                        title: "Max file should be 1MB"
                      });
                    </script>');
                    redirect('itemt?movies_list');
                } else {

                    if (move_uploaded_file($f_tmp, $store)) {

                        $f_newfile;
                        unlink("../../productimages/movi/$image_db");

                        $update = query("UPDATE products set product_title='$product' , product_category_id='$category' , product_description='$description' ,user_edit='$user_id', product_image='$f_newfile', date_update='$datee', end='$end' where product_id=$id");
                        confirm($update);

                        $update = query("UPDATE sub_products set video='$video' where pat=$pat");
                        confirm($update);

                        if ($update) {
                            set_message(' <script>
                          Swal.fire({
                            icon: "success",
                            title: "Movies Updated Successfully With New Image"
                          });
                        </script>');
                            redirect('itemt?movies_list');
                        } else {
                            set_message(' <script>
                            Swal.fire({
                           icon: "error",
                           title: "Movies Update Failed"
                            });
                          </script>');
                            redirect('itemt?movies_list');
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
                redirect('itemt?movies_list');
            }
        } else {
            $query = "UPDATE products SET ";
            $query .= "product_title          = '{$product}'      , ";
            $query .= "product_category_id    = '{$category}'     , ";
            $query .= "product_description    = '{$description}'  , ";
            $query .= "user_edit              = '{$user_id}'      , ";
            $query .= "product_image          = '{$image_db}'     , ";
            $query .= "end                    = '{$end}'     , ";
            $query .= "date_update            = '{$datee}'          ";

            $query .= "WHERE product_id =" . $id;

            $update = query($query);
            confirm($update);


            for ($i = 0; $i < count($pat); $i++) {


                $update = query("UPDATE sub_products set video='$video[$i]' where product_id = $id and  pat = $pat[$i]");
                confirm($update);
            } //end for loop


            if ($update) {

                set_message(' <script>
               Swal.fire({
              icon: "success",
              title: "Movies Updated Successfully"
               });
             </script>');
                redirect('itemt?movies_list');
            } else {
                set_message(' <script>
               Swal.fire({
              icon: "error",
              title: "Movies Update Failed"
               });
             </script>');
                redirect('itemt?movies_list');
            }
        }
    }
}


function show_name_category($catid)
{
    $tblcategory = query("SELECT * from tbl_admin where user_id = '$catid'");
    $row = $tblcategory->fetch_object();
    echo '
    <option selected  value="' . $row->user_id . '">' . $row->username . '</option>
';
}


function show_category_name($catid)
{
    $tblcategory = query("SELECT * from categories where cat_id = '$catid'");
    $row = $tblcategory->fetch_object();

    return $row->cat_title;
}


function movies()
{
    $query = query("SELECT * FROM categories");
    confirm($query);
    $moviss = '';
    while ($row = fetch_array($query)) {
        $cat_id = $row['cat_id'];
        $cat_title = $row['cat_title'];

        $moviss .= '
        <p class="patbg"><a href="itemt?movies=' . $cat_id . '"><i class="fas fa-video"></i> ' . $cat_title . '</a></p>
        ';
    }


    echo $moviss;
}

// //////////////////////////////////////////////////////////

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


///////////////////////////////////////////////////////////////////////


function num_alert()
{

    $select = query("SELECT count(alert) as num from tbl_payment where alert = 0");
    confirm($select);
    $row = $select->fetch_object();
    if ($row->num == 0) {
        $num = '';
    } else {
        $num = $row->num;
    }

    return $num;
}






function free_trial()
{

    $select = query("SELECT * from tbl_date_free ");
    confirm($select);


    while ($row = $select->fetch_object()) {


        echo '
           <tr>

           <td>' . $row->id . '</td>   
           <td>' . $row->number_of_date . '</td>
           <td>
           <div class="btn-group">      
           <a href="itemt?viewproduct&id=' . $row->id . '" class="btn btn-warning btn-xs" role="button"><span class="fa fa-eye" style="color:#ffffff" data-toggle="tooltip" title="View Product"></span></a>
           <a href="itemt?editproduct&id=' . $row->id . '" class="btn btn-success btn-xs" role="button"><span class="fa fa-edit" style="color:#ffffff" data-toggle="tooltip" title="Edit Product"></span></a>
           <button id=' . $row->id . '  class="btn btn-danger btn-xs btndelete"><span class="fa fa-trash" style="color:#ffffff" data-toggle="tooltip" title="Delete Product"></span></button>
           </div>
           </td>
           </tr>';
    }
}
