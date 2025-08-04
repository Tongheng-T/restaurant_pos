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
<?php



$year  = isset($_GET['year']) ? $_GET['year'] : date('Y');
$month = isset($_GET['month']) ? $_GET['month'] : date('m');

// Sales Total ពី tbl_invoice
$sales_query = query("
    SELECT SUM(total) AS total_sales 
    FROM tbl_invoice 
    WHERE aus='$aus'
      AND sale_status='paid'
      AND YEAR(updated_at)='$year'
      AND MONTH(updated_at)='$month'
");
$sales_row = fetch_array($sales_query);
$total_sales = $sales_row['total_sales'] ?? 0;

// Expenses Total ពី tbl_expense
$expense_query = query("
    SELECT SUM(amount) AS total_expense 
    FROM tbl_expense
    WHERE aus='$aus'
      AND YEAR(expense_date)='$year'
      AND MONTH(expense_date)='$month'
");
$expense_row = fetch_array($expense_query);
$total_expense = $expense_row['total_expense'] ?? 0;

$change = query("SELECT * from tbl_change where aus='$aus'");
confirm($change);
$row_exchange = $change->fetch_object();
$exchange = $row_exchange->exchange;
$usd_or_real = $row_exchange->usd_or_real;

if ($usd_or_real == "usd") {
  $grand_totalg = $total_sales;
  $total_saless = number_format($grand_totalg, 2);
  $subtotalg = $total_expense;
  $total_expensee = number_format($subtotalg, 2);
  $USD_usd = "$";
  $USD_txt = "USD";
  $expen = $grand_totalg - $subtotalg;
  $profit = number_format($expen, 2);
} else {
  $grand_totalg = $total_sales * $exchange;
  $total_saless = number_format($grand_totalg);
  $subtotalg = $total_expense * $exchange;
  $total_expensee = number_format($subtotalg);
  $USD_usd = "៛";
  $USD_txt = "KHR";
  $expen = $grand_totalg - $subtotalg;
  $profit = number_format($expen);
}





?>
<div class="card shadow-lg border-0">
  <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
    <h5 class="mb-0"><i class="fas fa-chart-line"></i> របាយការណ៍ប្រចាំខែ</h5>
    <form method="GET" class="form-inline">
      <input type="hidden" name="tablereport_copy" value="">
      <input type="number" name="year" value="<?php echo $year ?>" class="form-control form-control-sm mr-2" style="width:110px">
      <input type="number" name="month" value="<?php echo $month ?>" min="1" max="12" class="form-control form-control-sm mr-2" style="width:90px">
      <button type="submit" class="btn btn-sm btn-light"><i class="fa fa-search"></i> រក</button>
    </form>
  </div>

  <div class="card-body">

    <!-- Summary -->
    <div class="row text-center mb-4">
      <div class="col-md-4">
        <div class="p-3 border rounded bg-light">
          <h6 class="text-muted">ចំណូលសរុប</h6>
          <h4 class="text-primary"><?php echo $USD_usd ?> <?php echo $total_saless ?></h4>
        </div>
      </div>
      <div class="col-md-4">
        <div class="p-3 border rounded bg-light">
          <h6 class="text-muted">ចំណាយសរុប</h6>
          <h4 class="text-danger"><?php echo $USD_usd ?> <?php echo $total_expensee ?></h4>
        </div>
      </div>
      <div class="col-md-4">
        <div class="p-3 border rounded bg-light">
          <h6 class="text-muted">ចំណេញ</h6>
          <h4 class="text-success"><?php echo $USD_usd ?> <?php echo $profit ?></h4>
        </div>
      </div>
    </div>

    <!-- Invoice Table -->
    <h6 class="mt-4 mb-2"><i class="fas fa-file-invoice-dollar text-success"></i> តារាងវិក្កយបត្រ (Invoice)</h6>
    <div class="table-responsive">
      <table class="table table-hover table-bordered">
        <thead class="bg-dark text-white">
          <tr>
            <th>#</th>
            <th>Invoice ID</th>
            <th>កាលបរិច្ឆេទ</th>
            <th>តុ</th>
            <th>ចំណូលសរុប ($ / ៛)</th>
            <th>អ្នកលក់</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $i = 1;
          $invoice_result = query("SELECT * FROM tbl_invoice WHERE aus='$aus' AND sale_status='paid' AND YEAR(updated_at)='$year' AND MONTH(updated_at)='$month' ORDER BY updated_at ASC");
          while ($row = fetch_array($invoice_result)) {
            $grand_usd = number_format($row['total'], 2, '.', ',');
            $grand_khr = number_format($row['total'] * $exchange, 0, '.', ',');
            echo "<tr>
                    <td>{$i}</td>
                    <td><span class='badge badge-success'>{$row['invoice_id']}</span></td>
                    <td>{$row['order_date']}</td>
                    <td>{$row['table_name']}</td>
                    <td class='text-right'>
                      <b class='text-primary'>{$grand_usd} $</b><br>
                      <small class='text-muted'>({$grand_khr} ៛)</small>
                    </td>
                    <td>{$row['saler_name']}</td>
                  </tr>";
            $i++;
          }
          ?>
        </tbody>
      </table>
    </div>

    <!-- Expense Table -->
    <h6 class="mt-4 mb-2"><i class="fas fa-wallet text-danger"></i> តារាងចំណាយ (Expense)</h6>
    <div class="table-responsive">
      <table class="table table-hover table-bordered">
        <thead class="bg-danger text-white">
          <tr>
            <th>#</th>
            <th>កាលបរិច្ឆេទ</th>
            <th>ចំនួន ($ / ៛)</th>
            <th>ប្រភេទ</th>
            <th>ពិពណ៌នា</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $i = 1;
          $categories = [
            'ingredient'  => 'គ្រឿងផ្សំ',
            'salary'      => 'ប្រាក់ខែ',
            'electricity' => 'អគ្គិសនី/ទឹក',
            'other'       => 'ផ្សេងៗ'
          ];
          $exp_result = query("SELECT * FROM tbl_expense WHERE aus='$aus' AND YEAR(expense_date)='$year' AND MONTH(expense_date)='$month' ORDER BY expense_date ASC");
          while ($row = fetch_array($exp_result)) {
            $amount_usd = number_format($row['amount'], 2, '.', ',');
            $amount_khr = number_format($row['amount'] * $exchange, 0, '.', ',');
            echo "<tr>
                    <td>{$i}</td>
                    <td>{$row['expense_date']}</td>
                    <td class='text-right'>
                      <b class='text-danger'>{$amount_usd} $</b><br>
                      <small class='text-muted'>({$amount_khr} ៛)</small>
                    </td>
                    <td><span class='badge badge-secondary'>{$categories[$row['expense_category']]}</span></td>
                    <td>{$row['description']}</td>
                  </tr>";
            $i++;
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
