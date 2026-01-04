<?php
/**
 * User Profile Page
 */

$page_title = 'Mi Perfil';
require_once __DIR__ . '/../../includes/header.php';

$user = get_authenticated_user();

// Update Profile Logic
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $display_name = $_POST['display_name'] ?? '';

    if ($display_name) {
        $db = db();
        try {
            $stmt = $db->prepare('UPDATE users SET display_name = ? WHERE id = ?');
            $stmt->execute([$display_name, $user['id']]);

            // Update session
            $_SESSION['user_name'] = $display_name;
            $user['name'] = $display_name;

            $message = 'Perfil actualizado correctamente';
        } catch (PDOException $e) {
            $error = 'Error al actualizar perfil';
        }
    }
}
?>

<div class="flex h-screen bg-slate-950">
    <?php require_once __DIR__ . '/../../includes/components/sidebar.php'; ?>

    <main class="flex-1 overflow-y-auto bg-slate-950">
        <!-- Header -->
        <div class="border-b border-slate-800 bg-slate-950/50 backdrop-blur sticky top-0 z-10">
            <div class="px-8 py-6">
                <h1 class="text-3xl font-bold text-white">Mi Perfil</h1>
                <p class="text-slate-400">Gestiona tu cuenta y suscripción</p>
            </div>
        </div>

        <div class="px-8 py-8 max-w-2xl">
            <?php if ($message): ?>
                <div class="mb-6 rounded-md bg-green-500/10 p-4 text-sm text-green-500 border border-green-500/20">
                    <?= sanitize($message) ?>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="mb-6 rounded-md bg-red-500/10 p-4 text-sm text-red-500 border border-red-500/20">
                    <?= sanitize($error) ?>
                </div>
            <?php endif; ?>

            <!-- Profile Info -->
            <div class="mb-8 rounded-xl border border-slate-800 bg-slate-900/50 p-6">
                <h2 class="mb-6 text-lg font-semibold text-white">Información Personal</h2>

                <form action="" method="POST" class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-400 mb-2">Avatar</label>
                        <div class="flex items-center space-x-4">
                            <div
                                class="h-16 w-16 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-2xl font-bold text-white">
                                <?php if ($user['photo']): ?>
                                    <img src="<?= sanitize($user['photo']) ?>" alt=""
                                        class="h-full w-full rounded-full object-cover">
                                <?php else: ?>
                                    <?= substr(sanitize($user['name']), 0, 1) ?>
                                <?php endif; ?>
                            </div>
                            <button type="button"
                                class="text-sm font-medium text-indigo-400 hover:text-indigo-300">Cambiar foto</button>
                        </div>
                    </div>

                    <div>
                        <label for="display_name" class="block text-sm font-medium text-slate-400 mb-2">Nombre
                            Completo</label>
                        <input type="text" id="display_name" name="display_name" value="<?= sanitize($user['name']) ?>"
                            class="w-full rounded-lg border border-slate-800 bg-slate-950 py-2.5 px-4 text-white focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-400 mb-2">Correo Electrónico</label>
                        <input type="email" value="<?= sanitize($user['email']) ?>" disabled
                            class="w-full rounded-lg border border-slate-800 bg-slate-950/50 py-2.5 px-4 text-slate-500 cursor-not-allowed">
                        <p class="mt-1 text-xs text-slate-500">El correo electrónico no se puede cambiar.</p>
                    </div>

                    <div class="pt-4">
                        <button type="submit"
                            class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-indigo-700">
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>

            <!-- Subscription -->
            <div class="rounded-xl border border-slate-800 bg-slate-900/50 p-6">
                <h2 class="mb-6 text-lg font-semibold text-white">Suscripción</h2>

                <div class="flex items-center justify-between rounded-lg border border-slate-700 bg-slate-800/50 p-4">
                    <div>
                        <p class="font-medium text-white">Plan Actual</p>
                        <p class="text-sm text-slate-400">
                            <?= ucfirst($user['subscription_plan'] ?? 'Gratuito') ?>
                            (
                            <?= $user['subscription_status'] === 'active' ? 'Activo' : 'Inactivo/Trial' ?>)
                        </p>
                    </div>
                    <?php if ($user['subscription_status'] !== 'active'): ?>
                        <a href="#"
                            class="rounded-lg bg-gradient-to-r from-indigo-500 to-purple-600 px-4 py-2 text-sm font-bold text-white transition hover:opacity-90">
                            Mejorar Plan
                        </a>
                    <?php else: ?>
                        <button class="text-sm font-medium text-slate-400 hover:text-white">Gestionar Suscripción</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>