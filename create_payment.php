<?php
require "config.php";

// Prevent header errors
ob_start();

// Validate price
if (!isset($_GET['price'])) {
    die("Price not provided");
}

$price   = floatval($_GET['price']);
$type    = $_GET['type'] ?? "Challenge";
$balance = $_GET['balance'] ?? "";

// Create payment data
$data = [
    "price_amount" => $price,
    "price_currency" => "usd",          // your plan price
    "pay_currency" => "usdt",           // REQUIRED for your account (crypto only)
    "order_id" => "XV" . time(),
    "order_description" => "$type account - $balance USD",
    "success_url" => SUCCESS_URL,
    "cancel_url" => CANCEL_URL
];

// Start cURL
$ch = curl_init("https://api.nowpayments.io/v1/payment");

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "x-api-key: " . NOWPAY_API_KEY
]);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$result = json_decode($response, true);

// Clear output buffer (fix Render header error)
ob_end_clean();

// If payment created successfully
if ($http_code == 200 && isset($result['invoice_url'])) {
    header("Location: " . $result['invoice_url']);
    exit;
}

// If error happens
echo "<h2>Payment Error</h2>";
echo "<p>HTTP Code: $http_code</p>";
echo "<pre>";
print_r($result);
echo "</pre>";
exit;
?>
