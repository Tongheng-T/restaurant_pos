<?php
require_once __DIR__ . '/vendor/autoload.php';

use Mpdf\Mpdf;

$defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
$fontDirs = $defaultConfig['fontDir'];

$defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
$fontData = $defaultFontConfig['fontdata'];


// Init mPDF with thermal printer size
$mpdf = new Mpdf([
    'format' => [80, 200], // 80mm width x 200mm height (auto for long receipts)
    'margin_left' => 2,
    'margin_right' => 2,
    'margin_top' => 2,
    'margin_bottom' => 2,
    'fontDir' => array_merge($fontDirs, [__DIR__ . '/fone']),
    'fontdata' => $fontData + [
        'nokora' => ['R' => 'Nokora.ttf'],
    ],
    'default_font' => 'nokora',
]);

// HTML content
$html = '
<style>
body {
    font-family: nokora;
    font-size: 10pt;
    margin: 0;
}
table {
    width: 100%;
}
.text-center {
    text-align: center;
}
</style>

<div class="text-center">
    <h3>🍜 ហាងម្ហូបខ្មែរ</h3>
    <p>លេខវិក័យប័ត្រ: INV123456</p>
    <p>ថ្ងៃខែ៖ ' . date('d-m-Y H:i') . '</p>
</div>

<table cellspacing="0" cellpadding="4">
    <tr>
        <td>កាហ្វេ</td><td align="right">2 x $1.50</td><td align="right">$3.00</td>
    </tr>
    <tr>
        <td>នំដូស</td><td align="right">1 x $2.00</td><td align="right">$2.00</td>
    </tr>
    <tr>
        <td colspan="3"><hr></td>
    </tr>
    <tr>
        <td colspan="2" align="right"><strong>សរុប</strong></td>
        <td align="right"><strong>$5.00</strong></td>
    </tr>
</table>

<p class="text-center">🙏 សូមអរគុណចំពោះការជាវ!</p>
';

$mpdf->WriteHTML($html);
$mpdf->Output('pos_receipt.pdf', 'I'); // I = inline preview, D = force download
