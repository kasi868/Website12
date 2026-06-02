<?php
require_once __DIR__ . '/functions.php';

if (!defined('BASE_PATH')) {
    define('BASE_PATH', cms_detect_base_path());
}

if (!defined('BASE_URL')) {
    $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https')
        || (isset($_SERVER['SERVER_PORT']) && (int) $_SERVER['SERVER_PORT'] === 443);
    $scheme = $isHttps ? 'https' : 'http';
    $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
    define('BASE_URL', $scheme . '://' . $host . BASE_PATH . '/');
}

require_once __DIR__ . '/db.php';
