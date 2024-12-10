<?php
include('./db_connect.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

header('Content-Type: application/json'); // Set header before any output

$response = ['status' => 'error', 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['send-mail'])) {
        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);
        $phone = htmlspecialchars($_POST['phone']);
        $message = htmlspecialchars($_POST['message']);

        $sql = "INSERT INTO inquire (name, email, phone, message) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $name, $email, $phone, $message);

        if ($stmt->execute()) {
            $mail = new PHPMailer(true);

            try {
                // Server settings
                $mail->isSMTP();
                $mail->SMTPDebug = SMTP::DEBUG_OFF; // Disable debug output
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'quilbiolovelee58@gmail.com';
                $mail->Password   = 'qzpxzgwdhvfbmpst'; 
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587; 
    
                // Recipients
                $mail->setFrom($_POST['email'], $_POST['name']); 
                $mail->addAddress('quilbiolovelee58@gmail.com', 'Shemelle Apartment');
    
                // Content
                $mail->isHTML(true); 
                $mail->Subject = 'Reservation Request from Shemelle Apartment';
                $mail->Body    = '<p><strong>Name:</strong> ' . $name .'</p>' .
                                 '<p><strong>Email:</strong> ' . $email . '</p>' .
                                 '<p><strong>Phone:</strong> ' . $phone . '</p>' .
                                 '<p><strong>Message:</strong> ' . $message. '</p>';
    
                $mail->send();
                $response['status'] = 'success';
                $response['message'] = 'Message has been sent';
            } catch (Exception $e) {
                $response['message'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            $response['message'] = 'Database insertion failed: ' . $stmt->error;
        }
    } else {
        $response['message'] = 'Missing send-mail parameter';
    }
} else {
    $response['message'] = 'Invalid request method';
}

echo json_encode($response);
?>