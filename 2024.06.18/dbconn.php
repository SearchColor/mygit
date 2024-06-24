<?php
$hostaddress = 'https://cms.mhgkorea.com';

$conn = new mysqli("db.allmyshop.co.kr", "mhadmin", "87tt7876r%", "myshop");
$conn->query("SELECT DATABASE()");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>