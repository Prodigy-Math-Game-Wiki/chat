<?php
session_start();

if (!(is_null($_SESSION['last_action'])) & $_SESSION['last_action'] < time() - 30) {
    $_SESSION = array();

    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    session_destroy();
}

$_SESSION['last_action'] = time();
?>
