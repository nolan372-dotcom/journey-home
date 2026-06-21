<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="mb-5">
    <a href="/" class="text-xs font-medium text-stone-400 hover:text-stone-600 transition-colors">← Dashboard</a>
    <h1 class="mt-2 text-xl font-semibold text-stone-900">Assign Foster Home</h1>
</div>

<?php if (session()->getFlashdata('error')): ?>
    <div class="mb-6 flex items-center gap-3 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-red-800 text-sm font-medium">
        <svg class="w-4 h-4 text-red-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126z"/></svg>
        <?= esc(session()->getFlashdata('error')) ?>
    </div>
<?php endif ?>

<!-- Animal being placed -->
<div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4 mb-7 max-w-2xl">
    <p class="text-xs font-semibold text-emerald-500 uppercase tracking-wider mb-2">Placing</p>
    <p class="text-base font-semibold text-emerald-900"><?= esc($animal['name']) ?></p>
    <p class="text-sm text-emerald-700 capitalize mt-0.5">
        <?= esc($animal['species']) ?><?= $animal['breed'] ? ' · ' . esc($animal['breed']) : '' ?>
        · <?= esc($animal['age_group']) ?> · <?= esc($animal['size']) ?>
    </p>
    <?php if ($animal['notes']): ?>
        <p class="text-xs text-emerald-600 mt-2 leading-relaxed"><?= esc($animal['notes']) ?></p>
    <?php endif ?>
</div>

<!-- Search -->
<?php $baseQuery = http_build_query(['animal_id' => $animal['id'], 'q' => $q]); ?>
<form method="get" action="/placements/new" class="mb-5 flex gap-2 max-w-2xl">
    <input type="hidden" name="animal_id" value="<?= $animal['id'] ?>">
    <input type="text" name="q" value="<?= esc($q) ?>" placeholder="Search by name, email or species…"
        class="flex-1 rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400">
    <button type="submit" class="rounded-lg bg-stone-800 px-4 py-2 text-sm font-medium text-white hover:bg-stone-700 transition-colors">
        Search
    </button>
    <?php if ($q !== ''): ?>
    <a href="/placements/new?animal_id=<?= $animal['id'] ?>" class="rounded-lg border border-stone-300 px-4 py-2 text-sm font-medium text-stone-500 hover:bg-stone-50 transition-colors">
        Clear
    </a>
    <?php endif ?>
</form>

<!-- Available fosters -->
<?php if ($total === 0 && $q === ''): ?>
    <div class="rounded-lg border border-stone-200 bg-white px-5 py-8 text-center max-w-2xl">
        <p class="text-stone-400 text-sm">No foster homes are available right now.</p>
        <p class="text-stone-400 text-xs mt-1">All active fosters are at capacity, or none are active.</p>
        <a href="/fosters" class="mt-3 inline-block text-xs font-semibold text-emerald-600 hover:text-emerald-700">Manage foster homes →</a>
    </div>
<?php elseif ($total === 0): ?>
    <div class="rounded-lg border border-stone-200 bg-white px-5 py-8 text-center max-w-2xl">
        <p class="text-stone-400 text-sm">No foster homes match your search.</p>
        <a href="/placements/new?animal_id=<?= $animal['id'] ?>" class="mt-3 inline-block text-xs font-semibold text-emerald-600 hover:text-emerald-700">Clear search →</a>
    </div>
<?php else: ?>
<div class="flex items-center justify-between mb-3 max-w-2xl">
    <p class="text-xs font-semibold text-stone-400 uppercase tracking-wider">Select a foster home</p>
    <p class="text-xs text-stone-400">
        <?= $total ?> available<?= $q !== '' ? ' matching' : '' ?><?= $totalPages > 1 ? ' · page ' . $page . ' of ' . $totalPages : '' ?>
    </p>
</div>
<div class="space-y-2.5 max-w-2xl">
    <?php foreach ($available as $f): ?>
    <form action="/placements" method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="animal_id" value="<?= $animal['id'] ?>">
        <input type="hidden" name="foster_home_id" value="<?= $f['id'] ?>">
        <div class="flex items-center justify-between rounded-lg border border-stone-200 bg-white px-5 py-4 hover:border-emerald-300 hover:bg-emerald-50 transition-colors group">
            <div>
                <p class="font-semibold text-stone-900 text-sm"><?= esc($f['name']) ?></p>
                <p class="text-xs text-stone-400 mt-0.5 capitalize">
                    <?= esc($f['species_accepted']) ?> · <?= esc($f['size_preference'] ?: 'any size') ?>
                    · <?= $f['active_count'] ?>/<?= $f['max_capacity'] ?> slots used
                    <?= $f['has_kids'] ? ' · has kids' : '' ?>
                    <?= $f['has_other_pets'] ? ' · has pets' : '' ?>
                </p>
                <?php if ($f['notes']): ?>
                    <p class="text-xs text-stone-400 mt-1 leading-relaxed"><?= esc($f['notes']) ?></p>
                <?php endif ?>
            </div>
            <button type="submit"
                class="ml-4 shrink-0 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700 transition-colors">
                Place here
            </button>
        </div>
    </form>
    <?php endforeach ?>
</div>

<?php if ($totalPages > 1): ?>
<div class="flex items-center gap-1.5 mt-5 max-w-2xl">
    <?php if ($page > 1): ?>
    <a href="?<?= $baseQuery ?>&page=<?= $page - 1 ?>"
       class="rounded-lg border border-stone-300 px-3 py-1.5 text-sm font-medium text-stone-600 hover:bg-stone-50 transition-colors">
        ← Prev
    </a>
    <?php endif ?>

    <?php for ($p = 1; $p <= $totalPages; $p++): ?>
    <a href="?<?= $baseQuery ?>&page=<?= $p ?>"
       class="rounded-lg border px-3 py-1.5 text-sm font-medium transition-colors <?= $p === $page ? 'border-emerald-500 bg-emerald-50 text-emerald-700' : 'border-stone-300 text-stone-600 hover:bg-stone-50' ?>">
        <?= $p ?>
    </a>
    <?php endfor ?>

    <?php if ($page < $totalPages): ?>
    <a href="?<?= $baseQuery ?>&page=<?= $page + 1 ?>"
       class="rounded-lg border border-stone-300 px-3 py-1.5 text-sm font-medium text-stone-600 hover:bg-stone-50 transition-colors">
        Next →
    </a>
    <?php endif ?>
</div>
<?php endif ?>

<?php endif ?>

<?= $this->endSection() ?>
