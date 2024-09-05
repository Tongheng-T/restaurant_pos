<?php



if ($_SESSION['useremail'] == ""  or $_SESSION['role'] == "") {

  header('location:../');
}


display_message();
addproduct();

?>




<main>
  <h1>Product</h1>


  <form action="" method="post" enctype="multipart/form-data">
    <div class="card-body">
      <div class="row">
        <div class="col-md-6">

          <div style="overflow-x:auto;">
            <table class="table table-striped table-hover " > 
            <!-- id="table_product" -->
              <thead>
                <tr>
                  <td>id</td>
                  <td>free day</td>
                  <td>ActionIcons</td>

                </tr>

              </thead>

              <tbody>

                <?php free_trial(); ?>

              </tbody>

            </table>


          </div>



        </div>



        <div class="col-md-6">


        </div>

      </div>
    </div>

    <!-- <div class="card-footer">
      <div class="text-center">
        <button type="submit" class="btn btn-primary" name="btnsave">Save Product</button>
      </div>
    </div> -->

  </form>
</main>