<?php
require_role(['salonist']);

$stmt = $pdo->prepare(
  "SELECT a.*, s.name AS service, c.full_name AS customer, c.phone
   FROM appointments a
   JOIN services s ON s.id=a.service_id
   JOIN users c ON c.id=a.customer_id
   WHERE a.salonist_id=?
   ORDER BY a.appointment_date, a.appointment_time"
);
$stmt->execute([$_SESSION['user_id']]);
$rows = $stmt->fetchAll();
?>
<h1 class="text-2xl font-semibold mb-5">Assigned appointments</h1>
<div class="bg-white rounded-3xl p-5 shadow-sm overflow-x-auto">
<table class="w-full text-left text-sm">
<thead><tr class="border-b text-slate-500"><th class="p-3">Customer</th><th>Phone</th><th>Service</th><th>Date</th><th>Time</th><th>Status</th></tr></thead>
<tbody>
<?php foreach ($rows as $row): ?>
<tr class="border-b border-rose-50"><td class="p-3"><?= e($row['customer']) ?></td><td><?= e($row['phone']) ?></td>
<td><?= e($row['service']) ?></td><td><?= e($row['appointment_date']) ?></td><td><?= e(substr($row['appointment_time'],0,5)) ?></td><td><?= e(ucfirst($row['status'])) ?></td></tr>
<?php endforeach; ?>
<?php if (!$rows): ?><tr><td class="p-3" colspan="6">No appointments assigned yet.</td></tr><?php endif; ?>
</tbody></table></div>