<?php
require_once '../JWT/jwt-functions.php';
if (empty($_SERVER['HTTP_REFERER']) || strpos($_SERVER['HTTP_REFERER'], ADMIN_URL.'/admin/users') === false) {
    http_response_code(403);
    echo json_encode(["status" => "Denied", "message" => "Unauthorized access."]);
    exit;
}

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["status" => "Invalid request method. Use POST."]);
    exit;
}

$headers = getallheaders();
$jwt = isset($headers["Authorization"]) ? str_replace("Bearer ", "", $headers["Authorization"]) : null;
$data = json_decode(file_get_contents('php://input'), true);

if (!$jwt || empty($data) || empty($data["purpose"])) {
    http_response_code(401);
    echo json_encode(["status" => "Denied", "message" => "Unauthorized"]);
    exit;
}


$decoded = verifyJWT($jwt);
if (!$decoded) {
    http_response_code(401);
    echo json_encode(["status" => "Denied", "message" => "Invalid token"]);
    exit;
}

$userName = filter_var(trim($data['userName'] ?? ''), FILTER_SANITIZE_STRING);
$userEmail = filter_var(trim($data["userEmail"] ?? ''), FILTER_SANITIZE_EMAIL);
$userId = isset($data['userId']) ? (int) $data['userId'] : 0;
$userPass = trim($data['userPass'] ?? '');
$isAdmin = (int) ($data['isAdmin'] ?? 0);
$isActive = (int) ($data['isActive'] ?? 0);
$purpose = trim($data['purpose']);

if ($purpose !== 'user_delete' && !filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["status" => "Denied", "message" => "Invalid Email"]);
    exit;
}
$userEmail = strtolower($userEmail);
try {
    $sql = "SELECT id FROM users WHERE lower(email) = :email";
    if ($purpose === 'user_update') {
        $sql .= " AND id != :id";
    }
    $sql .= " LIMIT 1";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $userEmail, PDO::PARAM_STR);
    if ($purpose === 'user_update') {
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
    }
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo json_encode(["status" => "error", "message" => "Email Already Exists"]);
        exit;
    }

    require_once('../PasswordHash.php');
    $hasher = new PasswordHash(10, false);
    $hashedPassword = !empty($userPass) ? $hasher->HashPassword($userPass) : '';

    if ($purpose === 'user_creation') {
        $insertSQL = "INSERT INTO users (username, email, password_hash, is_admin, is_active) 
                      VALUES (:username, :email, :password_hash, :is_admin, :is_active)";
        $stmt = $conn->prepare($insertSQL);
        $stmt->bindParam(':username', $userName, PDO::PARAM_STR);
        $stmt->bindParam(':email', $userEmail, PDO::PARAM_STR);
        $stmt->bindParam(':password_hash', $hashedPassword, PDO::PARAM_STR);
        $stmt->bindParam(':is_admin', $isAdmin, PDO::PARAM_INT);
        $stmt->bindParam(':is_active', $isActive, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo json_encode(["status" => "success", "data" => "User has been created"]);
            exit;
        }

        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "User creation failed"]);
        exit;
    }

    if ($purpose === 'user_update') {
        $updateSQL = "UPDATE users SET username = :username, email = :email, 
                      is_admin = :is_admin, is_active = :is_active ";
        if (!empty($hashedPassword)) {
            $updateSQL .= ", password_hash = :password_hash ";
        }
        $updateSQL .= "WHERE id = :id";

        $stmt = $conn->prepare($updateSQL);
        $stmt->bindParam(':username', $userName, PDO::PARAM_STR);
        $stmt->bindParam(':email', $userEmail, PDO::PARAM_STR);
        $stmt->bindParam(':is_admin', $isAdmin, PDO::PARAM_INT);
        $stmt->bindParam(':is_active', $isActive, PDO::PARAM_INT);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        if (!empty($hashedPassword)) {
            $stmt->bindParam(':password_hash', $hashedPassword, PDO::PARAM_STR);
        }
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo json_encode(["status" => "success", "data" => "User updated successfully"]);
            exit;
        }

        echo json_encode(["status" => "error", "message" => "Update failed or no changes made"]);
        exit;
    }

    if ($purpose === 'user_delete') {
        if ($userId <= 0) {
            echo json_encode(["status" => "error", "message" => "Invalid User ID"]);
            exit;
        }

        $deleteSQL = "DELETE FROM users WHERE id = :id";
        $stmt = $conn->prepare($deleteSQL);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo json_encode(["status" => "success", "data" => "User deleted successfully"]);
            exit;
        }

        echo json_encode(["status" => "error", "message" => "User deletion failed or User not found"]);
        exit;
    }


    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Invalid operation"]);
    exit;

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
    exit;
} finally {
    $conn = null;
}

?>