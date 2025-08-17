<?php
require_once("../../config.php");



if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $select = query("SELECT * from tbl_service where id= '{$id}'");
    confirm($select);

    $no = 1;
    while ($row = $select->fetch_object()) {

        $price = $row->price;
        $tim = $row->tim;
        $qrcode = $row->qrcode;
    }
}


?>

<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">QR Code $<?php echo $price ?></h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <img width="200" src="logo/qrc/<?php echo $qrcode ?>" alt="">
                 <!-- <div class="modal-body">
                    <canvas id="qrCodeCanvas"></canvas>
                 </div> -->
                <div class="form-group">
                    <label >បញ្ចូលលេខយោង<p class="text-danger" style="display: inline-block;">*</p></label>
                    <input type="number" class="form-control" placeholder="សុំបញ្ចូលលេខយោង" name="numberidpay" autocomplete="off" required>
                    <input type="hidden" class="form-control" placeholder="សុំបញ្ចូលលេខយោង" name="price" value="<?php echo $price ?>">
                    <input type="hidden" class="form-control" placeholder="សុំបញ្ចូលលេខយោង" name="tim" value="<?php echo $tim ?>">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>សុំបញ្ចូល រូបភាព<p class="text-danger" style="display: inline-block;">*</p></label>
                    <input type="file" class="input-group" name="file" onchange="displayImage_pay(this)" id="profilImge_pay" required>
                    <img src="logo/qrc/qrcdoe.jpg" onclick="triggerClick_pay()" id="profiledisplay_pay" data-toggle="tooltip"  title="ចុចទីនេះដើម្បីបញ្ចូលរូបភាព" >
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button id="<?php echo $id?>" type="submit" class="btn btn-primary" value="<?php echo $id?>" name="payuser">Save Pay</button>
    </div>
</form>

<script>
  $(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip();
  });
</script>