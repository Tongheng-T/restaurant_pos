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


          <form action="" method="post" enctype="multipart/form-data" class="p-3">
            <div class="row">
              <!-- Left Column -->
              <div class="col-md-6">

                <!-- Product Name -->
                <div class="form-group">
                  <label for="txtproductname">ឈ្មោះផលិតផល <span class="text-danger">*</span></label>
                  <input type="text"
                    id="txtproductname"
                    class="form-control"
                    placeholder="បញ្ចូលឈ្មោះផលិតផល"
                    name="txtproductname"
                    autocomplete="off"
                    required>
                </div>

                <!-- Category -->
                <div class="form-group">
                  <label for="txtselect_option">ប្រភេទផលិតផល <span class="text-danger">*</span></label>
                  <select class="form-control" id="txtselect_option" name="txtselect_option" required>
                    <option value="" disabled selected>ជ្រើសរើសប្រភេទ</option>
                    <?php
                    $aus = $_SESSION['aus'];
                    $select = query("SELECT * from tbl_category where aus='$aus' order by catid desc");
                    confirm($select);
                    while ($row = $select->fetch_assoc()) {
                      echo "<option value='{$row['catid']}'>{$row['category']}</option>";
                    }
                    ?>
                  </select>
                </div>

                <!-- Description -->
                <div class="form-group">
                  <label for="txtdescription">ពិពណ៌នា</label>
                  <textarea id="txtdescription" class="form-control"
                    placeholder="បញ្ចូលពិពណ៌នា"
                    name="txtdescription" rows="4"></textarea>
                </div>

              </div>

              <!-- Right Column -->
              <div class="col-md-6">

                <!-- Type -->
                <div class="form-group clearfix">
                  <label>ប្រភេទ</label><br>
                  <div class="icheck-danger d-inline mr-3">
                    <input type="radio" id="radioPrimary1" name="r1" value="1" checked>
                    <label for="radioPrimary1">ម្ហូប / កែវ</label>
                  </div>
                  <div class="icheck-primary d-inline">
                    <input type="radio" id="radioPrimary2" name="r1" value="2">
                    <label for="radioPrimary2">ភេសជ្ជៈ</label>
                  </div>
                </div>

                <!-- Sale Price -->
                <div class="form-group">
                  <label for="txtsaleprice">តម្លៃលក់ <span class="text-danger">*</span></label>
                  <input type="number" min="1" step="any" id="txtsaleprice"
                    class="form-control"
                    placeholder="បញ្ចូលតម្លៃលក់"
                    name="txtsaleprice"
                    autocomplete="off"
                    required>
                </div>

                <!-- Size M Price -->
                <div class="form-group">
                  <label for="txtm_price">តម្លៃ Size M (ចានធំ/ដប់)</label>
                  <input type="number" min="1" step="any" id="txtm_price"
                    class="form-control"
                    value="<?php echo $m_price_db ?? ''; ?>"
                    placeholder="បញ្ចូលតម្លៃ"
                    name="txtm_price" autocomplete="off">
                </div>

                <!-- Image -->
                <div class="form-group">
                  <label for="myfile">រូបភាពផលិតផល</label>
                  <input type="file" id="myfile" class="form-control-file" name="myfile">
                  <small class="form-text text-muted">ប្រភេទឯកសារ: jpg, jpeg, png, gif (Max: 1MB)</small>
                </div>

              </div>
            </div>

            <!-- Submit -->
            <div class="text-center">
              <button type="submit" class="btn btn-primary btn-lg px-5" name="btnsave">
                <i class="fas fa-save"></i> រក្សាទុកផលិតផល
              </button>
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