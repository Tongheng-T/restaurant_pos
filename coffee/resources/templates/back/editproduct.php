<?php

if ($_SESSION['useremail'] == ""  or $_SESSION['role'] == "User") {

  header('location:../');
}




edit_product();

$id = $_GET['id'];

$select = query("SELECT * from tbl_product where pid=$id");
confirm($select);

$row = $select->fetch_assoc();

$id_db = $row['pid'];

$product_db = $row['product'];
$category_id_db = $row['category_id'];
$description_db = $row['description'];


$saleprice_db = $row['saleprice'];
$image_db = $row['image'];

$change = query("SELECT * from tbl_change where aus = '$aus'");
confirm($change);
$row_exchange = $change->fetch_object();
$exchange = $row_exchange->exchange;
$usd_or_real = $row_exchange->usd_or_real;

if ($usd_or_real == "usd") {
  $USD_usd = "$";

  $saleprice = number_format($saleprice_db, 2) . $USD_usd;
} else {
  $USD_usd = "៛";

  $salepricee = $saleprice_db * $exchange;

  $saleprice = number_format($salepricee) . $USD_usd;
}
?>



<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <!-- <h1 class="m-0">Admin Dashboard</h1> -->
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


        <div class="card card-success card-outline">
          <div class="card-header">
            <h5 class="m-0">Edit Product</h5>
          </div>

          <form action="" method="post" name="formeditproduct" enctype="multipart/form-data">
            <div class="card-body">
              <div class="row">
                <div class="col-md-6">

                  <div class="form-group">
                    <label>Product Name</label>
                    <input type="text" class="form-control" value="<?php echo $product_db; ?>" placeholder="Enter Name" name="txtproductname" autocomplete="off" required>
                  </div>

                  <div class="form-group">
                    <label>Category</label>
                    <select class="form-control" name="txtselect_option" required>
                      <option value="" disabled selected>Select Category</option>

                      <?php
                      $aus = $_SESSION['aus'];
                      $select = query("SELECT * from tbl_category where aus='$aus'");
                      confirm($select);

                      while ($row = $select->fetch_assoc()) {
                        extract($row);

                      ?>
                        <option value="<?php echo $row['catid'] ?>" <?php if ($row['catid'] == $category_id_db) { ?> selected="selected" <?php } ?>><?php echo $row['category']; ?></option>

                      <?php

                      }

                      ?>
                    </select>
                  </div>


                  <div class="form-group">
                    <label>Description</label>
                    <textarea class="form-control" placeholder="Enter Description" name="txtdescription" rows="4" ><?php echo $description_db; ?> </textarea>
                  </div>

                </div>


                <div class="col-md-6">

                  <div class="form-group">
                    <label>Sale Price</label>
                    <input type="number" min="1" step="any" class="form-control" value="<?php echo $saleprice_db; ?>" placeholder="Enter Stock" name="txtsaleprice" autocomplete="off" required>
                  </div>


                  <div class="form-group">
                    <label>Product image</label><br />
                    <image src="../productimages/<?php echo $image_db; ?>" class="img-rounded" width="50px" height="50px/">


                      <input type="file" class="input-group" name="myfile">
                      <p>Upload image</p>
                  </div>

                </div>


              </div>

            </div>

            <div class="card-footer">
              <div class="text-center">
                <button type="submit" class="btn btn-success" name="btneditproduct">Update Product</button>
              </div>
            </div>

          </form>


        </div>

      </div>
      <!-- /.col-md-6 -->
    </div>
    <!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content -->