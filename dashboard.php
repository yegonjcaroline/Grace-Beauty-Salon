<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/auth.php';
require_login();

$role = $_SESSION['role'];
$page = $_GET['page'] ?? 'home';

$pages = [
    'customer' => [
        'home' => 'customer_home.php',
        'book' => 'book.php',
        'appointments' => 'my_appointments.php',
    ],
    'salonist' => [
        'home' => 'salonist_home.php',
        'appointments' => 'salonist_appointments.php',
    ],
    'admin' => [
        'home' => 'admin_home.php',
        'salonists' => 'manage_salonists.php',
        'customers' => 'manage_customers.php',
        'appointments' => 'manage_appointments.php',
    ],
];

if (!isset($pages[$role][$page])) {
    $page = 'home';
}
$contentFile = __DIR__ . '/pages/' . $pages[$role][$page];

$nav = [
    'customer' => ['home'=>'Dashboard', 'book'=>'Book an appointment', 'appointments'=>'My appointments'],
    'salonist' => ['home'=>'Dashboard', 'appointments'=>'Assigned appointments'],
    'admin' => ['home'=>'Dashboard', 'salonists'=>'Manage salonists', 'customers'=>'Customers', 'appointments'=>'Appointments'],
];
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Grace Beauty Salon</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-rose-50 text-slate-800 min-h-screen flex flex-col">
<header class="bg-white border-b border-rose-100 px-5 py-4 flex justify-between items-center">
  <a href="dashboard.php" class="font-semibold text-xl text-rose-600">Grace <span class="font-light">Beauty Salon</span></a>
  <div class="text-sm"><?= e($_SESSION['full_name']) ?> · <?= e(ucfirst($role)) ?> <a class="ml-3 text-rose-600" href="logout.php">Log out</a></div>
</header>
<nav class="md:hidden bg-white border-b border-rose-100 px-3 py-2 flex gap-2 overflow-x-auto">
  <?php foreach ($nav[$role] as $key => $label): ?>
    <a href="dashboard.php?page=<?= e($key) ?>"
       class="shrink-0 rounded-full px-4 py-2 text-sm <?= $page === $key ? 'bg-rose-100 text-rose-700' : 'hover:bg-rose-50' ?>">
      <?= e($label) ?>
    </a>
  <?php endforeach; ?>
</nav>
<div class="flex flex-1">
  <aside class="w-60 bg-white border-r border-rose-100 p-4 hidden md:flex md:flex-col">
  <nav class="space-y-2 flex-1">
    <?php foreach ($nav[$role] as $key => $label): ?>
      <a href="dashboard.php?page=<?= e($key) ?>"
         class="block rounded-xl px-4 py-3 <?= $page === $key ? 'bg-rose-100 text-rose-700' : 'hover:bg-rose-50' ?>">
        <?= e($label) ?>
      </a>
    <?php endforeach; ?>
  </nav>

  <div class="mt-6 border-t border-rose-100 pt-4 text-xs text-slate-500">
    <p class="font-medium text-rose-600">Grace Beauty Salon</p>
    <p class="mt-1">Beauty, thoughtfully booked.</p>
    <p class="mt-3">© <?= date('Y') ?></p>
  </div>
</aside>

  <main class="flex-1 p-5 md:p-8">
    <?php require $contentFile; ?>
  </main>
</div>

<footer class="bg-white border-t border-rose-100 text-center text-sm text-slate-500 p-4">
  © <?= date('Y') ?> Grace Beauty Salon · Beauty, thoughtfully booked.
</footer>
</body>
</html>