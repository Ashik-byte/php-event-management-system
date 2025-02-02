<?php
session_start();
require_once('../../config.php');

$userId = $_SESSION["user_id"] ?? 0;

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["profileImage"])) {
    $uploadDir = "../../admin/uploads/avatars/";
    $fileName = basename($_FILES["profileImage"]["name"]);
    $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $allowedTypes = ["jpg", "jpeg", "png", "gif"];
    $maxFileSize = 2 * 1024 * 1024;

    if (!in_array($fileType, $allowedTypes)) {
        echo "Invalid file type! Only JPG, JPEG, PNG, and GIF are allowed.";
        exit;
    }

    if ($_FILES["profileImage"]["size"] > $maxFileSize) {
        echo "File size exceeds 2MB limit!";
        exit;
    }

    $targetFilePath = $uploadDir . $fileName;

    if (file_exists($targetFilePath)) {
        $fileName = pathinfo($fileName, PATHINFO_FILENAME) . "_" . time() . "." . $fileType;
        $targetFilePath = $uploadDir . $fileName;
    }

    if (move_uploaded_file($_FILES["profileImage"]["tmp_name"], $targetFilePath)) {
        $relativeFilePath = $fileName;

        $stmt = $conn->prepare("UPDATE users SET image_url = ? WHERE id = ?");
        $stmt->execute([$relativeFilePath, $userId]);

        $_SESSION["image_url"] = $relativeFilePath;
        echo "Profile image updated successfully";
    } else {
        echo "Error uploading file.";
    }
}
?>
