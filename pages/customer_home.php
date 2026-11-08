<?php
require_role(['customer']);
$customerId = (int) $_SESSION['user_id'];
$statsQuery = $pdo->prepare("SELECT COUNT(*) AS total, SUM(status = 'pending') AS pending, SUM(status IN ('pending','confirmed') AND appointment_date >= CURDATE()) AS upcoming FROM appointments WHERE customer_id = :customer_id");
$statsQuery->execute(['customer_id' => $customerId]);
$stats = $statsQuery->fetch() ?: ['total' => 0, 'pending' => 0, 'upcoming' => 0];
$appointmentsQuery = $pdo->prepare("SELECT a.appointment_date, a.appointment_time, a.status, s.name AS service_name, u.full_name AS salonist_name FROM appointments a JOIN services s ON s.id = a.service_id LEFT JOIN users u ON u.id = a.salonist_id WHERE a.customer_id = :customer_id AND a.status <> 'cancelled' AND a.appointment_date >= CURDATE() ORDER BY a.appointment_date, a.appointment_time LIMIT 5");
$appointmentsQuery->execute(['customer_id' => $customerId]);
$upcomingAppointments = $appointmentsQuery->fetchAll();
?>
<section class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
  <div><p class="text-xs font-semibold uppercase tracking-[.18em] text-rose-500">Your beauty space</p><h1 class="mt-2 text-2xl font-semibold tracking-tight sm:text-3xl">Good day, <?= e($_SESSION['full_name']) ?></h1><p class="mt-2 text-sm text-slate-500">Plan your next moment of self-care.</p></div>
  <a href="dashboard.php?page=book" class="inline-flex items-center justify-center gap-2 rounded-xl bg-rose-500 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-rose-600"><span class="text-lg leading-none">+</span> Book an appointment</a>
</section>
<div class="mb-6 flex items-center gap-3 rounded-2xl border border-rose-100 bg-white px-4 py-3 text-sm shadow-sm"><span class="grid h-9 w-9 place-items-center rounded-xl bg-rose-50 text-rose-500">✿</span><p class="text-slate-600">Ready for a little refresh? Your next salon visit is just a few clicks away.</p><a href="dashboard.php?page=book" class="ml-auto shrink-0 font-medium text-rose-600 hover:text-rose-700">Book now</a></div>
<section class="mb-6 grid gap-4 sm:grid-cols-3" aria-label="Appointment summary">
  <?php foreach ([['All appointments', (int) $stats['total'], '▣'], ['Awaiting confirmation', (int) $stats['pending'], '◷'], ['Upcoming visits', (int) $stats['upcoming'], '♡']] as [$label, $value, $icon]): ?>
  <article class="flex items-center gap-4 rounded-2xl border border-rose-100 bg-white p-5 shadow-sm"><span class="grid h-12 w-12 place-items-center rounded-2xl bg-rose-50 text-xl text-rose-500"><?= e($icon) ?></span><div><p class="text-sm text-slate-500"><?= e($label) ?></p><p class="mt-1 text-2xl font-semibold text-slate-800"><?= $value ?></p></div></article>
  <?php endforeach; ?>
</section>
<section class="rounded-2xl border border-rose-100 bg-white p-5 shadow-sm sm:p-6">
  <div class="mb-4 flex items-center justify-between gap-3"><div><h2 class="text-lg font-semibold">Your upcoming appointments</h2><p class="mt-1 text-sm text-slate-500">A quick look at what you have booked.</p></div><a href="dashboard.php?page=appointments" class="shrink-0 text-sm font-medium text-rose-600 hover:text-rose-700">View all</a></div>
  <?php if (!$upcomingAppointments): ?><div class="rounded-xl bg-rose-50/70 px-4 py-8 text-center"><p class="font-medium text-slate-700">No upcoming appointments yet</p><p class="mt-1 text-sm text-slate-500">Choose a service and find a time that works for you.</p><a href="dashboard.php?page=book" class="mt-4 inline-flex rounded-lg bg-white px-4 py-2 text-sm font-medium text-rose-600 shadow-sm ring-1 ring-rose-100 hover:bg-rose-50">Book your first visit</a></div>
  <?php else: ?><div class="divide-y divide-rose-50">
    <?php foreach ($upcomingAppointments as $appointment): ?><article class="flex flex-col gap-3 py-4 sm:flex-row sm:items-center sm:justify-between"><div class="flex items-start gap-3"><span class="mt-1 grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-rose-50 text-rose-500">♡</span><div><p class="font-medium"><?= e($appointment['service_name']) ?></p><p class="mt-1 text-sm text-slate-500"><?= e(date('D, j M Y', strtotime($appointment['appointment_date']))) ?> at <?= e(date('g:i A', strtotime($appointment['appointment_time']))) ?><?php if (!empty($appointment['salonist_name'])): ?> · <?= e($appointment['salonist_name']) ?><?php endif; ?></p></div></div><span class="w-fit rounded-full bg-rose-50 px-3 py-1 text-xs font-medium capitalize text-rose-700"><?= e($appointment['status']) ?></span></article><?php endforeach; ?>
  </div><?php endif; ?>
</section>
