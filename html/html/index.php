<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
<h1>로그인</h1><hr>
    <form action="admin.php" method="POST">
        <p><input type="text" name="username" placeholder="관리자아이디" required></p>
        <p><input type="password" name="password" placeholder="관리자비밀번호" required></p>
        <p><input type="submit" value="로그인"></p>

        <a href="phpDown.php">클라이언트 파일 다운로드</a>
    </form>
</body>
</html>

