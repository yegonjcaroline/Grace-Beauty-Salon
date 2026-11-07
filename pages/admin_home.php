<?php require_role(['admin']); ?>
<section class="bg-white rounded-3xl p-8 shadow-sm">
  <p class="text-rose-500 uppercase tracking-widest text-xs">Studio management</p>
  <h1 class="text-3xl font-semibold mt-2">Welcome, <?= e($_SESSION['full_name']) ?></h1>
  <p class="text-slate-600 mt-3">Manage salonists, view customers, and assign or update appointments from the sidebar.</p>
</section>