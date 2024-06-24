<!DOCTYPE html>
<html lang="ko">
<head>
    <?
    include "dbconn.php";
    ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=yes">
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.2.0/dist/signature_pad.umd.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/total_addjoin.css">
    <title>통합회원가입</title>
</head>

<body>
<?
$name = $_GET['name']??'';
$tel = $_GET['tel']??'';
$address = $_GET['address']??'';
$email = $_GET['email']??'';
// $pnumber =$_GET['pnumber']??'0';

// $birth = explode('-', $pnumber);

// if (count($birth) == 2) {

//     $birthdate = $birth[0];

//     $back_number = $birth[1];
// }
$tel2 = substr($tel, 0, 3) . '-' . substr($tel, 3, 4) . '-' . substr($tel, 7);

$member_query = "SELECT COUNT(*) AS count FROM member WHERE name='$name' AND tel = HEX(AES_ENCRYPT('$tel', 'myshop'))";
$member_result = mysqli_query($conn, $member_query);
$member_count = mysqli_fetch_assoc($member_result)['count'];

$member_query2 = "SELECT COUNT(*) AS count FROM member WHERE name='$name' AND tel = HEX(AES_ENCRYPT('$tel2', 'myshop'))";
$member_result2 = mysqli_query($conn, $member_query2);
$member_count2 = mysqli_fetch_assoc($member_result2)['count'];

$cms_member_query = "SELECT COUNT(*) AS count FROM cms_member WHERE name = '$name' AND phone_number = '$tel'";
$cms_member_result = mysqli_query($conn, $cms_member_query);
$cms_member_count = mysqli_fetch_assoc($cms_member_result)['count'];

$cms_perm_query = "SELECT COUNT(*) AS count FROM cms_perm WHERE name = '$name' AND phone_number = '$tel'";
$cms_perm_result = mysqli_query($conn, $cms_perm_query);
$cms_perm_count = mysqli_fetch_assoc($cms_perm_result)['count'];


?>
<form id="form_chk" name="form_chk" method="post">
<input type="hidden" name="text_bankcode" id="text_bankcode">
<input type="hidden" name="text_region" id="text_region">

<div class="container">
    <input type="button" id="generalButton" onclick="showForm('general')" value="올마이샵 회원가입">
    <input type="button" id="premiumButton" onclick="showForm('premium')" value="정회원 가입">
</div>

    <div id="generalForm" style="display: none;">
        <!-- 올마이샵 회원가입 폼 -->
        <h2>올마이샵 회원가입</h2>
        <div class="form-item">
        <span class="form-label">이름</span>
        <input type="text" id="name" name="name" placeholder="이름" value="<?=$name?>"><p>
        </div>
        <div class="form-item">
        <span class="form-label">전화번호 <span class="red-text">(-빼고 입력해주세요)</span></span></span>
        <input type="tel" id="tel" name="tel" placeholder="전화번호" value="<?=$tel?>" maxlength="11" oninput="validatePhoneNumber(this)"><p>
        </div>
        <div class="form-item">
        <span class="form-label">주소</span>
        <input type="text" id="address" name="address" placeholder="주소" value="<?=$address?>"><p>
        </div>
        <div class="form-item">
    <span class="form-label">아이디</span>
    <input type="text" id="id_input" name="id_input" placeholder="신청인 아이디" maxlength="15" oninput="validateId()"><p>
    <input type="button" value="중복확인" onclick="openIdCheckPopup()">
    <div id="id_check_message" style="color: red; display: none;">중복 확인을 해주세요.</div>
     </div>

     <div class="form-item">
    <span class="form-label">비밀번호</span>
    <input type="password" id="password" name="password" placeholder="비밀번호" oninput="validatePassword()" minlength="6" maxlength="15">
</div>

<div class="form-item">
    <span class="form-label">비밀번호 확인</span>
    <input type="password" id="password2" name="password2" placeholder="비밀번호 확인" oninput="validatePassword()" minlength="6" maxlength="15">
    <span id="password_match" style="color: red; display: none;">비밀번호가 일치하지 않습니다.</span>
</div>


<input type="button" id="nextbutton" value="올마이샵 회원가입" onclick="simpleaddjoin()">
    </div>

    <div id="premiumForm" style="display: none;">
        <!-- 정회원 가입 폼 -->
        <h2>정회원 회원가입</h2>
        <div class="form-item">
        <span class="form-label">이름</span>
        <input type="text" id="name2" name="name2" placeholder="이름" value="<?=$name?>">
    </div>
    <div class="form-item">
        <span class="form-label">전화번호 <span class="red-text">(-빼고 입력해주세요)</span></span></span>
        <input type="tel" id="tel2" name="tel2" placeholder="전화번호" value="<?=$tel?>" maxlength="11" oninput="validatePhoneNumber(this)">
    </div>
    <div class="form-item">
        <span class="form-label">주소</span>
        <input type="text" id="address2" name="address2" placeholder="주소" value="<?=$address?>">
    </div>
    <div class="form-item inline-form-item">
    <span class="form-label">주민등록번호</span>
    <div class="input-group">
        <input type="tel" id="birthdate" name="birthdate" placeholder="앞6자리" maxlength="6" oninput="validatePhoneNumber(this)">
        <span class="separator">-</span>
        <input type="tel" id="back_number" name="back_number" placeholder="뒤7자리" maxlength="7" oninput="validatePhoneNumber(this)">
    </div>
</div>
        <div class="form-item">
        <span class="form-label">지역</span>
        <select id="region" name="region">
            <option value="">지역 선택</option>
            <option value="01">서울</option>
            <option value="02">부산</option>
            <option value="03">인천</option>
            <option value="04">대구</option>
            <option value="05">대전</option>
            <option value="05">광주</option>
            <option value="07">울산</option>
            <option value="08">세종</option>
            <option value="09">경기</option>
            <option value="10">강원</option>
            <option value="11">충남</option>
            <option value="12">충북</option>
            <option value="13">경남</option>
            <option value="14">경북</option>
            <option value="15">전남</option>
            <option value="16">전북</option>
            <option value="17">제주</option>
        </select>
    </div>

    <div class="form-item">
        <span class="form-label">신청인직책</span>
        <input type="text" id="m_position" name="m_position" placeholder="직책">
    </div>

    <div class="form-item">
        <span class="form-label">회비</span><p>
        <input type="checkbox" id="pay_amount_1" name="pay_amount" value="1" onclick="checkOnlyOne(this.id)"> 월 회비 10,000원 <p>
        <input type="checkbox" id="pay_amount_2" name="pay_amount" value="2" onclick="checkOnlyOne(this.id)"> 연 회비 100,000원
    </div>


    <div class="form-item">
        <span class="form-label">결제은행</span>
        <select id="pay_bankcode" name="pay_bankcode">
                    <option value="null">선택</option>
                    <option value="054">HSBC</option>
                    <option value="023">SC제일</option>
                    <option value="039">경남</option>
                    <option value="034">광주</option>
                    <option value="004">국민</option>
                    <option value="003">기업</option>
                    <option value="011">농협은행</option>
                    <option value="012">단위농협</option>
                    <option value="031">대구</option>
                    <option value="032">부산</option>
                    <option value="002">산업</option>
                    <option value="050">상호저축</option>
                    <option value="045">새마을금고</option>
                    <option value="007">수협중앙회</option>
                    <option value="048">신협중앙회</option>
                    <option value="088">신한</option>
                    <option value="020">우리</option>
                    <option value="071">우체국</option>
                    <option value="037">전북</option>
                    <option value="035">제주</option>
                    <option value="081">KEB하나</option>
                    <option value="027">한국씨티</option>
                    <option value="064">산림조합중앙회</option>
                    <option value="209">유안타증권</option>
                    <option value="055">도이치</option>
                    <option value="057">제이피모간체이스</option>
                    <option value="261">교보증권</option>
                    <option value="278">신한금융투자</option>
                    <option value="267">대신증권</option>
                    <option value="238">미래에셋대우</option>
                    <option value="279">동부증권</option>
                    <option value="287">메리츠종합금융증권</option>
                    <option value="290">부국증권</option>
                    <option value="240">삼성증권</option>
                    <option value="291">신영증권</option>
                    <option value="266">SK증권</option>
                    <option value="263">현대차투자증권</option>
                    <option value="292">케이프투자증권</option>
                    <option value="247">NH투자증권</option>
                    <option value="280">유진투자증권</option>
                    <option value="265">이베스트투자증권</option>
                    <option value="264">키움증권</option>
                    <option value="270">하나대투증권</option>
                    <option value="262">하이투자증권</option>
                    <option value="243">한국투자증권</option>
                    <option value="269">한화투자증권</option>
                    <option value="218">KB증권</option>
                    <option value="089">K뱅크</option>
                    <option value="090">카카오뱅크</option>
                    <option value="092">토스뱅크</option>
                    <option value="060">BOA은행</option>
                    <option value="062">중국공상은행</option>
                    <option value="103">SBI저축은행</option>
        </select>
    </div>

    <div class="form-item">
        <span class="form-label">결제자 이름</span>
        <input type="text" name="pay_acountname" id="pay_acountname" placeholder="결제자이름">
    </div>

    <div class="form-item">
        <span class="form-label">계좌번호 <span class="red-text">(-빼고 입력해주세요)</span></span></span>
        <input type="text" id="pay_acountnumber" name="pay_acountnumber" placeholder="결제번호(계좌,통신번호)"><p>
        <input type="button" value="계좌인증" id="account_btn" onclick="account_check()">
        <input type='hidden' id = 'request_no'>
        <input type='hidden' id = 'res_uniq_id'>
        <input type='hidden' id = 'otp_result_cd'> <!-- 계좌otp 인증성공시 value = success  -->
        <input type='hidden' id = 'smsotp_result_cd'> <!-- smsotp 인증성공시 value = success  -->
    </div>

    <p class="highlight-text">*미리보기 이미지에서 (인)란 클릭후 서명작성 완료후 가입신청이 완료됩니다*</p>

    <input type="button" id="nextbutton" value="가입신청서 미리보기" onclick="addjoin()">
    
</div>

    </div>

    <div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h1>올마이샵 회원가입 완료!</h1>
        <p>정회원 가입시 더욱더 많은혜택으로 올마이샵을 이용하실수 있습니다.</p>
        <div class="modal-button-container">
            <input type="button" onclick="closeModal()" value="정회원가입하기">
            <input type="button" onclick="goallmyshop()" value="올마이샵 바로가기">
        </div>
    </div>
</div>

<script>

var memberCount = <?= json_encode($member_count) ?>;
var memberCount2 = <?= json_encode($member_count2) ?>;
var cmsMemberCount = <?= json_encode($cms_member_count) ?>;
var cmsPermCount = <?= json_encode($cms_perm_count) ?>;

function showForm(formType) {
    var generalForm = document.getElementById('generalForm');
    var premiumForm = document.getElementById('premiumForm');


    generalForm.style.display = 'none';
    premiumForm.style.display = 'none';


    if (formType === 'general') {
        if (memberCount >= 1 || memberCount2 >=1) {  
            alert("이미 올마이샵 회원입니다.");
        } else {
            generalForm.style.display = 'block';
        }
    } else if (formType === 'premium') {
        if (cmsMemberCount >= 1 || cmsPermCount >= 1) { 
            alert("이미 정회원입니다.");
        } else {
            premiumForm.style.display = 'block';
        }
    }
}

var idChecked = false;  

function openIdCheckPopup() {
    var width = 500;
    var height = 500;
    var top = 100;
    var left = -1200;
    var userId = document.getElementById('id_input').value;
    if (userId) {
        var popupWindow = window.open('id_check_popup.php?user_id=' + userId, 'popup_window', 'width=' + width + ',height=' + height + ',left=' + left + ',top=' + top + ',location=no,status=no,scrollbars=yes');
        popupWindow.focus();
    } else {
        alert('아이디를 입력해주세요.');
    }
}


function setIdCheckStatus(checked, userId) {
    idChecked = checked;
    document.getElementById('id_check_message').style.display = checked ? 'none' : 'block';
    if (checked) {
        document.getElementById('id_input').value = userId; 
    }
}

function validateId() {
    var input = document.getElementById('id_input');
    var valid = /^[a-zA-Z0-9]+$/;  

    if (!valid.test(input.value)) {
        input.value = input.value.replace(/[^a-zA-Z0-9]/g, ''); 
        alert('아이디는 영문자와 숫자만 입력 가능합니다.');
    }
}
function validatePassword() {
    var password = document.getElementById('password').value;
    var password2 = document.getElementById('password2').value;
    var matchMessage = document.getElementById('password_match');

    if (password2.length > 0) { 
        if (password === password2) {
            matchMessage.style.display = 'none';
        } else {
            matchMessage.style.display = 'block'; 
        }
    } else {
        matchMessage.style.display = 'none'; 
    }
}

function validatePhoneNumber(input) {
    input.value = input.value.replace(/\D/g, '');
}


function checkOnlyOne(checkedId) {
    var checkboxes = document.getElementsByName('pay_amount');
    checkboxes.forEach((item) => {
        if (item.id !== checkedId) item.checked = false;
    });
}


function account_check(){

document.getElementById('account_btn').disabled = true;
var bank_cd = document.getElementById('pay_bankcode').value;
var account_id = document.getElementById('pay_acountnumber').value;
var name = document.getElementById('pay_acountname').value;
var otp_result = document.getElementById('otp_result_cd').value;



if(bank_cd && account_id && name){

var url=`./check_account.php?bank_cd=${bank_cd}&account_id=${account_id}&name=${name}`;

fetch(url).then(function(response){ 

    return response.text(); 
    
}).then(function(response){ 
    console.log(response);
    const topics = JSON.parse(response)
    if(topics.dataBody.result_cd == '0000')
    {
        alert("입력하신 계좌로 1원송금되었습니다.\n 인증번호를 확인해주세요");
        
        var request_no = topics.dataBody.request_no;
        var res_uniq_id = topics.dataBody.res_uniq_id;
        var width = 490;
        var height = 691;
        var left = -1200;
        var top = 100;
        var url = `https://cms.mhgkorea.com/cms_wpopup.php?request_no=${request_no}&res_uniq_id=${res_uniq_id}`;
        window.open( url , 'popup_window', 'width=' + width + ',height=' + height + ',left=' + left + ',top=' + top + ',location=no,status=no,scrollbars=yes');
    }else if(topics.dataBody.rsp_cd == "S605"){
        document.getElementById('account_btn').disabled = false;
        alert("1일 기준 동일 계좌번호로 10회 초과하였습니다.\n 해당 계좌의 인증 요청에 대해 당일 기한으로 제한됩니다.");
        return;
    }else{
        document.getElementById('account_btn').disabled = false;
        alert("이름과 계좌번호를 확인해주세요");
        return;
    }
    
})
}else if(bank_cd =="null"){
document.getElementById('account_btn').disabled = false;
alert("해당 은행을 선택해주세요.");
return;
}else if(!name){
document.getElementById('account_btn').disabled = false;
alert("계좌주 성명을 입력해주세요.");
return;
}else if(!account_id){
document.getElementById('account_btn').disabled = false;
alert("계좌번호를 입력해주세요.");
return;
}
}

function addjoin() {
    // var otp_result_cd = document.getElementById("otp_result_cd").value; // 계좌인증 결과 가져오기

    // if(otp_result_cd != "success") {
    //     alert("계좌인증을 해주세요");
    //     return false;
    // }

    // 입력 필드 검증
    var name = document.getElementById("name2").value.trim();
    var tel = document.getElementById("tel2").value.trim();
    var address = document.getElementById("address2").value.trim();
    var pay_acountname = document.getElementById("pay_acountname").value.trim();
    var pay_acountnumber = document.getElementById("pay_acountnumber").value.trim();
    var m_position = document.getElementById("m_position").value.trim();

    if (!name || !tel || !address || !pay_acountname || !pay_acountnumber || !m_position) {
        alert("모든 필드를 채워주세요.");
        return false;
    }


    var region = document.getElementById("region").value;
    var pay_bankcode = document.getElementById("pay_bankcode").value;
    if (region === "" || pay_bankcode === "null") {
        alert("지역과 결제은행을 선택해주세요.");
        return false;
    }


    var payAmountChecked = document.querySelector('input[name="pay_amount"]:checked');
    if (!payAmountChecked) {
        alert("회비 옵션을 선택해주세요.");
        return false;
    }

    // 입력 데이터를 서버로 전송
    var selectElement = document.getElementById("pay_bankcode");
    var selectedText = selectElement.options[selectElement.selectedIndex].text;
    document.getElementById("text_bankcode").value = selectedText;
    var selectElement2 = document.getElementById("region");
    var selectedText2 = selectElement2.options[selectElement2.selectedIndex].text;
    document.getElementById("text_region").value = selectedText2;

    document.form_chk.action="ex_cms_myshop2.php";
    document.form_chk.submit();
}

function simpleaddjoin() {
    var userId = document.getElementById('id_input').value.trim();
    var password = document.getElementById('password').value;
    var passwordConfirm = document.getElementById('password2').value;
    var name = document.getElementById('name').value.trim();
    var tel = document.getElementById('tel').value.trim();
    var address = document.getElementById('address').value.trim();

    // 필수 입력 필드 검사
    if (!userId || !name || !tel || !address || !password || !passwordConfirm) {
        alert("모든 가입 정보를 입력해야 합니다.");
        return false;
    }

    if (!idChecked) {
        alert("아이디 중복 확인을 해주세요.");
        return false;
    }

    if (password.length < 6 || password.length > 15) {
        alert("비밀번호는 6자리에서 15자리 사이여야 합니다.");
        return false;
    }

    if (password !== passwordConfirm) {
        alert("입력하신 비밀번호가 일치하지 않습니다.");
        return false;
    }

    let params = new URLSearchParams();
    params.append('name', name);
    params.append('tel', tel);
    params.append('address', address);
    params.append('id_input', userId);
    params.append('password', password);

    fetch('check_counts.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded', 
        },
        body: params 
    })
    .then(response => response.json())
    .then(data => {
        if(data.member_count >= 1) {
            alert("이미 올마이샵에 가입된 회원입니다.");
        } else {
            var modal = document.getElementById('myModal');
            modal.style.display = 'block';
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function closeModal() {
    document.getElementById('myModal').style.display = 'none';
    showForm('premium');  // 모달 닫을 때 'premium' 폼을 보여줍니다.
}

function goallmyshop()
{
    document.form_chk.action="myshop_finish.php";
    document.form_chk.submit();
}


</script>
</body>
</html>