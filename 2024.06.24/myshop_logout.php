<?php
session_start();
include("dbconn.php");
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


        
      </script>
<?
// 현재 로그인된 사용자의 아이디 가져오기
if (isset($_SESSION['useridx'])) {
    $useridx = $_SESSION['useridx'];
    // include("log/logout_log.php");
    // $connection_time = $_SESSION['connection_time'];
    // $dateString = $connection_time;
    // $timestamp = strtotime($dateString);
    // $outdate = date("Y-m-d H:i:s",$timestamp);
    // $logout_time = time();
    // $holdingtime = $logout_time - $timestamp;
    // $useridx = $_SESSION['useridx'];

    // $query="update session_log set session_holding_time='$holdingtime' where useridx='$useridx' && idate = '$outdate'";
    // $result=mysqli_query($conn,$query);
    // 세션 파기
    session_destroy();
    session_unset();
    setcookie("useridx", "", time() - 3600, "/");
    ?>
    <script>
        printdlg("로그아웃이 완료되었습니다","myshop.php")
        function printdlg(msg,url){
            viewinfo("printdlg</>"+msg+"</>"+url);
        }
        </script>
    <?    
exit();
}

?>