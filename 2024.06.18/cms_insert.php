<!DOCTYPE html>
<html lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=yes">
<script>
          function viewinfo(data)
    {
        window.customermanager.viewinfo(data);
    }
    </script>

<?php

    include "dbconn.php";

    session_start();

    $name2 = $_POST['name']??'0';
    $birth = $_POST['birth']??'0';
    // $roadAddrPart1 = $_POST['roadAddrPart1']??'0';
    // $addrDetail = $_POST['addrDetail']??'0';
    $n_address = $_POST['n_address']??'0';
    $email = $_POST['email']??'0';
    $phone_number = $_POST['phone_number']??'0';
    $region = $_POST['region']??'0';
    $m_position = $_POST['m_position']??'0';
    $reco_name = $_POST['reco_name']??'0';
    $reco_tel = $_POST['reco_tel']??'0';
    $pay_amount_1 = $_POST['pay_amount_1']??'0';
    $pay_method_1 = $_POST['pay_method_1']??'0';
    $pay_bankcode = $_POST['text_bankcode']??'0';
    $bankcode = $_POST['pay_bankcode']??'0';
    $pay_acountname = $_POST['pay_acountname']??'0';
    $pay_acountnumber = $_POST['pay_acountnumber']??'0';
    $c_name = $_POST['c_name']??'0';
    $pic_agree1 = $_POST['pic_agree1']??'0';
    $pio_agree1 = $_POST['pio_agree1']??'0';
    $pay_year = $_POST['pay_year']??'0';
    $pay_month = $_POST['pay_month']??'0';
    $pay_date = $_POST['pay_date']??'0';
    $s_name = $_POST['name3']??'0';
    $p_name = $_POST['name4']??'0';
    $back_number = $_POST['back_number']??'0';
    $actionurl = $_POST['action_url']??'0';
    $pdf_file = $_SESSION['pdf_file']??'0';
    $CMS_number = $_SESSION['CMS_number']??'0';
    $sign1 = $_SESSION['sign1']??'0';
    $sign2 = $_SESSION['sign2']??'0';

    if (strlen($pay_acountnumber) > 6) {

        $numXs = strlen($pay_acountnumber) - 6; 
        $xString = str_repeat('x', $numXs); 

        $hash_number = substr($pay_acountnumber, 0, 3) . $xString . substr($pay_acountnumber, -3);
    } else {
        $hash_number = $pay_acountnumber; 
    }
    

    $query2 = "SELECT phone_number FROM cms_member WHERE phone_number = '$phone_number'";
    $result2 = mysqli_query($conn, $query2);
    
    if (mysqli_num_rows($result2) > 0) {
        ?>
        <script>
            var ms = "이미 가입되어 있습니다.";
            var msg="cmsdlg</>" + ms + "</>"+ "fail";
            viewinfo(msg);
         </script>
        <?
        exit;
    }
    

    if($pay_amount_1 == 1)
    {
        $pay_amount = "10,000";
    }
    else if($pay_amount_1 == 2)
    {
        $pay_amount = "100,000";
    }

    if($pay_method_1 == 1)
    {
        $pay_method = "자동이체";
    }
    else if($pay_method_1 == 2)
    {
        $pay_method = "무통장 입금";
    }

    $timezone = new DateTimeZone('Asia/Seoul');
    $idate_korea = new DateTime("now", $timezone);
    $idate = $idate_korea->format("Y-m-d H:i:s");

    $query = "INSERT INTO cms_member (name,c_name,ip_number,n_address,phone_number,email,pay_acountname,pay_birth,pay_bankcode,acountnumber,pay_year,pay_month,pay_date,region,m_position,reco_name,CMS_number,reco_tell,pay_amount,pay_method,pic_agree,pio_agree,idate,newapply,cmschk,pdf_file,sign1,sign2,bankcode,pay_acountnumber)
    values
    ('$name2','$c_name','$back_number','$n_address','$phone_number','$email','$pay_acountname','$c_name','$pay_bankcode','$pay_acountnumber','$pay_year','$pay_month','22','$region','$m_position','$reco_name','$CMS_number','$reco_tel','$pay_amount','$pay_method','$pic_agree1','$pio_agree1','$idate','1','1','$pdf_file','$sign1','$sign2','$bankcode','$hash_number')";
    $result = mysqli_query($conn,$query);

    if($result === true)
    {
        ?>
        <script>
            var msg ="cmsdlg</>"+"회원가입이 완료되었습니다"+ "</>"+ "suc";
            window.customermanager.viewinfo(msg);
        </script>
        <?
    }
    else
    {
        ?>
        <script>
            var error = "cmsdlg</>"+"다시 시도해주세요"+ "</>"+ "fail";
            window.customermanager.viewinfo(error);
        </script>
        <?
    }







?>