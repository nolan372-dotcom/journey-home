<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="flex items-center justify-center min-h-[60vh]">
    <div class="w-full max-w-sm">
        <div class="text-center mb-8">
            <h1 class="text-xl font-semibold text-stone-900">Sign in</h1>
            <p class="text-sm text-stone-400 mt-1">Foster coordination tool</p>
        </div>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="mb-5 flex items-center gap-3 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-red-800 text-sm">
                <svg class="w-4 h-4 text-red-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126z"/></svg>
                <?= esc(session()->getFlashdata('error')) ?>
            </div>
        <?php endif ?>

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="mb-5 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-red-800 text-sm space-y-1">
                <?php foreach ((array) session()->getFlashdata('errors') as $e): ?>
                    <div><?= esc($e) ?></div>
                <?php endforeach ?>
            </div>
        <?php endif ?>

        <form action="<?= site_url('login') ?>" method="post" class="bg-white rounded-lg border border-stone-200 p-6 space-y-4">
            <?= csrf_field() ?>

            <div>
                <label for="email" class="block text-sm font-medium text-stone-700 mb-1.5">Email</label>
                <input type="email" id="email" name="email" value="<?= old('email') ?>"
                    required autofocus autocomplete="email"
                    class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-stone-700 mb-1.5">Password</label>
                <input type="password" id="password" name="password"
                    required autocomplete="current-password"
                    class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-800 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400">
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" id="remember" name="remember" value="1"
                    class="rounded border-stone-300 text-emerald-600 focus:ring-emerald-500">
                <label for="remember" class="text-sm text-stone-600">Remember me for 30 days</label>
            </div>

            <button type="submit"
                class="w-full rounded-lg bg-emerald-600 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700 transition-colors">
                Sign in
            </button>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
