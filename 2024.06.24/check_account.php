<?
    $nice_id='0eea444d-99fc-4034-be6b-4a90d459d1a3';
    $nice_sec='c1da709cbf016952d4e56aed2fae5ef7';
    $access_token='7887abd5-492a-46e7-887d-7a82a4ea7ee5';
    $url = 'https://svc.niceapi.co.kr:22001/digital/niceid/api/v1.0/account/otp/name';
    $current_time = time();
    $auth = base64_encode($access_token.":".$current_time.":".$nice_id);
    $header = array(
        'Content-Type: application/json',
        'Authorization: bearer '.$auth,
        'ProductID:2101838010'
    );
    $request_no = strval(time()); // 요청고유번호
    $bank_cd = $_GET['bank_cd'];  //은행코드
    $account_id = $_GET['account_id']; //계좌번호
    $name = $_GET['name']; //예금주명
    
    // $dataHeader = ['CNTY_CD'=>'ko'];
    // $dataBody = ['request_no'=>$request_no,'bank_cd'=>$bank_cd,'account_id'=>$account_id,'name'=>$name];
    $jdatabody = json_encode(['dataHeader'=>['CNTY_CD'=>'ko'],'dataBody'=>['request_no'=>$request_no,'bank_cd'=>$bank_cd,'account_id'=>$account_id,'name'=>$name]]);
     //echo $jdatabody;
    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $jdatabody,
        CURLOPT_HTTPHEADER => $header,
    ));

    $rtn = curl_exec($ch);
    curl_close($ch);

    if ($rtn === FALSE) {
        error_log('Curl failed');
        die('Curl failed: ' . curl_error($ch));
    }
    curl_close($ch);
    $arr_tmp = json_decode($rtn,true);
    echo($rtn);
    //$result_cd = $arr_tmp['dataBody']['result_cd'];
    
    // if($result_cd != "0000"){
    //     error_log('Check failed');
    //     die('check account failed: ' . $arr_tmp['dataBody']['err_msg']);
    // }
    // $res_uniq_id = $arr_tmp['dataBody']['res_uniq_id'];
?>