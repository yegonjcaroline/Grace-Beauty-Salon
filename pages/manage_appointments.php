<?php
require_role(['admin']);
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $appointmentId = (int) ($_POST['appointment_id'] ?? 0);
    $salonistId = ($_POST['salonist_id'] ?? '') === '' ? null : (int) $_POST['salonist_id'];
    $status = $_POST['status'] ?? 'pending';

    if (in_array($status, ['pending','confirmed','completed','cancelled'], true)) {
        $stmt = $pdo->prepare('UPDATE appointments SET salonist_id=?, status=? WHERE id=?');
        $stmt->execute([$salonistId, $status, $appointmentId]);
        $message = 'Appointment updated.';
    }
}

$salonists = $pdo->query("SELECT id,full_name FROM users WHERE role='salonist' ORDER BY full_name")->fetchAll();
$rows = $pdo->query(
  "SELECT a.*, c.full_name AS customer, s.name AS service
   FROM appointments a
   JOIN users c ON c.id=a.customer_id
   JOIN services s ON s.id=a.service_id
   ORDER BY a.appointment_date DESC, a.appointment_time DESC"
)->fetchAll();
?>
<h1 class="text-2xl font-semibold mb-5">Manage appointments</h1>
<?php if ($message): ?><p class="mb-4 bg-green-50 text-green-800 p-3 rounded-xl"><?= e($message) ?></p><?php endif; ?>
<div class="space-y-4">
<?php foreach ($rows as $row): ?>
<form method="post" class="bg-white rounded-2xl p-4 shadow-sm flex flex-wrap gap-3 items-center">
  <input type="hidden" name="appointment_id" value="<?= (int)$row['id'] ?>">
  <div class="min-w-48 flex-1"><strong><?= e($row['customer']) ?></strong><br><span class="text-sm text-slate-500"><?= e($row['service']) ?> · <?= e($row['appointment_date']) ?> <?= e(substr($row['appointment_time'],0,5)) ?></span></div>
  <select name="salonist_id" class="border rounded-xl p-2">
    <option value="">Unassigned</option>
    <?php foreach ($salonists as $salonist): ?>
      <option value="<?= (int)$salonist['id'] ?>" <?= (int)$row['salonist_id'] === (int)$salonist['id'] ? 'selected' : '' ?>><?= e($salonist['full_name']) ?></option>
    <?php endforeach; ?>
  </select>
  <select name="status" class="border rounded-xl p-2">
    <?php foreach (['pending','confirmed','completed','cancelled'] as $status): ?>
      <option value="<?= e($status) ?>" <?= $row['status'] === $status ? 'selected' : '' ?>><?= e(ucfirst($status)) ?></option>
    <?php endforeach; ?>
  </select>
  <button class="bg-rose-400 text-white rounded-xl px-4 py-2">Save</button>
</form>
<?php endforeach; ?>
<?php if (!$rows): ?><p class="bg-white rounded-2xl p-5">No appointments have been booked yet.</p><?php endif; ?>
</div>