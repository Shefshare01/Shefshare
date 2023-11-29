<?php
include_once "db_con.php";
$db_conn = db_con();
$sign = $_POST['sign'];
if($sign ) {
    $sql = "select * from member; ";
    $result = mysqli_query($db_conn, $sql);
    if ($num = mysqli_num_rows($result)) {
            echo "<script>
            window.alert('관리자 계정이 존재합니다. 로그인 해주세요.')
            location.replace('login.php')
        </script>";
    }
}else{
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        session_start();
        $name = $_SESSION[0];
        $pass = $_SESSION['pw'];
        //아이디 추가
        $id = $_POST['name'];
        $pw = $_POST['pw'];
        try {
            $sql = "insert into member value('{$id}','{$pw}'); ";

            if ($db_conn->query($sql)) {
                echo "<script>
            window.alert('가입 완료 되었습니다.')
            location.replace('login.php')
        </script>";
                $sql = "insert into log value('{$name}','member'{$id}' 추가');";
            } else {
                echo "Error: 가입 실패<br>";
            }
        }catch (mysqli_sql_exception $e){
            // MySQLi 예외를 다른 부분으로 넘기기
            header("Location: admin.php");
        }
    } else{
        header("Location: sign.php");
    }
}
?>