<?php
require_once('../config.php');

if (empty($_SERVER['HTTP_REFERER']) || strpos($_SERVER['HTTP_REFERER'], ADMIN_URL) === false) {
    echo json_encode(["status" => "Denied", "message"=> "Unauthorized access."]);
    http_response_code(403);
    exit;
}

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "Invalid request method. Use POST."]);
    http_response_code(405);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id']) || empty($data['id'])) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Invalid or missing event ID."]);
    exit;
}

$eventId = intval($data['id']);

try{
    $stmt = $conn->prepare("SELECT id, name, description, image, date_time, address FROM events WHERE id = :id");
    $stmt->bindParam(':id', $eventId, PDO::PARAM_INT);
    $stmt->execute();

    $event = $stmt->fetch();

    if ($event) {
        echo json_encode(["status" => "success", "data" => $event]);
        exit;
    } else {
        http_response_code(404);
        echo json_encode(["status" => "error", "message" => "Event details not found."]);
        exit;
    }

}catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
    exit;
}finally {
    $conn = null;
}

?>
