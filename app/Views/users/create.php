<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="mb-5">
    <a href="/users" class="text-xs font-medium text-stone-400 hover:text-stone-600 transition-colors">← Users</a>
    <h1 class="mt-2 text-xl font-semibold text-stone-900">Add User</h1>
</div>

<?php if (!empty($errors)): ?>
    <div class="mb-6 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-red-800 text-sm space-y-1">
        <?php foreach ($errors as $e): ?>
            <div><?= esc($e) ?></div>
        <?php endforeach ?>
    </div>
<?php endif ?>

<form action="/users" method="post" class="bg-white rounded-lg border border-stone-200 p-6 space-y-5 max-w-md">
    <?= csrf_field() ?>

    <div>
        <label for="u-email" class="block text-sm font-medium text-stone-700 mb-1.5">Email <span class="text-red-500">*</span></label>
        <input type="email" id="u-email" name="email" value="<?= esc($old['email'] ?? '') ?>" required
            class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400">
    </div>

    <div>
        <label for="u-password" class="block text-sm font-medium text-stone-700 mb-1.5">Password <span class="text-red-500">*</span></label>
        <input type="password" id="u-password" name="password" required minlength="8"
            class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400">
        <p class="mt-1 text-xs text-stone-400">Minimum 8 characters.</p>
    </div>

    <div>
        <label for="u-password-confirm" class="block text-sm font-medium text-stone-700 mb-1.5">Confirm Password <span class="text-red-500">*</span></label>
        <input type="password" id="u-password-confirm" name="password_confirm" required
            class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400">
    </div>

    <div class="flex gap-3 pt-2 border-t border-stone-100">
        <button type="submit" class="rounded-lg bg-emerald-600 px-5 py-2 text-sm font-semibold text-white hover:bg-emerald-700 transition-colors">
            Add User
        </button>
        <a href="/users" class="rounded-lg border border-stone-300 px-5 py-2 text-sm font-medium text-stone-700 hover:bg-stone-50 transition-colors">
            Cancel
        </a>
    </div>
</form>

<?= $this->endSection() ?>
