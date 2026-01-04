<?php
/**
 * Login Page
 */

$page_title = 'Iniciar Sesión';
$require_auth = false;

require_once __DIR__ . '/../includes/header.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($email && $password) {
        $result = login_user($email, $password);

        if ($result['success']) {
            $redirect = $_SESSION['redirect_after_login'] ?? '/';
            unset($_SESSION['redirect_after_login']);
            redirect($redirect);
        } else {
            $error = $result['message'];
        }
    } else {
        $error = 'Por favor completa todos los campos';
    }
}
?>

<div class="flex min-h-screen items-center justify-center bg-slate-950 px-4 py-12 sm:px-6 lg:px-8">
    <div class="w-full max-w-md space-y-8">
        <div class="text-center">
            <a href="/"
                class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600">
                <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </a>
            <h2 class="mt-6 text-3xl font-bold tracking-tight text-white">Bienvenido de vuelta</h2>
            <p class="mt-2 text-sm text-slate-400">
                ¿No tienes una cuenta?
                <a href="/auth/register.php" class="font-medium text-indigo-500 hover:text-indigo-400">Regístrate
                    gratis</a>
            </p>
        </div>

        <form class="mt-8 space-y-6" action="" method="POST">
            <?php if ($error): ?>
                <div class="rounded-md bg-red-500/10 p-4 text-sm text-red-500 border border-red-500/20">
                    <?= sanitize($error) ?>
                </div>
            <?php endif; ?>

            <div class="-space-y-px rounded-md shadow-sm">
                <div>
                    <label for="email" class="sr-only">Correo electrónico</label>
                    <input id="email" name="email" type="email" autocomplete="email" required
                        class="relative block w-full rounded-t-md border-0 bg-slate-900 py-3 text-white ring-1 ring-inset ring-slate-700 placeholder:text-slate-500 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                        placeholder="Correo electrónico">
                </div>
                <div>
                    <label for="password" class="sr-only">Contraseña</label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required
                        class="relative block w-full rounded-b-md border-0 bg-slate-900 py-3 text-white ring-1 ring-inset ring-slate-700 placeholder:text-slate-500 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                        placeholder="Contraseña">
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember-me" name="remember-me" type="checkbox"
                        class="h-4 w-4 rounded border-slate-700 bg-slate-900 text-indigo-600 focus:ring-indigo-600">
                    <label for="remember-me" class="ml-2 block text-sm text-slate-400">Recordarme</label>
                </div>

                <div class="text-sm">
                    <a href="#" class="font-medium text-indigo-500 hover:text-indigo-400">¿Olvidaste tu contraseña?</a>
                </div>
            </div>

            <div>
                <button type="submit"
                    class="group relative flex w-full justify-center rounded-md bg-indigo-600 px-3 py-3 text-sm font-semibold text-white hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition shadow-lg shadow-indigo-500/20">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-indigo-300 group-hover:text-indigo-200" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                            </path>
                        </svg>
                    </span>
                    Ingresar
                </button>
            </div>
        </form>


        <!-- Credenciales Demo (Borrar en producción) -->
        <div class="mt-8 border-t border-slate-800 pt-6">
            <h3 class="text-xs font-semibold uppercase tracking-wider text-slate-500">Credenciales Demo</h3>
            <div class="mt-4 rounded-md bg-slate-900 p-4">
                <div class="flex justify-between items-center text-sm">
                    <div>
                        <p class="text-slate-300"><span class="text-slate-500">Email:</span> admin@aiscalers.co</p>
                        <p class="text-slate-300"><span class="text-slate-500">Pass:</span> Admin123!</p>
                    </div>
                    <button
                        onclick="document.getElementById('email').value='admin@aiscalers.co';document.getElementById('password').value='Admin123!';"
                        class="text-indigo-400 hover:text-indigo-300 text-xs">Ups</button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>