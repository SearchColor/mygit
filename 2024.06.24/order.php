<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="utf-8" />
    <title>myshop</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" />
    <link href="https://webfontworld.github.io/goodchoice/Jalnan.css" rel="stylesheet">
    <title>주문 상세보기</title>

    <?php
    $coin = $_REQUEST["coin"]?? "0";
    $discountPrice = $_REQUEST["discountPrice"]?? "0";

    include "./dbconn.php";
    require_once('libs/properties.php');
    $prop = new properties();

    $P_STATUS    = $_REQUEST["P_STATUS"];
    $P_RMESG1    = $_REQUEST["P_RMESG1"];
    $P_TID       = $_REQUEST["P_TID"];
    $P_REQ_URL   = $_REQUEST["P_REQ_URL"];
    $P_NOTI      = $_REQUEST["P_NOTI"];
    $P_AMT       = $_REQUEST["P_AMT"];
    $useridx = $_GET['useridx'];
    $_SESSION['useridx'] = $useridx;
    $connection_time = $_GET['connection_time'];
    $_SESSION['connection_time'] = $connection_time;
    $ordernum = $_GET['ordernum'];            // 인증이 P_STATUS===00 일 경우만 승인 요청

    $id_merchant = substr($P_TID, '10', '10');     // P_TID 내 MID 구분
    $data = array(

        'P_MID' => $id_merchant,         // P_MID
        'P_TID' => $P_TID                // P_TID

    );

    //##########################################################################
    // 승인요청 API url (authUrl) 리스트 는 properties 에 세팅하여 사용합니다.
    // idc_name 으로 수신 받은 센터 네임을 properties 에서 include 하여 승인요청하시면 됩니다.
    //##########################################################################
    @$idc_name     = $_REQUEST["idc_name"];
    $P_REQ_URL  = $prop->getAuthUrl($idc_name);

    if (strcmp($P_REQ_URL, $_REQUEST["P_REQ_URL"]) == 0) {

        // curl 통신 시작 

        $ch = curl_init();                                                //curl 초기화
        curl_setopt($ch, CURLOPT_URL, $_REQUEST["P_REQ_URL"]);            //URL 지정하기
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                   //요청 결과를 문자열로 반환 
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);                     //connection timeout 10초 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);                      //원격 서버의 인증서가 유효한지 검사 안함
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));    //POST 로 $data 를 보냄
        curl_setopt($ch, CURLOPT_POST, 1);                                //true시 post 전송 


        $response = curl_exec($ch);
        curl_close($ch);

        parse_str($response, $out);
        foreach ($out as $key => $value) {
            $out[$key] = mb_convert_encoding($value, 'UTF-8', 'EUC-KR');
        }
    }
     //print_r($out);
     $timezone = new DateTimeZone('Asia/Seoul');
        $idate_korea = new DateTime("now", $timezone);
        $nowtime = $idate_korea->format("Y-m-d H:i:s");
        $date = $idate_korea->format("Y-m-d");
        $logfile = fopen("log/".$date."_pay.txt" , "a+");
        $outlog = print_r($out,true);


        fwrite($logfile, "************************************************\n");
        fwrite($logfile, $useridx . ":" . $nowtime . " : " . $outlog . "\r\n");

        fwrite($logfile, "************************************************");


        fclose($logfile);?>

<?  $coinquery = "SELECT name, tel FROM member WHERE useridx = '$useridx'";
    $coinresult = $conn->query($coinquery);
    $coinrow = mysqli_fetch_row($coinresult);
    $name = $coinrow[0];

    $query5 = "SELECT CAST(AES_DECRYPT(UNHEX(tel), 'myshop') AS CHAR(50)) AS tel from member where useridx = '$useridx'";
    $result5 = $conn->query($query5);
    $row5 = mysqli_fetch_row($result5);
    $tel1 = $row5[0];


    $tel2 = str_replace('-', '', $tel1);?>


        <?
    if (@$out["P_STATUS"] === "00") {

        

    ?>

        <style>
            @import url(https://cdn.jsdelivr.net/gh/moonspam/NanumSquare@2.0/nanumsquare.css);

            body {
                margin: 0;
                padding: 0 20px;
                font-family: 'Noto Sans CJK KR', sans-serif;
            }

            .orderContainer {
                width: 100%;
                overflow: auto;
                /* 스크롤이 필요한 경우에만 스크롤이 생성되도록 설정 */
                height: calc(100vh - 200px);
                max-height: calc(100vh - 200px);
                padding-bottom: 200px;
                /* 아래의 버튼만큼 여백을 주기 위한 값 (조절 가능) */
            }

            .ordernum_div {
                width: 100%;
                height: 48px;
                display: flex;
                justify-content: space-between;
                /* 주문번호, 주문시간, 결제완료를 동일한 간격으로 정렬 */
                align-items: center;
                /* 세로 중앙 정렬 */
                color: #3F3F3F;
                background: #F9F9F9;
                margin-bottom: 10px;
            }

            .ordernum,
            .order_idate,
            .order_end {
                font-size: 14px;
                letter-spacing: -0.35px;
                color: #3F3F3F;
                padding: 11px;
                box-sizing: border-box;
                /* padding이 요소의 크기에 영향을 미치도록 box-sizing 설정 */
            }

            .ordernum {
                font-family: 'NanumSquare';
                font-size: 14px;
                letter-spacing: -0.35px;
                color: #3F3F3F;
                padding: 11px;
            }

            .order_idate {
                font-family: 'NanumSquare';
                font-size: 14px;
                letter-spacing: -0.35px;
                color: #898989;
                padding: 11px;
            }

            .order_end {
                font-family: 'NanumSquare';
                font-size: 14px;
                letter-spacing: -0.35px;
                color: #3F3F3F;
                padding: 11px;
            }

            .order_product {
                font-family: 'NanumSquare';
                margin-top: 16px;
                font-size: 18px;
                width: 100%;
                height: 36px;
                display: flex;
                align-items: center;
                /* 세로 중앙 정렬 */
                color: #3F3F3F;
                border-bottom: 1px solid #898989;
                /* 아랫줄 스타일 설정 */
                padding-bottom: 8px;
                /* 원하는 간격 설정 */
            }

            .product_div {
                margin-top: 15px;
                width: 100%;
                height: 95px;
                display: flex;
                align-items: center;
                /* 세로 중앙 정렬 */
                justify-content: space-between;
                /* 가장 오른쪽에 붙이기 위한 스타일 */
                background: #FFFFFF;
            }

            .product_image {
                /*이미지 px고정 */
                width: 96px;
                height: 96px;
            }

            .product_info {
                margin-left: auto;
                /* 자동으로 왼쪽 여백을 최대화하여 가장 오른쪽으로 이동 */
                width: 57.78%;
                overflow: hidden;
            }

            .product_name {
                font-family: 'Jalnan';
                margin-top: 2px;
                font-size: 15px;
                letter-spacing: -0.35px;
                color: #3F3F3F;
                overflow: hidden;
                /* 넘치는 부분을 숨김 */
                text-overflow: ellipsis;
                /* 넘치는 부분에 대한 생략 부호 (...) 표시 */
                display: -webkit-box;
                -webkit-line-clamp: 2;
                /* 표시할 줄 수 */
                -webkit-box-orient: vertical;
                padding: 11px;
                margin-bottom: 16px;
            }

            .product_quantity {
                font-family: 'Jalnan';
                font-size: 14px;
                letter-spacing: -0.35px;
                color: #898989;
                padding: 11px;
                margin-bottom: 16px;
            }

            .product_price {
                font-family: 'Jalnan';
                font-weight: bold;
                font-size: 14px;
                letter-spacing: -0.35px;
                color: #3F3F3F;
                padding: 11px;
                margin-bottom: 16px;
            }

            .shipping_information {
                font-family: 'Jalnan';
                margin-top: 32px;
                margin-bottom: 16PX;
                font-size: 18px;
                width: 100%;
                height: 37px;
                display: flex;
                align-items: center;
                /* 세로 중앙 정렬 */
                color: #3F3F3F;
                border-bottom: 1px solid #898989;
                /* 아랫줄 스타일 설정 */
            }


            .recipient_div {
                display: flex;
                justify-content: space-between;

                width: 100%;
                height: 100px;
                margin-top: 14px;

            }

            .recipient_info {
                width: 33.75%;
                height: auto;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
            }

            .recipient_detail {
                width: 66.25%;
                height: auto;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
            }

            .recipient_left {
                font-family: 'NanumSquare';
                font-size: 14px;
                letter-spacing: -0.35px;
                color: #898989;

            }

            .recipient_right {
                font-family: 'NanumSquare';
                font-size: 14px;
                letter-spacing: -0.35px;
                color: #3F3F3F;
                text-align: left;

            }

            .payment_div {
                display: flex;
                justify-content: space-between;
                width: 100%;
                height: 66px;
                margin-top: 14px;
            }

            .payment_detail {
                width: 66.25%;
                height: 66px;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
            }

            .payment_right {
                font-family: 'Jalnan';
                font-weight: bold;
                font-size: 15px;
                letter-spacing: -0.35px;
                color: #F26052;
                text-align: left;
            }

            .orderlist_button {
                width: 100%;
                height: 48px;
                background: var(--unnamed-color-f26052) 0% 0% no-repeat padding-box;
                background: #F26052 0% 0% no-repeat padding-box;
                color: #FFFFFF;
                border: 2px solid #FFFFFF;
                border-radius: 6px;
                font-size: 16px;
                margin-bottom: 8px;
            }

            .home_button {
                width: 100%;
                height: 48px;
                background: #FFFFFF;
                /* 버튼 색상을 하얀색으로 변경 */
                color: #000000;
                /* 글씨 색상을 검정색으로 변경 */
                border: 2px solid #F26052;
                /* 버튼 바깥선 색상을 #F26052로 변경 */
                border-radius: 6px;
                font-size: 16px;
                /* 글씨 크기를 16px로 변경 */
                margin-bottom: 1%;
            }

            .main-content {
                width: 100%;
                overflow: auto;
                /* 스크롤이 필요한 경우에만 스크롤이 생성되도록 설정 */
                height: calc(100vh - 200px);
                max-height: calc(100vh - 200px);
                padding-bottom: 200px;
                /* 아래의 버튼만큼 여백을 주기 위한 값 (조절 가능) */
            }

            .fixed-bottom {
                position: fixed;
                bottom: 0;
                width: calc(100% - 40px);
                /* 양쪽에 20px 여백 */
                background: #F9F9F9;
                z-index: 2;
            }

            .order-completed {
                margin: 0;
                text-align: center;
                border: 1px solid gray;
                width: 100%;
                height: 100%;
                font-size: 25px;
            }

            .refund {
                border: 1px solid gray;
                width: 100%;
                font-size: 20px;
                background-color: #F26052;
                color: white;
            }
        </style>

        <script language="javascript">
            //여기는 라이브러리 함수
            <?php
            if ($iphone == "0") {
            ?>

                function viewinfo(data) {
                    window.customermanager.viewinfo(data);

                }
            <?php
            } else {
            ?>

                function viewinfo(sidx) {
                    try {
                        webkit.messageHandlers.callbackHandler.postMessage(sidx);
                    } catch (err) {

                        console.log('error');
                    }
                }
            <?php
            }

            ?>

            function loadwebview2(idx, name) {
                let hostaddress = "<?= $hostaddress ?>";
                url = hostaddress + "/myshop_login.php";
                var msg = "loadwebviewfinish3</>" + url + "</>" + idx + "</>" + name;
                viewinfo(msg);
            }

            function loadwebview(url, idx, name) {
                var msg = "loadwebview</>" + url + "</>" + idx + "</>" + name;
                viewinfo(msg);
            }

            function onviewclose() {
                viewinfo("viewclose</>0");
            }

            function newurldo(idx, name) {
                var url = document.maincont.myorderlist.value;

                loadwebview(url, idx, name);
            }
            
        </script>

        <?php
        $iphone = 0;

  

        $destinationidx = isset($_GET['destinationidx']) ? $_GET['destinationidx'] : 0;
        $buy = $_GET['buy'];
        $totalDeliveryCharge = $_GET['totalDeliveryCharge'];
        $recommender = $_REQUEST['recommender'];


        if ($recommender != "") {
            $recommender2 = "HEX(AES_ENCRYPT('$recommender','myshop'))";
        } else {
            $recommender2 = "'0'";
        }
        $delivery_memo = $_REQUEST['memo'];
        $timezone = new DateTimeZone('Asia/Seoul');
        $idate_korea = new DateTime("now", $timezone);
        $aaa = $idate_korea->format("Y-m-d H:i:s");

        if ($delivery_memo == '') {
            $delivery_memo = '배송 메모가 없습니다.';
        } else if ($delivery_memo == 1) {
            $delivery_memo = '집 문 앞에 놓아주세요.';
        } else if ($delivery_memo == 2) {
            $delivery_memo = '벨을 눌러주세요.';
        } else if ($delivery_memo == 3) {
            $delivery_memo = '배송 전 전화주세요';
        }
        $noroop = true;
        if ($buy == 3) {
            $Selectquery = "SELECT menuidx,company_idx,quantity, price,delivery, optidx,optidx2,optidx3,paycoin FROM bag WHERE useridx = '$useridx' AND checkbox = 1";
            $Selectresult = mysqli_query($conn, $Selectquery);
            $totalPrice = 0;
            $totalPrice += $totalDeliveryCharge;
            $qu = 3;
        } else {
            $menuidx = $_GET['menuidx'];
            if ($buy == 2) {
                $Selectquery = "SELECT menuidx,price,company_idx from menu where menuidx = '$menuidx'";
                $Selectresult = mysqli_query($conn, $Selectquery);
                $totalPrice = 0;
                $totalPrice += $totalDeliveryCharge;

                $quantity = $_GET['quantity'];
                $qu = 2;
            } elseif ($buy == 1) {
                $menuidx = $_GET['menuidx'];
                $option_idx = $_GET['option_idx'];

                $Selectquery = "SELECT p_optionidx,option_level,menuidx,optionidx,option_price from option_test where optionidx = '$option_idx'";
                $Selectresult = mysqli_query($conn, $Selectquery);
                $totalPrice = 0;
                $totalPrice += $totalDeliveryCharge;
                $qu = 1;
            }
        }

        $company_menu_prices = [];
        $delivery_fees = [];
        $total_menu_price = [];
        $company_idx_array = [];
        $company_delivery_fees = []; 
        $company_reson_value = [];

        while ($row = mysqli_fetch_array($Selectresult)) {
            if ($buy == 3) {
                $quantity = $row['quantity'];
            } else {
                $quantity = $_GET['quantity'];
            }
            if (isset($row['price'])) {
                $product_price = $row['price'] * $quantity;
            } else {
                @$product_price = $row['option_price'] * $quantity;

            }
            if (isset($row['optidx'])) {
                $optidx = $row['optidx'];
            } else if (isset($row['option_idx'])) {
                $optidx = $row['option_idx'];
            } else {

                $optidx = 0;
            }

            if ($buy == 3) {
                $menuidx = $row['menuidx'];

            } else {
                $menuidx = $_GET['menuidx'];
            }

            $totalPrice += $product_price;

            // 추가: 메뉴의 company_idx 가져오기
            if ($buy == 1) {
                $companyquery = "SELECT company_idx from menu where menuidx = $menuidx";
                $companyresult = mysqli_query($conn, $companyquery);
                $companyrow = mysqli_fetch_array($companyresult);
                $company_idx = $companyrow['company_idx'];
                $quantity = $_GET['quantity'];
            } else {
                $company_idx = $row['company_idx'];
            }
            $delresonquery = "SELECT delivery_condition,delivery_option,price,delivery_price FROM menu where menuidx = '$menuidx' ";
            $delresonresult = mysqli_query($conn, $delresonquery);
            $delresonrow = mysqli_fetch_array($delresonresult);
            $delivery_option = $delresonrow['delivery_option'];
            $delivery_fee = $delresonrow['delivery_price'];
            if($buy == 3){
                $quantity = $row['quantity'];
                $price = $row['price'];
            }
            if($buy == 1){
                $price = $row['option_price'];
            }
            if($buy == 2){
                $price = $row['price'];
            }
            if($delivery_option == 1){
                if($price*$quantity >= $delresonrow['delivery_condition']){
                    $resontest = 4;
                }else{
                    $resontest = 0;
                } 
            }else{
                $resontest = 0;
            }
            $company_idx_array[] = $company_idx;
            $company_menu_prices[$company_idx][] = $product_price;
            $company_delivery_fees[$company_idx][] = $delivery_fee;
            $company_reson_value[$company_idx] = $resontest;
        }
        // 추가: 해당 company_idx의 메뉴 가격을 기존 값에 더하기
        foreach ($company_menu_prices as $company_idx => $menu_prices) {
            // 회사별로 메뉴 총 가격 계산
            $total_menu_price = array_sum($menu_prices);

            $delivery_fee = array_sum($company_delivery_fees[$company_idx]);

 



            
            $delivery_reason_value = $company_reson_value[$company_idx];
            // 추가: 배송 정보를 INSERT 쿼리에 추가
            $delivery_query = "INSERT INTO delivery(company_idx, user_idx, order_num, delivery_fee, delivery_status, delivery_reason, idate) VALUES ('$company_idx', '$useridx','$ordernum', '$delivery_fee', '1', '$delivery_reason_value', '$aaa')";

            // 추가: 배송비 정보 쿼리 실행
            $result_delivery_query = mysqli_query($conn, $delivery_query);
        }

        mysqli_data_seek($Selectresult, 0); // 결과 포인터 재설정
        while ($row = mysqli_fetch_array($Selectresult)) {
            if ($buy == 3) {
                $quantity = $row['quantity'];
                if($row['paycoin']){
                $paycoin = $row['paycoin'];
                }else{
                    $paycoin = 0;
                }
            } else {
                $quantity = $_GET['quantity'];
            }
            if (isset($row['price'])) {
                $product_price = $row['price'] * $quantity;
            } else {


                if($row['p_optionidx']){

                    $optlevel = $row['option_level'];

                    $p_optionidx = $row['p_optionidx'];
                    $optQuery2 = "SELECT p_optionidx,option_value,option_price,option_level FROM option_test WHERE optionidx = '$p_optionidx'";
                    $optResult2 = $conn->query($optQuery2);
                    $optRow2 = mysqli_fetch_assoc($optResult2);
                    $optlevel = $optRow2['option_level'];
                    if($optRow2['p_optionidx']){
                        $p_optionidx2 = $optRow2['p_optionidx'];

                        $optQuery1 = "SELECT p_optionidx,option_value,option_price,option_level FROM option_test WHERE optionidx = '$p_optionidx2'";
                        $optResult1 = $conn->query($optQuery1);
                        $optRow1 = mysqli_fetch_assoc($optResult1);
                        $optlevel = $optRow1['option_level'];

                    }
                    if(@$optlevel == 2) {
                        @$product_price = $optRow2['option_price'] * $quantity;
                    }else if(@$optlevel == 1) {
                        @$product_price = $row['option_price'] * $quantity;
                    }else if(@$optlevel == 3){
                        $product_price = $optRow1['option_price'] * $quantity;
                    }

                    

                }
            }
            if (isset($row['optidx'])) {
                $optidx1 = $row['optidx'];
                $optidx2 = $row['optidx2'];
                $optidx3 = $row['optidx3'];
                $optidx = max($optidx3, $optidx2, $optidx1);
            } else if (isset($row['optionidx'])) {
                $optidx = $row['optionidx'];
            } else {

                $optidx = 0;
            }

            if ($buy == 3) {
                $menuidx = $row['menuidx'];
            } else {
                $menuidx = $_GET['menuidx'];
            }
            $company_idx = array_shift($company_idx_array);
            if($out['P_NOTI']){
            $destinationidx = $out['P_NOTI'];
            }
            $destinationidx = intval($destinationidx);
            $destinationselect = "SELECT * FROM destination WHERE destinationidx = '$destinationidx'";
            $destinationquery = mysqli_query($conn, $destinationselect);
            $destinationrow = mysqli_fetch_array($destinationquery);
            $recipient = $destinationrow['recipient'];
            $postcode = $destinationrow['postcode'];
            $old_address = $destinationrow['old_address'];
            $new_address = $destinationrow['new_address'];
            $detail_address = $destinationrow['detail_address'];
            $tel = $destinationrow['tel'];


            
            $timezone = new DateTimeZone('Asia/Seoul');
            $idate_korea = new DateTime("now", $timezone);
            $VACT_Date = $idate_korea->format("Y-m-d H:i:s");
            if (@$out['P_TYPE'] == "CARD") {

                $payment = "신용카드결제";
                $order_status = 2;
            } else if (@$out['P_TYPE'] == "VBANK") {
                $payment = "무통장입금";
                $order_status = 0;
            } else if (@$out['P_TYPE'] == "BANK") {
                $payment = "계좌이체";
                $order_status = 30;
            } else if (@$out['P_TYPE'] == "MOBILE") {
                $payment = "휴대폰결제";
                $order_status = 20;
            }
            $P_TYPE = $out['P_TYPE'];
            if ($P_TYPE == "VBANK") {
                $P_TYPE = "VBank";
                $P_VACT_DATE = $out['P_VACT_DATE'];
                $first = substr($P_VACT_DATE, -3, -2);
                $seconde = substr($P_VACT_DATE, -2);
                $P_VACT_TIME = $out['P_VACT_TIME'];
                $first2 = substr($P_VACT_TIME, 0, 2);
                $seconde2 = substr($P_VACT_TIME, 2, 2);
                $timestamp = strtotime($out['P_VACT_DATE']);
                $VACT_Date1 = date("Y-m-d H:i:s", $timestamp);
                $timestamp2 = strtotime($out['P_VACT_TIME']);
                
                $VACT_Date2 = date("H:i:s", $timestamp2);
                $updatedate = substr($VACT_Date1,0,10);
                
                $VACT_Date = $updatedate." ".$VACT_Date2;
            } else if ($P_TYPE == "MOBILE") {
                $P_TYPE = "HPP";
            } else if ($P_TYPE == "CARD") {
                $P_TYPE = "Card";
            } else if ($P_TYPE == "BANK") {
                $P_TYPE = "DirectBank";

            }
            $P_AUTH_NO = isset($out['P_AUTH_NO']) ? $out['P_AUTH_NO'] : '0';
            $P_TID = isset($out['P_TID']) ? $out['P_TID'] : '0';
            $P_RMESG2 = isset($out['P_RMESG2']) ? $out['P_RMESG2'] : '0';
            $P_FN_NM = isset($out['P_FN_NM']) ? $out['P_FN_NM'] : '0';
            $P_CARD_NUM = isset($out['P_CARD_NUM']) ? $out['P_CARD_NUM'] : '0';
            $P_VACT_NAME = isset($out['P_VACT_NAME']) ? $out['P_VACT_NAME'] : '0';
            $P_VACT_NUM = isset($out['P_VACT_NUM']) ? $out['P_VACT_NUM'] : '0';
            $P_VACT_BANK_CODE = isset($out['P_VACT_BANK_CODE']) ? $out['P_VACT_BANK_CODE'] : '0';
            $P_HPP_NUM = isset($out['P_HPP_NUM']) ? $out['P_HPP_NUM'] : '0';
            $P_FN_CD1 = isset($out['P_FN_CD1']) ? $out['P_FN_CD1'] : '0';
            $VACT_InputName = null;
            if (!empty($out['P_VACT_NUM']) && $out['P_TYPE'] == "VBANK") {
                // VBANK 결제 방식일 때만 가상 계좌 입금자명을 조회
                $inputname = "select name FROM member WHERE useridx = '$useridx'";
                $inputnameresult = mysqli_query($conn, $inputname);
                $inputrow = mysqli_fetch_assoc($inputnameresult);
                $VACT_InputName = $inputrow['name'];
            }
            
                $menuselect = "SELECT mname, price,company_idx,somemidx, imagepath1,reward_ratio,nap_price,MDpay,card_carge FROM menu WHERE menuidx = '$menuidx'";
                $menuquery = mysqli_query($conn, $menuselect);
                $menuData = mysqli_fetch_assoc($menuquery);
                $menuData_MDpay =  $menuData['MDpay']; // 
                $menuData_card_carge =  $menuData['card_carge']; //카드수수료
            if($optidx == 0){ // no option
                $menuData_price =  $menuData['price']; // 제품 가격
                $menuData_sidx = $menuData['somemidx']; // 제품 소싱멤버 idx
                $menureward_ratio = $menuData['reward_ratio']; // 제품 구매시 리워드 ratio
                $menunap_price = $menuData['nap_price']+$menuData_MDpay+($menuData_price*($menuData_card_carge/100)); // 제품 납품가
                $aaabbb= ($menuData_price-$menunap_price)*($menureward_ratio/100)*$quantity; //제품 구매시 구매자 리워드 포인트
                $menureward_point = round($aaabbb , -1); //1의자리 반올림
                $dddeee= (($menuData_price-$menunap_price)*(23/100))*$quantity; //제품구매시 소싱팀 리워드포인트 (마진의 23%)
                $menu_so_reward_point = round($dddeee , -1); 
                $cccccc = ($menuData_price-$menunap_price)*(1/100)*$quantity; // 제품 구매시 추천인 리워드 포인트(마진의 1%)
                $menu_reco_reward_point = round($cccccc , -1); 
            }else{ // option
                $menuOPselect = "SELECT option_price,option_nap_price FROM option_test WHERE optionidx = '$optidx'";
                $menuOPquery = mysqli_query($conn, $menuOPselect);
                $menuOPData = mysqli_fetch_assoc($menuOPquery);
                $menuData_price =  $menuOPData['option_price']; // 제품 가격
                $menuData_sidx = $menuData['somemidx']; // 제품 소싱멤버 idx
                $menureward_ratio = $menuData['reward_ratio']; // 제품 구매시 리워드 ratio
                $menunap_price = $menuOPData['option_nap_price']+$menuData_MDpay+($menuData_price*($menuData_card_carge/100)); // 제품 납품가
                $aaabbb= ($menuData_price-$menunap_price)*($menureward_ratio/100)*$quantity; //제품 구매시 구매자 리워드 포인트
                $menureward_point = round($aaabbb , -1); //1의자리 반올림
                $dddeee= (($menuData_price-$menunap_price)*(23/100))*$quantity; //제품구매시 소싱팀 리워드포인트(마진의 23%)
                $menu_so_reward_point = round($dddeee , -1); //1의자리 반올림
                $cccccc = ($menuData_price-$menunap_price)*(1/100)*$quantity; // 제품 구매시 추천인 리워드 포인트(마진의 1%)
                $menu_reco_reward_point = round($cccccc , -1);
                
            }
            
            if($buy == 3){
                $update_query = "INSERT into orderlist(destinationidx,useridx,company_idx,recipient,tel,payment,menuidx,ordernum,price,product_price,idate,quantity,order_status,orderfrom,P_AUTH_NO,P_TID,P_TYPE,P_RMESG2,P_FN_NM,P_CARD_NUM,option_idx,postcode,old_address,new_address,detail_address,recommender,delivery_memo,VACT_NUM,VACT_BankCode,vactBankName,VACT_Name,VACT_InputName,HPP_Num,ACCT_BankCode,VACT_Date,paycoin,j_discount)Values('$destinationidx','$useridx','$company_idx','$recipient','$tel','$payment','$menuidx','$ordernum','$P_AMT','$product_price','$aaa','$quantity','$order_status','1','$P_AUTH_NO','$P_TID','$P_TYPE','$P_RMESG2','$P_FN_NM','$P_CARD_NUM','$optidx','$postcode','$old_address','$new_address','$detail_address',$recommender2,'$delivery_memo','$P_VACT_NUM','$P_VACT_BANK_CODE','$P_FN_NM','$P_VACT_NAME','$VACT_InputName','$P_HPP_NUM','$P_FN_CD1','$VACT_Date','$paycoin','$discountPrice')";
                $result = $conn->query($update_query);
            }else{
                $update_query = "INSERT into orderlist(destinationidx,useridx,company_idx,recipient,tel,payment,menuidx,ordernum,price,product_price,idate,quantity,order_status,orderfrom,P_AUTH_NO,P_TID,P_TYPE,P_RMESG2,P_FN_NM,P_CARD_NUM,option_idx,postcode,old_address,new_address,detail_address,recommender,delivery_memo,VACT_NUM,VACT_BankCode,vactBankName,VACT_Name,VACT_InputName,HPP_Num,ACCT_BankCode,VACT_Date,paycoin,j_discount)Values('$destinationidx','$useridx','$company_idx','$recipient','$tel','$payment','$menuidx','$ordernum','$P_AMT','$product_price','$aaa','$quantity','$order_status','1','$P_AUTH_NO','$P_TID','$P_TYPE','$P_RMESG2','$P_FN_NM','$P_CARD_NUM','$optidx','$postcode','$old_address','$new_address','$detail_address',$recommender2,'$delivery_memo','$P_VACT_NUM','$P_VACT_BANK_CODE','$P_FN_NM','$P_VACT_NAME','$VACT_InputName','$P_HPP_NUM','$P_FN_CD1','$VACT_Date','$coin','$discountPrice')";
                $result = $conn->query($update_query);
            }

            if(@$out['P_TYPE'] != "VBANK"){
                include 'reco_point.php'; //추천인 적립
                include 'mem_point.php'; //구매자 적립
                include 'so_point.php'; //소싱팀 적립
            }
            


            
            


            // if(!$result){
                $logfile = fopen("log/".$date."_debug.txt" , "a+");
                $outlog = print_r($out,true);
        
        
                fwrite($logfile, "************************************************\n");
                fwrite($logfile, $useridx . ":" . $nowtime . " : " . "주소idx".$destinationidx . ":" . "회사idx".$company_idx . " : " .$P_RMESG2." : ".$outlog . "\r\n");
        
                fwrite($logfile, "************************************************");
        
        
                fclose($logfile);

            // }


            $select_delivery_query = "SELECT delivery_idx FROM delivery WHERE company_idx='$company_idx' AND order_num='$ordernum'";
            $delivery_result = $conn->query($select_delivery_query);
            $delivery_row = mysqli_fetch_assoc($delivery_result);

            $update_orderlist_query = "UPDATE orderlist SET delivery_idx = '{$delivery_row['delivery_idx']}' WHERE ordernum = '$ordernum' AND company_idx = '$company_idx'";
            mysqli_query($conn, $update_orderlist_query);



            if ($buy == 3) {
                $selectprice = "SELECT price from orderlist where ordernum = '$ordernum' order by orderidx DESC";
                $resultprice = mysqli_query($conn, $selectprice);
                $rowprice = mysqli_fetch_array($resultprice);
                $price9 = $rowprice['price'];
                $updateprice = "UPDATE orderlist set price = '$price9' Where ordernum='$ordernum'";
                $updateresult = mysqli_query($conn, $updateprice);
            }

            $stockupdate = "update menu set stock = stock-'$quantity' where menuidx = '$menuidx'";
            mysqli_query($conn, $stockupdate);

            $selectorderidx = "select orderidx, option_idx from orderlist where ordernum = '$ordernum'";
            $orderidxresult = mysqli_query($conn,$selectorderidx);
           while( $orderidxrow = mysqli_fetch_assoc($orderidxresult)){
            $menuimage = "SELECT imagepath1 from menu where menuidx = $menuidx";
            $menuresult = mysqli_query($conn, $menuimage);
            $menurow = mysqli_fetch_array($menuresult);
            $menuimagepath1 = $menurow['imagepath1'];
            $timezone2 = new DateTimeZone('Asia/Seoul');
            $idate_korea2 = new DateTime("now", $timezone2);
            $idateee = $idate_korea2->format("Y-m-d H:i:s");
           }
            
        }
        /////////////// 홈으로 버튼 추가
    
        if ($out['P_TYPE'] == "VBANK") {
        ?>
            <div style="margin-top : 60%;">

                <center><span style="color: #F26052;"> 품절될 수 있습니다. </span><br><span> 입금을 서둘러 주세요!</span></center><br>

                <div class="order-completed"> <span> <?= $P_FN_NM ?> <?= $P_VACT_NUM ?> <br> <?= number_format($P_AMT) ?>원<span><br><br>

                            <center>
                                <div style="background-color : #F26052; width : 80%; height: 70%;" onclick=""></div>
                            </center>

                            <div style="font-size : 19px;"><span style="color: #F26052;">내일(<?= $first ?>/<?= $seconde ?>) <?= $first2 ?>:<?= $seconde2 ?></span>분까지 미입금 시 취소</div><br>
                            
                            
                </div>

            </div>
            <br>
            
                
                <button class='home_button' onclick="onviewclose()">홈으로 
            </button>
                <br><br>
            
        <?php
        } else {




            ////////////////////////////////////////////////////////////////////////////////////////



        ?>

<body>
    <?php

            $orderselect2 = "select count(menuidx) from orderlist WHERE ordernum='$ordernum'";
            $result2 = mysqli_query($conn, $orderselect2);
            $row = mysqli_fetch_row($result2);

            $count = $row[0];

            $query = "SELECT menuidx,price,quantity,idate from orderlist where ordernum = '$ordernum'";
            $result = mysqli_query($conn, $query);
            // $orderlistquery에서 주문 정보를 가져옵니다.
    ?>

    <article class="orderContainer">
        <div class="ordernum_div">
            <span class="ordernum"><?= $ordernum ?></span>
            <span class="order_idate"><?= $aaa ?></span>
            <span class="order_end">결제완료</span>
        </div> <!--ordernum_div-->
        <div class="order_product">주문 상품(<?= $count ?>)</div> <!--order_product-->

        <?php
            $query3 = "SELECT menuidx, quantity,option_idx,product_price FROM orderlist WHERE ordernum = '$ordernum'";
            $result3 = $conn->query($query3);

            $totalPrice = 0;
            while ($row = mysqli_fetch_assoc($result3)) {
                $menuidx = $row['menuidx'];
                $quantity = $row['quantity'];
                $optidx = $row['option_idx'];


                $optQuery = "SELECT p_optionidx,option_value,option_price,option_level FROM option_test WHERE optionidx = '$optidx'";
                $optResult = $conn->query($optQuery);
                $optRow = mysqli_fetch_assoc($optResult);

                $menuQuery = "SELECT mname, price, imagepath1 FROM menu WHERE menuidx = '$menuidx'";
                $menuResult = $conn->query($menuQuery);
                $menuRow = mysqli_fetch_assoc($menuResult);
                if ($optRow) {
                    $current_option_idx = $optidx;
                    $options = [];
                    $menuname = $menuRow['mname'];
                
  
                    while ($current_option_idx) {
                        $optionquery = "SELECT option_value, p_optionidx FROM option_test WHERE optionidx = '$current_option_idx'";
                        $optionresult = mysqli_query($conn, $optionquery);
                        $optionrow = mysqli_fetch_assoc($optionresult);
                
                        if ($optionrow) {
                            array_unshift($options, $optionrow['option_value']); // 배열의 시작 부분에 옵션 값을 추가
                            $current_option_idx = $optionrow['p_optionidx']; 
                        } else {
                            break;
                        }
                    }
                
                    $mname = $menuname . " " . implode("/", $options);
                    $price = $row['product_price'];

                } else {
                    $mname = $menuRow['mname'];
                    $price = $menuRow['price'];
                }
                $imagepath1 = $menuRow['imagepath1'];

                $totalPrice += $price * $quantity;
        ?>
            <div class="product_div">
                <img src="<?= $imagepath1 ?>" alt="Product Image" class='product_image'>
                <div class="product_info">
                    <div class="product_name"><?= $mname ?></div>
                    <div>
                        <span class="product_quantity"><?= $quantity ?>개</span>
                        <span class="product_price"><?= number_format($price * $quantity) ?>원</span>
                    </div>
                </div>
            </div> <!--product_div-->
        <?php
            }
        ?>
        <?php  ?>

        <div class="shipping_information">배송정보</div>
        <?php
            $destinationquery = "SELECT destinationidx,AES_DECRYPT(unhex(tel), 'myshop')AS tel,AES_DECRYPT(unhex(postcode), 'myshop')AS postcode,AES_DECRYPT(unhex(new_address), 'myshop')AS new_address,
AES_DECRYPT(unhex(detail_address), 'myshop')AS detail_address,
address_status,recipient 
 from destination where destinationidx = '$destinationidx'";
            $destinationresult = mysqli_query($conn, $destinationquery);
            $destinationrow = mysqli_fetch_array($destinationresult);
        ?>
        <div class="recipient_div">
            <div class="recipient_info">
                <div class="recipient_left">받는분</div>
                <div class="recipient_left">배송지</div>
                <div class="recipient_left">휴대폰 번호</div>
            </div>
            <div class="recipient_detail">
                <div class="recipient_right"><?= $destinationrow['recipient'] ?></div>
                <div class="recipient_right"><?= $destinationrow['postcode'] ?><?= $destinationrow['new_address'] ?><?= $destinationrow['detail_address'] ?></div>
                <div class="recipient_right"><?= $destinationrow['tel'] ?></div>
            </div>
        </div>
        <div class="shipping_information">결제 정보</div>
        <div class="payment_div">
            <div class="recipient_info">
                <div class="recipient_left">결제수단</div>
                <div class="recipient_left">결제금액</div>
            </div>
            <div class="payment_detail">
                <div class="recipient_right"><?= $payment ?></div>
                <div class="payment_right"><?=$P_AMT?>원</div>
            </div>
        </div>
    </article>



    <div class="fixed-bottom">
        <button class='orderlist_button' onclick="newurldo('0','주문내역','<?= $useridx ?>')">주문내역 가기</button>
        <button class='home_button' onclick="onviewclose()">홈으로 가기</button>
    </div>

    <form action='' name='maincont' method="post">
        <input type="hidden" name="myorderlist" value="<?=$hostaddress?>/myorderlist.php">
    </form>
</body>
<?php
    
        }
?>
<script language="javascript">
    //여기는 라이브러리 함수
    <?php
    
        if ($iphone == "0") {
    ?>

        function viewinfo(data) {
            window.customermanager.viewinfo(data);

        }
        function pay_check() {

        var name = "<?php echo $name; ?>"; 
        var phone = "<?php echo $tel2; ?>";
        var product = "<?php echo $ordernum; ?>"; 
        var count = "<?php echo $quantity; ?>";
        var coin = "<?php echo $coin; ?>";

        var url=`check_pay.php?name=${name}&phone=${phone}&coin=${coin}&product=${product}&count=${count}`;


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
    <?php

if($coin != ""){
    ?>
        // var msg = "seterror</>1111"
        // viewinfo(msg);
        pay_check();
    <?
}
        } else {
    ?>

        function viewinfo(sidx) {
            try {
                webkit.messageHandlers.callbackHandler.postMessage(sidx);
            } catch (err) {

                console.log('error');
            }
        }
    <?php
        }

    ?>
</script>
<script>
    function loadwebview(url, idx, name, useridx) {
        var msg = "loadwebview</>" + url + "</>" + idx + "</>" + name + "</>" + useridx;
        viewinfo(msg);
    }

    function viewinfo(data) {
        window.customermanager.viewinfo(data);

    }

    function onviewclose() {
        viewinfo("viewclose</>0");
    }

    function newurldo(idx, name, useridx) {
        var url = document.maincont.myorderlist.value;

        loadwebview(url, idx, name, useridx);
    }

    function printdlg2(msg, url, titlename) {
        let useridx = "<?= $useridx ?>";
        viewinfo("printdlg2</>" + msg + "</>" + url + "</>" + titlename + "</>" + useridx);
    }
</script>
<?php
        $update_query = "DELETE FROM bag Where useridx = '$useridx' AND checkbox = 1";
        $result = $conn->query($update_query);
    } else if (@$out["P_STATUS"] === "0272") {
?>
    <script>
        function viewinfo(data) {
            window.customermanager.viewinfo(data);

        }

        function printdlg(msg, url, titlename) {
            let useridx = "<?= $useridx ?>";
            viewinfo("printdlg</>" + msg + "</>" + url);
        }
        printdlg("계좌번호 오류", 'myshop.php');
    </script>
<?

    } else {
?>
    <script>
        //여기는 라이브러리 함수
        <?php
        $iphone == "0";
        if ($iphone == "0") {
        ?>

            function viewinfo(data) {
                window.customermanager.viewinfo(data);

            }
        <?php
        } else {
        ?>

            function viewinfo(sidx) {
                try {
                    webkit.messageHandlers.callbackHandler.postMessage(sidx);
                } catch (err) {

                    console.log('error');
                }
            }
        <?php
        }

        ?>
    </script>
    <script>
        function loadwebview(url, idx, name, useridx) {
            var msg = "loadwebview</>" + url + "</>" + idx + "</>" + name + "</>" + useridx;
            viewinfo(msg);
        }

        function viewinfo(data) {
            window.customermanager.viewinfo(data);

        }

        function onviewclose() {
            viewinfo("viewclose</>0");
        }

        function newurldo(idx, name, useridx) {
            var url = document.maincont.myorderlist.value;

            loadwebview(url, idx, name, useridx);
        }

        function printdlg(msg, url, titlename) {
            let useridx = "<?= $useridx ?>";
            viewinfo("printdlg</>" + msg + "</>" + url);
        }
        printdlg("비정상적인 경로입니다.", 'myshop.php');
    </script>
<?php
    }

?>

</html>