//Initialize Select2 Elements
$('.select2').select2()

//Initialize Select2 Elements
$('.select2bs4').select2({
    theme: 'bootstrap4'
});

// make table-detail hidden by default
$("#table-detail").hide();

//show all tables when a client click on the button


var parts = window.location.search.substr(1).split("&");
var $_GET = {};
for (var i = 0; i < parts.length; i++) {
    var temp = parts[i].split("=");
    $_GET[decodeURIComponent(temp[0])] = decodeURIComponent(temp[1]);
}

var SELECTED_TABLE_ID = $_GET.TABLE_ID;
var SELECTED_TABLE_NAME = $_GET.TABLE_NAME;
var SALE_ID = "";
var aus = $_GET.aus;
var user = $_GET.user;
if (SELECTED_TABLE_ID) {
    var aus = $_GET.aus;
    $("#selected-table").html('<br><h3>Table: ' + SELECTED_TABLE_NAME + '</h3><hr>');

    $.ajax({
        url: "resources/templates/back/getSaleDetailsByTable_qr.php",
        method: "get",
        data: {
            table_id: SELECTED_TABLE_ID,
            aus: aus,
        },
        success: function (data) {
            $("#order-detail").html(data);
        }
    })

}
$("#list-menu").on("click", ".btn-menu", function () {
    if (SELECTED_TABLE_ID == "") {
        alert("You need to select a table for the customer first");
    } else {

        var productid = $(this).data("id");

        $.ajax({
            url: "resources/templates/back/getproduct_qr.php",
            method: "POST",
            data: {
                id: productid,
                aus: aus,
                user: user,
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
            "sale_id": invoice_id,
            
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
            "saleDetail_id": saleDetailID,
            "aus": aus
        },
        url: "resources/templates/back/deleteSaleDetail_qr.php",
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
            "saleDetail_id": saleDetailID,
            "aus": aus,
        },
        url: "resources/templates/increase-quantiy_qr.php",
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
            "saleDetail_id": saleDetailID,
            "aus": aus,
        },
        url: "resources/templates/decrease-quantiy_qr.php",
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
            "aus": aus,

        },
        url: "resources/templates/btn-size_qr.php",
        success: function (data) {
            $("#order-detail").html(data);
        }
    })

});



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




$(document).ready(function () {
    var aus = $_GET.aus;
    var user = $_GET.user;

    $(".nav-link").click(function () {
        var id = $(this).data("id");
        $.ajax({
            url: "resources/templates/getMenuByCategory_qr.php",
            method: "get",
            data: {
                id: id,
                aus: aus,
                user: user
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
        url: "resources/templates/back/update_real.php",
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


