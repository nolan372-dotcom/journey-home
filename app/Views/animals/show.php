<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="mb-5">
    <a href="/animals" class="text-xs font-medium text-stone-400 hover:text-stone-600 transition-colors">← Animals</a>
</div>

<?php if (!empty($animal['archived'])): ?>
<div class="mb-5 flex items-center gap-2.5 rounded-lg bg-stone-100 border border-stone-200 px-4 py-3 text-stone-500 text-sm font-medium">
    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-.375c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v.375c0 .621.504 1.125 1.125 1.125z"/></svg>
    This animal is archived and no longer active in the foster program.
</div>
<?php endif ?>

<div class="flex items-start justify-between mb-7">
    <div class="flex items-center gap-4">
        <?php if ($animal['photo_url']): ?>
            <img src="<?= esc($animal['photo_url']) ?>" alt="<?= esc($animal['name']) ?>"
                class="h-14 w-14 rounded-lg object-cover border border-stone-200">
        <?php endif ?>
        <div>
            <h1 class="text-xl font-semibold text-stone-900"><?= esc($animal['name']) ?></h1>
            <p class="text-sm text-stone-500 mt-0.5 capitalize">
                <?= esc($animal['species']) ?><?= $animal['breed'] ? ' · ' . esc($animal['breed']) : '' ?>
                · <?= esc($animal['age_group']) ?> · <?= esc($animal['size']) ?>
            </p>
        </div>
    </div>
    <div class="flex items-center gap-2">
        <?php if (empty($animal['archived'])): ?>
        <form action="/animals/<?= $animal['id'] ?>/archive" method="post"
              onsubmit="return confirm('Archive <?= esc($animal['name'], 'attr') ?>? They will be removed from the active animal list and cannot be assigned to a foster.')">
            <?= csrf_field() ?>
            <button type="submit"
                class="rounded-lg border border-stone-300 px-4 py-2 text-sm font-medium text-stone-500 hover:border-red-300 hover:text-red-600 hover:bg-red-50 transition-colors">
                Archive
            </button>
        </form>
        <?php endif ?>
        <a href="/animals/<?= $animal['id'] ?>/edit"
           class="rounded-lg border border-stone-300 px-4 py-2 text-sm font-medium text-stone-700 hover:bg-stone-50 transition-colors">
            Edit
        </a>
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
    <div class="bg-white rounded-lg border border-stone-200 p-5 space-y-3 text-sm">
        <h2 class="text-xs font-semibold text-stone-400 uppercase tracking-wider mb-4">Details</h2>
        <div class="flex justify-between"><span class="text-stone-400">Intake date</span><span class="text-stone-700"><?= esc($animal['intake_date']) ?></span></div>
        <div class="flex justify-between"><span class="text-stone-400">Age group</span><span class="text-stone-700 capitalize"><?= esc($animal['age_group']) ?></span></div>
        <div class="flex justify-between"><span class="text-stone-400">Size</span><span class="text-stone-700 capitalize"><?= esc($animal['size']) ?></span></div>
        <?php if ($animal['notes']): ?>
        <div class="pt-3 border-t border-stone-100 text-stone-600 text-xs leading-relaxed"><?= esc($animal['notes']) ?></div>
        <?php endif ?>
    </div>

    <div class="bg-white rounded-lg border border-stone-200 p-5 text-sm">
        <h2 class="text-xs font-semibold text-stone-400 uppercase tracking-wider mb-4">Status</h2>
        <?php
            $colors = [
                'needs_foster' => 'bg-orange-100 text-orange-800',
                'in_foster'    => 'bg-orange-100 text-orange-800',
                'adopted'      => 'bg-sky-100 text-sky-800',
            ];
            $labels = ['needs_foster' => 'Needs foster', 'in_foster' => 'In foster', 'adopted' => 'Adopted'];
            $color = $colors[$animal['status']] ?? 'bg-stone-100 text-stone-500';
            $label = $labels[$animal['status']] ?? $animal['status'];
        ?>
        <span class="inline-block rounded-full px-3 py-1 text-xs font-semibold <?= $color ?> mb-5"><?= $label ?></span>

        <?php if ($placement): ?>
            <div class="rounded-lg bg-orange-50 border border-orange-200 p-4">
                <p class="font-semibold text-orange-900 text-sm"><?= esc($placement['foster_name']) ?></p>
                <p class="text-orange-700 text-xs mt-1">Since <?= esc($placement['start_date']) ?></p>
                <p class="text-orange-700 text-xs"><?= esc($placement['email']) ?> · <?= esc($placement['phone']) ?></p>
                <a href="/fosters/<?= $placement['foster_id'] ?>" class="text-xs font-semibold text-orange-700 hover:underline mt-2 inline-block">View foster home →</a>
            </div>
        <?php elseif ($animal['status'] === 'needs_foster'): ?>
            <p class="text-stone-400 text-sm">Not currently placed.
                <a href="/" class="text-orange-600 hover:underline">Go to dashboard to assign a foster.</a>
            </p>
        <?php endif ?>
    </div>
</div>

<?= $this->endSection() ?>
