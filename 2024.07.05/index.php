<?php
include "dbconn.php";
include "header.php";
$name = $_SESSION['admin'];
?>
<div class="info bottom">
    <ul>
        <li>
            <button class="button button--sacnite button--round-s button--inverted" onclick="scrollToSection('section1')"><i class="button__icon icon icon-user"></i><span>User</span></button>
        </li>
        <li>
            <button class="button button--sacnite button--round-s button--inverted" onclick="scrollToSection('section2')"><i class="button__icon icon icon-user"></i><span>User</span></button>
        </li>
        <li>
            <button class="button button--sacnite button--round-s button--inverted" onclick="scrollToSection('section3')"><i class="button__icon icon icon-user"></i><span>User</span></button>
        </li>
        <li>
            <button class="button button--sacnite button--round-s button--inverted" onclick="scrollToSection('section4')"><i class="button__icon icon icon-user"></i><span>User</span></button>
        </li>
        <li>
            <button class="button button--sacnite button--round-s button--inverted" onclick="scrollToSection('section5')"><i class="button__icon icon icon-user"></i><span>User</span></button>
        </li>

    </ul>
</div>
<div id="wrap1">
    <div class="contents" >
        <section id="section1">
            <div id="carouselExampleAutoplaying" class="carousel slide main_slide" data-bs-ride="carousel">
                <div class="carousel-inner" >
                    <div class="carousel-item active">
                        <img src="images/main1.png" class="d-block w-100"  alt="...">
                        <div class="carousel-caption" >
                            <h1>독보적인<span > 기술</span></h1>
                            <h1>당신의 니즈를 생각합니다.</h1>
                        </div>
                    </div>
                </div>
                <!-- <button
        class="carousel-control-prev"
        type="button"
        data-bs-target="#carouselExampleAutoplaying"
        data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button
        class="carousel-control-next"
        type="button"
        data-bs-target="#carouselExampleAutoplaying"
        data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>-->
            </div>
        </section>


        <!-- END #gtco-header -->

        <section id="section2">

            <div class="row row-pb-md">
                <div class="image-content-container ">
                    <img src="images/1_main_bg.png" class="d-block section2_img1"  alt="...">
                    <div class="col-md-6 gtco-heading text-center section1_text" data-aos="fade-right" >
                        <h1>우리는</h1>
                        <h1><span>혁신적인 기술력</span>을</h1>
                        <h1>자랑합니다</h1>
                    </div>
                    <div class="parallax-container" >
                        <div class="content" >
                            <div class="col-md-6 gtco-heading" data-aos="fade-left">
                                <div>
                                    <a href="#">
                                        <img src="images/01_main_1.png" class="d-block section2_img2"  alt="...">
                                        <h2>메타버스</h2>
                                    </a>
                                </div>
                                <div style="margin-left:40%;">
                                    <a href="#">
                                        <img src="images/01_main_2.png" class="d-block section2_img2" alt="...">
                                        <h2>loT 통합 시스템</h2>
                                    </a>
                                </div>
                                <div>
                                    <a href="#">
                                        <img src="images/01_main_3.png" class="d-block section2_img2" alt="...">
                                        <h2>빌딩 보안 시스템</h2>
                                    </a>
                                </div>
                                <div style="margin-left:40%;">
                                    <a href="#">
                                        <img src="images/01_main_4.png" class="d-block section2_img2"  alt="...">
                                        <h2>시스템 통합</h2>
                                    </a>
                                </div>
                                <div>
                                    <a href="#">
                                        <img src="images/01_main_5.png" class="d-block section2_img2" alt="...">
                                        <h2>전기차 충전 안전</h2>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section id="section3">
            <div class="row row-pb-md">
                <img src="images/0_main_bg_1.png" class="d-block section3_img1"  alt="...">
                <div class="tit">
                    <h3 data-aos="fade-up" data-aos-duration="1000" data-aos-delay="0" class="aos-init">
                        <font>
                            <font>SUSTAINABILITY</font>
                        </font>
                    </h3>
                    <p data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200" class="aos-init">
                        <font>
                            <font>
                                대한민국 공학 기술 자립화의 꿈
                            </font>
                        </font><br>
                    </p>
                </div>
                <div class="row row-pb-md justify-content-center mSection03">
                    <!-- Slick Slider -->
                    <div class="slider">
                        <div class="col-md-4 col-sm-4 service-wrap item">
                            <div class="service">
                                <img src="images/ic-.png" class="mx-auto d-block" alt="...">
                                <h3>
                                    <i class="ti-pie-chart"></i>
                                    비전
                                </h3>
                                <p>대한민국 공학 기술 자립화의 꿈을 asterdynamics의 기술이 세계 표준이 되는 그날까지...asterdynamics가 보유한 컴퓨터 그래픽스 기반의 CAE 소프트웨어 개발 기술은세계가 인정하는 우리의 기술입니다. 이러한 핵심 기술을 기반으로
                                    세계 최고의 CAE 소프트웨어 개발 및 보급사로 성장할 것이며, 엔지니어링 컨설팅분야와 웹 비즈니스 솔루션 분야에서도 세계적 수준의 기업으로 도약할 것입니다.</p>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4 service-wrap item">
                            <div class="service animate-change">
                                <img src="images/ic--1.png" class="mx-auto d-block" alt="...">
                                <h3>
                                    <i class="ti-ruler-pencil"></i>
                                    미션
                                </h3>
                                <p>우리는 다양한 IT인프라로부터의 정보를 가장 효율적인 방식으로 가장 정확하게 수집하여
                                    가장 통찰력 있는 정보로 가공하여 가장 보기 쉽게 고객에게 제공하여,
                                    고객으로 하여금 가장 효율적으로 손쉽게 IT인프라의 성능을 유지할 수 있게 하며,
                                    궁극적으로 IT서비스의 효율성과 연속성을 제고한다.</p>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4 service-wrap item">
                            <div class="service">
                                <img src="images/ic--2.png" class="mx-auto d-block" alt="...">
                                <h3>
                                    <i class="ti-settings"></i>
                                    가치
                                </h3>
                                <p>asterdynamics가
                                    지향하는 최고의 가치는 행복입니다.
                                    핵심가치는 asterdynamics가 존재하는 이유이며, 정체성이자 최고의 가치 기준입니다.
                                    구성원 모두의 행복과 함께 우리의 기술로써 온 세상을 행복하게 하는 것이
                                    asterdynamics가 추구하는 궁극의 지향점입니다.</p>
                            </div>
                        </div>

                    </div>
                    <!-- End Slick Slider -->
                </div>
            </div>
        </section>






        </script>

        <section id="section4">
            <div class="row row-pb-md">
                <div class="image-content-container ">
                    <img src="images/0_main_bg_2.png" class="d-block section4_img1"  alt="...">
                    <div class="col-md-4 text-md-start section1_text text-wrap" data-aos="fade-right">

                        <h1>SOLUTIONS</h1>
                        <h3>이렇게 제안합니다</h3>
                        <p>가장 기본적인IT 솔루션은 기업이 데이터를 효과적이고
                            효율적으로 사용할 수 있도록 지원합니다.</p>
                        <p>여기에는 고객, 직원, 서비스 및 프로세스와 관련된
                            정보의 생성, 관리, 액세스, 수집, 분석, 최적화, 보고 및 제시가 포함됩니다.</p>

                        <div class="indicaotr">
                            <span class="prevArrow">
                                <img src="images/prev.png">

                            </span>
                            <span class="nextArrow">
                                <img src="images/next.png">

                            </span>
                        </div>

                    </div>
                    <div class="parallax-container2 slider2">

                        <div class="col-md-4 col-sm-4 service-wrap item" >
                            <a href="#">
                            <img src="images/img1.png" class="section4_img2" alt="...">
                            <div class="service">
                                <h3 class="card-text text-white">IT개발진</h3>
                                <p class="card-text text-white">웹퍼블리싱, 앱, 보안체계로 인한 수입구조 확립</p>
                            </div>
                            </a>
                        </div>
                        <div class="col-md-4 col-sm-4 service-wrap item" >
                        <a href="#">
                            <img src="images/img1-1.png" class="section4_img2" alt="...">
                            <div class="service">
                                <h3 class="card-text text-white">마이샵 수익</h3>
                                <p class="card-text text-white">쇼핑몰을 이용한 수익구조 창출</p>
                            </div>
                            </a>
                        </div>
                        <div class="col-md-4 col-sm-4 service-wrap item" >
                        <a href="#">
                            <img src="images/img1-2.png" class="section4_img2" alt="...">
                            <div class="service">
                                <h3 class="card-text text-white">라이더</h3>
                                <p class="card-text text-white">전기오토바이를 이용하여 수익구조 창출</p>
                            </div>
                            </a>
                            
                        </div>



                    </div>
                </div>
            </div>
    </div>
    </section>


    <section id="section5">
        <div class="board">
            <div class="board">
                <div class="inner">
                    <div class="box aos-init" data-aos="fade-right" data-aos-duration="1000" data-aos-delay="0">
                        <div class="top">
                            <h3 class="board_text1">
                                <font style="vertical-align: inherit;">
                                    공지사항
                                </font>
                            </h3>

                            <a href="service1.php" class="more">
                                <img src="images/index_more.png">
                            </a>

                        </div>
                        <div class="area">
                            <?php
                            $ls_qry = "SELECT notice_idx, title, notice_date FROM notice ORDER BY notice_date DESC LIMIT 4";
                            $result = mysqli_query($conn, $ls_qry);

                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                                    <div class="area_text1">
                                        <a href="service1.php?viewMode=view&idx=<?php echo $row['notice_idx']; ?>">
                                            <div class="area_text2">
                                                <span class="area_text3"><?php echo $row['title']; ?></span>
                                                <span class="area_text4"><?php echo date('Y년 m월 d일', strtotime($row['notice_date'])); ?></span>
                                            </div>
                                        </a>
                                    </div>
                            <?php
                                }
                            } else {
                                echo "공지사항이 없습니다.";
                            }
                            ?>
                        </div>
                    </div>

                    <div class="box aos-init" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="0">
                        <div class="top" style="border-bottom: 3px solid #707070;">
                            <h3 class="board_text1">
                                <font>
                                    <font>Q&A</font>
                                </font>
                            </h3>

                            <a href="service2.php" class="more">
                                <img src="images/index_more.png">
                            </a>

                        </div>

                        <div class="area">
                            <?php
                            $ls_qry = "SELECT qna_idx, q_title, q_date FROM qa ORDER BY q_date DESC LIMIT 4";
                            $result = mysqli_query($conn, $ls_qry);

                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                                    <div class="area_text1">
                                        <a href="service2.php">
                                            <div class="area_text2">
                                                <span class="area_text3"><?php echo $row['q_title']; ?></span>
                                                <span class="area_text4"><?php echo date('Y년 m월 d일', strtotime($row['q_date'])); ?></span>
                                            </div>
                                        </a>
                                    </div>
                            <?php
                                }
                            } else {
                                echo "문의사항이 없습니다.";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </section>
</div>

<script src="js/mainset.js"></script>

<?php
include "footer.php";
?>