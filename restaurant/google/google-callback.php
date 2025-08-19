<?php
require_once '../vendor/autoload.php';

require_once "../resources/config.php"; // ត្រូវមាន function query() និង confirm()

$client = new Google_Client();
$client->setClientId('678847511198-c7rhe1h2udm2urs3j1hsul7srm0i0m1q.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('https://restaurant.thposs.uk/google/google-callback.php');
$client->addScope('email');
$client->addScope('profile');


if (isset($_GET['code'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ??
        $_SERVER['HTTP_CLIENT_IP'] ??
        $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
    $location = getLocationByIP($ip);
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    if (!isset($token['error'])) {
        $client->setAccessToken($token['access_token']);

        $google_oauth = new Google_Service_Oauth2($client);
        $user_info = $google_oauth->userinfo->get();

        $email = $user_info->email;
        $name = $user_info->name;

        // ពិនិត្យមើលថាតើមាន email រួចហើយទេ
        $check_user = query("SELECT * FROM tbl_user WHERE useremail = '$email'");
        confirm($check_user);

        if (mysqli_num_rows($check_user) > 0) {
            // ប្រើមានរួចហើយ -> login
            $row = $check_user->fetch_assoc();
            $_SESSION['userid'] = $row['user_id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['useremail'] = $row['useremail'];
            $_SESSION['role'] = $row['role'];
            $_SESSION['aus'] = $row['aus'];
            $_SESSION['location'] = $location;
            $_SESSION['login_type'] = 'google'; // អ្នកត្រូវ set វា
            $res = query("UPDATE tbl_user SET login_online='$time', last_login='$datee',last_ip = '$ip', location_ip = '$location' WHERE user_id=" . $_SESSION['userid']);

            header("Location: ../ui/"); // redirect to dashboard
            exit;
        } else {
            // ប្រើថ្មី -> បញ្ចូលទៅក្នុង db
            //  Free Trial Logic
            $tbl_date_free = query("SELECT number_of_date FROM tbl_date_free LIMIT 1");
            $row = $tbl_date_free->fetch_assoc();
            $numdatefree = $row['number_of_date'] ?? 30; // fallback if not set
            $code = rand(111111, 999999);
            $aus = $code + time();
            $createdate = (new DateTime('now', new DateTimeZone('Asia/Bangkok')))->format('Y-m-d H:i:s');
            $time = time() + 10;
            query("INSERT INTO tbl_user (username, useremail, createdate, date_new, code, aus, role, verified,tim,login_online,login_type,last_ip,location_ip)
                VALUES ('$name', '$email', '$createdate', '$createdate', '$code', '$aus', 'Admin', 2,$numdatefree,$time,'google','$ip','$location')");
            $uid = last_id();

            $_SESSION['userid'] = $uid;
            $_SESSION['username'] = $name;
            $_SESSION['useremail'] = $email;
            $_SESSION['role'] = 'Admin';
            $_SESSION['aus'] = $aus;

            // ចង់បញ្ចូល row default ទៅ tbl_change / tbl_taxdis / tbl_logo ដែរ
            query("INSERT INTO tbl_change (aus) VALUES('$aus')");
            query("INSERT INTO tbl_taxdis (aus) VALUES('$aus')");
            query("INSERT INTO tbl_logo (name, img, aus) VALUES('TH POS', 'logo.png', '$aus')");

            header('refresh:2;../ui/');
            exit;
        }
    } else {
        echo "Authentication failed: " . $token['error'];
        exit;
    }
} else {
    echo "Invalid request";
}
