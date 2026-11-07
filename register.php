<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/config/mail.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['full_name'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || $phone === '' || strlen($password) < 8) {
    $error = 'Enter your name, a valid email, phone number, and a password of at least 8 characters.';
} elseif ($password !== $confirmPassword) {
    $error = 'The passwords do not match.';
} else {
        $check = $pdo->prepare('SELECT id FROM users WHERE email = ?');
        $check->execute([$email]);

        if ($check->fetch()) {
            $error = 'That email is already registered.';
        } else {
            $otp = (string) random_int(100000, 999999);
            $otpHash = password_hash($otp, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare(
                "INSERT INTO users (full_name,email,phone,password_hash,role,email_verified,otp_hash,otp_expires_at)
                 VALUES (?,?,?,?, 'customer',0,?,DATE_ADD(NOW(), INTERVAL 10 MINUTE))"
            );
            $stmt->execute([
                $name,
                $email,
                $phone,
                password_hash($password, PASSWORD_DEFAULT),
                $otpHash
            ]);

            if (sendOtpEmail($email, $name, $otp)) {
                $_SESSION['verify_email'] = $email;
                redirect('verify.php');
            }

            $error = 'The account was created, but the verification email could not be sent. Check your SMTP settings.';
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Create account | Grace Beauty Salon</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-rose-50 flex items-center justify-center p-4">
  <form method="post" class="bg-white w-full max-w-md rounded-3xl shadow-xl p-8 space-y-4">
    <p class="text-rose-500 uppercase tracking-[.25em] text-xs">Grace Beauty Salon</p>
    <h1 class="text-3xl font-semibold text-slate-800">Create your account</h1>
    <?php if ($error): ?><p class="text-red-700 bg-red-50 p-3 rounded-xl"><?= e($error) ?></p><?php endif; ?>
    <input required name="full_name" placeholder="Full name" class="w-full border rounded-xl p-3">
    <input required type="email" name="email" placeholder="Email" class="w-full border rounded-xl p-3">
    <input required name="phone" placeholder="Phone number" class="w-full border rounded-xl p-3">
   <div class="relative">
  <input required type="password" id="password" name="password" minlength="8"
         placeholder="Password (8+ characters)"
         class="w-full border rounded-xl p-3 pr-12">
  <button type="button" class="password-eye" data-target="password"
          aria-label="Show password" aria-pressed="false"
          style="position:absolute; right:12px; top:12px;">👁</button>
</div>

<div class="relative">
  <input required type="password" id="confirm_password" name="confirm_password"
         placeholder="Confirm password"
         class="w-full border rounded-xl p-3 pr-12">
  <button type="button" class="password-eye" data-target="confirm_password"
          aria-label="Show password" aria-pressed="false"
          style="position:absolute; right:12px; top:12px;">👁</button>
</div>
    <button class="w-full rounded-xl bg-rose-400 hover:bg-rose-500 text-white p-3">Register</button>
    <p class="text-sm text-slate-600">Already registered? <a class="text-rose-600" href="login.php">Log in</a></p>
  </form>
  <script>
document.querySelectorAll('.password-eye').forEach(function(button) {
  button.addEventListener('click', function() {
    const input = document.getElementById(button.dataset.target);
    const showPassword = input.type === 'password';

    input.type = showPassword ? 'text' : 'password';
    button.setAttribute('aria-label', showPassword ? 'Hide password' : 'Show password');
    button.setAttribute('aria-pressed', String(showPassword));
  });
});
</script>
</body>

</html>