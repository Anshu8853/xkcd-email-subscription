<?php
session_start();
require_once __DIR__ . '/functions.php';

if (!isset($_GET['email'])) {
    echo "<p style='color:red;'>Invalid request: no email provided.</p>";
    exit;
}

$email = $_GET['email'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $enteredCode = $_POST['code'] ?? '';
    if (verifyCode('unsubscribe_code', $enteredCode)) {
        unsubscribeEmail($email);
        echo "<p style='color:green;'>✅ You have been unsubscribed successfully.</p>";
        exit;
    } else {
        echo "<p style='color:red;'>❌ Incorrect code. Please try again.</p>";
    }
} else {
    // Generate and send code
    $code = generateVerificationCode();
    $_SESSION['unsubscribe_code'] = $code;
    sendUnsubscribeVerification($email, $code);
    echo "<p>We have sent a verification code to your email: <strong>$email</strong></p>";
}
?>

<form method="POST">
    <label for="code">Enter Verification Code:</label><br>
    <input type="text" name="code" id="code" maxlength="6" required><br><br>
    <button id="submit-verification">Unsubscribe</button>
</form>
