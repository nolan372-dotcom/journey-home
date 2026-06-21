<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="flex items-center justify-between mb-7">
    <h1 class="text-xl font-semibold text-stone-900">Users</h1>
    <a href="/users/new" class="inline-flex items-center gap-1.5 rounded-lg bg-orange-600 px-4 py-2 text-sm font-semibold text-white hover:bg-orange-700 transition-colors">
        + Add User
    </a>
</div>

<div class="overflow-x-auto rounded-lg border border-stone-200 bg-white">
    <table class="min-w-full divide-y divide-stone-200 text-sm">
        <thead class="bg-stone-50">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-stone-500 uppercase tracking-wider">Email</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-stone-500 uppercase tracking-wider">Added</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-stone-500 uppercase tracking-wider">Status</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-stone-100">
            <?php foreach ($users as $u): ?>
            <?php $isMe = (int) $u['id'] === (int) auth()->id(); ?>
            <tr class="hover:bg-stone-50 transition-colors">
                <td class="px-4 py-3 font-medium text-stone-800">
                    <?= esc($u['email']) ?>
                    <?php if ($isMe): ?>
                        <span class="ml-1.5 text-xs text-stone-400">(you)</span>
                    <?php endif ?>
                </td>
                <td class="px-4 py-3 text-stone-400"><?= esc(date('M j, Y', strtotime($u['created_at']))) ?></td>
                <td class="px-4 py-3">
                    <?php $color = $u['active'] ? 'bg-orange-100 text-orange-800' : 'bg-stone-100 text-stone-500'; ?>
                    <span class="inline-block rounded-full px-2.5 py-0.5 text-xs font-semibold <?= $color ?>">
                        <?= $u['active'] ? 'Active' : 'Inactive' ?>
                    </span>
                </td>
                <td class="px-4 py-3 text-right">
                    <?php if (!$isMe || !$u['active']): ?>
                    <form action="/users/<?= $u['id'] ?>/toggle-active" method="post"
                          onsubmit="return confirm('<?= $u['active'] ? 'Deactivate' : 'Activate' ?> <?= esc($u['email'], 'attr') ?>?')">
                        <?= csrf_field() ?>
                        <button type="submit" class="text-xs font-medium text-stone-400 hover:text-<?= $u['active'] ? 'red' : 'orange' ?>-600 transition-colors">
                            <?= $u['active'] ? 'Deactivate' : 'Activate' ?>
                        </button>
                    </form>
                    <?php endif ?>
                </td>
            </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
