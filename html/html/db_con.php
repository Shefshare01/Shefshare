<?php
function db_con() {

// 데이터베이스 연결 설정
    $servername = "db.cwrsnrlxgnde.ap-northeast-2.rds.amazonaws.com";
    $username = "root";
    $password = "port2023";
    $dbname = "usbdata";

    $db = mysqli_connect($servername, $username, $password, $dbname);

    // 연결 확인
    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    } else{ return $db; }
}