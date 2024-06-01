<?
session_start();
include "dbconn.php";
@$id = $_REQUEST['id'];
$id = str_replace(" ", "", $id);
@$password = $_REQUEST['password'];
@$state = $_REQUEST['state'];

if (empty($id) || empty($password)) {
    echo "<script>alert('아이디와 비밀번호를 입력해주세요.');</script>";
    echo "<script>history.back();</script>";
    exit();
}

$query = "SELECT id, password, useridx, name, tel FROM member WHERE id = '$id' AND withdrawal = '0'";
// $query="select id, AES_DECRYPT(unhex(password),'moduit') AS decrypted_password, useridx,name,tel from member where id ='$id' ";
$result = mysqli_query($conn,$query);
$row = mysqli_fetch_row($result);
if ($id === $row[0]) {
    if (password_verify($password, $row[1])) {
        $_SESSION['useridx'] = $row[2];
        $_SESSION['name'] = $row[3];
        $_SESSION['tel'] = $row[4];
        $_SESSION['connection_time'] = time();
        $_SESSION['connection_ip'] = $_SERVER['REMOTE_ADDR'];

        // 자동 로그인 상태가 체크되어 있으면 쿠키에 저장
        if ($state == 1) {
            setcookie("useridx", $row[2], time() + 90 * 24 * 60 * 60, "/");
        } 
        // else {
        //     setcookie("useridx", $row[2], time() + 1, "/");

        // }
                
        $useridx = $row[2];
        include "log/login_suc_log.php";
        $tel = $row[4];
        $connection_ip = $_SERVER['REMOTE_ADDR'];
        $idate = date("Y-m-d H:i:s");
        $session_query = "INSERT INTO session_log(useridx, connection_ip, idate) VALUES ('$useridx', '$connection_ip', '$idate')";
        $result = mysqli_query($conn, $session_query);
?>
        <script>
    <?
        $iphone=0;
		if($iphone=="0")
		{
	?>
        function viewinfo(data)
        {
            window.customermanager.viewinfo(data);
        }
	<?
		}
		else
		{
	?>

	function viewinfo(sidx){
		try{
			webkit.messageHandlers.callbackHandler.postMessage(sidx);
		}catch(err){

			console.log('error');
		}
	}
	<?
		}
        
        
	?>
        function loadwebview(url,idx,name,useridx)
        {
            var msg="loadwebviewfinish</>"+url+"</>"+idx+"</>"+name+"</>"+useridx;
            viewinfo(msg);
        }
        function printdlg(msg,url){
            let useridx = "<?=$useridx?>";
            viewinfo("printdlg</>"+msg+"</>"+url);
        }

        printdlg("로그인이 완료되었습니다","myshop.php",null);

        
      </script>
    <?
    }else {
            echo "<script>alert('비밀번호가 잘못되었습니다.');</script>";
            echo "<script>history.back();</script>";
            exit();
        }
    } else {

        echo "<script>alert('아이디를 찾을수 없습니다.');</script>";
        echo "<script>history.back();</script>";
        exit();
    }
?>
