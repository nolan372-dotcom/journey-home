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

<form action="/animals" method="post" enctype="multipart/form-data" class="bg-white rounded-lg border border-stone-200 p-6 space-y-5 max-w-2xl">
    <?= csrf_field() ?>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div class="sm:col-span-2">
            <label for="a-name" class="block text-sm font-medium text-stone-700 mb-1.5">Name <span class="text-red-500">*</span></label>
            <input type="text" id="a-name" name="name" value="<?= esc($old['name'] ?? '') ?>" required
                class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400">
        </div>

        <div>
            <label for="a-species" class="block text-sm font-medium text-stone-700 mb-1.5">Species <span class="text-red-500">*</span></label>
            <select id="a-species" name="species" required class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400">
                <?php foreach (['dog' => 'Dog', 'cat' => 'Cat', 'other' => 'Other'] as $val => $lbl): ?>
                    <option value="<?= $val ?>" <?= ($old['species'] ?? 'dog') === $val ? 'selected' : '' ?>><?= $lbl ?></option>
                <?php endforeach ?>
            </select>
        </div>

        <div>
            <label for="a-breed" class="block text-sm font-medium text-stone-700 mb-1.5">Breed</label>
            <input type="text" id="a-breed" name="breed" value="<?= esc($old['breed'] ?? '') ?>" placeholder="e.g. Beagle mix"
                class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400">
        </div>

        <div>
            <label for="a-age" class="block text-sm font-medium text-stone-700 mb-1.5">Age Group</label>
            <select id="a-age" name="age_group" class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400">
                <?php foreach (['puppy/kitten' => 'Puppy / Kitten', 'young' => 'Young', 'adult' => 'Adult', 'senior' => 'Senior'] as $val => $lbl): ?>
                    <option value="<?= $val ?>" <?= ($old['age_group'] ?? 'adult') === $val ? 'selected' : '' ?>><?= $lbl ?></option>
                <?php endforeach ?>
            </select>
        </div>

        <div>
            <label for="a-size" class="block text-sm font-medium text-stone-700 mb-1.5">Size</label>
            <select id="a-size" name="size" data-selected="<?= esc($old['size'] ?? 'medium') ?>"
                class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400">
                <?php foreach (['small' => 'Small', 'medium' => 'Medium', 'large' => 'Large'] as $val => $lbl): ?>
                    <option value="<?= $val ?>" <?= ($old['size'] ?? 'medium') === $val ? 'selected' : '' ?>><?= $lbl ?></option>
                <?php endforeach ?>
            </select>
        </div>

        <div>
            <label for="a-intake" class="block text-sm font-medium text-stone-700 mb-1.5">Intake Date <span class="text-red-500">*</span></label>
            <input type="date" id="a-intake" name="intake_date" value="<?= esc($old['intake_date'] ?? date('Y-m-d')) ?>" required
                class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400">
        </div>

        <div>
            <label for="a-status" class="block text-sm font-medium text-stone-700 mb-1.5">Status</label>
            <select id="a-status" name="status" class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400">
                <option value="needs_foster" <?= ($old['status'] ?? 'needs_foster') === 'needs_foster' ? 'selected' : '' ?>>Needs foster</option>
                <option value="in_foster"    <?= ($old['status'] ?? '') === 'in_foster'                ? 'selected' : '' ?>>In foster</option>
                <option value="adopted"      <?= ($old['status'] ?? '') === 'adopted'                  ? 'selected' : '' ?>>Adopted</option>
            </select>
        </div>

        <div class="sm:col-span-2">
            <label class="block text-sm font-medium text-stone-700 mb-1.5">
                Photo <span class="text-stone-400 font-normal">(optional)</span>
            </label>
            <div class="flex items-center gap-4">
                <img id="photo-preview" src="" alt="" class="hidden h-16 w-16 rounded-lg object-cover border border-stone-200">
                <label class="cursor-pointer rounded-lg border border-stone-300 bg-white px-4 py-2 text-sm text-stone-700 hover:bg-stone-50 transition-colors">
                    Choose photo
                    <input type="file" id="photo-input" name="photo" accept="image/*" class="sr-only">
                </label>
                <span id="photo-filename" class="text-sm text-stone-400">No file chosen</span>
            </div>
        </div>

        <div class="sm:col-span-2">
            <p class="text-sm font-medium text-stone-700 mb-2">Special needs</p>
            <div class="flex flex-wrap gap-x-6 gap-y-2">
                <label class="flex items-center gap-2 text-sm text-stone-700 cursor-pointer">
                    <input type="checkbox" name="needs_medical" value="1" <?= !empty($old['needs_medical']) ? 'checked' : '' ?> class="rounded border-stone-300 text-orange-600 focus:ring-orange-500">
                    Medical case
                </label>
                <label class="flex items-center gap-2 text-sm text-stone-700 cursor-pointer">
                    <input type="checkbox" name="needs_behavior" value="1" <?= !empty($old['needs_behavior']) ? 'checked' : '' ?> class="rounded border-stone-300 text-orange-600 focus:ring-orange-500">
                    Behavior case
                </label>
                <label class="flex items-center gap-2 text-sm text-stone-700 cursor-pointer">
                    <input type="checkbox" name="needs_fenced_yard" value="1" <?= !empty($old['needs_fenced_yard']) ? 'checked' : '' ?> class="rounded border-stone-300 text-orange-600 focus:ring-orange-500">
                    Requires fenced yard
                </label>
                <label class="flex items-center gap-2 text-sm text-stone-700 cursor-pointer">
                    <input type="checkbox" name="no_other_dogs" value="1" <?= !empty($old['no_other_dogs']) ? 'checked' : '' ?> class="rounded border-stone-300 text-orange-600 focus:ring-orange-500">
                    No other dogs
                </label>
                <label class="flex items-center gap-2 text-sm text-stone-700 cursor-pointer">
                    <input type="checkbox" name="no_dogs" value="1" <?= !empty($old['no_dogs']) ? 'checked' : '' ?> class="rounded border-stone-300 text-orange-600 focus:ring-orange-500">
                    No dogs
                </label>
                <label class="flex items-center gap-2 text-sm text-stone-700 cursor-pointer">
                    <input type="checkbox" name="no_cats" value="1" <?= !empty($old['no_cats']) ? 'checked' : '' ?> class="rounded border-stone-300 text-orange-600 focus:ring-orange-500">
                    No cats
                </label>
                <label class="flex items-center gap-2 text-sm text-stone-700 cursor-pointer">
                    <input type="checkbox" name="no_other_cats" value="1" <?= !empty($old['no_other_cats']) ? 'checked' : '' ?> class="rounded border-stone-300 text-orange-600 focus:ring-orange-500">
                    No other cats
                </label>
                <label class="flex items-center gap-2 text-sm text-stone-700 cursor-pointer">
                    <input type="checkbox" name="no_kids" value="1" <?= !empty($old['no_kids']) ? 'checked' : '' ?> class="rounded border-stone-300 text-orange-600 focus:ring-orange-500">
                    No kids
                </label>
            </div>
        </div>

        <div class="sm:col-span-2">
            <p class="text-sm font-medium text-stone-700 mb-2">Other needs</p>
            <div class="flex items-center gap-2 max-w-xs">
                <div class="relative flex-1">
                    <input type="text" id="custom-need-input" placeholder="e.g. Crippled, blind in one eye…"
                        class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 pr-9 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400">
                    <button type="button" id="custom-need-add"
                        class="absolute right-2 top-1/2 -translate-y-1/2 text-stone-400 hover:text-orange-600 transition-colors text-lg leading-none">&#8594;</button>
                </div>
            </div>
            <div id="custom-needs-tags" class="flex flex-wrap gap-1.5 mt-2"></div>
            <div id="custom-needs-inputs"></div>
        </div>

        <div class="sm:col-span-2">
            <label for="a-notes" class="block text-sm font-medium text-stone-700 mb-1.5">Notes</label>
            <textarea id="a-notes" name="notes" rows="3"
                class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400"><?= esc($old['notes'] ?? '') ?></textarea>
        </div>
    </div>

    <div class="flex gap-3 pt-2 border-t border-stone-100">
        <button type="submit" class="rounded-lg bg-orange-600 px-5 py-2 text-sm font-semibold text-white hover:bg-orange-700 transition-colors">
            Save Animal
        </button>
        <a href="/animals" class="rounded-lg border border-stone-300 px-5 py-2 text-sm font-medium text-stone-700 hover:bg-stone-50 transition-colors">
            Cancel
        </a>
    </div>
</form>

<script>
(function () {
    var tags     = [];
    var tagsEl   = document.getElementById('custom-needs-tags');
    var inputsEl = document.getElementById('custom-needs-inputs');
    var input    = document.getElementById('custom-need-input');
    var addBtn   = document.getElementById('custom-need-add');

    function render() {
        tagsEl.innerHTML   = '';
        inputsEl.innerHTML = '';
        tags.forEach(function (val) {
            var tag = document.createElement('span');
            tag.className = 'inline-flex items-center gap-1 rounded-full bg-stone-100 border border-stone-300 text-stone-700 text-xs font-medium px-3 py-1';
            tag.innerHTML = '<span>' + esc(val) + '</span><button type="button" class="ml-1 leading-none hover:text-red-500 text-base">&times;</button>';
            tag.querySelector('button').addEventListener('click', function () { remove(val); });
            tagsEl.appendChild(tag);
            var hidden = document.createElement('input');
            hidden.type  = 'hidden';
            hidden.name  = 'custom_needs[]';
            hidden.value = val;
            inputsEl.appendChild(hidden);
        });
    }

    function esc(str) {
        return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    function add() {
        var val = input.value.trim();
        if (!val || tags.indexOf(val) !== -1) return;
        tags.push(val);
        input.value = '';
        render();
    }

    function remove(val) {
        tags = tags.filter(function (v) { return v !== val; });
        render();
    }

    addBtn.addEventListener('click', add);
    input.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') { e.preventDefault(); add(); }
    });
})();
</script>

<script>
(function () {
    var input   = document.getElementById('photo-input');
    var preview = document.getElementById('photo-preview');
    var label   = document.getElementById('photo-filename');
    input.addEventListener('change', function () {
        var file = this.files[0];
        if (!file) return;
        label.textContent = file.name;
        var reader = new FileReader();
        reader.onload = function (e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    });
})();
</script>

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
