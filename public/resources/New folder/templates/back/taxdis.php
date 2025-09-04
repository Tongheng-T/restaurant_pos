<?php


if ($_SESSION['useremail'] == ""  or $_SESSION['role'] == "User") {

  header('location:../');
}

display_message();

if (isset($_POST['btnsave'])) {

  $discount = $_POST['txtdiscount'];

  if (empty($sgst)) {
    set_message(' <script>
        Swal.fire({
          icon: "warning",
          title: "Feild is Empty"
        });
      </script>');
    redirect('itemt?taxdis');
  } else {

    $insert = query("INSERT into tbl_taxdis (discount) values('{$discount}')");
    confirm($insert);

    if ($insert) {
      set_message(' <script>
        Swal.fire({
          icon: "success",
          title: "And Discount Added successfully"
        });
      </script>');
      redirect('itemt?taxdis');
    } else {
      set_message(' <script>
      Swal.fire({
        icon: "warning",
        title: "Failed"
      });
    </script>');
      redirect('itemt?taxdis');
    }
  }
}



if (isset($_POST['btnupdate'])) {


  $discount = $_POST['txtdiscount'];



  $id = $_POST['txtid'];

  if ($discount < 0) {
    set_message(' <script>
    Swal.fire({
      icon: "warning",
      title: "Feild is Empty"
    });
  </script>');
    redirect('itemt?taxdis');
  } else {

    $update = query("UPDATE tbl_taxdis set discount='{$discount}' where taxdis_id =" . $id);
    confirm($update);

    if ($update) {
      set_message(' <script>
      Swal.fire({
        icon: "success",
        title: "Dis Update successfully"
      });
    </script>');
      redirect('itemt?taxdis');
    } else {
      set_message(' <script>
      Swal.fire({
        icon: "warning",
        title: "Failed"
      });
    </script>');
      redirect('itemt?taxdis');
    }
  }
}


//   If(isset($_POST['btndelete'])){

//    $delete=$pdo->prepare("delete from tbl_category where catid=".$_POST['btndelete']); 

//    if($delete->execute()){
//     $_SESSION['status']="Deleted";
//     $_SESSION['status_code']="success";

//    }else{

//     $_SESSION['status']="Delete Failed";
//     $_SESSION['status_code']="warning";


//    }




//   }else{




//   }


?>


<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">DISCOUNT</h1>
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

    <div class="card card-warning card-outline">
      <div class="card-header">
        <h5 class="m-0"> Discount Form</h5>
      </div>


      <form action="" method="post">
        <div class="card-body">


          <div class="row">


            <?php

            if (isset($_POST['btnedit'])) {

              $select = query("SELECT * from tbl_taxdis where taxdis_id =" . $_POST['btnedit']);
              confirm($select);
              if ($select) {
                $row = $select->fetch_object();

                echo '<div class="col-md-4">


               
               <div class="form-group">
                
               
                 <input type="hidden" class="form-control" placeholder="Enter Category"  value="' . $row->taxdis_id . '" name="txtid" >

               
               <div class="form-group">
                 <label for="exampleInputEmail1">Discount(%)</label>
                 <input type="text" class="form-control" placeholder="Enter Discount" value="' . $row->discount . '" name="txtdiscount" >
               </div>
               
               </div>
               
               <div class="card-footer">
               <button type="submit" class="btn btn-info" name="btnupdate">Update</button>
               </div>
               
               
               </div>';
              }
            } else {

              echo '<div class="col-md-4">
               
               
               <div class="form-group">
                 <label for="exampleInputEmail1">Discount(%)</label>
                 <input type="text" class="form-control" placeholder="Enter Discount"  name="txtdiscount" >
               </div>
               

               
               </div>';
            }




            ?>


            <div class="col-md-8">

              <table class="table table-striped table-hover ">
                <thead>
                  <tr>
                    <td>#</td>
                    <td>Discount</td>
                    <td>Edit</td>

                  </tr>

                </thead>


                <tbody>

                  <?php
                  $aus = $_SESSION['aus'];
                  $no = 1;
                  $select = query("SELECT * from tbl_taxdis where aus = '$aus' ");
                  confirm($select);

                  while ($row = $select->fetch_object()) {

                    echo '
                      <tr>
                      <td>' . $no . '</td>
                      <td>' . $row->discount . '</td>
                      <td>
                      
                      <button type="submit" class="btn btn-primary" value="' . $row->taxdis_id . '" name="btnedit">Edit</button>
                      
                      </td>
                      
                      
                      </tr>';
                  }
                  $no++;

                  ?>

                </tbody>
                <!-- <tfoot>
                  <tr>
                    <td>#</td>
                    <td>SGST</td>
                    <td>CGST</td>
                    <td>Discount</td>
                    <td>Edit</td>

                  </tr>

                </tfoot> -->

              </table>
            </div>

          </div>

        </div>
      </form>
    </div>
  </div><!-- /.container-fluid -->
</div>
<!-- /.content -->