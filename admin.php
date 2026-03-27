<?php
$conn = new mysqli("localhost","root","","propfirm");

$result = $conn->query("SELECT * FROM users ORDER BY id DESC");

echo "<h2>Paid Users</h2>";

while($row=$result->fetch_assoc()){
echo $row['firstname']." ".
$row['lastname']." - ".
$row['email']." - ".
$row['balance']." - $".
$row['price']."<br><br>";
}
?>
