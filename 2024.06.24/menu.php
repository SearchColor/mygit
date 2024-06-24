<?php
  session_start();
  unset($_SESSION['lv1idx']);
  unset($_SESSION['lv2idx']);
  unset($_SESSION['lv3idx']);
  unset($_SESSION['optidx']);
  unset($_SESSION['quantity']);
  @$useridx = $_SESSION['useridx'];

  include "dbconn.php";
  // include "session_useridx.php";

  @$menuidx = $_REQUEST['data'];
  //  @$menuidx = "373";
  @$REQUESTidx= $_REQUEST['dataa'];

  if(isset($REQUESTidx)){
      @$useridx = $REQUESTidx;
      $heartselect ="SELECT status from zzim Where useridx=$useridx && menuidx = $menuidx";
      $selectresult = mysqli_query($conn,$heartselect);
      $row = mysqli_fetch_array($selectresult);
      if(@$row['status'] == 0){
        $idate = date("Y-m-d");
        $heartquery = "INSERT into zzim(useridx,status,menuidx,idate) VALUES('$useridx','1','$menuidx','$idate')";
        $heartresult = mysqli_query($conn,$heartquery);
        }
      if(@$row['status' == 1]){
          $heartdelete = "DELETE from zzim Where useridx=$useridx && menuidx = $menuidx";
          $heartresult2 = mysqli_query($conn,$heartdelete);
      }
    }

      $query = "SELECT * FROM menu WHERE menuidx='$menuidx'";
      $result = mysqli_query($conn, $query);
      $row = mysqli_fetch_array($result);
      $stock = $row['stock'];
      $delivery_notice = $row['delivery_notice'];
      $category = $row['category'];
      $sale_status = $row['sale_status'];
      if($sale_status == null){
        $sale_status = 0;
      }

$url = $row['videocontent'];
$url2 = $row['videocontent2'];

// YouTube 링크인지 확인
if (strpos($url, 'youtube.com') !== false) {
    // YouTube 동영상 ID를 URL에서 추출
    @$parsedUrl = parse_url($url);
    @parse_str($parsedUrl['query'], $query9);
    // YouTube 임베드 링크 생성
    @$embedUrl = "https://www.youtube.com/embed/" . $query9['v'];
} else {
    // YouTube 링크가 아니라면, 직접 MP4 파일 링크로 가정
    $embedUrl = $url;
}

if (strpos($url2, 'youtube.com') !== false) {
  // YouTube 동영상 ID를 URL에서 추출
  @$parsedUrl2 = parse_url($url2);
  @parse_str($parsedUrl2['query'], $query9);
  // YouTube 임베드 링크 생성
  @$embedUrl2 = "https://www.youtube.com/embed/" . $query9['v'];
} else {
  // YouTube 링크가 아니라면, 직접 MP4 파일 링크로 가정
  $embedUrl2 = $url2;
}

  if($useridx){
    $heartselect2 ="SELECT status from zzim Where useridx=$useridx && menuidx = $menuidx";
    $selectresult2 = mysqli_query($conn,$heartselect2);
    $statusrow = mysqli_fetch_array($selectresult2);
    }else{
      echo "";
    }
  ?>

<?
$query9 = "SELECT member.*, ask.*,ask.idate As i FROM ask JOIN member ON ask.useridx = member.useridx WHERE menuidx='$menuidx'";
  $result9 = mysqli_query($conn, $query9);
  @$data = $_REQUEST['query9'];
  $asktitle ="상품 문의";
  $totalQuestions = mysqli_num_rows($result9);
  ?>
  <?php
  
      $query1 = "SELECT member.*, review.* FROM review JOIN member ON review.useridx = member.useridx WHERE menuidx='$menuidx'&&review_status = '1'";
      $result1 = mysqli_query($conn, $query1);
      @$data = $_REQUEST['query1'];
      $iphone = 0;
      $imagetitle ="상세이미지";
      $productstitle = "필수 표기정보";


      
?>
     <?
$query3 = "SELECT admin_member.*, menu.* FROM admin_member JOIN menu ON admin_member.admin_member_idx = menu.company_idx where menuidx='$menuidx'";
$result3 = mysqli_query($conn, $query3);
$row3 = mysqli_fetch_array($result3);
?>
<?php
$query4 = "SELECT menuoption.*, review.* FROM review JOIN menuoption ON review.option_idx = menuoption.option_idx WHERE menuidx='$menuidx'";
$result4 = mysqli_query($conn, $query4);
$row8 = mysqli_fetch_array($result4);


?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    

    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=3.0, user-scalable=yes" />
    <title>페이지 제목</title>
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link rel="stylesheet" href="<?=$hostaddress?>/css/menu.css" />
  <style>
            
  </style>
  <script>
    <?
    $iphone = 0;
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


function printdlg(msg,url){
        
        viewinfo("printdlg</>"+msg+"</>"+url);
}

   
  </script>
  </head>
  <body> 
  <?php
    $bagcountquery = "SELECT count(useridx) AS count FROM bag WHERE useridx = '$useridx'";
    $bagcountresult = $conn->query($bagcountquery);
    $bagrow = $bagcountresult->fetch_assoc();
    $bagcount = $bagrow['count'];
    ?> 
  <div class="out-div">
           <div class="swiper mySwiper">
           <div class="swiper-wrapper" onclick="newurldo1('<?= $menuidx ?>', '<?= $imagetitle ?>', '<?= $useridx ?>')">
          <?php if (!empty($row['imagepath1'])): ?>
          <div class="swiper-slide"><img src="<?= $row['imagepath1']; ?>" ></div>
          <?php endif; ?>
          <?php if (!empty($row['imagepath2'])): ?>
          <div class="swiper-slide"><img src="<?= $row['imagepath2']; ?>"></div>
          <?php endif; ?>
          <?php if (!empty($row['imagepath3'])): ?>
          <div class="swiper-slide"><img src="<?= $row['imagepath3']; ?>"></div>
          <?php endif; ?>
          <?php if (!empty($row['imagepath4'])): ?>
          <div class="swiper-slide"><img src="<?= $row['imagepath4']; ?>"></div>
          <?php endif; ?>
          <?php if (!empty($row['imagepath5'])): ?>
          <div class="swiper-slide"><img src="<?= $row['imagepath5']; ?>"></div>
          <?php endif; ?>
          <?php if (!empty($row['imagepath6'])): ?>
          <div class="swiper-slide"><img src="<?= $row['imagepath6']; ?>"></div>
          <?php endif; ?>
          <?php if (!empty($row['imagepath7'])): ?>
          <div class="swiper-slide"><img src="<?= $row['imagepath7']; ?>"></div>
          <?php endif; ?>
          <?php if (!empty($row['imagepath8'])): ?>
          <div class="swiper-slide"><img src="<?= $row['imagepath8']; ?>"></div>
          <?php endif; ?>
          <?php if (!empty($row['imagepath10'])): ?>
          <div class="swiper-slide"><img src="<?= $row['imagepath9']; ?>"></div>
          <?php endif; ?>
          <?php if (!empty($row['imagepath11'])): ?>
          <div class="swiper-slide"><img src="<?= $row['imagepath10']; ?>"></div>
          <?php endif; ?>
      </div>

      <div class="swiper-pagination"></div>
  </div>
    
    <div class="totalrr">
    <?php
    $_SESSION['idx'] = 1;

    if (empty($data)) {
        ?>
        <?php
        $rowcount = mysqli_num_rows($result1);
        $totalStars = 0;
        $totalReviews = 0;
        $reviewtitle = "리뷰 보기";
        


        if ($rowcount > 0) {
            mysqli_data_seek($result1, 0); // 결과 집합을 다시 첫 번째 행으로 이동

            for ($a = 0; $a < $rowcount; $a++) {
                $row1 = mysqli_fetch_array($result1);
                $starscore = $row1['starscore'];
                $totalStars += $starscore;
                $totalReviews++;
            }

            $averageRating = ($totalReviews > 0) ? round($totalStars / $totalReviews, 1) : 0;
            ?><span class='totalreview'>
              <?php
													if ($totalReviews > 99) {
														// 리뷰 수가 99개를 넘어가면 숫자 옆에 + 표시 추가
														echo '(99+)';
													} else {
														echo '(' . $totalReviews . ')';
													}
													?>              
               </span>
        <?php
        
              ?>
              <a onclick="newurldo('<?= $menuidx?>','<?= $reviewtitle ?>','<?=$useridx?>')">
              <?
              for ($i = 1; $i <= 5; $i++) {
                  if ($i <= $averageRating) {
                      echo '<img src="'.$hostaddress.'/image/1.png" style="width: 15%; height: 15%;" alt="Gold Star">';
                  } else {
                      echo '<img src="'.$hostaddress.'/image/2.png" style="width: 15%; height: 15%;" alt="Empty Star">';
                  }
              }
              ?>
              </a>
              <?
              ?>
             
              <?php
              }
              ?>
          
          <?php
          }
          ?>
            </div>     
            <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
  
    </script>
     <button class="quickmenu">🔺<br>맨위로</button>
      
      
      <script>
          document.addEventListener("DOMContentLoaded", function() {
            var quickmenu = document.querySelector(".quickmenu");

            window.addEventListener("scroll", function() {
              var position = window.scrollY;

              // Toggle the 'visible' class based on scroll position
              if (position > 200) { // Adjust the scroll position threshold as needed
                quickmenu.classList.add('visible');
              } else {
                quickmenu.classList.remove('visible');
              }
            });

            quickmenu.addEventListener("click", function() {
              // Scroll to the top when the button is clicked
              window.scrollTo({ top: 0, behavior: 'smooth' });
            });
            });

              var swiper = new Swiper(".mySwiper", {
              pagination: {
                el: ".swiper-pagination",
                      },
                    });
              
                  document.addEventListener('DOMContentLoaded', function() {
                  // Display the 'productInfo' section
                  toggleContent('productButton', 'productInfo');
                  });
              function toggleContent(buttonId, contentId) {

              if (document.getElementById(contentId).style.display === 'block') {
                //버튼눌렀을때 document.getElementById(contentId).style.display = 'none';

                // 선택된 버튼의 스타일을 초기화합니다.
                var selectedButton = document.getElementById(buttonId);
                selectedButton.classList.remove('active-button');
                selectedButton.style.backgroundColor = ''; // 기본값으로 변경

                //버튼 리턴하기return;
              }


              // 모든 콘텐츠 섹션을 숨깁니다.
              document.getElementById('productInfo').style.display = 'none';
              document.getElementById('reviews').style.display = 'none';
              document.getElementById('questionContent').style.display = 'none';

              // 모든 버튼의 스타일을 초기화합니다.
              var allButtons = document.querySelectorAll('.toggle-button');
              allButtons.forEach(function (btn) {
                btn.classList.remove('active-button');
                btn.style.backgroundColor = ''; // 기본값으로 변경
              });

              // 선택한 콘텐츠 섹션을 표시합니다.
              document.getElementById(contentId).style.display = 'block';

              // 선택한 버튼을 강조 표시하고 배경색을 변경합니다.
              var selectedButton = document.getElementById(buttonId);
              selectedButton.classList.add('active-button');
              selectedButton.style.backgroundColor = '#F26052';
              }


              window.addEventListener('load', function () {
                          let contentHeight = document.querySelector('.detailinfo > .content').offsetHeight;
                          if (contentHeight <= 600) { /* Updated condition */
                              document.querySelector('.detailinfo').classList.remove('showstep1');
                              document.querySelector('.btn_open').classList.add('hide');
                          }
                      });

                      document.addEventListener('DOMContentLoaded', function () {
                          document.querySelector('.btn_open').addEventListener('click', function (e) {
                              let classList = document.querySelector('.detailinfo').classList;
                              let contentHeight = document.querySelector('.detailinfo > .content').offsetHeight;

                              if (classList.contains('showstep1')) {
                                  classList.remove('showstep1');
                                  document.querySelector('.btn_open').classList.add('hide');
                              }

                              if (!classList.contains('showstep1')) {
                                  e.target.classList.add('hide');
                                  document.querySelector('.btn_close').classList.remove('hide');
                              }
                          });

                          document.querySelector('.btn_close').addEventListener('click', function (e) {
                              e.target.classList.add('hide');
                              document.querySelector('.btn_open').classList.remove('hide');
                              document.querySelector('.detailinfo').classList.add('showstep1');
                          });
                      });
                      
                      function toggleExchangeInfo() {
                        // exchangeInfo 요소를 가져옴
                        var exchangeInfo = document.getElementById('exchangeInfo');
                        
                        // 현재 display 속성 확인
                        var currentDisplay = exchangeInfo.style.display;
                        
                        // display 속성 토글
                        exchangeInfo.style.display = currentDisplay === 'none' ? 'block' : 'none';

                        // 버튼이 클릭되었을 때 배경색과 폰트 색 변경
                        if (exchangeInfo.style.display === 'block') {
                            // 보이는 경우: 배경색을 #F26052로, 폰트 색을 흰색으로 설정
                            document.getElementById('toggleExchange').style.backgroundColor = '#F26052';
                            document.getElementById('toggleExchange').style.color = 'white';
                        } else {
                            // 숨겨진 경우: 기본 스타일로 설정 (원하는 기본 배경색과 폰트 색으로 변경)
                            document.getElementById('toggleExchange').style.backgroundColor = ''; // 기본 배경색 설정
                            document.getElementById('toggleExchange').style.color = ''; // 기본 폰트 색 설정
                        }
                    }
      </script>
</div>
<div>
  <h1 class="a"><?= $row['mname']; ?></h1><hr class="i">
  <div class="stockas">
  <?php
if ($stock > 0) {
    echo "재고가 {$stock}개 남았습니다.";
} elseif ($stock == 0) {
    echo "상품 준비중입니다.";
}
?></div>
<?php


    $query2 = "SELECT menu_code, mname, price,price2, c_status, c_rate,delivery_option, delivery_condition, delivery_price,nap_price,MDpay,card_carge FROM menu WHERE menuidx = '$menuidx'";
    $result2 = mysqli_query($conn, $query2);
    $row11 = mysqli_fetch_row($result2);

    $menu_code = $row11[0];
    $name = $row11[1];
    $price = $row11[2];
    $price2 = $row11[3];
    $c_status = $row11[4];
    $c_rate = $row11[5];
    $delivery_option = $row11[6];
    $delivery_condition = $row11[7];
    $delivery_price = $row11[8];
    $nap_price = $row11[9];
    $MDpay = $row11[10];
    $card_carge = $row11[11];

    $aaabbb = $price; // 할인된 가격 초기화

    $nap_price2 = $nap_price+$MDpay+$price*($card_carge/100);

    // j_coupon이 적용되어 있다면
    if ($c_status == '1') {
        $c_discount = ($price-$nap_price2) * $c_rate / 100;
        $aaabbb -= $c_discount; // j_rate에 따라 할인
        $discounted_price = round($aaabbb , -1);
    }
    // c_coupon이 적용되어 있다면
  
    // 할인된 가격과 할인율 출력
    echo "<div>";
    
    // $j_coupon 및 $c_coupon이 0이 아니면 할인된 가격을 표시
    if ($c_status) {
      echo "<div style='font-size: 16px;'><del style='color: gray; text-decoration: line-through;'>" . number_format($price2) . " 원</del><span style='font-size: 0.5em; display: inline; margin-left: 10px; vertical-align: 3px;'>정가</span></div>";
      echo "<div style='color: black;'>" . number_format($price) . " 원<span style='font-size: 0.5em; display: inline; margin-left: 10px; vertical-align: 3px;'>판매가</span></div>";
      echo "<span style='font-size: 0.6em; color: red;'>최대코인할인적용가</span>";
      echo "<div style='font-size: 16px; color: red;'>" . number_format($discounted_price) . " 원</div>";
     } else { // $j_coupon 및 $c_coupon이 0이면 원래 가격만 표시
      echo "<div style='font-size: 16px;'><del style='color: gray; text-decoration: line-through;'>" . number_format($price2) . " 원</del><span style='font-size: 0.5em; display: inline; margin-left: 10px; vertical-align: 3px;'>정가</span></div>";
      echo "<div style='color: black;'>" . number_format($price) . " 원<span style='font-size: 0.5em; display: inline; margin-left: 10px; vertical-align: 3px;'>판매가</span></div>";
      echo "<span style='font-size: 0.6em; color: red;'>코인할인미적용상품</span>";
     }
    echo "</div>";



// 카테고리가 4번인 경우 무료배송
if ($category == 4) {
  echo '<span style="font-size: 12px;">(무료배송)</span>';
}else if($menuidx == "241" ){
  echo '<span style="font-size: 12px;">업체배송 문의</span>';
}else if($category == "206"){
  echo '<span style="font-size: 12px;">업체배송 문의</span>';
}else if($category == "10"){
  echo '<span style="font-size: 12px;">착불배송</span>';
} else {
  // 배송비 설정
if ($delivery_option == 0) {
  if($delivery_price == 0){
    echo '<span style="font-size: 12px;">(무료배송)</span>';
  }else{
    echo '<span style="font-size: 12px;">배송비: ' . number_format($delivery_price) . '원</span>';
  }
} else if ($delivery_option == 1) {
  if($delivery_price == 0)
  {
    echo '<span style="font-size: 12px;">(무료배송)</span>';
  }
  else{
    if($price >= $delivery_condition)
    {
      echo '<span style="font-size: 12px;">(무료배송)</span>';
      echo '<span style="font-size: 12px;">배송비: ' . number_format($delivery_price) . '원</span><br>';
      echo '<span style="font-size: 12px;">' . number_format($delivery_condition) . '원 이상 주문시 무료배송</span>';
    }
    else{
    echo '<span style="font-size: 12px;">배송비: ' . number_format($delivery_price) . '원</span><br>';
    echo '<span style="font-size: 12px;">' . number_format($delivery_condition) . '원 이상 주문시 무료배송</span>';
    }
  }
}
}

?>
  <hr class="i">
  <!-- "지금 주문시" 문구 -->
  <img src="<?=$hostaddress?>/image/bonggo.jpg" style="width:6%; height: auto; float: left; margin-right: 10px;">
  <div class="nam" id="deliveryInfo"> 
  
  </div>
    <!-- 도착 예정일과 가격을 함께 표시하는 부분 -->
    <!-- JavaScript 부분 -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    var currentDate = new Date();
    var deliveryDate = new Date();
    var deliveryInfoElement = document.getElementById('deliveryInfo');
    var formattedDate, dayOfWeek, daysOfWeekText, dayText;

    var daysToAdd = 2;

    if (currentDate.getHours() < 18) {
        deliveryDate.setDate(currentDate.getDate() + daysToAdd);
    } else {
        daysToAdd++;
        deliveryDate.setDate(currentDate.getDate() + daysToAdd);
    }

    if (deliveryDate.getDay() === 0) {
        deliveryDate.setDate(deliveryDate.getDate() + 1);
    }

    var deliveryDay = deliveryDate.getDay();
    var deliveryDateString = deliveryDate.toISOString().split('T')[0];
    var holidayDates = ["2022-03-14", "2022-03-15"];

    if (deliveryDay === 0 || deliveryDay === 6 || holidayDates.includes(deliveryDateString)) {
        deliveryDate.setDate(deliveryDate.getDate() + 1);
    }

    formattedDate = (deliveryDate.getMonth() + 1) + '/' + deliveryDate.getDate();
    dayOfWeek = deliveryDate.getDay();
    daysOfWeekText = ["일", "월", "화", "수", "목", "금", "토"];
    dayText = daysOfWeekText[dayOfWeek];

    var delivery_notice = <?php echo $delivery_notice; ?>;

    if (delivery_notice === 1) {
        deliveryInfoElement.innerText = '현장수령';
    } else if (delivery_notice === 2) {
        deliveryInfoElement.innerText = '추후공지';
    } else if (delivery_notice === 3){
        deliveryInfoElement.innerText = '업체배송 문의';
    } else if (delivery_notice === 4){
        deliveryInfoElement.innerText = '착불배송';
    } else {
        deliveryInfoElement.innerText = '지금 주문시 (' + dayText + ') ' + formattedDate + ' 도착 예정';
    }

    deliveryInfoElement.style.fontSize = '0.9em';
});
</script>
</div>
      <hr class="ii">
      <?php if (!empty($row3['admin_name'])): ?>
        <img src="<?=$hostaddress?>/image/admin.jpg" style="width:6%; height: auto; float: left; margin-right: 10px;" alt="Admin Image">
      <div class="adminname">
     
      판매자: <?= $row3['admin_name']; ?>
      </div><?php endif; ?>
      <!-- <hr class="ii">

      옵션 선택
      <?
      
@$menuidx = $_REQUEST['data'];
$_SESSION['quantity'] = '1';

$countQuery = "select count(*) from menuoption where menu_idx = '$menuidx'";
$countResult = mysqli_query($conn, $countQuery);
$countRow = mysqli_fetch_array($countResult);
$count = $countRow[0];

$query = "select mname, price from menu where menuidx = '$menuidx'";
$result = mysqli_query($conn, $query);
$row6 = mysqli_fetch_row($result);

$name = $row6[0];
$price = $row6[1];

$_SESSION['optname'] = $name;


$optQuery = "select option_idx,option_name, menu_price from menuoption where menu_idx = '$menuidx'";
$optResult = mysqli_query($conn, $optQuery);

$options = [];
while($row6 = mysqli_fetch_assoc($optResult)) {
    $options[] = $row6;

}


if (count($options) == 0) {
    ?>
    <script>document.addEventListener('DOMContentLoaded', function() { document.getElementById('option').disabled = true; 
    document.getElementById('opt_id').innerText = '옵션없음';
    });</script>
<?php
}

?>



<select class="select_opt" name="option" id="option" onchange="valChange(this.value)" >
        <option class="opt_class" id="opt_id" value="<?=$price?>" data-option-idx='0' selected><?=$name?></option>
        <?php foreach ($options as $opt): ?>
        <option class="opt_class" value="<?=$opt['menu_price']?>" data-option-idx="<?=$opt['option_idx']?>"><?=$opt['option_name']?></option>
        <?php endforeach; ?>
    </select> -->
      <hr class="iiii">
      <div class="goods_div3">
      <div class="goods_explain">
      <li id="product"><input type="button" id="productButton" class="toggle-button" value="상품정보" onclick="toggleContent('productButton', 'productInfo')"></li>
      <li id="review"><input type="button" id="reviewButton"  class="toggle-button" value="구매후기 (<?=$totalReviews?>)" onclick="toggleContent('reviewButton', 'reviews')"></li>
      <li id="question"><input type="button" id="questionButton" class="toggle-button" value="Q&A (<?=$totalQuestions?>)" onclick="toggleContent('questionButton', 'questionContent')"></li>
    
      </div>
      </div>
      <div class="goods_div4">
      <div class="detailinfo showstep1">
      <div class="content" id="productInfo" >     
      <br>
      <div>
      <table>

       
        <tr>
        <td>상품번호</td>
        <td><?php echo $row['menu_code']; ?></td>
        </tr>
        <tr>
        <td>제품 품질 보증 기준</td>
        <td><?php echo $row['products_warranty']; ?></td>
        
        </tr><tr>
          <td>원산지</td>
          <td><?php echo $row['products_origin']; ?></td>
        </tr>
      </table>
       
        </div>
        <button class="productbtn" onclick="newurldo3('<?= $menuidx ?>', '<?= $productstitle ?>', '<?= $useridx ?>')">필수표기정보 보기</button> 
         
        <?
      if (!empty($row['videocontent'])) {
        ?>            
        <iframe width="100%" height="315" src="<?= $embedUrl ?>" frameborder="0" allowfullscreen></iframe>
        <?
    } else {
        ?>
        <div class="content" id="productInfo">
        <?
    }
    ?>    
    
    <hr class="i">
        <?
      if (!empty($row['videocontent2'])) {
        ?>            
        <iframe width="100%" height="315" src="<?= $embedUrl2 ?>" frameborder="0" allowfullscreen></iframe>
        <?
    } else {
        ?>
        <div class="content" id="productInfo">
        <?
    }
    ?>    
                        <?php if (!empty($row['detail_image1'])): ?>
                        <img src="<?= $row['detail_image1']; ?>" width='100%' alt="Product Image 3">
                        <button class="btn_open">상품정보 더보기</button><div>
                        <button class="btn_close hide">상품정보 접기</button></div><?php endif; ?>
      </div>
      </div>
      </div>  
      <div>
   
      
      <br>
                          <div id="reviews" class="tab-content" style="display: none;" >
                          <?php
    $query0 = "SELECT member.*, review.* FROM review JOIN member ON review.useridx = member.useridx WHERE menuidx = $menuidx && review_status = 1";
    $result0 = mysqli_query($conn, $query0);
    $rowcount1 = mysqli_num_rows($result0);
    
    ?>
                          <?php
$query4 = "SELECT * FROM review WHERE review_status = 1 && menuidx = $menuidx ORDER BY idate DESC";
$result4 = mysqli_query($conn, $query4);

$reviewsToShow = 3; // Set the number of reviews to display

if ($result4) {
    $reviewCounter = 0; // Initialize a counter for the reviews
    while ($row4 = mysqli_fetch_array($result4)) {
        $menuidx1 = $row4['menuidx'];
        $optionname = '';

        if ($row4['option_idx'] > 0) {
            $optionidx = $row4['option_idx'];
            $query6 = "SELECT * FROM menuoption WHERE option_idx = $optionidx";
            $result6 = mysqli_query($conn, $query6);
            $row6 = mysqli_fetch_assoc($result6);
            $optionname = $row6['option_name'];
        }

        $query5 = "SELECT * FROM menu WHERE menuidx = $menuidx1";
        $result5 = mysqli_query($conn, $query5);
        $row5 = mysqli_fetch_assoc($result5);

        if ($row4) {
            $_SESSION['idx'] = 1;
            if (empty($data) && $rowcount1 > 0) {
              $row0 = mysqli_fetch_array($result0);

                $aaa = $row0['idate'];
                $idate = substr($aaa, 0, 10);

                $starscore = $row0['starscore'];
                $name = $row0['name'];

                $hidden_username = $name;

                if (mb_strlen($name, 'UTF-8') > 1) {
                    $length = mb_strlen($name, 'UTF-8');
                    $middle = floor($length / 2);
                    $hidden_username = mb_substr($name, 0, $middle) . str_repeat('*', 1) . mb_substr($name, $middle + 1);
                }

                $totalStars += $starscore;
                $totalReviews++;
                ?>
                <div class="review-container">
                    <?php
                    $privateStatus = $row0['private_status'];
                    if ($privateStatus == 1) {
                        ?>
                        <div class="top">
                            <span style="font-style: italic; color: #888; margin-top: 30px; font-size: 13px;">
                                관리자에 의해 블라인드 처리된 리뷰입니다.
                            </span>
                        </div>
                        <div class="right-aligned" >
                            <hr style="border: 0.5px solid #E2E2E2;">
                        </div>
                        <?php
                    } else {
                        ?>
                        <div class="top">
                            <span class="rdn"><?= $hidden_username ?></span>
                        </div>
                        <div class="topa">
                            <div class="tops">
                                <?php
                                for ($i = 1; $i <= 5; $i++) {
                                    $starImage = ($i <= $starscore) ? '1.png' : '2.png';
                                    echo '<img src="' . $hostaddress . '/image/' . $starImage . '" style="width: 25px; height: 25px; margin-left: 0px;" alt="Star">';
                                }
                                ?>
                            </div>
                            <span class="nrd"><?= $idate; ?></span>
                        </div><br>
                        <span class="ndr"><?= $row5['mname'] . (!empty($row6['option_name']) ? '(' . $row6['option_name'] . ')' : ''); ?></span><br>

                        <?php if (!empty($row0['imagepath'])) : ?>
                          
                            <img class="z" src="<?= $row0['imagepath']; ?>"onclick="newurldo('<?= $menuidx ?>','<?= $reviewtitle ?>','<?= $useridx ?>')" >
                        <?php endif; ?>
                        <p><?= $row0['content']; ?><br><br></p>
                        <div class="right-aligned">
                            <hr style="border: 0.5px solid #E2E2E2;">
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <?php
                $averageRating = ($totalReviews > 0) ? round($totalStars / $totalReviews, 1) : 0;

                $reviewCounter++; 

                if ($reviewCounter >= $reviewsToShow) {
                    break; 
                }
            } else {
                echo "구매후기가 없습니다.";
            }
        }
    }
}


?>
          <button class="reviewbtn" onclick="newurldo('<?= $menuidx ?>','<?= $reviewtitle ?>','<?= $useridx ?>')">리뷰 전체보기</button>         
          </div>
          </div>
        </div>




              <div id="questionContent" class="tab-content" style="display: none;">
                <?php
                $_SESSION['idx'] = 1;

                if (empty($data)) {
                
                  ?>
                  
                  <br>   

                <div class="qaad">

                상품문의 게시판입니다. 문의하신 내용에 대해서 판매자가 확인 후 답변을 드립니다.

                </div>
                <br>


                <?
                if($useridx == 0){
                ?>
                <button class="QAbtn" onclick="printdlg('로그인후 이용가능한 서비스입니다','myshop_login.php')">상품 Q&A 작성하기</button>
                <?
                }else{    
                ?>
                <button class="QAbtn" onclick="newurldo2('<?= $menuidx ?>','<?= $asktitle ?>','<?= $useridx ?>')">상품 Q&A 작성하기</button>
                <?
                }
                ?>




                          <?php
                          function getResultString($askRow) {
                              $resultString = "";

                              $resultString .= "<div class='question-title'>" . $askRow['asktitle'] . "</div>";
                              $resultString .= "<div class='question-content'>Q: " . $askRow['askcontent'] . "</div>";

                              if ($askRow['admincontent'] == '0') {
                                  $resultString .= "<div class='no-answer'>등록된 답변이 없습니다.</div>";
                              } else {
                                  $resultString .= "<div class='answer'>A: " . $askRow['admincontent'] . "</div>";
                              }

                              $resultString .= "<hr>";

                              return $resultString;
                          }

                          $finalResultString = "";

                          if ($row2 = mysqli_fetch_array($result9)) {
                              $bbb = $row2['i'];
                              $idate = substr($bbb, 0, 10);

                              $finalResultString .= getResultString($row2);

                              while ($askRow = mysqli_fetch_array($result9)) {
                                  $finalResultString .= getResultString($askRow);
                              }

                              echo $finalResultString;
                          } else {
                              echo '<div class="qaad">문의 내용이 없습니다.</div>';
                          }
                          ?>
                          <?
                          }
                          ?>                                        

                                              <input type="button" class="QAbtn2" id="toggleExchange" value="교환/반품안내" onclick="toggleExchangeInfo()">
                                              
                                              <div id="exchangeInfo" style="display: none;">
                                              <table>
                                                <!-- <caption class="section-title">교환/반품 안내</caption> -->
                                                <tbody>
                                                  <tr>
                                                    <td colspan="2">ㆍ 교환/반품에 관한 일반적인 사항은 판매자가 제시사항보다 관계법령이 우선합니다. 다만, 판매자의 제시사항이 관계법령보다 소비자에게 유리한 경우에는 판매자 제시사항이 적용됩니다.</td>
                                                  </tr>
                                                  <tr>
                                                    <td class="p">교환/반품 비용</td>
                                                    <td>ㆍMYSHOP멤버십 회원: 무료로 반품/교환 가능</td>
                                                  </tr>
                                                  <tr>
                                                    <td class="p">교환/반품 신청기준일</td>
                                                    <td>· 단순변심에 의한 교환/반품은 제품 수령 후 7일 이내까지, 교환/반품 제한사항에 해당하지 않는 경우에만 가능 (배송비용과 교환/반품 비용 왕복배송비 고객부담)<br>
                                                      · 상품의 내용이 표시·광고의 내용과 다른 경우에는 상품을 수령한 날부터 3개월 이내, 그 사실을 안 날 또는 알 수 있었던 날부터 30일 이내에 청약철회 가능</td>
                                                  </tr>
                                                </tbody>
                                              </table>

                                              <table><br><tr>
                                                <caption class="section-title">교환/반품 제한사항</caption>
                                                <tbody>
                                                <td colspan="2"> · 주문/제작 상품의 경우, 상품의 제작이 이미 진행된 경우
                                                    · 상품 포장을 개봉하여 사용 또는 설치 완료되어 상품의 가치가 훼손된 경우 (단, 내용 확인을 위한 포장 개봉의 경우는 예외)<br>
                                                    · 고객의 사용, 시간경과, 일부 소비에 의하여 상품의 가치가 현저히 감소한 경우<br>
                                                    · 세트상품 일부 사용, 구성품을 분실하였거나 취급 부주의로 인한 파손/고장/오염으로 재판매 불가한 경우<br>
                                                    · 모니터 해상도의 차이로 인해 색상이나 이미지가 실제와 달라, 고객이 단순 변심으로 교환/반품을 무료로 요청하는 경우<br>
                                                    · 제조사의 사정 (신모델 출시 등) 및 부품 가격 변동 등에 의해 무료 교환/반품으로 요청하는 경우<br><br>
                                                    <p class="limitation-text">※ 각 상품별로 아래와 같은 사유로 취소/반품이 제한될 수 있습니다.</p></td></tr>
                                                    <tr>
                                                    <td class="p">의류/잡화/수입명품</td>
                                                    <td>· 상품의 택(TAG) 제거, 라벨 및 상품 훼손, 구성품 누락으로 상품의 가치가 현저히 감소된 경우</td>
                                                  </tr>
                                                  <tr>
                                                    <td class="p">계절상품/식품/화장품</td>
                                                    <td>· 신선/냉장/냉동 식품의 단순변심의 경우<br>
                                                      · 뷰티 상품 이용 시 트러블(알러지, 붉은 반점, 가려움, 따가움)이 발생하는 경우, 진료확인서 및 소견서 등을 증빙하면 환불이 가능 (제반비용 고객부담)
                                                    </td>
                                                  </tr>
                                                  <tr>
                                                    <td class="p">전자/가전/설치상품</td>
                                                    <td>· 설치 또는 사용하여 재판매가 어려운 경우, 액정이 있는 상품의 전원을 켠 경우<br>
                                                      · 상품의 시리얼 넘버 유출로 내장된 소프트웨어의 가치가 감소한 경우 (내비게이션, OS시리얼이 적힌 PMP)<br>
                                                      · 홀로그램 등을 분리, 분실, 훼손하여 상품의 가치가 현저히 감소하여 재판매가 불가할 경우 (노트북, 데스크탑 PC 등)
                                                    </td>
                                                  </tr>
                                                  <tr>
                                                    <td class="p">자동차용품</td>
                                                    <td>· 상품을 개봉하여 장착한 이후 단순변심인 경우</td>
                                                  </tr>
                                                  <tr>
                                                    <td class="p">CD/ DVD / GAME/ BOOK</td>
                                                    <td>· 복제가 가능한 상품의 포장 등을 훼손한 경우</td>
                                                  </tr>
                                                </tbody>
                                              </table>

                                              <table><br><br>
                                                <caption class="section-title">판매자 정보</caption>
                                                <tbody>
                                                  <tr>
                                                    <td class="p">상호/대표자</td>
                                                    <td>주식회사 MHG Korea / 강기효</td>
                                                  </tr>
                                                  <tr>
                                                    <td class="p">연락처</td>
                                                    <td>02-6326-9192</td>
                                                  </tr>
                                                  <tr>
                                                    <td class="p">E-mail</td>
                                                    <td>happymaker2017@gmail.com</td>
                                                  </tr>
                                                  <tr>
                                                    <td class="p">사업장소재지</td>
                                                    <td>서울시 용산구 원효로 255(원효로1가) 3층</td>
                                                  </tr>
                                                  <tr>
                                                    <td class="p">통신판매 신고번호</td>
                                                    <td>2022-서울마포-1173</td>
                                                  </tr>
                                                  <tr>
                                                    <td class="p">사업자번호</td>
                                                    <td>695-88-01620</td>
                                                  </tr>
                                                
                                                </tbody>
                                              </table><br>
                                          <div class="disclaimer">
                                          미성년자가 체결한 계약은 법정대리인이 동의하지 않는 경우 본인 또는 법정대리인이 취소할 수 있습니다.
                                          MYSHOP은 통신판매중개자로서 통신판매의 당사자가 아니며, 광고, 상품 주문, 배송 및 환불의 의무와 책임은 각 판매자에 있습니다.<br>
                                          </div>
                                            </div>
                                            </div>

     
                      <form action="<?=$hostaddress?>/menu.php" name="maincont">

                      <input type="hidden" name="data" value="<?= $row['menuidx'] ?>">
                      <input type='hidden' name='datatext' value="<?=$hostaddress?>/review_page.php">
                      <input type="hidden" name="dataa" value=<?= $useridx ?>> 
                      <input type="hidden" name="heartresult" value="0">
                      <input type="hidden" name="stock" value="<?=$stock?>">
                      <input type="hidden" name="sale_status" value="<?=$sale_status?>">

                      <?
                      if (@$statusrow != Null) {
                      ?>
                                      <input type="hidden" name="zzim" value="<?= @$statusrow['status'] ?>"> 
                                          <?
                      } else {
                      "";
                      }
                      ?>
                      </form>
                      <script language="javascript">

                      // 라이브러리 함수
                      <?php
                      ?>
              

                      function newurldo1(menuidx, mname, useridx ) 
                      {
                          var url ="<?=$hostaddress?>/image_page.php";
                          loadwebview(url, menuidx, mname, useridx );
                          
                      }
                      function newurldo2(menuidx, mname, useridx ) 
                      { 
                          var url ="<?=$hostaddress?>/ask_page.php";
                          loadwebview(url, menuidx, mname, useridx );
                        }

                      function newurldo3(menuidx, mname, useridx ) 
                      {
                          var url ="<?=$hostaddress?>/products.php";
                          loadwebview(url, menuidx, mname, useridx );
                          
                      }

                      function loadwebview(url, menuidx, mname, useridx)
                      {
                          var msg = "loadwebview</>" + url + "</>" + menuidx + "</>" +mname+ "</>"+ useridx;
                          viewinfo(msg);
                      }

                      function newurldo(menuidx, mname, useridx ) 
                      {
                          var url = document.maincont.datatext.value;
                          loadwebview(url, menuidx, mname, useridx );
                          
                      }


                      function bagcount() {
                                  var bagcount = <?= $bagcount ?>;
                                  viewinfo('bagcount1</>' + bagcount);
                              }
                              bagcount()



                      function hearton(){
                          
                          let heart = document.maincont.heartresult.value = 1;
                          document.maincont.heartresult.value = heart;
                          document.maincont.submit();
                          viewinfo("seterror</>"+"찜이 완료되었습니다");
                      }
                      function heartoff(){
                          document.maincont.submit();
                          viewinfo("seterror</>"+"찜이 취소되었습니다");
                      }
                      function ratingToPercent() {
                        const score = + this.restaurant.averageScore * 20;
                        return score + 1.5;
                      }
                      document.addEventListener('DOMContentLoaded', function() {
                          zzim = document.maincont.zzim.value;
                          viewinfo('zzim</>'+zzim+'</>');
                      });
                      <?
                      if($stock == 0 || $sale_status == 4){
                      ?>
                      document.addEventListener('DOMContentLoaded', function() {
                          stock = document.maincont.stock.value;
                          sale_status = document.maincont.sale_status.value;
                          viewinfo("pumzul</>"+stock+"</>"+sale_status+"</>");
                      });
                      <?
                      }else{
                        "";
                      }
                      ?>

                      </script>

</body>
</html>