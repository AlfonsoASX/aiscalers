<?php
/**
 * AiScalers - Configuración Global
 * PHP 8.1+
 */

// ============================================================================
// ENVIRONMENT VARIABLES LOADER
// ============================================================================

if (file_exists(__DIR__ . '/../.env')) {
    $lines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0)
            continue;
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);
        if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
            putenv(sprintf('%s=%s', $name, $value));
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }
}

// ============================================================================
// ERROR REPORTING
// ============================================================================

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../logs/php_errors.log');

// ============================================================================
// TIMEZONE
// ============================================================================

date_default_timezone_set('America/Mexico_City');

// ============================================================================
// SESSION CONFIGURATION
// ============================================================================

ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 1); // Solo HTTPS
ini_set('session.cookie_samesite', 'Lax');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ============================================================================
// CONSTANTS
// ============================================================================

define('APP_NAME', 'AiScalers');
define('APP_URL', getenv('APP_URL') ?: 'http://localhost:8000');
define('APP_ENV', getenv('APP_ENV') ?: 'development');

// Paths
define('ROOT_PATH', dirname(__DIR__));
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('INCLUDES_PATH', ROOT_PATH . '/includes');
define('UPLOADS_PATH', ROOT_PATH . '/uploads');

// Database
define('DB_HOST', getenv('DB_HOST') ?: '198.23.62.146');
define('DB_PORT', getenv('DB_PORT') ?: '3306');
define('DB_NAME', getenv('DB_NAME') ?: 'alfonsosg_aiscalers');
define('DB_USER', getenv('DB_USER') ?: 'alfonsosg_admin');
define('DB_PASS', getenv('DB_PASS') ?: '5TKpjHN4Lhu.$Bee');
define('DB_CHARSET', 'utf8mb4');

// OpenAI
define('OPENAI_API_KEY', getenv('OPENAI_API_KEY') ?: '');

// Stripe
define('STRIPE_PUBLIC_KEY', getenv('STRIPE_PUBLIC_KEY') ?: '');
define('STRIPE_SECRET_KEY', getenv('STRIPE_SECRET_KEY') ?: '');

// Upload limits
define('MAX_FILE_SIZE', 50 * 1024 * 1024); // 50MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/webp']);
define('ALLOWED_AUDIO_TYPES', ['audio/mpeg', 'audio/mp3', 'audio/wav']);
define('ALLOWED_PDF_TYPES', ['application/pdf']);

// ============================================================================
// AUTOLOAD
// ============================================================================

require_once INCLUDES_PATH . '/db.php';
require_once INCLUDES_PATH . '/auth.php';
require_once INCLUDES_PATH . '/functions.php';

// ============================================================================
// SECURITY HEADERS
// ============================================================================

header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');

if (APP_ENV === 'production') {
    header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
}
