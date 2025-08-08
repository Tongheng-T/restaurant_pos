<?php

if ($_SESSION['useremail'] == ""  or $_SESSION['role'] == "") {

  header('location:../');
}


display_message();
?>


<main>
  <h1>Movies List</h1>

  <div style="overflow-x:auto;">

    <table class="table table-striped table-hover " id="table_orderlist">
      <thead>
        <tr>
          <td>N.o</td>
          <td>id</td>
          <td>nameMovie</td>
          <td>Category</td>

          <td>View</td>
          <td>Pat</td>
          <td>Image</td>
          <td>ActionIcons</td>

        </tr>

      </thead>


      <tbody >

        <?php productlist(); ?>

      </tbody>

    </table>


  </div>

</main>

