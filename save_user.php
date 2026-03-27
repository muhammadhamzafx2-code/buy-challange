<?php
$conn = new mysqli("localhost","root","","propfirm");

$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$dob = $_POST['dob'];
$country = $_POST['country'];
$balance = $_POST['balance'];
$price = $_POST['price'];
$type = $_POST['type'];

$conn->query("INSERT INTO users(firstname,lastname,email,phone,dob,country,balance,price,type,status)
VALUES('$firstname','$lastname','$email','$phone','$dob','$country','$balance','$price','$type','pending')");

header("Location: create_payment.php?price=$price&email=$email&balance=$balance&type=$type");
exit;
?>
