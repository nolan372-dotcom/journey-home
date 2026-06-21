<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="mb-5">
    <a href="/animals" class="text-xs font-medium text-stone-400 hover:text-stone-600 transition-colors">← Animals</a>
    <h1 class="mt-2 text-xl font-semibold text-stone-900">Add Animal</h1>
</div>

<?php if (!empty($errors)): ?>
    <div class="mb-6 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-red-800 text-sm space-y-1">
        <?php foreach ($errors as $e): ?>
            <div><?= esc($e) ?></div>
        <?php endforeach ?>
    </div>
<?php endif ?>

<form action="/animals" method="post" class="bg-white rounded-lg border border-stone-200 p-6 space-y-5 max-w-2xl">
    <?= csrf_field() ?>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div class="sm:col-span-2">
            <label for="a-name" class="block text-sm font-medium text-stone-700 mb-1.5">Name <span class="text-red-500">*</span></label>
            <input type="text" id="a-name" name="name" value="<?= esc($old['name'] ?? '') ?>" required
                class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400">
        </div>

        <div>
            <label for="a-species" class="block text-sm font-medium text-stone-700 mb-1.5">Species <span class="text-red-500">*</span></label>
            <select id="a-species" name="species" required class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400">
                <?php foreach (['dog' => 'Dog', 'cat' => 'Cat', 'other' => 'Other'] as $val => $lbl): ?>
                    <option value="<?= $val ?>" <?= ($old['species'] ?? 'dog') === $val ? 'selected' : '' ?>><?= $lbl ?></option>
                <?php endforeach ?>
            </select>
        </div>

        <div>
            <label for="a-breed" class="block text-sm font-medium text-stone-700 mb-1.5">Breed</label>
            <input type="text" id="a-breed" name="breed" value="<?= esc($old['breed'] ?? '') ?>" placeholder="e.g. Beagle mix"
                class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400">
        </div>

        <div>
            <label for="a-age" class="block text-sm font-medium text-stone-700 mb-1.5">Age Group</label>
            <select id="a-age" name="age_group" class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400">
                <?php foreach (['puppy/kitten' => 'Puppy / Kitten', 'young' => 'Young', 'adult' => 'Adult', 'senior' => 'Senior'] as $val => $lbl): ?>
                    <option value="<?= $val ?>" <?= ($old['age_group'] ?? 'adult') === $val ? 'selected' : '' ?>><?= $lbl ?></option>
                <?php endforeach ?>
            </select>
        </div>

        <div>
            <label for="a-size" class="block text-sm font-medium text-stone-700 mb-1.5">Size</label>
            <select id="a-size" name="size" data-selected="<?= esc($old['size'] ?? 'medium') ?>"
                class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400">
                <?php foreach (['small' => 'Small', 'medium' => 'Medium', 'large' => 'Large'] as $val => $lbl): ?>
                    <option value="<?= $val ?>" <?= ($old['size'] ?? 'medium') === $val ? 'selected' : '' ?>><?= $lbl ?></option>
                <?php endforeach ?>
            </select>
        </div>

        <div>
            <label for="a-intake" class="block text-sm font-medium text-stone-700 mb-1.5">Intake Date <span class="text-red-500">*</span></label>
            <input type="date" id="a-intake" name="intake_date" value="<?= esc($old['intake_date'] ?? date('Y-m-d')) ?>" required
                class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400">
        </div>

        <div>
            <label for="a-status" class="block text-sm font-medium text-stone-700 mb-1.5">Status</label>
            <select id="a-status" name="status" class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400">
                <option value="needs_foster" <?= ($old['status'] ?? 'needs_foster') === 'needs_foster' ? 'selected' : '' ?>>Needs foster</option>
                <option value="in_foster"    <?= ($old['status'] ?? '') === 'in_foster'                ? 'selected' : '' ?>>In foster</option>
                <option value="adopted"      <?= ($old['status'] ?? '') === 'adopted'                  ? 'selected' : '' ?>>Adopted</option>
            </select>
        </div>

        <div class="sm:col-span-2">
            <label for="a-photo" class="block text-sm font-medium text-stone-700 mb-1.5">
                Photo URL <span class="text-stone-400 font-normal">(optional)</span>
            </label>
            <input type="url" id="a-photo" name="photo_url" value="<?= esc($old['photo_url'] ?? '') ?>" placeholder="https://..."
                class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400">
        </div>

        <div class="sm:col-span-2">
            <label for="a-notes" class="block text-sm font-medium text-stone-700 mb-1.5">Notes</label>
            <textarea id="a-notes" name="notes" rows="3"
                class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400"><?= esc($old['notes'] ?? '') ?></textarea>
        </div>
    </div>

    <div class="flex gap-3 pt-2 border-t border-stone-100">
        <button type="submit" class="rounded-lg bg-emerald-600 px-5 py-2 text-sm font-semibold text-white hover:bg-emerald-700 transition-colors">
            Save Animal
        </button>
        <a href="/animals" class="rounded-lg border border-stone-300 px-5 py-2 text-sm font-medium text-stone-700 hover:bg-stone-50 transition-colors">
            Cancel
        </a>
    </div>
</form>

<script>
(function () {
    var speciesEl = document.getElementById('a-species');
    var sizeEl    = document.getElementById('a-size');

    var dogSizes = [
        { value: 'small',   label: 'Small (Under 25 lb)' },
        { value: 'medium',  label: 'Medium (25–50 lb)' },
        { value: 'large',   label: 'Large (50–90 lb)' },
        { value: 'x-large', label: 'Extra Large (Over 90 lb)' },
    ];

    var genericSizes = [
        { value: 'small',  label: 'Small' },
        { value: 'medium', label: 'Medium' },
        { value: 'large',  label: 'Large' },
    ];

    function updateSizes(species, selectedValue) {
        var options = species === 'dog' ? dogSizes : genericSizes;
        sizeEl.innerHTML = '';
        options.forEach(function (opt) {
            var el = document.createElement('option');
            el.value = opt.value;
            el.textContent = opt.label;
            if (opt.value === selectedValue) { el.selected = true; }
            sizeEl.appendChild(el);
        });
        if (!sizeEl.value) { sizeEl.value = 'medium'; }
    }

    speciesEl.addEventListener('change', function () {
        updateSizes(this.value, sizeEl.value);
    });

    updateSizes(speciesEl.value, sizeEl.dataset.selected);
})();
</script>

<?= $this->endSection() ?>
