<?php
/**
 * Register Page
 */

$page_title = 'Registro';
$require_auth = false;

require_once __DIR__ . '/../includes/header.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($name && $email && $password) {
        if (!validate_email($email)) {
            $error = 'Email inválido';
        } elseif (!validate_password($password)) {
            $error = 'La contraseña debe tener al menos 8 caracteres, una letra y un número';
        } else {
            $result = register_user($email, $password, $name);

            if ($result['success']) {
                $login = login_user($email, $password);
                redirect('/');
            } else {
                $error = $result['message'];
            }
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
            <h2 class="mt-6 text-3xl font-bold tracking-tight text-white">Crea tu cuenta gratis</h2>
            <p class="mt-2 text-sm text-slate-400">
                ¿Ya tienes una cuenta?
                <a href="/auth/login.php" class="font-medium text-indigo-500 hover:text-indigo-400">Inicia sesión</a>
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
                    <label for="name" class="sr-only">Nombre completo</label>
                    <input id="name" name="name" type="text" autocomplete="name" required
                        class="relative block w-full rounded-t-md border-0 bg-slate-900 py-3 text-white ring-1 ring-inset ring-slate-700 placeholder:text-slate-500 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                        placeholder="Nombre completo">
                </div>
                <div>
                    <label for="email" class="sr-only">Correo electrónico</label>
                    <input id="email" name="email" type="email" autocomplete="email" required
                        class="relative block w-full border-0 bg-slate-900 py-3 text-white ring-1 ring-inset ring-slate-700 placeholder:text-slate-500 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                        placeholder="Correo electrónico">
                </div>
                <div>
                    <label for="password" class="sr-only">Contraseña</label>
                    <input id="password" name="password" type="password" autocomplete="new-password" required
                        class="relative block w-full rounded-b-md border-0 bg-slate-900 py-3 text-white ring-1 ring-inset ring-slate-700 placeholder:text-slate-500 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                        placeholder="Contraseña">
                </div>
            </div>

            <div>
                <button type="submit"
                    class="group relative flex w-full justify-center rounded-md bg-indigo-600 px-3 py-3 text-sm font-semibold text-white hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition shadow-lg shadow-indigo-500/20">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-indigo-300 group-hover:text-indigo-200" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z">
                            </path>
                        </svg>
                    </span>
                    Crear Cuenta
                </button>
            </div>

            <p class="text-center text-xs text-slate-500">
                Al registrarte, aceptas nuestros términos y condiciones y política de privacidad.
            </p>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>