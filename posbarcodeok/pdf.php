
<?php require_once "resources/config.php"; ?>
<?php

require_once __DIR__ . '/vendor/autoload.php'; // mpdf path

use Mpdf\Mpdf;

$defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
$fontDirs = $defaultConfig['fontDir'];

$defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
$fontData = $defaultFontConfig['fontdata'];

$mpdf = new Mpdf([
    'fontDir' => array_merge($fontDirs, [__DIR__ . '/fone']),
    'fontdata' => $fontData + [
        'nokora' => [  // ← key to use as font-family
            'R' => 'Nokora.ttf',
        ],
    ],
    'default_font' => 'nokora', // ← set as default Khmer font
]);


$html = '

<style>
body {
    font-family: nokora;
    font-size: 14pt;
}
</style>
<h2 style="text-align:center;">Stock Report</h2>
<table border="1" cellspacing="0" cellpadding="5" style="border-collapse: collapse; width: 100%;  sans-serif;">
    <thead style="background-color: #f2f2f2;">
        <tr>
            <th>ល.រ</th>
            <th>ឈ្មោះផលិតផល</th>
            <th>Barcode</th>
            <th>Category</th>
            <th>Purchase Price</th>
            <th>Sale Price</th>
            <th>Image</th>
            <th>បរិមាណសរុប (Stock)</th>
            <th>តម្លៃសរុប (Stock × Price)</th>
        </tr>
    </thead>
    <tbody>
';

$selectstock = query("
    SELECT 
        p.product AS product_name, 
        p.barcode,
        p.category,
        p.purchaseprice,
        p.saleprice,
        p.image,
        s.product_id,
        SUM(s.stock) AS total_stock, 
        SUM(s.stock * s.price) AS total_value
    FROM tbl_product_stock s
    JOIN tbl_product p ON s.product_id = p.pid
    WHERE s.aus = '1736279042'
    GROUP BY s.product_id
    ORDER BY p.product ASC
");

$i = 1;
$grand_total_value = 0;

while ($row = $selectstock->fetch_object()) {
    $html .= '<tr>';
    $html .= '<td>' . $i . '</td>';
    $html .= '<td>' . htmlspecialchars($row->product_name) . '</td>';
    $html .= '<td>' . htmlspecialchars($row->barcode) . '</td>';
    $html .= '<td>' . htmlspecialchars($row->category) . '</td>';
    $html .= '<td style="text-align: right;">$ ' . number_format($row->purchaseprice, 2) . '</td>';
    $html .= '<td style="text-align: right;">$ ' . number_format($row->saleprice, 2) . '</td>';

    // Image (real embed)
    $image_path = __DIR__ . '/productimages/' . $row->image; // Ex: 'uploads/image1.jpg'
    if (file_exists($image_path)) {
        $html .= '<td><img src="' . $image_path . '" style="width:50px; height:auto;"></td>';
    } else {
        $html .= '<td>No Image</td>';
    }

    $html .= '<td style="text-align: right;">' . number_format($row->total_stock) . '</td>';
    $html .= '<td style="text-align: right;">$ ' . number_format($row->total_value, 2) . '</td>';
    $html .= '</tr>';

    $grand_total_value += $row->total_value;
    $i++;
}

$html .= '</tbody>
<tfoot>
    <tr style="font-weight: bold; background-color: #f9f9f9;">
        <td colspan="8" style="text-align: right;">សរុបតម្លៃ (Grand Total):</td>
        <td style="text-align: right;">$ ' . number_format($grand_total_value, 2) . '</td>
    </tr>
</tfoot>
</table>
';

// Generate PDF
$mpdf->WriteHTML($html);
$mpdf->Output('stock_report.pdf', 'I'); // download
