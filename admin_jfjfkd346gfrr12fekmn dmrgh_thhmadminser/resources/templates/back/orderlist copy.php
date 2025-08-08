<?php


if ($_SESSION['useremail'] == ""  or $_SESSION['role'] == "User") {

  header('location:../');
}




if (isset($_POST['date_1'])) {
  $_SESSION['date'] = $_POST['date_1'];
  $date_1 = $_POST['date_1'];
  $date_2 = $_POST['date_2'];
  $saler_name = $_POST['category_id'];

  $_SESSION['category_id'] = $saler_name;

  $_SESSION['date_1'] = $date_1;
  $_SESSION['date_2'] = $date_2;
} else {
  $date_1 = date('Y-m-01');
  $date_2 = date("Y-m-d");
  $_SESSION['date'] = $date_1;
  $_SESSION['date_1'] = $date_1;
  $_SESSION['date_2'] = $date_2;
  $saler_name = ' ';
  $_SESSION['category_id'] = $saler_name;
}

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


        <div class="card card-warning card-outline">
          <div class="card-header">
            <h5 class="m-0">FROM : <?php echo date('d-m-Y', strtotime($date_1)) ?> -- To : <?php echo date('d-m-Y', strtotime($date_2)) ?> </h5>
          </div>


          <form action="" method="post" name="">

            <div class="card-body">
              <div class="row">

                <div class="col-md-3">
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

                <div class="col-md-3">
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

                <div class="col-md-3">
                  <div class="input-group">
                    <div class="input-group-append">
                      <div class="input-group-text"><i class="fa fa-tasks"></i></div>
                    </div>
                    <select class="form-control addEventListener" name="category_id" required>
                      <?php
                      show_name_category($saler_name);
                      $select = query("SELECT * from tbl_user order by user_id desc");
                      confirm($select);
                      while ($row = $select->fetch_object()) {

                        echo '
                             <option value="' . $row->user_id . '">' . $row->username . '</option>
                                ';
                      }
                      ?>

                    </select>
                  </div>

                </div>

                <div class="col-md-2">

                  <div class="text-center">
                    <button type="submit" class="btn btn-warning" name="btnfilter">Filter Records</button>
                  </div>
                </div>

              </div>


              <br>

              <?php
              $select = query("SELECT sum(total) as grandtotal , sum(subtotal) as stotal, count(invoice_id) as invoice from tbl_invoice where order_date between '$date_1' AND '$date_2' AND saler_name = '$saler_name'");
              confirm($select);

              $row = $select->fetch_object();
              $grand_total = $row->grandtotal;
              $subtotal = $row->stotal;
              $invoice = $row->invoice;

              ?>



              <div class="row">
                <div class="col-12 col-sm-6 col-md-4">
                  <div class="info-box">
                    <span class="info-box-icon bg-info elevation-1"><i class="fas fa-book"></i></span>

                    <div class="info-box-content">
                      <span class="info-box-text">TOTAL INVOICE</span>
                      <span class="info-box-number">
                        <h2><?php echo number_format($invoice, 2); ?></h2>

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
                      <span class="info-box-text">SUBTOTAL</span>
                      <span class="info-box-number">
                        <h2><?php echo number_format($subtotal, 2); ?></h2>
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
                      <span class="info-box-text">GRAND TOTAL</span>
                      <span class="info-box-number">
                        <h2><?php echo number_format($grand_total, 2); ?></h2>
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
                <table class="table table-striped table-hover " id="table_report">
                  <thead>
                    <tr>

                      <td>Invoice ID</td>
                      <td>Order Date</td>
                      <td>SubTotal</td>
                      <td>Discount(%)</td>
                      <td>Total</td>
                      <td>Paid</td>
                      <td>Due</td>
                      <td>Payment Type</td>



                    </tr>

                  </thead>


                  <tbody>

                    <?php

                    $select = query("SELECT * from tbl_invoice where order_date between '$date_1' AND '$date_2' AND saler_name = '$saler_name'");
                    confirm($select);

                    while ($row = $select->fetch_object()) {

                      echo '
               <tr>
               
               <td>' . $row->invoice_id   . '</td>
               <td>' . $row->order_date   . '</td>
               <td>' . $row->subtotal        . '</td>
               <td>' . $row->discount       . '</td>
               <td><span class="badge badge-danger">' . $row->total        . '</td></span></td>
               <td>' . $row->paid         . '</td>
               <td>' . $row->due          . '</td>';




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


          </form>


        </div>
      </div>



    </div>
    <!-- /.col-md-6 -->
  </div>
  <!-- /.row -->
</div><!-- /.content-fluid -->