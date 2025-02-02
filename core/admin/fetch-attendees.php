<?php
session_start();
require_once('../../config.php');
if (empty($_SERVER['HTTP_REFERER']) || strpos($_SERVER['HTTP_REFERER'], ADMIN_URL.'/admin/attendees.php') === false || ($_SESSION["is_admin"] != 1 && $_SESSION["is_admin"] != 0)) {
    echo json_encode(["status" => "Denied", "message"=> "Unauthorized access."]);
    http_response_code(403);
    exit;
}

$columns = ['id','name', 'email']; // Define columns for ordering

$query = "SELECT a.id, a.event_id, a.name, a.email, e.name AS event_name FROM attendees a LEFT JOIN events e ON a.event_id = e.id";

// Handling search (adding WHERE clause only if search value is provided)
$search_value = isset($_POST["search"]["value"]) ? trim($_POST["search"]["value"]) : "";
$search_clause = "";
if (!empty($search_value)) {
    $search_value = "%" . strtolower($search_value) . "%";
    $search_clause = " WHERE LOWER(a.name) LIKE '$search_value' OR LOWER(a.email) LIKE '$search_value'";
}

$query .= $search_clause;  // Add search clause to the main query

// Handling ordering
$order_column = isset($_POST["order"]) ? (int)$_POST['order'][0]['column'] : 0;
$order_dir = isset($_POST["order"]) ? $_POST['order'][0]['dir'] : "ASC";

// Ensure the order column exists in $columns array
$order_column = isset($columns[$order_column]) ? $columns[$order_column] : "id";
$query .= " ORDER BY " . $order_column . " " . strtoupper($order_dir);

// Handling pagination
$limit_query = ($_POST["length"] != -1) ? " LIMIT " . (int)$_POST["start"] . ", " . (int)$_POST["length"] : "";

$query .= $limit_query;  // Add pagination clause if necessary

// Execute the query
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Count filtered rows
$filtered_rows = $stmt->rowCount();

// Data for the response
$data = array();

foreach ($result as $row) {
    $sub_array = array();
    $sub_array[] = $row["id"];
    $sub_array[] = $row["name"]; 
    $sub_array[] = $row["email"]; 
    $sub_array[] = $row["event_name"];
    
    $sub_array[] = '<button type="button" name="update" class="btn btn-warning btn-xs edit-attendee"
                        data-id="'.$row["id"].'"
                        data-name="'.$row["name"].'"
                        data-email="'.$row["email"].'"
                        data-event="'.$row["event_id"].'">
                        <i class="fa fa-pencil"></i>
                    </button>
                    <button type="button" name="delete" class="btn btn-danger btn-xs delete-attendee"
                        data-id="'.$row["id"].'">
                        <i class="fa fa-trash"></i>
                    </button>';

    $data[] = $sub_array;
}

function get_all_data_count($conn) {
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM attendees");
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['total'];
}

function get_filtered_data_count($conn, $search_clause) {
    $query = "SELECT COUNT(*) as total FROM attendees a LEFT JOIN events e ON a.event_id = e.id " . $search_clause;
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['total'];
}

$output = array(
    "draw"              =>  intval($_POST["draw"]),
    "recordsTotal"      =>  get_all_data_count($conn),
    "recordsFiltered"   =>  get_filtered_data_count($conn, $search_clause),
    "data"              =>  $data
);

echo json_encode($output);

$conn = null;
?>