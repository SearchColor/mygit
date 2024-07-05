<?php
session_start();
$adminidx = $_SESSION['admin_idx']; // 관리자 고유 인덱스

include 'dbconn.php';

$title = $conn->real_escape_string($_POST['title']);
$content = $conn->real_escape_string($_POST['content']);

$notice_date_obj = new DateTime("now", new DateTimeZone("Asia/Seoul"));
$notice_date = $notice_date_obj->format('Y-m-d h:i:s');

$insertquery = "INSERT INTO notice (admin_idx, title, content, notice_date) VALUES ('$adminidx', '$title', '$content', '$notice_date')";
mysqli_query($conn, $insertquery);

$conn->close();

echo '<script> alert("공지글이 등록되었습니다."); </script>';
echo '<script> window.location.href="service1.php"; </script>';
?>