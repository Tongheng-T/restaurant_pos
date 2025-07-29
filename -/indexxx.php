<?php require_once "../posbarcode/resources/config.php"; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="THPOS, thpos.store, ប្រព័ន្ធគ្រប់គ្រងទិន្នន័យ, Cambodia, Sales Management System, ប្រព័ន្ធគ្រប់គ្រងភោជនីយដ្ឋាន, Restaurant Management System">
    <title>RESTAURANT POS | Log in | By:THPOS</title>
    <link rel='shortcut icon' href="../ui/logo/b256.ico" type="image/x-icon">
    <link rel="icon" href="../ui/logo/b32.ico" sizes="32x32">
    <link rel="icon" href="../ui/logo/b48.ico" sizes="48x48">
    <link rel="icon" href="../ui/logo/b96.ico" sizes="96x96">
    <link rel="icon" href="../ui/logo/b256.ico" sizes="144x144">
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
        width: 353px;
        display: flex;
        justify-content: center;
        align-items: center;
        position: absolute;
        height: 548px;
        opacity: 49%;

    }
    .back .img {
        width: 1112px;
    }
</style>
<?php

$email = "";
if (isset($_POST['btn_login'])) {
    $email = $_POST['txt_email'];
} elseif (isset($_SESSION['useremail'])) {
    $email = $_SESSION['useremail'];
}


?>
<?php display_messag_signin();
unset($_SESSION['messagee']) ?>

<body class="hold-transition login-page" onload="getLocation();">
    <div class="login-box">

        <!-- <div class="back"> <img class="img" src="../resources/images/logo/backg.png" alt=""></div> -->
        <!-- /.login-logo -->
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="https://www.thpos.store/" class="h1"><b>TH</b> POS</a>
            </div>

            <div class="card-body">
                <p class="login-box-msg">Sign in to start your session</p>
                <p class="center text-center"><img src="../resources/images/logo3.png" height="200px" alt=""></p>
                <?php login_user(); ?>
                <?php display_message(); ?>
                <form action="" method="post">
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" placeholder="Email" name="txt_email" required value="<?php echo $email ?>">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" id="pwd" placeholder="Password" name="txt_password" required>
                        <div class="input-group-append">
                            <div class="input-group-text" id="eye">
                                <span class="fas fa-eye-slash" id="eyeicon"></span>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" class="form-control" value="" name="latitude">
                    <input type="hidden" class="form-control" value="" name="longitude">

                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <!-- <a href="#" class="toastrDefaultInfo">I forgot my password</a> -->
                                <p class="mb-1">
                                    <a href="forgot_password">I forgot my password</a>
                                </p>

                                <p class="mb-0">
                                    <a href="register" class="text-center">Register a new membership</a>
                                </p>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block" name="btn_login">Login</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>

                <!-- /.social-auth-links -->

                <p class="mb-1">

                </p>
                <p class="mb-0">

                </p>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.login-box -->



    <script>
        $(function() {
            var Toast = Swal.mixin({
                toast: true,
                position: 'top',
                showConfirmButton: false,
                timer: 5000
            });
            $('.toastrDefaultInfo').click(function() {
                Toast.fire({
                    icon: 'info',
                    title: 'ដើម្បីទទួលបានពាក្យសម្ងាត់ សូមទាក់ទងអ្នកគ្រប់គ្រង ឬអ្នកផ្តល់សេវា'
                })
            });

        });
    </script>




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




        // function getLocation() {
        //     if (navigator.geolocation) {
        //         navigator.geolocation.getCurrentPosition(showPosition, showError);
        //     }
        // }

        // function showPosition(position) {
        //     document.querySelector('input[name = "latitude"]').value = position.coords.latitude;
        //     document.querySelector('input[name = "longitude"]').value = position.coords.longitude;
        // }

        // function showError(error) {
        //     switch (error.code) {
        //         case error.PERMISSION_DENIED:
        //             alert("You Must Allow The Request For Geolocation To Fill Out The Form ");
        //             location.reload();
        //             break;
        //     }
        // }
    </script>
</body>

</html>