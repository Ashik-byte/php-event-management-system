<?php
require_once("JWT/jwt-functions.php");

if (empty($_SERVER['HTTP_REFERER']) || strpos($_SERVER['HTTP_REFERER'], ADMIN_URL) === false) {
    echo json_encode(["status" => "Denied", "message"=> "Unauthorized access."]);
    http_response_code(403);
    exit;
}
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "Invalid request method. Use POST."]);
    http_response_code(405);
    exit;
}

require_once("PasswordHash.php");

$data = json_decode(file_get_contents("php://input"), true);
$email = filter_var($data["email"], FILTER_SANITIZE_EMAIL);
$password = $data["password"];

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["status" => "error", "message" => "Invalid email format."]);
    exit;
}

$stmt = $conn->prepare("SELECT id, username, email, password_hash, is_admin, is_active, image_url FROM users WHERE email = :email");
$stmt->bindParam(':email', $email, PDO::PARAM_STR);
$stmt->execute();

if($stmt->rowCount() > 0){
    $row = $stmt->fetch();
    // var_dump($row); exit;
    session_start();

    // Unset all session variables
    $_SESSION = array();
    session_destroy();
    session_start(); // Restart a fresh session
    
    $user_id = $row["id"];
    $user_name = $row["username"];
    $user_email = $row["email"];
    $user_pass = $row["password_hash"];
    $is_admin = $row["is_admin"];
    $is_active = $row["is_active"];
    $img_url = $row["image_url"];

    if($is_active !== 1){
        echo json_encode(["status" => "Error", "message" => "User Inactive"]);
        exit;
    }

    $hasher = new PasswordHash(10, false);

    if(!$hasher->CheckPassword($password, $user_pass)){
        echo json_encode(["status" => "Error", "message" => "Incorrect email or password."]);
        exit;
    }

    // **Prevent session fixation attacks**
    session_regenerate_id(true);

    $_SESSION['user_id'] = $user_id;
    $_SESSION['user_name'] = $user_name;
    $_SESSION['user_email'] = $user_email;
    $_SESSION['is_admin'] = $is_admin;
    $_SESSION['image_url'] = $img_url;
    $_SESSION['last_activity'] = time();
    $_SESSION['initiated'] = true;

    $payload = [
        "user_id" => $user_id,
        "email" => $user_email,
        "is_admin" => $is_admin,
        "user_name" => $user_name
    ];

    $jwt = createJWT($payload);
    $conn = null;
    echo json_encode(["status" => "success", "data" => $jwt]);
    exit;
}else {
    $conn = null;
    echo json_encode(["status" => "error", "message" => "Incorrect email or password."]);
    exit;
}
