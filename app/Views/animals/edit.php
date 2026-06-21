<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="mb-5">
    <a href="/animals/<?= $animal['id'] ?>" class="text-xs font-medium text-stone-400 hover:text-stone-600 transition-colors">← <?= esc($animal['name']) ?></a>
    <h1 class="mt-2 text-xl font-semibold text-stone-900">Edit Animal</h1>
</div>

<?php if (!empty($errors)): ?>
    <div class="mb-6 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-red-800 text-sm space-y-1">
        <?php foreach ($errors as $e): ?>
            <div><?= esc($e) ?></div>
        <?php endforeach ?>
    </div>
<?php endif ?>

<form action="/animals/<?= $animal['id'] ?>" method="post" class="bg-white rounded-lg border border-stone-200 p-6 space-y-5 max-w-2xl">
    <?= csrf_field() ?>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div class="sm:col-span-2">
            <label for="a-name" class="block text-sm font-medium text-stone-700 mb-1.5">Name <span class="text-red-500">*</span></label>
            <input type="text" id="a-name" name="name" value="<?= esc($animal['name']) ?>" required
                class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400">
        </div>

        <div>
            <label for="a-species" class="block text-sm font-medium text-stone-700 mb-1.5">Species <span class="text-red-500">*</span></label>
            <select id="a-species" name="species" required class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400">
                <?php foreach (['dog' => 'Dog', 'cat' => 'Cat', 'other' => 'Other'] as $val => $lbl): ?>
                    <option value="<?= $val ?>" <?= $animal['species'] === $val ? 'selected' : '' ?>><?= $lbl ?></option>
                <?php endforeach ?>
            </select>
        </div>

        <div>
            <label for="a-breed" class="block text-sm font-medium text-stone-700 mb-1.5">Breed</label>
            <input type="text" id="a-breed" name="breed" value="<?= esc($animal['breed'] ?? '') ?>"
                class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400">
        </div>

        <div>
            <label for="a-age" class="block text-sm font-medium text-stone-700 mb-1.5">Age Group</label>
            <select id="a-age" name="age_group" class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400">
                <?php foreach (['puppy/kitten' => 'Puppy / Kitten', 'young' => 'Young', 'adult' => 'Adult', 'senior' => 'Senior'] as $val => $lbl): ?>
                    <option value="<?= $val ?>" <?= $animal['age_group'] === $val ? 'selected' : '' ?>><?= $lbl ?></option>
                <?php endforeach ?>
            </select>
        </div>

        <div>
            <label for="a-size" class="block text-sm font-medium text-stone-700 mb-1.5">Size</label>
            <select id="a-size" name="size" class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400">
                <?php foreach (['small' => 'Small', 'medium' => 'Medium', 'large' => 'Large'] as $val => $lbl): ?>
                    <option value="<?= $val ?>" <?= $animal['size'] === $val ? 'selected' : '' ?>><?= $lbl ?></option>
                <?php endforeach ?>
            </select>
        </div>

        <div>
            <label for="a-intake" class="block text-sm font-medium text-stone-700 mb-1.5">Intake Date <span class="text-red-500">*</span></label>
            <input type="date" id="a-intake" name="intake_date" value="<?= esc($animal['intake_date']) ?>" required
                class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400">
        </div>

        <div>
            <label for="a-status" class="block text-sm font-medium text-stone-700 mb-1.5">Status</label>
            <select id="a-status" name="status" class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400">
                <option value="needs_foster" <?= $animal['status'] === 'needs_foster' ? 'selected' : '' ?>>Needs foster</option>
                <option value="in_foster"    <?= $animal['status'] === 'in_foster'    ? 'selected' : '' ?>>In foster</option>
                <option value="adopted"      <?= $animal['status'] === 'adopted'      ? 'selected' : '' ?>>Adopted</option>
            </select>
        </div>

        <div class="sm:col-span-2">
            <label for="a-photo" class="block text-sm font-medium text-stone-700 mb-1.5">
                Photo URL <span class="text-stone-400 font-normal">(optional)</span>
            </label>
            <input type="url" id="a-photo" name="photo_url" value="<?= esc($animal['photo_url'] ?? '') ?>" placeholder="https://..."
                class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400">
        </div>

        <div class="sm:col-span-2">
            <label for="a-notes" class="block text-sm font-medium text-stone-700 mb-1.5">Notes</label>
            <textarea id="a-notes" name="notes" rows="3"
                class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-orange-400"><?= esc($animal['notes'] ?? '') ?></textarea>
        </div>
    </div>

    <input type="hidden" name="return_to" value="<?= esc($returnTo) ?>">
    <div class="flex items-center justify-between pt-2 border-t border-stone-100">
        <div class="flex gap-3">
            <button type="submit" class="rounded-lg bg-orange-600 px-5 py-2 text-sm font-semibold text-white hover:bg-orange-700 transition-colors">
                Save Changes
            </button>
            <a href="<?= esc($returnTo) ?>" class="rounded-lg border border-stone-300 px-5 py-2 text-sm font-medium text-stone-700 hover:bg-stone-50 transition-colors">
                Cancel
            </a>
        </div>
        <?php if (empty($animal['archived'])): ?>
        <a href="/animals/<?= $animal['id'] ?>"
           class="text-xs font-medium text-stone-400 hover:text-red-600 transition-colors">
            Archive from the animal page →
        </a>
        <?php endif ?>
    </div>
</form>

<?= $this->endSection() ?>
