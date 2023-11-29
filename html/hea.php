<?php
function logout()
{
    if(session_start()) {
        unset($_SESSION["name"]);
        unset($_SESSION["pw"]);
        header('location:login.php');
    }
}
if(session_start()) {
    if (isset($_SESSION["name"])) $id = $_SESSION["name"];
    else $id = '';
    if (isset($_SESSION["pw"])) $pw = $_SESSION["pw"];
    else $pw = '';
}
?>
<div id="top">
    <h3>
        <a href="login.php">로그인 페이지</a>
    </h3><ul id="top_menu">
?>