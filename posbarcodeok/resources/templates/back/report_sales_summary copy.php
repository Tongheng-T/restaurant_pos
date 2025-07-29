<?php


if ($_SESSION['useremail'] == ""  or $_SESSION['role'] == "User") {

    header('location:../');
}



$aus = $_SESSION['aus'];

?>

<!-- ChartJS -->
<script src="../plugins/chart.js/Chart.min.js"></script>
<!-- 
Content Header (Page header) -->

<!-- /.content -->

<!-- Filter Form -->
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">Sales Summary Report</h3>
    </div>
    <div class="card-body">
        <form method="get" class="form-inline">
            <div class="form-group mr-3">
                <label for="start_date" class="mr-2">ចាប់ពីថ្ងៃ:</label>
                <input type="date" name="start_date" class="form-control" value="<?php echo $_GET['start_date'] ?? ''; ?>">
            </div>
            <div class="form-group mr-3">
                <label for="end_date" class="mr-2">ដល់ថ្ងៃ:</label>
                <input type="date" name="end_date" class="form-control" value="<?php echo $_GET['end_date'] ?? ''; ?>">
            </div>
            <div class="form-group mr-3">
                <label for="saler_id" class="mr-2">អ្នកលក់:</label>
                <select name="saler_id" class="form-control">
                    <option value="">-- ទាំងអស់ --</option>
                    <?php
                    $salers = query("SELECT DISTINCT saler_id, saler_name FROM tbl_invoice WHERE aus='$aus'");
                    while ($s = $salers->fetch_object()) {
                        $selected = ($_GET['saler_id'] ?? '') == $s->saler_id ? 'selected' : '';
                        echo "<option value='{$s->saler_id}' $selected>$s->saler_name</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary ml-2">🔍 Filter</button>
        </form>
    </div>
</div>

<!-- Result Summary -->
<?php
// Handle filters
$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';
$saler_id = $_GET['saler_id'] ?? '';

$where = "WHERE aus = '$aus'";
if ($start_date) $where .= " AND DATE(order_date) >= '$start_date'";
if ($end_date) $where .= " AND DATE(order_date) <= '$end_date'";
if ($saler_id) $where .= " AND saler_id = '$saler_id'";

// Main query to summarize
$result = query("SELECT 
        SUM(d.qty * d.original_price) AS total_cost,
        SUM(d.qty * d.saleprice) AS total_sale,
        SUM((d.saleprice - d.original_price) * d.qty) AS total_profit
    FROM tbl_invoice_details d
    $where
");

$row = $result->fetch_assoc();
?>

<div class="row">
    <div class="col-md-4">
        <div class="info-box bg-info">
            <span class="info-box-icon"><i class="fas fa-cubes"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">ចំនួនលក់សរុប</span>
                <span class="info-box-number">$ <?php echo number_format($row['total_sale'], 2); ?></span>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="info-box bg-warning">
            <span class="info-box-icon"><i class="fas fa-coins"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">ចំណាយសរុប</span>
                <span class="info-box-number">$ <?php echo number_format($row['total_cost'], 2); ?></span>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="info-box bg-success">
            <span class="info-box-icon"><i class="fas fa-chart-line"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">ប្រាក់ចំណេញសរុប</span>
                <span class="info-box-number">$ <?php echo number_format($row['total_profit'], 2); ?></span>
            </div>
        </div>
    </div>
</div>
<table class="table table-bordered table-striped">
    <thead class="thead-dark">
        <tr>
            <th>ល.រ</th>
            <th>ឈ្មោះទំនិញ</th>
            <th>ចំនួនលក់សរុប</th>
            <th>តម្លៃនាំចូល</th>
            <th>តម្លៃលក់ចេញ</th>
            <th>ចំណាយសរុប</th>
            <th>លក់សរុប</th>
            <th>ចំណេញសរុប</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i = 1;
        $query = query("SELECT 
                d.product_name,
                SUM(d.qty) AS total_qty_sold,
                d.original_price AS purchase_price,
                d.saleprice AS selling_price,
                SUM(d.qty * d.original_price) AS total_cost,
                SUM(d.qty * d.saleprice) AS total_sale,
                SUM((d.saleprice - d.original_price) * d.qty) AS total_profit
            FROM tbl_invoice_details d
            JOIN tbl_invoice i ON i.invoice_id = d.invoice_id
            WHERE i.aus = '{$aus}'
            GROUP BY d.product_id
            ORDER BY total_qty_sold DESC
        ");
        while ($row = $query->fetch_object()) {
            echo "<tr>";
            echo "<td>{$i}</td>";
            echo "<td>{$row->product_name}</td>";
            echo "<td>{$row->total_qty_sold}</td>";
            echo "<td>$ " . number_format($row->purchase_price, 2) . "</td>";
            echo "<td>$ " . number_format($row->selling_price, 2) . "</td>";
            echo "<td>$ " . number_format($row->total_cost, 2) . "</td>";
            echo "<td>$ " . number_format($row->total_sale, 2) . "</td>";
            echo "<td style='color:green;'>$ " . number_format($row->total_profit, 2) . "</td>";
            echo "</tr>";
            $i++;
        }
        ?>
    </tbody>
</table>
