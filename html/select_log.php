<?php

include_once "db_con.php";
if(!isset($db)){
    $db = db_con();
}
    $db_result = mysqli_query($db, "select * from log order by logtime desc");
    $rows = $db_result->num_rows;
    if ($rows > 0) {

        echo "------------------------<br>";
        $num = 1;
        while (($row = mysqli_fetch_array($db_result)) && $num < 11) {

            $value = $row['id'];
            $value2 = $row['log'];
            echo $num . ":\t";
            echo $value . "\t " . $value2;
            echo "<br>";

            $num++;
        }
        echo "------------------------";
    } else {
        echo '결과 없음';
    }