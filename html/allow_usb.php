<?php
include "db_con.php";
$db_conn = db_con();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    session_start();
    $name = $_SESSION["name"];

    $sql = "SELECT vid, pid FROM vpid";
    try {
        $db_result = mysqli_query($db_conn, $sql);
        $row = mysqli_fetch_array($db_result);


    if ($db_result->num_rows > 0) {
        $rows = $_POST['record'];
        $suc =true;
        foreach ($db_result as $value) {
            $vid = $value['vid'];
            $pid = $value['pid'];
            $al = $rows[0] ? "1" : "0";
            $num = count($rows);

            $update_sql = "UPDATE vpid SET allow = '$al' WHERE vid = '$vid' and pid = '$pid';";

            if ($db_conn->query($update_sql) !== TRUE) {
                $suc = false;
                break;
            }
            $d = date('Y-m-d H:i:s');
            if ($suc) {
                $log_message = "$name 이(가) USB 설정 변경 시도";
                $log_sql = "INSERT INTO log VALUES ('$name', '$log_message', NOW());";

                if ($db_conn->query($log_sql)) {
                    echo "<script>window.alert('설정이 저장되었습니다.')
                        window.location.replace('admin.php')</script>";
                }
            } else {
                $log_message = "$name 이(가) USB 허용 설정: $num 개 변경";
                $log_sql = "INSERT INTO log VALUES ('$name', '$log_message', NOW());";
                $db_conn->query($log_sql);
            }
        }
    } else {
        echo 'usb 데이터가 없습니다.';
    }
    }catch (Exception $e){
        echo "<script> window.location.replace('admin.php')</script>";
    }
} else {
    echo '로그인하세요.';
}