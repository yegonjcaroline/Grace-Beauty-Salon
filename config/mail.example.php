<?php
declare(strict_types=1);

use PHPMailer\PHPMailer\PHPMailer;

require_once __DIR__ . '/../vendor/autoload.php';

function sendOtpEmail(string $email, string $name, string $otp): bool
{
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'grace_beauty_salon@gmail.com';
        $mail->Password = 'abcdefghijklmnop';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('grace_beauty_salon@gmail.com', 'Grace Beauty Salon');
        $mail->addAddress($email, $name);
        $mail->isHTML(true);
        $mail->Subject = 'Verify your Grace Beauty Salon account';
        $mail->Body = '<p>Hello ' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') .
            ',</p><p>Your verification code is <strong>' . $otp .
            '</strong>. It expires in 10 minutes.</p>';
        $mail->AltBody = "Your Grace Beauty Salon verification code is $otp. It expires in 5 minutes.";

        $mail->send();
        return true;
    } catch (Throwable $exception) {
        error_log('OTP email error: ' . $exception->getMessage());
        return false;
    }
}