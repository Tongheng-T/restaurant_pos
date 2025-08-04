<?php 
ob_start(); 
session_name("project2_session");
session_start();
// session_destroy();

defined("DS") ? null : define("DS", DIRECTORY_SEPARATOR);

defined("TEMPLATE_FRONT") ? null : define("TEMPLATE_FRONT", __DIR__ . DS . "templates/front");

defined("UPLOAD_DIRECTORY_UDER") ? null : define("UPLOAD_DIRECTORY_UDER", "../resources/images/userpic/");
defined("UPLOAD_DIRECTORY_IDPAY") ? null : define("UPLOAD_DIRECTORY_IDPAY", "../resources/images/userpay/");
defined("TEMPLATE_BACK") ? null : define("TEMPLATE_BACK", __DIR__ . DS . "templates/back");


defined("UPLOAD_DIRECTORY") ? null : define("UPLOAD_DIRECTORY", __DIR__ . DS . "uploads");


// defined("UPLOAD_DIRECTORY") ? null : define("UPLOAD_DIRECTORY", "../resources/");


defined("DB_HOST") ? null : define("DB_HOST", "localhost");

defined("DB_USER") ? null : define("DB_USER","pos_barcode");


defined("DB_PASS") ? null : define("DB_PASS", "mLZdbWHz5MECMSMG");

defined("DB_NAME") ? null : define("DB_NAME",  "pos_barcode_db");


$connection = mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);

require_once("functions.php");
require_once("functions_create.php");



// ob_start();

// session_start();

// defined("DS") ? null : define("DS", DIRECTORY_SEPARATOR);

// defined("TEMPLATE_FRONT") ? null : define("TEMPLATE_FRONT", __DIR__ . DS . "templates/front");
// defined("TEMPLATE_BACK") ? null : define("TEMPLATE_BACK", __DIR__ . DS . "templates/back");

// defined("UPLOAD_DIRECTORY_UDER") ? null : define("UPLOAD_DIRECTORY_UDER", "../resources/images/userpic/");
// defined("UPLOAD_DIRECTORY_IDPAY") ? null : define("UPLOAD_DIRECTORY_IDPAY", "../resources/images/userpay/");
// defined("UPLOAD_DIRECTORY") ? null : define("UPLOAD_DIRECTORY", __DIR__ . DS . "uploads");

// defined("DB_HOST") ? null : define("DB_HOST", "localhost");
// defined("DB_USER") ? null : define("DB_USER", "root");
// defined("DB_PASS") ? null : define("DB_PASS", "");
// defined("DB_NAME") ? null : define("DB_NAME", "pos_barcode_db");

// try {
//     $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS, [
//         PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,        // Error handling
//         PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,     // Default fetch
//         PDO::ATTR_EMULATE_PREPARES => false                 // Use real prepared statements
//     ]);
// } catch (PDOException $e) {
//     die("Connection failed: " . $e->getMessage());
// }

require_once("functions.php");
require_once("functions_create.php");
?>


