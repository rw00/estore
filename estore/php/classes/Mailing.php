<?php
define("BASELINK", "http://localhost/store/");
define("ADMIN_EMAIL", "xxx@gmail.com"); // CHANGE EMAIL HERE
define("APP_NAME", "eSTORE");

function sendMail($to, $title, $body)
{
    require_once("php/libs/phpmailer/PHPMailerAutoload.php");
    $username = ADMIN_EMAIL;
    $password = ""; // CHANGE PASSWORD HERE
    $from = APP_NAME . " Support";

    $mail = new PHPMailer();
    // CHANGE PROPERTIES HERE IF NOT GMAIL!
    $mail->isSMTP();
    $mail->Host = "smtp.gmail.com";
    $mail->SMTPAuth = true;
    $mail->Username = $username;
    $mail->Password = $password;
    $mail->SMTPSecure = "tls";
    $mail->Port = 587;

    $mail->From = $username;
    $mail->FromName = $from;

    $mail->isHTML(true);
    $mail->Subject = $title;
    $mail->Body = $body;
    $mail->addAddress($to);

    if ($mail->send()) {
        return true;
    } else {
        return false;
    }
}

function sendActivationMail($to, $id, $code)
{
    return sendMail($to, APP_NAME . " Account Verification", "<p>Welcome to " . APP_NAME . "!</p><p>Please verify your account by clicking the link below:</p>" . BASELINK . "account_activation?id={$id}&code={$code}");
}

function sendResetMail($to, $id, $code)
{
    return sendMail($to, APP_NAME . " Reset Password", "<p>Dear User!</p><p>Click the following link to reset your forgotten password...</p>" . BASELINK . "reset_password?id={$id}&code={$code}");
}

?>
