<!DOCTYPE html>
<html lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=yes">
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.2.0/dist/signature_pad.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.3.2/html2canvas.min.js"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.3.1/jspdf.umd.min.js"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.debug.js"></script>
    <link rel="stylesheet" href="css/regular.css?v=1">
    <link rel="shortcut_icon" href="#">
    <title>정회원 가입</title>
    <?php
    include 'dbconn.php';
    session_start();
        $action_url = "https://www.allmyshop.co.kr";
        $useridx = $_GET['data'];

        date_default_timezone_set('Asia/Seoul');
    ?>
</head>
<?php
$name2 = $_REQUEST['name2'] ?? '';
$mobileno = $_REQUEST['mobileno'] ?? '';
$birthdate = $_REQUEST['birthdate'] ?? '';
// $n_address = $_REQUEST['n_address'] ?? '';
// $roadAddrPart1 = $_REQUEST['roadAddrPart1']??'';
// $addrDetail = $_REQUEST['addrDetail']??'';
// $zipNo = $_REQUEST['zipNo']??'';


$member_query = "SELECT `name`, email, AES_DECRYPT(UNHEX(tel), 'myshop') AS tel,
AES_DECRYPT(UNHEX(address), 'myshop') AS address,
AES_DECRYPT(UNHEX(daddress), 'myshop') AS daddress
FROM member
WHERE useridx = ?";
$stmt = mysqli_prepare($conn, $member_query);
mysqli_stmt_bind_param($stmt, 's', $useridx);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

$name2 = $row['name'];
$email = $row['email'];
$tel = $row['tel'];
$address = $row['address'];
$daddress = $row['daddress'];

$mobileno = str_replace("-", "", $tel);

$full_address = $address . '/' . $daddress;
    //**************************************************************************************************************
    //NICE평가정보 Copyright(c) KOREA INFOMATION SERVICE INC. ALL RIGHTS RESERVED
    
    //서비스명 :  체크플러스 - 안심본인인증 서비스
    //페이지명 :  체크플러스 - 메인 호출 페이지
    
    //보안을 위해 제공해드리는 샘플페이지는 서비스 적용 후 서버에서 삭제해 주시기 바랍니다.
    //방화벽 정보 : IP 121.131.196.215 , Port 80, 443     
    //**************************************************************************************************************
    
    session_start();
    
    $sitecode = "CC804";			// NICE로부터 부여받은 사이트 코드
    $sitepasswd = "X3iTSqB8lfUr";			// NICE로부터 부여받은 사이트 패스워드
    
    // Linux = /절대경로/ , Window = D:\\절대경로\\ , D:\절대경로\
    $cb_encode_path = "/var/www/html/niceauthen/CPClient";
	/*
	┌ cb_encode_path 변수에 대한 설명  ──────────────────────────────────
		모듈 경로설정은, '/절대경로/모듈명' 으로 정의해 주셔야 합니다.
		
		+ FTP 로 모듈 업로드시 전송형태를 'binary' 로 지정해 주시고, 권한은 755 로 설정해 주세요.
		
		+ 절대경로 확인방법
		  1. Telnet 또는 SSH 접속 후, cd 명령어를 이용하여 모듈이 존재하는 곳까지 이동합니다.
		  2. pwd 명령어을 이용하면 절대경로를 확인하실 수 있습니다.
		  3. 확인된 절대경로에 '/모듈명'을 추가로 정의해 주세요.
	└────────────────────────────────────────────────────────────────────
	*/
    
    $authtype = "";      		// 없으면 기본 선택화면, M(휴대폰), X(인증서공통), U(공동인증서), F(금융인증서), S(PASS인증서), C(신용카드)
    	
    $customize 	= "";		//없으면 기본 웹페이지 / Mobile : 모바일페이지 (default값은 빈값, 환경에 맞는 화면 제공)
    
    // $reqseq = "$marketing_agree/$email_agree/$sms_agree/$push_agree";     // 요청 번호, 이는 성공/실패후에 같은 값으로 되돌려주게 되므로
                                    // 업체에서 적절하게 변경하여 쓰거나, 아래와 같이 생성한다.
									
    // 실행방법은 백틱(`) 외에도, 'exec(), system(), shell_exec()' 등등 귀사 정책에 맞게 처리하시기 바랍니다.
    // 위의 실행함수를 통해 아무런 값도 출력이 안될 경우 쉘 스크립트 오류출력(2>&1)을 통해 오류 확인 부탁드립니다.
    $reqseq = `$cb_encode_path SEQ $sitecode`;
    
    // CheckPlus(본인인증) 처리 후, 결과 데이타를 리턴 받기위해 다음예제와 같이 http부터 입력합니다.
    // 리턴url은 인증 전 인증페이지를 호출하기 전 url과 동일해야 합니다. ex) 인증 전 url : http://www.~ 리턴 url : http://www.~
    $returnurl = "https://cms.mhgkorea.com/ini_sucess.php";	// 성공시 이동될 URL
    $errorurl = "https://cms.mhgkorea.com/checkplus_fail.php";		// 실패시 이동될 URL
	
    // reqseq값은 성공페이지로 갈 경우 검증을 위하여 세션에 담아둔다.
    
    $_SESSION["REQ_SEQ"] = $reqseq;

    // 입력될 plain 데이타를 만든다.
    $plaindata = "7:REQ_SEQ" . strlen($reqseq) . ":" . $reqseq .
				 "8:SITECODE" . strlen($sitecode) . ":" . $sitecode .
				 "9:AUTH_TYPE" . strlen($authtype) . ":". $authtype .
				 "7:RTN_URL" . strlen($returnurl) . ":" . $returnurl .
				 "7:ERR_URL" . strlen($errorurl) . ":" . $errorurl .
				 "9:CUSTOMIZE" . strlen($customize) . ":" . $customize;
    
    $enc_data = `$cb_encode_path ENC $sitecode $sitepasswd $plaindata`;

    $returnMsg = "";

    if( $enc_data == -1 )
    {
        $returnMsg = "암/복호화 시스템 오류입니다.";
        $enc_data = "";
    }
    else if( $enc_data== -2 )
    {
        $returnMsg = "암호화 처리 오류입니다.";
        $enc_data = "";
    }
    else if( $enc_data== -3 )
    {
        $returnMsg = "암호화 데이터 오류 입니다.";
        $enc_data = "";
    }
    else if( $enc_data== -9 )
    {
        $returnMsg = "입력값 오류 입니다.";
        $enc_data = "";
    }
?>
<body>
    <form class="regular_member" action="" id="form_chk" name="form_chk" method="post">
    <input type="hidden" name="m" value="checkplusService">				<!-- 필수 데이타로, 누락하시면 안됩니다. -->
    <input type="hidden" name="EncodeData" value="<?= $enc_data ?>">		<!-- 위에서 업체정보를 암호화 한 데이타입니다. -->
    <input type="hidden" name="inputYn" id="inputYn">
    <input type="hidden" name="roadFullAddr" id="roadFullAddr">
    <input type="hidden" name="roadAddrPart1" id="roadAddrPart1" value="<?=$roadAddrPart1?>">
    <input type="hidden" name="jibunAddr" id="jibunAddr">
    <input type="hidden" name="addrDetail" id="addrDetail" value="<?=$addrDetail?>"> <!--상세주소-->
    <input type="hidden" name="zipNo" id="zipNo" value="<?=$zipNo?>"> <!-- 우편번호 -->
    <input type="hidden" name="action_url" id="action_url" value="<?=$action_url?>">
    <input type="hidden" name="useridx" id="useridx" value="<?=$useridx?>">
    <input type="hidden" name="text_bankcode" id="text_bankcode">

        <div class="regular_div" id="content-to-capture"> <!-- 전체 div -->

            <img class="regular_img" src="img/regular.png">

            <div class="name_div"> <!-- 신청자 이름 생년월일 div -->

            <input type="text" class="r_name" id="name2" name="name" value ="<?=$name2?>" placeholder="신청인 이름" oninput="name_input(this)">

            <input type="text" class="r_birth" id="birthdate" name="birth" value="<?=$birthdate?>" placeholder="주민번호앞자리" oninput="birth_input(this)" maxlength="6"> <!-- 신청자 생년월일 -->

            </div><!-- 신청자 이름 생년월일 div -->

            <div class="address_div"> <!-- 주소 div -->

                <input type="text" class="r_address" id="n_address" name="n_address" value="<?=$full_address?>" placeholder="신청인 주소"  readonly > <!-- 도로명주소 -->
                <input type="button" class="address_btn" value="주소찾기" onclick="gojuso()">

            </div>

            <div class="r_phone_div"> <!-- 신청인 휴대폰,이메일 div -->

                <input type="text" class="r_phone" id="mobileno" name="phone_number" value="<?=$mobileno?>" placeholder="휴대폰 번호" oninput="phone_input(this)"> <!-- 휴대폰 -->
                <input type="button" class="mycheck" id="aaaa" value="본인인증" onclick="aaa()">
                <input type="email" class="r_email" id="email" name="email" value="<?=$email?>" placeholder="이메일" oninput="email_input(this)"> <!-- 이메일 -->

            </div><!-- 신청인 휴대폰,이메일 div -->

            <div class="r_region_div"> <!-- 신청인 지역,직책 div -->

                <select class="r_region" name="region" id="region" > <!-- 신청인 지역 -->
                    <option value="null">지역 선택</option>
                    <option value="01">서울</option>
                    <option value="02">부산</option>
                    <option value="03">인천</option>
                    <option value="04">대구</option>
                    <option value="05">대전</option>
                    <option value="06">광주</option>
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

                <input type="text" class="rm_position" id="m_position" name="m_position"  placeholder="신청인 직책" oninput="position_input(this)"> <!-- 신청인 직책 -->

            </div><!-- 신청인 지역,직책 div -->

            <div class="pay_div"> <!-- 회비 div -->

                <input type="checkbox" class="pay1" name="pay_amount_1" id="pay_amount_1" value="1"> <!-- 월 회비 10,000원 -->
                <input type="checkbox" class="pay2" name="pay_amount_1" id="pay_amount_1" value="2"> <!-- 연 회비 100,000원 -->

            </div><!-- 회비 div -->

            <div class="method_div"> <!-- 결제 방식 div -->

                <input type="checkbox" class="method1" name="pay_method_1" id="pay_method_1" value="1" checked="checked" onclick="return false;"> <!-- 자동 이체 매월 25일(CMS 월납) -->

            </div><!-- 결제 방식 div -->

            <div class="amount_div"> <!-- 결제자 은행, 결제자 이름 div -->

                <select class="bank_code" name="pay_bankcode" id="pay_bankcode"> <!-- 은행명, 은행코드 -->
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

                <input type="text" class="amount" id="pay_acountname" name="pay_acountname" placeholder="결제자 이름" oninput="acount_name_input(this)"> <!-- 결제자 이름 -->

            </div><!-- 결제자 은행 결제자 이름 div -->

            <div class="account_div"> <!--≈결제번호(계좌,통신번호) div -->
                <input type="text" class="account_num" id="pay_acountnumber" name="pay_acountnumber" placeholder="결제번호(계좌,통신번호)" oninput="acount_number_input(this)"> <!-- 결제번호(계좌,통신번호) -->
                <input type="button" class="account_check" value="계좌인증" id='account_btn' onclick="account_check()">
                <input type='hidden' id = 'request_no'>
                <input type='hidden' id = 'res_uniq_id'>
                <input type='hidden' id = 'otp_result_cd'> <!-- 계좌otp 인증성공시 value = success  -->
                <input type='hidden' id = 'smsotp_result_cd'> <!-- smsotp 인증성공시 value = success  -->
            </div>

            <div class="front_div"> <!-- 결제자 주민번호 앞자리 div -->
                <input type="text" class="front_num" id="c_name" name="c_name" placeholder="주민번호 앞자리" maxlength="6"> <!-- 주민번호 앞자리 -->
                <input type="password" class="back_num" id="back_number" name="back_number" placeholder="주민번호 뒷자리" maxlength="7">
            </div>

            <div class="pic_div"> <!-- 개인정보 동의 div -->
                <input type="checkbox" class="pic1" name="pic_agree1" id="pic_agree1" value="1"> <!-- 개인정보동의 1 -->
                <input type="checkbox" class="pic2" name="pic_agree2" id="pic_agree2" value="2"> <!-- 개인정보동의 2 -->
            </div>

            <div class="pio_div"> <!-- 개인정보 3자 동의 div -->
                <input type="checkbox" class="pio1" name="pio_agree1" id="pio_agree1" value="1"> <!-- 개인정보동의 3자 1 -->
                <input type="checkbox" class="pio2" name="pio_agree2" id="pio_agree2" value="2"> <!-- 개인정보동의 3자 2 -->
            </div>

            <div class="t_pay_div"> <!-- 결제 시작 연도,월,일 div -->
                <input type="text" class="t_year" id="pay_year" name="pay_year" placeholder="작성" value="<? echo date("Y") ?>" readonly/> <!-- 시작 연도 -->
                <input type="text" class="t_month" id="pay_month" name="pay_month" placeholder="작성" value="<? echo date("m") ?>" readonly/> <!-- 시작 월 -->
                <input type="text" class="t_day" id="pay_date" name="pay_date" placeholder="작성" value="<? echo date("d") ?>" readonly/> <!-- 시작 일 -->
            </div>

            <div class="sign_div">
                <input type="text" class="s_name" id="name3" name="name3" value="<?=$name2?>" placeholder="신청인 이름" readonly>
                <div class="pop" onclick="pop_up('0','0','0')"></div> <!-- 팝업 div -->
            </div>

            <div class="sign_div2">
                <input type="text" class="p_name" name="name4" id="name4" placeholder="결제자 이름">
                <div class="pop2" onclick="pop_up2('0','0','0')"></div> <!-- 팝업 div -->
            </div>
            <div class="compleate"> <!-- 신청 버튼 -->
                <input class="com_btn" id="uploadnext" type="button" value="신청하기" onclick="onnext()">
            </div>

            <div class="white_div"></div> <!-- 그냥 가리는 div 신경안써도됨 -->

            <div class="terms_div">
                <input type="button" class="terms_btn" value="이용약관 보기" onclick="terms_btn('0','0','0')">
            </div>



        </div> <!-- 전체 div 끝 -->

<img id="someImageElement" src="" alt="Captured image will appear here" />
    </form>
    <script language="javascript">

    //     function name_input(input) {
    //     // 입력된 값에서 영어 또는 한글이 아닌 문자를 제거합니다.
    //     input.value = input.value.replace(/[^a-zA-Z가-힣ㆍᆞᆢㄱ-ㅎㅏ-ㅣ\x20]/g, '');
    // }
    function birth_input(input) {
        // 입력된 값에서 영어 또는 한글이 아닌 문자를 제거합니다.
        input.value = input.value.replace(/[^0-9]/g, '');
    }
    
    function phone_input(input) {
        // 입력된 값에서 영어 또는 한글이 아닌 문자를 제거합니다.
        input.value = input.value.replace(/[^0-9]/g, '');
    }

    function email_input(input) {
        // 입력된 값에서 영어 또는 한글이 아닌 문자를 제거합니다.
        input.value = input.value.replace(/[^0-9a-zA-Z@\.]/g, '');
    }

    // function position_input(input) {
    //     // 입력된 값에서 영어 또는 한글이 아닌 문자를 제거합니다.
    //     input.value = input.value.replace(/[^a-zA-Z가-힣ㆍᆞᆢㄱ-ㅎㅏ-ㅣ\x20]/g, '');
    // }

    // function acount_name_input(input) {
    //     // 입력된 값에서 영어 또는 한글이 아닌 문자를 제거합니다.
    //     input.value = input.value.replace(/[^a-zA-Z가-힣ㆍᆞᆢㄱ-ㅎㅏ-ㅣ\x20]/g, '');
    // }
    function acount_number_input(input) {
        // 입력된 값에서 영어 또는 한글이 아닌 문자를 제거합니다.
        input.value = input.value.replace(/[^0-9]/g, '');
    }
        function viewinfo(data)
        {
            window.customermanager.viewinfo(data);
        }

        function jusoresult(data){
            
            var Sdata = data.split(',');
            var n_data = Sdata[0] + "/" +Sdata[1];
            
            document.getElementById('n_address').value = n_data;
            document.getElementById('roadAddrPart1').value = Sdata[0];
            document.getElementById('addrDetail').value = Sdata[1];
            document.getElementById('zipNo').value = Sdata[2];
        }

        function optresult(data){
            document.getElementById('otp_result_cd').value =data;
            
		    var ms = "계좌 인증 완료되었습니다";
            var msg="seterror</>"+ms;
            viewinfo(msg);
            document.getElementById('pay_bankcode').readOnly = true;
            document.getElementById('pay_acountnumber').readOnly = true;
            document.getElementById('pay_acountname').readOnly = true;
        }

        function smsoptresult(data){
            document.getElementById('smsotp_result_cd').value =data;
		    var ms = "휴대폰 인증 완료되었습니다";
            var msg="seterror</>"+ms;
            viewinfo(msg);
            document.getElementById('mobileno').readOnly = true;
        }

        function aaa(){
            document.getElementById('aaaa').disabled = true;
            var telnum = document.getElementById('mobileno').value;
            var length= telnum.length;
            if(telnum.length == 11){
            var url=`./CMSSendSMS.php?receiver=${telnum}`;
                fetch(url).then(function(response){ 
                    return response.text();
                }).then(function(response){ 
                    var otp_num= Number(response);
                    var ms = "입력하신 전화번호로 인증번호가 발송되었습니다.";
                    var msg="seterror</>"+ms;
                    viewinfo(msg);
                    var url = `https://cms.mhgkorea.com/cms_sms_popup.php?otp_num=${otp_num}`;
                    var msg ="popupView</>"+url;
                    viewinfo(msg);  
                })
            }else{
                document.getElementById('aaaa').disabled = false;
                var ms = "휴대폰 번호를 정확히 입력해주세요";
                
                var msg="seterror</>"+ms;
                viewinfo(msg);
                return;  
            }
        }
    
    function account_check(){

                 document.getElementById('account_btn').disabled = true;
                var bank_cd = document.getElementById('pay_bankcode').value;
                var account_id = document.getElementById('pay_acountnumber').value;
                var name = document.getElementById('pay_acountname').value;
                var url=`./check_account.php?bank_cd=${bank_cd}&account_id=${account_id}&name=${name}`;

                fetch(url).then(function(response){ 
                    return response.text();   
                }).then(function(response){ 
                    const topics = JSON.parse(response)
                    if(topics.dataBody.result_cd == '0000')
                    {
                        document.getElementById('request_no').value = topics.dataBody.request_no;
                        document.getElementById('res_uniq_id').value = topics.dataBody.res_uniq_id;
                        var request_no = topics.dataBody.request_no;
                        var res_uniq_id = topics.dataBody.res_uniq_id;
                        var ms = "입력하신 계좌로 1원이 송금되었습니다. 인증번호를 확인해주세요";
                        var msg="seterror</>"+ms;
		                viewinfo(msg);
                        var url = `https://cms.mhgkorea.com/cms_popup.php?request_no=${request_no}&res_uniq_id=${res_uniq_id}`;
                        var msg="popupView</>"+url;
		                viewinfo(msg);             
                    }else{
                        document.getElementById('account_btn').disabled = false;
                        alert("이름과 계좌번호를 확인해주세요");
                        return;

                    }
                })

    }


    document.documentElement.style.zoom = "33%";

    function gojuso(){
            var url = `https://cms.mhgkorea.com/cms_jusoPopup.php`;
            var msg="popupView</>"+url;
            viewinfo(msg);  
        }
    </script>    
</body>

<script language="javascript">
    // 체크박스를 클릭할 때 실행되는 함수
    function handleCheckboxClick(clickedCheckbox, containerClass) {
        // 모든 체크박스 요소를 가져옴
        var checkboxes = document.querySelectorAll('.' + containerClass + ' input[type="checkbox"]');
        
        // 선택한 체크박스를 제외한 모든 체크박스를 반복하여
        // 선택 상태를 해제함
        checkboxes.forEach(function(checkbox) {
            if (checkbox !== clickedCheckbox) {
                checkbox.checked = false;
            }
        });
    }

    // 각 체크박스 요소에 onclick 이벤트 핸들러 추가
    document.querySelectorAll('.pay_div input[type="checkbox"]').forEach(function(checkbox) {
        checkbox.addEventListener('click', function() {
            handleCheckboxClick(this, 'pay_div'); // 현재 클릭된 체크박스를 넘겨줌
        });
    });

    // 각 체크박스 요소에 onclick 이벤트 핸들러 추가
    document.querySelectorAll('.method_div input[type="checkbox"]').forEach(function(checkbox) {
        checkbox.addEventListener('click', function() {
            handleCheckboxClick(this, 'method_div'); // 현재 클릭된 체크박스를 넘겨줌
        });
    });
        // 각 체크박스 요소에 onclick 이벤트 핸들러 추가
        document.querySelectorAll('.pic_div input[type="checkbox"]').forEach(function(checkbox) {
        checkbox.addEventListener('click', function() {
            handleCheckboxClick(this, 'pic_div'); // 현재 클릭된 체크박스를 넘겨줌
        });
    });
            // 각 체크박스 요소에 onclick 이벤트 핸들러 추가
            document.querySelectorAll('.pio_div input[type="checkbox"]').forEach(function(checkbox) {
        checkbox.addEventListener('click', function() {
            handleCheckboxClick(this, 'pio_div'); // 현재 클릭된 체크박스를 넘겨줌
        });
    });

    function terms_btn(idx,mname,useridx)
    {
        var url = "https://cms.mhgkorea.com/moduDelivery_app_terms.php";
        var msg = "loadwebview</>" + url + "</>" + idx + "</>" + mname + "</>" + useridx;
        viewinfo(msg);
    }

    function pop_up2(idx,mname,useridx) {
    var url = "https://cms.mhgkorea.com/sign_popup2.php";
    var msg="loadwebview</>" + url + "</>" + idx + "</>" +mname+ "</>"+ useridx;
    viewinfo(msg);
}
function pop_up(idx,mname,useridx) {
    var url = "https://cms.mhgkorea.com/sign_popup.php";
    var msg="loadwebview</>" + url + "</>" + idx + "</>" +mname+ "</>"+ useridx;
    viewinfo(msg);
}
function receiveSignature(data) {
    var img = document.createElement('img');
    img.src = data;
    img.id = "sign_pop1";

    var popDiv = document.querySelector('.pop'); 
    popDiv.innerHTML = ''; 
    popDiv.appendChild(img); 
}

function receiveSignature2(data) {
    var img2 = document.createElement('img');
    img2.src = data;
    img2.id = "sign_pop2";

    var popDiv = document.querySelector('.pop2'); 
    popDiv.innerHTML = ''; 
    popDiv.appendChild(img2); 
}

document.addEventListener('DOMContentLoaded', function () {
    var content = document.getElementById('content-to-capture');
    var computedFontSize = window.getComputedStyle(content, null).getPropertyValue('font-size');
    var fontSize = parseFloat(computedFontSize);

    // 만약 폰트 크기가 너무 크면 조절
    if (fontSize > 18) {
        content.style.fontSize = '18px';
    }
});

function onnext() {
    document.getElementById('uploadnext').disabled = true;

    var selectElement = document.getElementById("pay_bankcode");
    var selectedText = selectElement.options[selectElement.selectedIndex].text;
    document.getElementById("text_bankcode").value = selectedText;
    var input_name = document.getElementById("name2").value;
    var input_birthdate = document.getElementById("birthdate").value;
    var input_n_address = document.getElementById("n_address").value;
    var input_mobileno = document.getElementById("mobileno").value;
    var input_email = document.getElementById("email").value;
    var input_region = document.getElementById("region").value;
    var input_m_position = document.getElementById("m_position").value;
    var input_pay_bankcode = document.getElementById("pay_bankcode").value;
    var input_pay_acountnumber = document.getElementById("pay_acountnumber").value;
    var input_c_name = document.getElementById("c_name").value;
    var input_back_number = document.getElementById("back_number").value;
    var otp_result_cd = document.getElementById("otp_result_cd").value;
    var smsotp_result_cd = document.getElementById("smsotp_result_cd").value;
    var pay_acountname_ip = document.getElementById("pay_acountname").value;
    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    // 체크박스 그룹들을 선택합니다.
    var payCheckboxes = document.querySelectorAll('.pay_div input[type="checkbox"]');
    var methodCheckboxes = document.querySelectorAll('.method_div input[type="checkbox"]');
    var picCheckboxes = document.querySelectorAll('.pic_div input[type="checkbox"]');
    var pioCheckboxes = document.querySelectorAll('.pio_div input[type="checkbox"]');

    if(input_name == "")
    {
        document.getElementById('uploadnext').disabled = false;
        var ms = "이름을 입력해주세요";
        var msg="seterror</>"+ms;
        viewinfo(msg);
        return;
    }
    if(input_birthdate.length === 0 || input_birthdate.length !== 6) {
    // 버튼을 다시 활성화합니다.
    document.getElementById('uploadnext').disabled = false;
    
    // 사용자에게 경고 메시지를 보여줍니다.
    var ms = "생년월일을 6자리로 입력해주세요";
    var msg = "seterror/>" + ms;
    viewinfo(msg);
    
    // 여기서 함수 실행을 중단합니다.
    return;
}
    if(input_n_address == "")
    {
        document.getElementById('uploadnext').disabled = false;
        var ms = "주소를 입력해주세요";
        var msg="seterror</>"+ms;
        viewinfo(msg);
        return;
    }
    if(input_mobileno == "")
    {
        document.getElementById('uploadnext').disabled = false;
        var ms = "핸드폰 번호를 입력해주세요";
        var msg="seterror</>"+ms;
        viewinfo(msg);
        return;
    }
    if(smsotp_result_cd != "success")
    {
        document.getElementById('uploadnext').disabled = false;
        var ms = "문자 인증을 해주세요";
        var msg="seterror</>"+ms;
        viewinfo(msg);
        return;
    }
    if(input_email == "")
    {
        document.getElementById('uploadnext').disabled = false;
        var ms = "이메일을 입력해주세요";
        var msg="seterror</>"+ms;
        viewinfo(msg);
        return;
    }
    if(!emailRegex.test(input_email))
    {
        document.getElementById('uploadnext').disabled = false;
        var ms = "올바른 이메일을 입력해 주세요";
        var msg="seterror</>"+ms;
        viewinfo(msg);
        return;
    }
    if(input_region == "null")
    {
        document.getElementById('uploadnext').disabled = false;
        var ms = "지역을 선택해주세요";
        var msg="seterror</>"+ms;
        viewinfo(msg);
        return;
    }
    if(input_m_position == "")
    {
        document.getElementById('uploadnext').disabled = false;
        var ms = "신청인 직책을 입력해주세요";
        var msg="seterror</>"+ms;
        viewinfo(msg);
        return;
    }
    if (!isAtLeastOneChecked(payCheckboxes))
    {
        document.getElementById('uploadnext').disabled = false;
        var ms = "회비를 선택해주세요";
        var msg="seterror</>"+ms;
        viewinfo(msg);
        return;
    }
    if (!isAtLeastOneChecked(methodCheckboxes))
    {
        document.getElementById('uploadnext').disabled = false;
        var ms = "결제방식을 선택해주세요";
        var msg="seterror</>"+ms;
        viewinfo(msg);
        return;
    }
    if(input_pay_bankcode == "null")
    {
        document.getElementById('uploadnext').disabled = false;
        var ms = "은행을 선택해주세요";
        var msg="seterror</>"+ms;
        viewinfo(msg);
        return;
    }
    if(input_pay_acountnumber == "")
    {
        document.getElementById('uploadnext').disabled = false;
        var ms = "계좌번호를 입력해주세요";
        var msg="seterror</>"+ms;
        viewinfo(msg);
        return;
    }
    if(pay_acountname_ip == "")
    {
        document.getElementById('uploadnext').disabled = false;
        var ms = "결제자 이름을 입력해주세요";
        var msg = "seterror</>"+ms;
        viewinfo(msg);
        return;
    }
    if(otp_result_cd != "success")
    {
        document.getElementById('uploadnext').disabled = false;
        var ms = "계좌인증을 해주세요";
        var msg="seterror</>"+ms;
        viewinfo(msg);
        return;
    }

    if(input_c_name.length === 0 || input_c_name.length !== 6) {
    // 버튼을 다시 활성화합니다.
    document.getElementById('uploadnext').disabled = false;
    
    // 사용자에게 경고 메시지를 보여줍니다.
    var ms = "주민번호 앞자리를 정확히 6자리로 입력해주세요";
    var msg = "seterror/>" + ms;
    viewinfo(msg);
    
    // 여기서 함수 실행을 중단합니다.
    return;
}
if(input_back_number.length === 0 || input_back_number.length !== 7) {
    // 버튼을 다시 활성화합니다.
    documeament.getElementById('uploadnext').disabled = false;
    
    // 사용자에게 경고 메시지를 보여줍니다.
    var ms = "주민번호 앞자리를 정확히 7자리로 입력해주세요";
    var msg = "seterror/>" + ms;
    viewinfo(msg);
    
    // 여기서 함수 실행을 중단합니다.
    return;
}
    if (!isAtLeastOneChecked(picCheckboxes)) {
        document.getElementById('uploadnext').disabled = false;
        var ms = "개인정보 동의에 체크해주세요";
        var msg="seterror</>"+ms;
        viewinfo(msg);
        return;
    }
    if (!isAtLeastOneChecked(pioCheckboxes)) {
        document.getElementById('uploadnext').disabled = false;
        var ms = "개인정보 제3자 동의에 체크해주세요";
        var msg="seterror</>"+ms;
        viewinfo(msg);
        return;
    }

    if(document.getElementById("sign_pop1") == null)
    {
        document.getElementById('uploadnext').disabled = false;
        var ms = "신청자 사인을 해주세요";
        var msg="seterror</>"+ms;
        viewinfo(msg);
        return;
    }
    if(document.getElementById("sign_pop2") == null)
    {
        document.getElementById('uploadnext').disabled = false;
        var ms = "결제자 사인을 해주세요";
        var msg="seterror</>"+ms;
        viewinfo(msg);
        return;
    }

    window.scrollTo(0, 0);
    const content = document.getElementById("content-to-capture");
    const name2 = document.getElementById("name2").value;
    const region = document.getElementById("region").value;
    const sign_pop1 = document.getElementById("sign_pop1") ? document.getElementById("sign_pop1").src : '';
    const sign_pop2 = document.getElementById("sign_pop2") ? document.getElementById("sign_pop2").src : '';

    // html2canvas 옵션에서 높이 설정
    html2canvas(content, {
        scale: 1,  // 스케일 조정을 통해 이미지의 해상도를 높일 수 있습니다.
    logging: true, // 디버깅을 위해 로그 활성화
    useCORS: true // 외부 이미지 사용 시 CORS 문제 해결
    }).then(canvas => {
        var imgData = canvas.toDataURL('image/jpeg', 0.85);

        var formData = new FormData();
        formData.append('imgData', imgData);
        formData.append('name2', name2);
        formData.append('region', region);
        formData.append('sign_pop1', sign_pop1);
        formData.append('sign_pop2', sign_pop2);

        // 서버에 이미지 데이터를 전송하는 부분
        fetch('upload.php', {
            method: 'POST',
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            console.log('Success:', data);

            document.form_chk.action="cms_insert.php";
            document.form_chk.submit();
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });

}

function isAtLeastOneChecked(checkboxes) {
    for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i].checked) {
            return true;
        }
    }
    return false;
}

document.getElementById('pay_acountname').addEventListener('input', function() {
    document.getElementById('name4').value = this.value;
});
document.getElementById('name2').addEventListener('input', function() {
    document.getElementById('name3').value = this.value;
});
</script>

</html>