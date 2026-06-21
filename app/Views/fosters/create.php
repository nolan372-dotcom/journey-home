<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="mb-5">
    <a href="/fosters" class="text-xs font-medium text-stone-400 hover:text-stone-600 transition-colors">← Foster Homes</a>
    <h1 class="mt-2 text-xl font-semibold text-stone-900">Add Foster Home</h1>
</div>

<?php if (!empty($errors)): ?>
    <div class="mb-6 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-red-800 text-sm space-y-1">
        <?php foreach ($errors as $e): ?>
            <div><?= esc($e) ?></div>
        <?php endforeach ?>
    </div>
<?php endif ?>

<form action="/fosters" method="post" class="bg-white rounded-lg border border-stone-200 p-6 space-y-5 max-w-2xl">
    <?= csrf_field() ?>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div class="sm:col-span-2">
            <label for="f-name" class="block text-sm font-medium text-stone-700 mb-1.5">Full Name <span class="text-red-500">*</span></label>
            <input type="text" id="f-name" name="name" value="<?= esc($old['name'] ?? '') ?>" required autocomplete="name"
                class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400">
        </div>

        <div>
            <label for="f-email" class="block text-sm font-medium text-stone-700 mb-1.5">Email <span class="text-red-500">*</span></label>
            <input type="email" id="f-email" name="email" value="<?= esc($old['email'] ?? '') ?>" required autocomplete="email"
                class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400">
        </div>

        <div>
            <label for="f-phone" class="block text-sm font-medium text-stone-700 mb-1.5">Phone <span class="text-red-500">*</span></label>
            <input type="text" id="f-phone" name="phone" value="<?= esc($old['phone'] ?? '') ?>" required autocomplete="tel"
                class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400">
        </div>

        <div>
            <label for="f-zip" class="block text-sm font-medium text-stone-700 mb-1.5">ZIP Code</label>
            <input type="text" id="f-zip" name="area_zip" value="<?= esc($old['area_zip'] ?? '') ?>" autocomplete="postal-code"
                class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400">
        </div>

        <div>
            <label for="f-capacity" class="block text-sm font-medium text-stone-700 mb-1.5">Max Capacity <span class="text-red-500">*</span></label>
            <input type="number" id="f-capacity" name="max_capacity" value="<?= esc($old['max_capacity'] ?? '1') ?>" min="1" required
                class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400">
        </div>

        <div>
            <label for="f-species" class="block text-sm font-medium text-stone-700 mb-1.5">Species Accepted</label>
            <select id="f-species" name="species_accepted" class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400">
                <?php foreach (['both' => 'Dogs & Cats', 'dog' => 'Dogs only', 'cat' => 'Cats only'] as $val => $lbl): ?>
                    <option value="<?= $val ?>" <?= ($old['species_accepted'] ?? 'both') === $val ? 'selected' : '' ?>><?= $lbl ?></option>
                <?php endforeach ?>
            </select>
        </div>

        <div>
            <label for="f-size" class="block text-sm font-medium text-stone-700 mb-1.5">Size Preference</label>
            <input type="text" id="f-size" name="size_preference" value="<?= esc($old['size_preference'] ?? '') ?>" placeholder="e.g. small, medium"
                class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400">
        </div>

        <div>
            <label for="f-status" class="block text-sm font-medium text-stone-700 mb-1.5">Status</label>
            <select id="f-status" name="status" class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400">
                <option value="active"   <?= ($old['status'] ?? 'active') === 'active'   ? 'selected' : '' ?>>Active</option>
                <option value="paused"   <?= ($old['status'] ?? '') === 'paused'          ? 'selected' : '' ?>>Paused</option>
                <option value="inactive" <?= ($old['status'] ?? '') === 'inactive'        ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>

        <div class="sm:col-span-2 flex gap-6 pt-1">
            <label class="flex items-center gap-2 text-sm text-stone-700 cursor-pointer">
                <input type="checkbox" id="f-kids" name="has_kids" value="1" <?= !empty($old['has_kids']) ? 'checked' : '' ?>
                    class="rounded border-stone-300 text-emerald-600 focus:ring-emerald-500">
                Has kids in home
            </label>
            <label class="flex items-center gap-2 text-sm text-stone-700 cursor-pointer">
                <input type="checkbox" id="f-pets" name="has_other_pets" value="1" <?= !empty($old['has_other_pets']) ? 'checked' : '' ?>
                    class="rounded border-stone-300 text-emerald-600 focus:ring-emerald-500">
                Has other pets
            </label>
        </div>

        <div class="sm:col-span-2">
            <label for="f-notes" class="block text-sm font-medium text-stone-700 mb-1.5">Notes</label>
            <textarea id="f-notes" name="notes" rows="3"
                class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400"><?= esc($old['notes'] ?? '') ?></textarea>
        </div>
    </div>

    <div class="flex gap-3 pt-2 border-t border-stone-100">
        <button type="submit" class="rounded-lg bg-emerald-600 px-5 py-2 text-sm font-semibold text-white hover:bg-emerald-700 transition-colors">
            Save Foster Home
        </button>
        <a href="/fosters" class="rounded-lg border border-stone-300 px-5 py-2 text-sm font-medium text-stone-700 hover:bg-stone-50 transition-colors">
            Cancel
        </a>
    </div>
</form>

<?= $this->endSection() ?>
