<?php require_once("../resources/config.php"); ?>

<?php

if ($_SESSION['role'] == "Admin") {
    include_once(TEMPLATE_BACK . "/header.php");
} else {
    include_once(TEMPLATE_BACK . "/headeruser.php");
}
?>


<?php check_login();
if (!empty($_SESSION['message'])) {
    display_message();
}else {
    $id = $_SESSION['userid'];
    $query = query("SELECT * from tbl_user where user_id = '$id' limit 1");
    $row = $query->fetch_object();
    $showdate = $row->date_new;
    $new_date = date('Y-m-d');
    $datetime4 = new DateTime($new_date);
    $datetime3 = new DateTime($showdate);
    $intervall = $datetime3->diff($datetime4);
    $texttt =   $intervall->format('%a');
    $numdatee = $row->tim - $texttt;

    if ($numdatee <= 0) {
        echo ' <script>
            Swal.fire({
               title: "<strong>ដល់ថ្ងៃបង <u>ប្រាក់</u></strong>",
               icon: "info",
               html: `
               សុំធ្វើការបង់<b>ប្រាក់</b>ដើម្បីបន្តការប្រើប្រាស់
               
               `,
               showCloseButton: true,
               showCancelButton: true,
               focusConfirm: false,
               confirmButtonText: `
               <a type="button" class="text-white" data-toggle="modal" data-target="#staticBackdrop"><i class="fa fa-shopping-cart"></i> Pay now</a>
               `,
               confirmButtonAriaLabel: "Pay now, great!",
               cancelButtonText: `
               <i class="fa fa-ban"> Cancel</i>
               `,
               cancelButtonAriaLabel: "Cancel"
               });
            </script>';
    }
}



if ($_SESSION['useremail'] == "" or $_SESSION['role'] == "User") {
    header("Location: ../");
}
?>

<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Payment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="contentt">
                    
                <?php service_list(); ?>
                </div>
            </div>
            <div class="modal-footer">

            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<?php savepay() ?>


<div class="modal fade" id="exampleModalpay" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" id="payuser">
           

        </div>
    </div>
</div>

<div class="content-wrapper">


    <?php

   if (rtrim($_SERVER['REQUEST_URI'], '/') == "/ui" || $_SERVER['REQUEST_URI'] == "/ui/itemt") {
    include(TEMPLATE_BACK . "/pos.php");
}

    if (isset($_GET['dashboard'])) {

        include(TEMPLATE_BACK . "/dashboard.php");
    }
    if (isset($_GET['home'])) {

        include(TEMPLATE_BACK . "/home.php");
    }
    if (isset($_GET['category'])) {

        include(TEMPLATE_BACK . "/category.php");
    }
    if (isset($_GET['addproduct'])) {

        include(TEMPLATE_BACK . "/addproduct.php");
    }
    if (isset($_GET['productlist'])) {

        include(TEMPLATE_BACK . "/productlist.php");
    }
    if (isset($_GET['viewproduct'])) {

        include(TEMPLATE_BACK . "/viewproduct.php");
    }
    if (isset($_GET['editproduct'])) {

        include(TEMPLATE_BACK . "/editproduct.php");
    }
    if (isset($_GET['printbarcode'])) {

        include(TEMPLATE_BACK . "/printbarcode.php");
    }
    if (isset($_GET['taxdis'])) {

        include(TEMPLATE_BACK . "/taxdis.php");
    }

    if (isset($_GET['pos'])) {

        include(TEMPLATE_BACK . "/pos.php");
    }
    if (isset($_GET['orderlist'])) {

        include(TEMPLATE_BACK . "/orderlist.php");
    }

    if (isset($_GET['editorderpos'])) {

        include(TEMPLATE_BACK . "/editorderpos.php");
    }
    if (isset($_GET['tablereport'])) {

        include(TEMPLATE_BACK . "/tablereport.php");
    }
    if (isset($_GET['graphreport'])) {

        include(TEMPLATE_BACK . "/graphreport.php");
    }
    if (isset($_GET['orderlist_user'])) {

        include(TEMPLATE_BACK . "/orderlist copy.php");
    }

    if (isset($_GET['exchange'])) {

        include(TEMPLATE_BACK . "/exchange.php");
    }

    if (isset($_GET['registration'])) {
        include(TEMPLATE_BACK . "/registration.php");
    }
    if (isset($_GET['settings'])) {

        include(TEMPLATE_BACK . "/settings.php");
    }
    if (isset($_GET['logout'])) {

        include(TEMPLATE_BACK . "/logout.php");
    }
    
    ?>



</div>


<!-- /#page-wrapper -->
<?php include(TEMPLATE_BACK . "/footer.php"); ?>