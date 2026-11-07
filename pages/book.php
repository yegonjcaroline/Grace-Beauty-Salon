<?php
require_role(['customer']);

$error = '';
$services = $pdo->query('SELECT id,name,price FROM services WHERE active=1 ORDER BY name')->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $serviceId = (int) ($_POST['service_id'] ?? 0);
    $date = $_POST['appointment_date'] ?? '';
    $time = $_POST['appointment_time'] ?? '';
    $notes = trim($_POST['notes'] ?? '');

    $validDate = DateTime::createFromFormat('Y-m-d', $date);
    if (!$serviceId || !$validDate || $date < date('Y-m-d') || !$time) {
        $error = 'Choose a valid service, today or a future date, and a time.';
    } else {
        $stmt = $pdo->prepare('SELECT id FROM services WHERE id=? AND active=1');
        $stmt->execute([$serviceId]);
        if (!$stmt->fetch()) {
            $error = 'Please choose a listed service.';
        } else {
            $stmt = $pdo->prepare(
                "INSERT INTO appointments (customer_id,service_id,appointment_date,appointment_time,notes)
                 VALUES (?,?,?,?,?)"
            );
            $stmt->execute([$_SESSION['user_id'], $serviceId, $date, $time, $notes ?: null]);
            echo '<div class="mb-4 p-3 rounded-xl bg-green-50 text-green-800">Appointment request sent.</div>';
        }
    }
}
?>
<h1 class="text-2xl font-semibold mb-5">Book an appointment</h1>
<?php if ($error): ?><p class="mb-4 bg-red-50 text-red-800 p-3 rounded-xl"><?= e($error) ?></p><?php endif; ?>
<form method="post" class="max-w-xl bg-white rounded-3xl p-6 shadow-sm space-y-4" onsubmit="return validateBooking()">
  <label class="block">Salon service
    <select required name="service_id" class="mt-1 w-full border rounded-xl p-3">
      <option value="">Choose a service</option>
      <?php foreach ($services as $service): ?>
        <option value="<?= (int)$service['id'] ?>"><?= e($service['name']) ?> — KSh <?= number_format((float)$service['price'], 2) ?></option>
      <?php endforeach; ?>
    </select>
  </label>
  <label class="block">Preferred date
    <input required type="date" name="appointment_date" min="<?= date('Y-m-d') ?>" class="mt-1 w-full border rounded-xl p-3">
  </label>
  <label class="block">Preferred time
    <input required type="time" name="appointment_time" class="mt-1 w-full border rounded-xl p-3">
  </label>
  <label class="block">Additional notes (optional)
    <textarea name="notes" maxlength="1000" rows="3" class="mt-1 w-full border rounded-xl p-3"></textarea>
  </label>
  <button class="rounded-xl bg-rose-400 text-white px-5 py-3">Request booking</button>
</form>
<script>
function validateBooking() {
  const date = document.querySelector('[name="appointment_date"]').value;
  if (date < new Date().toISOString().slice(0, 10)) {
    alert('Please choose today or a future date.');
    return false;
  }
  return true;
}
</script>