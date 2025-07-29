//Initialize Select2 Elements
$('.select2').select2()

//Initialize Select2 Elements
$('.select2bs4').select2({
    theme: 'bootstrap4'
});

// make table-detail hidden by default
$("#table-detail").hide();

//show all tables when a client click on the button
$("#btn-show-tables").click(function () {
    if ($("#table-detail").is(":hidden")) {
        $.get("../resources/templates/get_table.php", function (data) {
            $("#table-detail").html(data);
            $("#table-detail").slideDown('fast');
            $("#btn-show-tables").html('Hide Tables').removeClass('btn-primary').addClass('btn-danger');
        })
    } else {
        $("#table-detail").slideUp('fast');
        $("#btn-show-tables").html('View All Tables').removeClass('btn-danger').addClass('btn-primary');
    }

});


var SELECTED_TABLE_ID = "";
var SELECTED_TABLE_NAME = "";
var SALE_ID = "";
// detect button table onclick to show table data
$("#table-detail").on("click", ".btn-table", function () {
    SELECTED_TABLE_ID = $(this).data("id");
    SELECTED_TABLE_NAME = $(this).data("name");
    $("#selected-table").html('<br><h3>Table: ' + SELECTED_TABLE_NAME + '</h3><hr>');

    $.ajax({
        url: "../resources/templates/back/getSaleDetailsByTable.php",
        method: "get",
        data: {
            table_id: SELECTED_TABLE_ID,
        },
        success: function (data) {
            $("#order-detail").html(data);
        }
    })

});
$("#list-menu").on("click", ".btn-menu", function () {
    if (SELECTED_TABLE_ID == "") {
        alert("You need to select a table for the customer first");
    } else {

        var productid = $(this).data("id");

        $.ajax({
            url: "../resources/templates/back/getproduct.php",
            method: "POST",
            data: {
                id: productid,
                table_id: SELECTED_TABLE_ID,
                table_name: SELECTED_TABLE_NAME,
                qty: 1
            },
            success: function (data) {
                $("#order-detail").html(data);
            }
        })
    }
})


$("#order-detail").on('click', ".btn-confirm-order", function () {
    var invoice_id = $(this).data("id");
    $.ajax({
        type: "POST",
        data: {
            "_token": $('meta[name="csrf-token"]').attr('content'),
            "sale_id": invoice_id
        },
        url: "../resources/templates/back/confirmOrderStatus.php",
        success: function (data) {
            $("#order-detail").html(data);
        }
    });
});


// delete saledetail

$("#order-detail").on("click", ".btn-delete-saledetail", function () {
    var saleDetailID = $(this).data("id");
    $.ajax({
        type: "POST",
        data: {
            "_token": $('meta[name="csrf-token"]').attr('content'),
            "saleDetail_id": saleDetailID
        },
        url: "../resources/templates/back/deleteSaleDetail.php",
        success: function (data) {
            $("#order-detail").html(data);
        }
    })

});



// increase quantity

$("#order-detail").on("click", ".btn-increase-quantiy", function () {
    var saleDetailID = $(this).data("id");
    $.ajax({
        type: "POST",
        data: {
            "_token": $('meta[name="csrf-token"]').attr('content'),
            "saleDetail_id": saleDetailID
        },
        url: "../resources/templates/increase-quantiy.php",
        success: function (data) {
            $("#order-detail").html(data);
        }
    })

});


// decrease quantity

$("#order-detail").on("click", ".btn-decrease-quantiy", function () {
    var saleDetailID = $(this).data("id");
    $.ajax({
        type: "POST",
        data: {
            "_token": $('meta[name="csrf-token"]').attr('content'),
            "saleDetail_id": saleDetailID
        },
        url: "../resources/templates/decrease-quantiy.php",
        success: function (data) {
            $("#order-detail").html(data);
        }
    })

});
// btn-size
$("#order-detail").on("click", ".btn-size", function () {
    var saleDetailID = $(this).data("id");
    $.ajax({
        type: "POST",
        data: {
            "_token": $('meta[name="csrf-token"]').attr('content'),
            "saleDetail_id": saleDetailID,
           
        },
        url: "../resources/templates/btn-size.php",
        success: function (data) {
            $("#order-detail").html(data);
        }
    })

});



// when a user click on the payment button
// $("#order-detail").on("click", ".btn-payment", function () {
//     var totalAmout = $(this).attr('data-totalAmount');

//     discount = parseFloat($("#txtdiscount_p").val());
//     discount = discount / 100;
//     discount = discount * totalAmout;

//     total = totalAmout - discount;
//     total_khr = total * 4000
//     // due = total - paid_amt;

//     $("#txtdiscount_n").val(discount);
//     $("#txttotall").val(total);
//     $("#txttotall_khr").val(total_khr);

//     $("#txtsubtotal_id").val(totalAmout);

//     // $("#txtsubtotal_id").val('');
//     // $(".changeAmount").html('');
//     SALE_ID = $(this).data('id');


// });

// calcuate change
$("#txtpaid").keyup(function () {
    var totalAmount = $("#txttotall").val();
    var recievedAmount = $(this).val();
    var changeAmount = recievedAmount - totalAmount;

    $("#txtdue").val(changeAmount);

    //check if cashier enter the right amount, then enable or disable save payment button

    if (changeAmount >= 0) {
        $('.btn-save-payment').prop('disabled', false);
    } else {
        $('.btn-save-payment').prop('disabled', true);
    }

});





// save payment
$(".btn-save-payment").click(function () {
    var total = $("#txtsubtotal_id").val();
    var totalAmount = $("#txttotall").val();
    var paymentType = $("input[name='rb']:checked").val();
    var discountp = $("#txtdiscount_p").val();
    var discountKHR = $("#txtdiscount_n").val();
    var Change = $("#txtdue").val();
    var recievedAmount = $("#txtpaid").val();

    var saleId = SALE_ID;

    $.ajax({
        type: "POST",
        data: {

            "saleId": saleId,
            "total": total,
            "Change": Change,
            "totalAmount": totalAmount,
            "discountp": discountp,
            "discountKHR": discountKHR,
            "recievedAmount": recievedAmount,
            "paymentType": paymentType

        },
        url: "../resources/templates/back/savePayment.php",
        success: function (data) {
            window.location.href = '../ui/showReceipt?id=' + saleId;
            // document.location.href = href;
        }
    });
});



// var productarr = [];

// $(function () {

//     $("#list-menu").on("click", ".btn-menu", function () {
//         if(SELECTED_TABLE_ID == ""){
//             alert("You need to select a table for the customer first");
//           }else{

//         var productid = $(this).data("id");

//         $.ajax({
//             url: "../resources/templates/back/getproduct.php",
//             method: "get",
//             dataType: "json",
//             data: {
//                 id: productid,
//                 table_id: SELECTED_TABLE_ID,
//                 table_name: SELECTED_TABLE_NAME
//             },
//             success: function (data) {



//                 if (jQuery.inArray(data["pid"], productarr) !== -1) {

//                     var actualqty = parseInt($('#qty_id' + data["pid"]).val()) + 1;
//                     $('#qty_id' + data["pid"]).val(actualqty);

//                     var saleprice = parseInt(actualqty) * data["saleprice"];

//                     $('#saleprice_id' + data["pid"]).html(saleprice);
//                     $('#saleprice_idd' + data["pid"]).val(saleprice);



//                     calculate(0, 0);
//                 } else {

//                     addrow(data["pid"], data["product"], data["saleprice"], data["image"]);

//                     productarr.push(data["pid"]);



//                     function addrow(pid, product, saleprice, image) {

//                         var tr = '<tr>' +

//                             '<td style="text-align:left; vertical-align:middle; font-size:17px;"> <class="form-control product_c" name="product_arr[]" <span class="badge badge-primary">' + product + '</span><input type="hidden" class="form-control pid" name="pid_arr[]" value="' + pid + '" ><input type="hidden" class="form-control product" name="product_arr[]" value="' + product + '" >  </td>' +

//                             '<td style="text-align:left; vertical-align:middle; font-size:17px;"><span class="badge badge-warning price" name="price_arr[]" id="price_id' + pid + '">' + saleprice + '</span><input type="hidden" class="form-control price_c" name="price_c_arr[]" id="price_idd' + pid + '" value="' + saleprice + '"></td>' +

//                             '<td><input type="text" class="form-control qty" name="quantity_arr[]" id="qty_id' + pid + '" value="' + 1 + '" size="1"></td>' +

//                             '<td style="text-align:left; vertical-align:middle; font-size:17px;"><span class="badge badge-success totalamt" name="netamt_arr[]" id="saleprice_id' + pid + '">' + saleprice + '</span><input type="hidden" class="form-control saleprice" name="saleprice_arr[]" id="saleprice_idd' + pid + '" value="' + saleprice + '"></td>' +


//                             '<td><center><button type="button" name="remove" class="btn btn-danger btn-sm btnremove" data-id="' + pid + '"><span class="fas fa-trash"></span></center></td>' +


//                             '</tr>';


//                         $('.details').append(tr);
//                         calculate(0, 0);

//                     } 

//                 }

//             } 
//         }) 
//     }
//     }) 
// }); 


$("#itemtable").delegate(".qty", "keyup change", function () {

    var quantity = $(this);
    var tr = $(this).parent().parent();


    tr.find(".totalamt").text(quantity.val() * tr.find(".price").text());

    tr.find(".saleprice").val(quantity.val() * tr.find(".price").text());
    calculate(0, 0);

});



function calculate(dis, paid) {

    var subtotal = 0;
    var discount = dis;
    var sgst = 0;
    var cgst = 0;
    var total = 0;
    var paid_amt = paid;
    var due = 0;

    $(".saleprice").each(function () {

        subtotal = subtotal + ($(this).val() * 1);
    });

    $("#txtsubtotal_id").val(subtotal.toFixed(2));


    discount = parseFloat($("#txtdiscount_p").val());


    discount = discount / 100;
    discount = discount * subtotal;


    $("#txtdiscount_n").val(discount.toFixed(2));


    total = subtotal - discount;
    due = total - paid_amt;


    $("#txttotall").val(total.toFixed(2));
    document.getElementById("txttotal").innerHTML = total;

    $("#txtdue").val(due.toFixed(2));


    if (total >= 0) {
        $('.btn-save-payment').prop('disabled', false);
    } else {
        $('.btn-save-payment').prop('disabled', true);
    }


} //end calculate function


// $("#txtdiscount_p").keyup(function () {

//     var discount = $(this).val();

//     calculate(discount, 0);

// });

// $("#txtpaid").keyup(function () {

//     var paid = $(this).val();
//     var discount = $("#txtdiscount_p").val();
//     calculate(discount, paid);

// });



// $(document).on('click', '.btnremove', function () {

//     var removed = $(this).attr("data-id");
//     productarr = jQuery.grep(productarr, function (value) {

//         return value != removed;
//         calculate(0, 0);
//     })

//     $(this).closest('tr').remove();
//     calculate(0, 0);
// });



$(document).ready(function () {


    $(".nav-link").click(function () {
        var id = $(this).data("id");
        $.ajax({
            url: "../resources/templates/getMenuByCategory.php",
            method: "get",
            data: {
                id: id
            },
            success: function (data) {

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



$('.chang_branch').on('click', function () {
    var val = $(this).attr("value");
    var aus = $("#aus").val();

    $.ajax({
        url: "../resources/templates/back/update_real.php",
        method: "post",
        data: {

            val: val,
            aus: aus
        },
        success: function (data) {
            window.location.href = 'itemt?exchange';
        }

    });
})

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
  
