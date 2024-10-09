<?php


if ($_SESSION['useremail'] == ""  or $_SESSION['role'] == "") {

  header('location:../');
}







$id = $_GET["id"];
$aus = $_SESSION['aus'];


$select = query("SELECT * from tbl_invoice where aus = '$aus' and invoice_id =$id");
confirm($select);
$row = $select->fetch_assoc();

$order_date = date('Y-m-d', strtotime($row['order_date']));
$subtotal     = $row['subtotal'];

$discount     = $row['discount'];
$total        = $row['total'];
$paid         = $row['paid'];
$due          = $row['due'];
$payment_type = $row['payment_type'];


$select = query("SELECT * from tbl_invoice_details where aus = '$aus' and invoice_id=$id");
confirm($select);
$row_invoice_details = $select->fetch_all();


if (isset($_POST['btnupdateorder'])) {

  //Steps for btnupdateorder button.

  // 1) Get values from text feilds and from array in variables.

  $txt_orderdate     = date('Y-m-d');
  $txt_subtotall      = $_POST['txtsubtotal'];
  $txt_discount      = $_POST['txtdiscount'];

  $txt_totall         = $_POST['txttotal'];
  $txt_payment_type  = $_POST['rb'];
  $txt_due           = $_POST['txtdue'];
  $txt_paid          = $_POST['txtpaid'];

  /////


  $arr_pid     = $_POST['pid_arr'];

  $arr_name    = $_POST['product_arr'];

  $arr_qty     = $_POST['quantity_arr'];
  $arr_pricee   = $_POST['price_c_arr'];
  $arr_totall   = $_POST['saleprice_arr'];
  $discountp   = $_POST['txtdiscountp'];


  $change = query("SELECT * from tbl_change where aus = '$aus'");
  confirm($change);
  $row_exchange = $change->fetch_object();
  $exchange = $row_exchange->exchange;
  $usd_or_real = $row_exchange->usd_or_real;
  if ($usd_or_real == "usd") {
    $subtotal = $subtotall;
    $total = $totall;
    $discountKHRk = $txt_discount;
    $txt_total = $txt_totall;
    $txt_subtotal = $txt_subtotall;

    $exchangee = 1;
  } else {
    $subtotal = $subtotall / $exchange;
    $total = $totall / $exchange;
    $discountKHRk = $txt_discount / $exchange;
    $txt_total = $txt_totall / $exchange;
    $txt_subtotal = $txt_subtotall / $exchange;
    $exchangee = $exchange;
  }

  // 3) Write delete query for tbl_invoice_details table data where invoice_id =$id .

  $delete_invoice_details = query("DELETE from tbl_invoice_details where aus = '$aus' and invoice_id =$id");
  confirm($delete_invoice_details);


  // 4) Write update query for tbl_invoice table data.
  $saler_name = $_SESSION['userid'];
  $update_tbl_invoice = query("UPDATE tbl_invoice SET order_date='{$txt_orderdate}',subtotal='{$txt_subtotal}',discount='{$discountKHRk}',discountp='{$discountp}',total='{$txt_total}',payment_type='{$txt_payment_type}',due='{$txt_due}',paid='{$txt_paid}',edit='{$saler_name}' where invoice_id=$id");
  confirm($update_tbl_invoice);
  $invoice_id = $id;

  if ($invoice_id != null) {

    // 5) Write select query for tbl_product table to get out stock value.
    for ($i = 0; $i < count($arr_pid); $i++) {
      $arr_price = $arr_pricee[$i] / $exchangee;
      $arr_total = $arr_totall[$i] / $exchangee;

      // $selectpdt = query("SELECT * from tbl_product where aus = '$aus' and pid='" . $arr_pid[$i] . "'");
      // confirm($selectpdt);

      // 7) Write insert query for tbl_invoice_details for insert new records.

      $insert = query("INSERT into tbl_invoice_details (invoice_id,product_id,product_name,qty,rate,saleprice,order_date) values ('{$id}','{$arr_pid[$i]}','{$arr_name[$i]}','{$arr_qty[$i]}','{$arr_price}','{$arr_total}','{$txt_orderdate}')");
      confirm($insert);
    } //end for loop
    set_message(' <script>
    Swal.fire({
      icon: "success",
      title: "Order Updated Successfully"
    });
    
  </script>');
    redirect('itemt?orderlist');
  } //1st if end
  //var_dump($arr_total);

}
ob_end_flush();

$select = query("SELECT * from tbl_taxdis where aus = '$aus'");
confirm($select);
$row = $select->fetch_object();

$change = query("SELECT * from tbl_change where aus = '$aus'");
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


        <div class="card card-danger card-outline">
          <div class="card-header">
            <h5 class="m-0">Edit Order POS</h5>
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

                  <h3>Total Amount:</strong> <span id="txttotal">0</span> ៛</h3>
                  <div><button type="button" class="btn btn-success btn-block btn-payment" data-toggle="modal" data-target="#exampleModal">Payment</button></div>



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

                  </div>
                </nav>
                <div id="list-menu" class="row mt-2"></div>

              </div>


              <!-- Modal -->
              <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                          <span class="input-group-text"><i class="fa" style="font-size: 24px;"><?php echo $USD_usd ?></i></span>
                        </div>
                      </div>


                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">DISCOUNT(%)</span>
                        </div>
                        <input type="text" class="form-control" name="txtdiscountp" id="txtdiscount_p" value=" <?php echo $row->discount ?> " readonly>
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
                          <span class="input-group-text"><i class="fa" style="font-size: 24px;"><?php echo $USD_usd ?></i></span>
                        </div>
                      </div>

                      <hr style="height:2px; border-width:0; color:black; background-color:black;">

                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">TOTAL(<?php echo $USD_txt ?>)</span>
                        </div>
                        <input type="text" class="form-control form-control-lg total" name="txttotal" id="txttotall" readonly>
                        <div class="input-group-append">
                          <span class="input-group-text"><i class="fa" style="font-size: 24px;"><?php echo $USD_usd ?></i></span>
                        </div>
                      </div>

                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">TOTAL(<?php echo $Change ?>)</span>
                        </div>
                        <input type="text" class="form-control form-control-lg total" name="txttotalkhr" id="txttotall_khr" readonly>
                        <div class="input-group-append">
                          <span class="input-group-text"><i class="fa" style="font-size: 24px;"><?php echo $Change_rea ?></i></span>
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
                          <span class="input-group-text"> <i class="fa" style="font-size: 24px;"><?php echo $USD_usd ?></i></span>
                        </div>
                      </div>

                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">Change(<?php echo $Change ?>)</span>
                        </div>
                        <input type="text" class="form-control" name="txtduekh" id="txtduekh" readonly>
                        <div class="input-group-append">
                          <span class="input-group-text"> <i class="fa" style="font-size: 24px;"><?php echo $Change_rea ?></i></span>
                        </div>
                      </div>

                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">Paid Amount(<?php echo $USD_txt ?>)</span>
                        </div>
                        <input type="text" class="form-control" name="txtpaid" id="txtpaid">
                        <div class="input-group-append">
                          <span class="input-group-text"> <i class="fa" style="font-size: 24px;"><?php echo $USD_usd ?></i></span>
                        </div>
                      </div>

                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-info" name="btnupdateorder">Update order</button>
                    </div>
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
</div><!-- /.container-fluid -->