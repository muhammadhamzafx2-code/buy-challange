<?php
require "config.php";

// check price
if (!isset($_GET['price'])) {
    die("Price missing");
}

$price   = floatval($_GET['price']);
$type    = $_GET['type'] ?? "Challenge";
$balance = $_GET['balance'] ?? "";

// payment request
$data = [
    "price_amount" => $price,
    "price_currency" => "usd",
    "pay_currency" => "usdt",   // required for your account
    "order_id" => "XV" . time(),
    "order_description" => "$type account - $balance USD",
    "success_url" => SUCCESS_URL,
    "cancel_url" => CANCEL_URL
];

// send request
$ch = curl_init("https://api.nowpayments.io/v1/payment");

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "x-api-key: " . NOWPAY_API_KEY
]);

$response = curl_exec($ch);
curl_close($ch);

$result = json_decode($response, true);

// redirect to checkout page
if (isset($result['invoice_url'])) {
    header("Location: " . $result['invoice_url']);
    exit;
}

// show error if something fails
echo "<h2>Payment Error</h2>";
echo "<pre>";
print_r($result);
echo "</pre>";
exit;
?>
