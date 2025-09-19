<?php


if ($_SESSION['useremail'] == "" or $_SESSION['role'] == "") {

  header('location:../');
}

display_message();
$aus = $_SESSION['aus'];
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

            <form method="get" class="form-inline mb-3">
              <input type="hidden" name="orderlist">
              <label for="date" class="mr-2">ជ្រើសរើសថ្ងៃ:</label>
              <input type="text" id="date" name="date" class="form-control"
                value="<?php echo $_GET['date'] ?? date('d-m-Y'); ?>">

              <button type="submit" class="btn btn-primary">Filter</button>
            </form>
          </div>
          <?php
          // ទាញថ្ងៃដែលមានទិន្នន័យ
          $dates = query("SELECT DISTINCT DATE(updated_at) as d ,aus
                FROM tbl_invoice  WHERE aus = '$aus' ORDER BY d DESC");
          $available_dates = [];
          while ($row = $dates->fetch_assoc()) {
            $available_dates[] = date("d-m-Y", strtotime($row['d']));
          }

          ?>
          <script>
            var availableDates = <?= json_encode($available_dates) ?>;

            flatpickr("#date", {
              dateFormat: "d-m-Y",
              onDayCreate: function (dObj, dStr, fp, dayElem) {
                let date = fp.formatDate(dayElem.dateObj, "d-m-Y");
                if (availableDates.includes(date)) {
                  dayElem.classList.add("has-data");
                }
              }
            });

          </script>
          <div class="card-body">
            <div style="overflow-x:auto;">
              <table class="table table-striped table-hover " id="table_orderlist">
                <thead>
                  <tr>

                    <td>N.0</td>
                    <td>Receipt ID</td>
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
                  $selected_date = $_GET['date'] ?? null;
                  // Get stock list (JOIN with product)
                  if ($selected_date) {
                    $selected_datee = date("Y-m-d", strtotime($selected_date));
                    $select = query("SELECT * from tbl_invoice where updated_at='$selected_datee' AND aus='" . intval($aus) . "' ORDER BY invoice_id DESC");
                    confirm($select);

                  } else {
                    $select = query("SELECT * from tbl_invoice where aus='" . intval($aus) . "' ORDER BY invoice_id DESC");
                    confirm($select);

                  }


                  $change = query("SELECT * from tbl_change where aus='$aus'");
                  confirm($change);
                  $row_exchange = $change->fetch_object();
                  $exchange = $row_exchange->exchange;
                  $usd_or_real = $row_exchange->usd_or_real ?? "usd";

                  $no = 1;
                  $grand_total = 0; // បូកសរុប
                  while ($row = $select->fetch_object()) {
                    $defaultt = '';
                    if ($usd_or_real == "usd") {
                      $currency = "$";
                      $totall = $row->total;
                      $total = number_format($totall, 2);
                    } else {
                      $currency = "៛";
                      $totall = $row->total * $exchange;
                      $total = number_format($totall);
                    }

                    $grand_total += $totall; // បូកចូល
                    if ($row->paid <= 0) {
                      $defaultt = 'defaultt';
                    }

                    echo '
                  <tr>

                  <td>' . $no . '</td>
                  <td>' . htmlspecialchars($row->receipt_id) . '</td>
                  <td>' . date("d-m-Y H:i:s", strtotime($row->order_date)) . '</td>
                  <td>' . $total . ' ' . $currency . '</td>
                  <td>' . $row->paid . '</td>
                  <td>' . $row->due . '</td>';
                    if ($row->payment_type == "Cash") {
                      echo '<td><span class="badge badge-warning">' . $row->payment_type . '</span></td>';
                    } else {
                      echo '<td><span class="badge badge-success">' . $row->payment_type . '</span></td>';
                    }


                    echo '
                    <td>
                    <div class="btn-group">
                     <a href="showReceipt?id=' . $row->invoice_id . '" class="btn btn-info ' . $defaultt . '" role="button">
                         <span class="fa fa-print" style="color:#ffffff" data-toggle="tooltip" title="Print Bill"></span>
                     </a>
                     ' . show_delete($row->invoice_id) . '
                    </div>
                    </td>
                    </tr>';
                    $no++;
                  }
                  // target="_blank"
                  // បង្ហាញ Total សរុប
                  echo '
                     <tr style="font-weight:bold; background:#f0f0f0;">
                     <td></td><td></td>
                       <td class="text-right">Total សរុប</td>
                       <td>' . number_format($grand_total, ($usd_or_real == "usd" ? 2 : 0)) . ' ' . $currency . '</td>
                       <td></td>
                       <td></td>
                       <td></td>
                       <td></td>
                     </tr>';
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <!-- /.col-md-6 -->
    </div>
    <!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content -->