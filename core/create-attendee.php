<?php
require_once 'JWT/jwt-functions.php';

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

$payload = [
    "purpose" => "attendee_registration",
];

$jwt = createJWT($payload);

$headers = [
    "Authorization: Bearer " . $jwt,
    "Content-Type: application/json"
];


$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['event_id']) || empty($data['event_id']) || !isset($data['name']) || empty($data['name']) || !isset($data['email']) || empty($data['email'])) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Invalid or missing properties."]);
    exit;
}

function sanitize_input($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}


$eventId = intval($data['event_id']);

$name = sanitize_input($data['name']);
$email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);

$event_name = $data["event_name"];
$event_address = $data["event_address"];
$event_date_time = strtotime($data["event_time"]);
$formattedEventDate = date('F j, Y, g:i a', $event_date_time);


try{

    $sql = "SELECT * FROM attendees WHERE event_id = :event_id AND email = :email LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':event_id', $eventId, PDO::PARAM_INT);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch();
    
    if($stmt->rowCount() > 0){
        echo json_encode(["status" => "error", "message" => "You have already enrolled to this event"]);
        exit;
    }

    $sql = "INSERT INTO attendees (event_id, name, email) VALUES (:event_id, :name, :email)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':event_id', $eventId, PDO::PARAM_INT);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() == 0) {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Enrolling failed"]);
        exit;
    }

    $updateSql = "UPDATE events SET attendees_count = attendees_count + 1 WHERE id = :event_id";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bindParam(':event_id', $eventId, PDO::PARAM_INT);

    if($updateStmt->execute() && $updateStmt->rowCount() > 0){

        if(USER_EMAIL){
            $email = USER_EMAIL;
        }

        $email_subject = "Event Registration Confirmed [". $event_name. "]";
        $email_body = "
                    Dear $name,<br/><br/>
                    Your registration has been successful for the event <b>$event_name</b>.<br/><br/>
                    Please remember the time & venue:<br/><br/>
                    - Address: $event_address<br/>
                    - Time: $formattedEventDate<br/><br/><br/>
                    Thank you for registration.<br/>
                ";
        $data = json_encode(array(
            'email' => $email,
            'subject' => $email_subject,
            'message' => $email_body
        ));

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, ADMIN_URL . "/core/send-mail.php");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);

        $output = curl_exec($ch);

        curl_close($ch);

        if($output == "200"){
            echo json_encode(["status" => "success", "data" => "Successfully Enrolled ! A confirmation email has been sent to $email. Please check the spam folder too."]);
            exit;
        }else{
            echo json_encode(["status" => "success", "data" => "Successfully Enrolled"]);
            exit;
        }

        
    }


}catch(Exception $e){
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
    exit;
}finally {
    $conn = null;
}
?>
