<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>add admin</title>
</head>
<body>
    <?
    include "dbconn.php";

    $admin = "관리자4";
    $id = "admin4";
    $pass ="abcd";

    $en_pass = password_hash( $pass , PASSWORD_DEFAULT );

    //echo ($en_pass);

    // if ( password_verify( $pass, $en_pass ) ) {
    //     echo "<h1>Success</h1>";
    //   } else {
    //     echo "<h1>Fail</h1>";
    //   }
    $insertquery = "insert into admin (admin, id, pass) VALUES ('$admin', '$id', '$en_pass')";
    mysqli_query($conn, $insertquery);

    ?>
    
</body>
</html>