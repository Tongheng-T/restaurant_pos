<?php

if ($_SESSION['useremail'] == "" or $_SESSION['role'] == "") {

  header('location:../');
}

$aus = $_SESSION['aus'];


if (isset($_POST['btnsaveorder'])) {

  $orderdate = date('Y-m-d');
  $subtotall = $_POST['txtsubtotal'];
  $discount = $_POST['txtdiscount'];
  $discountp = $_POST['txtdiscountp'];

  $totall = $_POST['txttotal'];
  $payment_type = $_POST['rb'];
  $due = $_POST['txtdue'];
  $paid = $_POST['txtpaid'];


  /////

  $arr_pid = $_POST['pid_arr'];

  $arr_name = $_POST['product_arr'];

  $arr_qty = $_POST['quantity_arr'];
  $arr_pricee = $_POST['price_c_arr'];
  $arr_totall = $_POST['saleprice_arr'];
  $saler_name = $_SESSION['username'];
  $saler_id = $_SESSION['userid'];

  $change = query("SELECT * from tbl_change where aus='$aus'");
  confirm($change);
  $row_exchange = $change->fetch_object();
  $exchange = $row_exchange->exchange;
  $usd_or_real = $row_exchange->usd_or_real;
  if ($usd_or_real == "usd") {
    $subtotal = $subtotall;
    $total = $totall;
    $discountKHRk = $discount;
    // $arr_price = $arr_pricee;
    // $arr_total = $arr_totall;
    $exchangee = 1;
  } else {
    $subtotal = $subtotall / $exchange;
    $total = $totall / $exchange;
    $discountKHRk = $discount / $exchange;
    // $arr_price = $arr_pricee / $exchange;
    // $arr_total = $arr_totall / $exchange;
    $exchangee = $exchange;
  }

  $tbl_invoice = query("SELECT MAX(receipt_id) as receipt_num from tbl_invoice where aus='$aus'");
  confirm($tbl_invoice);
  $row = $tbl_invoice->fetch_object();
  $receipt_id = $row->receipt_num + 1;

  $insert = query("INSERT into tbl_invoice (receipt_id,subtotal,discount,discountp,total,payment_type,due,paid,saler_id,saler_name,aus) values('{$receipt_id}','{$subtotal}','{$discountKHRk}','{$discountp}','{$total}','{$payment_type}','{$due}','{$paid}','{$saler_id}','{$saler_name}','{$aus}') ");
  confirm($insert);
  $invoice_id = last_id();

  if ($invoice_id != null) {
    for ($i = 0; $i < count($arr_pid); $i++) {
      $arr_price = $arr_pricee[$i] / $exchangee;
      $arr_total = $arr_totall[$i] / $exchangee;


      $insert = query("INSERT into tbl_invoice_details (invoice_id,product_id,product_name,qty,rate,saleprice,saler_id,aus) values ('{$invoice_id}','{$arr_pid[$i]}','{$arr_name[$i]}','{$arr_qty[$i]}','{$arr_price}','{$arr_total}','{$saler_id}','{$aus}')");
      confirm($insert);
    }

    redirect('showReceipt?id=' . $invoice_id . '');
  } //1st if end



  //var_dump($arr_total);

}


ob_end_flush();



$select = query("SELECT * from tbl_taxdis where aus = '$aus'");
confirm($select);
$row = $select->fetch_object();

$change = query("SELECT * from tbl_change where aus='$aus' ");
confirm($change);
$row_exchange = $change->fetch_object();
$exchange = $row_exchange->exchange;
$usd_or_real = $row_exchange->usd_or_real;

if ($usd_or_real == "usd") {
  $dddd = '$<span id="txttotal">0</span>';
  $USD_usd = " $";
  $USD_txt = "USD";
  $Change_rea = "៛";
  $Change = "KHR";
} else {
  $dddd = '<span id="txttotal">0</span>៛';
  $USD_usd = " ៛";
  $Change = "USD";
  $Change_rea = "$";
  $USD_txt = "KHR";
}
$_SESSION['change'] = ' ';
?>


<style type="text/css">
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





<!-- Main content -->
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">


        <div class="card card-primary card-outline">
          <div class="card-header">
            <h4 class="m-0">Cashier</h4>
            <!-- <img width="50px" src="../ui/logo/cashier.svg" /> -->
          </div>



          <div class="card-body">


            <div class="row">

              <div class="col-md-5">

                <!-- <div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-barcode"></i></span>
                  </div>
                  <input type="text" class="form-control" placeholder="Scan Barcode" autocomplete="off" name="txtbarcode" id="txtbarcode_id">
                </div> -->


                <form action="" method="post" name="">

                  </br>
                  <div class="tableFixHead">


                    <table id="producttable" class="table table-bordered table-hover">
                      <thead>
                        <tr>
                          <th>Product</th>
                          <th>price </th>
                          <th>QTY </th>
                          <th>Total </th>
                          <th>Del </th>
                        </tr>

                      </thead>


                      <tbody class="details" id="itemtable">
                        <tr data-widget="expandable-table" aria-expanded="false">

                        </tr>
                      </tbody>
                    </table>

                  </div>
                  <hr>

                  <h3>Total Amount: <?php echo $dddd ?></h3>
                  <div><button type="button" class="btn btn-success btn-block btn-payment" data-toggle="modal"
                      data-target="#exampleModal">Payment</button></div>



              </div>


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
                    <a class="nav-item nav-link" data-id="all" data-toggle="tab">ទាំងអស់ </a>
                  </div>
                </nav>
                <div id="list-menu" class="row mt-2"></div>

              </div>


              <!-- Modal -->
              <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
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
                          <span class="input-group-text"><i class="fa"
                              style="font-size: 24px;"><?php echo $USD_usd ?></i></span>
                        </div>
                      </div>


                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">DISCOUNT(%)</span>
                        </div>
                        <input type="text" class="form-control" name="txtdiscountp" id="txtdiscount_p"
                          value=" <?php echo $row->discount ?> " readonly>
                        <div class="input-group-append">
                          <span class="input-group-text">%</span>
                        </div>
                      </div>


                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">DISCOUNT(<?php echo $USD_txt ?>)</span>
                        </div>
                        <input type="text" class="form-control" name="txtdiscount" id="txtdiscount_n" readonly>
                        <div class="input-group-append">
                          <span class="input-group-text"><i class="fa"
                              style="font-size: 24px;"><?php echo $USD_usd ?></i></span>
                        </div>
                      </div>

                      <hr style="height:2px; border-width:0; color:black; background-color:black;">

                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">TOTAL(<?php echo $USD_txt ?>)</span>
                        </div>
                        <input type="text" class="form-control form-control-lg total" name="txttotal" id="txttotall"
                          readonly>
                        <div class="input-group-append">
                          <span class="input-group-text"><i class="fa"
                              style="font-size: 24px;"><?php echo $USD_usd ?></i></span>
                        </div>
                      </div>

                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">TOTAL(<?php echo $Change ?>)</span>
                        </div>
                        <input type="text" class="form-control form-control-lg total" name="txttotalkhr"
                          id="txttotall_khr" readonly>
                        <div class="input-group-append">
                          <span class="input-group-text"><i class="fa"
                              style="font-size: 24px;"><?php echo $Change_rea ?></i></span>
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
                          QR <i class="fas fa-qrcode"></i>
                        </label>
                      </div>
                      <!-- <div class="icheck-danger d-inline">
                          <input type="radio" name="rb" value="Check" id="radioSuccess3">
                          <label for="radioSuccess3">
                            CHECK
                          </label>
                        </div> -->
                      <hr style="height:2px; border-width:0; color:black; background-color:black;">


                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">Change(<?php echo $USD_txt ?>)</span>
                        </div>
                        <input type="text" class="form-control" name="txtdue" id="txtdue" readonly>
                        <div class="input-group-append">
                          <span class="input-group-text"> <i class="fa"
                              style="font-size: 24px;"><?php echo $USD_usd ?></i></span>
                        </div>
                      </div>

                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">Change(<?php echo $Change ?>)</span>
                        </div>
                        <input type="text" class="form-control" name="txtduekh" id="txtduekh" readonly>
                        <div class="input-group-append">
                          <span class="input-group-text"> <i class="fa"
                              style="font-size: 24px;"><?php echo $Change_rea ?></i></span>
                        </div>
                      </div>

                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">Paid Amount(<?php echo $USD_txt ?>)</span>
                        </div>
                        <input type="text" class="form-control" name="txtpaid" id="txtpaid">
                        <div class="input-group-append">
                          <span class="input-group-text"> <i class="fa"
                              style="font-size: 24px;"><?php echo $USD_usd ?></i></span>
                        </div>
                      </div>

                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary btn-save-payment" name="btnsaveorder" disabled>Save
                        Payment</button>
                    </div>
                  </div>
                </div>




              </div>
            </div>



          </div>


        </div>

        </form>

      </div>
      <!-- /.col-md-6 -->
    </div>
    <!-- /.row -->
  </div><!-- /.content-fluid -->
</div>