<?php
session_start();
require_once('../../config.php');
require_once('../PasswordHash.php');

$userId = $_SESSION["user_id"] ?? 0;

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $currentPassword = trim($_POST["currentPassword"]);
    $newPassword = trim($_POST["newPassword"]);

    if (!$userId) {
        echo "User not logged in";
        exit;
    }

    $hasher = new PasswordHash(10, false);
    $hashedPasswordCurrent = !empty($currentPassword) ? $hasher->HashPassword($currentPassword) : '';

    $stmt = $conn->prepare("SELECT password_hash FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $user_pass = $user['password_hash'];

    if(!$user || !$hasher->CheckPassword($currentPassword, $user_pass)){
        echo "Incorrect user password";
        exit;
    }

    $newPasswordHash = $hasher->HashPassword($newPassword);
    $stmt = $conn->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
    $stmt->execute([$newPasswordHash, $userId]);

    echo "Password updated successfully";
    exit;
}
?>