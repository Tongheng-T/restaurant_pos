<?php
ob_start();
session_name("project1_session");
session_start();
// session_destroy();

defined("DS") ? null : define("DS", DIRECTORY_SEPARATOR);

defined("TEMPLATE_FRONT") ? null : define("TEMPLATE_FRONT", __DIR__ . DS . "templates/front");

defined("TEMPLATE_BACK") ? null : define("TEMPLATE_BACK", __DIR__ . DS . "templates/back");
defined("UPLOAD_DIRECTORY_UDER") ? null : define("UPLOAD_DIRECTORY_UDER", "../resources/images/userpic/");
defined("UPLOAD_DIRECTORY_IDPAY") ? null : define("UPLOAD_DIRECTORY_IDPAY", __DIR__ . "/../resources/images/userpay/");

defined("UPLOAD_DIRECTORY") ? null : define("UPLOAD_DIRECTORY", __DIR__ . DS . "uploads");


// defined("UPLOAD_DIRECTORY") ? null : define("UPLOAD_DIRECTORY", "../resources/");


defined("DB_HOST") ? null : define("DB_HOST", "localhost");

defined("DB_USER") ? null : define("DB_USER", "root");


defined("DB_PASS") ? null : define("DB_PASS", "");

defined("DB_NAME") ? null : define("DB_NAME",  "restaurant_pos");


$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

require_once("functions.php");
require_once("functionspay.php");
require_once("functions_qr.php");

// require_once("templates/getMenuByCategory.php");
