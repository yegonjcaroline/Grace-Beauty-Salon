<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/helpers.php';

$error = '';
$flash = get_flash();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password_hash'])) {
        $error = 'Email or password is incorrect.';
    } elseif (!$user['email_verified'] && $user['role'] === 'customer') {
        $error = 'Please verify your email before logging in.';
    } else {
        session_regenerate_id(true);
        $_SESSION['user_id'] = (int) $user['id'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['role'] = $user['role'];
        redirect('dashboard.php');
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login | Grace Beauty Salon</title><script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-rose-50 flex items-center justify-center p-4">
  <form method="post" class="bg-white w-full max-w-md rounded-3xl shadow-xl p-8 space-y-4">
    <p class="text-rose-500 uppercase tracking-[.25em] text-xs">Grace Beauty Salon</p>
    <h1 class="text-3xl font-semibold text-slate-800">Welcome back</h1>
    <?php if ($flash): ?><p class="bg-green-50 text-green-800 p-3 rounded-xl"><?= e($flash) ?></p><?php endif; ?>
    <?php if ($error): ?><p class="bg-red-50 text-red-800 p-3 rounded-xl"><?= e($error) ?></p><?php endif; ?>
    <input required type="email" name="email" placeholder="Email" class="w-full border rounded-xl p-3">
    <div class="relative">
  
  <input required type="password" id="login_password" name="password"
         placeholder="Password" class="w-full border rounded-xl p-3 pr-12">
  <button type="button" class="password-eye" data-target="login_password"
          aria-label="Show password" aria-pressed="false"
          style="position:absolute; right:12px; top:12px;">👁</button>
</div>
    <button class="w-full rounded-xl bg-rose-400 hover:bg-rose-500 text-white p-3">Log in</button>
    <p class="text-sm text-slate-600">New customer? <a href="register.php" class="text-rose-600">Create an account</a></p>
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