<?php


if ($_SESSION['useremail'] == ""  or $_SESSION['role'] == "User") {

  header('location:../');
}




if (isset($_POST['date_1'])) {
  $_SESSION['date'] = $_POST['date_1'];
  $date_1 = $_POST['date_1'];
  $date_2 = $_POST['date_2'];

  $_SESSION['date_1'] = $date_1;
  $_SESSION['date_2'] = $date_2;
} else {
  $date_1 = date('Y-m-01');
  $date_2 = date("Y-m-d");
  $_SESSION['date'] = $date_1;
  $_SESSION['date_1'] = $date_1;
  $_SESSION['date_2'] = $date_2;
}
$aus = $_SESSION['aus'];
?>

<!-- daterange picker -->
<link rel="stylesheet" href="../plugins/daterangepicker/daterangepicker.css">

<!-- Tempusdominus Bootstrap 4 -->
<link rel="stylesheet" href="../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">


<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Table Report</h1>
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


        <div class="card card-warning card-outline dowjpg">
          <div class="card-header">
            <h5 class="m-0">FROM : <?php echo date('d-m-Y', strtotime($date_1)) ?> -- To : <?php echo date('d-m-Y', strtotime($date_2)) ?> </h5>
          </div>


          <form action="" method="post" name="">

            <div class="card-body">
              <div class="row">

                <div class="col-md-5">
                  <div class="form-group">
                    <!-- <label>Date:</label> -->
                    <div class="input-group date" id="date_1" data-target-input="nearest">
                      <input type="text" class="form-control date_1" data-target="#date_1" name="date_1" value="<?php echo $date_1; ?>" data-date-format="yyyy-mm-dd">
                      <div class="input-group-append" data-target="#date_1" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                      </div>
                    </div>
                  </div>

                </div>

                <div class="col-md-5">
                  <div class="form-group">
                    <!-- <label>Date:</label> -->
                    <div class="input-group date" id="date_2" data-target-input="nearest">
                      <input type="text" class="form-control date_2" data-target="#date_2" name="date_2" value="<?php echo $date_2; ?>" data-date-format="yyyy-mm-dd">
                      <div class="input-group-append" data-target="#date_2" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                      </div>
                    </div>
                  </div>



                </div>

                <div class="col-md-2">

                  <div class="text-center">
                    <button type="submit" class="btn btn-warning " name="btnfilter" title="Filter Records"><i class="fas fa-search"></i> </button>
                    <button class="btn btn-primary btn-print" type="button" id="dw_bt"> <i class="fas fa-images"></i></button>

                  </div>
                </div>

              </div>


              <br>

              <?php
              $aus = $_SESSION['aus'];
              $change = query("SELECT * from tbl_change where aus='$aus'");
              confirm($change);
              $row_exchange = $change->fetch_object();
              $exchange = $row_exchange->exchange;
              $usd_or_real = $row_exchange->usd_or_real;

              $select = query("SELECT sum(total) as grandtotal , sum(subtotal) as stotal, count(invoice_id) as invoice ,sum(discount) as discountd from tbl_invoice where aus='$aus' and updated_at between '$date_1' AND '$date_2'");
              confirm($select);

              $row = $select->fetch_object();

              $grand_totall = $row->grandtotal;
              $subtotall = $row->stotal;
              $invoice = $row->invoice;
              $discountdd = $row->discountd ?? 0;

              if ($usd_or_real == "usd") {
                $grand_totalg = $grand_totall;
                $grand_total = number_format($grand_totalg, 2);
                $subtotalg = $subtotall;
                $subtotal = number_format($subtotalg, 2);
                $USD_usd = "$";
                $USD_txt = "USD";
              } else {
                $grand_totalg = $grand_totall * $exchange;
                $grand_total = number_format($grand_totalg);
                $subtotalg = $subtotall * $exchange;
                $subtotal = number_format($subtotalg);
                $USD_usd = "៛";
                $USD_txt = "KHR";
              }

              ?>



              <div class="row">
                <div class="col-12 col-sm-6 col-md-4">
                  <div class="info-box">
                    <span class="info-box-icon bg-info elevation-1"><i class="fas fa-book"></i></span>

                    <div class="info-box-content">
                      <span class="info-box-text">TOTAL DISCOUNT</span>
                      <span class="info-box-number">
                        <h2><?php echo number_format($discountdd, 2); ?></h2>

                      </span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                  <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-12 col-sm-6 col-md-4">
                  <div class="info-box mb-3">
                    <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-file"></i></span>

                    <div class="info-box-content">
                      <span class="info-box-text">SUBTOTAL (<?php echo $USD_txt ?>)</span>
                      <span class="info-box-number">
                        <h2><?php echo $subtotal; ?></h2>
                      </span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                  <!-- /.info-box -->
                </div>
                <!-- /.col -->

                <!-- fix for small devices only -->
                <div class="clearfix hidden-md-up"></div>

                <div class="col-12 col-sm-6 col-md-4">
                  <div class="info-box mb-3">
                    <span class="info-box-icon bg-success elevation-1"><i class="fas fa-file"></i></span>

                    <div class="info-box-content">
                      <span class="info-box-text">GRAND TOTAL (<?php echo $USD_txt ?>)</span>
                      <span class="info-box-number">
                        <h2><?php echo $grand_total; ?></h2>
                      </span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                  <!-- /.info-box -->
                </div>
                <!-- /.col -->

                <!-- /.col -->
              </div>
              <!-- /.row -->


              <br>



              <div style="overflow-x:auto;">
                <table class="table table-striped table-hover " id="">
                  <thead>
                    <tr class="bg-primary text-light">

                      <td>#</td>
                      <td>Receipt ID</td>
                      <td>Order Date</td>
                      <td>Table</td>
                      <td>Staff</td>
                      <td>Total Amount</td>
                      <!-- <td>Paid</td>
                      <td>Due</td> -->
                      <!-- <td>Payment Type</td> -->



                    </tr>

                  </thead>


                  <tbody>

                    <?php

                    $select = query("SELECT * from tbl_invoice where aus='$aus' and updated_at between '$date_1' AND '$date_2'");
                    confirm($select);
                    $no = 1;

                    while ($row = $select->fetch_object()) {
                      $invoice_id = $row->invoice_id;
                      $men_total = $row->total;
                      if ($usd_or_real == "usd") {
                        $men_totall = number_format($men_total, 2);
                      } else {
                        $men_totalll = $men_total * $exchange;
                        $men_totall = number_format($men_totalll);
                      }
                      echo '
               <tr class="bg-primary text-light">
               <td>' . $no . '</td>
               <td>' . $row->receipt_id   . '</td>
               <td>' . date("d/m/Y H:i:s", strtotime($row->order_date))   . '</td>
  
               <td>' . $row->table_name       . '</td>
               <td>' . $row->saler_name       . '</td>
               <td><span class="badge badge-danger">' . $men_totall . $USD_usd . '</td></span></td>
               ';




                      $selectt = query("SELECT * from tbl_invoice_details where invoice_id= $invoice_id");
                      confirm($selectt);

                      echo '     
                       <tr>
                        <td></td>
                        <td>Menu ID</td>
                        <td>Menu</td>
                        <td>Quantity</td>
                        <td>Price</td>
                        <td>Total Price</td>
                        </tr>';

                      while ($roww = $selectt->fetch_object()) {
                        $total = $roww->saleprice * $roww->qty;

                        if ($usd_or_real == "usd") {
                          $totall = number_format($total, 2);

                          $salepricee = $roww->saleprice;
                          $saleprice = number_format($salepricee, 2);
                        } else {
                          $totalll = $total * $exchange;
                          $totall = number_format($totalll);

                          $salepricee = $roww->saleprice * $exchange;
                          $saleprice = number_format($salepricee);
                        }

                        echo '
                       <tr>
                       <td></td>
                       <td>' . $roww->product_id   . '</td>
                       <td>' . $roww->product_name   . '</td>
                       <td>' . $roww->qty   . '</td>
                       <td>' . $saleprice  . $USD_usd . '</td>
                       <td>' . $totall . $USD_usd . '</td>
                       ';
                      }
                      $no++;
                    }
                    ?>
                  </tbody>
                </table>
              </div>







            </div>


          </form>






        </div>
      </div>



    </div>
    <!-- /.col-md-6 -->
  </div>
  <!-- /.row -->
</div><!-- /.content-fluid -->