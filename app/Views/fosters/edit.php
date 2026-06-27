<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="mb-5">
    <a href="/fosters/<?= $foster['id'] ?>" class="text-xs font-medium text-stone-400 hover:text-stone-600 transition-colors">← <?= esc($foster['name']) ?></a>
    <h1 class="mt-2 text-xl font-semibold text-stone-900">Edit Foster Home</h1>
</div>

<?php if (!empty($errors)): ?>
    <div class="mb-6 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-red-800 text-sm space-y-1">
        <?php foreach ($errors as $e): ?>
            <div><?= esc($e) ?></div>
        <?php endforeach ?>
    </div>
<?php endif ?>

<form action="/fosters/<?= $foster['id'] ?>" method="post" class="bg-white rounded-lg border border-stone-200 p-6 space-y-5 max-w-2xl">
    <?= csrf_field() ?>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div class="sm:col-span-2">
            <label for="f-name" class="block text-sm font-medium text-stone-700 mb-1.5">Full Name <span class="text-red-500">*</span></label>
            <input type="text" id="f-name" name="name" value="<?= esc($foster['name']) ?>" required autocomplete="name"
                class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400">
        </div>

        <div>
            <label for="f-email" class="block text-sm font-medium text-stone-700 mb-1.5">Email <span class="text-red-500">*</span></label>
            <input type="email" id="f-email" name="email" value="<?= esc($foster['email']) ?>" required autocomplete="email"
                class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400">
        </div>

        <div>
            <label for="f-phone" class="block text-sm font-medium text-stone-700 mb-1.5">Phone <span class="text-red-500">*</span></label>
            <input type="text" id="f-phone" name="phone" value="<?= esc($foster['phone']) ?>" required autocomplete="tel"
                class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400">
        </div>

        <div>
            <label for="f-zip" class="block text-sm font-medium text-stone-700 mb-1.5">ZIP Code</label>
            <input type="text" id="f-zip" name="area_zip" value="<?= esc($foster['area_zip']) ?>" autocomplete="postal-code"
                class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400">
        </div>

        <div>
            <label for="f-capacity" class="block text-sm font-medium text-stone-700 mb-1.5">Max Capacity <span class="text-red-500">*</span></label>
            <input type="number" id="f-capacity" name="max_capacity" value="<?= esc($foster['max_capacity']) ?>" min="1" required
                class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400">
        </div>

        <div>
            <label for="f-species" class="block text-sm font-medium text-stone-700 mb-1.5">Species Accepted</label>
            <select id="f-species" name="species_accepted" class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400">
                <?php foreach (['both' => 'Dogs & Cats', 'dog' => 'Dogs only', 'cat' => 'Cats only'] as $val => $lbl): ?>
                    <option value="<?= $val ?>" <?= $foster['species_accepted'] === $val ? 'selected' : '' ?>><?= $lbl ?></option>
                <?php endforeach ?>
            </select>
        </div>


        <div>
            <label for="f-status" class="block text-sm font-medium text-stone-700 mb-1.5">Status</label>
            <select id="f-status" name="status" class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400">
                <option value="active"   <?= $foster['status'] === 'active'   ? 'selected' : '' ?>>Active</option>
                <option value="paused"   <?= $foster['status'] === 'paused'   ? 'selected' : '' ?>>Paused</option>
                <option value="inactive" <?= $foster['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>

        <div class="sm:col-span-2">
            <p class="text-sm font-medium text-stone-700 mb-2">Size Preferences</p>
            <div class="flex flex-wrap gap-x-6 gap-y-2">
                <label class="flex items-center gap-2 text-sm text-stone-700 cursor-pointer">
                    <input type="checkbox" name="ok_small" value="1" <?= !empty($foster['ok_small']) ? 'checked' : '' ?>
                        class="rounded border-stone-300 text-orange-600 focus:ring-orange-500">
                    Small
                </label>
                <label class="flex items-center gap-2 text-sm text-stone-700 cursor-pointer">
                    <input type="checkbox" name="ok_medium" value="1" <?= !empty($foster['ok_medium']) ? 'checked' : '' ?>
                        class="rounded border-stone-300 text-orange-600 focus:ring-orange-500">
                    Medium
                </label>
                <label class="flex items-center gap-2 text-sm text-stone-700 cursor-pointer">
                    <input type="checkbox" name="ok_large" value="1" <?= !empty($foster['ok_large']) ? 'checked' : '' ?>
                        class="rounded border-stone-300 text-orange-600 focus:ring-orange-500">
                    Large
                </label>
                <label class="flex items-center gap-2 text-sm text-stone-700 cursor-pointer">
                    <input type="checkbox" name="ok_xlarge" value="1" <?= !empty($foster['ok_xlarge']) ? 'checked' : '' ?>
                        class="rounded border-stone-300 text-orange-600 focus:ring-orange-500">
                    Extra Large
                </label>
            </div>
        </div>

        <div class="sm:col-span-2">
            <p class="text-sm font-medium text-stone-700 mb-2">Can handle</p>
            <div class="flex flex-wrap gap-x-6 gap-y-2">
                <label class="flex items-center gap-2 text-sm text-stone-700 cursor-pointer">
                    <input type="checkbox" name="ok_puppies" value="1" <?= $foster['ok_puppies'] ? 'checked' : '' ?>
                        class="rounded border-stone-300 text-orange-600 focus:ring-orange-500">
                    Puppies
                </label>
                <label class="flex items-center gap-2 text-sm text-stone-700 cursor-pointer">
                    <input type="checkbox" name="ok_kittens" value="1" <?= $foster['ok_kittens'] ? 'checked' : '' ?>
                        class="rounded border-stone-300 text-orange-600 focus:ring-orange-500">
                    Kittens
                </label>
                <label class="flex items-center gap-2 text-sm text-stone-700 cursor-pointer">
                    <input type="checkbox" name="ok_medical" value="1" <?= $foster['ok_medical'] ? 'checked' : '' ?>
                        class="rounded border-stone-300 text-orange-600 focus:ring-orange-500">
                    Medical cases
                </label>
                <label class="flex items-center gap-2 text-sm text-stone-700 cursor-pointer">
                    <input type="checkbox" name="ok_behavior" value="1" <?= $foster['ok_behavior'] ? 'checked' : '' ?>
                        class="rounded border-stone-300 text-orange-600 focus:ring-orange-500">
                    Behavior cases
                </label>
                <label class="flex items-center gap-2 text-sm text-stone-700 cursor-pointer">
                    <input type="checkbox" name="has_fenced_yard" value="1" <?= $foster['has_fenced_yard'] ? 'checked' : '' ?>
                        class="rounded border-stone-300 text-orange-600 focus:ring-orange-500">
                    Fenced yard
                </label>
                <label class="flex items-center gap-2 text-sm text-stone-700 cursor-pointer">
                    <input type="checkbox" id="f-kids" name="has_kids" value="1" <?= $foster['has_kids'] ? 'checked' : '' ?>
                        class="rounded border-stone-300 text-orange-600 focus:ring-orange-500">
                    Has kids in home
                </label>
                <label class="flex items-center gap-2 text-sm text-stone-700 cursor-pointer">
                    <input type="checkbox" id="f-pets" name="has_other_pets" value="1" <?= $foster['has_other_pets'] ? 'checked' : '' ?>
                        class="rounded border-stone-300 text-orange-600 focus:ring-orange-500">
                    Has other pets
                </label>
            </div>
        </div>

        <div class="sm:col-span-2">
            <p class="text-sm font-medium text-stone-700 mb-2">Other things they can handle</p>
            <div class="flex items-center gap-2 max-w-xs">
                <div class="relative flex-1">
                    <input type="text" id="custom-handle-input" placeholder="e.g. Diabetic, bottle fed…"
                        class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 pr-9 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400">
                    <button type="button" id="custom-handle-add"
                        class="absolute right-2 top-1/2 -translate-y-1/2 text-stone-400 hover:text-orange-600 transition-colors text-lg leading-none">&#8594;</button>
                </div>
            </div>
            <div id="custom-handle-tags" class="flex flex-wrap gap-1.5 mt-2"></div>
            <div id="custom-handle-inputs"></div>
        </div>

        <div class="sm:col-span-2">
            <label for="f-notes" class="block text-sm font-medium text-stone-700 mb-1.5">Notes</label>
            <textarea id="f-notes" name="notes" rows="3"
                class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400"><?= esc($foster['notes']) ?></textarea>
        </div>
    </div>

    <input type="hidden" name="return_to" value="<?= esc($returnTo) ?>">

    <div class="flex gap-3 pt-2 border-t border-stone-100">
        <button type="submit" class="rounded-lg bg-orange-600 px-5 py-2 text-sm font-semibold text-white hover:bg-orange-700 transition-colors">
            Save Changes
        </button>
        <a href="<?= esc($returnTo) ?>" class="rounded-lg border border-stone-300 px-5 py-2 text-sm font-medium text-stone-700 hover:bg-stone-50 transition-colors">
            Cancel
        </a>
    </div>
</form>

<script>
(function () {
    var tags     = <?= json_encode($foster['custom_can_handle'] ? json_decode($foster['custom_can_handle'], true) ?? [] : []) ?>;
    var tagsEl   = document.getElementById('custom-handle-tags');
    var inputsEl = document.getElementById('custom-handle-inputs');
    var input    = document.getElementById('custom-handle-input');
    var addBtn   = document.getElementById('custom-handle-add');

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
            hidden.name  = 'custom_can_handle[]';
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

    render();
})();
</script>

<?= $this->endSection() ?>
