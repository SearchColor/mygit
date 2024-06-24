<?php

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
include "dbconn.php";

$useridx = isset($_SESSION['useridx']) ? $_SESSION['useridx'] : 0;

$userquery = "SELECT name,AES_DECRYPT(UNHEX(tel), 'myshop') AS tel,j_coupon FROM member WHERE useridx='$useridx'";

$userresult = mysqli_query($conn, $userquery);

$userrow = mysqli_fetch_assoc($userresult);

$j_coupon = $userrow['j_coupon'] ?? '0';

$m_name = $userrow['name'] ?? '0';

$m_tel = $userrow['tel'] ?? '0';

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<HEAD>

  <TITLE>myshopMypage</TITLE>

  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="<?= $hostaddress ?>/css/mypage.css?aa11" />

</HEAD>
<style>
  .regular-member {
    color: #42A5F5;
    font-size: 24px;
  }
</style>
<script language="javascript">
  //여기는 라이브러리 함수
    function viewinfo(data)
    {
      window.customermanager.viewinfo(data);
    }

  function loadwebview2(idx, name, useridx)

  {
    let hostaddress = "<?= $hostaddress ?>";

    url = hostaddress + "/myshop_login.php";

    var msg = "loadwebviewfinish3</>" + url + "</>" + idx + "</>" + name + "</>" + useridx;

    viewinfo(msg);

  }

  function loadwebview(url, idx, name, useridx){
    var msg = "loadwebview</>" + url + "</>" + idx + "</>" + name + "</>" + useridx;
    viewinfo(msg);

  }

  function printdlg2(msg, url, titlename) {
    let useridx = "<?= $useridx ?>";
    viewinfo("printdlg2</>" + msg + "</>" + url + "</>" + titlename + "</>" + useridx);

  }

  function printdlg(msg, url, titlename) {
    let useridx = "<?= $useridx ?>";
    viewinfo("printdlg</>" + msg + "</>" + url + "</>" + titlename + "</>" + useridx);

  }
</script>

<body>

  <SCRIPT>
    //혀기는 호출 함수

    // function newurldo(idx,name)

    // {

    // 	var url= document.maincont.datatext.value;

    // 	loadwebview(url,idx,name);

    // }

    function ongoback()

    {

      document.maincont.action = "moduapi.php";

      document.maincont.submit();

    }

    function zzim(idx, name, useridx)

    {

      var url = document.maincont.zzim.value;

      loadwebview(url, idx, name, useridx);

    }

    function review(idx, name, useridx)

    {

      var url = document.maincont.review.value;

      loadwebview(url, idx, name, useridx);

    }

    function orderlist(idx, name, useridx) {

      var url = document.maincont.orderlist.value;



      loadwebview(url, idx, name, useridx);

    }

    function nobankbook(idx, name, useridx) {

      var url = document.maincont.nobankbook.value;

      loadwebview(url, idx, name, useridx);

    }

    function gouserupdate(idx, name, useridx) {

      var url = document.maincont.userupdate.value;



      loadwebview(url, idx, name, useridx);

    }

    function gopointhistory(idx, name, useridx) {

      var url = document.maincont.userpointHistory.value;



      loadwebview(url, idx, name, useridx);
    }

    // function goini(idx,name,useridx){

    //     var url = document.maincont.goini.value;



    //     loadwebview(url,idx,name,useridx);

    // }

    function agree(idx, name, useridx) {

      var url = document.maincont.agree.value;



      loadwebview(url, idx, name, useridx);

    }

    function qa(idx, name, useridx) {

      var url = document.maincont.qa.value;



      loadwebview(url, idx, name, useridx);

    }

    function rrreee(memPoint){
      if(memPoint <100000){
        var ms = "최소포인트 전환은 10만포인트 이상입니다.";
        var msg = "seterror</>" + ms;
        viewinfo(msg);
      }else{
        var ms = "시스템 준비중입니다.\n곧 제공 예정입니다.";
        var msg = "seterror</>" + ms;
        viewinfo(msg);
      }
      
      
    }


    function logout(idx, name, useridx) {
      location.href = "<?= $hostaddress ?>/myshop_logout.php";
    }
  </SCRIPT>

  <?php

  if ($useridx !== "0" && $useridx !== null) {

    $query = "SELECT * FROM member WHERE useridx = $useridx";

    $result = mysqli_query($conn, $query);



    if ($result && mysqli_num_rows($result) > 0) {

      $row = mysqli_fetch_array($result);

      $phone_number = str_replace("-", "", $m_tel);

      $cms_member_query = "SELECT COUNT(*) AS count FROM cms_member WHERE name = '$m_name' AND phone_number = '$phone_number'";
      $cms_member_result = mysqli_query($conn, $cms_member_query);
      $cms_member_count = mysqli_fetch_assoc($cms_member_result)['count'];

      $cms_member_query2 = "SELECT COUNT(*) AS count FROM cms_member WHERE name = '$m_name' AND phone_number = '$m_tel'";
      $cms_member_result2 = mysqli_query($conn, $cms_member_query2);
      $cms_member_count2 = mysqli_fetch_assoc($cms_member_result2)['count'];



  ?>
	<div class="popup-overlay" id="popup" style="display: none;">

<div class="popup-content">
<span class="close-btn" onclick="closePopup()">X</span>
	<?php if (!empty($adimgae)){?>
		<img src="<?= htmlspecialchars($adimgae) ?>" alt="Ad" style="max-width: 100%; height: auto;">
		<?
	}else{
		?>
		<h2>안내 사항</h2>
		<p>모두함께앱에서</p><p>정회원 간소화를 통해</p>
		 <p>간편한 가입이 가능하도록 변경하였습니다.</p>
		<?
	}
		?>
	<div>
		<input type="checkbox" id="dontShowAgain" onclick="closeset()"> 오늘 하루 보지 않기
	</div>
</div>
</div> 

      <div class="container">
        <div class="myspan">
          <?

          $mem_history_select_query = "SELECT * from memP_history where useridx = '$useridx' order by memP_idx desc Limit 1";
          $query = mysqli_query($conn, $mem_history_select_query);
          $memhData = mysqli_fetch_assoc($query);
          if ($memhData) {
            $memPoint = $memhData['memPoint'];
          } else {
            $memPoint = 0;
          }
          ?>
          <div class="myinfo">
            <?php if ($j_coupon == "1") : ?>
              <img src="<?= $hostaddress ?>/image/regular_icon.png"><span class="regular-member">정회원</span> <?= $row['name'] ?> 님
            <?php else : ?>
              <?= $row['name'] ?>님
            <?php endif; ?><br>
            반갑습니다<br>
          </div><br>
          <button class="infobutton" onclick="gouserupdate('0','정보 변경','<?= $useridx ?>')">
            <span class="infospan">정보변경</span>
          </button>
          <!-- <button class="infobutton2" <? if ($j_coupon == "1") { ?>style="display : none" <? } else { ?> onclick="cms('0','정회원','<?= $useridx ?>')"><? } ?>
          <span class="infospan">정회원 가입</span> -->
          </button>

        </div>
      </div>



      <!-- <div class="container">
    <div class="myspan">
        <span class="myinfo"><?= $row['name'] ?>님</span>
        <button class="infobutton2" <? if ($j_coupon == "1") { ?>style="display : none"<? } else { ?> onclick="cms('0','정회원','<?= $useridx ?>')"><? } ?>
            <span class="infospan">정회원 가입</span>
        </button>
    </div>
    <div class="greeting-container">
        <span class="myinfo">반갑습니다</span>
        <button class="infobutton" onclick="gouserupdate('0','정보 변경','<?= $useridx ?>')">
            <span class="infospan">정보변경</span>
        </button>
    </div>
</div> -->



      <div class="button3">



        <button class="button3child"><span class="childspan">주문배송</span>



          <br><span class="redspan">0</span><span class="defaultspan">건</span></button>

        <!-- <button class="button3child"><span class="childspan">적립금</span><br><span class="redspan">2000</span><span class="defaultspan">원</span></button> -->



        <button class="button3child2"  onclick="gopointhistory('0','포인트내역','<?= $useridx ?>')"><span class="childspan">보유 포인트</span><br><span class="redspan"><?= $memPoint ?></span><span class="defaultspan">P</span></button>



        <button class="button3child3"  onclick="rrreee('<?= $memPoint ?>')"><span class="childspan">코인 전환</span><br><span class="childspan"></span></button>

      </div>



      <hr class="hrr">

      <div class="container4">

        <button class="mk" onclick="orderlist('0','주문내역','<?= $useridx ?>')"><span class="mkspan">주문 상품</span><span class="mkarrow">></span></button><br>

        <button class="mk" onclick="nobankbook('0','무통장환불','<?= $useridx ?>')"><span class="mkspan">무통장 환불 계좌 정보</span><span class="mkarrow">></span></button><br>

        <button class="mk" onclick="zzim('0','찜목록','<?= $useridx ?>')"><span class="mkspan">찜한 상품</span><span class="mkarrow">></span></button><br>

        <button class="mk" onclick="review('0','구매후기','<?= $useridx ?>')"><span class="mkspan">나의 리뷰</span><span class="mkarrow">></span></button><br>

        <!-- <button class="mk"><span class="mkspan">적립금</span><span class="mkarrow">></span></button><br> -->

        <button class="mk" onclick="printdlg('서비스 준비중 입니다. \n메인 페이지로 이동합니다!','myshop.php','<?= $useridx ?>')"><span class="mkspan">쿠폰</span><span class="mkarrow">></span></button><br>

        <button class="mk" onclick="agree('0','수신설정','<?= $useridx ?>')"><span class="mkspan">수신설정</span><span class="mkarrow">></span></button><br>

        <button class="mk" onclick="qa('0','1:1문의','<?= $useridx ?>')"><span class="mkspan">1:1문의</span><span class="mkarrow">></span></button><br>

        <button class="mk" onclick="logout('0','로그아웃','<?= $useridx ?>')"><span class="mkspan">로그아웃</span><span class="mkarrow">></span></button>

        <br>

        <form action='cms.mhgkorea.com/regular.php' name='maincont'>

          <input type="hidden" name="zzim" value="<?= $hostaddress ?>/zzim.php">

          <input type="hidden" name="nobankbook" value="<?= $hostaddress ?>/nobankbook.php">

          <input type="hidden" name="review" value="<?= $hostaddress ?>/myreview.php">

          <input type="hidden" name="orderlist" value="<?= $hostaddress ?>/myorderlist.php">

          <input type="hidden" name="userupdate" value="<?= $hostaddress ?>/userupdate.php">

          <input type="hidden" name="userpointHistory" value="<?= $hostaddress ?>/userpointHistory.php">

          <input type="hidden" name="login" value="<?= $hostaddress ?>/myshop_login.php">

          <!-- <input type="hidden" name="printdlg" value="<?= $hostaddress ?>/mypage.php"> -->

          <input type="hidden" name="agree" value="<?= $hostaddress ?>/myshop_mypage_agree.php">

          <input type="hidden" name="qa" value="<?= $hostaddress ?>/myshop_qa.php">

        </form>

      <?

    } else {

      ?>

        <script>
          printdlg2("장시간 활동이 없어, 메인페이지로 이동합니다.", "myshop.php", "MyShop");
        </script>

      <?php

    }
  } else {

    // $useridx가 "0"이거나 null인 경우, 로그인 페이지로 이동하도록 하는 JavaScript

      ?>

      <script>
        printdlg2("로그인이 필요한 서비스 입니다", "myshop_login.php", "MyShop");
      </script>

    <?php

  }

    ?>
    <script>
      function cms(idx, name, useridx) {

        var cmscount = <?php echo json_encode($cms_member_count); ?>;
        var cmscount2 = <?php echo json_encode($cms_member_count2); ?>;

        var url = "cms.mhgkorea.com/regular.php";

        if (cmscount >= 1 || cmscount2 >= 1) {
          alert("정회원 처리중입니다.");
        } else {
          loadwebview(url, idx, name, useridx);
        }
      }
      		document.addEventListener("DOMContentLoaded", function() {
    checkPopupVisibility();
});
function closePopup() {
    var popup = document.getElementById('popup');
    popup.style.display = 'none';
}
function closeset(){
	var dontShowAgain = document.getElementById('dontShowAgain').checked;
	if (dontShowAgain) {
    setCookie("announcement", "false", 1);
	closePopup();
    }
}
function checkPopupVisibility() {
    var announcement = getCookie("announcement");
    if (announcement !== "false") {
        document.getElementById('popup').style.display = 'flex';
    }
}

function setCookie(name, value, days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days*24*60*60*1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "") + expires + "; path=/";
}

function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}
    </script>
</body>



</HTML>