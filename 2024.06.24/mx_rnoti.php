<?php
include "dbconn.php";
//*******************************************************************************
// FILE NAME : mx_rnoti.php
// FILE DESCRIPTION :
// �̴Ͻý� smart phone ���� ��� ���� ������ ����
// ������� : ts@inicis.com
// HISTORY 
// 2010. 02. 25 �����ۼ� 
// 2010  06. 23 WEB ����� ������� ���� ������� ä�� ��� ���� ó�� �߰�(APP ����� �ش� ����!!)
// WEB ����� ��� �̹� P_NEXT_URL ���� ä�� ����� ���� �Ͽ����Ƿ�, 
// �̴Ͻý����� �����ϴ� ������� ä�� ��� ������ ���� �Ͻñ� �ٶ��ϴ�.
//*******************************************************************************

  $PGIP = $_SERVER['REMOTE_ADDR'];
  if($PGIP == "203.238.37.15" || $PGIP == "118.129.210.25" || $PGIP == "183.109.71.153")	//PG���� ���´��� IP�� üũ
  {

		// �̴Ͻý� NOTI �������� ���� Value
		$P_TID;				// �ŷ���ȣ
		$P_MID;				// �������̵�
		$P_AUTH_DT;			// ��������
		$P_STATUS;			// �ŷ����� (00:����, 01:����)
		$P_TYPE;			// ���Ҽ���
		$P_OID;				// �����ֹ���ȣ
		$P_FN_CD1;			// �������ڵ�1
		$P_FN_CD2;			// �������ڵ�2
		$P_FN_NM;			// ������� (�����, ī����, ������)
		$P_AMT;				// �ŷ��ݾ�
		$P_UNAME;			// ����������
		$P_RMESG1;			// ����ڵ�
		$P_RMESG2;			// ����޽���
		$P_NOTI;			// ��Ƽ�޽���(�������� �ø� �޽���)
		$P_AUTH_NO;			// ���ι�ȣ
	

		$P_TID = $_REQUEST['P_TID'];
		$P_MID = $_REQUEST['P_MID'];
		$P_AUTH_DT = $_REQUEST['P_AUTH_DT'];
		$P_STATUS = $_REQUEST['P_STATUS'];
		$P_TYPE = $_REQUEST['P_TYPE'];
		$P_OID = $_REQUEST['P_OID'];
		// // $P_FN_CD1 = $_REQUEST[P_FN_CD1];
		// // $P_FN_CD2 = $_REQUEST[P_FN_CD2];
		$P_FN_NM = $_REQUEST['P_FN_NM'];
		$P_AMT = $_REQUEST['P_AMT'];
		$P_UNAME = $_REQUEST['P_UNAME'];
		$P_RMESG1 = $_REQUEST['P_RMESG1'];
		// $P_RMESG2 = $_REQUEST[P_RMESG2];
		// $P_NOTI = $_REQUEST[P_NOTI];
		// $P_AUTH_NO = $_REQUEST[P_AUTH_NO];


		// $query = "select price,VACT_Num from orderlist" ;
		// $result = mysqli_query($conn, $query);
		// $row = mysqli_fetch_assoc($result);
		// $price = $row['price'];
		// $VACT_Num = $row['VACT_Num'];

		//WEB ����� ��� ������� ä�� ��� ���� ó��
		//(APP ����� ��� �ش� ������ ���� �Ǵ� �ּ� ó�� �Ͻñ� �ٶ��ϴ�.)
		 if($P_TYPE == "VBANK")	//���������� ��������̸�
        	{
           	   if($P_STATUS != "02") //�Ա��뺸 "02" �� �ƴϸ�(������� ä�� : 00 �Ǵ� 01 ���)
           	   {
	              echo "OK";
        	      return;
           	   }
        	}



  		$PageCall_time = date("H:i:s");

		$value = array(
				"PageCall time" => $PageCall_time,
				"P_TID"			=> $P_TID,  
				"P_MID"     => $P_MID,  
				"P_AUTH_DT" => $P_AUTH_DT,      
				"P_STATUS"  => $P_STATUS,
				"P_TYPE"    => $P_TYPE,     
				"P_OID"     => $P_OID,  
				// "P_FN_CD1"  => $P_FN_CD1,
				// "P_FN_CD2"  => $P_FN_CD2,
				"P_FN_NM"   => $P_FN_NM,  
				"P_AMT"     => $P_AMT,  
				"P_UNAME"   => $P_UNAME  
				// "P_RMESG1"  => $P_RMESG1  
				// "P_RMESG2"  => $P_RMESG2,
				// "P_NOTI"    => $P_NOTI,  
				// "P_AUTH_NO" => $P_AUTH_NO
				);
 

 			// ����ó���� ���� �α� ���
 		writeLog($value);
 
 
		/***********************************************************************************
		 ' ������ ���� �����ͺ��̽��� ��� ���������� ���� �����ÿ��� "OK"�� �̴Ͻý��� ���нô� "FAIL" ��
		 ' �����ϼž��մϴ�. �Ʒ� ���ǿ� �����ͺ��̽� ������ �޴� FLAG ������ ��������
		 ' (����) OK�� �������� �����ø� �̴Ͻý� ���� ������ "OK"�� �����Ҷ����� ��� �������� �õ��մϴ�
		 ' ��Ÿ �ٸ� ������ echo "" �� ���� �����ñ� �ٶ��ϴ�
		'***********************************************************************************/
		
		// if(�����ͺ��̽� ��� ���� ���� ���Ǻ��� = true)
		if($P_STATUS == "02"){
			
				$update_query = "update orderlist set order_status = '1' where ordernum = '$P_OID'";
				$update_result = mysqli_query($conn, $update_query);


				include 'mx_reward.php';
			
		    echo "OK"; //����� ������ ������
			
		}
		else
		{
			 echo "FAIL";
		}

  }

function writeLog($msg)
{
    $file = "noti_input_".date("Ymd").".log";
	$path = "log/";

    if(!($fp = fopen("log/noti_".date("Ymd").".log", "a+"))) return 0;
                
    ob_start();
    print_r($msg);
    $ob_msg = ob_get_contents();
    ob_clean();
		
    if(fwrite($fp, " ".$ob_msg."\n") === FALSE)
    {
        fclose($fp);
        return 0;
    }
    fclose($fp);
    return 1;
}


?>
