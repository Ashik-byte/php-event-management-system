<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require_once 'JWT/jwt-functions.php';

$headers = getallheaders();
$jwt = isset($headers["Authorization"]) ? str_replace("Bearer ", "", $headers["Authorization"]) : null;
$data = json_decode(file_get_contents('php://input'), true);

if (!$jwt || empty($data)) {
    http_response_code(401);
    echo json_encode(["status" => "Denied", "message" => "Unauthorized"]);
    exit;
}

$email = filter_var($data["email"], FILTER_SANITIZE_EMAIL);
$subject = $data['subject'];
$message = $data['message'];

$decoded = verifyJWT($jwt);

if ($decoded && ($decoded->purpose == 'attendee_registration' || $decoded->purpose == 'user_registration' || $decoded->purpose == 'pass_reset')) {

    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = 0;//SMTP::DEBUG_SERVER;                      //Enable verbose debug output. Use 3 for detailed output, 2 for brief debugging.
        $mail->isSMTP();                                            //Send using SMTP
                        
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through                            //SMTP password
        $mail->Username   = 'ollyoeventmanagement@gmail.com';                     //SMTP username
        $mail->Password   = 'otoh mpar ymti aikl';       
    
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;//PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption //ENCRYPTION_SMTPS this is for SSL part
        $mail->Port       = 587;//465;//587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS` //PORT 465
    
        //Recipients
        $mail->setFrom('ollyoeventmanagement@gmail.com', 'Ollyo Event Management');
        $mail->addAddress($email);     //Add a recipient

    
        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->addEmbeddedImage('../assets/img/logo/logo.png', 'signature_logo');
        $mail->Subject = $subject;
        $signature = '
        <br/>
        <p>--<br>
           Regards
        </p>
        <p> Ollyo Event Management<br/>
            <img src="cid:signature_logo" alt="Signature Logo" style="width:150px;"><br> <!-- Embedded image -->
            phone: +880 17 4293 3775 <br/>
            1 Quantum Drive, Dhaka 1229, Bangladesh; <br/>
            hello@ollyo.com, <a href="https://ollyo.com/" target="_blank">www.ollyo.com</a>
        </p>';
        $mail->Body    = $message . $signature;

        if($mail->send()){
            echo '200';
            exit;
        }else{
            echo '500';
            exit;
        }
    } catch (Exception $e) {
        echo '400';
        exit;
    }
    
} else {
    echo json_encode(["status" => "Denied", "message" => "Unauthorized"]);
    exit;
}