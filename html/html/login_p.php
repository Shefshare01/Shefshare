<?php
//로그인 처리
$id = $_POST[ 'username' ];
$pw = $_POST[ 'password' ];
include 'db_con.php';

if ( isset( $id )&& isset( $pw ) ) {
    if(!isset($db)){
        $db = db_con();
    }
    $sql = "SELECT pw FROM member WHERE id = '". $id."' ;";
    $db_result = mysqli_query( $db, $sql );
    $row = mysqli_fetch_array( $db_result );

if (isset($row)) {
    $db_pass = $row['pw'];

    if ($pw === $db_pass) {
        // 세션 설정
        session_start();
        $num = 0;

            $name = $_SESSION['username'];
            $num ++;
       

        $sql = "INSERT INTO log VALUES (?, ?, NOW())";
        $stmt = $db->prepare($sql); $st = '이(가) 로그인 했습니다.';
        $stmt->bind_param("ss", $id, $st );
        $stmt->execute();
        $stmt->close();


        echo "<script> window.location.replace('admin.php'); </script>";
        exit(); // 페이지 이동 후 스크립트를 중지합니다.
    } else {
        echo "<script>
            window.alert('로그인에 실패했습니다.')
            history.go(-1)
            </script>";
    }
} else {
    echo "<script>
        window.alert('로그인에 실패했습니다.')
        history.go(-1)
        </script>";
}
} else{
    echo " <script>
                window.alert('정보를 입력해주세요.')
                history.go(-1)
              </script>";
}

if (isset($db_conn)){
    $db_conn->close();
}
