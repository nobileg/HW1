<?php
    require_once 'database.php';
    session_start();

    function checkSession() {
        if(isset($_SESSION['user_id'])) {
            return $_SESSION['user_id'];
        } else 
            return 0;
    }
?>