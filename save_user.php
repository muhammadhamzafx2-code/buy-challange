<?php

$file = "users.json";

// get form data
$data = [
    "firstname" => $_POST['firstname'],
    "lastname"  => $_POST['lastname'],
    "email"     => $_POST['email'],
    "phone"     => $_POST['phone'],
    "dob"       => $_POST['dob'],
    "country"   => $_POST['country'],
    "balance"   => $_POST['balance'],
    "price"     => $_POST['price'],
    "type"      => $_POST['type'],
    "status"    => "pending",
    "date"      => date("Y-m-d H:i:s")
];

// read existing users
if(file_exists($file)){
    $current = json_decode(file_get_contents($file), true);
}else{
    $current = [];
}

// add new user
$current[] = $data;

// save back
file_put_contents($file, json_encode($current, JSON_PRETTY_PRINT));

// redirect to real payment page
header("Location: create_payment.php?price=".$_POST['price']."&email=".$_POST['email']."&balance=".$_POST['balance']."&type=".$_POST['type']);
exit;

?>
