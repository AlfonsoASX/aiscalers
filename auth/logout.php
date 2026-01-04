<?php
/**
 * Logout Logic
 */

require_once __DIR__ . '/../includes/config.php';

logout_user();
redirect('/');
