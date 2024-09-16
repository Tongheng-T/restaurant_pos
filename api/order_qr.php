<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>RESTAURANT POS | Order QR | By:TH</title>
    <link rel='shortcut icon' href="ui/logo/256.ico" type="image/x-icon">
    <link rel="icon" href="../ui/logo/32.ico" sizes="32x32">
    <link rel="icon" href="../ui/logo/48.ico" sizes="48x48">
    <link rel="icon" href="../ui/logo/96.ico" sizes="96x96">
    <link rel="icon" href="../ui/logo/256.ico" sizes="144x144">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../dist/css/castermk.css">
    <link rel="stylesheet" href="../dist/css/pay.css">

    <link rel="stylesheet" href="../dist/css/styles_settingg.css">

    <!-- DataTables -->
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="../plugins/sweetalert2/sweetalert2.min.css">
    <!-- Toastr -->
    <link rel="stylesheet" href="../plugins/toastr/toastr.min.css">

    <!-- SweetAlert2 -->
    <script src="../plugins/sweetalert2/sweetalert2.min.js"></script>
    <!-- Toastr -->
    <script src="../plugins/toastr/toastr.min.js"></script>

    <script src="../dist/js/scriptss.js"></script>

</head>
<?php

require_once "../resources/config.php";

ob_end_flush();
// $aus = $_SESSION['aus'];where aus='$aus'

$aus = $_GET['aus'];
$select = query("SELECT * from tbl_taxdis where aus='$aus'");
confirm($select);
$row = $select->fetch_object();

$change = query("SELECT * from tbl_change where aus='$aus'");
confirm($change);
$row_exchange = $change->fetch_object();
$exchange = $row_exchange->exchange;
$usd_or_real = $row_exchange->usd_or_real;


if ($usd_or_real == "usd") {

    $USD_usd = " $";
    $USD_txt = "USD";
    $Change_rea = "៛";
    $Change = "KHR";
} else {

    $USD_usd = " ៛";
    $Change = "USD";
    $Change_rea = "$";
    $USD_txt = "KHR";
}

?>


<style type="text/css">
    @font-face {
        font-family: "OSbattambang";
        src: url(fone/KhmerOSbattambang.ttf)format("truetype");
    }

    * {
        font-family: "OSbattambang";
    }

    .table {
        background-color: white;
    }

    .dropdown-menu {
        max-height: 411px;
        overflow: auto;
    }

    .tableFixHead {
        overflow: scroll;
        height: 520px;
    }

    .tableFixHead thead th {
        position: sticky;
        top: 0;
        z-index: 1;
    }

    table {
        border-collapse: collapse;
        width: 100px;
    }

    th,
    td {
        padding: 8px 16px;
    }

    th {
        background: #eee;
    }

    a {
        color: #343a40;
    }

    a:hover {
        text-decoration: none;
        color: #343a40;
    }

    .tableFixHead {
        height: 410px;
        overflow-x: hidden;

    }
</style>


<body class="hold-transition sidebar-mini sidebar-collapse" onload="getLocation();">
    <div class="content-wrapper">
        <div class="wrapper">


            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">


                            <div class="card card-primary card-outline">

                                <div class="card-body">
                                    <div class="row" id="table-detail"></div>

                                    <div class="row">
                                        <div class="col-md-5">

                                            <div id="selected-table"></div>
                                            <div id="order-detail">

                                            </div>
                                        </div>

                                        <input type="hidden" class="form-control latitude" name="latitude" id="latitude">
                                        <input type="hidden" class="form-control" name="longitude">

                                        <div class="col-md-7">
                                            <nav>
                                                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                                    <?php

                                                    $select = query("SELECT * from tbl_category where aus='$aus'");
                                                    confirm($select);
                                                    foreach ($select as $roww) {
                                                        echo ' <a class="nav-item nav-link" data-id="' . $roww["catid"] . '" data-toggle="tab"> ' . $roww["category"] . '</a>';
                                                    }
                                                    ?>

                                                </div>
                                            </nav>
                                            <div id="list-menu" class="row mt-2"></div>

                                        </div>


                                        <!-- Modal -->
                                        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Payment</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <h3 class="totalAmount"></h3>
                                                        <h3 class="changeAmount"></h3>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">SUBTOTAL(<?php echo $USD_txt ?>) </span>
                                                            </div>
                                                            <input type="text" class="form-control" name="txtsubtotal" id="txtsubtotal_id" readonly>
                                                            <div class="input-group-append">
                                                                <span class="input-group-text"><i class="fa" style="font-size: 24px;"><?php echo $USD_usd ?></i></span>
                                                            </div>
                                                        </div>


                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">DISCOUNT(%)</span>
                                                            </div>
                                                            <input type="text" class="form-control" name="txtdiscount" id="txtdiscount_p" value=" <?php echo $row->discount ?> " readonly>
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">%</span>
                                                            </div>
                                                        </div>


                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">DISCOUNT(<?php echo $USD_txt ?>)</span>
                                                            </div>
                                                            <input type="text" class="form-control" id="txtdiscount_n" readonly>
                                                            <div class="input-group-append">
                                                                <span class="input-group-text"><i class="fa" style="font-size: 24px;"><?php echo $USD_usd ?></i></span>
                                                            </div>
                                                        </div>

                                                        <hr style="height:2px; border-width:0; color:black; background-color:black;">

                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">TOTAL(<?php echo $USD_txt ?>)</span>
                                                            </div>
                                                            <input type="text" class="form-control form-control-lg total" name="txttotal" id="txttotall" readonly>
                                                            <div class="input-group-append">
                                                                <span class="input-group-text"><i class="fa" style="font-size: 24px;"><?php echo $USD_usd ?></i></span>
                                                            </div>
                                                        </div>

                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">TOTAL(<?php echo $Change ?>)</span>
                                                            </div>
                                                            <input type="text" class="form-control form-control-lg total" name="txttotal" id="txttotall_khr" readonly>
                                                            <div class="input-group-append">
                                                                <span class="input-group-text"><i class="fa" style="font-size: 24px;"><?php echo $Change_rea ?></i></span>
                                                            </div>
                                                        </div>

                                                        <hr style="height:2px; border-width:0; color:black; background-color:black;">

                                                        <div class="icheck-success d-inline">
                                                            <input type="radio" name="rb" value="Cash" checked id="radioSuccess1">
                                                            <label for="radioSuccess1">
                                                                CASH
                                                            </label>
                                                        </div>
                                                        <div class="icheck-primary d-inline">
                                                            <input type="radio" name="rb" value="QR" id="radioSuccess2">
                                                            <label for="radioSuccess2">
                                                                QR
                                                            </label>
                                                        </div>

                                                        <hr style="height:2px; border-width:0; color:black; background-color:black;">


                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">Change(<?php echo $USD_txt ?>)</span>
                                                            </div>
                                                            <input type="text" class="form-control" name="txtdue" id="txtdue" readonly>
                                                            <div class="input-group-append">
                                                                <span class="input-group-text"> <i class="fa" style="font-size: 24px;">៛</i></span>
                                                            </div>
                                                        </div>


                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">Paid Amount(<?php echo $USD_txt ?>)</span>
                                                            </div>
                                                            <input type="text" class="form-control" name="txtpaid" id="txtpaid">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text"> <i class="fa" style="font-size: 24px;">៛</i></span>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary btn-save-payment" name="btnsaveorder" disabled>Save Payment</button>

                                                    </div>
                                                </div>
                                            </div>




                                        </div>
                                        <!-- Modal -->
                                        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Payment</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <h3 class="totalAmount"></h3>
                                                        <h3 class="changeAmount"></h3>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">SUBTOTAL(<?php echo $USD_txt ?>) </span>
                                                            </div>
                                                            <input type="text" class="form-control" name="txtsubtotal" id="txtsubtotal_id" readonly>
                                                            <div class="input-group-append">
                                                                <span class="input-group-text"><i class="fa" style="font-size: 24px;"><?php echo $USD_usd ?></i></span>
                                                            </div>
                                                        </div>


                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">DISCOUNT(%)</span>
                                                            </div>
                                                            <input type="text" class="form-control" name="txtdiscount" id="txtdiscount_p" value=" <?php echo $row->discount ?> " readonly>
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">%</span>
                                                            </div>
                                                        </div>


                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">DISCOUNT(<?php echo $USD_txt ?>)</span>
                                                            </div>
                                                            <input type="text" class="form-control" id="txtdiscount_n" readonly>
                                                            <div class="input-group-append">
                                                                <span class="input-group-text"><i class="fa" style="font-size: 24px;"><?php echo $USD_usd ?></i></span>
                                                            </div>
                                                        </div>

                                                        <hr style="height:2px; border-width:0; color:black; background-color:black;">

                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">TOTAL(<?php echo $USD_txt ?>)</span>
                                                            </div>
                                                            <input type="text" class="form-control form-control-lg total" name="txttotal" id="txttotall" readonly>
                                                            <div class="input-group-append">
                                                                <span class="input-group-text"><i class="fa" style="font-size: 24px;"><?php echo $USD_usd ?></i></span>
                                                            </div>
                                                        </div>

                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">TOTAL(<?php echo $Change ?>)</span>
                                                            </div>
                                                            <input type="text" class="form-control form-control-lg total" name="txttotal" id="txttotall_khr" readonly>
                                                            <div class="input-group-append">
                                                                <span class="input-group-text"><i class="fa" style="font-size: 24px;"><?php echo $Change_rea ?></i></span>
                                                            </div>
                                                        </div>

                                                        <hr style="height:2px; border-width:0; color:black; background-color:black;">

                                                        <div class="icheck-success d-inline">
                                                            <input type="radio" name="rb" value="Cash" checked id="radioSuccess1">
                                                            <label for="radioSuccess1">
                                                                CASH
                                                            </label>
                                                        </div>
                                                        <div class="icheck-primary d-inline">
                                                            <input type="radio" name="rb" value="QR" id="radioSuccess2">
                                                            <label for="radioSuccess2">
                                                                QR
                                                            </label>
                                                        </div>

                                                        <hr style="height:2px; border-width:0; color:black; background-color:black;">


                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">Change(<?php echo $USD_txt ?>)</span>
                                                            </div>
                                                            <input type="text" class="form-control" name="txtdue" id="txtdue" readonly>
                                                            <div class="input-group-append">
                                                                <span class="input-group-text"> <i class="fa" style="font-size: 24px;">៛</i></span>
                                                            </div>
                                                        </div>


                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">Paid Amount(<?php echo $USD_txt ?>)</span>
                                                            </div>
                                                            <input type="text" class="form-control" name="txtpaid" id="txtpaid">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text"> <i class="fa" style="font-size: 24px;">៛</i></span>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary btn-save-payment" name="btnsaveorder" disabled>Save Payment</button>
                                                    </div>
                                                </div>
                                            </div>




                                        </div>
                                    </div>



                                </div>


                            </div>



                        </div>
                        <!-- /.col-md-6 -->
                    </div>
                    <!-- /.row -->
                </div><!-- /.content-fluid -->
            </div>
        </div>
    </div>
    <footer class="main-footer">
        <!-- To the right -->
        <div class="float-right d-none d-sm-inline">
            POS RESTAURANT INVENTORY SYSTEM V-3.1
        </div>
        <!-- Default to the left -->
        <strong>BY: <a href="https://web.facebook.com/TonghengCoding " target="_blank">Tongheng-T</a></strong>
    </footer>


    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>


    <!-- Select2 -->
    <script src="plugins/select2/js/select2.full.min.js"></script>

    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="plugins/sweetalert2/sweetalert2.min.js"></script>
    <!-- InputMask -->
    <script src="plugins/moment/moment.min.js"></script>

    <!-- date-range-picker -->
    <script src="plugins/daterangepicker/daterangepicker.js"></script>

    <!-- Tempusdominus Bootstrap 4 -->
    <script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>


    <!-- pos order -->
    <script src="resources/templates/back/poslqr.js"></script>





    <script src="resources/templates/back/dom-to-image.js"></script>


    <?php

    $change = query("SELECT * from tbl_change where aus='$aus'");
    confirm($change);
    $row_exchange = $change->fetch_object();
    $exchange = $row_exchange->exchange;
    $usd_or_real = $row_exchange->usd_or_real; ?>

    <script>
        // when a user click on the payment button
        $("#order-detail").on("click", ".btn-payment", function() {
            var totalAmoutt = $(this).attr('data-totalAmount');

            discount = parseFloat($("#txtdiscount_p").val());
            discount = discount / 100;


            if ('<?php echo $usd_or_real ?>' == 'usd') {

                totalAmout = totalAmoutt
                discount = discount * totalAmout;

                total = totalAmout - discount;
                subtotal = total * <?php echo $exchange ?>;

            } else {
                totalAmout = totalAmoutt * <?php echo $exchange ?>

                discount = discount * totalAmout;

                total = totalAmout - discount;
                subtotal = total / <?php echo $exchange ?>

            }

            total_khr = subtotal
            // due = total - paid_amt;

            $("#txtdiscount_n").val(discount);
            $("#txttotall").val(total);
            $("#txttotall_khr").val(total_khr);

            $("#txtsubtotal_id").val(totalAmout);

            // $("#txtsubtotal_id").val('');
            // $(".changeAmount").html('');
            SALE_ID = $(this).data('id');


        });
    </script>






    <!-- ....../////////////////productlist -->

    <script>
        $(document).ready(function() {
            $('.btndelete').click(function() {
                var tdh = $(this);
                var id = $(this).attr("id");

                Swal.fire({
                    title: 'Do you want to delete?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {

                        $.ajax({
                            url: 'resources/templates/back/productdelete.php',
                            type: 'post',
                            data: {
                                pidd: id
                            },
                            success: function(data) {
                                tdh.parents('tr').hide();
                            }

                        });

                        Swal.fire(
                            'Deleted!',
                            'Your Product has been deleted.',
                            'success'
                        )
                    }
                })

            });

        });
    </script>


    <!-- taxdis -->





    <!-- tablereport -->


    <!-- dashboard -->
    <script>
        // $(document).ready(function() {
        //     $('#table_bestsellingproduct').DataTable({


        //     });
        // });



        // $('.id').on('click', function() {
        //     var id = $(this).attr("id");

        //     $.ajax({
        //         url: "resources/templates/back/payqr.php",
        //         method: "Post",
        //         data: {
        //             id: id

        //         },
        //         success: function(data) {

        //             $('#payuser').html(data);
        //             $('#payuser').append(data.htmlresponse);

        //         }
        //     });
        // });

        // $('.ff').on('click', function() {
        //   var id = $(this).attr("id");

        //   $.ajax({
        //     url: "resources/templates/back/payqr.php",
        //     method: "Post",
        //     data: {
        //       id: id

        //     },
        //     success: function(data) {

        //       $('#jj').html(data);
        //       $('#jj').append(data.htmlresponse);

        //     }
        //   });
        // });
        // $('.viw').on('click', function() {
        //     var id = $(this).attr("id");

        //     $.ajax({
        //         url: "resources/templates/back/unmesspay.php",
        //         method: "Post",
        //         data: {
        //             id: id

        //         },
        //         success: function(data) {

        //             window.location.reload(data);

        //         }
        //     });
        // });

        // $('.showmess').on('click', function() {

        //     $.ajax({
        //         url: "resources/templates/back/showmess.php",
        //         success: function(data) {
        //             $('#showmess').html(data);
        //             $('#showmess').append(data.htmlresponse);

        //         }
        //     });
        // });




        function notify() {
            const notification = new Notification('THPOS:', {
                body: `Notifications`,
                icon: 'logo/logo1.png',
                vibration: [300, 200, 300],
            });

            notification.addEventListener('click', function() {
                window.open('http://localhost/coffee%20-%20Copy/ui/');
            });
            setTimeout(() => notification.close(), 5 * 2000);

        }

        function playMusic() {
            let audio = new Audio("resources/sound/notification.mp3");
            audio.play()
        }


        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition, showError);
            }
        }

        function showPosition(position) {
            document.querySelector('input[name = "latitude"]').value = position.coords.latitude;
            document.querySelector('input[name = "longitude"]').value = position.coords.longitude;
            var lat = $_GET.lat;
            var latitudee = position.coords.latitude;
            vv=lat -latitudee;
alert(latitudee)
            if (vv == 0) {
                alert('fa')
            }else{
                
                window.location.href = '/';
            }
        }

        function showError(error) {
            switch (error.code) {
                case error.PERMISSION_DENIED:
                    alert("You Must Allow The Request For Geolocation To Fill Out The Form ");
                    location.reload();
                    break;
            }
        }


        var lat = $_GET.lat;
        // alert(dd);
        // if (latitude == lat) {
        //     window.location.href = '';
        // }

        
    </script>

    <!-- http://localhost/coffee%20-%20Copy/order_qr?aus=1&TABLE_ID=1&TABLE_NAME=A1&user=16&lat=13.6675328 -->
</body>

</html>