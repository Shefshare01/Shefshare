<?php
function db() {
    $servername = "db.cwrsnrlxgnde.ap-northeast-2.rds.amazonaws.com";
    $username = "root";
    $password = "port2023";
    $dbname = "usbdata";

    $db_conn = mysqli_connect($servername, $username, $password, $dbname);
    return $db_conn;
}