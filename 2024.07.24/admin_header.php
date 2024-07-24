<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0  charset=utf-8">
       <link rel="favicon" href="img/16px.ico">
    <link rel="icon" href="https://dev.allmyshop.co.kr/img/16px.ico?v=2" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Agbalumo&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/gh/orioncactus/pretendard/dist/web/static/pretendard.css" />
    <link rel="stylesheet" href="./css/admin_header.css">
    <?
        session_start();
        $admin_member_idx=isset($_SESSION['admin_member_idx']) ? $_SESSION['admin_member_idx'] : '';
        $admin_c_idx=isset($_SESSION['admin_c_idx']) ? $_SESSION['admin_c_idx'] : '';
        //$admin_c_idx = $_SESSION['admin_c_idx'];

        $admin_id=isset($_SESSION['admin_id']) ? $_SESSION['admin_id'] : '';
        $admin_grade=isset($_SESSION['admin_grade']) ? $_SESSION['admin_grade'] : '';

    ?>
    <title>관리자 메인</title>
</head>

<body class="main_tone">

    <section id="header_wrapper" class="header_wrapper">
 
<?php
if($admin_grade < 4) {
?>
    <div id="logo" class="logo" onclick="window.location.href='admin.php';">
        <img src="./img/all_logo_ko.svg" class="all_img" alt="이미지">

    </div>
<?php


//else if($admin_grade == 5) {
?>
    <!-- <div id="logo" class="logo" onclick="window.location.href='<?//=$hostaddress?>/newadmin/ordersums.php';">
        <img src="./img/logo_ko.png" alt="이미지">
        <div>관리자</div>
    </div> -->
<?php
//} 

}else {
    ?>
    <div id="logo" class="logo" onclick="window.location.href='shopBy.php';">
        <img src="./img/all_logo_ko.png" class="all_img" alt="이미지">

    </div>
<?php
} 
?>

    <div class="List_tab">
        <?php
        // 총 관리자
        if($admin_grade < 4) 
        {
        ?>            
                <div class="List_div"><a href="#" onclick='window.location.href="admin.php"'>회원관리</a></div> 
                    <!-- adminidx -> companyidx로 구분 -->
                <div class="List_div"><a href="#" onclick='window.location.href="shopBy_master.php"'>주문관리</a></div>

                <!-- adminidx -> companyidx로 구분 -->
                <div class="List_div"><a href="#" onclick='window.location.href="list.php"'>상품관리</a></div>
        
                <!-- adminidx -> companyidx로 구분 -->
                <div class="List_div"><a href="#" onclick='window.location.href="ordersums.php"'>주문통계</a></div>
        
                <!-- adminidx -> companyidx로 구분 -->
                <div class="List_div"><a href="#" onclick='window.location.href="customer_management.php"'>고객지원</a></div>
        <?php
            }
        
        // 정산
        //else if($admin_grade == 5){}
        
        
        // 발송
        else {
        ?>
            <!-- adminidx -> companyidx로 구분 -->
            <div class="List_div"><a href="#" onclick='window.location.href="list.php"'>상품관리</a></div>
            <!-- adminidx -> companyidx로 구분 -->
            <div class="List_div"><a href="#" onclick='window.location.href="shopBy.php"'>송장관리</a></div>
            <!-- adminidx -> companyidx로 구분 -->
            <div class="List_div"><a href="#" onclick='window.location.href="ordersums.php"'>주문통계</a></div>
            <!-- adminidx -> companyidx로 구분 -->
            <div class="List_div"><a href="#" onclick='window.location.href="customer_management.php"'>고객지원</a></div>
            <form action="#" id="maincont" name="maincont" method="post">
                    <?php
                    if($admin_id == "koreamhg")
                    {
                    ?>
                    <input type="hidden" name="admin_id" id="admin_id" value="<?=$admin_id?>">
                    <div class="List_div"><a href="#" style="font-size: 1.2rem;" onclick="regular_list()">정회원 목록</a></div>
                    <?php
                    }
                    ?>
            </form>

        <?php
        }
        ?>    
    </div>
    
    <div id="tap_wrapper" class="tap_wrapper"> 
        <div class="tap_name"><?php echo $admin_id;?>님</div>
    <div>
        <?php
        if($admin_c_idx) {
        ?>
            <a href="logout.php">로그아웃</a>
        <?} else {
            //로그인 하지 않은 경우
            header("Location: admin_login.php");
         exit();
        }
        ?>
        <p onclick='window.location.href="company_information.php"'>내 정보 관리</p>
    </div>
    </div>
  
    </section>
    <div class="header_line0"></div>
    <?php
// 현재 페이지 경로를 확인하여 주문관리 페이지일 때만 특정 div를 출력
$current_page = basename($_SERVER['PHP_SELF']);
if ($current_page == 'shopBy.php' || $current_page == 'shopByInfo.php' || $current_page == 'cancel_list_manage.php' || $current_page == 'exchange_list_manage.php' || $current_page == 'exchange_list_manage_modify.php'|| $current_page == 'return_list_manage.php'|| $current_page == 'return_list_manage_modify.php') {
    echo '<div class="header_sub">';
    echo '        <ul class="sub_text">';
    echo '            <li class="sub_0"><a href="shopBy.php">주문</a></li>';
    echo '            <li class="sub_0"><a href="cancel_list_manage.php">취소</a></li>';
    echo '            <li class="sub_0"><a href="exchange_list_manage.php">교환</a></li>';
    echo '            <li class="sub_0"><a href="return_list_manage.php">반품</a></li>';
    echo '        </ul>';
    echo '</div>';
}
?> 



    <div class="arrow_wrap">
        <i class="bi bi-arrow-up-circle" onclick="goTop()"></i>
        <i class="bi bi-arrow-down-circle" onclick="goDown()"></i>
    </div>

<script>
    function goTop() {
        window.scrollTo({
            top:0,
            behavior:"smooth"
        });
    }

    function regular_list()
    {
        var admin_id = document.getElementById("admin_id").value;
        document.getElementById("maincont").action="https://cms.mhgkorea.com/CMS_TABLE.php";
        document.getElementById("maincont").submit();
    }
    
    function goDown() {
        window.scrollTo({
            top:document.querySelector("html").scrollHeight,
            behavior:"smooth"
        });
    }
</script>