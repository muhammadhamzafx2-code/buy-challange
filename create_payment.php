<?php
require "config.php";

ob_start();

if (!isset($_GET['price'])) {
    die("Price not provided");
}

$price   = floatval($_GET['price']);
$type    = $_GET['type'] ?? "Challenge";
$balance = $_GET['balance'] ?? "";

// Convert USD price to USDT (1 USDT ≈ 1 USD)
$usdt_price = $price;

$data = [
    "price_amount" => $usdt_price,
    "price_currency" => "usdt",   // VERY IMPORTANT FIX
    "order_id" => "XV" . time(),
    "order_description" => "$type account - $balance Account",
    "success_url" => SUCCESS_URL,
    "cancel_url" => CANCEL_URL
];

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

ob_end_clean();

if ($http_code == 200 && isset($result['invoice_url'])) {
    header("Location: " . $result['invoice_url']);
    exit;
}

echo "<h2>Payment Error</h2>";
echo "<p>HTTP Code: $http_code</p>";
echo "<pre>";
print_r($result);
echo "</pre>";
exit;
?>
