<?php require_once("../resources/config.php"); ?>

<?php
$email = $_SESSION['useremail'];
if ($email == false) {
  header('Location: index.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>RESTAURANT POS | new password | By:THPOS</title>
  <link rel='shortcut icon' href="ui/logo/256.ico" type="image/x-icon">
  <link rel="icon" href="../ui/logo/32.ico" sizes="32x32">
  <link rel="icon" href="../ui/logo/48.ico" sizes="48x48">
  <link rel="icon" href="../ui/logo/96.ico" sizes="96x96">
  <link rel="icon" href="../ui/logo/256.ico" sizes="144x144">
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="../plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <!-- Toastr -->
  <link rel="stylesheet" href="../plugins/toastr/toastr.min.css">

  <!-- jQuery -->
  <script src="../plugins/jquery/jquery.min.js"></script>
  <!-- SweetAlert2 -->
  <script src="../plugins/sweetalert2/sweetalert2.min.js"></script>



</head>

<body>

  <?php change_pass(); ?>

  <div class="topnav">
    <div class="container" align="center">
      <a class="navbar-brand" href="#"><img src="../../resources/img/logo/tongheng-t2.jpg" alt="" width="130px"></a>
    </div>
  </div>
  <div class="container">
    <div class="row">
      <div class="col-md-4 offset-md-4 form">
        <div class="register-box">
          <div class="card card-outline card-primary">
            <div class="card-header text-center">
              <a href="login" class="h1"><b>TH</b>POS</a>
            </div>
            <div class="card-body">
              <?php display_messag_signin(); ?>

              <form action="" method="POST">
                <h2 class="text-center">New Password</h2>

                <div class="input-group mb-3">
                  <input type="password" class="form-control" placeholder="Password" name="password">
                  <div class="input-group-append">
                    <div class="input-group-text">
                      <span class="fas fa-lock"></span>
                    </div>
                  </div>
                </div>
                <div class="input-group mb-3">
                  <input type="password" class="form-control" placeholder="Confirm Password" name="cpassword">
                  <div class="input-group-append">
                    <div class="input-group-text">
                      <span class="fas fa-lock"></span>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12">
                    <button type="submit" class="btn btn-primary btn-block" name="change-password">Change password</button>
                  </div>
                  <!-- /.col -->
                </div>
              </form>
              <p class="mt-3 mb-1">
                <a href="login">Login</a>
              </p>

            </div>
            <!-- /.form-box -->
          </div><!-- /.card -->
        </div>
      </div>
    </div>
  </div>



</body>

</html>