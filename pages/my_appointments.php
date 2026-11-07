<?php
require_role(['customer']);

$stmt = $pdo->prepare(
  "SELECT a.*, s.name AS service, u.full_name AS salonist
   FROM appointments a
   JOIN services s ON s.id=a.service_id
   LEFT JOIN users u ON u.id=a.salonist_id
   WHERE a.customer_id=?
   ORDER BY a.appointment_date DESC, a.appointment_time DESC"
);
$stmt->execute([$_SESSION['user_id']]);
$rows = $stmt->fetchAll();
?>
<h1 class="text-2xl font-semibold mb-5">My appointments</h1>
<div class="bg-white rounded-3xl p-5 shadow-sm overflow-x-auto">
<table class="w-full text-left text-sm">
<thead><tr class="border-b text-slate-500"><th class="p-3">Service</th><th>Date</th><th>Time</th><th>Salonist</th><th>Status</th></tr></thead>
<tbody>
<?php foreach ($rows as $row): ?>
<tr class="border-b border-rose-50">
<td class="p-3"><?= e($row['service']) ?></td><td><?= e($row['appointment_date']) ?></td>
<td><?= e(substr($row['appointment_time'],0,5)) ?></td><td><?= e($row['salonist'] ?? 'To be assigned') ?></td><td><?= e(ucfirst($row['status'])) ?></td>
</tr>
<?php endforeach; ?>
<?php if (!$rows): ?><tr><td class="p-3" colspan="5">No appointments yet.</td></tr><?php endif; ?>
</tbody></table></div>