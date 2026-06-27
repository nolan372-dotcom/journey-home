<?= $this->extend('layouts/main') ?>

<?= $this->section('head') ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .flatpickr-day.inRange { background: #d1fae5; border-color: #d1fae5; }
    .flatpickr-day.startRange, .flatpickr-day.endRange { background: #059669; border-color: #059669; }
    .flatpickr-day.startRange:hover, .flatpickr-day.endRange:hover { background: #047857; border-color: #047857; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
    $anyFilter = ($filters['q'] !== '' || $filters['species'] || !empty($filters['size']) || $filters['status'] || $filters['dateFrom'] || $filters['dateTo']);
?>

<div class="flex items-center justify-between mb-5">
    <h1 class="text-xl font-semibold text-stone-900">Animals</h1>
    <a href="/animals/new" class="inline-flex items-center gap-1.5 rounded-lg bg-orange-600 px-4 py-2 text-sm font-semibold text-white hover:bg-orange-700 transition-colors">
        + Add Animal
    </a>
</div>

<form method="get" action="/animals" id="animal-filter-form" class="mb-5">
    <div class="flex flex-wrap gap-2">
        <input type="text" name="q" value="<?= esc($filters['q']) ?>" placeholder="Search name or breed…"
            class="rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400 w-48">

        <select name="species" class="rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400">
            <option value="">All species</option>
            <option value="dog"   <?= $filters['species'] === 'dog'   ? 'selected' : '' ?>>Dog</option>
            <option value="cat"   <?= $filters['species'] === 'cat'   ? 'selected' : '' ?>>Cat</option>
            <option value="other" <?= $filters['species'] === 'other' ? 'selected' : '' ?>>Other</option>
        </select>

        <select id="size-select" class="rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400">
            <option value="">Add size…</option>
            <option value="small">Small</option>
            <option value="medium">Medium</option>
            <option value="large">Large</option>
            <option value="x-large">Extra Large</option>
        </select>

        <select name="status" class="rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400">
            <option value="">All statuses</option>
            <option value="needs_foster" <?= $filters['status'] === 'needs_foster' ? 'selected' : '' ?>>Needs foster</option>
            <option value="in_foster"    <?= $filters['status'] === 'in_foster'    ? 'selected' : '' ?>>In foster</option>
            <option value="adopted"      <?= $filters['status'] === 'adopted'      ? 'selected' : '' ?>>Adopted</option>
        </select>

        <div class="relative">
            <input type="text" id="date-range-picker" placeholder="Intake date range…" readonly
                class="rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400 w-52 cursor-pointer">
            <input type="hidden" name="date_from" id="filter-date-from" value="<?= esc($filters['dateFrom']) ?>">
            <input type="hidden" name="date_to"   id="filter-date-to"   value="<?= esc($filters['dateTo']) ?>">
        </div>

        <button type="submit" class="rounded-lg bg-stone-800 px-4 py-2 text-sm font-medium text-white hover:bg-stone-700 transition-colors">
            Search
        </button>
        <a href="/animals" class="rounded-lg border border-stone-300 px-4 py-2 text-sm font-medium text-stone-500 hover:bg-stone-50 transition-colors">
            Clear
        </a>
    </div>

    <div id="size-tags" class="flex flex-wrap gap-1.5 mt-2"></div>
    <div id="size-inputs"></div>
</form>

<script>
(function () {
    var LABELS = { 'small': 'Small', 'medium': 'Medium', 'large': 'Large', 'x-large': 'Extra Large' };

    var selected = <?= json_encode(array_values($filters['size'])) ?>;

    var tagsEl   = document.getElementById('size-tags');
    var inputsEl = document.getElementById('size-inputs');
    var selectEl = document.getElementById('size-select');

    function render() {
        tagsEl.innerHTML   = '';
        inputsEl.innerHTML = '';

        selected.forEach(function (val) {
            var tag = document.createElement('span');
            tag.className = 'inline-flex items-center gap-1 rounded-full bg-orange-100 text-orange-800 text-xs font-semibold px-3 py-1';
            tag.innerHTML =
                '<span>' + (LABELS[val] || val) + '</span>' +
                '<button type="button" class="ml-1 leading-none hover:text-orange-600 text-base" data-val="' + val + '">&times;</button>';
            tag.querySelector('button').addEventListener('click', function () {
                remove(this.dataset.val);
            });
            tagsEl.appendChild(tag);

            var hidden = document.createElement('input');
            hidden.type  = 'hidden';
            hidden.name  = 'size[]';
            hidden.value = val;
            inputsEl.appendChild(hidden);
        });
    }

    function add(val) {
        if (!val || selected.indexOf(val) !== -1) return;
        selected.push(val);
        render();
    }

    function remove(val) {
        selected = selected.filter(function (v) { return v !== val; });
        render();
    }

    selectEl.addEventListener('change', function () {
        add(this.value);
        this.value = '';
    });

    render();
})();
</script>

<?php if (empty($animals)): ?>
    <div class="rounded-lg border border-stone-200 bg-white px-5 py-10 text-center">
        <?php if ($anyFilter): ?>
            <p class="text-stone-400 text-sm">No animals match your search.</p>
            <a href="/animals" class="mt-3 inline-block text-xs font-semibold text-orange-600 hover:text-orange-700">Clear filters →</a>
        <?php else: ?>
            <p class="text-stone-400 text-sm">No animals yet.</p>
            <a href="/animals/new" class="mt-3 inline-block text-xs font-semibold text-orange-600 hover:text-orange-700">Add the first one →</a>
        <?php endif ?>
    </div>
<?php else: ?>
<div class="overflow-x-auto rounded-lg border border-stone-200 bg-white">
    <table class="min-w-full divide-y divide-stone-200 text-sm">
        <thead class="bg-stone-50">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-stone-500 uppercase tracking-wider">Name</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-stone-500 uppercase tracking-wider">Species / Breed</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-stone-500 uppercase tracking-wider">Age / Size</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-stone-500 uppercase tracking-wider">Intake</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-stone-500 uppercase tracking-wider">Status</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-stone-100">
            <?php foreach ($animals as $a): ?>
            <tr class="hover:bg-stone-50 transition-colors">
                <td class="px-4 py-3 font-medium">
                    <div class="flex items-center gap-3">
                        <?php if ($a['photo_url']): ?>
                            <img src="<?= esc($a['photo_url']) ?>" alt="" class="h-8 w-8 rounded-full object-cover border border-stone-200">
                        <?php endif ?>
                        <a href="/animals/<?= $a['id'] ?>" class="text-orange-700 hover:text-orange-800 hover:underline"><?= esc($a['name']) ?></a>
                    </div>
                </td>
                <td class="px-4 py-3 text-stone-600 capitalize">
                    <?= esc($a['species']) ?><?= $a['breed'] ? ' · ' . esc($a['breed']) : '' ?>
                </td>
                <td class="px-4 py-3 text-stone-600 capitalize">
                    <?= esc($a['age_group']) ?> · <?= esc($a['size']) ?>
                </td>
                <td class="px-4 py-3 text-stone-400"><?= esc($a['intake_date']) ?></td>
                <td class="px-4 py-3">
                    <?php
                        $colors = [
                            'needs_foster' => 'bg-orange-100 text-orange-800',
                            'in_foster'    => 'bg-orange-100 text-orange-800',
                            'adopted'      => 'bg-sky-100 text-sky-800',
                        ];
                        $labels = [
                            'needs_foster' => 'Needs foster',
                            'in_foster'    => 'In foster',
                            'adopted'      => 'Adopted',
                        ];
                        $color = $colors[$a['status']] ?? 'bg-stone-100 text-stone-500';
                        $label = $labels[$a['status']] ?? $a['status'];
                    ?>
                    <span class="inline-block rounded-full px-2.5 py-0.5 text-xs font-semibold <?= $color ?>"><?= $label ?></span>
                </td>
                <td class="px-4 py-3 text-right">
                    <a href="/animals/<?= $a['id'] ?>/edit?from=list" class="text-xs font-medium text-stone-400 hover:text-orange-700 transition-colors">Edit</a>
                </td>
            </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>
<?php endif ?>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
(function () {
    var fromEl = document.getElementById('filter-date-from');
    var toEl   = document.getElementById('filter-date-to');
    var defaultDates = [fromEl.value, toEl.value].filter(Boolean);

    flatpickr('#date-range-picker', {
        mode: 'range',
        dateFormat: 'Y-m-d',
        altInput: true,
        altFormat: 'M j, Y',
        defaultDate: defaultDates.length ? defaultDates : undefined,
        onChange: function (selectedDates) {
            fromEl.value = selectedDates[0] ? flatpickr.formatDate(selectedDates[0], 'Y-m-d') : '';
            toEl.value   = selectedDates[1] ? flatpickr.formatDate(selectedDates[1], 'Y-m-d') : '';
        },
        onClose: function (selectedDates) {
            if (selectedDates.length === 0) {
                fromEl.value = '';
                toEl.value   = '';
            }
        },
    });
})();
</script>

<?= $this->endSection() ?>
