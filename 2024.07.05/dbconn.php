<?php
// $hostaddress = 'https://www.asterdynamics.kr/';

// 데이터베이스 연결 정보
$servername = "210.223.78.70";
$username = "root";
$password = "54ww4543q@";
$dbname = "aster";

// 데이터베이스 연결 생성
$conn = new mysqli($servername, $username, $password, $dbname);

// 연결 확인
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
