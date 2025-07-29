<?php
// db.php or config.php
$host = 'localhost';
$dbname = 'pos_barcode_db';
$user = 'root';
$pass = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

function query($sql, $params = [])
{
    global $conn; // ប្រាកដថា $conn គឺ PDO object
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    return $stmt;
}

?>
