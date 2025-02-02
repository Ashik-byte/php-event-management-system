<?php
session_start();
require_once('../../config.php');

if (empty($_SERVER['HTTP_REFERER']) || strpos($_SERVER['HTTP_REFERER'], ADMIN_URL.'/admin/users.php') === false || $_SESSION["is_admin"] != 1) {
    echo json_encode(["status" => "Denied", "message"=> "Unauthorized access."]);
    http_response_code(403);
    exit;
}

$columns = ['id', 'username', 'email', 'image_url', 'is_admin', 'is_active']; // Define columns for ordering

$query = "SELECT id, username, email, image_url, is_admin, is_active FROM users";

// Handling search (adding WHERE clause only if search value is provided)
$search_value = isset($_POST["search"]["value"]) ? trim($_POST["search"]["value"]) : "";
$search_clause = "";
if (!empty($search_value)) {
    $search_value = "%" . strtolower($search_value) . "%";
    $search_clause = " WHERE LOWER(username) LIKE '$search_value' OR LOWER(email) LIKE '$search_value'";
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
    $sub_array[] = $row["username"];
    $sub_array[] = $row["email"];
    $sub_array[] = $row["image_url"];
    $sub_array[] = $row["is_admin"] == "1" ? "Admin" : "User";
    $sub_array[] = $row["is_active"] == "1" ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Not Active</span>';
    $sub_array[] = '<button type="button" name="update" class="btn btn-warning mr-2 btn-xs edit-user" 
                    data-id="'.$row["id"].'" 
                    data-username="'.$row["username"].'" 
                    data-email="'.$row["email"].'" 
                    data-image="'.$row["image_url"].'" 
                    data-type="'.$row["is_admin"].'" 
                    data-status="'.$row["is_active"].'">
                    <i class="fa fa-pencil"></i>
                    </button>
                    <button type="button" name="delete" class="btn btn-danger btn-xs delete-user" data-id="'.$row["id"].'">
                    <i class="fa fa-trash"></i>
                    </button>';

    $data[] = $sub_array;
}

function get_all_data_count($conn) {
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM users");
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
    "draw"              =>  intval($_POST["draw"]),
    "recordsTotal"      =>  get_all_data_count($conn),
    "recordsFiltered"   =>  get_filtered_data_count($conn, $search_clause),
    "data"              =>  $data
);

echo json_encode($output);

$conn = null;
?>