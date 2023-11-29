<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    session_start();
    $name = $_SESSION['name'];
    $pass = $_SESSION['pw'];
    //아이디 추가
    $id = $_POST['name'];
    $pw = $_POST['pw'];
    include 'db_con.php';
    $conn = db_con();
    $sql = "insert into member value('{$id}','{$pw}'); ";

        try{
            if ( $conn->query($sql)) {
                echo "<script>
            window.alert('관리자가 추가 되었습니다.')
            location.go(-1)
            </script>";
                $sql = "insert into log value($name,'Admin: {$id} 추가');" ;
                if( !$conn->query($sql) ){
                    echo "ERROR";
                }
            } else {
                echo "Error: 추가 실패<br>";
            }
        } catch (mysqli_sql_exception $e) {
            // MySQLi 예외를 다른 부분으로 넘기기
            header("Location: admin.php");
        }
} else{
    header("Location: login.php");
}
?>