<?php
/**
 * Authentication System
 */

/**
 * Check if user is logged in
 */
function is_logged_in()
{
    return isset($_SESSION['user_id']) && isset($_SESSION['user_uid']);
}

/**
 * Check if user is admin
 */
function is_admin()
{
    return is_logged_in() && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

/**
 * Check if user has active subscription
 */
function has_active_subscription()
{
    return is_logged_in() && isset($_SESSION['subscription_status']) && $_SESSION['subscription_status'] === 'active';
}

/**
 * Require authentication (middleware)
 */
function require_auth()
{
    if (!is_logged_in()) {
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        header('Location: /auth/login.php');
        exit;
    }
}

/**
 * Require active subscription
 */
function require_subscription()
{
    require_auth();

    if (!has_active_subscription()) {
        header('Location: /pricing.php');
        exit;
    }
}

/**
 * Require admin role
 */
function require_admin()
{
    require_auth();

    if (!is_admin()) {
        http_response_code(403);
        die('Acceso denegado');
    }
}

/**
 * Login user
 */
function login_user($email, $password)
{
    $db = db();

    $stmt = $db->prepare('
        SELECT id, uid, email, password_hash, display_name, photo_url, role,
               subscription_status, subscription_plan
        FROM users
        WHERE email = ?
        LIMIT 1
    ');

    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user) {
        return ['success' => false, 'message' => 'Credenciales inválidas'];
    }

    if (!password_verify($password, $user['password_hash'])) {
        return ['success' => false, 'message' => 'Credenciales inválidas'];
    }

    // Create session
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_uid'] = $user['uid'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_name'] = $user['display_name'];
    $_SESSION['user_photo'] = $user['photo_url'];
    $_SESSION['user_role'] = $user['role'];
    $_SESSION['subscription_status'] = $user['subscription_status'];
    $_SESSION['subscription_plan'] = $user['subscription_plan'];

    // Update last login
    $stmt = $db->prepare('UPDATE users SET last_login = NOW() WHERE id = ?');
    $stmt->execute([$user['id']]);

    // Create session record
    create_session_record($user['id']);

    return ['success' => true, 'user' => $user];
}

/**
 * Register new user
 */
function register_user($email, $password, $display_name)
{
    $db = db();

    // Check if email exists
    $stmt = $db->prepare('SELECT id FROM users WHERE email = ?');
    $stmt->execute([$email]);

    if ($stmt->fetch()) {
        return ['success' => false, 'message' => 'El email ya está registrado'];
    }

    // Hash password
    $password_hash = password_hash($password, PASSWORD_BCRYPT);
    $uid = generate_uid();

    // Insert user
    $stmt = $db->prepare('
        INSERT INTO users (uid, email, password_hash, display_name, subscription_status)
        VALUES (?, ?, ?, ?, ?)
    ');

    try {
        $stmt->execute([$uid, $email, $password_hash, $display_name, 'trial']);
        return ['success' => true, 'message' => 'Usuario creado exitosamente'];
    } catch (PDOException $e) {
        error_log('Registration error: ' . $e->getMessage());
        return ['success' => false, 'message' => 'Error al crear usuario'];
    }
}

/**
 * Logout user
 */
function logout_user()
{
    // Delete session record
    if (isset($_SESSION['session_id'])) {
        $db = db();
        $stmt = $db->prepare('DELETE FROM sessions WHERE id = ?');
        $stmt->execute([$_SESSION['session_id']]);
    }

    // Destroy session
    $_SESSION = [];

    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/');
    }

    session_destroy();
}

/**
 * Get authenticated user
 */
function get_authenticated_user()
{
    if (!is_logged_in()) {
        return null;
    }

    return [
        'id' => $_SESSION['user_id'],
        'uid' => $_SESSION['user_uid'],
        'email' => $_SESSION['user_email'],
        'name' => $_SESSION['user_name'],
        'photo' => $_SESSION['user_photo'],
        'role' => $_SESSION['user_role'],
        'subscription_status' => $_SESSION['subscription_status'],
        'subscription_plan' => $_SESSION['subscription_plan'],
    ];
}

/**
 * Create session record in database
 */
function create_session_record($user_id)
{
    $db = db();
    $session_id = session_id();
    $ip_address = $_SERVER['REMOTE_ADDR'] ?? '';
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $expires_at = date('Y-m-d H:i:s', time() + (30 * 24 * 60 * 60)); // 30 days

    $stmt = $db->prepare('
        INSERT INTO sessions (id, user_id, ip_address, user_agent, expires_at)
        VALUES (?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE
        ip_address = VALUES(ip_address),
        user_agent = VALUES(user_agent),
        expires_at = VALUES(expires_at)
    ');

    $stmt->execute([$session_id, $user_id, $ip_address, $user_agent, $expires_at]);
    $_SESSION['session_id'] = $session_id;
}

/**
 * Generate unique user ID
 */
function generate_uid()
{
    return bin2hex(random_bytes(16));
}
