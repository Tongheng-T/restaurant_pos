<script>
    //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
        theme: 'bootstrap4'
    });


    var productarr = [];

    $(function() {


        $("#list-menu").on("click", ".btn-menu", function() {


            var productid = $(this).data("id");

            $.ajax({
                url: "../resources/templates/back/getproduct.php",
                method: "get",
                dataType: "json",
                data: {
                    id: productid,
                },
                success: function(data) {
                    //alert("pid");

                    //console.log(data);


                    if (jQuery.inArray(data["pid"], productarr) !== -1) {

                        var actualqty = parseInt($('#qty_id' + data["pid"]).val()) + 1;
                        $('#qty_id' + data["pid"]).val(actualqty);
                        
                        var saleprice = parseInt(actualqty) * data["saleprice"];
            
                        <?php
                        $change = query("SELECT * from tbl_change where aus= '$aus'");
                        confirm($change);
                        $row_exchange = $change->fetch_object();
                        $exchange = $row_exchange->exchange;
                        $usd_or_real = $row_exchange->usd_or_real;
                        ?>

                        if ('<?php echo $usd_or_real ?>' == 'usd') {
                            salepricee = saleprice;
                        } else {
                            salepricee = saleprice * <?php echo $exchange ?>;

                        }
        

                        $('#saleprice_id' + data["pid"]).html(salepricee);
                        $('#saleprice_idd' + data["pid"]).val(salepricee);

                        //$("#txtbarcode_id").val("");

                        calculate(0, 0);
                    } else {

                        addrow(data["pid"], data["product"], data["saleprice"], data["image"]);

                        productarr.push(data["pid"]);
                  
                        //$("#txtbarcode_id").val("");

                        function addrow(pid, product, saleprice, image) {

                            <?php
                            $change = query("SELECT * from tbl_change where aus = '$aus'");
                            confirm($change);
                            $row_exchange = $change->fetch_object();
                            $exchange = $row_exchange->exchange;
                            $usd_or_real = $row_exchange->usd_or_real;
                            ?>

                            if ('<?php echo $usd_or_real ?>' == 'usd') {
                                salepricee = saleprice;
                            } else {
                                salepricee = saleprice * <?php echo $exchange ?>;

                            }
                 
                            var tr = '<tr>' +

                                '<td style="text-align:left; vertical-align:middle; font-size:17px;"> <class="form-control product_c" name="product_arr[]" <span class="badge badge-primary">' + product + '</span><input type="hidden" class="form-control pid" name="pid_arr[]" value="' + pid + '" ><input type="hidden" class="form-control product" name="product_arr[]" value="' + product + '" >  </td>' +

                                '<td style="text-align:left; vertical-align:middle; font-size:17px;"><span class="badge badge-warning price" name="price_arr[]" id="price_id' + pid + '">' + salepricee + '</span><input type="hidden" class="form-control price_c" name="price_c_arr[]" id="price_idd' + pid + '" value="' + salepricee + '"></td>' +

                                '<td><input type="text" class="form-control qty" name="quantity_arr[]" id="qty_id' + pid + '" value="' + 1 + '" size="1"></td>' +

                                '<td style="text-align:left; vertical-align:middle; font-size:17px;"><span class="badge badge-success totalamt" name="netamt_arr[]" id="saleprice_id' + pid + '">' + salepricee + '</span><input type="hidden" class="form-control saleprice" name="saleprice_arr[]" id="saleprice_idd' + pid + '" value="' + salepricee + '"></td>' +

                                //remove button code start here

                                // '<td style="text-align:center; vertical-align:middle;"><center><name="remove" class"btnremove" data-id="'+pid+'"><span class="fas fa-trash" style="color:red"></span></center></td>'+

                                '<td><center><button type="button" name="remove" class="btn btn-danger btn-sm btnremove" data-id="' + pid + '"><span class="fas fa-trash"></span></center></td>' +


                                '</tr>';


                            $('.details').append(tr);
                            calculate(0, 0);

                        } //end function addrow

                    }

                } // end of success function
            }) // end of ajax request
        }) // end of onchange function
    }); // end of main function

    $("#itemtable").delegate(".qty", "keyup change", function() {

        var quantity = $(this);
        var tr = $(this).parent().parent();


        tr.find(".totalamt").text(quantity.val() * tr.find(".price").text());

        tr.find(".saleprice").val(quantity.val() * tr.find(".price").text());
        calculate(0, 0);

    });


    $("#txtpaid").keyup(function() {

        var totalAmount = $("#txttotall").val();
        var recievedAmount = $(this).val();
        var changeAmount = recievedAmount - totalAmount;

        if ('<?php echo $usd_or_real ?>' == 'usd') {
            var chkh = changeAmount * <?php echo $exchange ?>;
            var dd = 2;
            var ddkh = '';
        } else {
            var chkh = changeAmount / <?php echo $exchange ?>;
            var dd = '';
            var ddkh = 2;

        }

        $("#txtduekh").val(chkh.toFixed(ddkh));
        $("#txtdue").val(changeAmount.toFixed(dd));

        //check if cashier enter the right amount, then enable or disable save payment button

        if (changeAmount >= 0) {
            $('.btn-save-payment').prop('disabled', false);
        } else {
            $('.btn-save-payment').prop('disabled', true);
        }


    });

    function calculate(dis, paid) {

        var subtotal = 0;
        var discount = dis;
        var total = 0;
        var paid_amt = paid;
        var due = 0;

        $(".saleprice").each(function() {

            subtotal = subtotal + ($(this).val() * 1);
            // alert(subtotal)
        });

        $("#txtsubtotal_id").val(subtotal.toFixed(2));


        discount = parseFloat($("#txtdiscount_p").val());


        discount = discount / 100;
        discount = discount * subtotal;


        

        var dd = '';
        total = subtotal - discount;
        due = paid_amt - total;


        if ('<?php echo $usd_or_real ?>' == 'usd') {
            subtotal = total * <?php echo $exchange ?>;
            var dd = 2;
            var khc = '';

        } else {
            var khc = 2;
            subtotal = total / <?php echo $exchange ?>

        }
        total_khr = subtotal;
        $("#txtdiscount_n").val(discount.toFixed(dd));
        $("#txttotall_khr").val(total_khr.toFixed(khc));

        $("#txttotall").val(total.toFixed(dd));

        

        document.getElementById("txttotal").innerHTML = total.toFixed(dd);

        // $("#txtdue").val(due.toFixed(2));


        if (due >= 0) {
            $('.btn-save-payment').prop('disabled', false);
        } else {
            $('.btn-save-payment').prop('disabled', true);
        }


    } //end calculate function







    $(document).on('click', '.btnremove', function() {

        var removed = $(this).attr("data-id");
        productarr = jQuery.grep(productarr, function(value) {

            return value != removed;
            calculate(0, 0);
        })

        $(this).closest('tr').remove();
        calculate(0, 0);
    });



    $(document).ready(function() {


        $(".nav-link").click(function() {
            var id = $(this).data("id");
            $.ajax({
                url: "../resources/templates/getMenuByCategory.php",
                method: "get",
                data: {
                    id: id
                },
                success: function(data) {

                    $("#list-menu").hide();
                    $('#list-menu').html(data);
                    $("#list-menu").fadeIn('fast');
                    // $('#list-menu').append(data.htmlresponse);

                }

            });
        })

        // $(".nav-link").click(function() {
        //   $.get("../resources/templates/getMenuByCategory/" + $(this).data("id"), function(data) {
        //     $("#list-menu").hide();
        //     $("#list-menu").html(data);
        //     $("#list-menu").fadeIn('fast');
        //   });
        // })




    });
</script>

