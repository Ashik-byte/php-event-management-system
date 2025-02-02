<?php
require_once("PasswordHash.php");
require_once("JWT/jwt-functions.php");

if (empty($_SERVER['HTTP_REFERER']) || strpos($_SERVER['HTTP_REFERER'], ADMIN_URL) === false) {
    echo json_encode(["status" => "Denied", "message"=> "Unauthorized access."]);
    http_response_code(403);
    exit;
}

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $json = file_get_contents("php://input");
    $data = json_decode($json, true);

    $name = trim($data["name"] ?? "");
    $email = trim($data["email"] ?? "");

    if (empty($name) || empty($email)) {
        echo json_encode(["status" => "error", "message" => "All fields are required."]);
        exit;
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(["status" => "error", "message" => "Email already exists."]);
        exit;
    }

    // Generate a random password
    function generateRandomPassword($length = 12) {
        return bin2hex(random_bytes($length / 2)); // Generates a secure random password
    }

    $randomPassword = generateRandomPassword();

    $hasher = new PasswordHash(10, false);
    $hashedPassword = $hasher->HashPassword($randomPassword);

    // Insert user into database
    $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash, image_url, is_admin, is_active) VALUES (?, ?, ?, ?, ?, ?)");
    $success = $stmt->execute([$name, $email, $hashedPassword, 'admin.png', 0, 1]);

    if ($success) {

        if(USER_EMAIL){
            $email = USER_EMAIL;
        }

        $payload = [
            "purpose" => "user_registration",
        ];
        
        $jwt = createJWT($payload);
        
        $headers = [
            "Authorization: Bearer " . $jwt,
            "Content-Type: application/json"
        ];

        $email_subject = "Registration Successful";
        $email_body = "
                    Dear $name,<br/><br/>
                    Your registration has been successful.<br/><br/>
                    Please login with below credentials<br/><br/>
                    - email: $email<br/>
                    - pass: $randomPassword<br/><br/><br/>
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
            echo json_encode(["status" => "success", "data" => "Successfully registered ! A confirmation email has been sent to $email. Please check the spam folder too."]);
            exit;
        }else{
            echo json_encode(["status" => "success", "data" => "Successfully registered"]);
            exit;
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Registration failed. Please try again."]);
    }
}
?>