<?php require_once("../../config.php"); ?>
<?php
// $tbl_setting = query("SELECT * from tbl_setting");
// confirm($tbl_setting);
// $rowd = $tbl_setting->fetch_object(); 
?>


                    <?php $date_1 = $_SESSION['date_1'];
                    $date_2 = $_SESSION['date_2'];


                    function show_customer_name()
                    {
                        $id = $_GET['id'];
                        $select = query("select * from tbl_invoice where invoice_id = $id");
                        confirm($select);
                        $row = $select->fetch_object();
                        $customer_name = $row->customer_name;
                        $invoice_id = $row->invoice_id;
                        echo 'N0 ' . $invoice_id . ' _ ' . $customer_name;
                    } ?>


        <?php
        $select = query("SELECT sum(total) as grandtotal , sum(subtotal) as stotal, count(invoice_id) as invoice , sum(discount) as discount_total from tbl_invoice where order_date between '$date_1' AND '$date_2'");
        confirm($select);

        $row = $select->fetch_object();
        $messaggio = '';
        $grand_total = $row->grandtotal;
        $subtotal = $row->stotal;
        $invoice = $row->invoice;
        $Discount_total = $row->discount_total;
        $monthly = date('m', strtotime($date_1));


        $messaggio .= '
របាយការណ៍ការប្រចាំខែ ' . convert_month_kh($monthly) . '
ចាប់ពីថ្ងៃទី ' . date('d-m-Y', strtotime($date_1)) . ' ដល់ថ្ងៃទី ' . date('d-m-Y', strtotime($date_2));

        $messaggio .= '
ចំនួនវិក័យប័ត្រ ' . $invoice . ' | សរុប= ' . number_format($subtotal) . '៛  
Discount= ' . number_format($Discount_total) . '៛ | សរុបដក់Discount= ' . number_format($grand_total).'៛';

        ?>




        <?php
        // $date_2 = $_POST['id'];
        // $token = "6434881251:AAFv0b1v3Vf1DCqIRkGekE4NC7tyQ7hxKvk";

        // $date = [
        //     'text' => $oupu,
        //     'chat_id' => '717019581'
        // ];

        $chatID = 717019581;

        $token = "6377409643:AAH4sIu37aQN-8D3GBIx_Htz7pNAwcKPUJg";

        sendMessage($chatID, $messaggio, $token);


        function sendMessage($chatID, $messaggio, $token)
        {
            echo "sending message to telegram \n";

            $url = "https://api.telegram.org/bot" . $token . "/sendMessage?chat_id=" . $chatID;
            $url = $url . "&text=" . urlencode($messaggio);
            $ch = curl_init();
            $optArray = array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true
            );
            curl_setopt_array($ch, $optArray);
            $result = curl_exec($ch);
            curl_close($ch);
            return $result;
        }
        ?>