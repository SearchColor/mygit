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
$memberquery = "SELECT name FROM member WHERE useridx = $useridx";
$memberresult = mysqli_query($conn, $memberquery);
$memberrow = mysqli_fetch_assoc($memberresult);
?>
<link rel="stylesheet" href="css/nobankbook1.css">
<body>
<div class="container">
<div class="reg">환불계좌를 등록해주세요.</div>
<table>
    <tr>
      <td>예금주</td>
      <td id="account_name" ><?= $memberrow['name'] ?></td>
    </tr>
    <tr>
      <td>은행선택</td>
      <td>
        <select name="bank" id="bank" >
        <option disabled selected value="none">은행을 선택하세요</option>
        <option value="003">기업은행</option>
        <option value="088">신한(통합)은행</option>
        <option value="004">국민은행</option>
        <option value="011">농협은행</option>
        <option value="081">하나은행</option>
        <option value="090">카카오뱅크</option>
        <option value="007">수협중앙회</option>
        <option value="092">토스뱅크</option>
        <option value="016">축협중앙회</option>
        <option value="020">우리은행</option>
        <option value="024">한일은행</option>
        <option value="032">부산은행</option>
        <option value="025">서울은행</option>
        <option value="031">대구은행</option>
        <option value="032">부산은행</option>
        <option value="034">광주은행</option>
        <option value="035">제주은행</option>
        <option value="037">전북은행</option>
        <option value="038">강원은행</option>
        <option value="039">경남은행</option>
        <option value="045">새마을금고</option>
        <option value="053">한국씨티은행</option>
        <option value="071">우체국</option>
      </select>
    </td>
    </tr>
    <tr>
      <td>계좌번호</td>
      <td class="r3d1" >
      <input class="delivery_num_class" type="text" placeholder="'-'없이 숫자만 입력하세요" name="account_num" id="account_num">
      <input type="button" id='account_btn' onclick="account_check()" value="인증">
    </td>
    </tr>
</table>
<div class="agreecheckbox">
  <input type="checkbox" id="checkboxID"><span>개인정보 수집 및 이용 동의</span>
</div>
<div class="agreedetail">
  <div>개인정보 수집 및 이용 동의</div>
  <div>올마이샵(AllMyShop)은 아래와 같은 목적으로 개인정보를 수집 및 이용합니다. </div>
  <div>수집 항목: 은행명, 계좌번호</div>
  <div>수집목적: 무통장 환불</div>
  <div>보유기간: 정보 삭제 요청 또는 회원 탈퇴 시 파기</div>
  <div>- 동의를 거부할 수 있으나, 동의 거부 시 무통장 환불 처리가 어렵습니다.</div>
  <div>- 무통장 입금 결제 후 주문 취소시 무통장 계좌로 환불해 드립니다.(단, 고객님 본인 명의의 계좌만 가능)</div>
  <div>- 환불계좌 등록 후, 환불대기중의 상품은 등록하신 계좌로 변경되어 환불됩니다.</div>
</div>
<input type="button" id="nextbtn" disabled value="등록" class="registration" onclick="registration()">
</div>
<script>

</script>

  <input type='hidden' id = 'request_no'>
  <input type='hidden' id = 'res_uniq_id'>
  <input type='hidden' id = 'otp_result_cd'> <!--계좌otp 인증성공시 value = success -->
  <input type='hidden' id = 'smsotp_result_cd'> <!-- smsotp 인증성공시 value = success  -->
</body>
<script>   
function viewinfo(data) {
      window.customermanager.viewinfo(data);
    }
    function printdlg(msg,url){
    let useridx = "<?=$useridx?>";
    viewinfo("printdlg</>"+msg+"</>"+url);
  }
    function accoutchk(url) {
      var msg = "popupView</>" + url;
      viewinfo(msg);
    }

    function setmsg(ms) {
      var msg = "seterror</>" + ms;
      viewinfo(msg);
    }
    function optresult(data){
            document.getElementById('otp_result_cd').value =data;
            const checkbox = document.getElementById('checkboxID');
            const nextbtn = document.getElementById('nextbtn');
            const is_checked = checkbox.checked;
            
		    var ms = "계좌 인증 완료되었습니다";
            setmsg(ms)
            document.getElementById('bank').readOnly = true;
            document.getElementById('account_num').readOnly = true;
            if(is_checked){
              nextbtn.disabled = false; // 다음 단계 버튼 활성화
            }
        }

        const checkbox = document.getElementById('checkboxID');
    const nextButton = document.querySelector('.registration');
    checkbox.addEventListener('change', function() {
      var data = document.getElementById('otp_result_cd').value;

        if (this.checked) {
            // 체크되었을 때
            if(data == 'success') {
            nextButton.disabled = false; // 다음 단계 버튼 활성화
            nextButton.classList.remove('unabled');}
        } else {
            // 체크 해제되었을 때
            var ms = "환불계좌 수집/설정 동의에 체크해주시기 바랍니다.";
            setmsg(ms);
            nextButton.classList.add('unabled');
            nextButton.disabled = true; // 다음 단계 버튼 비활성화
        }
    });

    // function changevalue(bankcode) {
    //   document.getElementById("bank2").value = bankcode;
    // }

    function account_check() {
      var bank_cd = document.getElementById('bank').value;
      var account_id = document.getElementById('account_num').value;
      var name = document.getElementById('account_name').textContent;
      var url1 = `./check_account.php?bank_cd=${bank_cd}&account_id=${account_id}&name=${name}`;

      fetch(url1)
        .then(function(response) {
          return response.text();
        })
        .then(function(response) {
          const topics = JSON.parse(response);
          if (topics.dataBody.result_cd == '0000') {
            document.getElementById('request_no').value = topics.dataBody.request_no;
            document.getElementById('res_uniq_id').value = topics.dataBody.res_uniq_id;
            var request_no = topics.dataBody.request_no;
            var res_uniq_id = topics.dataBody.res_uniq_id;
            var ms = "입력하신 계좌로 1원이 송금되었습니다. 인증번호를 확인해주세요";
            setmsg(ms);
            var url2 = `<?= $hostaddress ?>/cms_popup.php?request_no=${request_no}&res_uniq_id=${res_uniq_id}`;
            accoutchk(url2);
          } else {
            alert("이름과 계좌번호를 확인해주세요("+topics.dataBody.result_cd+")");
          }
        });
    }
function registration(){
  var bank_cd = document.getElementById('bank').value;
      var account_id = document.getElementById('account_num').value;
  var hostAddress = "<?= $hostaddress ?>";
      const xhr = new XMLHttpRequest();
      xhr.open('POST', hostAddress + '/nobankbookregistration.php', true);
      xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
      xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
          if (xhr.status === 200) {
            printdlg("무통장 환불 계좌 등록이 완료되었습니다.","myshop.php");
          } else {
            console.error('Error:', xhr.statusText);
          }
        }
      };
      xhr.send('useridx=' + <?=$useridx?>+ '&bank_cd='+ bank_cd  + '&account_id=' + account_id);
}
  </script>
</html>