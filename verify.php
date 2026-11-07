<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/helpers.php';

$email = $_SESSION['verify_email'] ?? '';
$error = '';

if ($email === '') {
    redirect('register.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $otp = trim($_POST['otp'] ?? '');

    $stmt = $pdo->prepare('SELECT id, otp_hash FROM users WHERE email = ? AND email_verified = 0 AND otp_expires_at > NOW()');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($otp, $user['otp_hash'])) {
        $update = $pdo->prepare('UPDATE users SET email_verified=1, otp_hash=NULL, otp_expires_at=NULL WHERE id=?');
        $update->execute([$user['id']]);
        unset($_SESSION['verify_email']);
        set_flash('Email verified. You can now log in.');
        redirect('login.php');
    }

    $error = 'That code is invalid or expired. Register again or request a new code.';
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Verify email | Grace Beauty Salon</title><script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-rose-50 flex items-center justify-center p-4">
  <form method="post" class="bg-white w-full max-w-md rounded-3xl shadow-xl p-8 space-y-4">
    <h1 class="text-2xl font-semibold">Verify your email</h1>
    <p class="text-slate-600">Enter the six-digit code sent to <?= e($email) ?>.</p>
    <?php if ($error): ?><p class="text-red-700 bg-red-50 p-3 rounded-xl"><?= e($error) ?></p><?php endif; ?>
    <input required name="otp" inputmode="numeric" pattern="[0-9]{6}" maxlength="6" placeholder="Six-digit code" class="w-full border rounded-xl p-3">
    <button class="w-full rounded-xl bg-rose-400 text-white p-3">Verify email</button>
  </form>
</body>
</html>