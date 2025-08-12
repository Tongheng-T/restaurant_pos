<?php require_once "../resources/config.php"; ?>

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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
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


    .login-box .card {
        border-radius: 20px;
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
        background-color: #ffffff;
    }

    .card-header .h1 b {
        color: #007bff;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
        border-radius: 25px;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #0056b3;
    }

    input.form-control {
        border-radius: 20px;
        padding-left: 15px;
    }

    .card-outline.card-primary {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border: none;
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

                <img src="../../resources/images/logo3.png" alt="THPOS Logo" style="height: 80px; margin-bottom: 10px;">
                <h1 style="font-weight: bold; font-size: 26px; color: #007bff;">THPOS</h1>


                <a href="https://www.thpos.store/" class="h1"><b>TH</b> POS</a>
            </div>

            <div class="card-body">
                <p class="login-box-msg text-center" style="font-size: 18px;">សូមបញ្ចូល Email និង ពាក្យសម្ងាត់របស់អ្នក</p>


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

                                <div class="text-center mt-3">
                                    <a href="<?php
                                                $client = new Google_Client();
                                                $client->setClientId('678847511198-c7rhe1h2udm2urs3j1hsul7srm0i0m1q.apps.googleusercontent.com');
                                                $client->setClientSecret('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');
                                                $client->setRedirectUri('https://thposs.uk/restaurant/google/google-callback.php');
                                                $client->addScope('email');
                                                $client->addScope('profile');
                                                echo $client->createAuthUrl();
                                                ?>" class="btn btn-danger btn-block" style="border-radius: 25px;"><i class="fab fa-google mr-2"></i>Sign in with Google</a>
                                </div>
                                <!-- <div class="text-center mt-3">

                                    <a href="https://t.me/your_bot" class="btn btn-info btn-block" style="border-radius: 25px; margin-top: 10px;">
                                        <i class="fab fa-telegram-plane mr-2"></i> Sign in with Telegram
                                    </a>
                                </div> -->
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

    <div class="footer">
        <p class="text-center mt-4 text-muted">
            &copy; <?= date('Y') ?> THPOS | All Rights Reserved
        </p>

    </div>

    <!-- Add in body element -->
    <div class="login-box" data-aos="fade-up" data-aos-duration="1000">


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
        </script>

        <!-- <script>
            document.addEventListener("DOMContentLoaded", function() {
                getLocation();
            });

            function getLocation() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(showPosition, showError);
                }
            }

            function showPosition(position) {
                document.querySelector('input[name="latitude"]').value = position.coords.latitude;
                document.querySelector('input[name="longitude"]').value = position.coords.longitude;
            }

            function showError(error) {
                switch (error.code) {
                    case error.PERMISSION_DENIED:
                        alert("សូមអនុញ្ញាតការចូលដំណើរការទីតាំងដើម្បីបំពេញការចុះឈ្មោះ។");
                        break;
                    case error.POSITION_UNAVAILABLE:
                        alert("ទីតាំងមិនអាចរកបាន។");
                        break;
                    case error.TIMEOUT:
                        alert("ការស្នើសុំទីតាំងបានអស់ពេល។");
                        break;
                    default:
                        alert("កំហុសអំពី geolocation.");
                }
            }
        </script> -->
</body>

</html>