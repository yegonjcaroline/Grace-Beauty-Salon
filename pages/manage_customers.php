<?php
require_role(['admin']);
$rows = $pdo->query("SELECT id,full_name,email,phone,created_at FROM users WHERE role='customer' ORDER BY created_at DESC")->fetchAll();
?>
<h1 class="text-2xl font-semibold mb-5">Customers</h1>
<div class="bg-white rounded-3xl p-5 shadow-sm overflow-x-auto">
<table class="w-full text-left text-sm">
<thead><tr class="border-b text-slate-500"><th class="p-3">Name</th><th>Email</th><th>Phone</th><th>Joined</th></tr></thead>
<tbody>
<?php foreach ($rows as $row): ?><tr class="border-b border-rose-50"><td class="p-3"><?= e($row['full_name']) ?></td><td><?= e($row['email']) ?></td><td><?= e($row['phone']) ?></td><td><?= e($row['created_at']) ?></td></tr><?php endforeach; ?>
<?php if (!$rows): ?><tr><td class="p-3" colspan="4">No customers yet.</td></tr><?php endif; ?>
</tbody></table></div>