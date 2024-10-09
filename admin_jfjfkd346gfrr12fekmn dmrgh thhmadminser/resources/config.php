<?php
ob_start();

session_start();
// session_destroy();

defined("DS") ? null : define("DS", DIRECTORY_SEPARATOR);

defined("TEMPLATE_FRONT") ? null : define("TEMPLATE_FRONT", __DIR__ . DS . "templates/front");

defined("TEMPLATE_BACK") ? null : define("TEMPLATE_BACK", __DIR__ . DS . "templates/back");
defined("UPLOAD_DIRECTORY_UDER") ? null : define("UPLOAD_DIRECTORY_UDER", "../productimages/user/");

defined("UPLOAD_DIRECTORY") ? null : define("UPLOAD_DIRECTORY", __DIR__ . DS . "uploads");


// defined("UPLOAD_DIRECTORY") ? null : define("UPLOAD_DIRECTORY", "../resources/");


defined("DB_HOST") ? null : define("DB_HOST", "localhost");

defined("DB_USER") ? null : define("DB_USER", "root");


defined("DB_PASS") ? null : define("DB_PASS", "");

defined("DB_NAME") ? null : define("DB_NAME",  "restaurant_pos");


$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

require_once("functions.php");
// require_once("templates/getMenuByCategory.php");



// /////////////////////////// Coffee



defined("DB_HOSTC") ? null : define("DB_HOSTC", "localhost");

defined("DB_USERC") ? null : define("DB_USERC","root");


defined("DB_PASSC") ? null : define("DB_PASSC", "");

defined("DB_NAMEC") ? null : define("DB_NAMEC",  "coffee_db");


$connectionC = mysqli_connect(DB_HOSTC,DB_USERC,DB_PASSC,DB_NAMEC);