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

    $email = trim($data["email"] ?? "");

    if (empty($email)) {
        echo json_encode(["status" => "error", "message" => "All fields are required."]);
        exit;
    }

    $stmt = $conn->prepare("SELECT username FROM users WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);

    if ($stmt->rowCount() != 1) {
        echo json_encode(["status" => "error", "message" => "Email not found."]);
        exit;
    }

    $user = $stmt->fetch();

    $name = $user['username'];

    // Generate a random password
    function generateRandomPassword($length = 12) {
        return bin2hex(random_bytes($length / 2)); // Generates a secure random password
    }

    $randomPassword = generateRandomPassword();

    $hasher = new PasswordHash(10, false);
    $hashedPassword = $hasher->HashPassword($randomPassword);

    // Insert user into database
    $stmt = $conn->prepare("UPDATE users SET password_hash = ? WHERE email = ?");
    $success = $stmt->execute([$hashedPassword, $email]);

    if ($success) {

        if(USER_EMAIL){
            $email = USER_EMAIL;
        }

        $payload = [
            "purpose" => "pass_reset",
        ];
        
        $jwt = createJWT($payload);
        
        $headers = [
            "Authorization: Bearer " . $jwt,
            "Content-Type: application/json"
        ];

        $email_subject = "Password Reset Successful";
        $email_body = "
                    Dear $name,<br/><br/>
                    Your password reset has been successful.<br/><br/>
                    Please login with below credentials<br/><br/>
                    - email: $email<br/>
                    - pass: $randomPassword<br/><br/><br/>
                    Reset the password upon login.<br/>
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