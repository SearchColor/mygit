<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<?php
include "dbconn.php";
session_start();
$useridx = isset($_SESSION['useridx']) ? $_SESSION['useridx'] : 0;
$memberquery = "SELECT name, refundAcctNum, refundBankCode FROM member WHERE useridx = $useridx";
$memberresult = mysqli_query($conn, $memberquery);
$memberrow = mysqli_fetch_assoc($memberresult);
?>
<link rel="stylesheet" href="<?= $hostaddress ?>/css/nobankbook.css" />
<body>
  <div class="all">
    <div class="info">
      <?= $memberrow['name'] ?>님의 환불계좌 정보
    </div>
    <?php
    if ($memberrow['refundAcctNum'] == null || $memberrow['refundAcctNum'] == "") {
      ?>
    <div class="detail">
      무통장환불은 본인 명의 계좌로만 가능합니다.
    </div>
    <div class="detail2">
      본인 명의 계좌로 등록해주세요.
    </div>
    <?php
    }else{
      ?>
      <table>
      <tr>
        <td>은행</td>
        <td>
          <?php
          $bc = $memberrow['refundBankCode'];
                  if ($bc == "03") {
                    $bankcode = "기업은행";
                  } else if ($bc == "88") {
                    $bankcode = "신한(통합)은행";
                  } else if ($bc == "04") {
                    $bankcode = "국민은행";
                  } else if ($bc == "11") {
                    $bankcode = "농협은행";
                  } else if ($bc == "81") {
                    $bankcode = "하나은행";
                  } else if ($bc == "90") {
                    $bankcode = "카카오뱅크";
                  } else if ($bc == "07") {
                    $bankcode = "수협중앙회";
                  } else if ($bc == "92") {
                    $bankcode = "토스뱅크";
                  } else if ($bc == "16") {
                    $bankcode = "축협중앙회";
                  } else if ($bc == "20") {
                    $bankcode = "우리은행";
                  } else if ($bc == "24") {
                    $bankcode = "한일은행";
                  } else if ($bc == "32") {
                    $bankcode = "부산은행";
                  } else if ($bc == "25") {
                    $bankcode = "서울은행";
                  } else if ($bc == "31") {
                    $bankcode = "대구은행";
                  } else if ($bc == "32") {
                    $bankcode = "부산은행";
                  }else if ($bc == "34") {
                    $bankcode = "광주은행";
                  } else if ($bc == "35") {
                    $bankcode = "제주은행";
                  } else if ($bc == "37") {
                    $bankcode = "전북은행";
                  } else if ($bc == "39") {
                    $bankcode = "경남은핸";
                  } else if ($bc == "45") {
                    $bankcode = "새마을금고";
                  } else if ($bc == "53") {
                    $bankcode = "한국씨티은행";
                  } else if ($bc == "71") {
                    $bankcode = "우체국";
                  } 
          ?>
         <?=$bankcode?>

        </td>
      </tr>
      <tr>
        <td>계좌번호</td>
        <td class="r3d1">
   <?=$memberrow['refundAcctNum']?>
        </td>
      </tr>
    </table>
    <?php
    }
    ?>
  </div>
  <?php
    if ($memberrow['refundAcctNum'] == null || $memberrow['refundAcctNum'] == "") {
   ?>
      <input type="button" value="계좌 등록하기" onclick="nobankbook1()" class="registration">
      <?php
    }else{
      ?>
      <input type="button" value="계좌 변경하기" onclick="nobankbookc()" class="registration">
      <?php
    }
      ?>
  
  <form action='cms.mhgkorea.com/regular.php' name='maincont'>
    <input type="hidden" name="nobankbook1" value="<?= $hostaddress ?>/nobankbook1.php">
    <input type="hidden" name="nobankbookc" value="<?= $hostaddress ?>/nobankbookc.php">
  </form>

  <script>
    function viewinfo(data) {
      window.customermanager.viewinfo(data);
    }

    function loadwebview(url, idx, name, useridx) {
      var msg = "loadwebview</>" + url + "</>" + idx + "</>" + name + "</>" + useridx;
      viewinfo(msg);

    }
    function nobankbookc(idx, name, useridx) {

var url = document.maincont.nobankbookc.value;

loadwebview(url, idx, name, useridx);

}

    function nobankbook1(idx, name, useridx) {

      var url = document.maincont.nobankbook1.value;

      loadwebview(url, idx, name, useridx);

    }
  </script>
</body>

</html>