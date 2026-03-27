<?php
require "config.php";

// Prevent accidental output before header
ob_start();

// Get price and type from query
$price = isset($_GET['price']) ? (float)$_GET['price'] : 0;
$type = isset($_GET['type']) ? $_GET['type'] : 'Challenge';
$balance = isset($_GET['balance']) ? $_GET['balance'] : '';

// Prepare request for NOWPayments
$data = [
    "price_amount" => $price,
    "price_currency" => "usd",        // Price of plan in USD
    "order_id" => "XV".rand(10000,99999),
    "order_description" => "$type account - $balance USD",
    "success_url" => SUCCESS_URL,     // define in config.php
    "cancel_url" => CANCEL_URL        // define in config.php
];

// Send request via cURL
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

// Clear output buffer before redirect
ob_end_clean();

// Check response and redirect
if ($http_code === 200 && !empty($result['invoice_url'])) {
    header("Location: ".$result['invoice_url']);
    exit;
}

// If invoice_url is missing, show debug info
echo "<h2>Payment Error</h2>";
echo "<p>HTTP code: $http_code</p>";
echo "<pre>";
print_r($result ? $result : $response);
echo "</pre>";
exit;
