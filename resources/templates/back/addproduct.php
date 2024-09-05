<?php



if ($_SESSION['useremail'] == ""  or $_SESSION['role'] == "User") {

  header('location:../');
}


display_message();
addproduct();

?>


<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Add Product</h1>
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
            <h5 class="m-0">Product</h5>
          </div>


          <form action="" method="post" enctype="multipart/form-data">
            <div class="card-body">
              <div class="row">
                <div class="col-md-6">

                  <div class="form-group">
                    <label>Product Name</label>
                    <input type="text" class="form-control" placeholder="Enter Name" name="txtproductname" autocomplete="off" required>
                  </div>

                  <div class="form-group">
                    <label>Category</label>
                    <select class="form-control" name="txtselect_option" required>
                      <option value="" disabled selected>Select Category</option>

                      <?php
                      $aus = $_SESSION['aus'];
                      $select = query("SELECT * from tbl_category where aus='$aus' order by catid desc");
                      confirm($select);

                      while ($row = $select->fetch_assoc()) {
                        extract($row);

                      ?>
                        <option value="<?php echo $row['catid']; ?>"><?php echo $row['category']; ?></option>

                      <?php

                      }

                      ?>


                    </select>
                  </div>


                  <div class="form-group">
                    <label>Description</label>
                    <textarea class="form-control" placeholder="Enter Description" name="txtdescription" rows="4"></textarea>
                  </div>




                </div>




                <div class="col-md-6">
                  <div class="form-group clearfix">
                    <div class="icheck-danger d-inline">
                      <input type="radio" id="radioPrimary1" name="r1" value="1" checked>
                      <label for="radioPrimary1">
                       ប្រភេទម្ហូប/ កែវ
                      </label>
                    </div>
                    <div class="icheck-primary d-inline">
                      <input type="radio" id="radioPrimary2" name="r1" value="2">
                      <label for="radioPrimary2">
                      ប្រភេទភេសជ្ជៈ
                      </label>
                    </div>

                  </div>
                  <div class="form-group">
                    <label>Sale Price</label>

                    <input type="number" min="1" step="any" class="form-control" placeholder="Enter Price" name="txtsaleprice" autocomplete="off" required>
                  </div>

                  <div class="form-group">
                    <label>Size M Price ចានធំ​​/ដប់</label>
                    <input type="number" min="1" step="any" class="form-control" value="<?php echo $m_price_db; ?>" placeholder="Enter Price" name="txtm_price" autocomplete="off" >
                  </div>


                  <div class="form-group">
                    <label>Product image</label>
                    <input type="file" class="input-group" name="myfile" >
                    <p>Upload image</p>
                  </div>

                </div>

              </div>
            </div>

            <div class="card-footer">
              <div class="text-center">
                <button type="submit" class="btn btn-primary" name="btnsave">Save Product</button>
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