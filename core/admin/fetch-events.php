<?php
session_start();
require_once('../../config.php');

if (empty($_SERVER['HTTP_REFERER']) || strpos($_SERVER['HTTP_REFERER'], ADMIN_URL.'/admin/events.php') === false || ($_SESSION["is_admin"] != 1 && $_SESSION["is_admin"] != 0)) {
    echo json_encode(["status" => "Denied", "message" => "Unauthorized access."]);
    http_response_code(403);
    exit;
}

$is_admin = $_SESSION["is_admin"];

// Define columns for ordering
$columns = ['id', 'name', 'date_time', 'capacity', 'attendees_count'];

$query = "SELECT id, name, description, date_time, capacity, attendees_count, image, address FROM events";

$search_value = isset($_POST["search"]["value"]) ? trim($_POST["search"]["value"]) : "";

// Initialize search condition
$search_clause = "";
$params = []; // Array to store parameters for binding

if (!empty($search_value)) {
    $search_clause = " WHERE LOWER(name) LIKE :search_value";
    $params[":search_value"] = "%" . strtolower($search_value) . "%";
}

$query .= $search_clause;

// Order by clause
$order_column = isset($_POST["order"]) ? (int)$_POST['order'][0]['column'] : 0;
$order_dir = isset($_POST["order"]) ? $_POST['order'][0]['dir'] : "ASC";
$order_column = isset($columns[$order_column]) ? $columns[$order_column] : "id";

$query .= " ORDER BY " . $order_column . " " . strtoupper($order_dir);

// Add pagination
if ($_POST["length"] != -1) {
    $query .= " LIMIT :start, :length";
    $params[":start"] = (int)$_POST["start"];
    $params[":length"] = (int)$_POST["length"];
}

// Prepare and execute query
$stmt = $conn->prepare($query);

// Bind parameters dynamically
foreach ($params as $key => &$val) {
    if (strpos($key, 'start') !== false || strpos($key, 'length') !== false) {
        $stmt->bindValue($key, $val, PDO::PARAM_INT);
    } else {
        $stmt->bindValue($key, $val, PDO::PARAM_STR);
    }
}

$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
$filtered_rows = $stmt->rowCount();

$data = array();
foreach ($result as $row) {
    $sub_array = array();
    $sub_array[] = $row["id"];
    $sub_array[] = $row["name"];
    $sub_array[] = $row["description"];
    $sub_array[] = $row["date_time"];
    $sub_array[] = $row["capacity"];
    $sub_array[] = $row["attendees_count"];
    $sub_array[] = $row["image"];
    $sub_array[] = $row["address"];
    $sub_array[] = '<div class="btn-group">
                        <button type="button" name="update" class="btn btn-warning btn-sm edit-event" 
                            data-id="' . $row["id"] . '" 
                            data-name="' . $row["name"] . '" 
                            data-description="' . $row["description"] . '" 
                            data-time="' . $row["date_time"] . '" 
                            data-capacity="' . $row["capacity"] . '" 
                            data-attendees="' . $row["attendees_count"] . '"
                            data-image="' . $row["image"] . '"
                            data-address="' . $row["address"] . '">
                            <i class="fa fa-pencil"></i>
                        </button>
                        <button type="button" name="delete" class="btn btn-danger btn-sm delete-event" data-id="' . $row["id"] . '">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>';
    if ($is_admin == 1) {
        $sub_array[] = '<a href="../core/admin/download-attendees.php?event_id=' . $row["id"] . '" class="btn btn-success btn-sm">
                            <i class="fa fa-download"></i>
                        </a>';
    } 

    $data[] = $sub_array;
}

// Function to get total number of records
function get_all_data_count($conn)
{
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM events");
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['total'];
}

function get_filtered_data_count($conn, $search_clause) {
    $query = "SELECT COUNT(*) as total FROM events " . $search_clause;
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['total'];
}

$output = array(
    "draw" => intval($_POST["draw"]),
    "recordsTotal" => get_all_data_count($conn),
    "recordsFiltered" => get_filtered_data_count($conn, $search_clause),
    "data" => $data
);

echo json_encode($output);
$conn = null;
?>