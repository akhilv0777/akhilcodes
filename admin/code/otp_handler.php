<?php
session_start();
include 'db_connection.php';
require '../../mail_config.php';

$mail = MailConfig::getMailer();
header('Content-Type: application/json');
$response = ['status' => 'error', 'message' => 'Invalid action'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {
    $email = $_POST['email'] ?? null;
    $action = $_POST['action'];
    $response = ['status' => 'error', 'message' => 'Missing parameters'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response = ['status' => 'error', 'message' => 'Invalid email format'];
    } else {
        $email = $conn->real_escape_string($email);
        $sql = "SELECT * FROM user WHERE email = '$email'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $otp_date = strtotime($user['otp_date']);
            $current_time = time();
            if ($action == 'send_otp' || $action == 'resend_otp') {
                if ($current_time - $otp_date < 300) {
                    $response = ['status' => 'error', 'message' => 'Wait before requesting OTP again'];
                } else {
                    $otp = rand(100000, 999999);
                    $otp_count = $user['otp_count'] + 1;
                    $update_sql = "UPDATE user SET otp = '$otp', otp_date = NOW(), otp_count = '$otp_count' WHERE email = '$email'";
                    if ($conn->query($update_sql)) {
                        try {
                            $mail->addAddress($email, 'User');
                            $mail->isHTML(true);
                            $mail->Subject = 'OTP';
                            $mail->Body = 'Your OTP is ' . $otp;
                            $mail->send();
                            $response = ['status' => 'success', 'message' => 'OTP sent'];
                        } catch (Exception $e) {
                            $response = ['status' => 'error', 'message' => 'Mailer Error: ' . $mail->ErrorInfo];
                        }
                    } else {
                        $response = ['status' => 'error', 'message' => 'Failed to update OTP'];
                    }
                }
            } elseif ($action == 'verify_otp' && isset($_POST['otp'])) {
                $otp = $_POST['otp'];
                if ($user['otp'] == $otp && $current_time - $otp_date <= 300) {
                    $response = ['status' => 'success', 'message' => 'OTP verified'];
                } else {
                    $response = ['status' => 'error', 'message' => 'Invalid or expired OTP'];
                }
            } elseif ($action == 'reset_password' && isset($_POST['new_password'])) {
                $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
                $update_sql = "UPDATE user SET password = '$new_password' WHERE email = '$email'";
                if ($conn->query($update_sql)) {
                    try {
                        $mail->addAddress($email, 'User');
                        $mail->isHTML(true);
                        $mail->Subject = 'Password Reset';
                        $mail->Body = 'Your password has been reset';
                        $mail->send();
                        $response = ['status' => 'success', 'message' => 'Password reset successfully'];
                    } catch (Exception $e) {
                        $response = ['status' => 'error', 'message' => 'Mailer Error: ' . $mail->ErrorInfo];
                    }
                } else {
                    $response = ['status' => 'error', 'message' => 'Failed to reset password'];
                }
            }
        } else {
            $response = ['status' => 'error', 'message' => 'Email not found'];
        }
    }
}

echo json_encode($response);
$conn->close();
