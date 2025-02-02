<?php
session_start();
require_once('../../config.php');

if (empty($_SERVER['HTTP_REFERER']) || strpos($_SERVER['HTTP_REFERER'], ADMIN_URL.'/admin/events.php') === false || ($_SESSION["is_admin"] != 1 && $_SESSION["is_admin"] != 0)) {
    echo json_encode(["status" => "Denied", "message" => "Unauthorized access."]);
    http_response_code(403);
    exit;
}


if (!isset($_GET['event_id']) || empty($_GET['event_id'])) {
    die("Invalid event ID");
}

$event_id = intval($_GET['event_id']);

$event_query = "SELECT name FROM events WHERE id = :event_id";
$event_stmt = $conn->prepare($event_query);
$event_stmt->bindParam(":event_id", $event_id, PDO::PARAM_INT);
$event_stmt->execute();
$event = $event_stmt->fetch();

if (!$event) {
    die("Event not found");
}

$query = "SELECT id, name, email FROM attendees WHERE event_id = :event_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(":event_id", $event_id, PDO::PARAM_INT);
$stmt->execute();
$attendees = $stmt->fetchAll();

if (empty($attendees)) {
    die("No attendees found for this event.");
}

// Set headers for CSV file
$filename = "Attendees_" . preg_replace("/[^a-zA-Z0-9]/", "_", $event['name']) . "_" . date("Ymd") . ".csv";
header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=\"$filename\"");

// Open output stream for writing
$output = fopen("php://output", "w");

// Write CSV header
fputcsv($output, ["ID", "Name", "Email"]);

// Write data rows
foreach ($attendees as $row) {
    fputcsv($output, $row);
}

// Close the output stream
fclose($output);
$conn = null;
exit();
?>