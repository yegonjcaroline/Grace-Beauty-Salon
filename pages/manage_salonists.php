<?php
require_role(['admin']);
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['full_name'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($name && filter_var($email, FILTER_VALIDATE_EMAIL) && $phone && strlen($password) >= 8) {
        try {
            $stmt = $pdo->prepare(
                "INSERT INTO users (full_name,email,phone,password_hash,role,email_verified)
                 VALUES (?,?,?,?,'salonist',1)"
            );
            $stmt->execute([$name,$email,$phone,password_hash($password,PASSWORD_DEFAULT)]);
            $message = 'Salonist account created.';
        } catch (PDOException $e) {
            $message = 'Could not create account. Check whether the email is already in use.';
        }
    } else {
        $message = 'Fill all fields and use a password of at least 8 characters.';
    }
}

$rows = $pdo->query("SELECT id,full_name,email,phone FROM users WHERE role='salonist' ORDER BY full_name")->fetchAll();
?>
<h1 class="text-2xl font-semibold mb-5">Manage salonists</h1>
<?php if ($message): ?><p class="mb-4 bg-rose-100 p-3 rounded-xl"><?= e($message) ?></p><?php endif; ?>
<form method="post" class="bg-white rounded-3xl p-6 shadow-sm max-w-xl grid gap-3 md:grid-cols-2">
  <input required name="full_name" placeholder="Full name" class="border rounded-xl p-3">
  <input required type="email" name="email" placeholder="Email" class="border rounded-xl p-3">
  <input required name="phone" placeholder="Phone" class="border rounded-xl p-3">
  <input required type="password" minlength="8" name="password" placeholder="Temporary password" class="border rounded-xl p-3">
  <button class="md:col-span-2 bg-rose-400 text-white rounded-xl p-3">Create salonist</button>
</form>
<h2 class="text-xl font-semibold mt-8 mb-3">Salonist accounts</h2>
<div class="bg-white rounded-2xl p-4">
<?php foreach ($rows as $row): ?><p class="border-b p-3"><?= e($row['full_name']) ?> · <?= e($row['email']) ?> · <?= e($row['phone']) ?></p><?php endforeach; ?>
</div>