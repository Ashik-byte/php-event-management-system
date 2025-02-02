<?php
require_once '../JWT/jwt-functions.php';
if (empty($_SERVER['HTTP_REFERER']) || strpos($_SERVER['HTTP_REFERER'], ADMIN_URL.'/admin/events') === false) {
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
$data = $_POST;

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

$eventName = filter_var(trim($data['event_name'] ?? ''), FILTER_SANITIZE_STRING);
$eventDate = trim($data['event_date'] ?? '');
$eventLocation = filter_var(trim($data['event_location'] ?? ''), FILTER_SANITIZE_STRING);
$eventDescription = filter_var(trim($data['event_description'] ?? ''), FILTER_SANITIZE_STRING);
$eventCapacity = isset($data['event_capacity']) ? (int) $data['event_capacity'] : 0;
$eventAttendees = isset($data['event_attendees']) ? (int) $data['event_attendees'] : 0;
$eventId = isset($data['event_id']) ? (int) $data['event_id'] : 0;
$purpose = trim($data['purpose']);
$imagePath = '';

if (!empty($_FILES['event_image']['name'])) {
    $targetDir = '../../assets/img/events/';
    $fileName = time() . '_' . basename($_FILES['event_image']['name']);
    $targetFilePath = $targetDir . $fileName;
    
    $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
    
    if (in_array($fileType, $allowedTypes)) {
        if (move_uploaded_file($_FILES['event_image']['tmp_name'], $targetFilePath)) {
            $imagePath = $fileName;
        } else {
            echo json_encode(["status" => "error", "message" => "Image upload failed"]);
            exit;
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid file type"]);
        exit;
    }
}

try {
    if ($purpose === 'event_creation') {
        $insertSQL = "INSERT INTO events (name, date_time, address, description, capacity, attendees_count, image) VALUES (:name, :date, :location, :description, :capacity, :attendees, :image_path)";
        $stmt = $conn->prepare($insertSQL);
        $stmt->bindParam(':name', $eventName, PDO::PARAM_STR);
        $stmt->bindParam(':date', $eventDate, PDO::PARAM_STR);
        $stmt->bindParam(':location', $eventLocation, PDO::PARAM_STR);
        $stmt->bindParam(':description', $eventDescription, PDO::PARAM_STR);
        $stmt->bindParam(':capacity', $eventCapacity, PDO::PARAM_INT);
        $stmt->bindParam(':attendees', $eventAttendees, PDO::PARAM_INT);
        $stmt->bindParam(':image_path', $imagePath, PDO::PARAM_STR);
        $stmt->execute();

        echo json_encode(["status" => "success", "data" => "Event created successfully"]);
        exit;
    }

    if ($purpose === 'event_update') {
        $updateSQL = "UPDATE events SET name = :name, date_time = :date, address = :location, description = :description, capacity = :capacity, attendees_count = :attendees";
        if (!empty($imagePath)) {
            $updateSQL .= ", image = :image_path";
        }
        $updateSQL .= " WHERE id = :id";

        $stmt = $conn->prepare($updateSQL);
        $stmt->bindParam(':name', $eventName, PDO::PARAM_STR);
        $stmt->bindParam(':date', $eventDate, PDO::PARAM_STR);
        $stmt->bindParam(':location', $eventLocation, PDO::PARAM_STR);
        $stmt->bindParam(':description', $eventDescription, PDO::PARAM_STR);
        $stmt->bindParam(':capacity', $eventCapacity, PDO::PARAM_INT);
        $stmt->bindParam(':attendees', $eventAttendees, PDO::PARAM_INT);
        if (!empty($imagePath)) {
            $stmt->bindParam(':image_path', $imagePath, PDO::PARAM_STR);
        }
        $stmt->bindParam(':id', $eventId, PDO::PARAM_INT);
        $stmt->execute();

        echo json_encode(["status" => "success", "data" => "Event updated successfully"]);
        exit;
    }

    
    if ($purpose === 'event_delete') {
        if ($eventId <= 0) {
            echo json_encode(["status" => "error", "message" => "Invalid Event ID"]);
            exit;
        }
        
        $deleteSQL = "DELETE FROM events WHERE id = :id";
        $stmt = $conn->prepare($deleteSQL);
        $stmt->bindParam(':id', $eventId, PDO::PARAM_INT);
        $stmt->execute();
        
        echo json_encode(["status" => "success", "data" => "Event deleted successfully"]);
        exit;
    }


    echo json_encode(["status" => "error", "message" => "Invalid operation"]);
    exit;
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
    exit;
} finally {
    $conn = null;
}   