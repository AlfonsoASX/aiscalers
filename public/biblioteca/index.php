<?php
/**
 * Library Page (Biblioteca Ejecutiva)
 */

$page_title = 'Biblioteca Ejecutiva';
require_once __DIR__ . '/../../includes/header.php';

// Get categories
$categories = get_book_categories();
$selected_category = $_GET['category'] ?? '';
$search = $_GET['search'] ?? '';

// Build query
$db = db();
$sql = "SELECT * FROM library WHERE 1=1";
$params = [];

if ($selected_category) {
    $sql .= " AND category = ?";
    $params[] = $selected_category;
}

if ($search) {
    $sql .= " AND (title LIKE ? OR author LIKE ? OR description LIKE ? OR MATCH(title, description, author) AGAINST(?))";
    $term = "%$search%";
    $params[] = $term;
    $params[] = $term;
    $params[] = $term;
    $params[] = $search;
}

$sql .= " ORDER BY featured DESC, audio_plays DESC";

$stmt = $db->prepare($sql);
$stmt->execute($params);
$books = $stmt->fetchAll();
?>

<div class="flex h-screen bg-slate-950">
    <?php require_once __DIR__ . '/../../includes/components/sidebar.php'; ?>

    <main class="flex-1 overflow-y-auto bg-slate-950">
        <!-- Header -->
        <div class="border-b border-slate-800 bg-slate-950/50 backdrop-blur sticky top-0 z-10">
            <div class="px-8 py-6">
                <div class="mb-6 flex items-center space-x-3">
                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-purple-500 to-pink-600">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-white">Biblioteca Ejecutiva</h1>
                        <p class="text-slate-400">Libros con resúmenes en podcast para decidir antes de leer</p>
                    </div>
                </div>

                <!-- Feature Highlight -->
                <div class="mb-6 rounded-lg border border-purple-500/30 bg-purple-500/10 p-4">
                    <div class="flex items-start space-x-3">
                        <svg class="mt-0.5 h-5 w-5 text-purple-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z">
                            </path>
                        </svg>
                        <div>
                            <h3 class="font-semibold text-purple-300">Escucha antes de leer</h3>
                            <p class="text-sm text-purple-200/80">Cada libro incluye un podcast resumen. Escúchalo para
                                decidir si vale la pena invertir tu tiempo en el libro completo.</p>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <form class="space-y-4" method="GET">
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <input type="text" name="search" value="<?= sanitize($search) ?>"
                            placeholder="Buscar por título, autor o tema..."
                            class="w-full rounded-lg border border-slate-800 bg-slate-900/50 py-2 pl-10 pr-4 text-white placeholder-slate-400 focus:border-purple-500 focus:outline-none focus:ring-1 focus:ring-purple-500">
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <a href="?"
                            class="rounded-full border <?= !$selected_category ? 'border-purple-500 bg-purple-500/10 text-white' : 'border-slate-700 text-slate-300 hover:border-purple-500 hover:text-white' ?> px-4 py-1.5 text-sm font-medium transition">
                            Todos
                        </a>
                        <?php foreach ($categories as $cat): ?>
                            <a href="?category=<?= urlencode($cat) ?>"
                                class="rounded-full border <?= $selected_category === $cat ? 'border-purple-500 bg-purple-500/10 text-white' : 'border-slate-700 text-slate-300 hover:border-purple-500 hover:text-white' ?> px-4 py-1.5 text-sm font-medium transition">
                                <?= $cat ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </form>
            </div>
        </div>

        <!-- Content -->
        <div class="px-8 py-8">
            <?php if (empty($books)): ?>
                <div class="flex h-64 flex-col items-center justify-center text-center">
                    <svg class="mb-4 h-12 w-12 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                        </path>
                    </svg>
                    <h3 class="text-lg font-medium text-slate-300">No se encontraron libros</h3>
                </div>
            <?php else: ?>
                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    <?php foreach ($books as $book): ?>
                        <div
                            class="group overflow-hidden rounded-xl border border-slate-800 bg-slate-900/50 backdrop-blur transition hover:border-purple-500/50 hover:shadow-lg hover:shadow-purple-500/10">
                            <div class="p-5">
                                <div class="mb-4 flex items-start justify-between">
                                    <span
                                        class="inline-flex items-center rounded-full border border-purple-500/30 bg-purple-500/10 px-2 py-0.5 text-xs font-medium text-purple-400">
                                        <svg class="mr-1 h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z">
                                            </path>
                                        </svg>
                                        Podcast
                                    </span>
                                    <span
                                        class="rounded px-2.5 py-0.5 text-xs font-medium border border-slate-700 text-slate-400">
                                        <?= sanitize($book['category']) ?>
                                    </span>
                                </div>

                                <!-- Cover -->
                                <div class="relative mb-4 aspect-[3/4] w-full overflow-hidden rounded-lg bg-slate-800">
                                    <img src="<?= sanitize($book['cover_url']) ?>" alt="<?= sanitize($book['title']) ?>"
                                        class="h-full w-full object-cover transition-transform group-hover:scale-105">
                                </div>

                                <h3 class="mb-1 text-lg font-bold text-white group-hover:text-purple-400 transition-colors">
                                    <?= sanitize($book['title']) ?>
                                </h3>
                                <p class="mb-3 text-sm text-slate-400">por
                                    <?= sanitize($book['author']) ?>
                                </p>

                                <!-- Player HTML5 -->
                                <div class="mb-4 rounded-lg border border-slate-700 bg-slate-800/50 p-3">
                                    <audio controls class="w-full h-8" preload="none">
                                        <source src="<?= sanitize($book['audio_url']) ?>" type="audio/mpeg">
                                        Tu navegador no soporta el audio.
                                    </audio>
                                    <div class="mt-2 flex justify-between text-xs text-slate-400">
                                        <span>Resumen</span>
                                        <span>
                                            <?= format_duration($book['audio_duration']) ?>
                                        </span>
                                    </div>
                                </div>

                                <a href="<?= sanitize($book['pdf_url']) ?>" target="_blank"
                                    class="flex w-full items-center justify-center rounded-lg bg-purple-600 px-3 py-2 text-sm font-medium text-white transition hover:bg-purple-700">
                                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                    Descargar PDF
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>