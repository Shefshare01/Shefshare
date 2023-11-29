<?php
// 세션 시작
session_start();
$conn = mysqli_connect('db.cwrsnrlxgnde.ap-northeast-2.rds.amazonaws.com', 'root', 'port2023', 'usbdata', 3306);

// 사용자가 POST로 전송한 데이터 가져오기
$username = $_POST['name'];
$password = $_POST['pw'];
$sql = "insert into member value(' " . $username . ',' . $password . ")'; ";
$result = mysqli_query($conn, $sql);
$row=mysqli_fetch_array($result);
// 사용자 정보 검증
if ( $db_pass = $row[ 'pw' ]) {
    // 로그인 성공
    $_SESSION['id'] = $username; // 세션에 사용자 정보 저장

    // 로그인 후 리다이렉션 또는 다른 작업 수행
    echo "<script>
                window.alert('관리자 추가 되었습니다.')
                history.go(-1)
            </script>";
    exit();
} else {
    // 로그인 실패
    echo "<script>
                window.alert('관리자 추가에 실패했습니다.')
                history.go(-1)
            </script>";
}
