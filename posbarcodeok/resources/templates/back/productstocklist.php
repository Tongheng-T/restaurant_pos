<?php

if ($_SESSION['useremail'] == ""  or $_SESSION['role'] == "User") {

  header('location:../');
}


display_message();
?>

<style>
  .card{
    border: 1px solid rgb(0 0 0);
    border-radius: .25rem;
    margin: 2px;
  }
</style>

<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">

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
            <h5 class="m-0">Product List :</h5>
          </div>
          <div class="card-body">
            <div class="row">
              <?php
              $aus = $_SESSION['aus'];
              $select = query("SELECT * from tbl_product where aus=$aus order by pid ASC");
              confirm($select);

              $change = query("SELECT * from tbl_change where aus='$aus'");
              confirm($change);
              $row_exchange = $change->fetch_object();
              $exchange = $row_exchange->exchange;
              $usd_or_real = $row_exchange->usd_or_real;

              while ($row = $select->fetch_object()) {
                if ($usd_or_real == "usd") {
                  $USD_usd = "$";
                  // $m_price = number_format($row->m_price, 2) . $USD_usd;
                  $saleprice = number_format($row->saleprice, 2) . $USD_usd;
                  $purchaseprice = number_format($row->purchaseprice, 2) . $USD_usd;
                } else {
                  $USD_usd = "៛";
                  // $m_pricee = $row->m_price * $exchange;
                  $salepricee = $row->saleprice * $exchange;
                  $purchaseprice = $row->purchaseprice * $exchange;
                  // $m_price = number_format($m_pricee) . $USD_usd;
                  $saleprice = number_format($salepricee) . $USD_usd;
                  $purchaseprice = number_format($purchaseprice) . $USD_usd;
                }




              ?>

                <div class="card" style="width: 18rem;">
                  <img width="150px" class="card" src="<?php echo '../productimages/' . $row->image ?>"  alt="Card image cap">
                  <div class="card-body">
                    <h5 class="card-title"> <?php echo $row->product ?></h5>
                    <h1 class="card-text">stock: <?php echo $row->stock ?></h1>
                    <h1 class="card-text">price: $<?php echo $row->saleprice ?></h1>
                  
                  </div>
                </div>

              <?php  } ?>

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