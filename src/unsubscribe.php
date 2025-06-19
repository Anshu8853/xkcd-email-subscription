<?php
session_start();
require_once __DIR__ . '/functions.php';

$message = "";
$messageClass = "";

if (!isset($_GET['email']) || !filter_var($_GET['email'], FILTER_VALIDATE_EMAIL)) {
    echo "<p style='color:red;'>❌ Invalid request: no email provided.</p>";
    exit;
}

$email = $_GET['email'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $enteredCode = $_POST['code'] ?? '';
    if (verifyCode('unsubscribe_code', $enteredCode)) {
        unsubscribeEmail($email);
        $message = "✅ You have been unsubscribed successfully.";
        $messageClass = "text-green-600";
        unset($_SESSION['unsubscribe_code']);
    } else {
        $message = "❌ Incorrect code. Please try again.";
        $messageClass = "text-red-600";
    }
} else {
    // Generate and send code only on GET
    $code = generateVerificationCode();
    $_SESSION['unsubscribe_code'] = $code;
    sendUnsubscribeVerification($email, $code);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Unsubscribe - XKCD Comics</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center px-4">
  <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-md text-center">
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Unsubscribe from XKCD Comics</h1>

    <?php if ($message): ?>
      <p class="mb-4 font-medium <?php echo $messageClass; ?>"><?php echo $message; ?></p>
    <?php else: ?>
      <p class="text-sm text-gray-600 mb-6">
        We have sent a verification code to your email:<br>
        <span class="font-semibold text-black"><?php echo htmlspecialchars($email); ?></span>
      </p>
    <?php endif; ?>

    <?php if (!$message || $messageClass === "text-red-600"): ?>
    <form method="POST" class="space-y-4 text-left">
      <label for="code" class="block text-sm font-medium text-gray-700">Enter Verification Code:</label>
      <input type="text" name="code" id="code" maxlength="6" required
             class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" />

      <button type="submit"
              class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-md transition duration-200">
        Unsubscribe
      </button>
    </form>
    <?php endif; ?>

    <p class="mt-6 text-sm text-gray-400">Still want to keep getting XKCD comics? Just ignore this page.</p>
  </div>
</body>
</html>
