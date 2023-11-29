<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>sign</title>
    <script src="http://code.jquery.com/jquery-2.1.4.min.js">

    </script>
    <link rel="stylesheet" href="css/usbbutton.css">
</head>
<body>
<h1>가입</h1><hr>
<form action="add_admin.php" method="POST">
    <p><input type="text" name="addid" placeholder="관리자 아이디" required></p>
    <p><input type="password" name="addpassword" placeholder="관리자 비밀번호" required></p>
    <p><input type="hidden" name="sign" value="true"></p>
    <p><input type="submit" value="가입"></p>
    <input type="button" value="뒤로" id="back">
</form>
</body>
</html>
