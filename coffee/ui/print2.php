<?php require_once("../resources/config.php"); ?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- <xsl:attribute name="page-height">200cm</xsl:attribute> -->
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <!-- Custom Style -->
    <link rel="stylesheet" href="../dist/css/styleee.css">
    <link href="../dist/css/printt.css" rel="stylesheet" media="print">

    <title>របាយការណ៍ការលក់ប្រចាំខែ: <?php date_rankk(); ?></title>
</head>

<body>
    <div class="my-5 page" size="A4" id="example-table">
        <div class="p-5">
            <section class="top-content bb d-flex justify-content-between">
                <div class="logo">
                    <img src="../productimages/logo/a.png" alt="" class="img-fluid">
                    <!-- <h5>ក្រសួងអប់រំ យុវជន និងកីឡា</h5> -->
                    <br>
                    <h5 class="h5">មរតក កាហ្វេ</h5>
                </div>
                <div class="top-left">
                    <div class="graphicc-path">
                        <?php $date_1 = $_SESSION['date_1'];
                        $date_2 = $_SESSION['date_2']; ?>
                        <!-- <h4>ព្រះរាជាណាចក្រកម្ពុជា</h4>
                        <h4 class="margino">ជាតិ សាសនា ព្រះមហាក្សត្រ</h4> -->
                        <b>ចាប់ពីថ្ងៃទី: <?php echo date('d-m-Y', strtotime($date_1)) ?><br> ដល់ថ្ងៃទី: <?php echo date('d-m-Y', strtotime($date_2)) ?></b>
                    </div>
                </div>
            </section>
            <div class="dddc">
                <h5>របាយការណ៍ការលក់ប្រចាំខែ <?php date_rank(); ?></h5>
            </div>

            <section class="product-area mt-4">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ល.រ</th>
                            <th>ឈ្មោះ​ទំនិញ</th>
                            <th>ចំនួន</th>
                            <th>តម្លៃឯកតា</th>
                            <th>តម្លៃសរុប</th>
                            <th>Discount</th>
                            <th>សរុប</th>
                            <th>ថ្ងៃខែ</th>
                            <th>ប្រភេទការទូទាត់</th>
                        </tr>
                    </thead>
                    <tbody id="orderlisttable">
                        <?php
                        $select = query("SELECT * from tbl_invoice where order_date between '$date_1' AND '$date_2'");
                        confirm($select);
                        $oupu = "";
                        $totall = 0;
                        $no = 1;
                        $total_sub_f = 0;
                        $total_discount = 0;
                        $total_qty = 0;
                        while ($row = $select->fetch_object()) {
                            $id_invoice = $row->invoice_id;
                            $row_invoice_detaice = query("SELECT * from tbl_invoice_details where invoice_id ='$id_invoice'");
                            confirm($row_invoice_detaice);



                            while ($row_detaice = $row_invoice_detaice->fetch_object()) {
                                $product_name = show_productname($row_detaice->product_id);
                                $oupu .= '
                                <tr>
                                <td>' . $no . '</td>';

                                $oupu .= ' 
                                <td>' . $product_name . '</td>
                                <td>' . number_format($row_detaice->qty) . '</td>
                                <td>' . number_format($row_detaice->rate) . '</td>
                                <td>' . number_format($row_detaice->rate * $row_detaice->qty) . '</td>
                                
                                ';
                                $total_sub = $row_detaice->rate * $row_detaice->qty;
                                $subtotal_qty = $row_detaice->qty;

                                $total_sub_f += $total_sub;

                                $total_qty += $subtotal_qty;
                            }
                            $oupu .= '

                                <td>' . number_format($row->discount) . '</td>
                                <th><span class="label label-danger">' . number_format($row->total) . ' <b style="font-size: 16px;">&#x17DB </b></span></th>
                                <td>' . date('d-m-Y', strtotime($row->order_date)) . '</td>
                            ';

                            if ($row->payment_type == "Cash") {

                                $oupu .=  '<td><span class="label label-primary">' . $row->payment_type . '</span></td></tr>';
                            } elseif ($row->payment_type == "Card") {
                                $oupu .=  '<td><span class="label label-warning">' . $row->payment_type . '</span></td></tr>';
                            } else {
                                $oupu .=  '<td><span class="label label-info">' . $row->payment_type . '</span></td></tr>';
                            }
                            $no++;
                            $totall += $row->total;

                            $total_discount += $row->discount;

                        }

                        $oupu .=
                            '<tr>
                        <td colspan="1"></td>
                        
                        <th>សរុប</th>
                        <th>' . $total_qty . '</th>
                        <td colspan="1"></td>
                        <th>' . number_format($total_sub_f) . ' <b style="font-size: 16px;">&#x17DB </b></th>
                        <th>' . number_format($total_discount) . ' <b style="font-size: 16px;">&#x17DB </b></th>
                        <th>' . number_format($totall) . ' <b style="font-size: 16px;">&#x17DB </b></th>
                        </tr>
                         ';
                        echo $oupu;


                        ?>

                    </tbody>
                </table>
            </section>

            <script>
                window.print();
            </script>


        </div>
    </div>


</body>

</html>