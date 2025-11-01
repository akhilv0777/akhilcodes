<?php
require_once 'config.php';
require_once 'function.php';
require_once 'mail_config.php';

$user_ip = $_SERVER['REMOTE_ADDR'];
$today = date('Y-m-d');

/*
if (isset($_POST['contact_form']) && $_POST['contact_form'] == 'contact_form') {
    // Sanitize inputs
    $name = htmlspecialchars(trim($_POST['nameContact']));
    $email = filter_var(trim($_POST['emailContact']), FILTER_SANITIZE_EMAIL);
    $subject = htmlspecialchars(trim($_POST['subject']));
    $message = htmlspecialchars(trim($_POST['messageContact']));

    // Validate required fields
    $errors = [];
    if (empty($name)) $errors[] = "Name cannot be empty.";
    if (empty($email)) $errors[] = "Email cannot be empty.";
    if (empty($subject)) $errors[] = "Subject cannot be empty.";
    if (empty($message)) $errors[] = "Message cannot be empty.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email format.";

    if (!empty($errors)) {
        $_SESSION['err'] = implode(' ', $errors);
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    // Check submission limit
    $stmt = $conn->prepare("SELECT submission_count FROM contact_form WHERE date = ? AND user_ip = ?");
    $stmt->bind_param('ss', $today, $user_ip);
    $stmt->execute();
    $result = $stmt->get_result();
    $submission_count = ($result->num_rows > 0) ? $result->fetch_assoc()['submission_count'] : 0;

    if ($submission_count >= 5) {
        $_SESSION['err'] = "You have reached the submission limit for today. Please try again tomorrow.";
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    // Send acknowledgment email
    $mail = MailConfig::getMailer();
    $mail->addAddress($email, $name);
    $mail->Subject = "Thank You for Contacting Us - $subject";
    $mail->Body = "Hello $name,<br><br>Thank you for your message. We will respond soon.<br><br>Your message: $message";
    $mail->isHTML(true);
    $mail->send();

    // Notify admin
    $mail->clearAddresses();
    $mail->addAddress($user_data['email']);
    $mail->Subject = "New Contact Form Submission - $subject"; 
    $mail->Body = "New message from $name:<br>Email: $email<br>Subject: $subject<br>Message: $message";
    $mail->send();

    // Update submission count
    $new_submission_count = $submission_count + 1;
    if ($submission_count > 0) {
        $stmt = $conn->prepare("UPDATE contact_form SET submission_count = ? WHERE date = ? AND user_ip = ?");
        $stmt->bind_param('iss', $new_submission_count, $today, $user_ip);
    } else {
        $stmt = $conn->prepare("INSERT INTO contact_form (name, email, subject, message, date, user_ip, submission_count) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssssssi', $name, $email, $subject, $message, $today, $user_ip, $new_submission_count);
    }
    $stmt->execute();

    $_SESSION['msg'] = "Your message has been sent successfully!";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}
*/


if (isset($_POST['contact_delete']) && $_POST['contact_delete'] == 'contact_delete' && isset($_POST['selected_ids']) && !empty($_POST['selected_ids'])) {
    $selected_ids = $_POST['selected_ids'];
    $ids_to_delete = implode(',', $selected_ids);
    $query = "DELETE FROM $contacts_table WHERE id IN (?)";
    $result = $db->query($query, [$ids_to_delete]);
    sendResponse($result ? 'success' : 'error', null, $result ? 'Contacts deleted successfully.' : 'Failed to delete contacts.');
}


// if (isset($_POST['send_reply'])) {
//     $id = (int)$_POST['message_id'];
//     $sql = "SELECT subject FROM contact_form WHERE id = ?";
//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param("i", $id);
//     $stmt->execute();
//     $result = $stmt->get_result()->fetch_assoc();
//     $stmt->close();

//     $to = $_POST['to'];
//     $message = $_POST['message'];
//     $mail = MailConfig::getMailer();
    
//     try {
//         $mail->addAddress($to);
//         $mail->isHTML(true);
//         $mail->Subject = 'Reply: ' . (!empty($result['subject']) ? $result['subject'] : 'for your message');
//         $mail->Body = $message;

//         if (!empty($_FILES['files']['name'][0])) {
//             foreach ($_FILES['files']['name'] as $key => $fileName) {
//                 $fileTmpPath = $_FILES['files']['tmp_name'][$key];
//                 $newFileName = 'attachment' . ($key + 1) . '.' . pathinfo($fileName, PATHINFO_EXTENSION);
//                 if (move_uploaded_file($fileTmpPath, '../uploads/reply/' . $newFileName)) {
//                     $mail->addAttachment('../uploads/reply/' . $newFileName, $newFileName);
//                 } else {
//                     $_SESSION['err'] = "Error uploading file: $fileName";
//                     break;
//                 }
//             }
//         }
//         if ($mail->send()) {
//             $_SESSION['msg'] = "Reply sent!";
//         }
//     } catch (Exception $e) {
//         $_SESSION['err'] = "Mailer Error: {$mail->ErrorInfo}";
//     }
//     header('Location: ' . $_SERVER['HTTP_REFERER']);
//     exit();
// }
