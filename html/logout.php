<?php
function logout($id) {
    session_start();
    unset($_SESSION[$id]);
    echo "Error";

    if (isset($db)) {
        $db->close();
    }
}
    ?>