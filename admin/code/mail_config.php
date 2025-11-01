<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';
class MailConfig
{
    public static function getMailer()
    {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'akhilv0777@gmail.com';
            $mail->Password = 'bxgbbrpphaddoscz';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
            $mail->Port = 587;
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            $mail->setFrom('akhilv0777@gmail.com', 'Akhil');
            return $mail;
        } catch (Exception $e) {
            echo "Mailer configuration failed: {$e->getMessage()}";
            exit;
        }
    }
}
?>