<?php
require_once '../JWT/jwt-functions.php';

if (empty($_SERVER['HTTP_REFERER']) || strpos($_SERVER['HTTP_REFERER'], ADMIN_URL.'/admin/attendees') === false) {
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

$attendeeName = filter_var(trim($data['attendeeName'] ?? ''), FILTER_SANITIZE_STRING);
$attendeeEmail = filter_var(trim($data["attendeeEmail"] ?? ''), FILTER_SANITIZE_EMAIL);
$attendeeEventId = isset($data['attendeeEventId']) ? (int) $data['attendeeEventId'] : 0;
$attendeePrevEventId = isset($data['attendeePrevEvent']) ? (int) $data['attendeePrevEvent'] : 0;
$attendeeId = isset($data['attendeeId']) ? (int) $data['attendeeId'] : 0;
$purpose = trim($data['purpose']);

if ($purpose !== 'attendee_delete' && !filter_var($attendeeEmail, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["status" => "Denied", "message" => "Invalid Email"]);
    exit;
}

$attendeeEmail = strtolower($attendeeEmail);

try {
    if ($purpose === 'attendee_creation') {
        $eventQuery = "SELECT id, attendees_count, capacity FROM events WHERE id = :event_id LIMIT 1";
        $stmt = $conn->prepare($eventQuery);
        $stmt->bindParam(':event_id', $attendeeEventId, PDO::PARAM_INT);
        $stmt->execute();
        $event = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$event) {
            echo json_encode(["status" => "error", "message" => "Event not found"]);
            exit;
        }

        if ($event['attendees_count'] >= $event['capacity']) {
            echo json_encode(["status" => "error", "message" => "Event is at full capacity"]);
            exit;
        }

        $insertSQL = "INSERT INTO attendees (name, email, event_id) VALUES (:name, :email, :event_id)";
        $stmt = $conn->prepare($insertSQL);
        $stmt->bindParam(':name', $attendeeName, PDO::PARAM_STR);
        $stmt->bindParam(':email', $attendeeEmail, PDO::PARAM_STR);
        $stmt->bindParam(':event_id', $attendeeEventId, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $updateEventSQL = "UPDATE events SET attendees_count = attendees_count + 1 WHERE id = :event_id";
            $stmt = $conn->prepare($updateEventSQL);
            $stmt->bindParam(':event_id', $attendeeEventId, PDO::PARAM_INT);
            $stmt->execute();

            echo json_encode(["status" => "success", "data" => "Attendee added successfully"]);
            exit;
        }

        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Attendee creation failed"]);
        exit;
    }

    if ($purpose === 'attendee_update') {

        $updateSQL = "UPDATE attendees SET name = :name, email = :email, event_id = :event_id WHERE id = :id";
        $stmt = $conn->prepare($updateSQL);
        $stmt->bindParam(':name', $attendeeName, PDO::PARAM_STR);
        $stmt->bindParam(':email', $attendeeEmail, PDO::PARAM_STR);
        $stmt->bindParam(':event_id', $attendeeEventId, PDO::PARAM_INT);
        $stmt->bindParam(':id', $attendeeId, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            if ($attendeeEventId !== $attendeePrevEventId) {
                $decreaseSQL = "UPDATE events SET attendees_count = attendees_count - 1 WHERE id = :prev_event_id";
                $stmt = $conn->prepare($decreaseSQL);
                $stmt->bindParam(':prev_event_id', $attendeePrevEventId, PDO::PARAM_INT);
                $stmt->execute();

                $increaseSQL = "UPDATE events SET attendees_count = attendees_count + 1 WHERE id = :new_event_id";
                $stmt = $conn->prepare($increaseSQL);
                $stmt->bindParam(':new_event_id', $attendeeEventId, PDO::PARAM_INT);
                $stmt->execute();
            }
            echo json_encode(["status" => "success", "data" => "Attendee updated successfully"]);
            exit;
        }

        echo json_encode(["status" => "error", "message" => "Update failed or no changes made"]);
        exit;
    }

    if ($purpose === 'attendee_delete') {
        $attendeeQuery = "SELECT event_id FROM attendees WHERE id = :attendee_id LIMIT 1";
        $stmt = $conn->prepare($attendeeQuery);
        $stmt->bindParam(':attendee_id', $attendeeId, PDO::PARAM_INT);
        $stmt->execute();
        $attendee = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$attendee) {
            echo json_encode(["status" => "error", "message" => "Attendee not found"]);
            exit;
        }

        $deleteSQL = "DELETE FROM attendees WHERE id = :id";
        $stmt = $conn->prepare($deleteSQL);
        $stmt->bindParam(':id', $attendeeId, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $updateEventSQL = "UPDATE events SET attendees_count = attendees_count - 1 WHERE id = :event_id";
            $stmt = $conn->prepare($updateEventSQL);
            $stmt->bindParam(':event_id', $attendee['event_id'], PDO::PARAM_INT);
            $stmt->execute();

            echo json_encode(["status" => "success", "data" => "Attendee deleted successfully"]);
            exit;
        }

        echo json_encode(["status" => "error", "message" => "Attendee deletion failed or not found"]);
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