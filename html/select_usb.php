<?php
include_once "db_con.php";
if(!isset($db)){
    $db = db_con();
}

// 쿼리 작성 및 실행
$sql = "SELECT vid, pid, allow FROM vpid";
try{
    session_start();
    if( $name = $_SESSION["name"] ) {
        $db_result = mysqli_query($db, $sql);

        if ($db_result->num_rows > 0) {
            // 결과를 가져와서 출력합니다.
            echo "<form action='allow_usb.php' method='post'>";
            $num = 0;
            $permissions = [];
            while ($row = mysqli_fetch_array($db_result)) {
                $vid = $row['vid'];
                $pid = $row['pid'];
                $allow = $row['allow'] ? '1' : '0';
                array_push($permissions, $vid, $pid);
                // 체크박스로 출력
                echo "<input type='radio' name='record[$num]' ". (!$allow ?  'checked' : '' ) . "><label for=' $permissions[$num][$vid][$pid]'>허용</label>";
                echo "<input type='radio' name='record[$num]' " . ($allow ? 'checked' : '' ) ."><label for='$permissions[$num][$vid][$pid]'>차단</label>";

                echo ' : ' . $vid . '-' . $pid . "<br>";
                $num++;
            }
            echo '<input type="submit" value="저장" name="[$num]"></form>';
        } else {
            echo "0 results";
        }
    } else {
        echo "<script> window.location.replace('login.php');
                window.alert('로그인에 실패했습니다.') 
        </script>";
    }
} catch(mysqli_sql_exception $e){
    echo "연결 오류";
}
