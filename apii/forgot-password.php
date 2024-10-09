<?php require_once "../coffee/resources/config.php"; ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>RESTAURANT POS | Forgot Password | By:THPOS</title>
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

<style>
  @font-face {
    font-family: "OSbattambang";
    src: url(../fone/KhmerOSbattambang.ttf)format("truetype");
  }

  * {
    font-family: "OSbattambang";
  }

  .back {
    width: 318px;
    display: flex;
    justify-content: center;
    align-items: center;
    position: absolute;
    height: 196px;
    opacity: 16%;

  }

  .form-control {
    background-color: #ffffff69;
  }

</style>
<?php
$email = "";
if (isset($_SESSION['useremail'])) {
  $email  = $_SESSION['useremail'];
}
?>
<?php forgot_pass(); ?>

<body class="hold-transition login-page">
  <div class="login-box">
    <div class="card card-outline card-primary">
      <div class="card-header text-center">
        <a href="login" class="h1"><img width="60" src="../resources/images/logo/logo2.png" alt=""><b>TH</b>POS</a>
      </div>
      <div class="card-body">
        <div class="back"> <img width="200" src="../resources/images/logo/logo2.png" alt=""></div>
        <p class="login-box-msg">You forgot your password? Here you can easily retrieve a new password.</p>
        <?php display_messag_signin(); ?>
        <form action="" method="post">
          <div class="input-group mb-3">
            <input type="email" class="form-control" name="email" placeholder="Email" value="<?php echo $email ?>">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <button type="submit" class="btn btn-primary btn-block" name="check-email">Request new password</button>
            </div>
            <!-- /.col -->
          </div>
        </form>
        <div class="col-8 mt-3 mb-1">
          <a href="../coffee/">Login</a>
        </div>
      </div>
      <!-- /.login-card-body -->
    </div>
  </div>
  <!-- /.login-box -->

  <script>
    function show() {
      var p = document.getElementById('pwd');
      var eyeicon = document.getElementById('eyeicon');
      p.setAttribute('type', 'text');
      eyeicon.classList = 'fas fa-eye';
    }

    function hide() {
      var p = document.getElementById('pwd');
      var eyeicon = document.getElementById('eyeicon');
      p.setAttribute('type', 'password');
      eyeicon.classList = 'fas fa-eye-slash';
    }

    var pwShown = 0;

    document.getElementById("eye").addEventListener("click", function() {
      if (pwShown == 0) {
        pwShown = 1;
        show();
      } else {
        pwShown = 0;
        hide();
      }
    }, false);
  </script>
</body>

</html>