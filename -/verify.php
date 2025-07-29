<?php require_once "../posbarcode/resources/config.php"; ?>
<?php
$email = $_SESSION['useremail'];
if ($email == false) {
  header('Location: ../posbarcode/');
}
?>
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



    <!-- <link href="../css/style_loinn.css" rel="stylesheet">
    <link href="../css/staye.css" rel="stylesheet"> -->



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

<body>
    <?php verify(); ?>

    <div class="container">
        <div class="row">
            <div class="col-md-4 offset-md-4 form">
                <div class="register-box">
                    <div class="card card-outline card-primary">
                        <div class="card-header text-center">
                            <a href="login" class="h1"><img width="60" src="../resources/images/logo/logo2.png" alt=""><b>TH</b>POS</a>
                        </div>
                        <div class="card-body">
                            <!-- <div class="back"> <img width="200" src="../resources/images/logo/logo2.png" alt=""></div> -->
                             
                            <?php display_messag_signin(); ?>

                            <form action="" method="POST">
                                <h2 class="text-center">Code Verification</h2>

                                <div class="form-group">
                                    <input class="form-control" type="text" name="code" placeholder="Enter verification code" required>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-block" name="cerify_code">Submit</button>
                                </div>
                            </form>

                        </div>
                        <!-- /.form-box -->
                    </div><!-- /.card -->
                </div>
            </div>
        </div>
    </div>

</body>

</html>