<?php
// Secure access
if (!isset($_GET['token']) || $_GET['token'] !== 'MY_SECRET') {
    http_response_code(403);
     header('Location: https://thpos.store/');
    exit;
}

// Move to project root
chdir('/www/wwwroot/restaurant_pos');

// Auto pull
$output = shell_exec('git reset --hard && git pull origin main 2>&1');

// Save log
file_put_contents(__DIR__.'/webhook.log', date('Y-m-d H:i:s')."\n".$output."\n\n", FILE_APPEND);

echo "OK";
?>
