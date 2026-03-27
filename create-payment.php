<?php
require "config.php";

$price = $_GET['price'];
$email = $_GET['email'];
$balance = $_GET['balance'];
$type = $_GET['type'];

$data = [
  "price_amount" => $price,
  "price_currency" => "usd",
  "pay_currency" => "usdt",
  "order_id" => "XV".rand(10000,99999),
  "order_description" => "$type account - $balance USD",
  "success_url" => SUCCESS_URL,
  "cancel_url" => CANCEL_URL
];

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
$result = json_decode($response,true);

header("Location: ".$result['invoice_url']);
exit;

?>
