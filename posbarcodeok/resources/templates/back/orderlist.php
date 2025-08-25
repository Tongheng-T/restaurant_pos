<?php


if ($_SESSION['useremail'] == "" or $_SESSION['role'] == "") {

  header('location:../');
}



?>



<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">OrderList</h1>
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


        <div class="card card-primary card-outline">
          <div class="card-header">
            <h5 class="m-0">Orders</h5>
          </div>
          <div class="card-body">

            <table class="table table-striped table-hover " id="table_orderlist">
              <thead>
                <tr>

                  <td>N.0</td>
                  <td>Invoice ID</td>
                  <td>Order Date</td>
                  <td>Total</td>
                  <td>Paid</td>
                  <td>Due</td>
                  <td>Payment Type</td>

                  <td>ActionIcons</td>

                </tr>

              </thead>


              <tbody>

                <?php
                $aus = $_SESSION['aus'];
                $change = query("SELECT * from tbl_change where aus='$aus'");
                confirm($change);
                $row_exchange = $change->fetch_object();
                $exchange = $row_exchange->exchange;
                $usd_or_real = $row_exchange->usd_or_real;
                $select = query("SELECT * from tbl_invoice where aus='$aus' order by invoice_id DESC");
                confirm($select);
                $no = 1;
                $total_orig = 0;
                $total_origg = 0;
                $totalf = 0;
                while ($row = $select->fetch_object()) {

                  if ($usd_or_real == "usd") {
                    $USD_usd = "$";
                    $totall = $row->total;
                    $total = number_format($totall, 2);
                  } else {
                    $USD_usd = "៛";
                    $totall = $row->total * $exchange;
                    $total = number_format($totall);
                  }
                  $invoice_id = $row->invoice_id;
                  $selectdetails = query("SELECT * from tbl_invoice_details where invoice_id='$invoice_id' ");
                  if ($rowr = $selectdetails->fetch_object()) {
                    $product_prie = show_prieori($rowr->product_id);
                    $product_priee = show_prieorii($rowr->product_id);
                    $qtyy = $rowr->qty;

                    $total_ori = $product_prie * $qtyy;
                    $total_orii = $product_priee * $qtyy;
                    $class='';
                  } else {
                    $product_prie = 0;
                    $product_priee = 0;
                    $qtyy = 0;
                    $total_ori = 0;
                    $total_orii = 0;
                    $class ='badge-danger';
                    {
                      # code...
                    }
                  }

                  echo '
                      <tr class="'.$class.'">      
                      <td>' . $no . '</td>
                      <td>' . $row->receipt_id . '</td>
                      <td>' . $row->order_date . '</td>
                      <td>' . $total . $USD_usd . '</td>
                      <td>' . $row->paid . '</td>
                      <td>' . $row->due . '</td>';
                  if ($row->payment_type == "Cash") {
                    echo '<td><span class="badge badge-warning">' . $row->payment_type . '</td></span></td>';
                  } else {
                    echo '<td><span class="badge badge-success">' . $row->payment_type . '</td></span></td>';
                  }

                  echo '
                    <td>
                    <div class="btn-group">
                    <a href="showReceipt?id=' . $row->invoice_id . '" class="btn btn-warning " role="button" ><span class="fa fa-print" style="color:#ffffff" data-toggle="tooltip" title="Print Bill"></span></a>
                    <a href="itemt?editorderpos&id=' . $row->invoice_id . '" class="btn btn-info " role="button"><span class="fa fa-edit" style="color:#ffffff" data-toggle="tooltip" title="Edit Order"></span></a>
                  
                    ' . show_delete($row->invoice_id) . '
                   
                    </div>
                    </td>
                    </tr>';
                  $no++;
                  $totalf += $totall;
                  $total_orig += $total_ori;
                  $total_origg += $total_orii;

                  $ff = $total_origg - $total_orig;
                }

                echo '<tr>
                     <td></td>
                     <td></td>

                     <th>សរុប</th>
                     <td><span class="badge badgeth badge-danger">' . $totalf . '</span></td>
                     <td><span class="badge badgeth badge-danger">' . $total_orig . '</span></td>
                     <td><span class="badge badgeth badge-success">' . $total_origg . '</span></td>
                     <td><span class="badge badgeth badge-success">' . $ff . '</span></td>
                     <td></td>
                     <td></td>
                    </tr>';

                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <!-- /.col-md-6 -->
    </div>
    <!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content -->