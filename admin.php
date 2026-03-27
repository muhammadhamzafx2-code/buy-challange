<?php

$file = "users.json";

if(!file_exists($file)){
    echo "No users yet.";
    exit;
}

$users = json_decode(file_get_contents($file), true);

echo "<h2>Users Who Made Payment</h2>";

foreach($users as $u){
    echo "<div style='margin-bottom:20px'>";
    echo "<b>Name:</b> ".$u['firstname']." ".$u['lastname']."<br>";
    echo "<b>Email:</b> ".$u['email']."<br>";
    echo "<b>Phone:</b> ".$u['phone']."<br>";
    echo "<b>Country:</b> ".$u['country']."<br>";
    echo "<b>Account:</b> $".$u['balance']." (".$u['type'].")<br>";
    echo "<b>Price Paid:</b> $".$u['price']."<br>";
    echo "<b>Date:</b> ".$u['date']."<br>";
    echo "<hr>";
    echo "</div>";
}
?>
