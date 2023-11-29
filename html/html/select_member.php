<?php
include_once "db_con.php";
if(!isset($db)){
    $db = db_con();
}
$db_result = mysqli_query($db , "select id from member");

if($db_result->num_rows > 0){

    echo "-Admin-<br>";
$num = 0;
    while($row = mysqli_fetch_array($db_result)){
        // 체크박스로 출력
        $value = $row[0]; //0으로 해야 하나씩 가져옴
        echo "| $num | ";
        echo $value . " |<br>";

        $num++;
    }
    echo "------------------------<br>";
} else {
    echo '결과 없음';
}
?>