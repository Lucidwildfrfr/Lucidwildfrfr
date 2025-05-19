<?php
// Database connection settings
$host = "sql.freedb.tech"; // or your host
$user = "freedb_Fusion Hub";
$pass = "*xJUxFNCm%$a9R$";
$db   = "freedb_Fusion Hub";

// Connect to database
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("DB Connection failed: " . $conn->connect_error);
}

// How many keys per type
$one_week  = 75;
$one_month = 75;
$lifetime  = 50;

// Helper: Generate unique 12-digit code
function generateKey($used_keys) {
    do {
        $key = "";
        for ($i = 0; $i < 12; $i++) {
            $key .= mt_rand(0,9);
        }
    } while (isset($used_keys[$key]));
    return $key;
}

$used_keys = [];
$types = [
    ['count' => $one_week,  'type' => '1week'],
    ['count' => $one_month, 'type' => '1month'],
    ['count' => $lifetime,  'type' => 'lifetime']
];

foreach ($types as $info) {
    for ($i = 0; $i < $info['count']; $i++) {
        $key = generateKey($used_keys);
        $used_keys[$key] = true;

        $stmt = $conn->prepare("INSERT INTO fusion_keys (`code`, `used`, `type`) VALUES (?, 0, ?)");
        $stmt->bind_param("ss", $key, $info['type']);
        $stmt->execute();
        $stmt->close();
        echo "Inserted key: $key (" . $info['type'] . ")<br>\n";
    }
}

echo "<br>All keys inserted!";
$conn->close();
?>