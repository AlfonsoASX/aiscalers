<?php
/**
 * Global Helper Functions
 */

/**
 * Sanitize string
 */
function sanitize($string)
{
    return htmlspecialchars(trim($string), ENT_QUOTES, 'UTF-8');
}

/**
 * Validate email
 */
function validate_email($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate password strength
 */
function validate_password($password)
{
    // Min 8 characters, at least one letter and one number
    return strlen($password) >= 8 && preg_match('/[A-Za-z]/', $password) && preg_match('/\d/', $password);
}

/**
 * Redirect helper
 */
function redirect($url)
{
    header("Location: $url");
    exit;
}

/**
 * JSON response
 */
function json_response($data, $status_code = 200)
{
    http_response_code($status_code);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

/**
 * Format time ago
 */
function time_ago($datetime)
{
    $timestamp = strtotime($datetime);
    $diff = time() - $timestamp;

    if ($diff < 60)
        return 'Hace un momento';
    if ($diff < 3600)
        return 'Hace ' . floor($diff / 60) . ' minutos';
    if ($diff < 86400)
        return 'Hace ' . floor($diff / 3600) . ' horas';
    if ($diff < 604800)
        return 'Hace ' . floor($diff / 86400) . ' días';

    return date('d/m/Y', $timestamp);
}

/**
 * Format duration (seconds to MM:SS)
 */
function format_duration($seconds)
{
    $minutes = floor($seconds / 60);
    $secs = $seconds % 60;
    return sprintf('%d:%02d', $minutes, $secs);
}

/**
 * Get difficulty badge color
 */
function get_difficulty_color($difficulty)
{
    $colors = [
        'beginner' => 'bg-green-500/10 text-green-400 border-green-500/20',
        'intermediate' => 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20',
        'advanced' => 'bg-red-500/10 text-red-400 border-red-500/20',
    ];

    return $colors[$difficulty] ?? $colors['beginner'];
}

/**
 * Upload file
 */
function upload_file($file, $destination_dir, $allowed_types)
{
    if (!isset($file['error']) || is_array($file['error'])) {
        return ['success' => false, 'message' => 'Parámetros inválidos'];
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'Error al subir archivo'];
    }

    if ($file['size'] > MAX_FILE_SIZE) {
        return ['success' => false, 'message' => 'Archivo demasiado grande'];
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mime_type, $allowed_types)) {
        return ['success' => false, 'message' => 'Tipo de archivo no permitido'];
    }

    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = bin2hex(random_bytes(16)) . '.' . $extension;
    $destination = $destination_dir . '/' . $filename;

    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        return ['success' => false, 'message' => 'Error al guardar archivo'];
    }

    return ['success' => true, 'filename' => $filename, 'path' => $destination];
}

/**
 * Delete file
 */
function delete_file($path)
{
    if (file_exists($path)) {
        return unlink($path);
    }
    return false;
}

/**
 * Get categories for blueprints
 */
function get_blueprint_categories()
{
    return ['Marketing', 'Ventas', 'Operaciones', 'Finanzas', 'Recursos Humanos', 'Atención al Cliente'];
}

/**
 * Get categories for prompts
 */
function get_prompt_categories()
{
    return ['Ventas', 'Marketing', 'Contenido', 'Análisis', 'Estrategia', 'Operaciones'];
}

/**
 * Get categories for books
 */
function get_book_categories()
{
    return ['Negocios', 'Liderazgo', 'Productividad', 'Marketing', 'Ventas', 'Innovación', 'Estrategia'];
}

/**
 * Paginate results
 */
function paginate($total, $per_page = 20, $current_page = 1)
{
    $total_pages = ceil($total / $per_page);
    $current_page = max(1, min($current_page, $total_pages));
    $offset = ($current_page - 1) * $per_page;

    return [
        'total' => $total,
        'per_page' => $per_page,
        'current_page' => $current_page,
        'total_pages' => $total_pages,
        'offset' => $offset,
        'has_prev' => $current_page > 1,
        'has_next' => $current_page < $total_pages,
    ];
}

/**
 * CSRF Token generation
 */
function generate_csrf_token()
{
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * CSRF Token validation
 */
function validate_csrf_token($token)
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Get asset URL
 */
function asset($path)
{
    return APP_URL . '/assets/' . ltrim($path, '/');
}

/**
 * Get upload URL
 */
function upload_url($path)
{
    return APP_URL . '/uploads/' . ltrim($path, '/');
}
