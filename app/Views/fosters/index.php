<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php $anyFilter = ($filters['q'] !== '' || $filters['species'] || $filters['status']); ?>

<div class="flex items-center justify-between mb-5">
    <h1 class="text-xl font-semibold text-stone-900">Foster Homes</h1>
    <a href="/fosters/new" class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700 transition-colors">
        + Add Foster Home
    </a>
</div>

<form method="get" action="/fosters" class="mb-5 flex flex-wrap gap-2">
    <input type="text" name="q" value="<?= esc($filters['q']) ?>" placeholder="Search name, email or phone…"
        class="rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400 w-56">

    <select name="species" class="rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400">
        <option value="">All species</option>
        <option value="both" <?= $filters['species'] === 'both' ? 'selected' : '' ?>>Dogs &amp; Cats</option>
        <option value="dog"  <?= $filters['species'] === 'dog'  ? 'selected' : '' ?>>Dogs only</option>
        <option value="cat"  <?= $filters['species'] === 'cat'  ? 'selected' : '' ?>>Cats only</option>
    </select>

    <select name="status" class="rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400">
        <option value="">All statuses</option>
        <option value="active"   <?= $filters['status'] === 'active'   ? 'selected' : '' ?>>Active</option>
        <option value="paused"   <?= $filters['status'] === 'paused'   ? 'selected' : '' ?>>Paused</option>
        <option value="inactive" <?= $filters['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
    </select>

    <button type="submit" class="rounded-lg bg-stone-800 px-4 py-2 text-sm font-medium text-white hover:bg-stone-700 transition-colors">
        Search
    </button>
    <?php if ($anyFilter): ?>
    <a href="/fosters" class="rounded-lg border border-stone-300 px-4 py-2 text-sm font-medium text-stone-500 hover:bg-stone-50 transition-colors">
        Clear
    </a>
    <?php endif ?>
</form>

<?php if (empty($fosters)): ?>
    <div class="rounded-lg border border-stone-200 bg-white px-5 py-10 text-center">
        <?php if ($anyFilter): ?>
            <p class="text-stone-400 text-sm">No foster homes match your search.</p>
            <a href="/fosters" class="mt-3 inline-block text-xs font-semibold text-emerald-600 hover:text-emerald-700">Clear filters →</a>
        <?php else: ?>
            <p class="text-stone-400 text-sm">No foster homes yet.</p>
            <a href="/fosters/new" class="mt-3 inline-block text-xs font-semibold text-emerald-600 hover:text-emerald-700">Add the first one →</a>
        <?php endif ?>
    </div>
<?php else: ?>
<div class="overflow-x-auto rounded-lg border border-stone-200 bg-white">
    <table class="min-w-full divide-y divide-stone-200 text-sm">
        <thead class="bg-stone-50">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-stone-500 uppercase tracking-wider">Name</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-stone-500 uppercase tracking-wider">Contact</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-stone-500 uppercase tracking-wider">Accepts</th>
                <th class="px-4 py-3 text-center text-xs font-semibold text-stone-500 uppercase tracking-wider">Animals</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-stone-500 uppercase tracking-wider">Status</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-stone-100">
            <?php foreach ($fosters as $f): ?>
            <tr class="hover:bg-stone-50 transition-colors">
                <td class="px-4 py-3 font-medium">
                    <a href="/fosters/<?= $f['id'] ?>" class="text-emerald-700 hover:text-emerald-800 hover:underline"><?= esc($f['name']) ?></a>
                </td>
                <td class="px-4 py-3 text-stone-600">
                    <div><?= esc($f['email']) ?></div>
                    <div class="text-xs text-stone-400"><?= esc($f['phone']) ?></div>
                </td>
                <td class="px-4 py-3 capitalize text-stone-600"><?= esc($f['species_accepted']) ?></td>
                <td class="px-4 py-3 text-center">
                    <span class="tabular-nums font-medium <?= $f['active_count'] >= $f['max_capacity'] ? 'text-red-600' : 'text-stone-700' ?>">
                        <?= $f['active_count'] ?>/<?= $f['max_capacity'] ?>
                    </span>
                </td>
                <td class="px-4 py-3">
                    <?php
                        $colors = ['active' => 'bg-emerald-100 text-emerald-800', 'paused' => 'bg-amber-100 text-amber-800', 'inactive' => 'bg-stone-100 text-stone-500'];
                        $color = $colors[$f['status']] ?? 'bg-stone-100 text-stone-500';
                    ?>
                    <span class="inline-block rounded-full px-2.5 py-0.5 text-xs font-semibold <?= $color ?> capitalize"><?= esc($f['status']) ?></span>
                </td>
                <td class="px-4 py-3 text-right">
                    <a href="/fosters/<?= $f['id'] ?>/edit?from=list" class="text-xs font-medium text-stone-400 hover:text-emerald-700 transition-colors">Edit</a>
                </td>
            </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>
<?php endif ?>

<?= $this->endSection() ?>
