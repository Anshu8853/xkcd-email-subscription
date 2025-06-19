<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Generates a 6-digit verification code
function generateVerificationCode() {
    return rand(100000, 999999);
}

// Registers the email if not already present
function registerEmail($email) {
    $file = __DIR__ . '/../registered_emails.txt';
    $emails = file_exists($file) ? file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];

    if (!in_array($email, $emails)) {
        file_put_contents($file, $email . PHP_EOL, FILE_APPEND);
    }
}

// Sends the verification code to email for subscription
function sendVerificationEmail($email, $code) {
    $subject = "Your Verification Code";
    $message = "<p>Your verification code is: <strong>$code</strong></p>";
    $headers = "From: no-reply@example.com\r\n";
    $headers .= "Content-type: text/html\r\n";
    mail($email, $subject, $message, $headers);
}

// Sends the verification code to email for unsubscribe
function sendUnsubscribeVerification($email, $code) {
    $subject = "Confirm Un-subscription";
    $message = "<p>To confirm un-subscription, use this code: <strong>$code</strong></p>";
    $headers = "From: no-reply@example.com\r\n";
    $headers .= "Content-type: text/html\r\n";
    mail($email, $subject, $message, $headers);
}



// Verifies the code
function verifyCode($sessionKey, $code) {
    return isset($_SESSION[$sessionKey]) && $_SESSION[$sessionKey] == $code;
}

// Fetches and formats a random XKCD comic
function fetchAndFormatXKCDData() {
    $randomId = rand(1, 3000);
    $url = "https://xkcd.com/$randomId/info.0.json";
    $data = json_decode(file_get_contents($url), true);
    $img = $data['img'] ?? '';
    $title = $data['title'] ?? 'XKCD Comic';

    return "<h2>$title</h2>
            <img src=\"$img\" alt=\"XKCD Comic\">";
}

// Unsubscribes the given email
function unsubscribeEmail($email) {
    $file = __DIR__ . '/../registered_emails.txt';
    if (!file_exists($file)) return;

    $emails = array_map('trim', file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES));
    $email = trim($email);
    $emails = array_filter($emails, fn($e) => $e !== $email);
    file_put_contents($file, implode(PHP_EOL, $emails) . PHP_EOL);
}

// Sends XKCD comic with dynamic unsubscribe link
function sendXKCDUpdatesToSubscribers() {
    $file = __DIR__ . '/../registered_emails.txt';
    if (!file_exists($file)) return;

    $emails = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $comicHTML = fetchAndFormatXKCDData();
    $subject = "Your XKCD Comic";
    $headers = "From: no-reply@example.com\r\n";
    $headers .= "Content-type: text/html\r\n";

    foreach ($emails as $email) {
        $email = trim($email);
        $unsubscribeLink = "http://localhost/xkcd-email-subscription/src/unsubscribe.php?email=" . urlencode($email);




        $message = $comicHTML . "<p><a href='$unsubscribeLink'>Unsubscribe</a></p>";
        mail($email, $subject, $message, $headers);
    }
}
?>
