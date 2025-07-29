

<?php
// webhook.php

// --- Security Check: GitHub Secret (បើមាន) ---
$secret = "YOUR_SECRET_KEY"; // ចូលទៅ GitHub Webhook settings បន្ថែម secret ដូចគ្នា

$headers = getallheaders();
$payload = file_get_contents('php://input');
$signature = "sha1=" . hash_hmac('sha1', $payload, $secret);

if (!isset($headers['X-Hub-Signature']) || $headers['X-Hub-Signature'] !== $signature) {
    http_response_code(403);
    echo "Forbidden";
    exit;
}

// --- Change to project directory ---
chdir('/www/wwwroot/restaurant_pos');

// --- Run git reset and pull ---
$output = shell_exec('git reset --hard && git pull origin main 2>&1');

// --- Save log ---
file_put_contents(__DIR__ . '/webhook.log', date('Y-m-d H:i:s') . "\n" . $output . "\n\n", FILE_APPEND);

echo "OK\n";
echo nl2br($output);
?>
