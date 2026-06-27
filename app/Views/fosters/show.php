<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="mb-5">
    <a href="/fosters" class="text-xs font-medium text-stone-400 hover:text-stone-600 transition-colors">← Foster Homes</a>
</div>

<?php if (session()->getFlashdata('error')): ?>
<div class="mb-5 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-red-800 text-sm">
    <?= esc(session()->getFlashdata('error')) ?>
</div>
<?php endif ?>

<div class="flex items-start justify-between mb-7">
    <div>
        <h1 class="text-xl font-semibold text-stone-900"><?= esc($foster['name']) ?></h1>
        <p class="text-sm text-stone-500 mt-0.5"><?= esc($foster['email']) ?> · <?= esc($foster['phone']) ?></p>
    </div>
    <div class="flex items-center gap-2">
        <a href="/fosters/<?= $foster['id'] ?>/edit"
           class="rounded-lg border border-stone-300 px-4 py-2 text-sm font-medium text-stone-700 hover:bg-stone-50 transition-colors">
            Edit
        </a>
        <form action="/fosters/<?= $foster['id'] ?>/delete" method="post"
              onsubmit="return confirm('Permanently delete <?= esc($foster['name'], 'attr') ?>? This cannot be undone.')">
            <?= csrf_field() ?>
            <button type="submit"
                class="rounded-lg border border-red-200 px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50 hover:border-red-300 transition-colors">
                Delete
            </button>
        </form>
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-7">
    <div class="bg-white rounded-lg border border-stone-200 p-5 space-y-3 text-sm">
        <h2 class="text-xs font-semibold text-stone-400 uppercase tracking-wider mb-4">Details</h2>
        <div class="flex justify-between"><span class="text-stone-400">ZIP</span><span class="text-stone-700"><?= esc($foster['area_zip'] ?: '—') ?></span></div>
        <div class="flex justify-between"><span class="text-stone-400">Accepts</span><span class="text-stone-700 capitalize"><?= esc($foster['species_accepted']) ?></span></div>
        <div class="flex justify-between"><span class="text-stone-400">Max capacity</span><span class="text-stone-700"><?= esc($foster['max_capacity']) ?></span></div>
        <?php
            $sizes = [];
            if ($foster['ok_small'])  $sizes[] = 'Small';
            if ($foster['ok_medium']) $sizes[] = 'Medium';
            if ($foster['ok_large'])  $sizes[] = 'Large';
            if ($foster['ok_xlarge']) $sizes[] = 'Extra Large';
        ?>
        <div class="flex justify-between items-start gap-4"><span class="text-stone-400 shrink-0">Size preferences</span><span class="text-stone-700 text-right"><?= $sizes ? esc(implode(', ', $sizes)) : '—' ?></span></div>
        <?php
            $prefs = [];
            if ($foster['ok_puppies'])      $prefs[] = 'Puppies';
            if ($foster['ok_kittens'])      $prefs[] = 'Kittens';
            if ($foster['ok_medical'])      $prefs[] = 'Medical cases';
            if ($foster['ok_behavior'])     $prefs[] = 'Behavior cases';
            if ($foster['has_fenced_yard']) $prefs[] = 'Fenced yard';
            if ($foster['has_kids'])        $prefs[] = 'Has kids in home';
            if ($foster['has_other_pets'])  $prefs[] = 'Has other pets';
            $custom = $foster['custom_can_handle'] ? json_decode($foster['custom_can_handle'], true) ?? [] : [];
            $allPrefs = array_merge($prefs, $custom);
        ?>
        <div class="flex justify-between items-start gap-4"><span class="text-stone-400 shrink-0">Can handle</span><span class="text-stone-700 text-right"><?= $allPrefs ? esc(implode(', ', $allPrefs)) : '—' ?></span></div>
        <?php if ($foster['notes']): ?>
        <div class="pt-3 border-t border-stone-100 text-stone-600 text-xs leading-relaxed"><?= esc($foster['notes']) ?></div>
        <?php endif ?>
    </div>

    <div class="bg-white rounded-lg border border-stone-200 p-5 text-sm">
        <h2 class="text-xs font-semibold text-stone-400 uppercase tracking-wider mb-4">Status</h2>
        <?php
            $colors = ['active' => 'bg-orange-100 text-orange-800', 'paused' => 'bg-amber-100 text-amber-800', 'inactive' => 'bg-stone-100 text-stone-500'];
            $color = $colors[$foster['status']] ?? 'bg-stone-100 text-stone-500';
        ?>
        <span class="inline-block rounded-full px-3 py-1 text-xs font-semibold <?= $color ?> mb-5 capitalize"><?= esc($foster['status']) ?></span>

        <form action="/fosters/<?= $foster['id'] ?>/status" method="post" class="flex gap-2 flex-wrap">
            <?= csrf_field() ?>
            <?php foreach (['active', 'paused', 'inactive'] as $s): ?>
                <?php if ($s !== $foster['status']): ?>
                <button name="status" value="<?= $s ?>"
                    class="rounded-lg border border-stone-300 px-3 py-1.5 text-xs font-medium text-stone-600 hover:bg-stone-100 capitalize transition-colors">
                    Set <?= $s ?>
                </button>
                <?php endif ?>
            <?php endforeach ?>
        </form>
    </div>
</div>

<h2 class="text-xs font-semibold text-stone-400 uppercase tracking-wider mb-3">Current Animals (<?= count($placements) ?>)</h2>

<?php if (empty($placements)): ?>
    <div class="rounded-lg border border-stone-200 bg-white px-5 py-8 text-center">
        <p class="text-stone-400 text-sm">No animals currently placed here.</p>
    </div>
<?php else: ?>
<div class="bg-white rounded-lg border border-stone-200 overflow-x-auto">
    <table class="min-w-full divide-y divide-stone-200 text-sm">
        <thead class="bg-stone-50">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-stone-500 uppercase tracking-wider">Animal</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-stone-500 uppercase tracking-wider">Species / Breed</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-stone-500 uppercase tracking-wider">Age / Size</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-stone-500 uppercase tracking-wider">Since</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-stone-100">
            <?php foreach ($placements as $p): ?>
            <tr class="hover:bg-stone-50">
                <td class="px-4 py-3 font-medium">
                    <a href="/animals/<?= $p['animal_id'] ?>" class="text-orange-700 hover:underline"><?= esc($p['name']) ?></a>
                </td>
                <td class="px-4 py-3 text-stone-600 capitalize"><?= esc($p['species']) ?><?= $p['breed'] ? ' · ' . esc($p['breed']) : '' ?></td>
                <td class="px-4 py-3 text-stone-600 capitalize"><?= esc($p['age_group']) ?> · <?= esc($p['size']) ?></td>
                <td class="px-4 py-3 text-stone-400"><?= esc($p['start_date']) ?></td>
            </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>
<?php endif ?>

<?= $this->endSection() ?>
