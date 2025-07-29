<?php


if ($_SESSION['useremail'] == ""  or $_SESSION['role'] == "User") {

    header('location:../');
}



$aus = $_SESSION['aus'];
$change = query("SELECT * from tbl_change where aus='$aus'");
confirm($change);
$row_exchange = $change->fetch_object();
$exchange = $row_exchange->exchange;
$usd_or_real = $row_exchange->usd_or_real;

$select = query("SELECT sum(total) as gt , count(invoice_id) as invoice from tbl_invoice where aus='$aus'");
confirm($select);
$row = $select->fetch_object();

$total_order = $row->invoice;
$grand_total = $row->gt;

if ($usd_or_real == "usd") {
    $total_revn = $grand_total;
    $USD_usd = " $";
    $USD_txt = "USD";
} else {
    $total_revn = $grand_total * $exchange;
    $USD_usd = " ៛";
    $USD_txt = "KHR";
}


$select = query("SELECT count(product) as pname from tbl_product where aus='$aus'");
confirm($select);

$row = $select->fetch_object();

$total_product = $row->pname;

$select = query("SELECT count(category) as cate from tbl_category where aus='$aus'");
confirm($select);

$row = $select->fetch_object();

$total_category = $row->cate;




?>

<!-- ChartJS -->
<script src="../plugins/chart.js/Chart.min.js"></script>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Admin Dashboard</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <!-- <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Starter Page</li> -->
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">



                <div class="row">
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3><?php echo $total_order; ?></h3>

                                <p>TOTAL INVOICE</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-bag"></i>
                            </div>
                            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3><?php echo number_format($grand_total, 2); ?></h3>

                                <p>TOTAL REVENUE(<?php echo $USD_txt ?>)</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-stats-bars"></i>
                            </div>
                            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3><?php echo $total_product; ?></h3>

                                <p>TOTAL PRODUCT</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-person-add"></i>
                            </div>
                            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3><?php echo $total_category; ?></h3>

                                <p>TOTAL CATEGORY</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-pie-graph"></i>
                            </div>
                            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                </div>
                <!-- /.row -->


                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h5 class="m-0">Earning By Date</h5>
                    </div>
                    <div class="card-body">

                        <?php

                        $select = query("SELECT order_date , total  from tbl_invoice where aus='$aus' group by order_date LIMIT 50");
                        confirm($select);
                        $ttl = [];
                        $date = [];

                        while ($row = $select->fetch_assoc()) {
                            extract($row);

                            $date[] = $order_date;

                            if ($usd_or_real == "usd") {
                                $ttl[] = $total;
                            } else {
                                $ttl[] = $total * $exchange;
                            }
                        }

                        // echo json_encode($total);

                        ?>

                        <div>
                            <canvas id="myChart" style="height: 250px"></canvas>
                        </div>

                    </div>

                    <script>
                        const ctx = document.getElementById('myChart');

                        new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: <?php echo json_encode($date); ?>,
                                datasets: [{
                                    label: 'Total Earning',
                                    backgroundColor: 'rgb(255,99,132)',
                                    borderColor: 'rgb(255,99,132)',
                                    data: <?php echo json_encode($ttl); ?>,
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                scales: {
                                    y: {
                                        beginAtZero: true

                                    }
                                }
                            }
                        });
                    </script>


                </div>

            </div>


        </div>
        <!-- /.col-md-6 -->


        <div class="row">


            <div class="col-md-6">

                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h5 class="m-0">BEST SELLING PRODUCT</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-hover " id="table_bestsellingproduct">
                            <thead>
                                <tr>

                                    <td>Product ID</td>
                                    <td>Product Name</td>

                                    <td>QTY</td>
                                    <td>rate</td>
                                    <td>Total</td>

                                </tr>

                            </thead>


                            <tbody>

                                <?php

                                $select = query("SELECT product_id,product_name,rate,sum(qty) as q , sum(saleprice) as total from tbl_invoice_details where aus='$aus' group by product_id order by sum(qty) DESC LIMIT 12");
                                confirm($select);

                                while ($row = $select->fetch_object()) {

                                    echo '
                                        <tr>
                                        
                                        <td>' . $row->product_id   . '</td>
                                        <td><span class="badge badge-dark">' . $row->product_name   . '</td></span>
                                        
                                        <td><span class="badge badge-success">' . $row->q   . '</td></span>
                                        <td><span class="badge badge-primary">' . $row->rate   . '</td>
                                        <td><span class="badge badge-danger">' . $row->total . '</td>';
                                }
                                ?>
                            </tbody>
                        </table>



                    </div>
                </div>

            </div>


            <div class="col-md-6">

                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h5 class="m-0">Earning By Date</h5>
                    </div>
                    <div class="card-body">

                        <table class="table table-striped table-hover " id="table_recentorder">
                            <thead>
                                <tr>

                                    <td>Invoice ID</td>
                                    <td>Order Date</td>

                                    <td>Total</td>
                                    <td>Paymrnt Type</td>

                                </tr>

                            </thead>


                            <tbody>

                                <?php

                                $select = query("SELECT * from tbl_invoice where aus='$aus'  order by invoice_id DESC LIMIT 30");
                                confirm($select);

                                while ($row = $select->fetch_object()) {

                                    echo '
                                         <tr>
                                         
                                         <td>' . $row->invoice_id . '</td>
                                         <td><span class="badge badge-dark">' . $row->order_date   . '</td></span>
                                         <td><span class="badge badge-danger">' . $row->total . '</td>';

                                    if ($row->payment_type == "Cash") {
                                        echo '<td><span class="badge badge-warning">' . $row->payment_type . '</td></span></td>';
                                    } else if ($row->payment_type == "Card") {

                                        echo '<td><span class="badge badge-success">' . $row->payment_type . '</td></span></td>';
                                    } else {
                                        echo '<td><span class="badge badge-danger">' . $row->payment_type . '</td></span></td>';
                                    }
                                }
                                ?>
                            </tbody>
                        </table>



                    </div>
                </div>

            </div>

        </div>

    </div>
    <!-- /.row -->

</div>
<!-- /.content -->



