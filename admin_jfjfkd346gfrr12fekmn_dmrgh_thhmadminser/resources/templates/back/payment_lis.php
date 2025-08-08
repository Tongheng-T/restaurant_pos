<?php



if ($_SESSION['useremail'] == ""  or $_SESSION['role'] == "") {

  header('location:../');
}


display_message();
addproduct();

?>




<main>
  <h1>Movies List</h1>

  <div style="overflow-x:auto;">

    <table class="table table-striped table-hover " id="table_orderlist">
      <thead>
        <tr>
          <td>Id</td>
          <th>Course Name</th>
          <th>Number Payment</th>
          <th>Img</th>
          <th>Date</th>
          <th>Num Month</th>
          <th>Num Day</th>
          <th>User Id</th>
          <th>Alert</th>

        </tr>

      </thead>


      <tbody >

        <?php payment_lis(); ?>

      </tbody>

    </table>


  </div>

</main>