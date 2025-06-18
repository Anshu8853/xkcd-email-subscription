<?php
session_start();
require_once __DIR__ . '/functions.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Step 1: Subscribe email
    if (isset($_POST['email'])) {
        $email = trim($_POST['email']);
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $code = generateVerificationCode();
            $_SESSION['verification_code'] = $code;
            $_SESSION['email'] = $email;
            sendVerificationEmail($email, $code);
            $message = "✅ Verification code sent to your email.";
        } else {
            $message = "❌ Invalid email format.";
        }
    }

    // Step 2: Verify subscription code
    if (isset($_POST['verify_code'])) {
        $code = trim($_POST['verify_code']);
        if (isset($_SESSION['email'], $_SESSION['verification_code'])) {
            if ($_SESSION['verification_code'] == $code) {
                registerEmail($_SESSION['email']);
                $message = "✅ Your email has been verified and subscribed.";
                unset($_SESSION['verification_code'], $_SESSION['email']);
            } else {
                $message = "❌ Invalid verification code.";
            }
        } else {
            $message = "❌ Please submit your email first.";
        }
    }

    // Step 3: Start unsubscribe process
    if (isset($_POST['unsubscribe_email'])) {
        $email = trim($_POST['unsubscribe_email']);
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $code = generateVerificationCode();
            $_SESSION['unsubscribe_code'] = $code;
            $_SESSION['unsubscribe_email'] = $email;
            sendVerificationEmail($email, $code); // Reuse same function
            $message = "✅ Unsubscribe code sent to your email.";
        } else {
            $message = "❌ Invalid email format.";
        }
    }

    // Step 4: Unsubscribe code verification
    if (isset($_POST['unsubscribe_code'])) {
        $code = trim($_POST['unsubscribe_code']);
        if (isset($_SESSION['unsubscribe_email'], $_SESSION['unsubscribe_code'])) {
            if ($_SESSION['unsubscribe_code'] == $code) {
                unsubscribeEmail($_SESSION['unsubscribe_email']);
                $message = "✅ You have been unsubscribed.";
                unset($_SESSION['unsubscribe_code'], $_SESSION['unsubscribe_email']);
            } else {
                $message = "❌ Invalid unsubscribe code.";
            }
        } else {
            $message = "❌ Please request an unsubscribe code first.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>XKCD Comic Subscription</title>
</head>
<body>
    <h2>Subscribe to Daily XKCD Comics</h2>
    <p style="color: green;"><?php echo $message; ?></p>

    <!-- Subscribe Form -->
    <form method="POST">
        <label for="email">Email:</label><br>
        <input type="email" name="email" required>
        <button type="submit">Submit</button>
    </form>
    <br>

    <!-- Verification for Subscription -->
    <form method="POST">
        <label for="verify_code">Verification Code:</label><br>
        <input type="text" name="verify_code" maxlength="6" required>
        <button type="submit">Verify</button>
    </form>

    <hr>

    <h2>Unsubscribe from XKCD Comics</h2>

    <!-- Unsubscribe Form -->
    <form method="POST">
        <label for="unsubscribe_email">Email:</label><br>
        <input type="email" name="unsubscribe_email" required>
        <button type="submit">Unsubscribe</button>
    </form>
    <br>

    <!-- Unsubscribe Verification -->
    <form method="POST">
        <label for="unsubscribe_code">Verification Code:</label><br>
        <input type="text" name="unsubscribe_code" maxlength="6" required>
        <button type="submit">Verify</button>
    </form>
</body>
</html>
