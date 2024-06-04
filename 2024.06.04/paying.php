
<?php
    session_start();
    include "dbconn.php";
    
$useridx = $_SESSION['useridx'];
  // $useridx = $_GET['useridx'];
    
    @$connection_time = $_SESSION['connection_time'];

    $query4 = "SELECT j_coupon FROM member WHERE useridx = '$useridx'";
    $result4 = $conn->query($query4);
    $row4 = $result4->fetch_row();
    @$j_coupon = $row4[0];

    $coinquery = "SELECT name, tel FROM member WHERE useridx = '$useridx'";
    $coinresult = $conn->query($coinquery);
    $coinrow = mysqli_fetch_row($coinresult);
    @$name = $coinrow[0];

    $query5 = "SELECT CAST(AES_DECRYPT(UNHEX(tel), 'myshop') AS CHAR(50)) AS tel from member where useridx = '$useridx'";
    $result5 = $conn->query($query5);
    $row5 = mysqli_fetch_row($result5);
    @$tel1 = $row5[0];


@$tel2 = str_replace('-', '', $tel1);
    ?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <!-- <meta http-equiv="X-UA-Compatible" content="IE=edge"> -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://webfontworld.github.io/goodchoice/Jalnan.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/yejong.css?after">
    
    <title>결제하기</title>
    <script>
	function viewinfo(data)
	{
		window.customermanager.viewinfo(data);

	}
        function printdlg2(msg,url,titlename){
        let useridx = "<?=$useridx?>";
        viewinfo("printdlg2</>"+msg+"</>"+url+"</>"+titlename+"</>"+useridx);
        }

        function proceed(continuePayment) {
            let discountPrice = parseFloat(document.getElementById("discountPrice").innerText.replace(/[^0-9.-]+/g,""));
             let coin = document.getElementById("coin").value;
        document.getElementById('customModal').style.display = 'none';
        if (continuePayment) {
            var dontShowAgain = document.getElementById('dontShowAgain').checked;
            if (dontShowAgain) {
                setCookie("showWarning", "false", 30);
            }


            else{
                if(coin / 2 != discountPrice){
                if( confirm("코인 적용버튼을 누르지않으셨습니다 적용하지않고 결제진행하시겠습니까?") == true){
                            document.maincont.action = "https://mobile.inicis.com/smart/payment/";
                            document.maincont.target = "_self";
                            document.maincont.submit();
                }else{
                    return;
                }
            }
            // 결제 진행 로직
            document.maincont.action = "https://mobile.inicis.com/smart/payment/";
            document.maincont.target = "_self";
            document.maincont.submit();
            }

        }
    }
        function setCookie(name, value, days) {
            var expires = "";
            if (days) {
                var date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = "; expires=" + date.toUTCString();
            }
            document.cookie = name + "=" + (value || "") + expires + "; path=/";
        }
        function getCookie(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) === ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }
        </script>
</head>

<?php
$one_price = 0;
$coindiscount = 0;
$totalprice2 = 0; // 총 가격 초기화
$finalprice2 = 0; // 최종 결제 금액 초기화
$discountprice = 0; 
?>
<body style="margin:0; padding:0;">
<?
if($useridx == null && $connection_time == null){
    ?>
    <script>
    printdlg2("장시간 활동이 없어, 메인페이지로 이동합니다.", "myshop.php", "MyShop");
    </script>
    <?
}
if($useridx !="0"){

@$quantity = $_REQUEST['quantity'];
@$bagidx = $_REQUEST['bagidx2'];
@$mybag = $_REQUEST['mybag'];
@$menuidx = $_REQUEST['data'];
@$totalDeliveryCharge = $_REQUEST['totalDeliveryCharge'];
@$destinationidx = $_GET['destinationidx'];
// @$midx = $_REQUEST['data'];  //bottomsheetdialog에서 넘어오는 menuidx
if($destinationidx){
$query2 ="SELECT destinationidx,AES_DECRYPT(unhex(tel), 'myshop')AS tel,AES_DECRYPT(unhex(postcode), 'myshop')AS postcode,AES_DECRYPT(unhex(new_address), 'myshop')AS new_address,
AES_DECRYPT(unhex(detail_address), 'myshop')
AS detail_address,address_status,recipient 
from destination where useridx=$useridx && destinationidx = $destinationidx";
}else{
    $query2 ="SELECT destinationidx,AES_DECRYPT(unhex(tel), 'myshop')AS tel,AES_DECRYPT(unhex(postcode), 'myshop')AS postcode,AES_DECRYPT(unhex(new_address), 'myshop')AS new_address,
    AES_DECRYPT(unhex(detail_address), 'myshop')AS detail_address,address_status,recipient 
     from destination where useridx=$useridx && address_status = '1'";
}
$result2 =mysqli_query($conn,$query2);

$countquery = "select count(bagidx) from bag where useridx = '$useridx' AND checkbox = 1";
$countresult = $conn->query($countquery);
$countrow = mysqli_fetch_row($countresult);
$count = $countrow[0];

?>
<form method="post" action="" name="maincont" accept-charset="euc-kr">
<div class="main-content">
<?
if($mybag == 2)
{?>
<div class="order_information">주문상품(<?=$count?>)</div><?} 
else {?>
<div class="order_information">주문상품(1)</div>
<?}?>

<?php
if($mybag == 2)
{
    $paying = [];

    $query = "SELECT bagidx,menuidx,price,quantity,optidx,optidx2,optidx3 FROM bag WHERE useridx = '$useridx' AND checkbox = 1";
    $result = $conn->query($query);
    
    
    while ($row = mysqli_fetch_assoc($result)) {
        $menuidx = $row['menuidx'];
        $quantity = $row['quantity'];
        $option_idx1 = $row['optidx'];
        $option_idx2 = $row['optidx2'];
        $option_idx3 = $row['optidx3'];
        $option_idx = max($option_idx3, $option_idx2, $option_idx1);
        @$totalItems += 1;
        // if ($totalItems == 1) {
            $menuQuery = "SELECT mname FROM menu WHERE menuidx = '$menuidx'";
            $menuResult = $conn->query($menuQuery);
            $menuRow = mysqli_fetch_assoc($menuResult);
            $mname = $menuRow['mname'];
        // }
       
        $optQuery1 = "SELECT option_value,option_price,option_nap_price FROM option_test WHERE optionidx = '$option_idx1'";
        $optResult1 = $conn->query($optQuery1);
        $optRow1 = mysqli_fetch_assoc($optResult1);
        $optQuery2 = "SELECT option_value,option_price,option_nap_price FROM option_test WHERE optionidx = '$option_idx2'";
        $optResult2 = $conn->query($optQuery2);
        $optRow2 = mysqli_fetch_assoc($optResult2);
        $optQuery3 = "SELECT option_value,option_price,option_nap_price FROM option_test WHERE optionidx = '$option_idx3'";
        $optResult3 = $conn->query($optQuery3);
        $optRow3 = mysqli_fetch_assoc($optResult3);
       
        if($optRow1){
        $firstItemName = $mname." ".$optRow1['option_value'];
        $price = $optRow1['option_price'];
        $or_price = $optRow1['option_price'];
        $cu_nap =$optRow1['option_nap_price'];
        if($optRow2){
            $firstItemName .="/".$optRow2['option_value'];
            $price = $optRow2['option_price'];
            $or_price = $optRow2['option_price'];
            $cu_nap =$optRow2['option_nap_price'];
            if($optRow3){
                $firstItemName .= "/".$optRow3['option_value'];
                $price = $optRow3['option_price'];
                $or_price = $optRow3['option_price'];
                $cu_nap = $optRow3['option_nap_price'];
            }
        }
        }else{
        $firstItemName = $menuRow['mname'];
        $price = $row['price'];
        $or_price = $row['price'];
        }
        $menuQuery = "SELECT mname, price, imagepath1,company_idx,c_status,c_rate,nap_price,MDpay,card_carge FROM menu WHERE menuidx = '$menuidx'";
        $menuResult = $conn->query($menuQuery);
        $menuRow = mysqli_fetch_assoc($menuResult);
        $cu_nap = $menuRow['nap_price'];
        //$firstItemName = $menuRow['mname'];
        $c_status = $menuRow['c_status'];
        $c_rate = $menuRow['c_rate'];
        if($c_status != 1){
            $c_rate = 0;
        }
        $imagepath1 = $menuRow['imagepath1'];
        $buy =3;
        $totalprice2 += $price * $quantity;
        $MDpay = $menuRow['MDpay'];
        $card_carge = $menuRow['card_carge'];

        $nap_price = $cu_nap+$MDpay+$or_price*($card_carge/100);
        $coindiscount += ($or_price-$nap_price) * $quantity * ($c_rate/100);
        $coindiscount = round($coindiscount , -1);

        $bagidxpaying = $row['bagidx'];

        $yyy = ($or_price-$nap_price) * $quantity * ($c_rate/100);
        $paycoin = round($yyy , -1)*2;
        $paying[$bagidxpaying] = 0;
        $paying[$bagidxpaying] += $paycoin;

       
        
        if ($totalItems > 1) {
            $additionalCount = $totalItems - 1;
            $pGoodsValue = $firstItemName . " 외 " . $additionalCount . "개";
        } else {
            $pGoodsValue = $firstItemName;
        }
    ?>
   <div class="product_div" id="puroduct<?=$bagidxpaying?>">
                    <img src="<?=$imagepath1?>" alt="Product Image" class='product_image'>
                    <div class="product_info">
                        <div class="product_name2"><?=$firstItemName?></div>
                        <div>
                            <span class="product_quantity"><?=$quantity?>개</span>
                            <span class="product_price"><?= number_format($price * $quantity) ?>원</span>
                            
                        </div>
                        <div name ="paycoinTT">
                            <input type="text" style="width:70%" class="cointext" id = "cointext<?=$bagidxpaying?>"  placeholder="사용 가능한 코인:<?=$paycoin?>">
                            <input type="button" class = "beforecoin"value="적용" onclick ="readercoin(<?=$bagidxpaying?>,<?=$paycoin?>)">
                        </div >
                        <div  name = 'paycoinTT2' style=  "display : none">
                            <input type ="text"  style="width:70%"  class="cointext" value = '<?=$paycoin?>' readonly>
                            <input type="button" class = "beforecoin" value="적용" onclick ="readercoin(<?=$bagidxpaying?>,<?=$paycoin?>)">
                        </div>
                    </div>

                </div> <!--product_div-->



    <?php
    }
}
else{

    @$quantity = $_SESSION['quantity'];
    // @$option_idx = $_SESSION['optidx'];
    @$optidx1 = isset($_SESSION['lv1idx']) ? $_SESSION['lv1idx'] : 0;
    @$optidx2 =  isset($_SESSION['lv2idx']) ? $_SESSION['lv2idx'] : 0;
    @$optidx3 =  isset($_SESSION['lv3idx']) ? $_SESSION['lv3idx'] : 0;
    @$option_idx = max($optidx3, $optidx2, $optidx1); 

    @$basong = $_SESSION['basong'];

    $query1 = "select count(optionidx) from option_test where optionidx = '$option_idx'";
    $result1 = mysqli_query($conn, $query1);
    $row = mysqli_fetch_row($result1);
    $count = $row[0];
    
    $optQuery = "SELECT option_name,option_price FROM option_test WHERE optionidx = '$option_idx'";
    $optResult = $conn->query($optQuery);
    $optRow = mysqli_fetch_assoc($optResult);

    $categoryquery = "SELECT category,category2 FROM menu WHERE menuidx = '$menuidx'";
    $categoryresult = $conn->query($categoryquery);
    $categoryrow = mysqli_fetch_assoc($categoryresult);
    $categoryidx = $categoryrow['category'];

    
    if(!$totalDeliveryCharge){
        if(!$option_idx){
        $selectquery = "SELECT * from menu where menuidx = '$menuidx'";
        $selectresult = mysqli_query($conn,$selectquery);
        $selectrow = mysqli_fetch_array($selectresult);
        $selectprice = $selectrow['price'];
    }
    if($categoryidx == 4){
        $totalDeliveryCharge = 0;
    }
    }

    $menuQuery = "SELECT menuidx,mname,price,imagepath1,company_idx,c_status,c_rate,nap_price,MDpay,card_carge FROM menu WHERE menuidx = '$menuidx'";
    $menuResult = $conn->query($menuQuery);
    $menuRow = mysqli_fetch_assoc($menuResult);
    $mname = $menuRow['mname'];
    $price = $menuRow['price'];
    $imagepath1 = $menuRow['imagepath1'];
    $menuidxcoin = $menuRow['menuidx'];
    $c_status = $menuRow['c_status'];
    $c_rate = $menuRow['c_rate'];
    if($c_status != 1){
        $c_rate = 0;
    }
    
    if($optRow){

        $optQuery1 = "SELECT option_value,option_price,option_nap_price FROM option_test WHERE optionidx = '$optidx1'";
        $optResult1 = $conn->query($optQuery1);
        $optRow1 = mysqli_fetch_assoc($optResult1);
        
        $optQuery2 = "SELECT option_value,option_price,option_nap_price FROM option_test WHERE optionidx = '$optidx2'";
        $optResult2 = $conn->query($optQuery2);
        $optRow2 = mysqli_fetch_assoc($optResult2);
        $optQuery3 = "SELECT option_value,option_price,option_nap_price FROM option_test WHERE optionidx = '$optidx3'";
        $optResult3 = $conn->query($optQuery3);
        $optRow3 = mysqli_fetch_assoc($optResult3);
        if($optRow1){
        $mname = $mname." ".$optRow1['option_value'];
        $price = $optRow1['option_price'];
        $or_price = $optRow1['option_price'];
        $cu_nap = $optRow1['option_nap_price'];
        if($optRow2){
            $mname .="/".$optRow2['option_value'];
            $price = $optRow2['option_price'];
            $or_price = $optRow2['option_price'];
            $cu_nap = $optRow2['option_nap_price'];
            if($optRow3){
                $mname .= " "."/".$optRow3['option_value'];
                $price = $optRow3['option_price'];
                $or_price = $optRow3['option_price'];
                $cu_nap = $optRow3['option_nap_price'];
            }
        }
        }
            $totalDeliveryCharge = $basong;
        $buy = 1;
    }else{
    $mname = $menuRow['mname'];
    $price = $menuRow['price'];
    $or_price = $menuRow['price'];
    $cu_nap = $menuRow['nap_price'];
    $totalDeliveryCharge = $basong;
    $buy = 2;
    }
    if($optRow){
        $firstItemName = $mname;
        }else{
        $firstItemName = $menuRow['mname'];
         }
        $pGoodsValue = $firstItemName;
        $totalprice2 += $price * $quantity;
        $nap_price = $cu_nap+$menuRow['MDpay']+$or_price*($menuRow['card_carge']/100);
        $coindiscount += ($or_price-$nap_price) * $quantity * ($c_rate/100);
        $coindiscount = round($coindiscount , -1);

        $yyy = ($or_price-$nap_price) * $quantity * ($c_rate/100);
        $paycoin = round($yyy , -1)*2;
        $paying[$menuidx] = 0;
        $paying[$menuidx] += $paycoin;
        

      //  $discountprice += $or_price * $quantity * ($j_rate/100);
    ?>
    <div class="product_div" id="puroduct<?=$menuidxcoin?>">
                    <img src="<?=$imagepath1?>" alt="Product Image" class='product_image'>
                    <div class="product_info">
                        <div class="product_name2"><?=$mname?></div>
                        <div>
                            <span class="product_quantity"><?=$quantity?>개</span>
                            <span class="product_price"><?= number_format($price * $quantity) ?>원</span>
                        </div>
                        <div name ="paycoinTT">
                            <input type="text" style="width:70%" class="cointext" id = "cointext<?=$menuidxcoin?>"  placeholder="사용 가능한 코인:<?=$paycoin?>">
                            <input type="button" class = "beforecoin" value="적용" onclick ="readercoin2(<?=$menuidxcoin?>,<?=$paycoin?>)">
                        </div >
                        <div  name = 'paycoinTT2' style=  "display : none">
                            <input type ="text"  style="width:70%"  class="cointext" value = '<?=$paycoin?>' readonly>
                            <input type="button" class = "beforecoin" value="적용" onclick ="readercoin2(<?=$menuidxcoin?>,<?=$paycoin?>)">
                        </div>
                    </div>
                </div> <!--product_div-->


    <?
    // unset($_SESSION['option_name']);
}
?>
    <?php
    $totalDeliveryCharge = intval(str_replace(',', '', $totalDeliveryCharge));
$finalprice2 = $totalprice2 + $totalDeliveryCharge;

    $ordernum = "11".$useridx.date("is").rand(0, 9);
    $company_idx = $menuRow['company_idx'];
    ?>

<div class="shipping_information">배송정보<div class="updatediv"><button onclick="orderaddress()" class="basongupdate">배송지변경</button></div></div>
<div class="recipient_div">
<?
        if($row2 = mysqli_fetch_array($result2)){
            $destinationidx = $row2['destinationidx'];
            $recomender = "SELECT recommender,AES_DECRYPT(unhex(recommender), 'myshop')AS recommender from member where useridx = $useridx";
            $resultrecomender = mysqli_query($conn,$recomender);
            $rowrecomender = mysqli_fetch_array($resultrecomender);
            $recommender = $rowrecomender['recommender'];

                ?>

                    <div class="basongcontainer">
                    <div class="basonginfo2">
                    <input type="text" id="coin"  style="width: 150px; height: 23px;" readonly>
                <input type="button" class="coin_button" onclick="coin_check()" value="코인 적용">
                <input type="button" class="max-button" onclick="coin_check2()" value="최대 적용">
</div>
                    <div class="basonginfo2">
                    <span class='recipient_left2'>받는분</span> <span id="recipient" class="recipient_right"><?=$row2['recipient']?></span>
                    </div>
                    <div class="basonginfo2">
                    <span class='recipient_left2'>배송지</span> <span id="postcode" class="recipient_right"><?=$row2['postcode']?></span> <span id="address"><?=$row2['new_address']." "?></span> <span class="daddress" id="daddress"><?=$row2['detail_address']?></span>
                    </div>
                    <div class="basonginfo2">
                    <span class='recipient_left3'>휴대폰 번호</span> <span id="tel" class="recipient_right"><?=$row2['tel']?></span>
                    </div>

                    <div class="basonginfo2">
                    <span class='recipient_left4'>결제수단 선택</span> <select class = "basong_memo" id="paymentMethod" onchange="payment(this.value)">
                                                                        <option value="CARD">카드 결제</option>
                                                                        <option value="VBANK">무통장 입금</option>
                                                                      <!-- <option value="MOBILE">휴대폰 결제</option>   -->
                                                                        <option value="BANK">계좌 이체</option>
                                                                        </select>
                    </div>
                    <div class="basonginfo2">
                    <span class='recipient_left5'>추천인 전화번호</span>
                    
                    <?
                        if($recommender){
                        ?>
                        <input type="tel" class="recipient_right"  value= "<?=$recommender?>" placeholder="- 빼고 번호만 기입해주세요" name="recommender" id="recommender" >
                        <?
                        }else{
                            ?>
                            <input type="tel" class="recipient_right"  placeholder="- 빼고 번호만 기입해주세요" name="recommender" id="recommender">
                            <?
                        }
                        ?>
                            <input type="button" class="max-button" onclick="recommender_search()" value="검색">
                    </div>
                    <div class="basonginfo2">
                    <span class='recipient_left6'>배송메모</span>
                    <select class="basong_memo" id="deliveryRequest" name="selectname" onchange="toggleCustomInput()">
                        <option value="0">배송메모를 선택해 주세요</option>
                        <option value="1">집 문 앞에 놓아주세요</option>
                        <option value="2">벨을 눌러주세요</option>
                        <option value="3">배송 전 전화주세요</option>
                    </div>
                    </div>
                    <input type="hidden" name="P_NOTI" id="P_NOTI" value="<?=$destinationidx?>">
           <input type ="hidden" value="<?=$row2['destinationidx']?>" name ="destinationidx" id="destinationidx" >
                <?

        }else{
            $recomender = "SELECT AES_DECRYPT(unhex(recommender), 'myshop')AS recommender,AES_DECRYPT(unhex(tel), 'myshop')AS tel from member where useridx = $useridx";
            $resultrecomender = mysqli_query($conn,$recomender);
            $rowrecomender = mysqli_fetch_array($resultrecomender);
            $recommender = $rowrecomender['recommender'];
            $tel = $rowrecomender['tel'];

            ?>
            <div id="checkboxdiv" style="display:block">
            <span class="notbasong">등록된 기본 배송지정보가 없습니다.<br>기본 배송지를 선택해주세요.</span><br>

                <input type="checkbox" id="checkbox1" value='1'><span >기본주소지와 동일</span>
            </div>
            <div class="basongcontainer" id="addressdiv" style="display:none">
            <div class="basonginfo2">
            <input type="text" id="coin"  style="width: 150px; height: 23px;" readonly>
    <input type="button" class="coin_button" onclick="coin_check()" value="코인 적용">
    <input type="button" class="max-button" onclick="coin_check2()" value="최대 적용">

</div>
            <div class="basonginfo">
                <span class='recipient_left2'>받는분</span> <span class="recipient_right" id="recipient"></span>
            </div>
            <div class="basonginfo2">
                <span class='recipient_left2'>배송지</span> <span class="recipient_right" id="postcode"></span><span id="address"></span><span class="daddress" id="daddress"></span>
            </div>
            <div class="basonginfo2">
                <span class='recipient_left3'>휴대폰 번호</span> <span class="recipient_right" id="tel"></span>
            </div>

            <div class="basonginfo2">
                    <span class='recipient_left4'>결제수단 선택</span> <select class = "basong_memo" id="paymentMethod" onchange="payment(this.value)">
                                                                        <option value="CARD">카드 결제</option>
                                                                        <option value="VBANK">무통장 입금</option>
                                                                      <!-- <option value="MOBILE">휴대폰 결제</option>   -->
                                                                        <option value="BANK">계좌 이체</option>
                                                                        </select>
                    </div>
                    <div class="basonginfo2">
                    <span class='recipient_left5'>추천인 전화번호</span>
                    
                    <?
                        if($recommender){
                        ?>
                        <input type="tel" class="recipient_right" oninput="autoHyphen2(this)" value="<?=$recommender?>" name="recommender" id="recommender" readonly>
                        <?
                        }else{
                            ?>
                            <input type="tel" class="recipient_right" oninput="autoHyphen2(this)" placeholder="추천인 번호를 기입해주세요" name="recommender" id="recommender">
                            <?
                        }
                        ?>
                    </div>
                    <div class="basonginfo2">
                    <span class='recipient_left6'>배송메모</span>
                    <select class="basong_memo" id="deliveryRequest" name="selectname" onchange="toggleCustomInput()">
                        <option value="0">배송메모를 선택해 주세요</option>
                        <option value="1">집 문 앞에 놓아주세요</option>
                        <option value="2">벨을 눌러주세요</option>
                        <option value="3">배송 전 전화주세요</option>
                    </div>
                    </div>
                    <input type="hidden" name="P_NOTI" id="P_NOTI" value="">
           <input type ="hidden" value="<?=$row2['destinationidx']?>" name ="destinationidx" id="destinationidx" >
</div>
            <?
            if(!$row2){
            ?>
           <input type ="hidden" value="<?=$row2['destinationidx']?>" name ="destinationidx" id="destinationidx" >
            <?
            }
            ?>
            <script>
                document.getElementById('checkbox1').addEventListener('change', function() {
            if(this.checked) {
                selectandinsert();
            } else {
                
            }
        });
                </script>
            <?
            
        }
    ?>
</div>

    </div>
    <?
        $memberquery ="SELECT * from member where useridx = $useridx";
        $memberresult = mysqli_query($conn,$memberquery);
        $memberrow = mysqli_fetch_array($memberresult);
        $nice_state = $memberrow['nice_state'];
        $username = $memberrow['name'];


        $date = new DateTime();
        $date->modify('+1 day');
        $formattedDate = $date->format('Ymd');
    ?>


<div class="fixed-bottom">
    <div class="payment_price_div">
        <div class="detailed_price_div">
            <div class="total_price">
                <div class="total_price_left">상품 가격</div>
                <div class="total_price_right" id="totalPrice"><?=number_format($totalprice2)?>원</div>
            </div>
            <div class="discount_price">
                <div style="color: #F26052;" class="total_price_left">코인 할인금액</div>
                <div style="color: #F26052;" class="total_price_right" id="discountPrice">0원</div>
            </div>
            <div class="delivery_charge">
                <div class="total_price_left">배송비</div>
                <div class="total_price_right" id="delivery"><?=number_format($totalDeliveryCharge)?>원</div>
            </div>
        </div>
        <div class="final_payment_amount">
            <div class="final_payment_amount_text">최종 결제 금액</div>
            <div class="final_payment_amount_amount" id="finalPrice"><?=number_format($finalprice2)?>원</div>
        </div>
    </div>
    <div id="myModal" class="modal">
    <div id="customModal" style="display:none;">
    <p>추천인 전화번호를 기입하지 않으셨습니다. 그대로 진행하시겠습니까?</p>
    <p>추천인 전화번호는 마이페이지에서 수정하실수있습니다.</p>
    <input type="checkbox" id="dontShowAgain"> 한 달간 보지 않기<br>
    <button class ="modalyes" onclick="proceed(true)">예</button>
    <button class ="modalno" onclick="proceed(false)">아니오</button>
</div>
    </div>
    
    <input type ="hidden" value="<?=$hostaddress?>/order.php" name ="order">
    <input type="hidden" name="P_GOODS" value="<?=$pGoodsValue?>">
    <input type="hidden" name="mybag" value="<?=$mybag?>">
    <input type="hidden" name="menuidx" value="<?=$menuidx?>">
    <input type="hidden" name="totalDeliveryCharge" value="<?=$totalDeliveryCharge?>">
    <input type="hidden" name="P_INI_PAYMENT" value="CARD" id="P_INI_PAYMENT">
    <input type="hidden" name="P_MID" value="allmysho15"><!--INIpayTest  / allmysho15-->
    <input type="hidden" name="P_OID" value="<?=$ordernum?>">
    <input type="hidden" name="P_AMT" value="<?=$finalprice2?>">
    <input type="hidden" name="P_UNAME" value="<?=$username?>">
    <?
    if($row2){
    ?>
    <input type="hidden" name="P_MOBILE" value="<?=$row2['tel']?>">
    <?
    }else{
        ?>
            <input type="hidden" name="P_MOBILE" value="<?=$tel?>">
        <?
    }
    ?>
    <input type="hidden" name="P_VBANK_DT" value="<?=$formattedDate?>">
    <input type="hidden" id="P_NOTI_URL" name="P_NOTI_URL" value="https://host6.allmyshop.co.kr/A_team/mx_rnoti.php">
    <input type="hidden" name="P_HPP_METHOD" value="2">
    <input type="hidden" id="nexturl" name="P_NEXT_URL" value="<?=$hostaddress?>/order.php?useridx=<?=$useridx?>&destinationidx=<?=$destinationidx?>&ordernum=<?=$ordernum?>&connection_time=<?=$connection_time?>&menuidx=<?=$menuidx?>&quantity=<?=$quantity?>&buy=<?=$buy?>&option_idx=<?=$option_idx?>&totalDeliveryCharge=<?=$totalDeliveryCharge?>">
    <input type="hidden" name="selectname" id="selectname">
    <input type="hidden" name="" value="utf8">
    <input type="hidden" name="P_RESERVED" value="below1000=Y&vbank_receipt=Y&centerCd=Y&hpp_corp=SKT:KTF:LGT">
    <input type="hidden" name="P_EMAIL" value="<?=$memberrow['email']?>">
    <input type="hidden" name="nice_state" value="<?=$nice_state?>">
    <?

    ?>
    <?
?>
    </form>
    <button class="buy_button" onclick="on_pay(event)">결제하기</button>
                    
    <?
}else{
    ?>
     <script language="javascript">
    //여기는 라이브러리 함수
	<?
    $iphone ="0";
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
    printdlg2("로그인후 사용 가능 합니다.",'myshop_login.php',null);
</script>
<?
}
    ?>
</body>
<script>

    initcoin();
    let coin_checked = 0;
    var coinvalue = {};
    //초기화 백 코인
    function initcoin(){
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "testcoin7.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send("status="+ encodeURIComponent("44"));
    }


    const autoHyphen2 = (target) => {
    target.value = target.value
    .replace(/[^0-9]/g, '')
    .replace(/^(\d{0,3})(\d{0,4})(\d{0,4})$/g, "$1-$2-$3").replace(/(\-{1,2})$/g, "");
    }
    function orderaddress(){
        document.maincont.action="orderaddress.php?menuidx=<?=$menuidx?>&mybag=<?=$mybag?>&totalDeliveryCharge=<?=$totalDeliveryCharge?>";
        document.maincont.submit();
    }
        
        function selectandinsert() {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'checkaddress.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {

                
                var response = JSON.parse(this.responseText);
                if(response.success) {
                 document.getElementById('postcode').textContent = response.postcode;
                 document.getElementById('address').textContent = response.address;
                 document.getElementById('daddress').textContent = response.daddress;
                 document.getElementById('recipient').textContent = response.recipient;
                 document.getElementById('tel').textContent = response.tel;
                 document.getElementById('destinationidx').value = response.destinationidx;
                 document.getElementById('P_NOTI').value = response.destinationidx;
                 document.getElementById('addressdiv').style.display = "block";
                 document.getElementById('checkboxdiv').style.display ="none";

                } else {

                    alert('주소 정보를 가져오는 데 실패했습니다.');
                }

            }
        };
        xhr.send('useridx=<?=$useridx?>');
    }
    function payment(selectedPaymentMethod){
    document.getElementById('P_INI_PAYMENT').value = selectedPaymentMethod;
    }


    function toggleCustomInput() {
document.getElementById('selectname').value = document.getElementById('deliveryRequest').value;
            var ttt = document.getElementById('selectname').value;

        }

        function on_pay(event) { 
            event.preventDefault();
            let nice_state = document.maincont.nice_state.value;
            let P_noti = document.getElementById("P_NOTI").value;
            let c_coin = 0;

             let discountPrice = parseFloat(document.getElementById("discountPrice").innerText.replace(/[^0-9.-]+/g,""));
             let coin = document.getElementById("coin").value;
            if(coin_checked == 1){
            c_coin = document.getElementById("coin").value;
            }

            if(P_noti != ""||null){
            if(nice_state == 1){
            var recommender = document.getElementById('recommender').value;
            var destinationValue = document.getElementById('destinationidx').value;
            var nexturl = document.getElementById('nexturl').value;
            let memo = document.getElementById('selectname').value;
            var notinexturl = document.getElementById('P_NOTI_URL').value;
            var newurl = nexturl+"&recommender="+recommender+"&memo="+memo+"&coin="+c_coin;
            var newnotiurl = notinexturl+"?recommender="+recommender;
            document.getElementById('nexturl').value = newurl;
            document.getElementById('P_NOTI_URL').value = newnotiurl;
                    <?if($mybag == 2){?>
                    if(coin_checked == 1){
                        success_coin();
                    }
                    <?}?>
            if (destinationValue === "") {
                alert("등록된 기본 배송지정보가 없습니다");
            } else {
                if(recommender ==="" ){
                    var showWarning = getCookie("showWarning") !== "false";
                    
                    let modal = document.querySelector('.modal');
                    if (showWarning) {
                    modal.style.display = "block";
                    document.getElementById('customModal').style.display = 'block';
                    } else {
                    if(coin / 2 != discountPrice){
                        if( confirm("코인 적용버튼을 누르지않으셨습니다 적용하지않고 결제진행하시겠습니까?") == true){
                            document.maincont.action = "https://mobile.inicis.com/smart/payment/";
                            document.maincont.target = "_self";
                            document.maincont.submit();
                        }else{
                            return;
                        }
                    }
                    document.maincont.action = "https://mobile.inicis.com/smart/payment/";
                    document.maincont.target = "_self";
                    document.maincont.submit();
                    }
                }else{
                     if(coin / 2 != discountPrice){
                        if( confirm("코인 적용버튼을 누르지않으셨습니다 적용하지않고 결제진행하시겠습니까?") == true){
                            document.maincont.action = "https://mobile.inicis.com/smart/payment/";
                            document.maincont.target = "_self";
                            document.maincont.submit();
                        }else{
                            return;
                        }
                    }
                    document.maincont.action = "https://mobile.inicis.com/smart/payment/";
                    document.maincont.target = "_self";
                    document.maincont.submit();
                }
                // else if(recommender.length != 11){
                //     alert("추천인 전화번호 11자리를 입력해주세요 ex)010xxxxxxxx");
                //     return;
                // }
            }
        }else{
            printdlg2("휴대폰인증이 필요합니다.",'userupdate.php',"정보 변경");
        }
	        }
        else{
            alert("기본주소지와 동일 체크해주세요");
            return;
        }
    }

    function recommender_search(){
        var recommender = document.getElementById('recommender').value;
        var url=`check_reco.php?recommender=${recommender}`;

                fetch(url).then(function(response){

                    return response.text(); 
            
                }).then(function(data){ 
                    alert(data);
                    if(data == "해당 추천인이 없습니다." || data == "추천인 번호를 입력해주세요." ){
                        
                    }else{
                        document.getElementById('recommender').readOnly = true;
                    }
                })
    }
function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function coin_check() {
    var j_coupon = "<?php echo $j_coupon; ?>"; // 정회원 여부
    var coindiscount = "<?php echo $coindiscount; ?>"; // 코인 할인금액
    var name = "<?php echo $name; ?>"; // 이름
    var phone = "<?php echo $tel2; ?>"; // 전화번호 
    var coin = document.getElementById('coin').value; // 사용할 코인 개수

    // 서버로 보낼 URl(get방식)
    var url=`check_coin.php?name=${name}&phone=${phone}&coin=${coin}`; 

    if (j_coupon != 1 ) {
        var ms="정회원만 코인 적용 가능합니다.";
                var msg="seterror</>"+ms;
		        viewinfo(msg);
        return;
    }

if (isNaN(coin) || coin === '0' || coin === '') {
    var ms="코인은 숫자로 입력되어야 합니다.";
                var msg="seterror</>"+ms;
		        viewinfo(msg);
    return;
}

if (parseInt(coin) % 10 !== 0) {
    var ms="코인은 10의 배수로 입력되어야 합니다.";
                var msg="seterror</>"+ms;
		        viewinfo(msg);
    return;

}
if (coin > 2*coindiscount) {
        var ms="코인 <?php echo 2*$coindiscount; ?>개를 초과할 수 없습니다.";
                var msg="seterror</>"+ms;
		        viewinfo(msg);
        return;
    }
    
// 서버로 보낼 데이터 객체 생성
    var data = {
        name: name,
        phone: phone,
        coin: coin
    };

    //서버 통신
    fetch(url, {
        method: 'POST', //서버로 데이터 전송
        headers: {
            'Content-Type': 'application/json'  // 요청 본문의 데이터 타입이 JSON 형식
        },
        body: JSON.stringify(data) //요청의 본문(body)에 들어갈 데이터
    })
    
    .then(function(response) { //fetch 함수의 반환값 'response'

        return response.json();
    })

    .then(function(data) {
    if (data.coin == 0) {
        var ms="보유 코인이 부족합니다.";
                var msg="seterror</>"+ms;
		        viewinfo(msg);
    } else if (data.coin == 1) {
        applyPointLogic();
        document.getElementById("coin").readOnly = true;
        var ms="적용되었습니다.";
        coin_checked = 1;
                var msg="seterror</>"+ms;
		        viewinfo(msg);
                <?php if($mybag == 2) {
                    ?>
                    savecoin();
                    <?
                }
                    ?>


    } else if (data.coin == 2) {
        var ms="일치하는 회원정보가 없습니다.";
                var msg="seterror</>"+ms;
		        viewinfo(msg);
    } 
})

    .catch(function(error) {
        // 오류 처리
        console.error('Error:', error);
        alert("요청을 처리하는 동안 오류가 발생했습니다.");
    });
}

function coin_check2() {
    var j_coupon = "<?php echo $j_coupon; ?>"; // 정회원 여부
    var name = "<?php echo $name; ?>"; 
    var phone = "<?php echo $tel2; ?>"; 
    var coin = "<?php echo 2*$coindiscount; ?>"; 
    var nap_price = "<?=$nap_price?>";

    



    if (j_coupon != 1 ) {
        var ms="정회원만 코인 적용 가능합니다.";
                var msg="seterror</>"+ms;
		        viewinfo(msg);
        return;
    }

    var url=`check_coin.php?name=${name}&phone=${phone}&coin=${coin}`;

    var data = {
        name: name,
        phone: phone,
        coin: coin
    };

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(function(response) {

        return response.json();
    })
    .then(function(data) {
    if (data.coin == 0) {
        var ms="보유 코인이 부족합니다.";
                var msg="seterror</>"+ms;
		        viewinfo(msg);
        
    } else if (data.coin == 1) {
        max_discount(); 
        document.getElementById("coin").readOnly = true;
        document.querySelectorAll(".cointext").readOnly = true;
        var ms="최대코인이 적용되었습니다.";
        const paycoinTTlist = document.getElementsByName('paycoinTT');
        const paycoinTT2list = document.getElementsByName('paycoinTT2');
        for(paycoinTT of paycoinTTlist){
            paycoinTT.style.display = 'none';
        }
        for(paycoinTT2 of paycoinTT2list){
            paycoinTT2.style.display = 'block';
        }
        <?php if($mybag == 2) {
                    ?>
                    savecoinall();
                    <?
                }
                    ?>
        coin_checked = 1;
        
                var msg="seterror</>"+ms;
		        viewinfo(msg);
    } else if (data.coin == 2) {
        var ms="일치하는 회원정보가 없습니다.";
                var msg="seterror</>"+ms;
		        viewinfo(msg);
    } 
})
    .catch(function(error) {
        // 오류 처리
        console.error('Error:', error);
        alert("요청을 처리하는 동안 오류가 발생했습니다.");
    });
}
function pay_check(event) {

    event.preventDefault();

    var name = "<?php echo $name; ?>"; 
    var phone = "<?php echo $tel2; ?>";
    var product = "<?php echo $mname; ?>"; 
    var count = "<?php echo $quantity; ?>";
    var coin = document.getElementById('coin').value;
    
    var url=`check_pay.php?name=${name}&phone=${phone}&coin=${coin}&product=${product}&count=${count}`;

    if (isNaN(coin) || coin === '0' || coin === '') {
        on_pay(event);
        return;
    }

    fetch(url).then(function(response){
        console.log(response);
        return response.text(); 

    }).then(function(data){ 
        
         const topics = JSON.parse(data);
         console.log(topics.coin);
         if (topics.coin == 1) {
             on_pay(event); 
         } else if (topics.coin == 0) {
            var ms="결제가 실패하였습니다.";
                var msg="seterror</>"+ms;
		        viewinfo(msg);
         }
    else{
             alert("결제 실패.");
        }
    });
}

function applyPointLogic() {
    var coindiscount = "<?php echo $coindiscount; ?>";
    var totalPrice = parseFloat(document.getElementById("totalPrice").innerText.replace(/[^0-9.-]+/g,"")); // 총 상품 가격을 가져와서 소수점 형태로 변환
    var coin = parseFloat(document.getElementById("coin").value);
    var delivery = parseFloat(document.getElementById("delivery").innerText.replace(/[^0-9.-]+/g,""));
    var discountPrice = parseFloat(document.getElementById("discountPrice").innerText.replace(/[^0-9.-]+/g,""));

var pointAmount = coin * 0.5;

var maxDiscount = coindiscount;

pointAmount = Math.min(pointAmount, maxDiscount);

var finalPrice = totalPrice + delivery - pointAmount;

document.getElementById("discountPrice").innerText = numberWithCommas(pointAmount) + "원";
document.getElementById("finalPrice").innerText = numberWithCommas(finalPrice) + "원";

// hidden input에 최종 결제 금액 설정
document.getElementsByName("P_AMT")[0].value = finalPrice;
}

function max_discount() {
    var coindiscount = "<?php echo $coindiscount; ?>";
    var totalPrice = parseFloat(document.getElementById("totalPrice").innerText.replace(/[^0-9.-]+/g,"")); // 총 상품 가격을 가져와서 소수점 형태로 변환
    var coinInput = document.getElementById("coin"); // 코인 입력란 요소 가져오기
    var delivery = parseFloat(document.getElementById("delivery").innerText.replace(/[^0-9.-]+/g,""));
    var discountPrice = parseFloat(document.getElementById("discountPrice").innerText.replace(/[^0-9.-]+/g,""));

    // 텍스트 필드에 할인가능 금액의 10배값 설정, 1개당 0.5원
    coinInput.value = (coindiscount * 2).toFixed(0);

    // 할인 적용된 최종 상품 가격 계산
    var discountedTotalPrice = totalPrice - coindiscount;

    // 최종 결제 금액 계산
    var finalPrice = discountedTotalPrice + delivery;
  
    document.getElementById("discountPrice").innerText = numberWithCommas(coindiscount) + "원";
    // 최종 결제 금액 출력
    document.getElementById("finalPrice").innerText = numberWithCommas(finalPrice) + "원";

    // hidden input에 최종 결제
    document.getElementsByName("P_AMT")[0].value = finalPrice;
}

<?if($mybag == 2){?>
function savecoin() {
    let coin_value = coinvalue;
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "testcoin7.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send("coin_value="+encodeURIComponent(JSON.stringify(coin_value))+"&status="+ encodeURIComponent("1"));
}
function savecoinall() {
    let paying = <?= json_encode($paying) ?>;
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'testcoin7.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    var data = 'paying=' + encodeURIComponent(JSON.stringify(paying)) + "&status=" + encodeURIComponent("2");
    xhr.send(data);
}
function success_coin() {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'testcoin7.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    var data = "status=" + encodeURIComponent("3");
    xhr.send(data);
//     xhr.onreadystatechange = function() {
//         if (xhr.readyState == 4) { 
//             if (xhr.status == 200) { 
//                 alert("Success");
//             } else {
//                 alert("fail");
//             }
//         }
// }
}
function readercoin(bagidx,paycoin5) {
    let paycoin6 = paycoin5;
    let c_coin = document.getElementById("cointext" + bagidx).value;
    let coin = parseInt(c_coin) || 0;
        let currentcoin = parseInt(document.getElementById('coin').value);
        if (!isNaN(currentcoin)) { 
            let lastCoin = coinvalue[bagidx] || 0;
            if(paycoin6 >= coin){
                if (coin != lastCoin) {
                currentcoin = currentcoin - lastCoin + coin; 
                document.getElementById('coin').value = currentcoin; 
                coinvalue[bagidx] = coin; 
                }
            }else{
                alert("사용가능한 코인보다 많습니다 최대 "+ paycoin6+" 코인 사용가능합니다");
            }
        } else {
            if(paycoin6 >= coin){
            document.getElementById('coin').value = coin;
            coinvalue[bagidx] = coin; 
            }else{
                alert("사용가능한 코인보다 많습니다 최대 "+ paycoin6+" 코인 사용가능합니다");
            }
        }

}
<?}?>
function readercoin2(bagidx,paycoin5) {
    let paycoin6 = paycoin5;
    let c_coin = document.getElementById("cointext" + bagidx).value;
    let coin = parseInt(c_coin);
        let currentcoin = parseInt(document.getElementById('coin').value);
        if (!isNaN(currentcoin)) {
            let lastCoin = coinvalue[bagidx] || 0; 
            if(paycoin6 >= coin){
                if (coin != lastCoin) {
                currentcoin = currentcoin - lastCoin + coin; 
                document.getElementById('coin').value = currentcoin; 
                coinvalue[bagidx] = coin; 
                }
            }else{
                alert("사용가능한 코인보다 많습니다 최대 "+ paycoin6+" 코인 사용가능합니다");
            }
        } else {
            if(paycoin6 >= coin){
            document.getElementById('coin').value = coin;
            coinvalue[bagidx] = coin; 
            }else{
                alert("사용가능한 코인보다 많습니다 최대 "+ paycoin6+" 코인 사용가능합니다");
            }
        }
}

</script>