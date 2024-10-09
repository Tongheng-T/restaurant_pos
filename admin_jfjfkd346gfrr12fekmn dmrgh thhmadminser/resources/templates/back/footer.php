

<!-- Main Footer -->
<footer class="main-footer">
  <!-- To the right -->
  <div class="float-right d-none d-sm-inline">
    POS BARCODE INVENTORY SYSTEM V-2.0
  </div>
  <!-- Default to the left -->
  <strong>BY: <a href="https://web.facebook.com/TonghengCoding " target="_blank">Tongheng-T</a></strong>
</footer>

<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>


<!-- Select2 -->
<script src="../plugins/select2/js/select2.full.min.js"></script>

<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>


<!-- DataTables  & Plugins -->
<script src="../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>

<script src="../plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="../plugins/jszip/jszip.min.js"></script>
<script src="../plugins/pdfmake/pdfmake.min.js"></script>
<script src="../plugins/pdfmake/vfs_fonts.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="../plugins/datatables-buttons/js/buttons.colVis.min.js"></script>


<!-- SweetAlert2 -->
<script src="../plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- InputMask -->
<script src="../plugins/moment/moment.min.js"></script>

<!-- date-range-picker -->
<script src="../plugins/daterangepicker/daterangepicker.js"></script>

<!-- Tempusdominus Bootstrap 4 -->
<script src="../plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>


<!-- pos order -->
<script src="../resources/templates/back/pos.js"></script>
<!-- <script src="../resources/templates/back/orderlistt.js"></script> -->


<!-- <?php require_once("edit_order.php"); ?> -->



<script>
  $(document).ready(function() {
    $('#table_category').DataTable();
  });
</script>
<script>
  $(document).ready(function() {
    $('#table_product').DataTable({
      "autoWidth": false

    });

  });
</script>
<script>
  $(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip();
  });
</script>



<!-- ........//////////////////productlist -->

<script>
  $(document).ready(function() {
    $('.btndelete').click(function() {
      var tdh = $(this);
      var id = $(this).attr("id");

      Swal.fire({
        title: 'Do you want to delete?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.isConfirmed) {

          $.ajax({
            url: '../resources/templates/back/productdelete.php',
            type: 'post',
            data: {
              pidd: id
            },
            success: function(data) {
              tdh.parents('tr').hide();
            }

          });

          Swal.fire(
            'Deleted!',
            'Your Product has been deleted.',
            'success'
          )
        }
      })

    });

  });
</script>


<!-- taxdis -->

<script>
  $(document).ready(function() {
    $('#table_tax').DataTable();
  });
</script>



<!-- tablereport -->


<script>
  //Date picker
  $('#date_1').datetimepicker({
    format: 'YYYY-MM-DD'
  });



  //Date picker
  $('#date_2').datetimepicker({
    format: 'YYYY-MM-DD'
  });
</script>

<script>
  $(document).ready(function() {
    $('#table_report').DataTable({

      "order": [
        [0, "desc"]
      ]
    });
  });
</script>

<!-- dashboard -->
<script>
  $(document).ready(function() {
    $('#table_recentorder').DataTable({

      "order": [
        [0, "desc"]
      ]
    });
  });

  // $(document).ready(function() {
  //     $('#table_bestsellingproduct').DataTable({


  //     });
  // });


  function getUserStatusalert() {
    jQuery.ajax({
      url: '../resources/templates/back/a.php',
      success: function(result) {
        jQuery('#notifications').html(result);
      }
    })
  }

  setInterval(function() {
    getUserStatusalert();
  }, 1000);
  function notify() {
    const notification = new Notification('THPOS:', {
      body: `Notifications`,
      icon: 'logo/logo1.png',
      vibration: [300, 200, 300],
    });

    notification.addEventListener('click', function() {
      window.open('http://admin_jfjfkd346gfrr12fekmn%20dmrgh%20thhmadminser/ui/itemt?payment_lis');
    });
    setTimeout(() => notification.close(), 5 * 2000);

  }

  // //////////////////////

  function getUserStatusalertC() {
    jQuery.ajax({
      url: '../resources/templates/back/c.php',
      success: function(result) {
        jQuery('#notificationsC').html(result);
      }
    })
  }

  setInterval(function() {
    getUserStatusalertC();
  }, 1000);
  function notify() {
    const notificationC = new Notification('THPOS:', {
      body: `Notifications`,
      icon: 'logo/logo1.png',
      vibration: [300, 200, 300],
    });

    notificationC.addEventListener('click', function() {
      window.open('http://admin_jfjfkd346gfrr12fekmn%20dmrgh%20thhmadminser/ui/itemt?payment_lis');
    });
    setTimeout(() => notification.close(), 5 * 2000);

  }


  function playMusic() {
    let audio = new Audio("../../resources/sound/notification.mp3");
    audio.play()
  }

  function getUserStatuss(){
    jQuery.ajax({
      url:'../resources/templates/get_user_onlin.php',
      success:function(result){
        jQuery('#user_onlin').html(result);
      }
    })
  }
  setInterval(function(){
    getUserStatuss();
  },7000);
</script>


</body>

</html>