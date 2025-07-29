<?php require_once "../posbarcode/resources/config.php"; ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>RESTAURANT POS | Register | By:THPOS</title>
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
    height: 270px;
    opacity: 16%;

  }

  .form-control {
    background-color: #ffffff69;
  }
</style>
<?php create_acc(); ?>

<body class="hold-transition register-page" onload="getLocation();">
  <div class="register-box">
    <div class="card card-outline card-primary">
      <div class="card-header text-center">
        <a href="login" class="h1"><img width="60" src="../resources/images/logo/logo2.png" alt=""><b>TH</b>POS</a>
      </div>
      <div class="card-body">
        <div class="back"> <img width="200" src="../resources/images/logo/logo2.png" alt=""></div>

        <p class="login-box-msg">Register a new membership</p>
        <?php display_message(); ?>

        <form class="needs-validation" novalidate action="" method="post">
          <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Your name" name="username" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="email" class="form-control" placeholder="Email" name="ap_email" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>
          <input type="hidden" class="form-control" value="" name="latitude">
          <input type="hidden" class="form-control" value="" name="longitude">
          <div class="input-group mb-3">
            <input type="password" class="form-control" id="pwd" placeholder="Password" name="password" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="password" class="form-control" placeholder="Retype password" name="password_re" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-8">
              <a href="../-/" class="text-center">I already have a membership</a>
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
    // Example starter JavaScript for disabling form submissions if there are invalid fields
    (function() {
      'use strict';
      window.addEventListener('load', function() {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function(form) {
          form.addEventListener('submit', function(event) {
            if (form.checkValidity() === false) {
              event.preventDefault();
              event.stopPropagation();
            }
            form.classList.add('was-validated');
          }, false);
        });
      }, false);
    })();
  </script>


  <!-- <script>
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
  </script> -->

</body>


</html>