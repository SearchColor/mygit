<?php
$hostaddress = 'https://www.allmyshop.co.kr/A_team';

$conn = new mysqli("localhost", "mhadmin", "87tt7876r%", "myshop");
$conn->query("SELECT DATABASE()");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>