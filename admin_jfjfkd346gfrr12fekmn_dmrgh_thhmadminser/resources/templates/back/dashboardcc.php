<?php


if ($_SESSION['useremail'] == ""  or $_SESSION['role'] == "User") {

    header('location:../');
}




$select = query("SELECT sum(views) as viw , count(product_id) as invoice from products");
confirm($select);
$row = $select->fetch_object();

$total_movie = $row->invoice;

$viewss = $row->viw;

$select = query("SELECT count(cat_id) as cate from categories");
confirm($select);
$row = $select->fetch_object();
$total_category = $row->cate;



$select_sub = query("SELECT count(cat_id) as catesub from sub_categories");
confirm($select_sub);

$roww = $select_sub->fetch_object();

$total_category_sub = $roww->catesub;


?>

<!-- ChartJS -->
<script src="../plugins/chart.js/Chart.min.js"></script>

<main>
    <h1>Analytics</h1>


    <div class="row">
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
                <div class="inner">
                    <h3><?php echo $total_movie; ?></h3>

                    <p>TOTAL MOVIE</p>
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
                    <h3><?php echo $viewss; ?></h3>

                    <p>TOTAL VIEWS</p>
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
                    <h3><?php echo $total_category; ?></h3>

                    <p>TOTAL CATEGORY</p>
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
                    <h3><?php echo $total_category_sub; ?></h3>

                    <p>TOTAL CATEGORY_SUB</p>
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
  





</main>