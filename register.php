<?php require_once "resources/config.php"; ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>POS BARCODE | Log in | By:TH</title>
  <link rel='shortcut icon' href="ui/logo/256.ico" type="image/x-icon">
  <link rel="icon" href="ui/logo/32.ico" sizes="32x32">
  <link rel="icon" href="ui/logo/48.ico" sizes="48x48">
  <link rel="icon" href="ui/logo/96.ico" sizes="96x96">
  <link rel="icon" href="ui/logo/256.ico" sizes="144x144">
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <!-- Toastr -->
  <link rel="stylesheet" href="plugins/toastr/toastr.min.css">

  <!-- jQuery -->
  <script src="plugins/jquery/jquery.min.js"></script>
  <!-- SweetAlert2 -->
  <script src="plugins/sweetalert2/sweetalert2.min.js"></script>

</head>

<style>
  @font-face {
    font-family: "OSbattambang";
    src: url(fone/KhmerOSbattambang.ttf)format("truetype");
  }

  * {
    font-family: "OSbattambang";
  }
</style>
<?php create_acc(); ?>

<body class="hold-transition register-page" onload="getLocation();">
  <div class="register-box">
    <div class="card card-outline card-primary">
      <div class="card-header text-center">
        <a href="login" class="h1"><b>TH</b>POS</a>
      </div>
      <div class="card-body">
        <p class="login-box-msg">Register a new membership</p>
        <?php display_message(); ?>

        <form action="" method="post">
          <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Your name" name="username">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="email" class="form-control" placeholder="Email" name="ap_email">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>
          <input type="hidden" class="form-control" value="" name="latitude" >
          <input type="hidden" class="form-control" value="" name="longitude" >
          <div class="input-group mb-3">
            <input type="password" class="form-control" id="pwd" placeholder="Password" name="password">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="password" class="form-control" placeholder="Retype password" name="password_re">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-8">
              <a href="./" class="text-center">I already have a membership</a>
            </div>
            <!-- /.col -->
            <div class="col-4">
              <button type="submit" class="btn btn-primary btn-block" name="signup">Register</button>
            </div>
            <!-- /.col -->
          </div>
        </form>


      </div>
      <!-- /.form-box -->
    </div><!-- /.card -->
  </div>
  <!-- /.register-box -->






  <script>
    function getLocation() {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition, showError);
      }
    }

    function showPosition(position) {
      document.querySelector('input[name = "latitude"]').value = position.coords.latitude;
      document.querySelector('input[name = "longitude"]').value = position.coords.longitude;
    }

    function showError(error) {
      switch (error.code) {
        case error.PERMISSION_DENIED:
          alert("You Must Allow The Request For Geolocation To Fill Out The Form ");
          location.reload();
          break;
      }
    }
  </script>

</body>


</html>