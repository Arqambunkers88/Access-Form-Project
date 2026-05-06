<?php
require_once 'includes/db_connection.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);

    $stmt = $pdo->prepare("SELECT user_id FROM user WHERE email = :email");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $token = bin2hex(random_bytes(32));
        $expire = date("Y-m-d H:i:s", strtotime('+1 hour'));

        $update = $pdo->prepare("UPDATE user SET reset_token = :token, token_expire = :expire WHERE email = :email");
        $update->execute([':token' => $token, ':expire' => $expire, ':email' => $email]);

        $reset_link = "https://accessform.great-site.net/reset_password.php?token=" . $token;

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp-relay.brevo.com';       // ← Brevo SMTP
            $mail->SMTPAuth   = true;
            $mail->Username   = 'aa236f001@smtp-brevo.com'; // ← Brevo login email
            $mail->Password   = 'xsmtpsib-4029cbf1b5bf08f8bfef270a470eff9714a6acc12d4ccdbb8179fc49d6db0b56-Hi8yOyA4ft1EvI0c';    // ← Brevo SMTP key
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('aa236f001@smtp-brevo.com', 'Access Form System');
            $mail->addAddress($email);
            $mail->Subject = 'Password Reset - Access Form';
            $mail->isHTML(true);
            $mail->Body = "
                <div style='font-family:Arial,sans-serif;max-width:500px;margin:auto;'>
                    <h3 style='color:#333;'>Password Reset Request</h3>
                    <p>You have requested to reset your password.</p>
                    <p>Click the button below. This link will expire in <strong>1 hour</strong>.</p>
                    <a href='$reset_link'
                       style='display:inline-block;padding:12px 24px;background:#007bff;color:#fff;
                              text-decoration:none;border-radius:5px;margin:16px 0;'>
                        Reset Password
                    </a>
                    <p style='color:#888;font-size:12px;'>
                        If you did not request this, please ignore this email.
                    </p>
                </div>
            ";

            $mail->send();

            $msg = "<div style='background-color:#d4edda;color:#155724;padding:15px;
                                border-radius:5px;margin-bottom:20px;font-size:14px;'>
                        ✅ Password reset link has been sent to your email. Please check your inbox.
                    </div>";

        } catch (Exception $e) {
            $msg = "<div style='background-color:#f8d7da;color:#721c24;padding:10px;
                                border-radius:5px;margin-bottom:15px;'>
                        Email could not be sent. Error: {$mail->ErrorInfo}
                    </div>";
        }

    } else {
        $msg = "<div style='background-color:#f8d7da;color:#721c24;padding:10px;
                            border-radius:5px;margin-bottom:15px;text-align:center;'>
                    No account found with this email address.
                </div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Access Form</title>
    <link rel="stylesheet" href="assets/css/style.css?v=65">
</head>
<body>
    <main class="auth-card" role="main" aria-labelledby="forgot-title">
        <div style="text-align: center; margin-bottom: 20px;">
            <img src="assets/images/access_form_logo.png" alt="Access Form System Logo"
                 style="width: 120px; height: auto;" tabindex="0">
        </div>
        <h2 id="forgot-title">Reset Password</h2>
        <p style="font-size: 14px; color: var(--text-color); margin-bottom: 20px;">
            Enter your registered email address and we will send a reset link.
        </p>

        <?php echo $msg; ?>

        <form action="" method="POST">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" required
                   aria-required="true" aria-label="Email Address">
            <button type="submit" class="btn" style="margin-top: 20px;">Send Reset Link</button>
        </form>
        <p class="helper-text">
            Remember your password?
            <a href="login.php" style="color: var(--primary-color);">Login here</a>.
        </p>
    </main>
    <script src="assets/js/accessibility.js?v=65"></script>
</body>
</html>