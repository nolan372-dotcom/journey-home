<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Foster Coordinator') ?> — Take Me Home Pet Rescue</title>
    <link rel="icon" type="image/png" href="/favicon.png">
    <link rel="shortcut icon" type="image/png" href="/favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Outfit', 'system-ui', 'sans-serif'] }
                }
            }
        }
    </script>
    <?= $this->renderSection('head') ?>
</head>
<body class="bg-stone-50 text-stone-800 min-h-screen font-sans antialiased m-0">

<nav class="bg-stone-900 border-b border-stone-800">
    <div class="max-w-5xl mx-auto px-5 py-0 flex items-center justify-between h-14">
        <a href="/" class="flex items-center gap-2.5">
            <img src="/favicon.png" alt="" class="h-7 w-7 rounded-md">
            <span class="text-white font-semibold text-sm tracking-tight">Take Me Home Pet Rescue</span>
        </a>
        <div class="flex items-center gap-1">
            <?php if (auth()->loggedIn()): ?>
            <a href="/" class="px-3 py-1.5 rounded-md text-stone-400 hover:text-white hover:bg-stone-800 text-sm font-medium transition-colors">Dashboard</a>
            <a href="/fosters" class="px-3 py-1.5 rounded-md text-stone-400 hover:text-white hover:bg-stone-800 text-sm font-medium transition-colors">Fosters</a>
            <a href="/animals" class="px-3 py-1.5 rounded-md text-stone-400 hover:text-white hover:bg-stone-800 text-sm font-medium transition-colors">Animals</a>
            <a href="/users" class="px-3 py-1.5 rounded-md text-stone-400 hover:text-white hover:bg-stone-800 text-sm font-medium transition-colors">Users</a>
            <span class="ml-3 text-xs text-stone-500 hidden sm:inline"><?= esc(auth()->user()->getEmail()) ?></span>
            <a href="<?= site_url('logout') ?>" class="ml-1 px-3 py-1.5 rounded-md text-stone-400 hover:text-white hover:bg-stone-800 text-xs font-medium transition-colors">Log out</a>
            <?php endif ?>
        </div>
    </div>
</nav>

<main class="max-w-5xl mx-auto px-5 py-8">

    <?php if (session()->getFlashdata('success')): ?>
        <div class="mb-6 flex items-center gap-3 rounded-lg bg-orange-50 border border-orange-200 px-4 py-3 text-orange-800 text-sm font-medium">
            <svg class="w-4 h-4 text-orange-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
            <?= esc(session()->getFlashdata('success')) ?>
        </div>
    <?php endif ?>

    <?= $this->renderSection('content') ?>

</main>

</body>
</html>
