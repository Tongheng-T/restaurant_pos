<?php

function getSaleDetails_qr($invoice_id,$aus)

{
    
    $tbl_invoice = query("SELECT MAX(receipt_id) as receipt_num from tbl_invoice where aus='$aus'");
    confirm($tbl_invoice);
    $row = $tbl_invoice->fetch_object();
    $receipt_id = $row->receipt_num + 1;

    // list all saledetail
    $html = '<p>Sale ID: ' . $receipt_id . '</p>';
    $tbl_invoice_details = query("SELECT * from tbl_invoice_details where invoice_id=$invoice_id ");
    confirm($tbl_invoice_details);
    $html .= '<div class="table-responsive-md" style="overflow-y:scroll; height: 400px; border: 1px solid #343A40">
    <table class="table table-bordered table-hover text-center">
    <thead>
        <tr>
            <th scope="col">N0</th>
            <th scope="col">Menu</th>
            <th scope="col">Quantity</th>
            <th scope="col">Size</th>
            <th scope="col">Price</th>
            <th scope="col">Total</th>
            <th scope="col">Status</th>
        </tr>
    </thead>
    <tbody>';
    $showBtnPayment = true;
    $no = 1;
    foreach ($tbl_invoice_details as $invoice_details) {
        
        $change = query("SELECT * from tbl_change where aus='$aus'");
        confirm($change);
        $row_exchange = $change->fetch_object();
        $exchange = $row_exchange->exchange;
        $usd_or_real = $row_exchange->usd_or_real;

        if ($usd_or_real == "usd") {
            $USD_usd = "$";
            $total = $invoice_details["saleprice"] * $invoice_details["qty"];
            $saleprice = $invoice_details["saleprice"];
        } else {
            $USD_usd = "៛";
            $total = $invoice_details["saleprice"] * $invoice_details["qty"] * $exchange;;
            $saleprice = $invoice_details["saleprice"] * $exchange;;
        }

        $decreaseButton = '<button class="btn btn-danger btn-sm btn-decrease-quantiy" disabled>-</button>';
        if ($invoice_details["qty"] > 1) {
            $decreaseButton = '<button data-id="' . $invoice_details["id"] . '" class="btn btn-danger btn-sm btn-decrease-quantiy">-</button>';
        }

        $product_id = $invoice_details["product_id"];


        $tbl_product = query("SELECT * from tbl_product where pid= $product_id ");
        confirm($tbl_product);
        $row = $tbl_product->fetch_object();
        if ($row->m_price > 0) {
            $disabled = '';
        } else {
            $disabled = 'disabled';
        }

        if ($invoice_details["size"] == 'M') {
            $color = 'info';
        } else {
            $color = 'success';
        }

        $html .= '
        <tr>
            <td>' . $no . '</td>
            <td>' . $invoice_details["product_name"] . '</td>
            <td>' . $decreaseButton . ' ' . $invoice_details["qty"] . ' <button data-id="' . $invoice_details["id"] . '" class="btn btn-primary btn-sm btn-increase-quantiy">+</button></td>
            <td><button data-id="' . $invoice_details["id"] . '" class="btn btn-' . $color . ' btn-sm btn-size" ' . $disabled . '> ' . $invoice_details["size"] . '</button></td>
            <td>' . $saleprice . ' </td>
            <td>' . $total . '</td>';
        if ($invoice_details["status"] == "noConfirm") {
            $showBtnPayment = false;
            $html .= '<td><a data-id="' . $invoice_details["id"] . '" class="btn btn-danger btn-delete-saledetail"><i class="far fa-trash-alt"></a></td>';
        } else { // status == "confirm"
            $html .= '<td><i class="fas fa-check-circle"></i></td>';
        }
        $html .= '</tr>';
        $no++;
    }
    $html .= '</tbody></table></div>';

    $tbl_invoice = query("SELECT * from tbl_invoice where invoice_id=$invoice_id ");
    confirm($tbl_invoice);
    $row = $tbl_invoice->fetch_object();
    
    $change = query("SELECT * from tbl_change where aus='$aus'");
    confirm($change);
    $row_exchange = $change->fetch_object();
    $exchange = $row_exchange->exchange;
    $usd_or_real = $row_exchange->usd_or_real;

    if ($usd_or_real == "usd") {
        $USD_usd = "$";
        $totall = $USD_usd . number_format($row->total, 2);
    } else {
        $USD_usd = "៛";
        $totalll = $row->total * $exchange;;
        $totall = number_format($totalll) . $USD_usd;
    }

    $html .= '<hr>';
    $html .= '<h3>Total Amount: ' . $totall . '</h3>';

    if ($showBtnPayment) {
        $html .= '<button data-id="' . $invoice_id . '" data-totalAmount="' . $row->total . '" class="btn btn-success btn-block btn-payment" data-toggle="modal" data-target="#exampleModal">Payment</button>';
    } else {
        $html .= '<button data-id="' . $invoice_id . '" class="btn btn-warning btn-block btn-confirm-order">Confirm Order</button>';
    }


    return $html;
}
