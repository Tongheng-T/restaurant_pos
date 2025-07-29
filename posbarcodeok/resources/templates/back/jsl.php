<script>
  $(function() {

    $('.select2s').on('change', function() {

      var bacodeid = $(".select2s").val();


      $.ajax({
        url: "../resources/templates/back/getprobacode.php",
        method: "get",
        dataType: "json",
        data: {
          id: bacodeid

        },
        success: function(data) {

          // alert(data["sj_price"])
          var bacode = data["bacode"];
          // $("#txtbarcode_id").val("");
          var show_bacode = document.getElementById('barcode');
          show_bacode.value = bacode;

        }
      });
    })


    $(".barcode").keyup(function() {

      var bacodeid = $('.barcode').val();


      $.ajax({
        url: "../resources/templates/back/getnamepro.php",
        method: "get",
        data: {
          id: bacodeid

        },
        success: function(data) {
          $('#pname').html(data);
          $('#pname').append(data.htmlresponse);

        }
      })
    })



    $(".barcode").keyup(function() {

      var bacodeid = $('.barcode').val();

      $.ajax({
        url: "../resources/templates/back/showimgg.php",
        method: "get",
        dataType: "json",
        data: {
          id: bacodeid

        },

        success: function(data) {

          <?php
          $change = query("SELECT * from tbl_change where aus = '$aus'");
          confirm($change);
          $row_exchange = $change->fetch_object();
          $exchange = $row_exchange->exchange;
          $usd_or_real = $row_exchange->usd_or_real;
          ?>

          if ('<?php echo $usd_or_real ?>' == 'usd') {
            var purchaseprice = data["purchaseprice"];
            var saleprice = data["saleprice"];

          } else {
            var purchaseprice = data["purchaseprice"] * <?php echo $exchange ?>;
            var saleprice = data["saleprice"] * <?php echo $exchange ?>;

          }
          $("#purchaseprice").val(purchaseprice);
          $('#saleprice').val(saleprice);
          $('#showimg').html('<img width="300" src="../productimages/' + data["image"] + '" alt="">');

        }
      })
    })

    $('.select2s').on('change', function() {

      var bacodeid = $(".select2s").val();


      $.ajax({
        url: "../resources/templates/back/showimgg.php",
        method: "get",
        dataType: "json",
        data: {
          pid: bacodeid

        },
        success: function(data) {
          <?php
          $change = query("SELECT * from tbl_change where aus = '$aus'");
          confirm($change);
          $row_exchange = $change->fetch_object();
          $exchange = $row_exchange->exchange;
          $usd_or_real = $row_exchange->usd_or_real;
          ?>

          if ('<?php echo $usd_or_real ?>' == 'usd') {
            var purchaseprice = data["purchaseprice"];
            var saleprice = data["saleprice"];

          } else {
            var purchaseprice = data["purchaseprice"] * <?php echo $exchange ?>;
            var saleprice = data["saleprice"] * <?php echo $exchange ?>;

          }

          $("#purchaseprice").val(purchaseprice);
          $('#saleprice').val(saleprice);
          $('#showimg').html('<img width="300" src="../productimages/' + data["image"] + '" alt="">');

        }
      })
    })


  });
</script>