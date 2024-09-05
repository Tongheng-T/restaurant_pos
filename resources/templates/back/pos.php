<?php

if ($_SESSION['useremail'] == ""  or $_SESSION['role'] == "") {

  header('location:../');
}





ob_end_flush();
// $aus = $_SESSION['aus'];where aus='$aus'
$aus = $_SESSION['aus'];

$select = query("SELECT * from tbl_taxdis where aus='$aus'");
confirm($select);
$row = $select->fetch_object();

$change = query("SELECT * from tbl_change where aus='$aus'");
confirm($change);
$row_exchange = $change->fetch_object();
$exchange = $row_exchange->exchange;
$usd_or_real = $row_exchange->usd_or_real;


if ($usd_or_real == "usd") {

  $USD_usd = " $";
  $USD_txt = "USD";
  $Change_rea = "៛";
  $Change = "KHR";
} else {

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

          <div class="card-body">
            <div class="row" id="table-detail"></div>

            <div class="row">
              <div class="col-md-5">
                <button class="btn btn-primary btn-block" id="btn-show-tables">View All Tables</button>
                <div id="selected-table"></div>
                <div id="order-detail">

                </div>
              </div>



              <div class="col-md-7">
                <nav>
                  <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <?php
                    $aus = $_SESSION['aus'];
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
                        <input type="text" class="form-control" name="txtdiscount" id="txtdiscount_p" value=" <?php echo $row->discount ?> " readonly>
                        <div class="input-group-append">
                          <span class="input-group-text">%</span>
                        </div>
                      </div>


                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">DISCOUNT(<?php echo $USD_txt ?>)</span>
                        </div>
                        <input type="text" class="form-control" id="txtdiscount_n" readonly>
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
                        <input type="text" class="form-control form-control-lg total" name="txttotal" id="txttotall_khr" readonly>
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
                          QR
                        </label>
                      </div>

                      <hr style="height:2px; border-width:0; color:black; background-color:black;">


                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">Change(<?php echo $USD_txt ?>)</span>
                        </div>
                        <input type="text" class="form-control" name="txtdue" id="txtdue" readonly>
                        <div class="input-group-append">
                          <span class="input-group-text"> <i class="fa" style="font-size: 24px;">៛</i></span>
                        </div>
                      </div>


                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">Paid Amount(<?php echo $USD_txt ?>)</span>
                        </div>
                        <input type="text" class="form-control" name="txtpaid" id="txtpaid">
                        <div class="input-group-append">
                          <span class="input-group-text"> <i class="fa" style="font-size: 24px;">៛</i></span>
                        </div>
                      </div>

                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary btn-save-payment" name="btnsaveorder" disabled>Save Payment</button>
                      
                    </div>
                  </div>
                </div>




              </div>
              <!-- Modal -->
              <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content" id="receipt">
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
                        <input type="text" class="form-control" name="txtdiscount" id="txtdiscount_p" value=" <?php echo $row->discount ?> " readonly>
                        <div class="input-group-append">
                          <span class="input-group-text">%</span>
                        </div>
                      </div>


                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">DISCOUNT(<?php echo $USD_txt ?>)</span>
                        </div>
                        <input type="text" class="form-control" id="txtdiscount_n" readonly>
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
                        <input type="text" class="form-control form-control-lg total" name="txttotal" id="txttotall_khr" readonly>
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
                          QR
                        </label>
                      </div>

                      <hr style="height:2px; border-width:0; color:black; background-color:black;">


                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">Change(<?php echo $USD_txt ?>)</span>
                        </div>
                        <input type="text" class="form-control" name="txtdue" id="txtdue" readonly>
                        <div class="input-group-append">
                          <span class="input-group-text"> <i class="fa" style="font-size: 24px;">៛</i></span>
                        </div>
                      </div>


                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">Paid Amount(<?php echo $USD_txt ?>)</span>
                        </div>
                        <input type="text" class="form-control" name="txtpaid" id="txtpaid">
                        <div class="input-group-append">
                          <span class="input-group-text"> <i class="fa" style="font-size: 24px;">៛</i></span>
                        </div>
                      </div>

                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary btn-save-payment" name="btnsaveorder" disabled>Save Payment</button>
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

