<?php
/**
 * Header Component
 * Incluir en todas las páginas
 */

if (!defined('APP_NAME')) {
    require_once __DIR__ . '/config.php';
}

$page_title = $page_title ?? 'Dashboard';
$require_auth = $require_auth ?? true;
$require_subscription = $require_subscription ?? false;

if ($require_auth) {
    require_auth();
}

if ($require_subscription) {
    require_subscription();
}

$current_user = get_authenticated_user();
?>
<!DOCTYPE html>
<html lang="es" class="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= APP_NAME ?> - Club privado para empresarios que sistematizan con IA">
    <title><?= sanitize($page_title) ?> - <?= APP_NAME ?></title>

        <!-- Tailwind CSS CDN (para desarrollo rápido) -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                darkMode: 'class',
                theme: {
                    extend: {
                        colors: {
                            slate: {
                                950: '#0f172a',
                            }
                        }
                    }
                }
            }
        </script>

        <!-- Custom Styles -->
        <style>
            body {
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            }
        </style>
</head>

<body class="bg-slate-950 text-white antialiased">