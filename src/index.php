<?php
session_start();
require_once __DIR__ . '/functions.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

    if (isset($_POST['unsubscribe_email'])) {
        $email = trim($_POST['unsubscribe_email']);
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $code = generateVerificationCode();
            $_SESSION['unsubscribe_code'] = $code;
            $_SESSION['unsubscribe_email'] = $email;
            sendVerificationEmail($email, $code);
            $message = "✅ Unsubscribe code sent to your email.";
        } else {
            $message = "❌ Invalid email format.";
        }
    }

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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>XKCD Comic Subscription</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;


            background: linear-gradient(135deg, #e0f7fa, #ffffff);
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            animation: fadeIn 1s ease-in;
        }

        @keyframes fadeIn {
            from {opacity: 0;}
            to {opacity: 1;}
        }

        h2 {
            margin-top: 40px;
            color: #00796b;
        }

        .container {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 90%;
            max-width: 500px;
            margin: 20px auto;
        }

        label {
            font-weight: 600;
            color: #333;
        }

        input[type="email"],
        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin: 8px 0 16px 0;
            border: 1px solid #ccc;
            border-radius: 6px;
            transition: 0.3s;
        }

        input:focus {
            border-color: #26a69a;
            outline: none;
        }

        button {
            background: #00796b;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #004d40;
        }

        .message {
            color: #00796b;
            font-weight: bold;
            margin: 10px 0;
        }

        hr {
            border: none;
            border-top: 1px solid #ccc;
            margin: 30px 0;
        }
    </style>
</head>
<body>

    <h2>📰 Subscribe to Daily XKCD Comics</h2>
    <div class="message"><?php echo $message; ?></div>

    <div class="container">
        <form method="POST">
            <label for="email">📧 Email:</label>
            <input type="email" name="email" placeholder="Enter your email..." required>
            <button type="submit">Send Verification Code</button>
        </form>

        <form method="POST">
            <label for="verify_code">🔐 Verification Code:</label>
            <input type="text" name="verify_code" maxlength="6" placeholder="Enter 6-digit code" required>
            <button type="submit">Verify & Subscribe</button>
        </form>
    </div>

    <h2>❌ Unsubscribe</h2>

    <div class="container">
        <form method="POST">
            <label for="unsubscribe_email">📧 Email:</label>
            <input type="email" name="unsubscribe_email" placeholder="Enter your email..." required>
            <button type="submit">Request Unsubscribe Code</button>
        </form>

        <form method="POST">
            <label for="unsubscribe_code">🔐 Unsubscribe Code:</label>
            <input type="text" name="unsubscribe_code" maxlength="6" placeholder="Enter 6-digit code" required>
            <button type="submit">Unsubscribe</button>
        </form>
    </div>

</body>
</html>
