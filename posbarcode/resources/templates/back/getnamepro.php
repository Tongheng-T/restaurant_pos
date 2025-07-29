<?php

include_once '../../config.php';

$barcode = $_GET["id"];
$aus = $_SESSION['aus'];

$query =  query("SELECT * from tbl_product WHERE aus=$aus and barcode = $barcode");
confirm($query);
$roww = $query->fetch_assoc();
$pid = $roww['pid'];

?>


<option value="" disabled selected>Select Product</option>

<?php
$select = query("SELECT * from tbl_product where aus=$aus order by pid desc");
confirm($select);

while ($row = $select->fetch_assoc()) {

?>
    <option value="<?php echo $row['pid']; ?>" <?php if ($row['pid'] == $pid) { ?> selected="selected" <?php } ?> ><?php echo $row['product']; ?></option>

<?php

}

?>