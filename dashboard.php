<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/auth.php';
require_login();

$role = $_SESSION['role'];
$page = $_GET['page'] ?? 'home';

$pages = [
    'customer' => ['home' => 'customer_home.php', 'book' => 'book.php', 'appointments' => 'my_appointments.php'],
    'salonist' => ['home' => 'salonist_home.php', 'appointments' => 'salonist_appointments.php'],
    'admin' => ['home' => 'admin_home.php', 'salonists' => 'manage_salonists.php', 'customers' => 'manage_customers.php', 'appointments' => 'manage_appointments.php'],
];

if (!isset($pages[$role][$page])) {
    $page = 'home';
}
$contentFile = __DIR__ . '/pages/' . $pages[$role][$page];

$nav = [
    'customer' => ['home' => 'Dashboard', 'book' => 'Book an appointment', 'appointments' => 'My appointments'],
    'salonist' => ['home' => 'Dashboard', 'appointments' => 'Assigned appointments'],
    'admin' => ['home' => 'Dashboard', 'salonists' => 'Manage salonists', 'customers' => 'Customers', 'appointments' => 'Appointments'],
];
$roleLabel = ['customer' => 'Customer', 'salonist' => 'Salonist', 'admin' => 'Administrator'][$role] ?? 'Member';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e(ucfirst($page)) ?> | Grace Beauty Salon</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex flex-col bg-rose-50/70 text-slate-800">
<header class="sticky top-0 z-20 border-b border-rose-100 bg-white/95 shadow-sm backdrop-blur">
  <div class="mx-auto flex max-w-screen-2xl items-center justify-between gap-4 px-4 py-3 sm:px-6">
    <a href="dashboard.php" class="flex min-w-0 items-center gap-3" aria-label="Grace Beauty Salon dashboard">
      <span class="grid h-11 w-11 shrink-0 place-items-center rounded-2xl bg-rose-100 text-lg font-bold text-rose-600">G</span>
      <span class="min-w-0"><span class="block truncate text-lg font-semibold tracking-tight text-rose-700 sm:text-xl">Grace Beauty Salon</span><span class="hidden text-xs text-slate-500 sm:block">Beauty, thoughtfully booked.</span></span>
    </a>
    <div class="flex items-center gap-3 sm:gap-5">
      <div class="hidden text-right sm:block"><p class="text-sm font-medium">Welcome, <?= e($_SESSION['full_name']) ?></p><p class="text-xs text-slate-500"><?= e($roleLabel) ?></p></div>
      <a href="logout.php" class="rounded-xl bg-rose-500 px-3 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-rose-600 sm:px-4">Log out</a>
    </div>
  </div>
</header>
<div class="mx-auto flex w-full max-w-screen-2xl flex-1">
  <aside class="hidden w-64 shrink-0 border-r border-rose-100 bg-white p-4 md:flex md:flex-col">
    <p class="px-3 pb-3 pt-2 text-xs font-semibold uppercase tracking-[.16em] text-rose-400">Your salon</p>
    <nav class="flex-1 space-y-1" aria-label="Main navigation">
      <?php foreach ($nav[$role] as $key => $label): ?>
        <a href="dashboard.php?page=<?= e($key) ?>" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition <?= $page === $key ? 'bg-rose-100 text-rose-700' : 'text-slate-600 hover:bg-rose-50 hover:text-rose-700' ?>">
          <span class="grid h-8 w-8 place-items-center rounded-lg <?= $page === $key ? 'bg-white text-rose-500' : 'bg-rose-50 text-rose-400' ?>" aria-hidden="true"><?= ['home' => '⌂', 'book' => '+', 'appointments' => '▣', 'salonists' => '✿', 'customers' => '♡'][$key] ?? '•' ?></span>
          <span><?= e($label) ?></span>
        </a>
      <?php endforeach; ?>
    </nav>
    <div class="mt-6 rounded-2xl bg-rose-50 p-4 text-sm">
      <p class="font-semibold text-rose-700">A little time for you</p>
      <p class="mt-1 text-xs leading-5 text-slate-500">Your beauty appointments, all in one place.</p>
    </div>
  </aside>
  <div class="flex min-w-0 flex-1 flex-col">
    <nav class="sticky top-[68px] z-10 flex gap-2 overflow-x-auto border-b border-rose-100 bg-white px-3 py-2 md:hidden" aria-label="Mobile navigation">
      <?php foreach ($nav[$role] as $key => $label): ?>
        <a href="dashboard.php?page=<?= e($key) ?>" class="shrink-0 rounded-full px-4 py-2 text-sm <?= $page === $key ? 'bg-rose-100 font-medium text-rose-700' : 'text-slate-600 hover:bg-rose-50' ?>"><?= e($label) ?></a>
      <?php endforeach; ?>
    </nav>
    <main class="flex-1 p-4 sm:p-6 lg:p-8">
      <div class="mx-auto max-w-6xl"><?php require $contentFile; ?></div>
    </main>
    <footer class="border-t border-rose-100 bg-white px-4 py-4 text-center text-xs text-slate-500 sm:text-sm">© <?= date('Y') ?> Grace Beauty Salon <span class="mx-1 text-rose-300">·</span> Beauty, thoughtfully booked.</footer>
  </div>
</div>
</body>
</html>
