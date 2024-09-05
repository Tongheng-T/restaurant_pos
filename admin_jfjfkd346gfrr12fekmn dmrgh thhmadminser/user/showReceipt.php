<?php require_once("../resources/config.php"); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Receipt - SaleID : <?php show_customer_name(); ?></title>
  <link rel='shortcut icon' href="../ui/logo/256.ico" type="image/x-icon">
  <link rel="icon" href="../ui/logo/32.ico" sizes="32x32">
  <link rel="icon" href="../ui/logo/48.ico" sizes="48x48">
  <link rel="icon" href="../ui/logo/96.ico" sizes="96x96">
  <link rel="icon" href="../ui/logo/256.ico" sizes="144x144">
  <link type="text/css" rel="stylesheet" href="../dist/css/receiptt.css" media="all">
  <link type="text/css" rel="stylesheet" href="../dist/css/no-printt.css" media="print">
</head>

<body>
  <?php
  $id = $_GET['id'];
  $select = query("SELECT * from tbl_invoice where invoice_id =$id");
  confirm($select);
  $row = $select->fetch_object();
  $dd = $row->order_date;

  function show_customer_name()
  {
    $id = $_GET['id'];
    $select = query("select * from tbl_invoice where invoice_id = $id");
    confirm($select);
    $row = $select->fetch_object();
    $order_date = $row->order_date;
    $invoice_id = $row->invoice_id;
    echo  $invoice_id . ' _ ' . $order_date;
  }

  ?>

  <div id="wrapper">
    <div id="receipt-header">
      <br>
      <div class="logo">
        <img src="../ui/logo/logoo.png" alt="Logo" class="img-fluid">
      </div>
      <h3 id="resturant-name">Tongheng Coffee</h3>
      <p>Address: Dambea - Tboung Khmum Province</p>
      <p>ផ្លូវលេខ 73</p>
      <p>Tel: 0718989726</p>
      <p>Reference Receipt: <strong><?php echo $id ?></strong></p>
      <p>Date: <strong><?php echo date('d-m-Y', strtotime($dd)) ?></strong></p>
    </div>
    <div id="receipt-body">
      <table class="tb-sale-detail">
        <thead>
          <tr>
            <th>#</th>
            <th>Product</th>
            <th>Qty</th>
            <th>Price</th>
            <th>Total</th>
          </tr>
        </thead>
        <tbody>


          <?php
          $select = query("SELECT * from tbl_invoice_details where invoice_id =$id");
          confirm($select);
          $countqty = 0;
          $no = 1;
          while ($item = $select->fetch_object()) {

            echo '
                <tr>
                <td width="30"> ' . $no . '</td>
                <td width="190"> ' . $item->product_name . '</td>
                <td width="40"> ' . $item->qty . '</td>
                <td width="55"> ' . $item->rate . '</td>
                <td width="65"> ' . $item->rate * $item->qty . ' <span >&#x17DB </span></td>
               </tr>
                ';
            $no++;
            $countqty += $item->qty;
          }
          $discount_rs = $row->discount / 100;
          $discount_rs = $discount_rs * $row->subtotal;

          ?>
        </tbody>
      </table>
      <table class="tb-sale-total">
        <tbody>
          <tr>
            <td>Total Quantity</td>
            <td><?php echo $countqty ?></td>
            <td>Total</td>
            <td><?php echo number_format($row->total) ?> <span class="bb">&#x17DB </span></td>
          </tr>
          <tr>
            <td colspan="2">Payment Type</td>
            <td colspan="2"><?php echo $row->payment_type ?></td>
          </tr>
          <tr>
            <td colspan="2">Discount %</td>
            <td colspan="2"><?php echo number_format($row->discount) ?> % </span></td>
          </tr>
          <tr>
            <td colspan="2">Discount (KHR)</td>
            <td colspan="2"><?php echo number_format($discount_rs) ?> <span class="bb">&#x17DB </span></td>
          </tr>
          <tr>
            <td colspan="2">Paid Amount</td>
            <td colspan="2"><?php echo $row->paid ?> <span class="bb">&#x17DB </span></td>
          </tr>
          <tr>
            <td colspan="2">Change</td>
            <td colspan="2"><?php echo $row->due ?> <span class="bb">&#x17DB </span></td>
          </tr>
        </tbody>
      </table>
    </div>
    <div id="receipt-footer">
      <p>Thank You!!!</p>
    </div>
    <div id="buttons">
      <a href="/user/itemt?pos">
        <button class="btn btn-back">
          Back to Cashier
        </button>
      </a>
      <button class="btn btn-print" type="button" onclick="window.print(); return false;">
        Print
      </button>
    </div>
  </div>
</body>

</html>