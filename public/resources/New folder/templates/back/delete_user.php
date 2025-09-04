<?php
include_once '../../config.php';
$id = $_GET['id'];

if (isset($id)) {
    $select_inv = query("SELECT * from tbl_user where user_id= $id ");
    confirm($select_inv);
    $row_inv = $select_inv->fetch_object();

    $img = $row_inv->img;
    unlink("../../images/userpic/$img");

    $delete = query("DELETE from tbl_user where user_id =" . $id);
    confirm($delete);
    if ($delete) {

        set_message(' <script>
        Swal.fire({
        icon: "success",
        title: "Account deleted successfully"
        });
       </script>');
  
        redirect('../../../ui/itemt?registration');
    } else {
        set_message(' <script>
        Swal.fire({
        icon: "warning",
        title: "Account Is Not Deleted"
        });
       </script>');
        redirect('../../../ui/itemt?registration');
       
    }
}
