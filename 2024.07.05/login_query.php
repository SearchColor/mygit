<?php
session_start([
    'cookie_lifetime' => 30*60,
]);

include 'dbconn.php'; // 데이터베이스 연결

$adminid = mysqli_real_escape_string($conn, $_POST['adminid']);
$adminpass = mysqli_real_escape_string($conn, $_POST['adminpass']);

// 관리자 ID로 정보 검색
$chkquery = "SELECT * FROM admin WHERE id='$adminid'";
$result = mysqli_query($conn, $chkquery);

if ($row = mysqli_fetch_assoc($result)) {
    if (password_verify($adminpass, $row['pass'])) {
        // 세션 변수 설정
        $_SESSION['admin_idx'] = $row['admin_idx'];
        $_SESSION['admin'] = $row['admin'];
        $_SESSION['adminflag'] = true;

        echo '<script>alert("환영합니다. ' . $_SESSION['admin'] . '");</script>';
        echo '<script>window.location.href="index.php";</script>';
    } else {
        echo '<script>alert("비밀번호가 일치하지 않습니다.");</script>';
        echo '<script>window.location.href="login.php";</script>';
    }
} else {
    echo '<script>alert("존재하지 않는 아이디입니다.");</script>';
    echo '<script>window.location.href="login.php";</script>';
}

mysqli_close($conn);
?>