<!-- Control Sidebar -->
<!-- <aside class="control-sidebar control-sidebar-dark"> -->
<!-- Control sidebar content goes here -->
<!-- <div class="p-3">
    <h5>Title</h5>
    <p>Sidebar content</p>
  </div>
</aside> -->
<!-- /.control-sidebar -->

<!-- Main Footer -->
<footer class="main-footer">
  <!-- To the right -->
  <div class="float-right d-none d-sm-inline">
    POS COFFEE SYSTEM V-2.1
  </div>
  <!-- Default to the left -->
  <strong>BY: <a href="https://www.thpos.store/" target="_blank">THPOS.STORE</a></strong>
</footer>
</div>
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
<!-- <script src="../resources/templates/back/pos.js"></script> -->
<script src="../resources/templates/back/orderlis.js"></script>
<script src="../dist/js/timeee.js"></script>

<?php require_once("edit_order.php"); ?>
<?php require_once("posJS.php"); ?>



<script>
  $('.id').on('click', function() {
    var id = $(this).attr("id");

    $.ajax({
      url: "../resources/templates/back/payqr.php",
      method: "Post",
      data: {
        id: id

      },
      success: function(data) {

        $('#payuser').html(data);
        $('#payuser').append(data.htmlresponse);

      }
    });
  });

  $('.send_telegram').on('click', function() {
    var id = $(this).attr("id");
    $.ajax({
      url: "../resources/templates/back/send_telegram.php",
      method: "post",
      data: {
        id: id
      },
      success: function(data) {
        window.location.href = 'itemt?tablereport';
      }

    });
  })

  $('#print-btn').click(function() {
    var nw = window.open("print2", "_blank", "height=500,width=800")
    setTimeout(function() {
      nw.print()
      setTimeout(function() {
        nw.close()
      }, 500)
    }, 1000)
  })

  $('#print-btn_user').click(function() {
    var nw = window.open("print_category", "_blank", "height=500,width=800")
    setTimeout(function() {
      nw.print()
      setTimeout(function() {
        nw.close()
      }, 500)
    }, 1000)
  })



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
        [1, "desc"]
      ]
    });
  });

  // $(document).ready(function() {
  //     $('#table_bestsellingproduct').DataTable({


  //     });
  // });
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

  $('.showmess').on('click', function() {

$.ajax({
  url: "../resources/templates/back/showmess.php",
  success: function(data) {
    $('#showmess').html(data);
    $('#showmess').append(data.htmlresponse);

  }
});
});

  function getUserStatus() {
    jQuery.ajax({
      url: '../resources/templates/back/a.php',
      success: function(result) {
        jQuery('#notifications').html(result);
      }
    })
  }

  setInterval(function() {
    getUserStatus();
  }, 1000);



  function notify() {
    const notification = new Notification('THPOS:', {
      body: `Notifications`,
      icon: 'logo/logo2.png',
      vibration: [300, 200, 300],
    });

    notification.addEventListener('click', function() {
      window.open('https://www.thpos.store/coffee/ui/');
    });
    setTimeout(() => notification.close(), 5 * 2000);

  }

  function playMusic() {
    let audio = new Audio("../../resources/sound/notification.mp3");
    audio.play()
  }


  function updateUserStatuss(){
    jQuery.ajax({
      url:'../resources/templates/update_user_status.php',
      success:function(){
  
      }
    })
  }
  
  setInterval(function(){
    updateUserStatuss();
  },3000);
  


</script>

</body>

</html>