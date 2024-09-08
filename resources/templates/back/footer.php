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
    POS RESTAURANT INVENTORY SYSTEM V-3.2
  </div>
  <!-- Default to the left -->
  <strong>BY: <a href="https://www.thpos.store/" target="_blank">THPOS.STORE</a></strong>
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
<script src="../resources/templates/back/posl.js"></script>
<script src="../resources/templates/back/orderlisl.js"></script>


<!-- <?php require_once("edit_order.php"); ?> -->

<script src="../resources/templates/back/dom-to-image.js"></script>

<script>
  var ti = document.getElementsByClassName("dowjpg")[0];
  var ddd = document.getElementById("dw_bt");

  ddd.addEventListener("click", () => {
    domtoimage.toJpeg(ti).then((data) => {
      var link = document.createElement("a");
      link.download = "rr.jpg";
      link.href = data;
      link.click();
    })
  })
</script>

<?php
$aus = $_SESSION['aus'];
$change = query("SELECT * from tbl_change where aus='$aus'");
confirm($change);
$row_exchange = $change->fetch_object();
$exchange = $row_exchange->exchange;
$usd_or_real = $row_exchange->usd_or_real; ?>

<script>
  // when a user click on the payment button
  $("#order-detail").on("click", ".btn-payment", function() {
    var totalAmoutt = $(this).attr('data-totalAmount');

    discount = parseFloat($("#txtdiscount_p").val());
    discount = discount / 100;


    if ('<?php echo $usd_or_real ?>' == 'usd') {

      totalAmout = totalAmoutt
      discount = discount * totalAmout;

      total = totalAmout - discount;
      subtotal = total * <?php echo $exchange ?>;

    } else {
      totalAmout = totalAmoutt * <?php echo $exchange ?>

      discount = discount * totalAmout;

      total = totalAmout - discount;
      subtotal = total / <?php echo $exchange ?>

    }

    total_khr = subtotal
    // due = total - paid_amt;

    $("#txtdiscount_n").val(discount);
    $("#txttotall").val(total);
    $("#txttotall_khr").val(total_khr);

    $("#txtsubtotal_id").val(totalAmout);

    // $("#txtsubtotal_id").val('');
    // $(".changeAmount").html('');
    SALE_ID = $(this).data('id');


  });
</script>


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

  $('#date_11').datetimepicker({
    format: 'DD-MM-YYYY'
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

      // "order": [
      //   [0, "desc"]
      // ]
    });
  });

  // $(document).ready(function() {
  //     $('#table_bestsellingproduct').DataTable({


  //     });
  // });

  // $('.btn-save-payment').on('click', function() {

  //   $.ajax({
  //     url: "../resources/templates/back/payqr.php",
  //     method: "Post",
  //     data: {
  //       id: id

  //     },
  //     success: function(data) {

  //       $('#receipt').html(data);
  //       $('#receipt').append(data.htmlresponse);

  //     }
  //   });
  // });



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

  // $('.ff').on('click', function() {
  //   var id = $(this).attr("id");

  //   $.ajax({
  //     url: "../resources/templates/back/payqr.php",
  //     method: "Post",
  //     data: {
  //       id: id

  //     },
  //     success: function(data) {

  //       $('#jj').html(data);
  //       $('#jj').append(data.htmlresponse);

  //     }
  //   });
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
      icon: 'logo/logo1.png',
      vibration: [300, 200, 300],
    });

    notification.addEventListener('click', function() {
      window.open('http://localhost/coffee%20-%20Copy/ui/');
    });
    setTimeout(() => notification.close(), 5 * 2000);

  }

  function playMusic() {
    let audio = new Audio("../resources/sound/notification.mp3");
    audio.play()
  }


  function getLocation() {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(showPosition, showError);
    }
  }

  function showPosition(position) {
    document.querySelector('.myForm input[name = "latitude"]').value = position.coords.latitude;
    document.querySelector('.myForm input[name = "longitude"]').value = position.coords.longitude;
  }

  function showError(error) {
    switch (error.code) {
      case error.PERMISSION_DENIED:
       alert("You Must Allow The Request For Geolocation To Fill Out The Form ");
        location.reload();
        break;
    }
  }
</script>



</body>

</html>