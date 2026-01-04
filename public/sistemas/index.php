<?php
/**
 * Blueprints Page
 */

$page_title = 'Sistemas';
require_once __DIR__ . '/../../includes/header.php';

// Get categories
$categories = get_blueprint_categories();
$selected_category = $_GET['category'] ?? '';
$search = $_GET['search'] ?? '';

// Build query
$db = db();
$sql = "SELECT * FROM blueprints WHERE 1=1";
$params = [];

if ($selected_category) {
    $sql .= " AND category = ?";
    $params[] = $selected_category;
}

if ($search) {
    $sql .= " AND (title LIKE ? OR description LIKE ? OR MATCH(title, description) AGAINST(?))";
    $term = "%$search%";
    $params[] = $term;
    $params[] = $term;
    $params[] = $search;
}

$sql .= " ORDER BY featured DESC, created_at DESC";

$stmt = $db->prepare($sql);
$stmt->execute($params);
$blueprints = $stmt->fetchAll();
?>

<div class="flex h-screen bg-slate-950">
    <?php require_once __DIR__ . '/../../includes/components/sidebar.php'; ?>

    <main class="flex-1 overflow-y-auto bg-slate-950">
        <!-- Header -->
        <div class="border-b border-slate-800 bg-slate-950/50 backdrop-blur sticky top-0 z-10">
            <div class="px-8 py-6">
                <div class="mb-6 flex items-center space-x-3">
                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-white">Sistemas de Automatización</h1>
                        <p class="text-slate-400">Blueprints listos para copiar y usar en n8n o Make</p>
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
                            placeholder="Buscar sistemas por nombre, descripción o tags..."
                            class="w-full rounded-lg border border-slate-800 bg-slate-900/50 py-2 pl-10 pr-4 text-white placeholder-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <a href="?"
                            class="rounded-full border <?= !$selected_category ? 'border-indigo-500 bg-indigo-500/10 text-white' : 'border-slate-700 text-slate-300 hover:border-indigo-500 hover:text-white' ?> px-4 py-1.5 text-sm font-medium transition">
                            Todos
                        </a>
                        <?php foreach ($categories as $cat): ?>
                            <a href="?category=<?= urlencode($cat) ?>"
                                class="rounded-full border <?= $selected_category === $cat ? 'border-indigo-500 bg-indigo-500/10 text-white' : 'border-slate-700 text-slate-300 hover:border-indigo-500 hover:text-white' ?> px-4 py-1.5 text-sm font-medium transition">
                                <?= $cat ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </form>
            </div>
        </div>

        <!-- Content -->
        <div class="px-8 py-8">
            <?php if (empty($blueprints)): ?>
                <div class="flex h-64 flex-col items-center justify-center text-center">
                    <svg class="mb-4 h-12 w-12 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                        </path>
                    </svg>
                    <h3 class="text-lg font-medium text-slate-300">No se encontraron sistemas</h3>
                    <p class="text-slate-500">Intenta con otra búsqueda o categoría</p>
                </div>
            <?php else: ?>
                <div class="mb-4 text-sm text-slate-400">
                    Mostrando
                    <?= count($blueprints) ?> sistemas
                </div>

                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    <?php foreach ($blueprints as $bp): ?>
                        <div
                            class="group overflow-hidden rounded-xl border border-slate-800 bg-slate-900/50 backdrop-blur transition hover:border-indigo-500/50 hover:shadow-lg hover:shadow-indigo-500/10">
                            <div class="p-5">
                                <div class="mb-4 flex items-start justify-between">
                                    <span
                                        class="rounded bg-indigo-500/10 px-2.5 py-0.5 text-xs font-medium text-indigo-400 border border-indigo-500/20">
                                        <?= strtoupper($bp['platform']) ?>
                                    </span>
                                    <span
                                        class="rounded px-2.5 py-0.5 text-xs font-medium border <?= get_difficulty_color($bp['difficulty']) ?>">
                                        <?= ucfirst($bp['difficulty']) ?>
                                    </span>
                                </div>

                                <h3 class="mb-2 text-xl font-bold text-white group-hover:text-indigo-400 transition-colors">
                                    <?= sanitize($bp['title']) ?>
                                </h3>

                                <p class="mb-4 text-sm text-slate-400 line-clamp-2">
                                    <?= sanitize($bp['description']) ?>
                                </p>

                                <!-- Video Embed -->
                                <div class="mb-4 aspect-video overflow-hidden rounded-lg bg-slate-800 relative group/video">
                                    <?php
                                    $video_url = $bp['video_url'];
                                    if (preg_match('/loom\.com\/share\/([a-zA-Z0-9]+)/', $video_url, $matches)) {
                                        $embed_url = "https://www.loom.com/embed/" . $matches[1];
                                        echo '<iframe src="' . $embed_url . '" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen class="h-full w-full"></iframe>';
                                    } else {
                                        echo '<div class="flex h-full items-center justify-center text-slate-500">Video no disponible</div>';
                                    }
                                    ?>
                                </div>

                                <div class="flex items-center justify-between text-xs text-slate-500 mb-4">
                                    <div class="flex items-center">
                                        <svg class="mr-1 h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <?= $bp['estimated_time'] ?> min
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="mr-1 h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                        </svg>
                                        <?= $bp['copies'] ?> copias
                                    </div>
                                </div>

                                <div class="flex gap-2">
                                    <button
                                        onclick="copyToClipboard(<?= htmlspecialchars(json_encode($bp['json_code'])) ?>, 'btn-copy-<?= $bp['id'] ?>')"
                                        id="btn-copy-<?= $bp['id'] ?>"
                                        class="flex-1 inline-flex items-center justify-center rounded-lg bg-indigo-600 px-3 py-2 text-sm font-medium text-white transition hover:bg-indigo-700">
                                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3">
                                            </path>
                                        </svg>
                                        Copiar Sistema
                                    </button>
                                    <a href="<?= sanitize($bp['video_url']) ?>" target="_blank"
                                        class="inline-flex items-center justify-center rounded-lg border border-slate-700 px-3 py-2 text-slate-300 transition hover:bg-indigo-500/10 hover:border-indigo-500 hover:text-white">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                            </path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>