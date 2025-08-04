<?php
require_once "../resources/config.php";
// Query ទាញផលិតផល
$aus = $_GET['aus'] ?? ''; 
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
if ($searchTerm != '') {
  $searchTerm = mysqli_real_escape_string($connection, $searchTerm);
  $result = query("SELECT product, stock, image FROM tbl_product WHERE aus ='$aus' AND category LIKE '%$searchTerm%' ORDER BY product ASC");
} else {
  $result = query("SELECT product, stock, image FROM tbl_product WHERE aus = '$aus' ORDER BY product ASC");
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>ផលិតផល IMOU</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
  <style>
    
@font-face {
    font-family: "tong";
    src: url(../fone/KhmerOSbattambang.ttf)format("truetype");
}

@font-face {
    font-family: "KhmerOSMoulLight";
    src: url(../fone/KhmerOSMoulLight.ttf)format("truetype");
}
    .product-img {
      width: 100%;
      height: auto;
      max-height: 250px;
      object-fit: contain;
      /* ឲ្យរូបនៅក្នុងក្រឡាដែលមានទំហំកំណត់ */
      background-color: #f8f9fa;
      border-radius: 0.5rem;
    }

    .card-title {
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      font-family: 'tong';
    }
    .card-body .mb-0 {
      font-family: 'tong';
    }
  </style>
</head>

<body class="hold-transition layout-top-nav">
  <div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
      <div class="container">
        <a href="#" class="navbar-brand">
          <i class="fas fa-camera"></i>
          <span class="brand-text font-weight-light">ផលិតផល IMOU</span>
        </a>
      </div>
    </nav>

    <!-- Content -->
    <div class="content-wrapper">
      <div class="content">
        <div class="container pt-3">
          <div class="row">
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
              $name = $row['product'];
              $stock = $row['stock'];
              $img = $row['image'] ? "../productimages/" . $row['image'] : "https://via.placeholder.com/200";

              $badge = $stock == 0 ? "<span class='badge badge-danger'>អស់ស្តុក</span>" : ($stock <= 5 ? "<span class='badge badge-warning'>ស្តុកតិច ($stock)</span>" :
                "<span class='badge badge-success'>$stock ក្នុងស្តុក</span>");

              echo "
               <div class='col-lg-3 col-md-4 col-sm-6 col-12 mb-4'>
                 <div class='card h-100 shadow-sm'>
                   <img src='$img' class='product-img card-img-top'>
                   <div class='card-body'>
                     <h6 class='card-title'>$name</h6>
                     <p class='mb-0'>$badge</p>
                   </div>
                 </div>
               </div>
             ";
            }

            ?>
          </div>
        </div>
      </div>
    </div>

    <!-- Footer -->
    <footer class="main-footer text-center">
      <strong>&copy; 2025</strong> - POS Camera System
    </footer>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>

</html>