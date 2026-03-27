<?php
require "config.php";

// Disable output to avoid header warnings
ob_start();

// Get data from previous page
$price = isset($_GET['price']) ? (float)$_GET['price'] : 0;
$balance = isset($_GET['balance']) ? $_GET['balance'] : '';
$type = isset($_GET['type']) ? $_GET['type'] : 'Challenge';

// Prepare NOWPayments API request
$data = [
    "price_amount" => $price,
    "price_currency" => "usd",   // Keep USD; users can pay in any currency
    "order_id" => "XV".rand(10000,99999),
    "order_description" => "$type account - $balance USD",
    "success_url" => SUCCESS_URL,
    "cancel_url" => CANCEL_URL
];

// cURL request
$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => "https://api.nowpayments.io/v1/payment",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "x-api-key: ".NOWPAY_API_KEY,
        "Content-Type: application/json"
    ],
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode($data)
]);

$response = curl_exec($curl);
$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);

$result = json_decode($response, true);

// Stop output buffering before redirect
ob_end_clean();

// Check for valid response
if ($http_code != 200 || !$result) {
    echo "<h2>Payment Error</h2>";
    echo "<p>HTTP code: $http_code</p>";
    echo "<pre>";
    print_r($result ? $result : $response);
    echo "</pre>";
    exit;
}

// Redirect to checkout
if (!empty($result['invoice_url'])) {
    header("Location: ".$result['invoice_url']);
    exit;
}

if (!empty($result['payment_url'])) {
    header("Location: ".$result['payment_url']);
    exit;
}

// If no URL, show raw response for debugging
echo "<h2>Payment Error: Unexpected response</h2>";
echo "<pre>";
print_r($result);
echo "</pre>";
exit;
