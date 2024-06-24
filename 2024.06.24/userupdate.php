<?php
session_start();
include "dbconn.php";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
 <HEAD>
 <TITLE>myshopMypage</TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
     body{
        height: 1600px;
        padding: 0;
        margin:0;
    }
    .container{
        margin-top: 5%;
        margin-left: 5%;
        height: 800px;
        width:95%;
    }
    .text{
        margin-top: 2%;
        background: #F9F9F9;
        border: 1px solid #D3D3D3;
        border-radius: 6px;
        opacity: 1;
        height: 6%;
        width: 73%;
        margin-right: 5%;
    }
    .text2{
        margin-top: 2%;
        background: #F9F9F9;
        border: 1px solid #D3D3D3;
        border-radius: 6px;
        opacity: 1;
        height: 6%;
        width: 96%;
        margin-right: 4%;
    }
    .spanclass{
        margin-top: 2%;
        text-align: left;
        font-size:18px;
        color: #3F3F3F;
    }
    .textbox{
        margin-top: 5%;
    }
    .update{
        background-color: #898989;
        color: #F9F9F9;
        border-radius: 4px;
        opacity: 1;
        width: 18%;
        height: 4%;
        border:none;
    }
    .updatebutton{
        margin-top: 15%;
        background: #F26052;
        color:#F9F9F9;
        border-radius: 4px;
        opacity: 1;
        width: 96%;
        border: none;
        height: 7%;
    }
    .update2{
        background-color: #898989;
        color: #F9F9F9;
        border-radius: 4px;
        opacity: 1;
        width: 96%;
        height: 4%;
        border:none;
    }
        .endgo{
            background: #F26052;
                color:#F9F9F9;
                border-radius: 4px;
                opacity: 1;
                width: 96%;
                border: none;
                height: 7%;
            }
            .ii{
                position: relative;
                width: 95%;
                float: left;
            }
            .i{
                
                        position: relative;
                        width: 95%;
                        border: 0.5px solid #E2E2E2;
                        float: left;
            }
</style>
<script language="javascript">
    //여기는 라이브러리 함수
	<?
    $iphone = "0";
		if($iphone=="0")
		{
	?>
	function viewinfo(data)
	{0
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
        function loadwebview2(idx,name,useridx)
	{   let hostaddress = "<?=$hostaddress?>";
        url = hostaddress+"/myshop_login.php";
		var msg="loadwebviewfinish3</>"+url+"</>"+idx+"</>"+name+"</>"+useridx;
		viewinfo(msg);
	}

    	function loadwebview(url,idx,name,useridx)
	{
		var msg="loadwebviewfinish</>"+url+"</>"+idx+"</>"+name+"</>"+useridx;
		viewinfo(msg);
	}
</script>
</HEAD>

    <?php
    $useridx = isset($_SESSION['useridx']) ? $_SESSION['useridx'] : 0;

    if ($useridx !== "0" && $useridx !== null) {
        $query = "SELECT * FROM member WHERE useridx = $useridx";
        $result = mysqli_query($conn, $query);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_array($result);



    $userquery = "SELECT AES_DECRYPT(unhex(tel), 'myshop') AS tel,id,name,email,nice_state,AES_DECRYPT(unhex(address), 'myshop') AS address,AES_DECRYPT(unhex(oaddress), 'myshop') AS oaddress,AES_DECRYPT(unhex(daddress), 'myshop') AS daddress,AES_DECRYPT(unhex(postcode), 'myshop') AS postcode FROM member Where useridx = $useridx";
    $userresult = mysqli_query($conn,$userquery);

    $userrow = mysqli_fetch_array($userresult);

    $checkquery = "SELECT AES_DECRYPT(unhex(postcode), 'myshop')AS postcode,AES_DECRYPT(unhex(old_address), 'myshop')AS old_address,
    AES_DECRYPT(unhex(detail_address), 'myshop')AS detail_address,address_status FROM destination WHERE useridx = '$useridx' && address_status = '1'";
    $checkresult = mysqli_query($conn, $checkquery);
    $checkrow = mysqli_fetch_assoc($checkresult);
    

    @$recommender = $_POST['recommender'];
    // echo $recommender."()";
    if($recommender){
        $updatequery = "UPDATE member set recommender = hex(aes_encrypt('$recommender','myshop')) Where useridx = $useridx";
        $updateresult = mysqli_query($conn,$updatequery);
        ?>
        <script>
                function printdlg2(msg,url,titlename){
            let hostaddress = "<?=$hostaddress?>";
            let useridx = "<?=$useridx?>";
            viewinfo("printdlg2</>"+msg+"</>"+url+"</>"+titlename+"</>"+useridx);
        }
            printdlg2('추천인 전화번호가 변경되었습니다',"userupdate.php",'정보변경');
            

            function printdlg22(msg,url,titlename){
            let useridx = "<?=$useridx?>";
            viewinfo("printdlg22</>"+msg+"</>"+url+"</>"+titlename+"</>"+useridx);
        }
        </script>
        <?
    }
	?>
    <?
    // print_r($_SESSION);
    ?>
    <form name="maincont" method="post" action="">
    <div class="container">
                
    

        <div class="textbox">
        <span class="spanclass">아이디</span><br>
        <input type="text" value="<?=$userrow['id']?>" class="text2" readonly>
        </div>


        <div class="textbox">
        <span class="spanclass">이름</span><br>
        <input type="text" value="<?=$userrow['name']?>" class="text2" readonly>
        </div>

        <div class="textbox">
        <input type="button"  onclick ="goupdate('0','비밀번호변경','<?=$useridx?>')"class="update2" value="비밀번호 변경">
        </div>

        

        <div class="textbox">
            <span class="spanclass">휴대폰번호(변경시, 인증된번호로 변경됩니다)</span><br>
            <input type="text" class="text" value="<?= $userrow['tel'] ?>" readonly>
            <input type="button" class="update" value="변경" onclick="tel_update('0','휴대폰 번호 변경','<?=$useridx?>')">
</div>
            <div class="textbox">
            <?php 
                if ($userrow['nice_state'] == '0'): 
            ?>
                <!-- nice_state가 0인 경우 인증 버튼 표시 -->
                <input type="button" class="update2" value="인증" onclick="adduction('0','인증','<?=$useridx?>')"><br>
            <?php else: ?>
                <!-- nice_state가 1인 경우 메시지 표시 -->
                <p style="color: #F26052;">인증이 완료된 회원입니다.</p>
            <?php endif; ?>
        </div>


        <div class="textbox">
        <span class="spanclass">이메일주소</span><br>
        <input type="text" class="text" name="email2" value="<?=$userrow['email']?>" readonly><input type="button" class="update" value="변경" onclick="email_update('0','이메일 변경','<?=$useridx?>')">
        </div>

        <div class="textbox">
        <span class="spanclass">기본주소지 관리</span><br>
        <input type="text" class="text" readonly value="<?=$userrow['address']." ".$userrow['daddress']?>"
        ><input type="button" class="update" value="변경" onclick="go_useraddress('0','기본 배송지 변경','<?=$useridx?>')">
            </div>

        <div class="textbox">
        <span class="spanclass">배송지 관리</span><br>
        <input type="text" class="text" readonly value="<?if(@$checkrow['address_status'] == '1'){
            echo $checkrow['postcode']." ".$checkrow['old_address']." ".$checkrow['detail_address'];
            }
            else{
                echo "기본 배송지가없습니다 등록해주세요.";
            }?>"><input type="button" class="update" value="변경" onclick="go_address('0','배송지 변경','<?=$useridx?>')">

<div class="textbox">
        <span class="spanclass">추천인 전화번호</span><br>
        <?
            $recomender = "SELECT AES_DECRYPT(unhex(recommender), 'myshop')AS recommender from member where useridx = $useridx";
            $resultrecomender = mysqli_query($conn,$recomender);
            $rowrecomender = mysqli_fetch_array($resultrecomender);
            $recommender = $rowrecomender['recommender'];
                        if($recommender){
                        ?>
                        <input type="tel" class="text" oninput="autoHyphen2(this)" value="<?=$recommender?>" name="recommender" id="recommender" readonly>
                        <?
                        }else{
                            ?>
                            <!-- oninput="autoHyphen2(this)" -->
                            <input type="tel" class="text" placeholder="추천인 번호를 기입해주세요" name="recommender" id="recommender" readonly>
                            <?
                        }?><input type="button" class="update" value="변경" onclick="recommender2()">
        </div>
        <input type="button" onclick ="printdlg('사용자 정보가 변경되었습니다','myshop.php')" value="변경하기" class="updatebutton"></div>
    <hr class="ii"><br>
    <strong>회원탈퇴</strong>
    <hr class="i">
    <input type="button" class="endgo"  value="탈퇴하러 가기" onclick ="on_cancel('0','회원탈퇴','<?=$useridx?>')">
    </div>

    <input type="hidden" value="<?=$hostaddress?>/updatepassword.php" name="updatepassword">
    <input type="hidden" value="<?=$hostaddress?>/updateaddress.php" name="updateaddress">
    <input type="hidden" value="<?=$hostaddress?>/useraddress.php" name="useraddress">
    <input type="hidden" value="<?=$hostaddress?>/userupdate.php" name="userupdate">
    <input type="hidden" value="<?=$hostaddress?>/myshop_phone_pass.php" name="phone">
    <input type="hidden" value="<?=$hostaddress?>/telupdate.php" name="telupdate">
    <input type="hidden" value="<?=$hostaddress?>/emailupdate.php?email2=<?=$userrow['email']?>" name="emailupdate">
</form>

<?
        } else {
            ?>
        <script>
            printdlg22("장시간 활동이 없어, 메인페이지로 이동합니다.", "myshop.php", "MyShop");
        </script>
        <?php
        }
    } else {
        // $useridx가 "0"이거나 null인 경우, 로그인 페이지로 이동하도록 하는 JavaScript
        ?>
        <script>
            printdlg22("로그인이 필요한 서비스 입니다", "myshop_login.php", "MyShop");
        </script>
        <?php
    }
?>

<body>
<SCRIPT>
    function goupdate(idx,name,useridx){
        var url = document.maincont.updatepassword.value;

        loadwebview(url,idx,name,useridx);
    }
    function go_address(idx,name,useridx){
        var url = document.maincont.updateaddress.value;

        loadwebview(url,idx,name,useridx);
    }
    function go_useraddress(idx,name,useridx){
        var url = document.maincont.useraddress.value;

        loadwebview(url,idx,name,useridx);
    }
    function tel_update(idx,name,useridx){
        var url = document.maincont.telupdate.value;

        loadwebview(url,idx,name,useridx);
    }
    function updateuser(idx,name,useridx){
        var url = document.maincont.mypage.value;

        loadwebview(url,idx,name,useridx);
    }
    function email_update(idx,name,useridx){
        var url = document.maincont.emailupdate.value;

        loadwebview(url,idx,name,useridx);
    }
    function adduction(idx, name, useridx) {
        if (idx !== undefined && name !== undefined && useridx !== undefined) {
            var url = document.maincont.phone.value;
            loadwebview(url, idx, name, useridx);
        } 
        // 인자가 전달되지 않은 경우 다른 로직 수행
        else {
            var userIdx = <?= $useridx ?>;
            var phoneNumber = document.querySelector('.text2').value; // 휴대폰 번호 필드의 값을 가져옴

        }
    }


    
    function printdlg(msg,url){
            viewinfo("printdlg</>"+msg+"</>"+url);
        }
        


        function on_cancel(idx,name,useridx) {
            var url = "<?=$hostaddress?>/member_cancel_01.php";
        loadwebview(url,idx,name,useridx);
        }
        function recommender2(){
            var recommender = document.getElementById('recommender').value;
            
            // if(recommender.length != 11 && recommender.length != 0){
            //         alert("전화번호 형식에 맞춰서 입력해주세요 ex)010xxxxxxxx");
            //         return;
            //     }
        // if(confirm("추천인 전화번호를 변경하시겠습니까?")==true){
        // document.maincont.action="userupdate.php";
        // document.maincont.submit();
        // }else{
        //     return;
        // }
            viewinfo("seterror</>"+"서비스 준비중 입니다.")

    }
    const autoHyphen2 = (target) => {
    target.value = target.value
    .replace(/[^0-9]/g, '')
    }
    const inputFields = document.querySelectorAll('input[type=text], input[type=password],input[type=tel]');

inputFields.forEach(input => {
    input.addEventListener('focus', () => {
        setTimeout(() => {
            const scrollOffset = input.getBoundingClientRect().top - 50; // 조절하고 싶은 상단 여백 값을 설정
            window.scrollBy({ top: scrollOffset, behavior: 'smooth' });
        }, 300); // 포커스 이벤트 후 0.3초 뒤에 스크롤 조절
    });
});


</SCRIPT>
</body>

</HTML>

