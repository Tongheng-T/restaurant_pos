<?php require_once("../resources/config.php"); ?>
<?php check_login();
if ($_SESSION['useremail'] == "" or $_SESSION['role'] == "User") {
    header("Location: ../");
}

?>

<?php include(TEMPLATE_BACK . "/header.php"); ?>

<div class="containerr">
    <!-- Sidebar Section -->
    <?php include(TEMPLATE_BACK . "/sidebar.php"); ?>
    <!-- End of Sidebar Section -->

    <!-- Main Content -->



    <?php

    if ($_SERVER['REQUEST_URI'] == "/admin_jfjfkd346gfrr12fekmn%20dmrgh%20thhmadminser/ui/" || $_SERVER['REQUEST_URI'] == "/admin_jfjfkd346gfrr12fekmn%20dmrgh%20thhmadminser/itemt") {

        include(TEMPLATE_BACK . "/dashboard.php");
    }
    if (isset($_GET['dashboard'])) {

        include(TEMPLATE_BACK . "/dashboard.php");
    }
    if (isset($_GET['dashboardall'])) {

        include(TEMPLATE_BACK . "/dashboard copy.php");
    }
    if (isset($_GET['addmovies'])) {

        include(TEMPLATE_BACK . "/addproduct.php");
    }
    if (isset($_GET['payment_lis'])) {

        include(TEMPLATE_BACK . "/payment_lis.php");
    }
    if (isset($_GET['payment_lis_coffee'])) {

        include(TEMPLATE_BACK . "/payment_lis_coffee.php");
    }
    if (isset($_GET['movies'])) {

        include(TEMPLATE_BACK . "/addpat_movies.php");
    }
    if (isset($_GET['movies_list'])) {

        include(TEMPLATE_BACK . "/movies_list.php");
    }
    if (isset($_GET['viewpay'])) {

        include(TEMPLATE_BACK . "/viewpay.php");
    }
    if (isset($_GET['viewpayC'])) {

        include(TEMPLATE_BACK . "/viewpayC.php");
    }
    if (isset($_GET['editmovies'])) {

        include(TEMPLATE_BACK . "/edit_movies.php");
    }
    if (isset($_GET['registration'])) {

        include(TEMPLATE_BACK . "/registration.php");
    }
    if (isset($_GET['logout'])) {

        include(TEMPLATE_BACK . "/logout.php");
    }
    if (isset($_GET['users'])) {

        include(TEMPLATE_BACK . "/users.php");
    }
    ?>


    <!-- End of Main Content -->

    <!-- Right Section -->
    <div class="right-section">
        <div class="nav">
            <button id="menu-btn">
                <span class="material-icons-sharp">
                    menu
                </span>
            </button>
            <div class="dark-mode">
                <span class="material-icons-sharp active">
                    light_mode
                </span>
                <span class="material-icons-sharp">
                    dark_mode
                </span>
            </div>

            <div class="profile">
                <div class="info">
                    <p>Hey, <b>Reza</b></p>
                    <small class="text-muted">Admin</small>
                </div>
                <div class="profile-photo">
                    <img src="../productimages/user/<?php img_user() ?>">
                </div>
            </div>

        </div>
        <!-- End of Nav -->

        <div class="user-profile">
            <div class="logo">
                <img src="../productimages/logo6.png">
                <h2>THMovies</h2>
                <p>Fullstack Web Developer</p>
            </div>
        </div>

        <div class="reminders">
            <div class="header">
                <h2>Reminders</h2>
                <span class="material-icons-sharp">
                    notifications_none
                </span>
            </div>

            <div class="notification">
                <div class="icon">
                    <span class="material-icons-sharp">
                        volume_up
                    </span>
                </div>
                <div class="content">
                    <div class="info">
                        <h3>Workshop</h3>
                        <small class="text_muted">
                            08:00 AM - 12:00 PM
                        </small>
                    </div>
                    <span class="material-icons-sharp">
                        more_vert
                    </span>
                </div>
            </div>

            <div class="notification deactive">
                <div class="icon">
                    <span class="material-icons-sharp">
                        edit
                    </span>
                </div>
                <div class="content">
                    <div class="info">
                        <h3>Workshop</h3>
                        <small class="text_muted">
                            08:00 AM - 12:00 PM
                        </small>
                    </div>
                    <span class="material-icons-sharp">
                        more_vert
                    </span>
                </div>
            </div>

            <div class="notification add-reminder">
                <div>
                    <span class="material-icons-sharp">
                        add
                    </span>
                    <h3>Add Reminder</h3>
                </div>
            </div>

        </div>

    </div>


</div>

<!-- <script src="orders.js"></script> -->
<script src="indexb.js"></script>


<?php include(TEMPLATE_BACK . "/footer.php"); ?>