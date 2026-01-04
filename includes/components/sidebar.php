<?php
/**
 * Sidebar Navigation Component
 */

$current_page = basename($_SERVER['PHP_SELF'], '.php');
$current_dir = basename(dirname($_SERVER['PHP_SELF']));

function is_active($page)
{
    global $current_dir;
    return $current_dir === $page ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/50' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white';
}

$user = get_authenticated_user();
?>

<div class="flex h-screen w-64 flex-col border-r border-slate-800 bg-slate-950">
    <!-- Logo -->
    <div class="flex h-16 items-center border-b border-slate-800 px-6">
        <a href="/" class="flex items-center space-x-2">
            <div
                class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600">
                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
            <span class="text-xl font-bold text-white">AiScalers</span>
        </a>
    </div>

    <!-- Navigation -->
    <div class="flex-1 overflow-y-auto px-3 py-4">
        <nav class="space-y-1">
            <!-- Dashboard -->
            <a href="/"
                class="group flex items-center justify-between rounded-lg px-3 py-2.5 text-sm font-medium transition-all <?= is_active('') ?>">
                <div class="flex items-center space-x-3">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                        </path>
                    </svg>
                    <span>Dashboard</span>
                </div>
            </a>

            <!-- Sistemas -->
            <a href="/sistemas"
                class="group flex items-center justify-between rounded-lg px-3 py-2.5 text-sm font-medium transition-all <?= is_active('sistemas') ?>">
                <div class="flex items-center space-x-3">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                        </path>
                    </svg>
                    <span>Sistemas</span>
                </div>
                <?php if (is_active('sistemas') === 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/50'): ?>
                    <span class="rounded-full bg-white/20 px-2 py-0.5 text-xs font-semibold">Popular</span>
                <?php else: ?>
                    <span
                        class="rounded-full bg-indigo-500/10 px-2 py-0.5 text-xs font-semibold text-indigo-400">Popular</span>
                <?php endif; ?>
            </a>

            <!-- Prompt-Teca -->
            <a href="/prompt-teca"
                class="group flex items-center justify-between rounded-lg px-3 py-2.5 text-sm font-medium transition-all <?= is_active('prompt-teca') ?>">
                <div class="flex items-center space-x-3">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    <span>Prompt-Teca</span>
                </div>
            </a>

            <!-- Biblioteca Ejecutiva -->
            <a href="/biblioteca"
                class="group flex items-center justify-between rounded-lg px-3 py-2.5 text-sm font-medium transition-all <?= is_active('biblioteca') ?>">
                <div class="flex items-center space-x-3">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                        </path>
                    </svg>
                    <span>Biblioteca Ejecutiva</span>
                </div>
                <?php if (is_active('biblioteca') === 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/50'): ?>
                    <span class="rounded-full bg-white/20 px-2 py-0.5 text-xs font-semibold">Nuevo</span>
                <?php else: ?>
                    <span
                        class="rounded-full bg-indigo-500/10 px-2 py-0.5 text-xs font-semibold text-indigo-400">Nuevo</span>
                <?php endif; ?>
            </a>

            <!-- Consultor IA -->
            <a href="/consultor-ia"
                class="group flex items-center justify-between rounded-lg px-3 py-2.5 text-sm font-medium transition-all <?= is_active('consultor-ia') ?>">
                <div class="flex items-center space-x-3">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
                        </path>
                    </svg>
                    <span>Consultor IA</span>
                </div>
            </a>
        </nav>
    </div>

    <!-- User Profile -->
    <div class="border-t border-slate-800 p-4">
        <div class="mb-3 flex items-center space-x-3 rounded-lg bg-slate-800/50 p-3">
            <div
                class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-indigo-500 to-purple-600">
                <?php if ($user && $user['photo']): ?>
                    <img src="<?= sanitize($user['photo']) ?>" alt="<?= sanitize($user['name']) ?>"
                        class="h-full w-full rounded-full object-cover">
                <?php else: ?>
                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                <?php endif; ?>
            </div>
            <div class="flex-1 overflow-hidden">
                <p class="truncate text-sm font-medium text-white">
                    <?= $user ? sanitize($user['name']) : 'Usuario' ?>
                </p>
                <p class="truncate text-xs text-slate-400">
                    <?= $user && $user['subscription_plan'] ? ucfirst($user['subscription_plan']) : 'Plan Gratis' ?>
                </p>
            </div>
        </div>

        <a href="/perfil"
            class="mb-2 flex w-full items-center justify-start rounded-lg px-3 py-2 text-sm text-slate-400 transition hover:bg-slate-800/50 hover:text-white">
            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            Mi Perfil
        </a>

        <a href="/auth/logout.php"
            class="flex w-full items-center justify-start rounded-lg px-3 py-2 text-sm text-slate-400 transition hover:bg-slate-800/50 hover:text-red-400">
            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                </path>
            </svg>
            Cerrar Sesión
        </a>
    </div>
</div>