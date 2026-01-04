<?php
/**
 * Prompt-Teca Page
 */

$page_title = 'Prompt-Teca';
require_once __DIR__ . '/../../includes/header.php';

// Get categories
$categories = get_prompt_categories();
$selected_category = $_GET['category'] ?? '';
$search = $_GET['search'] ?? '';

// Build query
$db = db();
$sql = "SELECT * FROM prompts WHERE 1=1";
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

$sql .= " ORDER BY featured DESC, uses DESC";

$stmt = $db->prepare($sql);
$stmt->execute($params);
$prompts = $stmt->fetchAll();
?>

<div class="flex h-screen bg-slate-950">
    <?php require_once __DIR__ . '/../../includes/components/sidebar.php'; ?>

    <main class="flex-1 overflow-y-auto bg-slate-950">
        <!-- Header -->
        <div class="border-b border-slate-800 bg-slate-950/50 backdrop-blur sticky top-0 z-10">
            <div class="px-8 py-6">
                <div class="mb-6 flex items-center space-x-3">
                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-pink-500 to-orange-600">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-white">Prompt-Teca</h1>
                        <p class="text-slate-400">Biblioteca de prompts probados para IA</p>
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
                            placeholder="Buscar prompts..."
                            class="w-full rounded-lg border border-slate-800 bg-slate-900/50 py-2 pl-10 pr-4 text-white placeholder-slate-400 focus:border-pink-500 focus:outline-none focus:ring-1 focus:ring-pink-500">
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <a href="?"
                            class="rounded-full border <?= !$selected_category ? 'border-pink-500 bg-pink-500/10 text-white' : 'border-slate-700 text-slate-300 hover:border-pink-500 hover:text-white' ?> px-4 py-1.5 text-sm font-medium transition">
                            Todos
                        </a>
                        <?php foreach ($categories as $cat): ?>
                            <a href="?category=<?= urlencode($cat) ?>"
                                class="rounded-full border <?= $selected_category === $cat ? 'border-pink-500 bg-pink-500/10 text-white' : 'border-slate-700 text-slate-300 hover:border-pink-500 hover:text-white' ?> px-4 py-1.5 text-sm font-medium transition">
                                <?= $cat ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </form>
            </div>
        </div>

        <!-- Content -->
        <div class="px-8 py-8">
            <?php if (empty($prompts)): ?>
                <div class="flex h-64 flex-col items-center justify-center text-center">
                    <svg class="mb-4 h-12 w-12 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-slate-300">No se encontraron prompts</h3>
                </div>
            <?php else: ?>
                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    <?php foreach ($prompts as $p): ?>
                        <div
                            class="group overflow-hidden rounded-xl border border-slate-800 bg-slate-900/50 backdrop-blur transition hover:border-pink-500/50 hover:shadow-lg hover:shadow-pink-500/10">
                            <div class="p-5">
                                <div class="mb-4 flex items-start justify-between">
                                    <span
                                        class="inline-flex items-center rounded-full border border-pink-500/30 bg-pink-500/10 px-2 py-0.5 text-xs font-medium text-pink-400">
                                        <?= sanitize($p['category']) ?>
                                    </span>
                                    <div class="flex items-center text-xs text-slate-400">
                                        <svg class="mr-1 h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                        </svg>
                                        <?= $p['uses'] ?> usos
                                    </div>
                                </div>

                                <h3 class="mb-2 text-lg font-bold text-white group-hover:text-pink-400 transition-colors">
                                    <?= sanitize($p['title']) ?>
                                </h3>

                                <p class="mb-4 text-sm text-slate-400">
                                    <?= sanitize($p['description']) ?>
                                </p>

                                <div class="mb-4 rounded-lg bg-slate-800/50 p-3">
                                    <p class="text-sm font-mono text-slate-300 line-clamp-4">
                                        <?= sanitize($p['template']) ?>
                                    </p>
                                </div>

                                <button
                                    onclick="copyToClipboard(<?= htmlspecialchars(json_encode($p['template'])) ?>, 'btn-prompt-<?= $p['id'] ?>')"
                                    id="btn-prompt-<?= $p['id'] ?>"
                                    class="w-full inline-flex items-center justify-center rounded-lg bg-pink-600 px-3 py-2 text-sm font-medium text-white transition hover:bg-pink-700">
                                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3">
                                        </path>
                                    </svg>
                                    Copiar Prompt
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>