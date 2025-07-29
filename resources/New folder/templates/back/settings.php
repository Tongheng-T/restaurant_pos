<?php
$aus = $_SESSION['aus'];
$user = $_SESSION['userid'];
$selectk = query("SELECT * from tbl_user where user_id = '$user'");
$rows = $selectk->fetch_object();
$username = $rows->username;
$useremail = $rows->useremail;
$createdate = $rows->createdate;
$aus = $rows->aus;
$tim = $rows->tim;
$img = $rows->img;
$lat = $rows->lat;
$lng = $rows->lng;

$date_1 = date("d-m-Y", strtotime($rows->birthday));

if (isset($_POST['savemap'])) {
    $lat = $_POST['lat'];
    $lng = $_POST['lng'];

    if (!empty($lng)) {
        $update = query("UPDATE tbl_user set lat='$lat', lng='$lng' where user_id='$user'");
        confirm($update);
        if ($update) {

            set_message(' <script>
            Swal.fire({
            position: "top-end",
            icon: "success",
            title: "Your work has been saved",
            showConfirmButton: false,
            timer: 1500
            });
          </script>');
            redirect('itemt?settings');
        }
    } else {

        redirect('itemt?settings');
    }
}

save_st();
// display_message();
changepasswordd();

$change = "";
$cc = $_SESSION['change'];
if (($cc == 'active')) {
    $change = 'active';
    $changee = 'active show';
} else {
    $general = 'active';
    $generall = 'active show';
}

?>


<section class="content container-fluid">
    <div class="box box-warning">
        <div class="card card-danger card-outline">
            <div class="card-header with-border">
                <h5 class="box-title">Account settings</h5>
            </div>
            <!-- daterange picker -->
            <link rel="stylesheet" href="../plugins/daterangepicker/daterangepicker.css">

            <!-- Tempusdominus Bootstrap 4 -->
            <link rel="stylesheet" href="../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">

            <div class="card overflow-hidden">
                <div class="row no-gutters row-bordered row-border-light">
                    <div class="col-md-3 pt-0">
                        <div class="list-group ">
                            <a class="list-group-item list-group-item-action <?php echo $general ?>" data-toggle="list" href="#account-general">General </a>
                            <?php if ($_SESSION['login_type'] != 'google'): ?>
                                <a class="list-group-item list-group-item-action <?php echo $change ?>" data-toggle="list" href="#account-change-password">Change password</a>
                            <?php endif; ?>

                            <a class="list-group-item list-group-item-action" data-toggle="list" href="#account-social-links">Location</a>

                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="tab-content">

                            <div class="tab-pane fade <?php echo $generall ?>" id="account-general">
                                <form action="" method="post" name="formeditproduct" enctype="multipart/form-data">
                                    <div class="card-body media align-items-center">
                                        <div class="form-group">
                                            <label>រូបថត រូបភាព</label>
                                            <input type="file" class="input-group" name="file" onchange="displayImage(this)" id="profilImg">
                                            <img src="../resources/images/userpic/<?php echo $img ?> " onclick="triggerClick()" id="profiledisplay">
                                        </div>
                                        <div class="media-body ml-4">

                                            <div class="text-lightt small mt-1">Allowed JPG, GIF or PNG. Max size of 800K</div>
                                            <?php echo $img ?>
                                        </div>
                                    </div>
                                    <hr class="border-light m-0">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label class="form-label">Username</label>
                                            <input type="text" class="form-control mb-1" name="username" value="<?php echo $username ?>">
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label">E-mail</label>
                                            <input type="text" class="form-control mb-1" readonly value="<?php echo $useremail ?>">

                                        </div>


                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label class="form-label">Birthday</label>
                                                <div class="input-group date" id="date_11" data-target-input="nearest">
                                                    <input type="text" class="form-control date_11" data-target="#date_11" name="birthday" value="<?php echo $date_1; ?>" data-date-format="dd-mm-yyyy">
                                                    <div class="input-group-append" data-target="#date_11" data-toggle="datetimepicker">
                                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Company</label>

                                            <select class="form-control select2" data-dropdown-css-class="select2-purple" name="province" style="width: 100%;">
                                                <option>Select OR Search</option><?php echo ket(); ?>

                                                <?php
                                                $a = array("ខេត្តបាត់ដំបង", "ខេត្តកំពង់ចាម", "ខេត្តកំពង់ឆ្នាំង", "ខេត្តព្រះសីហនុ (កំពង់សោម)", "ខេត្តកំពង់ស្ពឺ", "ខេត្តកំពង់ធំ", "ខេត្តកំពត", "ខេត្តកោះកុង", "ខេត្តក្រចេះ", "ខេត្តប៉ៃលិន", "រាជធានីភ្នំពេញ", "ខេត្តតាកែវ", "ខេត្តព្រៃវែង", "ខេត្តពោធិ៍សាត់", "ខេត្តសៀមរាប", "ខេត្តស្ទឹងត្រែង", "ខេត្តស្វាយរៀង", "ខេត្តឧត្ដរមានជ័យ", "ខេត្តព្រះវិហារ", "ខេត្តបន្ទាយមានជ័យ", "ខេត្តកណ្តាល", "ខេត្តរតនៈគិរី", "ខេត្តមណ្ឌលគិរី", "ខេត្តត្បូងឃ្មុំ", "ខេត្តកែប");

                                                foreach ($a as $key => $value):

                                                ?>
                                                    <option <?php if ($key == $rows->location) { ?> selected="selected" <?php } ?> value="<?php echo $key ?>"><?php echo $value ?></option>

                                                <?php endforeach; ?>
                                            </select>

                                        </div>
                                    </div>

                                    <div class="text-right mt-3">
                                        <button type="submit" class="btn btn-primary" name="save_st">Save changes</button>&nbsp;
                                        <button type="button" class="btn btn-default">Cancel</button>
                                    </div>

                                </form>
                            </div>

                            <div class="tab-pane fade <?php echo $changee ?>" id="account-change-password">
                                <form class="form-horizontal" action="" method="post">
                                    <div class="card-body pb-2">
                                        <div class="form-group">
                                            <label class="form-label">Current password</label>
                                            <input type="password" class="form-control" placeholder=" Old Password" name="txt_oldpassword">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">New password</label>
                                            <input type="password" class="form-control" placeholder="New Password" name="txt_newpassword">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Repeat new password</label>
                                            <input type="password" class="form-control" placeholder="Repeate New Password" name="txt_rnewpassword">
                                        </div>
                                    </div>
                                    <div class="text-right mt-3">
                                        <button type="submit" class="btn btn-primary" name="btnupdate">Save changes</button>&nbsp;
                                        <button type="button" class="btn btn-default">Cancel</button>
                                    </div>
                                </form>
                            </div>

                            <div class="tab-pane fade" id="account-social-links">
                                <div class="card-body pb-2">
                                    <div class="form-group">
                                        <label class="form-label"> 📍 Your Location on Map (OSM)</label>
                                        <div class="content">
                                            <form method="post">
                                                <div class="row mb-3">
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control" placeholder="Latitude" name="lat" id="lat" value="<?php echo $lat ?>">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control" placeholder="Longitude" name="lng" id="lng" value="<?php echo $lng ?>">
                                                    </div>
                                                </div>

                                                <div id="map" style="height: 400px;" class="rounded shadow-sm mb-3"></div>

                                                <button type="submit" name="savemap" class="btn btn-primary">Save Location</button>
                                            </form>



                                        </div>
                                    </div>

                                </div>
                            </div>


                            <script>
                                let lat = <?php echo $lat ?> || 11.5564;
                                let lng = <?php echo $lng ?> || 104.9282;

                                setMap(lat, lng);

                                function setMap(lat, lng) {
                                    const map = L.map('map').setView([lat, lng], 13);

                                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                        maxZoom: 19,
                                        attribution: '&copy; OpenStreetMap contributors'
                                    }).addTo(map);

                                    const marker = L.marker([lat, lng]).addTo(map)
                                        .bindPopup("You are here!").openPopup();

                                    map.on('click', function(e) {
                                        document.getElementById('lat').value = e.latlng.lat;
                                        document.getElementById('lng').value = e.latlng.lng;

                                        marker.setLatLng(e.latlng)
                                            .bindPopup("You picked here!")
                                            .openPopup();
                                    });
                                }
                            </script>

                        </div>
                    </div>
                </div>
            </div>

            <br>
        </div>
    </div>
</section>


<h1>ស្កេន QR Code</h1>
<button onclick="validateLocationBeforeScan()">📍 ស្កេន QR</button>

<script>
    const allowedLat = <?php echo $lat ?>; // ← ជំនួសជាទីតាំងកំណត់let lat = 
    const allowedLng = <?php echo $lng ?>;
    const allowedRadius = 0.3; // គីឡូម៉ែត្រ

    function haversineDistance(lat1, lng1, lat2, lng2) {
        const R = 6371; // Earth radius in KM
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLng = (lng2 - lng1) * Math.PI / 180;
        const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
            Math.sin(dLng / 2) * Math.sin(dLng / 2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        return R * c;
    }

    function validateLocationBeforeScan() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const userLat = position.coords.latitude;
                const userLng = position.coords.longitude;

                const distance = haversineDistance(userLat, userLng, allowedLat, allowedLng);

                if (distance > allowedRadius) {
                    alert("⛔ មិនអាចប្រើ QR នៅទីតាំងនេះ! សូមមកទីតាំងដែលបានអនុញ្ញាត។");
                } else {
                    startQRScan();
                }
            }, function(error) {
                alert("សូមអនុញ្ញាតទីតាំង (GPS) ដើម្បីបញ្ចូលប្រព័ន្ធ");
            });
        } else {
            alert("Browser មិនគាំទ្រ GPS");
        }
    }

    function startQRScan() {
        alert("✅ Start scanning QR...");
        // QR scan code here (add your real QR scan script)
    }

</script>