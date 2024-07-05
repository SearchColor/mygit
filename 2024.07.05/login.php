<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="favicon" href="images/16px.ico">
    <link rel="icon" href="https://www.asterdynamics.kr/images/16px.ico?v=2" />
    <title>asterdynamics</title>
</head>


<!---- main container ----->
<div class="container d-flex justify-content-center align-items-center min-vh-100">

        <!---- right box ----->
        <div class="col-md-6 right-box" style="border: 1px solid #E5E5E5; padding:100px 50px; border-radius: 10px;">
            <form id="maincontForm" method="POST">
                <div class="row align-items-center">
                    <div class="heder-text mb-4 text-center">
                        <h1 class="mb-5">관리자 로그인</h1>
            
                    </div>
                    <div class="input-group mb3">
                        <input type="text" name="adminid" class="form-control form-control-lg bg-light fs-6 mb-3" placeholder="아이디를 입력해주세요">
                    </div>
                    <div class="input-group mb1">
                        <input type="password" name="adminpass" class="form-control form-control-lg bg-light fs-6 mb-3" placeholder="비밀번호를 입력해주세요">
                    </div>
                    <div class="input-group mb5 d-flex justify-content-between">
                        <div class="input-check">
                            <input type="checkbox" class="form-check-input" id="formCheck">
                            <label for="formCheck" class="form-check-label text-secondary mb-5"><small>아이디 저장하기</small></label>
                        </div>

                    </div>
                </div>
                <div class="input-group mb-3">
                    <button class="btn btn-lg btn-primary w-100 fs-6" onclick="admin_login()">로그인</button>
                </div>
            
                    <div class="input-group mb-3">
                        <button class="btn btn-lg btn-light w-100 fs-6" onclick="home()"><small>홈으로</small></button>
                    </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>

<script src="./js/jquery.min.js"></script>
<script src="./js/global.js"></script>
