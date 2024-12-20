<?php
include('./db_connect.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// Set header for JSON response
header('Content-Type: application/json');

// Initialize response array
$response = ['status' => 'error', 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['send-mail'])) {
        // Sanitize and assign POST variables
        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);
        $phone = htmlspecialchars($_POST['phone']);
        $message = htmlspecialchars($_POST['message']);
        $unread = 0;

        // Function to get the real IP address of the visitor
        function getVisitorIP() {
            if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
                return $_SERVER['HTTP_CF_CONNECTING_IP'];
            } elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                return $_SERVER['HTTP_CLIENT_IP'];
            } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                return explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
            } else {
                return $_SERVER['REMOTE_ADDR'];
            }
        }

        // Get the visitor's IP
        $ip_response = getVisitorIP();

        // Check if IP was retrieved
        if (empty($ip_response)) {
            $response['message'] = "Failed to retrieve the visitor's IP address.";
            echo json_encode($response); // Ensure no extra output before this
            exit;
        }

        // Set the visitor's IP address
        $ip_address = $ip_response;

        // Insert data into database
        $sql = "INSERT INTO inquire (name, email, phone, message, ip_address, unread) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $name, $email, $phone, $message, $ip_address, $unread);

        if ($stmt->execute()) {
            // Prepare to send email using PHPMailer
            $mail = new PHPMailer(true);
            try {
                // Server settings
                $mail->isSMTP();
                $mail->SMTPDebug = SMTP::DEBUG_OFF;  // Disable debug output
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'quilbiolovelee58@gmail.com';  // SMTP username
                $mail->Password   = 'qzpxzgwdhvfbmpst';  // SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;  // SMTP port

                // Recipients
                $mail->setFrom($email, $name);  // Sender's email and name
                $mail->addAddress('quilbiolovelee58@gmail.com', 'Shemelle Apartment');  // Receiver email

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Reservation Request from Shemelle Apartment';
                $mail->Body    = '<p><strong>Name:</strong> ' . $name . '</p>' .
                                 '<p><strong>Email:</strong> ' . $email . '</p>' .
                                 '<p><strong>Phone:</strong> ' . $phone . '</p>' .
                                 '<p><strong>Message:</strong> ' . $message . '</p>';

                // Send the email
                if ($mail->send()) {
                    $response['status'] = 'success';
                    $response['message'] = 'Message has been sent successfully and data stored.';
                } else {
                    $response['message'] = 'Mailer Error: ' . $mail->ErrorInfo;
                }
            } catch (Exception $e) {
                // Catch any exceptions related to PHPMailer
                $response['message'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            // Database insertion failed
            $response['message'] = 'Database insertion failed: ' . $stmt->error;
        }
    } else {
        // Missing 'send-mail' parameter in POST request
        $response['message'] = 'Missing send-mail parameter';
    }
} else {
    // Invalid request method (not POST)
    $response['message'] = 'Invalid request method';
}

// Output the response as JSON
echo json_encode($response);
?>