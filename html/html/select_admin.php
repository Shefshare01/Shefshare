<?php
// 데이터베이스 연결 설정
$servername = "db.cwrsnrlxgnde.ap-northeast-2.rds.amazonaws.com";
$username = "root";
$password = "port2023";
$dbname = "usbdata";

$db_conn = new mysqli($servername, $username, $password, $dbname);

// 연결 확인
if ($db_conn->connect_error) {
    die("Connection failed: " . $db_conn->connect_error);
}
// 쿼리 작성 및 실행
$sql = "SELECT id FROM member";
$db_result = mysqli_query($db_conn, $sql);
$id = "0";
if ( $db_result->num_rows > 0 ) {
    // 결과를 가져와서 출력합니다.
    if( $array_id = mysqli_fetch_array($db_result) ) {
        echo "<form action='delete.php' method='post'>";
        echo "관리자 목록 : ";
        foreach ($array_id as $i) {
            echo $array_id[$i];
            echo '<br>';
        }
        echo '<input type="submit" value="삭제" num="[$num]"></form>';
    } else { echo '오류'; }
} else {
    echo "관리자 0명";
}
// 연결 해제
$conn = null;