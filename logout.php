<?php
// Start the session so we can access and destroy it
session_start();

// Unset all session variables
$_SESSION = array();

// Delete the session cookie as well (if used)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Destroy the session completely
session_unset();
session_destroy();

// Redirect back to homepage after logout
header("Location: index.php");
exit;
?>