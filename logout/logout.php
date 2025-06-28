<?php
session_start(); 

if (session_status() === PHP_SESSION_ACTIVE) {
    $_SESSION = array();

    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, 
            $params["path"], 
            $params["domain"], 
            $params["secure"] ?? false, 
            $params["httponly"] ?? true
        );
    }

    session_unset();
    session_destroy();
}

header("Location: ../Login/login.php"); 
exit();
?>
