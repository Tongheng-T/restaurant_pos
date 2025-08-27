<?php

if ($_SESSION['useremail'] == ""  or $_SESSION['role'] == "User") {

  header('location:../');
}


display_message();
?>



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
            <form method="get" class="form-inline mb-3">
              <input type="hidden" name="stock_list" >
              <label for="date" class="mr-2">ជ្រើសរើសថ្ងៃ:</label>
              <input type="date" name="date" id="date" class="form-control mr-2"
                value="<?php echo $_GET['date'] ?? date('Y-m-d'); ?>">
              <button type="submit" class="btn btn-primary">Filter</button>
            </form>
            <table class="table table-striped table-hover " id="table_product">
              <thead>
                <tr>
                  <td>No</td>
                  <td>Id</td>
                  <td>Product</td>
                  <td>Stock</td>
                  <td>PurchasePrice</td>
                  <td>Total</td>
                  <td>Image</td>
                  <td>ActionIcons</td>

                </tr>

              </thead>


              <tbody>

                <?php stock_list(); ?>

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