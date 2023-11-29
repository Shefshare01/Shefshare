<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="/css/middele.css">
</head>
<body>
<h3>로그인</h3>
    <table><form action="login_p.php" method="POST">
        <div class="input-box ">
            <label for="id">아이디</label>
            <p><input type="text" name="username" class="id" required></p>
            <label for="pw">비밀번호</label>
            <p><input type="password" name="password" placeholder="" required></p>
        <input type="submit" class="btn btn-primary" value="로그인">
    </form>

    <form action="sign_admin.php" method="POST">
        <input type="hidden" name="sign" value="true">
        <p><input type="submit" class="btn btn-primary" value="가입하기"></p>
        </div>
    </form>
    <a href="phpDown.php" class="list-group-item list-group-item-action" aria-current="true">클라이언트 파일 다운로드</a>

<div class="btn-group" role="group" aria-label="Basic example"></div>


</body>
</html>

