<?php
require "config.php";

// Get data from previous page
$price = isset($_GET['price']) ? (float)$_GET['price'] : 0;
$email = isset($_GET['email']) ? $_GET['email'] : '';
$balance = isset($_GET['balance']) ? $_GET['balance'] : '';
$type = isset($_GET['type']) ? $_GET['type'] : 'Challenge';

// Prepare NOWPayments API request
$data = [
    "price_amount" => $price,
    "price_currency" => "usd",   // Keep USD, NOWPayments allows user to pay in any currency automatically
    "pay_currency" => "usdt",    // Optional: can be left empty, users can still pay with card or other crypto
    "order_id" => "XV".rand(10000,99999),
    "order_description" => "$type account - $balance USD",
    "success_url" => SUCCESS_URL,
    "cancel_url" => CANCEL_URL,
    "buyer_email" => $email
];

// Initialize cURL
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

// Decode response
$result = json_decode($response, true);

// Handle errors first
if($http_code != 200 || !$result) {
    echo "<h2>Payment Error</h2>";
    echo "<p>HTTP code: $http_code</p>";
    echo "<pre>";
    print_r($result ? $result : $response);
    echo "</pre>";
    exit;
}

// Redirect to NOWPayments checkout
if(isset($result['invoice_url']) && !empty($result['invoice_url'])) {
    header("Location: ".$result['invoice_url']);
    exit;
}

// Fallback: some accounts use 'payment_url' instead
if(isset($result['payment_url']) && !empty($result['payment_url'])) {
    header("Location: ".$result['payment_url']);
    exit;
}

// If still nothing, show full response for debugging
echo "<h2>Payment Error: Unexpected response from NOWPayments</h2>";
echo "<pre>";
print_r($result);
echo "</pre>";
exit;
?>
