<?php

$file = "users.json";

// make sure form was submitted
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    die("Invalid request");
}

// collect form data safely
$data = [
    "firstname" => $_POST['firstname'] ?? "",
    "lastname"  => $_POST['lastname'] ?? "",
    "email"     => $_POST['email'] ?? "",
    "phone"     => $_POST['phone'] ?? "",
    "dob"       => $_POST['dob'] ?? "",
    "country"   => $_POST['country'] ?? "",
    "balance"   => $_POST['balance'] ?? "",
    "price"     => $_POST['price'] ?? "",
    "type"      => $_POST['type'] ?? "",
    "status"    => "pending",
    "date"      => date("Y-m-d H:i:s")
];

// read existing users
if (file_exists($file)) {
    $current = json_decode(file_get_contents($file), true);
    if (!is_array($current)) {
        $current = [];
    }
} else {
    $current = [];
}

// add new user
$current[] = $data;

// save users.json
file_put_contents($file, json_encode($current, JSON_PRETTY_PRINT));

// redirect to payment page
header("Location: create_payment.php?price=".$data['price']."&balance=".$data['balance']."&type=".$data['type']);
exit;

?>
