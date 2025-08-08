<?php

if ($_SESSION['useremail'] == ""  or $_SESSION['role'] == "") {

  header('location:../');
}




edit_product();
deletepat();
display_message();

$id = $_GET['id'];

$select = query("SELECT * from products where product_id=$id");
confirm($select);

$row = $select->fetch_assoc();

$id_db = $row['product_id'];

$product_db = $row['product_title'];
$category_id_db = $row['product_category_id'];
$description_db = $row['product_description'];

$image_db = $row['product_image'];
$end = $row['end'];

?>



<main>
  <h1 class="title_item">បញ្ចូលភាគប្រភេទរឿង៖ <?php echo show_category_name($category_id_db) ?></h1>


  <form action="" method="post" enctype="multipart/form-data">
    <div class="card-body">
      <div class="row">
        <div class="col-md-6">

          <div class="form-group">
            <label>Movies Name</label>
            <input type="text" class="form-control" placeholder="Enter Name" name="txtproductname" autocomplete="off" required value="<?php echo $product_db; ?>">
          </div>

          <div class="form-group">
            <label>Category</label>
            <select class="form-control" name="txtselect_option" required>
              <option value="" disabled selected>Select Category</option>

              <?php
              $select = query("SELECT * from categories");
              confirm($select);

              while ($row = $select->fetch_assoc()) {
                extract($row);

              ?>
                <option value="<?php echo $row['cat_id'] ?>" <?php if ($row['cat_id'] == $category_id_db) { ?> selected="selected" <?php } ?>><?php echo $row['cat_title']; ?></option>

              <?php

              }

              ?>
            </select>
          </div>


          <div class="form-group">
            <label>Description</label>
            <textarea class="form-control" placeholder="Enter Description" name="txtdescription" rows="4"> <?php echo $description_db ?> </textarea>
          </div>



        </div>



        <div class="col-md-6">

          <div class="form-group">
            <label>Product image</label>
            <img style="width: 100px;" src="../../../../productimages/movi/<?php echo $image_db; ?>" alt="">
            <input type="file" class="input-group" name="myfile">
            <p>Upload image</p>
          </div>

          <div class="icheck-primary d-inline ml-2">
            <input type="checkbox" value="1" name="end" <?php echo ($end == '1') ? 'checked' : '' ?> id="todoCheck3">
            <label for="todoCheck3"></label>
          </div>
          <label>END</label>
        </div>
        <?php

        $select = query("SELECT * from sub_products where product_id=$id");
        confirm($select);

        while ($row = $select->fetch_assoc()) {
          extract($row);



        ?>
          <div class="col-md-6">
            <div class="form-group">
              <label>Movies Pat <?php echo $row['pat'] ?></label>
              <input type="hidden" class="form-control" placeholder="Enter Link Video" name="pat[]" autocomplete="off" required value="<?php echo $row['pat'] ?>">
              <input type="text" class="form-control" placeholder="Enter Link Video" name="video[]" autocomplete="off" required value="<?php echo $row['video'] ?>">
              <button type="submit" value="<?php echo $row['pat'] ?>" class="btn btn-danger btn-xs" name="btndeletepat"><span class="fa fa-trash" style="color:#ffffff" data-toggle="tooltip" title="Delete Product"></span></button>
            </div>
          </div>
        <?php

        }

        ?>


      </div>
    </div>

    <div class="card-footer">
      <div class="text-center">
        <button type="submit" class="btn btn-primary" name="btneditMovie">Updat Movie</button>
      </div>
    </div>

  </form>
</main>