<?php
require_once("../../config.php");


// // echo num_message();
// $aus = $_SESSION['aus'];
// $rows = [];
// $select = query("SELECT messages AS msg from tbl_message where viw = 0 and aus= '{$aus}'");


// while($row_invoice_details = $select->fetch_assoc()) {
//     $rows[] = $row_invoice_details;
// }

//     header('Content-Type: application/json');
//     echo json_encode($rows);

?>
<span class="dropdown-item dropdown-header"><?php echo num_message() ?> Notifications</span>


<?php show_message_pay() ?>


<script>
      $('.viw').on('click', function() {
    var id = $(this).attr("id");

    $.ajax({
      url: "../resources/templates/back/unmesspay.php",
      method: "Post",
      data: {
        id: id

      },
      success: function(data) {

        window.location.reload(data);

      }
    });
  });
</script>