<?php
// Define base URL for assets
$protocol = (!empty($_SERVER['HTTPS']) ? 'https' : 'http');
$host = $_SERVER['HTTP_HOST'];
$scriptName = $_SERVER['SCRIPT_NAME'];
$path = dirname($scriptName);
$baseUrl = $protocol . '://' . $host . rtrim($path, '/') . '/';
define('BASE_URL', $baseUrl);

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';

