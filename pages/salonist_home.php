<?php require_role(['salonist']); ?>
<section class="bg-white rounded-3xl p-8 shadow-sm">
  <p class="text-rose-500 uppercase tracking-widest text-xs">Salonist space</p>
  <h1 class="text-3xl font-semibold mt-2">Hello, <?= e($_SESSION['full_name']) ?></h1>
  <p class="text-slate-600 mt-3">View the appointments assigned to you from the sidebar.</p>
</section>