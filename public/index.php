<?php
/**
 * Homepage
 */

$page_title = 'Inicio';
$require_auth = false;

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="flex min-h-screen items-center justify-center bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950">
    <div class="text-center px-4">
        <!-- Logo -->
        <div class="mb-8 flex justify-center">
            <div
                class="flex h-20 w-20 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 shadow-2xl shadow-indigo-500/50">
                <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
        </div>

        <!-- Title -->
        <h1 class="mb-4 text-6xl font-bold text-white">
            AiScalers
        </h1>

        <!-- Tagline -->
        <p class="mb-8 text-xl text-slate-400 max-w-2xl mx-auto">
            Club privado para empresarios que sistematizan con IA
        </p>

        <!-- CTA Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <?php if (is_logged_in()): ?>
                <a href="/sistemas"
                    class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-8 py-4 font-semibold text-white transition hover:bg-indigo-700 shadow-lg shadow-indigo-500/50">
                    <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                        </path>
                    </svg>
                    Ver Sistemas
                </a>
                <a href="/biblioteca"
                    class="inline-flex items-center justify-center rounded-lg border border-slate-700 bg-slate-800/50 px-8 py-4 font-semibold text-white transition hover:bg-slate-800">
                    <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                        </path>
                    </svg>
                    Biblioteca Ejecutiva
                </a>
            <?php else: ?>
                <a href="/auth/login.php"
                    class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-8 py-4 font-semibold text-white transition hover:bg-indigo-700 shadow-lg shadow-indigo-500/50">
                    Iniciar Sesión
                </a>
                <a href="/auth/register.php"
                    class="inline-flex items-center justify-center rounded-lg border border-slate-700 bg-slate-800/50 px-8 py-4 font-semibold text-white transition hover:bg-slate-800">
                    Registrarse
                </a>
            <?php endif; ?>
        </div>

        <!-- Features -->
        <div class="mt-16 grid grid-cols-1 md:grid-cols-3 gap-8 max-w-4xl mx-auto">
            <div class="rounded-xl border border-slate-800 bg-slate-900/50 p-6 backdrop-blur">
                <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-lg bg-indigo-500/10">
                    <svg class="h-6 w-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                        </path>
                    </svg>
                </div>
                <h3 class="mb-2 text-lg font-semibold text-white">Sistemas</h3>
                <p class="text-sm text-slate-400">Blueprints de n8n y Make listos para copiar y usar</p>
            </div>

            <div class="rounded-xl border border-slate-800 bg-slate-900/50 p-6 backdrop-blur">
                <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-lg bg-purple-500/10">
                    <svg class="h-6 w-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                        </path>
                    </svg>
                </div>
                <h3 class="mb-2 text-lg font-semibold text-white">Biblioteca Ejecutiva</h3>
                <p class="text-sm text-slate-400">Libros con podcasts resumen para decidir antes de leer</p>
            </div>

            <div class="rounded-xl border border-slate-800 bg-slate-900/50 p-6 backdrop-blur">
                <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-lg bg-pink-500/10">
                    <svg class="h-6 w-6 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <h3 class="mb-2 text-lg font-semibold text-white">Prompt-Teca</h3>
                <p class="text-sm text-slate-400">Biblioteca de prompts probados para IA</p>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>