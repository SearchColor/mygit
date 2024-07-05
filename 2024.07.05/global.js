$(function () {
  // 헤더바 hover 클래스
  $(".main-nav").hover(
    function () {
      $(".header").addClass("hover");
    },

    function () {
      $(".header").removeClass("hover");
    }
  );

  // 햄버거 버튼과 사이드바
  $(".hamburger").click(function () {
    $(this).toggleClass("is-active");

    // 햄버거 바 클릭 시에만 mobile-menu를 보여주거나 숨깁니다.
    if (!$(this).hasClass("is-active")) {
      // "is-active" 클래스가 없을 때
      $(".mobile-menu").removeClass("opener");
    } else {
      $(".mobile-menu").addClass("opener");
    }
  });

  // 사이드바 아코디언
  var Accordion = function (el, multiple) {
    this.el = el || {};
    this.multiple = multiple || false;
    var links = this.el.find(".main-nav-link-btn");

    // on (이벤트, 지정자, 함수 정의)
    links.on(
      "click",
      {
        el: this.el,
        multiple: this.multiple,
      },
      this.dropdown
    );
  };

  // 지정 함수
  Accordion.prototype.dropdown = function (e) {
    var $el = e.data.el;
    ($this = $(this)), ($next = $this.next());

    // 아래 조건을 추가하여 햄버거 바 클릭 시에만 드롭다운 효과 발생
    if ($this.hasClass("main-nav-link-btn")) {
      $next.slideToggle();
      $this.parent().toggleClass("open");

      if (!e.data.multiple) {
        $el
          .find(".main-nav-sub")
          .not($next)
          .slideUp()
          .parent()
          .removeClass("open");
      }
    }
  };

  var accordion = new Accordion($(".mobile-nav"), false);

  // 헤더바 서브메뉴와 탭바
  let $mainMenu = $(".main-nav .depth1"),
    $mobileNav = $(".mobile-nav .depth1"),
    $subMenu = $(".depth2"),
    $tabBar = $("#tabBar li"),
    $tabBar2 = $("#tabBar2 li"),
    $tabContent = $("#wrap section"),
    currentUrl = location.href;

  // 메인메뉴 클릭 시 서브메뉴 처음 로드
  $mainMenu.add($mobileNav).each(function () {
    let targetUrl = $(this).find("a").attr("href");
    let active = currentUrl.indexOf(targetUrl);

    if (active > -1) {
      let idx = 0; // main-nav-sub li index번호
      let pidx = $(this).index(); // main-nav li index번호

      linkTab(idx, pidx);
    }
  });

  // 처음 로드 시 현재 URL에 해당하는 서브메뉴에 active 클래스 추가
  $subMenu.each(function () {
    let targetUrl = $(this).find("a").attr("href");
    let active = currentUrl.indexOf(targetUrl);

    if (active > -1) {
      let idx = $(this).closest(".depth2").index(); // main-nav-sub li index번호
      let pidx = $(this).parent().parent().index(); // main-nav li index번호

      linkTab(idx, pidx);
    }
  });

  function linkTab(idx, pidx) {
    $mainMenu
      .eq(pidx)
      .find(".depth2")
      .eq(idx)
      .addClass("active")
      .siblings()
      .removeClass("active");
    $mobileNav
      .eq(pidx)
      .find(".depth2")
      .eq(idx)
      .addClass("active")
      .siblings()
      .removeClass("active");

    $tabBar.eq(idx).addClass("active").siblings().removeClass("active");
    $tabBar2.eq(idx).addClass("active").siblings().removeClass("active");

    $tabContent.eq(0).addClass("active").siblings().removeClass("active");
  }

  // 상단 노출 탭 active
  $("#activated").on("click", function () {
    $tabBar2.stop().slideToggle();
  });

  // active 클래스가 추가된 li 태그를 찾아서 id가 activated인 span 태그에 생성합니다.
  var activatedTab = $("#tabBar2 .active");

  // active 클래스가 추가된 li 태그를 id가 activated인 span 태그에 복사합니다.
  if (activatedTab.length > 0) {
    var activatedTabContent = activatedTab.clone();
    $("#activated").empty().append(activatedTabContent);
  }

  // FAQ 아코디언
  $(".accordion-header").on("click", function () {
    let accorditem = $(this).parent();
    let accord = $(this).parent().parent();

    if (accorditem.hasClass("active") === true) {
      accorditem.removeClass("active");

      accorditem.find(".accordion-collapse").removeClass("show");
      accorditem.find(".accordion-button").addClass("collapsed");
    } else {
      if (accord.children().hasClass("active")) {
        accord.children().removeClass("active");
        accord.find(".accordion-collapse").removeClass("show");
        accord.find(".accordion-button").addClass("collapsed");

        accorditem.addClass("active");
        accorditem.find(".accordion-collapse").addClass("show");
        accorditem.find(".accordion-button").removeClass("collapsed");
      } else {
        accorditem.addClass("active");
        accorditem.find(".accordion-collapse").addClass("show");
        accorditem.find(".accordion-button").removeClass("collapsed");
      }
    }
  });
});

// 공지사항 검색
function searchTxt() {
  let sch =
    document.getElementById("sch").options[
      document.getElementById("sch").selectedIndex
    ].value;
  let sch_txt = document.getElementById("sch_txt").value;

  if (sch == "") {
    alert("검색 조건을 선택해주세요");
  } else if (sch_txt == "") {
    alert("검색어를 입력해주세요");
  } else {
    window.location.href = `service1.php?sch=${sch}&sch_txt=${sch_txt}`;
  }
}

// 공지사항 새글 추가
function add_notice() {
  var form = document.getElementById("maincontForm");

  let title = document.getElementById("title").value;
  let content = document.getElementById("content").value;

  if (title == "") {
    alert("제목를 입력해주세요.");
  } else if (content == "") {
    alert("내용을 입력해주세요.");
  } else {
    form.action = "notice_query.php";
    form.submit();
  }
}

// 공지사항 글 수정하기
function go_modify_notice(idx) {
  // alert(idx);
  let notice_idx = idx;
  window.location.href = `service1.php?viewMode=modify&idx=${notice_idx}`;
}

// 공지사항 글 수정하기
function modify_notice(idx) {
  var form = document.getElementById("maincontForm");
  document.getElementById("idx").value = idx;

  let title = document.getElementById("title").value;
  let content = document.getElementById("content").value;

  if (title == "") {
    alert("제목을 입력해주세요.");
  } else if (content == "") {
    alert("내용을 입력해주세요.");
  } else {
    form.action = "notice_modify_query.php";
    form.submit();
  }
}

// 공지사항 글 삭제하기
function delete_notice(idx) {
  var confirmDelete = confirm("정말 삭제하시겠습니까?");

  if (confirmDelete) {
    var form = document.getElementById("maincontForm");
    document.maincontForm.idx.value = idx;

    form.action = "notice_delete_query.php";
    form.submit();
  }
}

// Q & A 검색
function searchTxt2() {
  let sch =
    document.getElementById("sch").options[
      document.getElementById("sch").selectedIndex
    ].value;
  let sch_txt = document.getElementById("sch_txt").value;

  if (sch == "") {
    alert("검색 조건을 선택해주세요");
  } else if (sch_txt == "") {
    alert("검색어를 입력해주세요");
  } else {
    window.location.href = `service2.php?sch=${sch}&sch_txt=${sch_txt}`;
  }
}

// Q & A 새글 추가
function add_qna() {
  var form = document.getElementById("maincontForm");

  let q_title = document.getElementById("q_title").value;
  let q_name = document.getElementById("q_name").value;
  let q_pass = document.getElementById("q_pass").value;
  let q_content = document.getElementById("q_content").value;

  if (q_title == "") {
    alert("제목을 입력해주세요.");
  } else if (q_name == "") {
    alert("작성자를 입력해주세요.");
  } else if (q_pass == "") {
    alert("비밀번호를 입력해주세요.");
  } else if (q_content == "") {
    alert("내용을 입력해주세요.");
  } else {
    // 보안코드 숨겨진 필드로 추가 (필드 이름을 'secure_flag'로 설정)
    var secureField = document.createElement("input");
    secureField.setAttribute("type", "hidden");
    secureField.setAttribute("name", "secure_flag");
    secureField.setAttribute("value", "true");
    form.appendChild(secureField);

    form.action = "qna_query.php";
    form.submit();
  }
}

//문의 비밀번호 검증
function promptPassword(qnaIdx) {
  var password = prompt("비밀번호를 입력하세요.");
  if (password !== null && password !== "") {
    // 동적으로 폼을 생성하고 서버에 요청
    var form = document.createElement("form");
    form.setAttribute("method", "post");
    form.setAttribute("action", "qa_password.php");

    // 문의글 인덱스를 숨겨진 필드로 추가
    var idxField = document.createElement("input");
    idxField.setAttribute("type", "hidden");
    idxField.setAttribute("name", "idx");
    idxField.setAttribute("value", qnaIdx);
    form.appendChild(idxField);

    // 비밀번호를 숨겨진 필드로 추가 (필드 이름을 'q_pass'로 설정)
    var passwordField = document.createElement("input");
    passwordField.setAttribute("type", "hidden");
    passwordField.setAttribute("name", "q_pass");
    passwordField.setAttribute("value", password);
    form.appendChild(passwordField);

    // 보안코드 숨겨진 필드로 추가 (필드 이름을 'secure_flag'로 설정)
    var secureField = document.createElement("input");
    secureField.setAttribute("type", "hidden");
    secureField.setAttribute("name", "secure_flag");
    secureField.setAttribute("value", "true");
    form.appendChild(secureField);

    // 폼을 body에 추가하고 제출
    document.body.appendChild(form);
    form.submit();
  } else if (password === "") {
    alert("비밀번호를 입력해주세요.");
  }
}

//문의 비밀번호 패스 (관리자)
function WithoutPassword(qnaIdx) {
  var form = document.createElement("form");
  form.setAttribute("method", "post");
  form.setAttribute("action", "qa_password.php");

  // 문의글 인덱스를 숨겨진 필드로 추가
  var idxField = document.createElement("input");
  idxField.setAttribute("type", "hidden");
  idxField.setAttribute("name", "idx");
  idxField.setAttribute("value", qnaIdx);
  form.appendChild(idxField);

  // 보안코드 숨겨진 필드로 추가 (필드 이름을 'secure_flag'로 설정)
  var secureField = document.createElement("input");
  secureField.setAttribute("type", "hidden");
  secureField.setAttribute("name", "secure_flag");
  secureField.setAttribute("value", "true");
  form.appendChild(secureField);

  // 폼을 body에 추가하고 제출
  document.body.appendChild(form);
  form.submit();
}

// Q & A 글 수정하기
function go_modify_qna(qna_idx) {
  var form = document.createElement("form");
  form.setAttribute("method", "post");
  form.setAttribute("action", "qna_modify.php");

  // 문의글 인덱스를 숨겨진 필드로 추가
  var idxField = document.createElement("input");
  idxField.setAttribute("type", "hidden");
  idxField.setAttribute("name", "idx");
  idxField.setAttribute("value", qna_idx);
  form.appendChild(idxField);

  // 보안코드 숨겨진 필드로 추가 (필드 이름을 'secure_flag'로 설정)
  var secureField = document.createElement("input");
  secureField.setAttribute("type", "hidden");
  secureField.setAttribute("name", "secure_flag");
  secureField.setAttribute("value", "true");
  form.appendChild(secureField);

  // 폼을 body에 추가하고 제출
  document.body.appendChild(form);
  form.submit();
}

// Q & A 글 삭제하기
function go_delete_qna(qna_idx) {
  if (confirm("이 문의를 정말 삭제하시겠습니까?")) {
    var form = document.createElement("form");
    form.setAttribute("method", "post");
    form.setAttribute("action", "qna_all_delete.php");

    // 문의글 인덱스를 숨겨진 필드로 추가
    var idxField = document.createElement("input");
    idxField.setAttribute("type", "hidden");
    idxField.setAttribute("name", "idx");
    idxField.setAttribute("value", qna_idx);
    form.appendChild(idxField);

    // 보안코드 숨겨진 필드로 추가 (필드 이름을 'secure_flag'로 설정)
    var secureField = document.createElement("input");
    secureField.setAttribute("type", "hidden");
    secureField.setAttribute("name", "secure_flag");
    secureField.setAttribute("value", "true");
    form.appendChild(secureField);

    // 폼을 body에 추가하고 제출
    document.body.appendChild(form);
    form.submit();
  }
}

// Q & A 글 수정하기
function modify_qna(qna_idx) {
  var form = document.getElementById("maincontForm");
  document.maincontForm.qna_idx.value = qna_idx;

  let q_title = document.getElementById("q_title").value;
  let q_content = document.getElementById("q_content").value;
  let q_name = document.getElementById("q_name").value;

  if (q_title == "") {
    alert("제목을 입력해주세요.");
  } else if (q_content == "") {
    alert("내용을 입력해주세요.");
  } else if (q_name == "") {
    alert("작성자를 입력해주세요.");
  } else {
    // 보안코드 숨겨진 필드로 추가 (필드 이름을 'secure_flag'로 설정)
    var secureField = document.createElement("input");
    secureField.setAttribute("type", "hidden");
    secureField.setAttribute("name", "secure_flag");
    secureField.setAttribute("value", "true");
    form.appendChild(secureField);
    
    form.action = "qna_modify_query.php";
    form.submit();
  }
}

//답변등록
function submitReply() {
  var form = document.getElementById("adminReplySection");

  // 폼 데이터 검증
  let a_title = document.getElementById("a_title").value;
  let a_content = document.getElementById("a_content").value;

  if (a_title == "") {
    alert("답변 제목을 입력해주세요.");
  } else if (a_content == "") {
    alert("답변 내용을 입력해주세요.");
  } else {
    // 보안코드 숨겨진 필드로 추가 (필드 이름을 'secure_flag'로 설정)
    var secureField = document.createElement("input");
    secureField.setAttribute("type", "hidden");
    secureField.setAttribute("name", "secure_flag");
    secureField.setAttribute("value", "true");
    form.appendChild(secureField);

    form.action = "qna_answer.php";
    form.submit();
  }
}

// Q & A 답변글 삭제하기
function go_delete_answer(qna_idx) {
  if (confirm("이 답변을 정말 삭제하시겠습니까?")) {
    var form = document.createElement("form");
    form.setAttribute("method", "post");
    form.setAttribute("action", "qna_answer_delete.php");

    // 문의글 인덱스를 숨겨진 필드로 추가
    var idxField = document.createElement("input");
    idxField.setAttribute("type", "hidden");
    idxField.setAttribute("name", "idx");
    idxField.setAttribute("value", qna_idx);
    form.appendChild(idxField);

    // 보안코드 숨겨진 필드로 추가 (필드 이름을 'secure_flag'로 설정)
    var secureField = document.createElement("input");
    secureField.setAttribute("type", "hidden");
    secureField.setAttribute("name", "secure_flag");
    secureField.setAttribute("value", "true");
    form.appendChild(secureField);

    // 폼을 body에 추가하고 제출
    document.body.appendChild(form);
    form.submit();
  }
}

// 관리자 로그
function admin_login() {
  var form = document.getElementById("maincontForm");

  let adminid = document.getElementById("maincontForm").value;
  let adminpass = document.getElementById("maincontForm").value;

  if (adminid == "") {
    alert("아이디를 입력해주세요.");
  } else if (adminpass == "") {
    alert("비밀번호를 입력해주세요.");
  } else {
    form.action = "login_query.php";
    form.submit();
  }
}

// 관리자 로그아웃
function exit_account() {
  window.location.href = "exit_query.php";
}

function home() {
  var form = document.getElementById("maincontForm");

  form.action = "index.php";
  form.submit();
}
//긴 카테고리메뉴 이니셜화


