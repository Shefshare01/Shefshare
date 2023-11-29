<?php
include "logout.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['name'];
    $pw = $_POST['pw'];

    if (isset($id)) {
        include "db_con.php";
        $db = db_con();
        $sql = "DELETE FROM member WHERE id = '{$id}' AND pw = '{$pw}' ;";
        session_start();
        $name = $_SESSION['name'];
        if ($db->query($sql) === TRUE) {
            $sql = "INSERT INTO log VALUES (?, ?, NOW())";
            $stmt = $db->prepare($sql); $st = $id. '관리자 삭제 되었습니다.';
            $stmt->bind_param("ss", $name, $st );
            $stmt->execute();
            $stmt->close();
            logout($id, $pw);
        } else {
            echo "<script>
                window.alert('이미 존재하지 않는 아이디입니다.')
                history.go(-1)
            </script>";
        }
    } else {
    echo "정보가 일치하지 않습니다.";
    }
} else{
    echo '로그인하세요.';
}
?>