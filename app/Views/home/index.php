<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="mb-6 flex items-center gap-3 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-red-800 text-sm font-medium">
        <svg class="w-4 h-4 text-red-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126z"/></svg>
        <?= esc(session()->getFlashdata('error')) ?>
    </div>
<?php endif ?>

<div class="flex items-baseline justify-between mb-6">
    <h1 class="text-xl font-semibold text-stone-900">Dashboard</h1>
    <span class="text-xs text-stone-400"><?= date('F j, Y') ?></span>
</div>

<!-- Summary strip -->
<div class="grid grid-cols-3 gap-3 mb-7">
    <div class="rounded-lg border border-stone-200 bg-white px-4 py-3.5">
        <p class="text-xs font-medium text-stone-400 uppercase tracking-wider">Active placements</p>
        <p class="text-2xl font-bold text-stone-900 mt-1"><?= array_sum(array_map(fn($g) => count($g['animals']), $fosterGroups)) ?></p>
    </div>
    <div class="rounded-lg border border-stone-200 bg-white px-4 py-3.5">
        <p class="text-xs font-medium text-stone-400 uppercase tracking-wider">Needs foster</p>
        <p class="text-2xl font-bold mt-1 <?= count($needsFoster) > 0 ? 'text-orange-600' : 'text-stone-900' ?>"><?= count($needsFoster) ?></p>
    </div>
    <div class="rounded-lg border border-stone-200 bg-white px-4 py-3.5">
        <p class="text-xs font-medium text-stone-400 uppercase tracking-wider">Open slots</p>
        <p class="text-2xl font-bold text-orange-600 mt-1"><?= array_sum(array_map(fn($f) => $f['max_capacity'] - $f['active_count'], $availableFosters)) ?></p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Panel 1: Active placements -->
    <div class="lg:col-span-2">
        <h2 class="text-xs font-semibold text-stone-400 uppercase tracking-wider mb-3">
            Active Placements · <?= count($fosterGroups) ?> foster<?= count($fosterGroups) !== 1 ? 's' : '' ?>
        </h2>

        <?php if (empty($fosterGroups)): ?>
            <div class="rounded-lg border border-stone-200 bg-white px-5 py-10 text-center">
                <p class="text-stone-400 text-sm">No active placements yet.</p>
                <?php if (!empty($needsFoster) && !empty($availableFosters)): ?>
                <a href="/placements/new?animal_id=<?= $needsFoster[0]['id'] ?>"
                   class="mt-3 inline-block text-xs font-semibold text-orange-600 hover:text-orange-700">
                    Assign the first animal →
                </a>
                <?php endif ?>
            </div>
        <?php else: ?>
        <div class="space-y-3">
            <?php foreach ($fosterGroups as $group): ?>
            <div class="rounded-lg border border-stone-200 bg-white overflow-hidden">
                <div class="flex items-center justify-between px-4 py-2.5 bg-stone-50 border-b border-stone-100">
                    <div class="flex items-center gap-2">
                        <a href="/fosters/<?= $group['foster_id'] ?>" class="font-semibold text-orange-700 hover:text-orange-800 text-sm">
                            <?= esc($group['foster_name']) ?>
                        </a>
                        <span class="text-xs text-stone-400 tabular-nums"><?= count($group['animals']) ?>/<?= $group['max_capacity'] ?> slots</span>
                    </div>
                    <span class="text-xs text-stone-400"><?= esc($group['foster_email']) ?></span>
                </div>
                <ul class="divide-y divide-stone-100">
                    <?php foreach ($group['animals'] as $row): ?>
                    <li class="flex items-center justify-between px-4 py-3 text-sm gap-4">
                        <div class="min-w-0">
                            <a href="/animals/<?= $row['animal_id'] ?>" class="font-medium text-stone-800 hover:text-orange-700">
                                <?= esc($row['animal_name']) ?>
                            </a>
                            <span class="text-stone-400 text-xs capitalize ml-2">
                                <?= esc($row['species']) ?><?= $row['breed'] ? ' · ' . esc($row['breed']) : '' ?>
                                · since <?= esc($row['start_date']) ?>
                            </span>
                        </div>
                        <form action="/placements/<?= $row['placement_id'] ?>/end" method="post" class="flex items-center gap-2 shrink-0">
                            <?= csrf_field() ?>
                            <select name="final_status" class="rounded-md border border-stone-300 bg-white px-2 py-1 text-xs text-stone-600 focus:outline-none focus:ring-1 focus:ring-orange-400">
                                <option value="needs_foster">→ Needs foster</option>
                                <option value="adopted">→ Adopted</option>
                            </select>
                            <button type="submit"
                                onclick="return confirm('End this placement for <?= esc($row['animal_name'], 'attr') ?>? This cannot be undone.')"
                                class="rounded-md border border-stone-300 bg-white px-2.5 py-1 text-xs font-medium text-stone-600 hover:bg-stone-100 transition-colors">
                                Update
                            </button>
                        </form>
                    </li>
                    <?php endforeach ?>
                </ul>
            </div>
            <?php endforeach ?>
        </div>
        <?php endif ?>
    </div>

    <!-- Right column -->
    <div class="space-y-6">

        <!-- Panel 2: Needs a Foster -->
        <div>
            <h2 class="text-xs font-semibold text-stone-400 uppercase tracking-wider mb-3">
                Needs a Foster · <?= count($needsFoster) ?>
            </h2>
            <?php if (empty($needsFoster)): ?>
                <div class="rounded-lg border border-stone-200 bg-white px-4 py-6 text-center">
                    <p class="text-stone-400 text-sm">All animals are placed.</p>
                </div>
            <?php else: ?>
            <div class="rounded-lg border border-stone-200 bg-white overflow-hidden">
                <ul class="divide-y divide-stone-100">
                    <?php foreach ($needsFoster as $a): ?>
                    <li class="px-4 py-3 text-sm">
                        <div class="flex items-center justify-between gap-2">
                            <div class="min-w-0">
                                <a href="/animals/<?= $a['id'] ?>" class="font-medium text-stone-800 hover:text-orange-700 block truncate">
                                    <?= esc($a['name']) ?>
                                </a>
                                <p class="text-xs text-stone-400 capitalize mt-0.5">
                                    <?= esc($a['species']) ?> · <?= esc($a['age_group']) ?> · <?= esc($a['size']) ?>
                                </p>
                            </div>
                            <?php if (!empty($availableFosters)): ?>
                            <a href="/placements/new?animal_id=<?= $a['id'] ?>"
                               class="shrink-0 rounded-md bg-orange-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-orange-700 transition-colors">
                                Assign
                            </a>
                            <?php else: ?>
                            <span class="shrink-0 text-xs text-stone-400">No fosters</span>
                            <?php endif ?>
                        </div>
                    </li>
                    <?php endforeach ?>
                </ul>
            </div>
            <?php endif ?>
        </div>

        <!-- Panel 3: Available Fosters -->
        <div>
            <h2 class="text-xs font-semibold text-stone-400 uppercase tracking-wider mb-3">
                Available Fosters · <?= count($availableFosters) ?>
            </h2>
            <?php if (empty($availableFosters)): ?>
                <div class="rounded-lg border border-stone-200 bg-white px-4 py-6 text-center">
                    <p class="text-stone-400 text-sm">No fosters available.</p>
                    <a href="/fosters" class="text-xs text-orange-600 hover:underline mt-1 inline-block">Manage fosters →</a>
                </div>
            <?php else: ?>
            <div class="rounded-lg border border-stone-200 bg-white overflow-hidden">
                <ul class="divide-y divide-stone-100">
                    <?php foreach ($availableFosters as $f): ?>
                    <li class="px-4 py-3 text-sm">
                        <div class="flex items-center justify-between gap-2">
                            <div class="min-w-0">
                                <a href="/fosters/<?= $f['id'] ?>" class="font-medium text-stone-800 hover:text-orange-700 block truncate">
                                    <?= esc($f['name']) ?>
                                </a>
                                <p class="text-xs text-stone-400 mt-0.5 capitalize">
                                    <?= esc($f['species_accepted']) ?> · <?= $f['active_count'] ?>/<?= $f['max_capacity'] ?> slots
                                </p>
                            </div>
                            <span class="shrink-0 inline-flex items-center rounded-full bg-orange-50 border border-orange-200 px-2.5 py-0.5 text-xs font-semibold text-orange-700">
                                <?= $f['max_capacity'] - $f['active_count'] ?> open
                            </span>
                        </div>
                    </li>
                    <?php endforeach ?>
                </ul>
            </div>
            <?php endif ?>
        </div>

    </div>
</div>

<?= $this->endSection() ?>
