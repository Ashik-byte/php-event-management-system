<?php
session_start();
require_once('../config.php');
// Function to destroy session and clear cookies
function destroySession() {
    session_unset();
    session_destroy();
    session_write_close();

    // Clear the session cookie
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, 
            $params["path"], $params["domain"], 
            $params["secure"], $params["httponly"]);
    }
}

// Check for POST request (for AJAX)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    destroySession();
    echo json_encode(["status" => "success", "message" => "Session destroyed"]);
    exit;
}

// For web browser requests (GET method)
destroySession();
header("Location: ".ADMIN_URL);
exit;
?>