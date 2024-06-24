<?php
session_start();
include "dbconn.php";

   unset($_SESSION['lv1idx']);
   unset($_SESSION['lv2idx']);
   unset($_SESSION['lv3idx']);
   unset($_SESSION['optidx']);
   unset($_SESSION['quantity']);
if (empty($_SESSION['useridx'])) {
	$_SESSION['useridx'] = "0";
}
$useridx = $_SESSION['useridx'];
$_SESSION['connection_ip'] = $_SERVER['REMOTE_ADDR'];
$connection_ip = $_SERVER['REMOTE_ADDR'];

// 자동 로그인 처리
if (isset($_COOKIE['useridx']) && $useridx == 0) {
	$useridx = $_COOKIE['useridx'];
	$_SESSION['connection_time'] = time();

	// 데이터베이스에서 쿠키 정보를 사용하여 사용자를 조회
	$query = "SELECT id, useridx, name FROM member WHERE useridx='$useridx'";
	$result = mysqli_query($conn, $query);
	$row = mysqli_fetch_array($result);


	if ($row) {
		// 세션에 사용자 정보 저장
		$_SESSION['useridx'] = $row['useridx'];


		$idate = date("Y-m-d H:i:s");
		$session_query = "INSERT INTO session_log(useridx, connection_ip, idate) VALUES ('$useridx', '$connection_ip', '$idate')";
		$result = mysqli_query($conn, $session_query);
	}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>

<HEAD>
	<TITLE> 마이샵 </TITLE>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<link rel="stylesheet" type="text/css" href="css/myshop.css?after">
	<link href="https://webfontworld.github.io/goodchoice/Jalnan.css" rel="stylesheet">
</HEAD>


<?php
$data = isset($_GET['data']) ? $_GET['data'] : '';
@$search = $_GET['query'];
?>
<?php
$iphone = 0;
?>
<SCRIPT language="javascript">


	//여기는 라이브러리 함수
	<?
	if ($iphone == "0") {
	?>

		function viewinfo(data) {
			window.customermanager.viewinfo(data);
		}

		function testdata(data) {
			const searchContent = data;
			const xhr = new XMLHttpRequest();
			xhr.open('POST', 'searchInput.php', true);
			xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

			xhr.onreadystatechange = function() {
				if (xhr.readyState === XMLHttpRequest.DONE) {
					if (xhr.status === 200) {
						console.log(xhr.responseText);
						window.location.href = "<?= $hostaddress ?>/myshop.php?query=" + encodeURIComponent(searchContent);
					} else {
						console.error('Error:', xhr.statusText);
					}
				}
			};

			xhr.send('data=' + encodeURIComponent(searchContent));
		}
	<?
	} else {
	?>

		function viewinfo(sidx) {
			try {
				webkit.messageHandlers.callbackHandler.postMessage(sidx);
			} catch (err) {

				console.log('error');
			}
		}
	<?
	}
	?>

	function calljavascript(func) {
		var msg = "javascript</>" + func;
		viewinfo(msg);
	}

	function loadwebview(url, menuidx, mname, useridx) {
		var msg = "loadwebview</>" + url + "</>" + menuidx + "</>" + mname + "</>" + useridx;
		viewinfo(msg);
	}

	function onvlewclose() {
		viewinfo("viewclose</>0");
	}

	function newnotice(mname, useridx) {
		var url = "<?= $hostaddress ?>/appnotice.php";
		loadwebview(url, mname, useridx);

	}
	function aaa(idx,mname,useridx)
{
var url = document.maincont.aaa.value;
loadwebview(url,idx,mname,useridx);
}
function bbb(idx,mname,useridx)
{
var url = document.maincont.bbb.value;
loadwebview(url,idx,mname,useridx);
}
function ccc(idx,mname,useridx)
{
var url = document.maincont.ccc.value;
loadwebview(url,idx,mname,useridx);
}

	//여기는 호출 함수

	function newurldo(menuidx, mname, useridx) {
		viewinfo("latelygoods</>" + mname);
		var url = document.maincont.datatext.value;
		loadwebview(url, menuidx, mname, useridx);
	}


	function filterProducts(data) {
		window.location.href = '<?= $hostaddress ?>/myshop.php?data=' + data;

	}

	function gout(menuidx, mname, useridx) {
		var url = 'https://youtu.be/tvayZqa_zkk';
		loadwebview(url, menuidx, mname, useridx);
	}


</SCRIPT>

<body>
	<!-- 팝업 -->

	<!-- <div class="popup-overlay" id="popup" style="display: none;">

<div class="popup-content">
<span class="close-btn" onclick="closePopup()">X</span>
	<?php if (!empty($adimgae)){?>
		<img src="<?= htmlspecialchars($adimgae) ?>" alt="Ad" style="max-width: 100%; height: auto;">
		<?
	}else{
		?>
		<h2>안내 사항</h2>
		<p>올마이샵의</p><p>할인가 변경 및</p>
		 <p>일부 상품의 판매가 조정으로인하여</p><p>잠시 결제가 되지않습니다.</P>
		<?
	}
		?>
	<div>
		<input type="checkbox" id="dontShowAgain" onclick="closeset()"> 오늘 하루 보지 않기
	</div>
</div>
</div> -->







	<div id="slider">
		<div id="slider-container">
			<!-- 각 이미지에 대한 슬라이드 -->
			<!-- 추가 이미지 URL을 필요에 따라 계속해서 넣어주세요. -->
			<!-- <div class="slide" onclick="newurldo('199','이블라위너스 내한공연 콘서트 티켓','<?= $useridx ?>')" style="background-image: url('<?= $hostaddress ?>/image/ibla_app.jpeg');"></div> -->
			<!-- <div class="slide" onclick="newurldo('198','땅콩새싹 300세트 한정판매','<?= $useridx ?>')" style="background-image: url('<?= $hostaddress ?>/image/peanut-banner.png');"></div> -->
			<!-- <div class="slide" onclick="gout(0,0,0)" style="background-image: url('<?= $hostaddress ?>/image/myshop.jpg');"></div> -->
			<!-- <div class="slide" style="background-image: url('<?= $hostaddress ?>/image/main_brn_1_m.jpg');"></div> -->
			<!-- <div class="slide" style="background-image: url('<?= $hostaddress ?>/image/main_brn_1_m.png');"></div>
			<div class="slide" style="background-image: url('<?= $hostaddress ?>/image/main_brn_4_m.png');"></div>
			<div class="slide" style="background-image: url('<?= $hostaddress ?>/image/main_brn_5_m.png');"></div>
			<div class="slide" onclick="newurldo('172','메이셀 프리미엄 트리플 시너지 라인50(3종세트)','<?= $useridx ?>')" style="background-image: url('<?= $hostaddress ?>/image/main_brn_1_m-10.png');"></div> -->

			<?
			$selectQuery = "select * from banner where b_status = '0' order by b_number";
			$selectResult = $conn->query($selectQuery);
			$selectCount = mysqli_num_rows($selectResult);
			for ($i =0; $i < $selectCount ; $i++){
				$selectRow = mysqli_fetch_assoc($selectResult);
				$b_img = $selectRow['b_img'];
				$b_link = $selectRow['b_link'];
		
				$selectMquery = "select mname from menu where menuidx = '$b_link'";
				$selectMResult = $conn->query($selectMquery);
				$selectMRow = mysqli_fetch_assoc($selectMResult);
				if($selectMRow){
					$mname = $selectMRow['mname'];
				}
				if($b_link == 0 ){
					?>
					<div class="slide" style="background-image: url('<?= $b_img ?>');"></div>
					<?
				}else{
					?>
					<div class="slide" onclick="newurldo('<?=$b_link?>','<?=$mname?>','<?= $useridx ?>')" style="background-image: url('<?= $b_img ?>');"></div>
					<?
				}
				
		
			}
			?>
		</div>
		<div class="pagination"></div>
	</div>
	<script>
		document.addEventListener("DOMContentLoaded", function() {
			var sliderContainer = document.getElementById('slider-container');
			var currentIndex = 0;
			var touchStartX = 0;
			var touchMoveX = 0;
			var touchStartTime = 0;
			var isTransitioning = false;
			var slideInterval;

			function createPaginationDots() {
				var paginationContainer = document.querySelector('.pagination');
				for (var i = 0; i < sliderContainer.children.length; i++) {
					var dot = document.createElement('div');
					dot.classList.add('dot');
					dot.addEventListener('click', function() {
						slideTo(Array.from(dot.parentNode.children).indexOf(dot));
					});
					paginationContainer.appendChild(dot);
				}
				updatePagination();
			}

			function updatePagination() {
				var paginationContainer = document.querySelector('.pagination');
				var dots = paginationContainer.getElementsByClassName('dot');
				for (var i = 0; i < dots.length; i++) {
					dots[i].classList.remove('active');
				}
				dots[currentIndex].classList.add('active');
			}

			function slideTo(index) {
				if (!isTransitioning && currentIndex !== index) {
					isTransitioning = true;
					currentIndex = index;
					sliderContainer.style.transition = 'transform 0.3s ease-in-out';
					sliderContainer.style.transform = 'translateX(' + (-index * 100) + '%)';
					setTimeout(function() {
						isTransitioning = false;
						sliderContainer.style.transition = '';
						updatePagination();
					}, 300);
				} else if (currentIndex === index) {
					isTransitioning = false;
				}
			}

			function startSlideInterval() {
				slideInterval = setInterval(function() {
					var nextIndex = (currentIndex + 1) % sliderContainer.children.length;
					slideTo(nextIndex);
				}, 3000);
			}

			function stopSlideInterval() {
				clearInterval(slideInterval);
			}

			// 터치 이벤트에 대한 이벤트 리스너 추가
			sliderContainer.addEventListener('touchstart', function(event) {
				touchStartX = event.touches[0].clientX;
				touchStartTime = Date.now();
				stopSlideInterval();
			});

			sliderContainer.addEventListener('touchmove', function(event) {
				touchMoveX = event.touches[0].clientX;
			});

			sliderContainer.addEventListener('touchend', function() {
				var touchDistance = touchMoveX - touchStartX;
				var touchDuration = Date.now() - touchStartTime;

				if (Math.abs(touchDistance) > 50 && touchDuration < 500) {
					if (touchDistance > 0) {
						slideTo(currentIndex > 0 ? currentIndex - 1 : sliderContainer.children.length - 1);
					} else if (touchDistance < 0) {
						slideTo(currentIndex < sliderContainer.children.length - 1 ? currentIndex + 1 : 0);
					}
				}

				startSlideInterval();
			});

			createPaginationDots();
			startSlideInterval();
		});
	</script>
	<!--------------------------------------------------------------------------------------------------------   카테고리  ----------------------------------------------------------------------->
	<div class="category-container-wrapper">
		<div class="category-container" id="category-container">
			<?php
            $categoryquery2 = "SELECT category.category_idx, category.category_name, 
            COUNT(CASE WHEN menu.display_status = '0' THEN menu.menuidx ELSE NULL END) as category_count
            FROM category
            LEFT JOIN menu ON category.category_idx = menu.category
            WHERE category.category_level = 1
            GROUP BY category.category_idx
			ORDER BY CASE WHEN FIELD(category.category_idx, 205, 1, 12, 2, 9, 5, 3, 206, 10, 4, 6, 7, 8, 11, 13, 14, 8) = 0 THEN 1 ELSE 0 END, 
            FIELD(category.category_idx, 205, 1, 12, 2, 9, 5, 3, 206, 10, 4, 6, 7, 8, 11, 13, 14, 8);";
			$categoryresult2 = $conn->query($categoryquery2);

			if ($categoryresult2->num_rows > 0) {
				while ($row = $categoryresult2->fetch_assoc()) {
					$category_idx = $row['category_idx'];
					$category_name = $row['category_name'];
					$category_count = $row['category_count'];
			?>
					<div class="product-item2">&nbsp;
						<img src="<?= $hostaddress ?>/image/<?= $category_idx ?>.svg" onclick="filterProducts('<?= $category_idx ?>')">
						<span class="categoryText">&nbsp;
							<?= $category_name ?>&nbsp;
							<?php
							// 제품 개수가 99개 이상일 경우 '999+'로 표시, 그 외에는 개수 그대로 표시
							if ($category_count > 99) {
								echo '99+';
							} else {
								echo "($category_count)";
							}
							?>
						</span>
					</div>
			<?php
				}
			}
			?>
		</div>
		<div class="bar-container">
			<div class="bar-progress"></div>
		</div>
	</div>
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			var barProgress = document.querySelector('.bar-progress');
			var categoryContainer = document.getElementById('category-container');
			var isTouching = false;

			function updateProgressBar() {
				var containerWidth = categoryContainer.clientWidth;
				var containerScrollWidth = categoryContainer.scrollWidth;
				var maxScroll = containerScrollWidth - containerWidth;
				var scrollPercentage = (categoryContainer.scrollLeft / maxScroll) * 100;

				var translateValue = (containerWidth / 500) * scrollPercentage;
				var maxTranslateValue = Math.max(0, containerWidth - barProgress.clientWidth);

				// 수정: 스크롤이 끝에 도달할 때 프로그레스 바가 바로 끝으로 이동
				if (scrollPercentage >= 120) {
					barProgress.style.transform = 'translateX(' + maxTranslateValue + 'px)';
				} else {
					translateValue = Math.min(translateValue, maxTranslateValue);
					barProgress.style.transform = 'translateX(' + translateValue + 'px)';
				}
			}

			function handleScroll() {
				updateProgressBar();
			}

			function handleTouchStart() {
				isTouching = true;
			}

			function handleTouchMove() {
				if (isTouching) {
					updateProgressBar();
				}
			}

			function handleTouchEnd() {
				isTouching = false;
				updateProgressBar();
			}

			categoryContainer.addEventListener('scroll', handleScroll);
			categoryContainer.addEventListener('touchstart', handleTouchStart);
			categoryContainer.addEventListener('touchmove', handleTouchMove);
			categoryContainer.addEventListener('touchend', handleTouchEnd);
			document.querySelectorAll('.product-item2 img').forEach(function(item) {
				item.addEventListener('click', function() {
					var category = item.getAttribute('onclick').match(/'(.*?)'/)[1];
					filterProducts(category);
				});
			});

			function allGoods() {
				window.location.href = '<?= $hostaddress ?>/allgoods.php';
			}

		});
	</script>
	<!--------------------------------------------------------------------------------------------------------   올타고 기획전  ----------------------------------------------------------------------->
	<?php
	// 사용자가 선택한 카테고리 정보를 가져오기
	@$data = $_REQUEST['data'];

	if ($data == '0' || $data == null) {
		$data = 'all';
	}
	if ($data == "all") {
	?>
		<div class="recom_div">
			<span style="font-size: 32px;	font-family: 'Jalnan'; margin-left: 3%;">올타고 브랜드관</span><span><img src="<?= $hostaddress ?>/image/alltago.png" style="margin-left:2.5px; margin-bottom:-5px; width:170px; height:30px;"></span>
			<?php
			$planQuery = "SELECT * FROM menu where category='205' and display_status = '0' ORDER BY idate DESC";
			$planResult = $conn->query($planQuery);

			$totalplan = $planResult->num_rows;

			for ($j = 0; $j < $totalplan; $j++) {
				$row = $planResult->fetch_assoc();
				// 해당 메뉴의 리뷰 수와 평균 별점을 가져옴
				$menuidx = $row['menuidx'];
				$img_11 = $row['imagepath1'] ?: $hostaddress . '/image/no.png'; 
				$mname = $row['mname'];
				$price = $row['price'];
				$price2 = $row['price2'];
				$c_status = $row['c_status']; 
				$c_rate = $row['c_rate']; 
				$nap_price = $row['nap_price'];
				$MDpay = $row['MDpay'];
				$card_carge = $row['card_carge'];
				$nap_price2 = $row['nap_price']+$MDpay+$price*($card_carge/100);

	      	$aaabbb= $c_status == '1' ? $price - (($price-$nap_price2) * $c_rate / 100) : $price;
	    	$discounted_price = round($aaabbb , -1);

				// 리뷰 수와 별점을 가져오기
				$review_query = "SELECT COUNT(*) AS review_count, AVG(starscore) AS average_rating FROM review WHERE menuidx = '$menuidx' && review_status = '1'";
				$review_result = $conn->query($review_query);
				$review_row = $review_result->fetch_assoc();
				$review_count = $review_row['review_count'];
				$average_rating = round($review_row['average_rating'], 1);
			?>
				<div class="plan_table" onclick="newurldo('<?= $row['menuidx'] ?>','<?= $row['mname'] ?>','<?= $useridx ?>')">
					<?php if ($img_11 == 0) { ?>
						<img src="<?= $hostaddress ?>/image/no.png" class="plan_img">
					<?php } else { ?>
						<img src="<?= $img_11 ?>" class="plan_img">
					<?php } ?>
					<div class="product-info">
						<div class="product-title"><strong><?= $row['mname']; ?></strong></div>
						<div class="product-price">
	<div><del><?= number_format($price2) ?>원</del><span style="font-size: 0.5em; display: inline; margin-left: 10px; vertical-align: 3px;">정가</span></div>
    <div style="color: black;"><?= number_format($price) ?>원<span style="font-size: 0.5em; display: inline; margin-left: 10px; vertical-align: 3px;">판매가</span></div>
    <div style="color: red;"><?= number_format($discounted_price) ?>원 <span style="font-size: 0.6em; color: red;">최대코인할인적용가</span></div>
</div>
						<!-- 별점 표시 및 상품에 대한 리뷰 수를 표시 -->
						<div class="product-rating">
							<?php
							for ($k = 1; $k <= 5; $k++) {
								if ($k <= $average_rating) {
									echo '<img src="' . $hostaddress . '/image/1.png">'; 
								} else {
									echo '<img src="' . $hostaddress . '/image/2.png">';
								}
							}
							?>
							<div class="product-review-count">
								<?php
								if ($review_count > 99) {
									// 리뷰 수가 99개를 넘어가면 숫자 옆에 + 표시 추가
									echo '(99+)';
								} else {
									echo '(' . $review_count . ')';
								}
								?>
							</div>
						</div>
					</div>
				</div>
			<?php
			}
			?>
		</div>
	<?php
	}
	?>
		<!-----------------------------------------------------------------------------베스트상품-----------------------------------------------------------------------------------------------  -->
		<?php


	if ($data == '0' || $data == null) {
		$data = 'all';
	}
	if ($data == "all") {
	?>
		<div class="recom_div">
			<span style="font-size: 32px; font-family: 'Jalnan'; margin-left: 3%;">TOP5 베스트상품</span>
			<!-- <span><img src="<?= $hostaddress ?>/image/alltago.png" style="margin-left:2.5px; margin-bottom:-5px; width:170px; height:30px;"></span> -->
			<?php
$mquery = "SELECT menu.menuidx, COUNT(orderlist.orderidx) AS order_count
FROM orderlist
JOIN menu ON menu.menuidx = orderlist.menuidx and orderlist.order_status = 6 where menu.display_status = '0'
GROUP BY menu.menuidx
ORDER BY order_count DESC
LIMIT 5";
$mresult = mysqli_query($conn,$mquery);
while($mrow = mysqli_fetch_assoc($mresult))
{
    $menuidx5 = $mrow['menuidx'];

    $reviewquery ="SELECT * from menu where menuidx = '$menuidx5'";
    $result = $conn->query($reviewquery);
    $count =mysqli_num_rows($result);

			for ($j = 0; $j < $count; $j++) {
				$row = $result->fetch_assoc();
				// 해당 메뉴의 리뷰 수와 평균 별점을 가져옴
				$menuidx = $row['menuidx'];
				$img_11 = $row['imagepath1'] ?: $hostaddress . '/image/no.png'; 
				$mname = $row['mname'];
				$price = $row['price'];
				$price2 = $row['price2'];
				$c_status = $row['c_status']; 
				$c_rate = $row['c_rate']; 
				$nap_price = $row['nap_price'];
				$MDpay = $row['MDpay'];
				$card_carge = $row['card_carge'];
				$nap_price2 = $row['nap_price']+$MDpay+$price*($card_carge/100);

	      	$aaabbb= $c_status == '1' ? $price - (($price-$nap_price2) * $c_rate / 100) : $price;
	    	$discounted_price = round($aaabbb , -1);

				// 리뷰 수와 별점을 가져오기
				$review_query = "SELECT COUNT(*) AS review_count, AVG(starscore) AS average_rating FROM review WHERE menuidx = '$menuidx' && review_status = '1'";
				$review_result = $conn->query($review_query);
				$review_row = $review_result->fetch_assoc();
				$review_count = $review_row['review_count'];
				$average_rating = round($review_row['average_rating'], 1);
			?>
				<div class="plan_table" onclick="newurldo('<?= $row['menuidx'] ?>','<?= $row['mname'] ?>','<?= $useridx ?>')">
					<?php if ($img_11 == 0) { ?>
						<img src="<?= $hostaddress ?>/image/no.png" class="plan_img">
					<?php } else { ?>
						<img src="<?= $img_11 ?>" class="plan_img">
					<?php } ?>
					<div class="product-info">
						<div class="product-title"><strong><?= $row['mname']; ?></strong></div>
						<div class="product-price">
    <div><del><?= number_format($price2) ?>원</del><span style="font-size: 0.5em; display: inline; margin-left: 10px; vertical-align: 3px;">정가</span></div>
    <div style="color: black;"><?= number_format($price) ?>원<span style="font-size: 0.5em; display: inline; margin-left: 10px; vertical-align: 3px;">판매가</span></div>
    <div style="color: red;"><?= number_format($discounted_price) ?>원 <span style="font-size: 0.6em; color: red;">최대코인할인적용가</span></div>
</div>
						<!-- 별점 표시 및 상품에 대한 리뷰 수를 표시 -->
						<div class="product-rating">
							<?php
							for ($k = 1; $k <= 5; $k++) {
								if ($k <= $average_rating) {
									echo '<img src="' . $hostaddress . '/image/1.png">'; 
								} else {
									echo '<img src="' . $hostaddress . '/image/2.png">';
								}
							}
							?>
							<div class="product-review-count">
								<?php
								if ($review_count > 99) {
									// 리뷰 수가 99개를 넘어가면 숫자 옆에 + 표시 추가
									echo '(99+)';
								} else {
									echo '(' . $review_count . ')';
								}
								?>
							</div>
						</div>
					</div>
				</div>
			<?php
			}}
			?>
		</div>
	<?php
	}
	?>
		<!-----------------------------------------------------------------------------전체상품------------------------------------------------------------------------------------------------  -->
	<?
	if ($data == '0' || $data == null) {
		$data = 'all';
	} // 기본값을 'all'로 설정

	// 데이터베이스 쿼리에서 사용할 조건 문자열 초기화
	$optionstr = "";

	// 사용자가 'all'이 아닌 특정 카테고리를 선택한 경우
	if ($data != 'all') {
		// 해당 카테고리의 정보를 데이터베이스에서 가져오기
		$categoryquery = "SELECT category_idx, category_name FROM category WHERE category_idx = $data";
		$categoryresult = $conn->query($categoryquery);

		// 데이터베이스에서 결과가 있을 경우
		if ($categoryresult->num_rows > 0) {
			// 가져온 카테고리 정보를 변수에 저장
			$row = $categoryresult->fetch_assoc();
			$category_idx = $row['category_idx'];
			$category_name = $row['category_name'];

			// 해당 카테고리에 속하는 상품만을 표시하기 위한 조건 설정
			$optionstr = "AND category = '$category_idx' ORDER BY category DESC";
		} else {
			// 해당하는 카테고리 정보가 없는 경우
			$optionstr = "AND 1=0"; // 아무 상품도 표시하지 않음
		}
	} else {
		// 사용자가 'all'을 선택한 경우
		// 모든 상품을 표시하기 위한 조건 설정
		$optionstr = "AND category >= 1 AND category <= 9999 ORDER BY idate DESC";
	}

	$count = 0;
	$query = "SELECT * FROM menu WHERE display_status ='0' $optionstr";
	$result = mysqli_query($conn, $query);

	// 상품이 있는 경우
	if ($result->num_rows > 0) {
		$count = $result->num_rows;
	?>

		<div class="box">
			<div class="new-product">
				<span class="<?= $data ?>">
					<?php
					if ($data == 'all') {
						$allquery = "SELECT COUNT(menuidx) FROM menu where display_status = '0'";
						$allresult = $conn->query($allquery);
						$row4 = mysqli_fetch_row($allresult);
						$count5 = $row4[0];
					?>
						<div >
							<div onclick="allGoods()" style="font-family:'Jalnan'; cursor:pointer;">전체 상품&nbsp;<span class="span1"><?= $count5 ?></span></div>
						</div>
					<?php } else {
						$categoryquery1 = "SELECT COUNT(menuidx) FROM menu WHERE category = $category_idx and display_status = '0'";
						$categoryresult1 = $conn->query($categoryquery1);
						$row2 = mysqli_fetch_row($categoryresult1);
						$count1 = $row2[0];
					?>
						<div><?= $category_name ?>&nbsp;<span class="span1"><?= $count1 ?></span></div>
					<?php } ?>
				</span>
			</div>
		</div>


		<div class="product-container">
    <?php
    for ($i = 0; $i < $count; $i++) {
        $row = $result->fetch_assoc();

        $menuidx = $row['menuidx'];
        $img_11 = $row['imagepath1'] ?: $hostaddress . '/image/no.png'; 
        $mname = $row['mname'];
        $price = $row['price'];
		$price2 = $row['price2'];
        $c_status = $row['c_status']; 
        $c_rate = $row['c_rate']; 
		$nap_price = $row['nap_price'];
		$MDpay = $row['MDpay'];
		$card_carge = $row['card_carge'];
		$nap_price2 = $row['nap_price']+$MDpay+$price*($card_carge/100);

		$aaabbb= $c_status == '1' ? $price - (($price-$nap_price2) * $c_rate / 100) : $price;
    	$discounted_price = round($aaabbb , -1);


        $review_query = "SELECT COUNT(*) AS review_count, AVG(starscore) AS average_rating FROM review WHERE menuidx = '$menuidx' AND review_status = '1'";
        $review_result = mysqli_query($conn, $review_query);
        $review_row = $review_result->fetch_assoc();
        $review_count = $review_row['review_count'];
        $average_rating = round($review_row['average_rating'], 1);
    ?>

    <?php if ($i % 2 == 0) : ?>
        <div> 
    <?php endif; ?>

    <div class="product-item" onclick="newurldo('<?= $menuidx ?>', '<?= $mname ?>', '<?= $useridx ?>')">
        <img src="<?= $img_11 ?>">
        <div class="product-info">
            <div class="product-title"><strong><?= $mname ?></strong></div>
			<div class="product-price">
    <div><del><?= number_format($price2) ?>원</del><span style="font-size: 0.5em; display: inline; margin-left: 10px; vertical-align: 3px;">정가</span></div>
    <div style="color: black;"><?= number_format($price) ?>원<span style="font-size: 0.5em; display: inline; margin-left: 10px; vertical-align: 3px;">판매가</span></div>
    <div style="color: red;"><?= number_format($discounted_price) ?>원 <span style="font-size: 0.6em; color: red;">최대코인할인적용가</span></div>
</div>
            <div class="product-rating">
                <?php
                for ($j = 1; $j <= 5; $j++) {
                    echo '<img src="' . ($j <= $average_rating ? $hostaddress . '/image/1.png' : $hostaddress . '/image/2.png') . '">';
                }
                ?>
                <span>(<?= $review_count ?>)</span>
            </div>
        </div>
    </div>

    <?php if ($i % 2 == 1 || $i == $count - 1) : ?>
        </div> 
    <?php endif; ?>

    <?php
    }
    ?>
</div>
<?php
} else {
    echo "<div class='box'>해당 카테고리에 속하는 상품이 없습니다.</div>";
}
?>


	<!----------------------------------------------------------------------------------공지사항------------------------------------------------------------------------------------------------------->
	<div class="recom_div" style="margin: 10% 0 20% 0;">
		<span class="gongji_sahang">공지사항</span>
		<hr style="border: 1px solid #000; margin-top: 7%;">
		<?php
		// content 테이블에서 날짜 기준으로 최신 5개의 데이터를 가져오는 쿼리
		$planQuery = "SELECT * FROM content ORDER BY idate DESC LIMIT 5";
		$planResult = $conn->query($planQuery);
		$title = "공지사항";
		$aaa = "이용약관";
		$bbb = "전자금융거래약관";
		$ccc = "개인정보처리방침";


		while ($planRow = $planResult->fetch_assoc()) {
			// 날짜 형식을 "Y-m-d H:i:s"에서 "m.d"로 변환
			$formattedDate = date("m.d", strtotime($planRow['idate']));
			// title이 일정 길이 이상이면 일정 길이로 자르고 "..." 추가
			$shortenedTitle = (mb_strlen($planRow['title'], 'UTF-8') > 30) ? mb_substr($planRow['title'], 0, 30, 'UTF-8') . '...' : $planRow['title']; ?>
			<div class="gongji_table" onclick="newnotice('0','<?=$title?>','0')">
				<div class="gongji_img">
					<div class="gongji_idx"><?= $planRow['contentidx']; ?></div>
					<div class="gongji_idate"><?= $formattedDate; ?></div>
				</div>
				<div class="gongji_content">
					<!-- "title"을 가운데 정렬하기 위한 컨테이너로 감싸기 -->
					<div class="gongji_name_container">
						<span class="gongji_name"><?= htmlspecialchars($shortenedTitle); ?></span>
					</div>
				</div>
			</div>
		<?php
		}
		?>

		<!-- <div class="hot_deobogibox">
						<span class="hot_spanlt">더 보러가기</span>
						<p></p>
						<div class="hot_img"><img src="https://www.allmyshop.co.kr/A_team/image/arrow.svg"></div>
					</div> -->
	</div>
	<!------------------------------------------------------------------------로고------------------------------------------------------------------------------------>
	<div class="foot_logo">
		<img src="<?= $hostaddress ?>/image/helpk.png" class="logo_img">
		<div style='padding: 20px;'>
			<div class="logo_div">무엇을</div>
			<div class="logo_div">도와드릴까요?</div>
			<div class="logo_tel">1544-1785</div>
			<div class="logo_mail">평일 : 10:00 ~ 17:00</div>
			<div class="logo_mail">점심시간 : 12:00 ~ 13:00</div>
			<div class="logo_gps">Mail : mhg@mhgkorea.com</div>
			<div class="logo_gps">Addrees : 서울특별시 마포구 마포대로 182-7, 2층</div>
		</div>
	</div>

	<div class="footer">
					<div class="footer_sangdan">
					<div class="footer_usediv"><div class="footer_uselt" onclick="aaa('0','<?=$aaa?>','0')"><span style="font-size: 14px; color:#747474;">이용약관 |</span></div></div>&nbsp;&nbsp;
						<div class="footer_transdiv"><div class="footer_uselt" onclick="bbb('0','<?=$bbb?>','0')"><span style="font-size: 14px; color:#747474;">전자금융거래약관 |</span></div></div>&nbsp;&nbsp;
						<div class="footer_transdiv"><div class="footer_uselt" onclick="ccc('0','<?=$ccc?>','0')"><span style="font-size: 14px; color:#747474;">개인정보처리방침</span></div></div>
					</div>

		<div class="footer_logodiv">
			<div style='margin-top:3%;'><img src="<?= $hostaddress ?>/image/myshop_logo.png"></div>
		</div>

		<div class="footer_info">
			<div class="footer_lt"><strong><span style="font-size: 12px;">대표번호 : 02-6326-9192</span></strong><br>
				<span style="font-size: 12px;">운영시간: AM 10:00 ~ PM 05:00 (월-금)</span><br>
				<span style="font-size: 12px;">점심시간: PM 12:00 ~ PM 13:00 (주말 및 공휴일 휴무)</span><br>
				<span style="font-size: 12px;">팩스번호: 02-1234-5678</span><br>
				<span style="font-size: 12px;">이메일: mhg@mhgkorea.com</span><br><br>

				<span style="font-size: 12px;">상호 : 엠에이치지코리아</span><br>
				<span style="font-size: 12px;">대표자명 : 강기효</span><br>
				<span style="font-size: 12px;">사업자소재지 : 서울특별시 마포구 마포대로 182-7, 2층 </span><br>
				<span style="font-size: 12px;">소재지 : 서울특별시 마포구 마포대로 182-7, 2층</span><br>
				<span style="font-size: 12px;">주관운영사 : 주식회사 더리더리</span><br>
				<span style="font-size: 12px;">사업자등록번호 : 695-88-01620</span><br>
				<span style="font-size: 12px;">통신판매번호 : 2022-서울마포-1173</span>
				<br>
			</div>
		</div>
	</div>

	<form action="" name="maincont">
		<input type="hidden" name="datatext" value="<?= $hostaddress ?>/menu.php">
		<input type="hidden" name="data" value="<?= $hostaddress ?>/myshop.php">
		<input type="hidden" name="query" value="<?= $query ?>">
		<input type="hidden" name="aaa" value="<?= $hostaddress ?>/Condition_of_use.html">
            <input type="hidden" name="bbb" value="<?= $hostaddress ?>/Condition_of_use2.html">
            <input type="hidden" name="ccc" value="<?= $hostaddress ?>/Personal_Information.html">
	</form>
	<button class="quickmenu">🔺<br>맨위로</button>


	<script>
		document.addEventListener("DOMContentLoaded", function() {
			var quickmenu = document.querySelector(".quickmenu");

			window.addEventListener("scroll", function() {
			var position = window.scrollY;

			// 스크롤 위치에 따라 'visible' 클래스를 토글합니다.
			if (position > 200) { // 필요에 따라 스크롤 위치 임계값을 조정하세요.
				quickmenu.classList.add('visible');
			} else {
				quickmenu.classList.remove('visible');
			}
			});

			quickmenu.addEventListener("click", function() {
			// 버튼을 클릭하면 맨 위로 스크롤합니다.
			window.scrollTo({ top: 0, behavior: 'smooth' });
			});
		});

// 		document.addEventListener("DOMContentLoaded", function() {
//     checkPopupVisibility();
// });
// function closePopup() {
//     var popup = document.getElementById('popup');
//     popup.style.display = 'none';
// }
// function closeset(){
// 	var dontShowAgain = document.getElementById('dontShowAgain').checked;
// 	if (dontShowAgain) {
//     setCookie("announcement", "false", 1);
// 	closePopup();
//     }
// }
// function checkPopupVisibility() {
//     var announcement = getCookie("announcement");
//     if (announcement !== "false") {
//         document.getElementById('popup').style.display = 'flex';
//     }
// }

// function setCookie(name, value, days) {
//     var expires = "";
//     if (days) {
//         var date = new Date();
//         date.setTime(date.getTime() + (days*24*60*60*1000));
//         expires = "; expires=" + date.toUTCString();
//     }
//     document.cookie = name + "=" + (value || "") + expires + "; path=/";
// }

// function getCookie(name) {
//     var nameEQ = name + "=";
//     var ca = document.cookie.split(';');
//     for(var i=0;i < ca.length;i++) {
//         var c = ca[i];
//         while (c.charAt(0)==' ') c = c.substring(1,c.length);
//         if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
//     }
//     return null;
// }



	</script>
</body>

</HTML>