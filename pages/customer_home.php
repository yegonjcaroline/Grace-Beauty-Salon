<?php
require_role(['customer']);
?>
<section class="bg-white rounded-3xl p-8 shadow-sm">
  <p class="text-rose-500 uppercase tracking-widest text-xs">Your beauty time</p>
  <h1 class="text-3xl font-semibold mt-2">Welcome, <?= e($_SESSION['full_name']) ?></h1>
  <p class="text-slate-600 mt-3">Book a little time for yourself, then follow your appointments here.</p>
  <a href="dashboard.php?page=book" class="inline-block mt-6 rounded-xl bg-rose-400 text-white px-5 py-3">Book an appointment</a>
</section>