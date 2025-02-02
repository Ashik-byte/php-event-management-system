<?php

require_once('../config.php');
// **Set Secure Session Cookie Parameters (MUST be before session_start())**
session_set_cookie_params([
    'lifetime' => 0,        // Session expires when browser closes
    'path' => '/',          // Accessible across the entire domain
    'domain' => '',         // Default to current domain
    'secure' => true,       // Only send cookies over HTTPS
    'httponly' => true,     // Block JavaScript access (prevents XSS attacks)
    'samesite' => 'Strict'  // Prevents CSRF attacks
]);

// **Start Session**
session_start();

// **Prevent Session Fixation Attacks**
if (!isset($_SESSION['initiated'])) {
    session_regenerate_id(true); // Regenerate ID to prevent session hijacking
    $_SESSION['initiated'] = true;
}

$timeout_duration = 600; // Set inactivity timeout (600 seconds = 10 minutes)
$url = ADMIN_URL;
// **Check Last Activity for Timeout**
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout_duration) {
    session_unset();
    session_destroy();
    session_write_close(); // Ensure session data is saved before redirect
    header("Location: $url");
    exit;
}

// **Update Last Activity Timestamp**
$_SESSION['last_activity'] = time();

// **Check If User is Logged In**
if (!isset($_SESSION['user_id'])) {
    session_write_close(); // Save session data before redirecting
    header("Location: $url"); 
    exit;
}
?>